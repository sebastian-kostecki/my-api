<?php

namespace App\Models;

use App\Lib\Interfaces\Connections\ArtificialIntelligenceInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * @property string $name
 * @property ArtificialIntelligenceInterface $type
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
