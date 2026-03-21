<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditScoreHistory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'organization_id',
        'old_score',
        'new_score',
        'delta',
        'trigger_event',
        'factor_breakdown',
        'created_at',
    ];

    protected $casts = [
        'factor_breakdown' => 'array',
        'created_at'       => 'datetime',
    ];
}