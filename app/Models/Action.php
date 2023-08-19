<?php

namespace App\Models;

use App\Enums\OpenAiModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

/**
 * @method static where(string $column, mixed $value)
 * @method static pluck(string $column)
 * @method static findOrFail(int $id)
 * @method static class(string $class)
 */
class Action extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'icon',
        'shortcut',
        'prompt',
        'model',
        'enabled'
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'model' => OpenAiModel::class
    ];

    public static function scan(): array
    {
        $namespace = 'App\Lib\Assistant\Actions';
        $dir = app_path() . '/Lib/Assistant/Actions';
        $files = scandir($dir);

        $actions = [];

        foreach ($files as $file) {
            if (!Str::endsWith($file, '.php')) {
                continue;
            }
            $name = Str::beforeLast($file, '.php');
            $classname = $namespace . '\\' . $name;
            $actions[] = $classname;
        }

        return $actions;
    }

    /**
     * @param Builder $query
     * @param string $class
     * @return void
     */
    public function scopeClass(Builder $query, string $class): void
    {
        $query->where('type', $class);
    }
}
