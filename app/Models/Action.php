<?php

namespace App\Models;

use App\Enums\Assistant\ChatModel;
use App\Lib\Assistant\Assistant;
use App\Lib\Interfaces\ActionInterface;
use App\Models\Assistant as AssistantModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
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
        'instructions',
        'enabled',
        'hidden'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'enabled' => 'boolean',
        'model' => ChatModel::class
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'enabled' => true,
        'hidden' => false,
    ];

    /**
     * @return HasOne
     */
    public function assistant(): HasOne
    {
        return $this->hasOne(AssistantModel::class);
    }

    /**
     * @return Collection
     */
    public static function scan(): Collection
    {
        $dir = app_path() . '/Lib/Assistant/Actions';
        $files = File::allFiles($dir);

        return collect($files)->filter(function ($file) {
            return !(Str::startsWith($file->getBasename(),'Default')
                || Str::startsWith($file->getBasename(),'Abstract'));
        })->map(function ($file) {
            $namespace = 'App\Lib\Assistant\Actions';
            $endClass = $file->getRelativePathname();
            $endClass = Str::beforeLast($endClass, '.php');
            $endClass = Str::replace('/', '\\', $endClass);
            return $namespace . '\\' . $endClass;
        })->values();
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
