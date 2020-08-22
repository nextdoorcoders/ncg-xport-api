<?php

namespace App\Models\Marketing;

use App\Models\Account\User;
use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Campaign
 *
 * @package App\Models\Marketing
 * @property string              $id
 * @property string              $user_id
 * @property string              $name
 * @property string              $desc
 * @property Carbon              $created_at
 * @property Carbon              $updated_at
 * @property User                $user
 * @property Collection<Account> $accounts
 * @property Collection<Group>   $groups
 */
class Project extends Model
{
    use UuidTrait;

    protected $table = 'marketing_projects';

    protected $fillable = [
        'user_id',
        'name',
        'desc',
    ];

    /*
     * Relations
     */

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return HasMany
     */
    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'campaign_id');
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
