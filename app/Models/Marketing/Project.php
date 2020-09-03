<?php

namespace App\Models\Marketing;

use App\Models\Account\User;
use App\Models\Geo\City;
use App\Models\Traits\TriggerTrait;
use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Project
 *
 * @package App\Models\Marketing
 * @property string               $id
 * @property string               $city_id
 * @property string               $owner_user_id
 * @property string               $client_user_id
 * @property string               $name
 * @property string               $desc
 * @property Carbon               $date_start_at
 * @property Carbon               $date_end_at
 * @property Carbon               $created_at
 * @property Carbon               $updated_at
 * @property City                 $city
 * @property User                 $ownerUser
 * @property User                 $clientUer
 * @property Collection<Account>  $accounts
 * @property Collection<Campaign> $campaigns
 * @property Collection<Group>    $groups
 */
class Project extends Model
{
    use TriggerTrait, UuidTrait;

    protected $table = 'marketing_projects';

    protected $fillable = [
        'city_id',
        'owner_user_id',
        'client_user_id',
        'name',
        'desc',
        'date_start_at',
        'date_end_at',
    ];

    protected $dates = [
        'date_start_at',
        'date_end_at',
    ];

    /*
     * Relations
     */

    /**
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    /**
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    /**
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_user_id');
    }

    /**
     * @return HasMany
     */
    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'project_id');
    }

    /**
     * @return BelongsToMany
     */
    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(Account::class, 'marketing_campaigns', 'project_id', 'account_id')
            ->using(Campaign::class);
    }

    /**
     * @return HasMany
     */
    public function groups(): HasMany
    {
        return $this->hasMany(Group::class, 'project_id');
    }
}
