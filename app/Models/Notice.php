<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'content', 'category', 'user_id',
        'is_approved', 'approved_by', 'approved_at', 'published_at'
    ];

    // This fixes the red line on ->user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
    public function scopeRejected($query)
{
    return $query->where('is_rejected', true);
}
}