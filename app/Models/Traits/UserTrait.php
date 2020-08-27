<?php

namespace App\Models\Traits;

use App\Casts\Encrypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

trait UserTrait
{
    private static string $passwordRegex = '/(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/';

    private static int $lastSeenInterval = 15;

    protected function initializeUserTrait(): void
    {
        $this->dates = [
            'last_login_at',
            'last_seen_at',
        ];

        $this->mergeCasts([
            'password_reset_code' => Encrypt::class,
        ]);
    }

    /*
     * Mutators
     */

    /**
     * @param $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    /*
     * Accessors
     */

    /**
     * @return bool
     */
    public function getIsOnlineAttribute()
    {
        return $this->last_seen_at ? $this->last_seen_at->diffInMinutes() < self::$lastSeenInterval : false;
    }

    /*
     * Functions
     */

    /**
     * @return string
     */
    public static function getRandomPassword(): string
    {
        do {
            preg_match(self::$passwordRegex, Str::random(8), $password);
        } while (empty($password));

        return $password[0];
    }

    /**
     * @return string
     */
    public static function getPasswordResetCode(): string
    {
        return Str::random(8);
    }

    /**
     * Touch last login timestamp
     */
    public function touchLastLogin()
    {
        $this->last_login_at = now();
        $this->last_seen_at = now();
        $this->save();
    }

    /**
     * Touch last seen timestamp
     */
    public function touchLastSeen()
    {
        if ($this->last_seen_at == null || $this->last_seen_at->diffInMinutes() > self::$lastSeenInterval || $this->last_seen_at < $this->last_login_at) {
            $this->last_seen_at = now();
            $this->save();
        }
    }
}
