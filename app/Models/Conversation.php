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
 */
class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'answer'
    ];

    public static function updateSystemPrompt(): void
    {
        $systemPrompt = self::findOrFail(1);
        $systemPrompt->answer = "You are helpful assistant.\n\n" . Carbon::now();
        $systemPrompt->save();
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
