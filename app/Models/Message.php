<?php

namespace App\Models;

use App\Lib\Apis\OpenAI;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $thread_id
 * @property int $remote_id
 * @property string $text
 * @property string $role
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

    public static function createUserMessage(Thread $thread, string $query)
    {
        $remoteMessage = OpenAI::factory()->assistant()->message()->create($thread->remote_id, $query);
        return self::create([
            'thread_id' => $thread->id,
            'remote_id' => $remoteMessage['id'],
            'role' => $remoteMessage['role'],
            'text' => $query,
            'status' => 'complete',
            'details' => $remoteMessage,
            'completed_at' => Carbon::now()
        ]);
    }
}
