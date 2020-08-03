<?php

namespace App\Models\Account;

use App\Models\File;
use App\Models\Geo\Country;
use App\Models\Marketing\Company;
use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 *
 * @package App\Models\Account
 * @property string  $id
 * @property string  $country_id
 * @property string  $name
 * @property string  $language
 * @property string  $email
 * @property string  $password
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 * @property Carbon  $last_login_at
 * @property Carbon  $last_seen_at
 * @property boolean $is_online
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable, UuidTrait;

    const PASSWORD_REGEX = '/(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/';
    const LAST_SEEN_INTERVAL = 15;

    protected $table = 'account_users';

    protected $fillable = [
        'country_id',
        'name',
        'language',
        'email',
        'password',
        'last_login_at',
        'last_seen_at',
    ];

    protected $hidden = [
        'password',
    ];

    protected $appends = [
        'is_online',
    ];

    /*
     * Relations
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contacts()
    {
        return $this->hasMany(Contact::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function companies()
    {
        return $this->hasManyThrough(Company::class, SocialAccount::class, 'user_id', 'social_account_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function picture()
    {
        return $this->morphOne(File::class, 'fileable')
            ->where('field', 'picture');
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
        return $this->last_seen_at ? $this->last_seen_at->diffInMinutes() < self::LAST_SEEN_INTERVAL : false;
    }

    /*
     * Functions
     */

    /**
     * @return string
     */
    public static function getRandomPassword()
    {
        do {
            preg_match(self::PASSWORD_REGEX, Str::random(8), $password);
        } while (empty($password));

        return $password[0];
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
        if ($this->last_seen_at == null || $this->last_seen_at->diffInMinutes() > self::LAST_SEEN_INTERVAL || $this->last_seen_at < $this->last_login_at) {
            $this->last_seen_at = now();
            $this->save();
        }
    }
}
