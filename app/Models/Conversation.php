<?php

namespace App\Models;

use Carbon\Carbon;
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
    protected string $systemPrompt = "";
    protected $fillable = [
        'question',
        'answer'
    ];
    
    /**
     * @param string $prompt
     * @return $this
     */
    public function setSystemPrompt(string $prompt): static
    {
        $this->systemPrompt = $prompt;
        return $this;
    }

    public function updateSystemPrompt(array $resources = []): void
    {
        if (!$this->systemPrompt) {
            $context = implode("\n", $resources);
            $currentDateTime = Carbon::now()->format('Y-m-d H:i');
            $this->systemPrompt = "You're a helpful assistant. Answer questions as short and concise and as truthfully as possible, based on a context.\n\n"
                . "Please note that context below may include:\n"
                . "- long-term memory,\n"
                . "- actions you may take,\n"
                . "- user's personal notes and/or links you do have access to.\n\n"
                . "And you should prioritize this knowledge while answering.\n\n"
                . "If you don't know the answer say \"I don't know\" or \"I have no information about this\" in your own words.\n\n"
                . "context```\n" . $context . "\n````\n\n" . $currentDateTime;

        }

        $systemPrompt = self::findOrFail(1);
        $systemPrompt->answer = $this->systemPrompt;
        $systemPrompt->save();
    }

    /**
     * @return array
     */
    public static function getConversationsLastFiveMinutes(): array
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

    /**
     * @param string $question
     * @return void
     */
    public function saveQuestion(string $question): void
    {
        $this->question = $question;
        $this->save();
    }

    /**
     * @param string $answer
     * @return void
     */
    public function saveAnswer(string $answer): void
    {
        $this->answer = $answer;
        $this->save();
    }
}
