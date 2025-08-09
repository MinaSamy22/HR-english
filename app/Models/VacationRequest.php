<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class VacationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vacation_type',
        'start_date',
        'end_date',
        'reason',
        'emergency_contact',
        'is_urgent',
        'status',
        'total_days'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_urgent' => 'boolean',
    ];

    // Relationship with User

    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

    // Boot method to automatically set user_id
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($vacationRequest) {
            if (!$vacationRequest->user_id) {
                $vacationRequest->user_id = auth()->id() ?? request()->user()?->id;
            }
        });
    }

    // Accessor to get formatted vacation type
    public function getFormattedVacationTypeAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->vacation_type));
    }

    // Accessor to calculate total days if not stored
    public function getTotalDaysAttribute($value)
    {
        if ($value) {
            return $value;
        }

        if ($this->start_date && $this->end_date) {
            $startDate = Carbon::parse($this->start_date);
            $endDate = Carbon::parse($this->end_date);
            return $startDate->diffInDays($endDate) + 1;
        }

        return 0;
    }

    // Scope for pending requests
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope for approved requests
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Scope for rejected requests
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Scope for urgent requests
    public function scopeUrgent($query)
    {
        return $query->where('is_urgent', true);
    }
}
