<?php

namespace App\Models;

use App\Lib\Apis\OpenAI;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $thread_id
 * @property int $remote_id
 * @property string $text
 * @property string $role
 * @property Thread $thread
 * @method static create(array $array)
 */
class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'thread_id',
        'remote_id',
        'role',
        'text',
        'status',
        'details',
        'completed_at'
    ];

    protected $casts = [
        'details' => 'array',
    ];

    /**
     * @return BelongsTo
     */
    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * @param Thread $thread
     * @param string $query
     * @return mixed
     */
    public static function createUserMessage(Thread $thread, string $query): Message
    {
        $remoteMessage = OpenAI::factory()->assistant()->message()->create($thread->remote_id, $query);
        return self::create([
            'thread_id' => $thread->id,
            'remote_id' => $remoteMessage['id'],
            'role' => $remoteMessage['role'],
            'text' => $query,
            'status' => 'completed',
            'details' => $remoteMessage,
            'completed_at' => Carbon::now()
        ]);
    }

    /**
     * @param Thread $thread
     * @return mixed
     */
    public static function createAssistantMessage(Thread $thread): Message
    {
        return self::create([
            'thread_id' => $thread->id,
            'remote_id' => 0,
            'role' => 'assistant',
            'status' => 'started'
        ]);
    }

    /**
     * @param Run $run
     * @return void
     */
    public function markAsFailed(Run $run): void
    {
        $this->update([
            'text' => $run->details['last_error'],
            'status' => $run->status,
            'completed_at' => Carbon::now()
        ]);
    }

    /**
     * @return void
     */
    public function markAsInProgress(): void
    {
        $this->update([
            'status' => 'in_progress'
        ]);
    }

    /**
     * @return void
     */
    public function markAsCompleted(): void
    {
        $messages = OpenAI::factory()->assistant()->message()->list($this->thread->remote_id);
        $lastRemoteMessage = $messages['data'][0];

        $this->update([
            'remote_id' => $lastRemoteMessage['id'],
            'text' => $lastRemoteMessage['content'][0]['text']['value'],
            'status' => 'completed',
            'details' => $lastRemoteMessage,
            'completed_at' => Carbon::now()
        ]);
    }
}
