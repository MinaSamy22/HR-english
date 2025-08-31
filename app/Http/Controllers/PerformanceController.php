<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\PerformanceEvaluation;
use App\Models\PerformanceCriteria;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerformanceController extends Controller
{
public function index(Request $request)
{
    // Get current HR user's company and branch
    $companyId = Auth::user()->company_id;
    $branchId = session('branch_id');

    // Determine filtering logic based on branch_id and is_main
    $showAllCompanyData = false;
    $filterBranchId = null;

    if ($branchId) {
        $currentBranch = Branch::find($branchId);
        if ($currentBranch && $currentBranch->is_main == 1) {
            // Main branch - show all company evaluations
            $showAllCompanyData = true;
        } else {
            // Regular branch - show only this branch's evaluations
            $filterBranchId = $branchId;
        }
    } else {
        // No branch_id in session - show all company evaluations
        $showAllCompanyData = true;
    }

    // Create a closure for the employee filtering logic
    $employeeFilterClosure = function($query) use ($showAllCompanyData, $companyId, $filterBranchId) {
        if ($showAllCompanyData) {
            $query->where('company_id', $companyId);
        } else {
            $query->where('branch_id', $filterBranchId);
        }
    };

    $query = PerformanceEvaluation::with(['employee', 'evaluator'])
        ->forCompany($companyId)
        ->whereHas('employee', $employeeFilterClosure);

    // Search by employee name
    if ($request->filled('employee_name')) {
        $query->whereHas('employee', function($q) use ($request, $showAllCompanyData, $companyId, $filterBranchId) {
            $q->where('name', 'LIKE', '%' . $request->employee_name . '%');

            // Apply branch filtering to the name search as well
            if ($showAllCompanyData) {
                $q->where('company_id', $companyId);
            } else {
                $q->where('branch_id', $filterBranchId);
            }
        });
    }

    // Filter by month and year
    if ($request->filled('month') && $request->filled('year')) {
        $query->where('evaluation_year', $request->year)
              ->where('evaluation_period', 'LIKE', '%' . $request->month . '%');
    } elseif ($request->filled('year')) {
        $query->where('evaluation_year', $request->year);
    }

    // Filter by status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $evaluations = $query->orderBy('created_at', 'desc')->paginate(15);

    // Keep search parameters in pagination
    $evaluations->appends($request->query());

    // Get employees for dropdown with branch filtering
    $employees = User::when($showAllCompanyData,
        fn($q) => $q->where('company_id', $companyId),
        fn($q) => $q->where('branch_id', $filterBranchId)
    )
    ->where('is_role', 0)
    ->select('id', 'name')
    ->orderBy('name')
    ->get();

    return view('backend.performances.index', compact('evaluations', 'employees'));
}
    public function create()
    {
        // Get employees from the same company
        $companyId = Auth::user()->company_id;

        $employees = User::where('company_id', $companyId)
            ->where('is_role', 0) // Only employees
            ->where('id', '!=', Auth::id()) // Exclude current HR user
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        // Get custom criteria for the company
        $customCriteria = PerformanceCriteria::forCompany($companyId)
            ->active()
            ->ordered()
            ->get();

        return view('backend.performances.create', compact('employees', 'customCriteria'));
    }

    public function store(Request $request)
    {
        $companyId = Auth::user()->company_id;

        // Get custom criteria for validation
        $customCriteria = PerformanceCriteria::forCompany($companyId)->active()->get();
        $hasCustomCriteria = $customCriteria->count() > 0;

        // Base validation rules
        $validationRules = [
            'employee_id' => 'required|exists:users,id',
            'evaluation_period' => 'required|string|max:255',
            'evaluation_year' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'strengths' => 'nullable|string',
            'areas_for_improvement' => 'nullable|string',
            'goals_for_next_period' => 'nullable|string',
            'hr_comments' => 'nullable|string',
            'status' => 'required|in:draft,completed,reviewed'
        ];

        // Add custom criteria validation if they exist
        if ($hasCustomCriteria) {
            foreach ($customCriteria as $criteria) {
                $validationRules['criteria_' . $criteria->id] = 'required|integer|min:1|max:5';
            }
        } else {
            // Add standard criteria validation if no custom criteria
            $validationRules = array_merge($validationRules, [
                'quality_of_work' => 'required|integer|min:1|max:5',
                'productivity' => 'required|integer|min:1|max:5',
                'communication' => 'required|integer|min:1|max:5',
                'teamwork' => 'required|integer|min:1|max:5',
                'punctuality' => 'required|integer|min:1|max:5',
                'initiative' => 'required|integer|min:1|max:5',
            ]);
        }

        $validated = $request->validate($validationRules);

        // Check for duplicate evaluation
        $existingEvaluation = PerformanceEvaluation::where([
            'company_id' => $companyId,
            'employee_id' => $request->employee_id,
            'evaluation_period' => $request->evaluation_period,
            'evaluation_year' => $request->evaluation_year,
        ])->first();

        if ($existingEvaluation) {
            return back()->withErrors([
             'employee_id' => __('h_performance.employee_evaluation_exists')
            ])->withInput();
        }

        // Create the evaluation
        $evaluationData = [
            'employee_id' => $request->employee_id,
            'company_id' => $companyId,
            'evaluator_id' => Auth::id(),
            'evaluation_period' => $request->evaluation_period,
            'evaluation_year' => $request->evaluation_year,
            'strengths' => $request->strengths,
            'areas_for_improvement' => $request->areas_for_improvement,
            'goals_for_next_period' => $request->goals_for_next_period,
            'hr_comments' => $request->hr_comments,
            'status' => $request->status,
        ];

        if ($hasCustomCriteria) {
            // Handle custom criteria
            $evaluationData['uses_custom_criteria'] = true;
            $criteriaScores = [];
            $totalScore = 0;
            $criteriaCount = 0;

            foreach ($customCriteria as $criteria) {
                $score = $request->input('criteria_' . $criteria->id);
                $criteriaScores[$criteria->id] = [
                    'score' => $score,
                    'name' => $criteria->name
                ];
                $totalScore += $score;
                $criteriaCount++;
            }

            $evaluationData['criteria_scores'] = $criteriaScores;
            $evaluationData['overall_score'] = $criteriaCount > 0 ? round($totalScore / $criteriaCount, 2) : 0;
        } else {
            // Handle standard criteria
            $evaluationData['uses_custom_criteria'] = false;
            $evaluationData['quality_of_work'] = $request->quality_of_work;
            $evaluationData['productivity'] = $request->productivity;
            $evaluationData['communication'] = $request->communication;
            $evaluationData['teamwork'] = $request->teamwork;
            $evaluationData['punctuality'] = $request->punctuality;
            $evaluationData['initiative'] = $request->initiative;

            // Calculate overall score for standard criteria (simple average)
            $scores = [
                $request->quality_of_work,
                $request->productivity,
                $request->communication,
                $request->teamwork,
                $request->punctuality,
                $request->initiative
            ];
            $evaluationData['overall_score'] = round(array_sum($scores) / count($scores), 2);
        }

        $evaluation = PerformanceEvaluation::create($evaluationData);

return redirect()->route('performance.index')
    ->with('success', __('h_performance.evaluation_created_success'));
    }

    public function show($id)
    {
        $evaluation = PerformanceEvaluation::with(['employee', 'evaluator'])
            ->forCompany(Auth::user()->company_id)
            ->findOrFail($id);

        return view('backend.performances.show', compact('evaluation'));
    }

    public function edit($id)
    {
        $companyId = Auth::user()->company_id;

        $evaluation = PerformanceEvaluation::forCompany($companyId)
            ->findOrFail($id);

        $employees = User::where('company_id', $companyId)
            ->where('is_role', 0)
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        // Get custom criteria for the company
        $customCriteria = PerformanceCriteria::forCompany($companyId)
            ->active()
            ->ordered()
            ->get();

        return view('backend.performances.edit', compact('evaluation', 'employees', 'customCriteria'));
    }

    public function update(Request $request, $id)
    {
        $companyId = Auth::user()->company_id;

        $evaluation = PerformanceEvaluation::forCompany($companyId)
            ->findOrFail($id);

        // Get custom criteria for validation
        $customCriteria = PerformanceCriteria::forCompany($companyId)->active()->get();
        $hasCustomCriteria = $customCriteria->count() > 0;

        // Base validation rules
        $validationRules = [
            'employee_id' => 'required|exists:users,id',
            'evaluation_period' => 'required|string|max:255',
            'evaluation_year' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'strengths' => 'nullable|string',
            'areas_for_improvement' => 'nullable|string',
            'goals_for_next_period' => 'nullable|string',
            'hr_comments' => 'nullable|string',
            'status' => 'required|in:draft,completed,reviewed'
        ];

        // Add validation based on current evaluation type
        if ($evaluation->uses_custom_criteria && $hasCustomCriteria) {
            foreach ($customCriteria as $criteria) {
                $validationRules['criteria_' . $criteria->id] = 'required|integer|min:1|max:5';
            }
        } else {
            $validationRules = array_merge($validationRules, [
                'quality_of_work' => 'required|integer|min:1|max:5',
                'productivity' => 'required|integer|min:1|max:5',
                'communication' => 'required|integer|min:1|max:5',
                'teamwork' => 'required|integer|min:1|max:5',
                'punctuality' => 'required|integer|min:1|max:5',
                'initiative' => 'required|integer|min:1|max:5',
            ]);
        }

        $validated = $request->validate($validationRules);

        // Update basic fields
        $evaluation->update([
            'employee_id' => $request->employee_id,
            'evaluation_period' => $request->evaluation_period,
            'evaluation_year' => $request->evaluation_year,
            'strengths' => $request->strengths,
            'areas_for_improvement' => $request->areas_for_improvement,
            'goals_for_next_period' => $request->goals_for_next_period,
            'hr_comments' => $request->hr_comments,
            'status' => $request->status,
        ]);

        if ($evaluation->uses_custom_criteria && $hasCustomCriteria) {
            // Handle custom criteria update
            $criteriaScores = [];
            $totalScore = 0;
            $criteriaCount = 0;

            foreach ($customCriteria as $criteria) {
                $score = $request->input('criteria_' . $criteria->id);
                $criteriaScores[$criteria->id] = [
                    'score' => $score,
                    'name' => $criteria->name
                ];
                $totalScore += $score;
                $criteriaCount++;
            }

            $evaluation->update([
                'criteria_scores' => $criteriaScores,
                'overall_score' => $criteriaCount > 0 ? round($totalScore / $criteriaCount, 2) : 0,
            ]);
        } else {
            // Handle standard criteria update
            $scores = [
                $request->quality_of_work,
                $request->productivity,
                $request->communication,
                $request->teamwork,
                $request->punctuality,
                $request->initiative
            ];

            $evaluation->update([
                'quality_of_work' => $request->quality_of_work,
                'productivity' => $request->productivity,
                'communication' => $request->communication,
                'teamwork' => $request->teamwork,
                'punctuality' => $request->punctuality,
                'initiative' => $request->initiative,
                'overall_score' => round(array_sum($scores) / count($scores), 2),
            ]);
        }

return redirect()->route('performance.index')
    ->with('success', __('h_performance.evaluation_updated_success'));
    }

    public function destroy($id)
    {
        $evaluation = PerformanceEvaluation::forCompany(Auth::user()->company_id)
            ->findOrFail($id);

        $evaluation->delete();

return redirect()->route('performance.index')
    ->with('success', __('h_performance.evaluation_deleted_success'));
    }

    public function employeeReport($employeeId)
    {
        $employee = User::where('company_id', Auth::user()->company_id)
            ->where('is_role', 0)
            ->findOrFail($employeeId);

        $evaluations = PerformanceEvaluation::with(['evaluator'])
            ->forCompany(Auth::user()->company_id)
            ->forEmployee($employeeId)
            ->orderBy('evaluation_year', 'desc')
            ->orderBy('evaluation_period', 'desc')
            ->get();

        return view('backend.performances.employee-report', compact('employee', 'evaluations'));
    }
}
