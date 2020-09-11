<?php

namespace App\Models\Marketing;

use App\Casts\Encrypt;
use App\Models\Account\SocialAccount;
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
 * @property string               $social_account_id
 * @property string               $organization_id
 * @property string               $map_id
 * @property string               $name
 * @property string               $desc
 * @property array                $parameters
 * @property Carbon               $date_start_at
 * @property Carbon               $date_end_at
 * @property Carbon               $created_at
 * @property Carbon               $updated_at
 * @property Map                  $map
 * @property SocialAccount        $socialAccount
 * @property Organization         $organization
 * @property Collection<Campaign> $campaigns
 */
class Project extends Model
{
    use UuidTrait;

    protected $table = 'marketing_projects';

    protected $fillable = [
        'social_account_id',
        'organization_id',
        'map_id',
        'name',
        'desc',
        'parameters',
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
    public function socialAccount(): BelongsTo
    {
        return $this->belongsTo(SocialAccount::class, 'social_account_id');
    }

    /**
     * @return BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    /**
     * @return HasMany
     */
    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'account_id');
    }

    /**
     * @return BelongsTo
     */
    public function map(): BelongsTo
    {
        return $this->belongsTo(Map::class, 'map_id');
    }
}
