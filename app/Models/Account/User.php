<?php

namespace App\Models\Account;

use App\Models\Geo\Location;
use App\Models\Marketing\Account;
use App\Models\Marketing\Campaign;
use App\Models\Marketing\Organization;
use App\Models\Marketing\Project;
use App\Models\Storage;
use App\Models\Traits\UserTrait;
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
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 *
 * @package App\Models\Account
 * @property string                   $id
 * @property string                   $location_id
 * @property string                   $language_id
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
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, UserTrait, UuidTrait;

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
}
