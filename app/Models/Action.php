<?php

namespace App\Models;

use App\Lib\Interfaces\ActionInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * @property string $type
 * @property string $name
 * @property string $icon
 * @property string $shortcut
 * @method static findOrFail(int $id)
 */
class Action extends Model
{
    protected $fillable = [
        'type',
        'name',
        'icon',
        'shortcut'
    ];

    /**
     * @param Assistant $assistant
     * @param Thread|null $thread
     * @param string $input
     * @return ActionInterface
     */
    public function getInstance(Assistant $assistant, ?Thread $thread, string $input): ActionInterface
    {
        return new $this->type($assistant, $thread, $input);
    }

    /**
     * @return Collection
     */
    public static function scan(): Collection
    {
        $dir = app_path() . '/Lib/Actions';
        $files = File::allFiles($dir);

        return collect($files)->filter(function ($file) {
            return !Str::startsWith($file->getBasename(), 'Abstract');
        })->map(function ($file) {
            $namespace = 'App\Lib\Actions';
            $endClass = $file->getRelativePathname();
            $endClass = Str::beforeLast($endClass, '.php');
            $endClass = Str::replace('/', '\\', $endClass);
            return $namespace . '\\' . $endClass;
        })->values();
    }



//    /**
//     * @param Builder $query
//     * @param string|null $class
//     * @return void
//     */
//    public function scopeType(Builder $query, ?string $class): void
//    {
//        $query->where('type', $class);
//    }
//
//    /**
//     * @param Assistant $assistant
//     * @return ActionInterface
//     */
//    public function factory(Assistant $assistant): ActionInterface
//    {
//        return new $this->type($assistant);
//    }
//
//    /**
//     * @return void
//     */
//    public function syncRemote(): void
//    {
//        if ($this->remoteAssistant) {
//            OpenAI::factory()->assistant()->assistant()->modify($this);
//        }
//    }
//
//    /**
//     * @return void
//     */
//    public function deleteRemote(): void
//    {
//        if ($this->remoteAssistant) {
//            OpenAI::factory()->assistant()->assistant()->delete($this);
//        }
//    }
}
