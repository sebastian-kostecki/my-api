<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail(int $int)
 * @method static create(string[] $array)
 * @method static where(string $column, string $operator, Carbon $value)
 * @property string $question
 * @property string $answer
 * @property int $id
 */
class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'answer'
    ];

    public static function updateSystemPrompt(array $resources = []): void
    {
        $context = implode("\n", $resources);
        $currentDateTime = Carbon::now()->format('Y-m-d H:i');
        $prompt = <<<EOD
You're a helpful assistant. Answer questions as short and concise and as truthfully as possible, based on a context.

Please note that context below may include:
- long-term memory,
- actions you may take,
- user's personal notes and/or links you do have access to.

And you should prioritize this knowledge while answering.

If you don't know the answer say "I don't know" or "I have no information about this" in your own words.

context```
{$context}
```

$currentDateTime
EOD;

        $systemPrompt = Conversation::findOrFail(1);
        $systemPrompt->answer = $prompt;
        $systemPrompt->save();
    }

    public static function getConversationsLastFiveMinutes()
    {
        $currentTime = Carbon::now();
        $startTime = $currentTime->subMinutes(5);

        $conversations = self::where('created_at', '>', $startTime)
            ->orWhere('system_prompt', '=', 1)
            ->get();

        $messages = [];
        foreach ($conversations as $conversation) {
            if ($conversation->question === 'SYSTEM') {
                $messages[] = [
                    'role' => 'system',
                    'content' => $conversation->answer
                ];
                continue;
            }
            $messages[] = [
                'role' => 'user',
                'content' => $conversation->question
            ];

            if ($conversation->answer) {
                $messages[] = [
                    'role' => 'assistant',
                    'content' => $conversation->answer
                ];
            }
        }
        return $messages;
    }

    public function saveQuestion(string $question): void
    {
        $this->question = $question;
        $this->save();
    }

    public function saveAnswer(string $answer): void
    {
        $this->answer = $answer;
        $this->save();
    }

}
