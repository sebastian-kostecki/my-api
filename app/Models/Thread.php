<?php

namespace App\Models;

use App\Lib\Connections\ArtificialIntelligence\OpenAI;
use App\Lib\Connections\Qdrant;
use App\Lib\Exceptions\ConnectionException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use JsonException;

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
    public static function getOrMake(?int $id): self
    {
        $thread = self::find($id);
        if ($thread) {
            return $thread;
        }
        return self::make([
            'description' => '',
        ]);
    }

    /**
     * @param string $input
     * @return void
     */
    public function addDescription(string $input): void
    {
        if (!empty($this->description)) {
            return;
        }
        $this->description = OpenAI::factory()->shortSummarize($input);
        $this->save();
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
     * @throws ConnectionException
     * @throws JsonException
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

        $embeddings = OpenAI::factory()->createEmbeddings($text);
        Qdrant::factory()->upsertPoint($embeddings, [
            'text' => $text,
            'category' => 'conversation'
        ]);
    }
}
