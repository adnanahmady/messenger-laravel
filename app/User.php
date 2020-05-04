<?php

namespace App;

use App\Traits\TokenKey;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, TokenKey;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'token_key', 'otp', 'otp_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'otp', 'otp_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'otp_at' => 'datetime',
    ];

    public function sends()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receives()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public static function otp($length = 4)
    {
        do {
            $otp = substr_replace(
                str_repeat('0', $length),
                $pureOtp = rand(1, str_repeat('9', $length)),
                $length - strlen($pureOtp)
            );
        } while (!! User::whereOtp($otp)->first('id'));

        return $otp;
    }

    public function verify($token)
    {
        $this->update([
            'otp' => null,
            'token_key' => $this->tokenKey($token)
        ]);
    }

    public function scopeKeyExists($query, $token)
    {
        return $query->where([
            'token_key' => $this->tokenKey($token)
        ])
            ->exists();
    }
}
