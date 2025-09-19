<?php
namespace App\Http\Controllers\EmployeesInterface;

use App\Http\Controllers\Controller;
use App\Models\PerformanceCriteria;
use App\Models\PerformanceEvaluation;

use Illuminate\Support\Facades\DB;

class EmployeePerformanceController extends Controller
{

public function index()
{
    // Check if employee is logged in using your custom session
    $employeeId = session('employee_id');
    $companyId = session('company_id');

    if (!$employeeId) {
        return redirect()->route('employee.login')->with('error', 'Please log in to continue.');
    }

    // Get performance criteria for the company
    $performanceCriteria = DB::table('performance_criterias')
        ->where('company_id', $companyId)
        ->where('is_active', 1)
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get();

    // Get ONLY the logged-in employee's evaluations using the model
    $evaluations = PerformanceEvaluation::with(['evaluator'])
        ->where('employee_id', $employeeId)
        ->where('company_id', $companyId)
        ->orderBy('created_at', 'desc')
        ->get();

    return view('EmployeeInterface.performances.index', compact('performanceCriteria', 'evaluations'));
}

public function show($id)
{
    // Check if employee is logged in using your custom session
    $employeeId = session('employee_id');
    $companyId = session('company_id');

    if (!$employeeId) {
        return redirect()->route('employee.login')->with('error', 'Please log in to continue.');
    }

    // Get the specific evaluation for this employee
    $evaluation = DB::table('performance_evaluations')
        ->join('users as evaluators', 'performance_evaluations.evaluator_id', '=', 'evaluators.id')
        ->join('users as employees', 'performance_evaluations.employee_id', '=', 'employees.id')
        ->where('performance_evaluations.id', $id)
        ->where('performance_evaluations.employee_id', $employeeId) // Ensure employee can only see their own evaluation
        ->where('performance_evaluations.company_id', $companyId)
        ->select(
            'performance_evaluations.*',
            'evaluators.name as evaluator_name',
            'employees.name as employee_name',
            'employees.email as employee_email'
        )
        ->first();

    if (!$evaluation) {
        return redirect()->route('employee.performances.index')->with('error', 'Evaluation not found or access denied.');
    }

    // Get criteria scores if using custom criteria
    $criteriaScores = collect();
    if ($evaluation->uses_custom_criteria && $evaluation->criteria_scores) {
        // Decode the JSON data from criteria_scores column
        $scores = json_decode($evaluation->criteria_scores, true);
        if ($scores && is_array($scores)) {
            // The data is stored as: {criteriaId: {score: X, name: "Y"}}
            $criteriaScores = collect($scores)->map(function ($scoreData, $criteriaId) {
                return (object)[
                    'id' => $criteriaId,
                    'name' => $scoreData['name'] ?? 'Unknown Criteria',
                    'score' => is_numeric($scoreData['score']) ? (float)$scoreData['score'] : 0.0
                ];
            });
        }
    }

    // Get standard criteria with scores
    $standardCriteria = $this->getStandardCriteriaWithScores($evaluation);

    return view('EmployeeInterface.performances.view', compact('evaluation', 'criteriaScores', 'standardCriteria'));
}

/**
 * Get standard criteria with their calculated scores
 */
private function getStandardCriteriaWithScores($evaluation)
{
    $standardCriteria = [
        [
            'name' => 'Quality of Work',
            'icon' => 'fas fa-star',
            'score' => $evaluation->uses_custom_criteria ? null : $evaluation->quality_of_work
        ],
        [
            'name' => 'Productivity',
            'icon' => 'fas fa-chart-line',
            'score' => $evaluation->uses_custom_criteria ? null : $evaluation->productivity
        ],
        [
            'name' => 'Communication',
            'icon' => 'fas fa-comments',
            'score' => $evaluation->uses_custom_criteria ? null : $evaluation->communication
        ],
        [
            'name' => 'Teamwork',
            'icon' => 'fas fa-users',
            'score' => $evaluation->uses_custom_criteria ? null : $evaluation->teamwork
        ],
        [
            'name' => 'Punctuality',
            'icon' => 'fas fa-clock',
            'score' => $evaluation->uses_custom_criteria ? null : $evaluation->punctuality
        ],
        [
            'name' => 'Initiative',
            'icon' => 'fas fa-lightbulb',
            'score' => $evaluation->uses_custom_criteria ? null : $evaluation->initiative
        ]
    ];

    return $standardCriteria;
}

}
