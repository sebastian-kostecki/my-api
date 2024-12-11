<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $thread_id
 * @property string $text
 * @property string $role
 * @property Thread $thread
 * @property int $id
 *
 * @method static create(array $array)
 */
class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'thread_id',
        'role',
        'text',
        'status',
        'details',
        'completed_at',
    ];

    protected $casts = [
        'details' => 'array',
        'completed_at' => 'datetime',
    ];

    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }
}
