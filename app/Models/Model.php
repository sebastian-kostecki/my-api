<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * @property string $name
 * @method static create(array $array)
 * @method static where(string $column, array|string $value)
 */
class Model extends EloquentModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type'
    ];
}
