<?php

namespace App\Models;

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
        'text'
    ];
}
