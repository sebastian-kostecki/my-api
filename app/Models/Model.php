<?php

namespace App\Models;

use App\Lib\Interfaces\Connections\ArtificialIntelligenceInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * @property string $name
 * @property ArtificialIntelligenceInterface $type
 *
 * @method static create(array $array)
 * @method static where(string $column, string $param, string $value)
 */
class Model extends EloquentModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
    ];

    public function connectionName(): Attribute
    {
        return Attribute::make(
            get: fn () => class_basename($this->type),
        );
    }

    public static function getModel(string $name): Model
    {
        return self::where('name', 'like', $name.'%')->first();
    }
}
