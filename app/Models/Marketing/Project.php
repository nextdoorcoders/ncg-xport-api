<?php

namespace App\Models\Marketing;

use App\Casts\Encrypt;
use App\Models\Account\User;
use App\Models\Traits\UuidTrait;
use App\Models\Trigger\Map;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Project
 *
 * @package App\Models\Marketing
 * @property string               $id
 * @property string               $account_id
 * @property string               $organization_id
 * @property string               $map_id
 * @property string               $user_id
 * @property string               $name
 * @property string               $desc
 * @property array                $parameters
 * @property Carbon               $date_start_at
 * @property Carbon               $date_end_at
 * @property Carbon               $created_at
 * @property Carbon               $updated_at
 * @property Account              $account
 * @property Map                  $map
 * @property Organization         $organization
 * @property User                 $user
 * @property Collection<Campaign> $campaigns
 */
class Project extends Model
{
    use UuidTrait;

    protected $table = 'marketing_projects';

    protected $fillable = [
        'account_id',
        'organization_id',
        'map_id',
        'user_id',
        'name',
        'desc',
        'parameters',
        'date_start_at',
        'date_end_at',
    ];

    protected $dates = [
        'date_start_at',
        'date_end_at',
    ];

    protected $casts = [
        'parameters' => Encrypt::class,
    ];

    /*
     * Relations
     */

    /**
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    /**
     * @return BelongsTo
     */
    public function map(): BelongsTo
    {
        return $this->belongsTo(Map::class, 'map_id');
    }

    /**
     * @return BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

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
        return $this->hasMany(Campaign::class, 'project_id');
    }
}
