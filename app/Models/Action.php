<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @method static where(string $column, mixed $value)
 * @method static pluck(string $column)
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
        'enabled'
    ];

    protected $casts = [
      'enabled' => 'boolean'
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
}
