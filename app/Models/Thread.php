<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $description
 * @property Collection $messages
 * @property int $id
 * @method static find(int $id)
 * @method static create(string[] $array)
 * @method static make(string[] $array)
 */
class Thread extends Model
{
    use HasFactory;

    protected $fillable = [
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
     * @param int|null $id
     * @return self
     */
    public static function getOrCreate(?int $id): self
    {
        $thread = self::find($id);
        if ($thread) {
            return $thread;
        }
        //ToDo generate by AI
        return self::make([
            'description' => "some description",
        ]);
    }

    /**
     * @return array<array{
     *     role: string,
     *     content: string
     * }>
     */
    public function getLastMessages(): array
    {
        return $this->messages->map(function (Message $message) {
            return [
                'role' => $message->role,
                'content' => $message->text
            ];
        })->toArray();
    }

    /**
     * @param string $role
     * @param string $text
     * @return void
     */
    public function addMessage(string $role, string $text): void
    {
        Message::create([
            'thread_id' => $this->id,
            'role' => $role,
            'text' => $text,
            'status' => 'completed',
            'completed_at' => Carbon::now()
        ]);
    }

//    /**
//     * @return BelongsTo
//     */
//    public function assistant(): BelongsTo
//    {
//        return $this->belongsTo(Assistant::class);
//    }
//
//    /**
//     * @return array
//     */
//    public static function remoteCreate(): array
//    {
//        $api = new OpenAI();
//        return $api->assistant()->thread()->create();
//    }
//
//    /**
//     * @param string $query
//     * @return Message
//     */
//    public function createMessage(string $query): Message
//    {
//        $api = new OpenAI();
//        $remoteMessage = $api->assistant()->message()->create($this->remote_id, $query);
//        return Message::create([
//            'thread_id' => $this->id,
//            'remote_id' => $remoteMessage['id'],
//            'role' => $remoteMessage['role'],
//            'text' => $query,
//            'details' => $remoteMessage
//        ]);
//    }
//
//    /**
//     * @return Message
//     */
//    public function getLastMessage(): Message
//    {
//        $messages = OpenAI::factory()->assistant()->message()->list($this->remote_id);
//        $lastRemoteMessage = $messages['data'][0];
//        return Message::create([
//            'thread_id' => $this->id,
//            'remote_id' => $lastRemoteMessage['id'],
//            'role' => $lastRemoteMessage['role'],
//            'text' => $lastRemoteMessage['content'][0]['text']['value'],
//            'details' => $lastRemoteMessage
//        ]);
//    }
//
//    /**
//     * @param string $query
//     * @return string
//     * @throws JsonException
//     */
//    public static function createDescription(string $query): string
//    {
//        $api = OpenAI::factory();
//        $model = ChatModel::GPT3;
//        $messages = [
//            [
//                'role' => 'user',
//                'content' => $query
//            ],
//        ];
//        $temperature = 0.5;
//        $tools = [
//            [
//                'type' => 'function',
//                'function' => [
//                    'name' => 'generate_short_description',
//                    'description' => 'Generates a short description (maximum three words) for a given text',
//                    'parameters' => [
//                        'type' => 'object',
//                        'properties' => [
//                            'input_text' => [
//                                'type' => 'string',
//                                'description' => 'The input text for which a short description will be generated'
//                            ],
//                        ],
//                        'required' => ['input_text'],
//                    ],
//                    'examples' => [
//                        'Array Methods',
//                        'Docker Info'
//                    ],
//                ]
//            ]
//        ];
//        $response = $api->chat()->create($model, $messages, $temperature, $tools);
//        return $api->chat()->getFunctions($response)->input_text;
//    }
}
