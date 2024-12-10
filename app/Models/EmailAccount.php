<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

/**
 * @method static create(array $array)
 * @method static where(string $column, string $value)
 */
class EmailAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'hostname',
        'username',
        'password',
    ];

    public function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = Crypt::encryptString($value);
    }

    public function getPasswordAttribute($value): string
    {
        return Crypt::decryptString($value);
    }

    public static function getModel(string $address): self
    {
        return self::where('username', $address)->first();
    }
}
