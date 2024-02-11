<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function comment()
    {
        return $this->belongsTo('App\Models\NotificationComment', 'notification_comment_id');
    }
}
