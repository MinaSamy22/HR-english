<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'recipient_ids',
        'subject',
        'content',
        'is_urgent',
        'read_by'
    ];

    protected $casts = [
        'recipient_ids' => 'array',
        'read_by' => 'array',
        'is_urgent' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship with sender
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Check if message is read by specific user
    public function isReadBy($userId)
    {
        $readBy = $this->read_by ?? [];
        return isset($readBy[$userId]);
    }

    // Mark message as read by specific user
    public function markAsRead($userId)
    {
        $readBy = $this->read_by ?? [];
        if (!isset($readBy[$userId])) {
            $readBy[$userId] = now()->toISOString();
            $this->update(['read_by' => $readBy]);
        }
    }

    // Get read time for specific user
    public function getReadTime($userId)
    {
        $readBy = $this->read_by ?? [];
        return isset($readBy[$userId]) ? Carbon::parse($readBy[$userId]) : null;
    }

    // Get recipients
    public function recipients()
    {
        return User::whereIn('id', $this->recipient_ids ?? [])->get();
    }
    
}
