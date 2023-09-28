<?php

namespace App\Models;

use App\Enums\Assistant\ChatModel;
use App\Lib\Assistant\Assistant;
use App\Lib\Interfaces\ActionInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @method static where(string $column, mixed $value)
 * @method static pluck(string $column)
 * @method static findOrFail(int $id)
 * @method static type(mixed $action)
 * @method static get()
 * @method static create(array $array)
 * @property string $type
 */
class Action extends Model
{
    protected $fillable = [
        'name',
        'type',
        'icon',
        'shortcut',
        'model',
        'enabled'
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'model' => ChatModel::class
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
            if (Str::endsWith($file, 'AbstractAction.php')) {
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
     * @param string|null $class
     * @return void
     */
    public function scopeType(Builder $query, ?string $class): void
    {
        $query->where('type', $class);
    }

    /**
     * @param Assistant $assistant
     * @return ActionInterface
     */
    public function factory(Assistant $assistant): ActionInterface
    {
        return new $this->type($assistant);
    }
}
