<?php

namespace App\Models;

use App\Lib\Interfaces\AssistantInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * @property class-string<AssistantInterface> $type
 * @property string $name
 * @property string $description
 * @property string $instructions
 * @property int $model_id
 * @property Model $model
 * @method static findOrFail(int $assistantId)
 */
class Assistant extends EloquentModel
{
    use HasFactory;

    protected $fillable = [
        'model_id',
        'type',
        'name',
        'description',
        'instructions',
    ];

    /**
     * @return BelongsTo
     */
    public function model(): BelongsTo
    {
        return $this->belongsTo(Model::class);
    }





    public function action(): BelongsTo
    {
        return $this->belongsTo(Action::class);
    }

    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }

    public function latestThread(): HasOne
    {
        return $this->hasOne(Thread::class)->latestOfMany();
    }

//    /**
//     * @param array $params
//     * @return void
//     */
//    public function create(array $params): void
//    {
//        $assistant = $this->getOrCreateRemote($params);
//        $this->action_id = 0;
//        $this->remote_id = $assistant['id'];
//        $this->details = $assistant;
//        $this->save();
//    }
//
//    public function getOrCreateRemote(array $params)
//    {
//        $list = OpenAI::factory()->assistant()->assistant()->list();
//        if (!empty($list['data'])) {
//            foreach ($list['data'] as $remoteAssistant) {
//                if ($remoteAssistant['name'] === $params['name']) {
//                    return $remoteAssistant;
//                }
//            }
//        }
//        return OpenAI::factory()->assistant()->assistant()->create($params);
//    }
//
//    /**
//     * @param int|null $threadId
//     * @param string $query
//     * @return Thread
//     * @throws JsonException
//     */
//    public function getOrCreateThread(?int $threadId, string $query): Thread
//    {
//        if ($thread = $this->getThread($threadId)) {
//            return $thread;
//        }
//        return $this->createThread($query);
//    }
//
//    /**
//     * @param int|null $threadId
//     * @return Thread|null
//     */
//    public function getThread(?int $threadId): ?Thread
//    {
//        return $this->threads->where('id', $threadId)->first();
//    }
//
//    /**
//     * @param string $query
//     * @return Thread
//     * @throws JsonException
//     */
//    public function createThread(string $query): Thread
//    {
//        $remoteThread = Thread::remoteCreate();
//        $description = Thread::createDescription($query);
//        return Thread::create([
//            'assistant_id' => $this->id,
//            'remote_id' => $remoteThread['id'],
//            'description' => $description,
//            'details' => $remoteThread
//        ]);
//    }
//
//
//    /**
//     * @param string $remoteThreadId
//     * @return void
//     */
//    public function run(string $remoteThreadId): void
//    {
//        $startedRun = OpenAI::factory()->assistant()->run()->create($remoteThreadId, $this->remote_id);
//        do {
//            sleep(2);
//            $run = OpenAI::factory()->assistant()->run()->retrieve($remoteThreadId, $startedRun['id']);
//        } while ($run['status'] !== 'completed');
//    }

    /**
     * @return Collection
     */
    public static function scan(): Collection
    {
        $dir = app_path() . '/Lib/Assistants';
        $files = File::allFiles($dir);

        return collect($files)->filter(function ($file) {
            return !Str::startsWith($file->getBasename(), 'Abstract');
        })->map(function ($file) {
            $namespace = 'App\Lib\Assistants';
            $endClass = $file->getRelativePathname();
            $endClass = Str::beforeLast($endClass, '.php');
            $endClass = Str::replace('/', '\\', $endClass);
            return $namespace . '\\' . $endClass;
        })->values();
    }

    /**
     * @param int $model_id
     * @return void
     */
    public function setModel(int $model_id): void
    {
        $this->model_id = $model_id;
        $this->save();
    }
}
