<?php

namespace App\Models\Marketing;

use App\Models\Account\User;
use App\Models\Traits\UuidTrait;
use App\Models\Trigger\Map;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
 * @property string               $user_id
 * @property string               $name
 * @property string               $desc
 * @property object               $parameters
 * @property boolean              $is_enabled
 * @property Carbon               $date_start_at
 * @property Carbon               $date_end_at
 * @property Carbon               $created_at
 * @property Carbon               $updated_at
 * @property Account              $account
 * @property Organization         $organization
 * @property User                 $user
 * @property Collection<Campaign> $campaigns
 * @property Collection<Map>      $maps
 */
class Project extends Model
{
    use HasFactory, UuidTrait;

    protected $table = 'marketing_projects';

    protected $fillable = [
        'account_id',
        'organization_id',
        'user_id',
        'name',
        'desc',
        'parameters',
        'is_enabled',
        'date_start_at',
        'date_end_at',
    ];

    protected $dates = [
        'date_start_at',
        'date_end_at',
    ];

    protected $casts = [
        'parameters' => 'encrypted:object',
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
     * @return HasMany
     */
    public function maps(): HasMany
    {
        return $this->hasMany(Map::class, 'project_id');
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
