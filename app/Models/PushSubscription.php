<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PushSubscription extends Model
{
    protected $fillable = ['user_id', 'endpoint', 'keys_p256dh', 'keys_auth'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}