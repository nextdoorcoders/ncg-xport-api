<?php

namespace App\Models\Account;

use App\Models\File;
use App\Models\Geo\Country;
use App\Models\Marketing\Account;
use App\Models\Marketing\Project;
use App\Models\Traits\UserTrait;
use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 *
 * @package App\Models\Account
 * @property string                    $id
 * @property string                    $country_id
 * @property string                    $language_id
 * @property string                    $name
 * @property string                    $email
 * @property string                    $password
 * @property Carbon                    $created_at
 * @property Carbon                    $updated_at
 * @property Carbon                    $last_login_at
 * @property Carbon                    $last_seen_at
 * @property boolean                   $is_online
 * @property Collection                $country
 * @property Language                  $language
 * @property Collection<Contact>       $contacts
 * @property Collection<SocialAccount> $socialAccounts
 * @property Collection<Project>       $ownerProjects
 * @property Collection<Project>       $clientProjects
 * @property File                      $picture
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable, UserTrait, UuidTrait;

    protected $table = 'account_users';

    protected $fillable = [
        'country_id',
        'language_id',
        'name',
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
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
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
    public function socialAccounts(): HasMany
    {
        return $this->hasMany(SocialAccount::class, 'user_id');
    }

    /**
     * @return HasManyThrough
     */
    public function accounts(): HasManyThrough
    {
        return $this->hasManyThrough(Account::class, SocialAccount::class, 'user_id', 'social_account_id');
    }

    /**
     * @return HasMany
     */
    public function ownerProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'owner_user_id');
    }

    /**
     * @return HasMany
     */
    public function clientProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'client_user_id');
    }

    /**
     * @return MorphOne
     */
    public function picture(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable')
            ->where('field', 'picture');
    }
}
