<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EarlyLeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'request_date',
        'requested_leave_time',
        'reason',
        'status',
        'urgent_request',
        'is_seen', // Make sure this is in fillable
        'created_by',
        'updated_by',
    ];

    protected $guarded = []; // Or remove $fillable entirely and use this

    protected $casts = [
        'request_date' => 'date',
        'urgent_request' => 'boolean',
        'is_seen' => 'boolean',
    ];

    /**
     * Get the user (employee) who made the request
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    /**
     * Get the user who created the record
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the record
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope for pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved requests
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for rejected requests
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope for urgent requests
     */
    public function scopeUrgent($query)
    {
        return $query->where('urgent_request', 1);
    }

    /**
     * Scope for unseen requests
     */
    public function scopeUnseen($query)
    {
        return $query->where('is_seen', 0);
    }
}
