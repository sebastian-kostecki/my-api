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
        'password'
    ];

    /**
     * @param $value
     * @return void
     */
    public function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = Crypt::encryptString($value);
    }

    /**
     * @param $value
     * @return string
     */
    public function getPasswordAttribute($value): string
    {
        return Crypt::decryptString($value);
    }

    /**
     * @param string $address
     * @return self
     */
    public static function getModel(string $address): self
    {
        return self::where('username', $address)->first();
    }
}
