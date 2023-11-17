<?php

namespace App\Models;

use App\Lib\Apis\OpenAI;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static findOrFail(int|null $threadId)
 * @method static find(int|null $getThreadId)
 * @method static create(array $array)
 * @property int $assistant_id
 * @property string $remote_id
 * @property int $id
 */
class Thread extends Model
{
    use HasFactory;

    protected $fillable = [
        'assistant_id',
        'remote_id',
        'description'
    ];

    /**
     * @return HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * @return array
     */
    public static function remoteCreate(): array
    {
        $api = new OpenAI();
        return $api->assistant()->thread()->create();
    }

    /**
     * @param string $query
     * @return Message
     */
    public function createMessage(string $query): Message
    {
        $api = new OpenAI();
        $remoteMessage = $api->assistant()->message()->create($this->remote_id, $query);
        return Message::create([
            'thread_id' => $this->id,
            'remote_id' => $remoteMessage['id'],
            'role' => $remoteMessage['role'],
            'text' => $query
        ]);
    }

    /**
     * @return Message
     */
    public function getLastMessage(): Message
    {
        $messages = OpenAI::factory()->assistant()->message()->list($this->remote_id);
        $lastRemoteMessage = $messages['data'][0];
        return Message::create([
            'thread_id' => $this->id,
            'remote_id' => $lastRemoteMessage['id'],
            'role' => $lastRemoteMessage['role'],
            'text' => $lastRemoteMessage['content'][0]['text']['value']
        ]);
    }
}
