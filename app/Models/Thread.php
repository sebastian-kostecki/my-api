<?php

namespace App\Models;

use App\Lib\Apis\OpenAI;
use App\Enums\Assistant\ChatModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use JsonException;

/**
 * @method static findOrFail(int|null $threadId)
 * @method static find(int|null $getThreadId)
 * @method static create(array $array)
 * @property int $assistant_id
 * @property string $remote_id
 * @property int $id
 * @property string|null $description
 * @property Assistant $assistant
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
     * @return BelongsTo
     */
    public function assistant(): BelongsTo
    {
        return $this->belongsTo(Assistant::class);
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

    /**
     * @param string $query
     * @return void
     * @throws JsonException
     */
    public function createDescription(string $query): void
    {
        if (!$this->description) {
            $api = OpenAI::factory();
            $model = ChatModel::GPT3;
            $messages = [
                [
                    'role' => 'user',
                    'content' => $query
                ],
            ];
            $temperature = 0.7;
            $tools = [
                [
                    'type' => 'function',
                    'function' => [
                        'name' => 'generate_short_description',
                        'description' => 'Generates a short description (maximum three words) for a given text',
                        'parameters' => [
                            'type' => 'object',
                            'properties' => [
                                'input_text' => [
                                    'type' => 'string',
                                    'description' => 'The input text for which a short description will be generated'
                                ],
                            ],
                            'required' => ['input_text'],
                        ],
                        'examples' => [
                            'Array Methods',
                            'Docker Info'
                        ],
                    ]
                ]
            ];
            $response = $api->chat()->create($model, $messages, $temperature, $tools);
            $result = $api->chat()->getFunctions($response);
            $this->description = $result->input_text;
            $this->save();
        }
    }
}
