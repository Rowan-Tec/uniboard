<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LostFoundItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'type', 'title', 'description', 'location',
        'date_lost_found', 'images', 'is_resolved', 'resolved_at'
    ];

    protected $casts = [
        'images' => 'array',
        'date_lost_found' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeLost($query)
    {
        return $query->where('type', 'lost')->where('is_resolved', false);
    }

    public function scopeFound($query)
    {
        return $query->where('type', 'found')->where('is_resolved', false);
    }
}