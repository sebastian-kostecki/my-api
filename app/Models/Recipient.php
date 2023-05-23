<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * @method static where(string $string, string $type)
 * @property mixed|string $name
 * @property mixed|string $email
 * @property mixed|string $type
 */
class Recipient extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'type',
    ];
}
