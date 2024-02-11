<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentsInvalidSteps extends Model
{
    protected $guarded = [];

    protected $casts = [
        'invalid_steps' => 'array',
    ];

    public function document()
    {
        return $this->belongsTo('App\Models\Document');
    }
}
