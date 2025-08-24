<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'company_id',
        'evaluator_id',
        'evaluation_period',
        'evaluation_year',
        'quality_of_work',
        'productivity',
        'communication',
        'teamwork',
        'punctuality',
        'initiative',
        'criteria_scores',
        'uses_custom_criteria',
        'overall_score',
        'strengths',
        'areas_for_improvement',
        'goals_for_next_period',
        'hr_comments',
        'status'
    ];

    protected $casts = [
        'evaluation_year' => 'integer',
        'quality_of_work' => 'integer',
        'productivity' => 'integer',
        'communication' => 'integer',
        'teamwork' => 'integer',
        'punctuality' => 'integer',
        'initiative' => 'integer',
        'overall_score' => 'decimal:2',
        'uses_custom_criteria' => 'boolean',
        'criteria_scores' => 'array'
    ];

    /**
     * Get the employee being evaluated
     */
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    /**
     * Get the evaluator (HR user)
     */
    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    /**
     * Get the company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Scope to get evaluations for a specific company
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Scope to get evaluations for a specific employee
     */
    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    /**
     * Calculate overall score for standard criteria
     */
    public function calculateOverallScore()
    {
        if ($this->uses_custom_criteria) {
            return $this->overall_score; // Already calculated
        }

        $scores = [
            $this->quality_of_work,
            $this->productivity,
            $this->communication,
            $this->teamwork,
            $this->punctuality,
            $this->initiative
        ];

        $validScores = array_filter($scores, function($score) {
            return !is_null($score) && $score > 0;
        });

        return count($validScores) > 0 ? round(array_sum($validScores) / count($validScores), 2) : 0;
    }

    /**
     * Get performance rating based on overall score
     */
    public function getPerformanceRating()
    {
        $score = $this->overall_score;

        if ($score >= 4.5) return 'Excellent';
        if ($score >= 3.5) return 'Good';
        if ($score >= 2.5) return 'Satisfactory';
        if ($score >= 1.5) return 'Needs Improvement';
        return 'Poor';
    }

    /**
     * Get CSS class for performance rating badge
     */
    public function getPerformanceRatingClass()
    {
        $score = $this->overall_score;

        if ($score >= 4.5) return 'success';
        if ($score >= 3.5) return 'primary';
        if ($score >= 2.5) return 'info';
        if ($score >= 1.5) return 'warning';
        return 'danger';
    }

    /**
     * Get custom criteria scores with names
     */
    public function getCustomCriteriaScores()
    {
        if (!$this->uses_custom_criteria || !$this->criteria_scores) {
            return collect();
        }

        return collect($this->criteria_scores);
    }

    /**
     * Get standard criteria scores
     */
    public function getStandardCriteriaScores()
    {
        if ($this->uses_custom_criteria) {
            return collect();
        }

        return collect([
            'quality_of_work' => [
                'score' => $this->quality_of_work,
                'name' => 'Quality of Work',
                'weight' => 1
            ],
            'productivity' => [
                'score' => $this->productivity,
                'name' => 'Productivity',
                'weight' => 1
            ],
            'communication' => [
                'score' => $this->communication,
                'name' => 'Communication',
                'weight' => 1
            ],
            'teamwork' => [
                'score' => $this->teamwork,
                'name' => 'Teamwork',
                'weight' => 1
            ],
            'punctuality' => [
                'score' => $this->punctuality,
                'name' => 'Punctuality',
                'weight' => 1
            ],
            'initiative' => [
                'score' => $this->initiative,
                'name' => 'Initiative',
                'weight' => 1
            ]
        ]);
    }

    /**
     * Get all criteria scores (custom or standard)
     */
    public function getAllCriteriaScores()
    {
        return $this->uses_custom_criteria
            ? $this->getCustomCriteriaScores()
            : $this->getStandardCriteriaScores();
    }

    /**
     * Get readable evaluation period with year
     */
    public function getFullEvaluationPeriod()
    {
        return $this->evaluation_period . ' ' . $this->evaluation_year;
    }

    /**
     * Check if evaluation can be edited
     */
    public function canBeEdited()
    {
        return in_array($this->status, ['draft', 'completed']);
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass()
    {
        switch ($this->status) {
            case 'draft':
                return 'secondary';
            case 'completed':
                return 'primary';
            case 'reviewed':
                return 'success';
            default:
                return 'secondary';
        }
    }
}
