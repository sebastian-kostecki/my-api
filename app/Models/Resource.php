<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $category
 * @property array $tags
 * @method static findOrFail(mixed $record_id)
 * @method static where(string $column, string $category)
 */
class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'category',
        'tags',
    ];

    protected $casts = [
        'tags' => 'array'
    ];
}
