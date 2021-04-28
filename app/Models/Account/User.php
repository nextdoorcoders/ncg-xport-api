<?php

namespace App\Models\Account;

use App\Casts\Encrypt;
use App\Models\Access\Permission;
use App\Models\Access\Role;
use App\Models\Geo\Location;
use App\Models\Marketing\Account;
use App\Models\Marketing\Campaign;
use App\Models\Marketing\Organization;
use App\Models\Marketing\Project;
use App\Models\Storage;
use App\Models\Traits\UuidTrait;
use App\Models\Trigger\Map;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User
 *
 * @package App\Models\Account
 * @property string                   $id
 * @property string                   $location_id
 * @property string                   $language_id
 * @property string                   $name
 * @property string                   $email
 * @property string                   $password
 * @property string                   $password_reset_code
 * @property Carbon                   $last_login_at
 * @property Carbon                   $last_seen_at
 * @property boolean                  $is_online
 * @property Carbon                   $created_at
 * @property Carbon                   $updated_at
 * @property Location                 $location
 * @property Language                 $language
 * @property Collection<Contact>      $contacts
 * @property Collection<Account>      $accounts
 * @property Collection<Project>      $projects
 * @property Collection<Map>          $maps
 * @property Collection<Organization> $organizations
 * @property Storage                  $picture
 * @property Collection<Permission>   $permissions
 * @property Collection<Role>         $roles
 * @property-read int|null $accounts_count
 * @property-read Collection|Campaign[] $campaigns
 * @property-read int|null $campaigns_count
 * @property-read int|null $contacts_count
 * @property-read int|null $maps_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read int|null $organizations_count
 * @property-read int|null $permissions_count
 * @property-read int|null $projects_count
 * @property-read int|null $roles_count
 * @property-read Collection|\App\Models\Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastSeenAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePasswordResetCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasRoles, HasApiTokens, HasFactory, Notifiable, UuidTrait;

    private static string $passwordRegex = '/(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/';

    private static int $lastSeenInterval = 15;

    protected $table = 'account_users';

    protected $fillable = [
        'location_id',
        'language_id',
        'role',
        'name',
        'email',
        'password',
        'password_reset_code',
        'last_login_at',
        'last_seen_at',
    ];

    protected $dates = [
        'last_login_at',
        'last_seen_at',
    ];

    protected $casts = [
        'password_reset_code' => Encrypt::class,
    ];

    protected $hidden = [
        'password',
        'password_reset_code',
    ];

    protected $appends = [
        'is_online',
    ];

    /*
     * Relations
     */

    /**
     * @return BelongsTo
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    /**
     * @return BelongsTo
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    /**
     * @return HasMany
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class, 'user_id');
    }

    /**
     * @return HasMany
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class, 'user_id');
    }

    /**
     * @return HasMany
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'user_id');
    }

    /**
     * @return HasMany
     */
    public function maps(): HasMany
    {
        return $this->hasMany(Map::class, 'user_id');
    }

    /**
     * @return HasManyThrough
     */
    public function campaigns(): HasManyThrough
    {
        return $this->hasManyThrough(Campaign::class, Map::class, 'user_id', 'map_id');
    }

    /**
     * @return HasMany
     */
    public function organizations(): HasMany
    {
        return $this->hasMany(Organization::class, 'user_id');
    }

    /**
     * @return MorphOne
     */
    public function picture(): MorphOne
    {
        return $this->morphOne(Storage::class, 'fileable')
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
