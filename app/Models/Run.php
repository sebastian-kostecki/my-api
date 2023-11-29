<?php

namespace App\Models;

use App\Lib\Apis\OpenAI;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Thread $thread
 * @property string $remote_id
 * @property string $status
 * @property array $details
 */
class Run extends Model
{
    use HasFactory;

    protected $fillable = [
        'thread_id',
        'remote_id',
        'status',
        'started_at',
        'completed_at',
        'details'
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
     * @return Run
     */
    public static function createRun(Thread $thread): Run
    {
        $remoteRun = OpenAI::factory()->assistant()->run()->create($thread->remote_id, $thread->assistant->remote_id);
        return self::create([
            'thread_id' => $thread->id,
            'remote_id' => $remoteRun['id'],
            'status' => 'started',
            'started_at' => Carbon::now(),
            'details' => $remoteRun
        ]);
    }


    /**
     * @return $this
     */
    public function retrieve(): Run
    {
        $remoteRun = OpenAI::factory()->assistant()->run()->retrieve($this->thread->remote_id, $this->remote_id);

        if (!empty($remoteRun['last_error'])) {
            $this->update([
                'status' => 'failed',
                'completed_at' => Carbon::now(),
                'details' => $remoteRun
            ]);
            return $this;
        }

        if ($remoteRun['status'] !== 'completed') {
            $this->update([
                'status' => 'in_progress',
                'details' => $remoteRun
            ]);
            return $this;
        }

        $this->update([
            'status' => 'completed',
            'completed_at' => Carbon::now(),
            'details' => $remoteRun
        ]);
        return $this;
    }

}
