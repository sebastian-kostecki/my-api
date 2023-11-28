<?php

namespace App\Models;

use App\Lib\Apis\OpenAI;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use JsonException;

/**
 * @property string $remote_id
 * @property int $action_id
 * @property Collection $threads
 * @property int $id
 * @property array $details
 */
class Assistant extends Model
{
    use HasFactory;

    protected $fillable = [
        'action_id',
        'remote_id',
        'details'
    ];

    protected $casts = [
        'details' => 'array',
    ];

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

    /**
     * @param array $params
     * @return void
     */
    public function create(array $params): void
    {
        $assistant = $this->getOrCreateRemote($params);
        $this->action_id = 0;
        $this->remote_id = $assistant['id'];
        $this->details = $assistant;
        $this->save();
    }

    public function getOrCreateRemote(array $params)
    {
        $list = OpenAI::factory()->assistant()->assistant()->list();
        if (!empty($list['data'])) {
            foreach ($list['data'] as $remoteAssistant) {
                if ($remoteAssistant['name'] === $params['name']) {
                    return $remoteAssistant;
                }
            }
        }
        return OpenAI::factory()->assistant()->assistant()->create($params);
    }

    /**
     * @param int|null $threadId
     * @param string $query
     * @return Thread
     * @throws JsonException
     */
    public function getOrCreateThread(?int $threadId, string $query): Thread
    {
        if ($thread = $this->getThread($threadId)) {
            return $thread;
        }
        return $this->createThread($query);
    }

    /**
     * @param int|null $threadId
     * @return Thread|null
     */
    public function getThread(?int $threadId): ?Thread
    {
        return $this->threads->where('id', $threadId)->first();
    }

    /**
     * @param string $query
     * @return Thread
     * @throws JsonException
     */
    public function createThread(string $query): Thread
    {
        $remoteThread = Thread::remoteCreate();
        $description = Thread::createDescription($query);
        return Thread::create([
            'assistant_id' => $this->id,
            'remote_id' => $remoteThread['id'],
            'description' => $description,
            'details' => $remoteThread
        ]);
    }


    /**
     * @param string $remoteThreadId
     * @return void
     */
    public function run(string $remoteThreadId): void
    {
        $startedRun = OpenAI::factory()->assistant()->run()->create($remoteThreadId, $this->remote_id);
        do {
            sleep(2);
            $run = OpenAI::factory()->assistant()->run()->retrieve($remoteThreadId, $startedRun['id']);
        } while ($run['status'] !== 'completed');
    }
}
