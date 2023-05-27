<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'content'
    ];

    public $timestamps = false;
}
