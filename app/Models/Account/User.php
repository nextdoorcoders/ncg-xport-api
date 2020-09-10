<?php

namespace App\Models\Account;

use App\Models\File;
use App\Models\Geo\Location;
use App\Models\Marketing\Organization;
use App\Models\Marketing\Project;
use App\Models\Traits\UserTrait;
use App\Models\Traits\UuidTrait;
use App\Models\Trigger\Map;
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
 * @property string                    $location_id
 * @property string                    $language_id
 * @property Carbon                    $created_at
 * @property Carbon                    $updated_at
 * @property Location                  $location
 * @property Language                  $language
 * @property Collection<Contact>       $contacts
 * @property Collection<SocialAccount> $socialAccounts
 * @property Collection<Project>       $projects
 * @property Collection<Map>           $maps
 * @property Collection<Organization>  $organizations
 * @property File                      $picture
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable, UserTrait, UuidTrait;

    protected $table = 'account_users';

    protected $fillable = [
        'location_id',
        'language_id',
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
    public function socialAccounts(): HasMany
    {
        return $this->hasMany(SocialAccount::class, 'user_id');
    }

    /**
     * @return HasManyThrough
     */
    public function projects(): HasManyThrough
    {
        return $this->hasManyThrough(Project::class, SocialAccount::class, 'user_id', 'social_account_id');
    }

    /**
     * @return HasMany
     */
    public function maps(): HasMany
    {
        return $this->hasMany(Map::class, 'user_id');
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
        return $this->morphOne(File::class, 'fileable')
            ->where('field', 'picture');
    }
}
