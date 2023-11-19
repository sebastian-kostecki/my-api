<?php

namespace App\Models;

use App\Lib\Apis\OpenAI;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property string $assistant_remote_id
 * @property int $action_id
 * @property Collection $threads
 * @property int $id
 */
class Assistant extends Model
{
    use HasFactory;

    protected $fillable = [
        'action_id',
        'assistant_remote_id'
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
        $this->assistant_remote_id = $assistant['id'];
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
     * @return Thread
     */
    public function getOrCreateThread(?int $threadId): Thread
    {
        if ($thread = $this->threads->where('id', $threadId)->where('assistant_id', $this->id)->first()) {
            return $thread;
        }
        $remoteThread = Thread::remoteCreate();
        return Thread::create([
            'assistant_id' => $this->id,
            'remote_id' => $remoteThread['id']
        ]);
    }

    /**
     * @param string $remoteThreadId
     * @return void
     */
    public function run(string $remoteThreadId): void
    {
        $startedRun = OpenAI::factory()->assistant()->run()->create($remoteThreadId, $this->assistant_remote_id);
        do {
            sleep(2);
            $run = OpenAI::factory()->assistant()->run()->retrieve($remoteThreadId, $startedRun['id']);
        } while($run['status'] !== 'completed');
    }
}
