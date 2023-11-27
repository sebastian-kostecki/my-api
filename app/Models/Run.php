<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Run extends Model
{
    use HasFactory;

    protected $fillable = [
        'thread_id',
        'remote_id',
        'status',
        'started_at',
        'completed_at',
        'details'
    ];

    protected $casts = [
        'details' => 'array',
    ];
}
