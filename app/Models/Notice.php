<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'category',
        'priority',
        'user_id',
        'is_approved',
        'approved_by',
        'approved_at',
        'published_at',
        'is_rejected',
        'reject_reason',
        'rejected_by',
        'rejected_at',
        'scheduled_at',
        'expires_at',
    ];

    protected $casts = [
        'approved_at'   => 'datetime',
        'published_at'  => 'datetime',
        'rejected_at'   => 'datetime',
        'scheduled_at'  => 'datetime',
        'expires_at'    => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejecter()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_approved', true)
                     ->where('is_rejected', false)
                     ->where(function ($q) {
                         $q->whereNull('scheduled_at')
                           ->orWhere('scheduled_at', '<=', now());
                     })
                     ->where(function ($q) {
                         $q->whereNull('expires_at')
                           ->orWhere('expires_at', '>', now());
                     });
    }

    public function scopePending($query)
    {
        return $query->where('is_approved', false)
                     ->where('is_rejected', false);
    }

    public function scopeRejected($query)
    {
        return $query->where('is_rejected', true);
    }
}