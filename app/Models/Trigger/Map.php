<?php

namespace App\Models\Trigger;

use App\Models\Account\User;
use App\Models\Marketing\Campaign;
use App\Models\Marketing\Project;
use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Map
 *
 * @package App\Models\Trigger
 * @property string               $id
 * @property string               $user_id
 * @property string               $project_id
 * @property string               $name
 * @property string               $desc
 * @property boolean              $is_enabled
 * @property Carbon               $refreshed_at
 * @property Carbon               $changed_at
 * @property integer              $shutdown_delay
 * @property Carbon               $shutdown_in
 * @property Carbon               $created_at
 * @property Carbon               $updated_at
 * @property User                 $user
 * @property Project              $project
 * @property Collection<Campaign> $campaigns
 * @property Collection<Group>    $groups
 * @property-read int|null $campaigns_count
 * @property-read int|null $groups_count
 * @property-read Collection|\App\Models\Trigger\History[] $histories
 * @property-read int|null $histories_count
 * @method static \Illuminate\Database\Eloquent\Builder|Map newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Map newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Map query()
 * @method static \Illuminate\Database\Eloquent\Builder|Map whereChangedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Map whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Map whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Map whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Map whereIsEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Map whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Map whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Map whereRefreshedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Map whereShutdownDelay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Map whereShutdownIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Map whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Map whereUserId($value)
 * @mixin \Eloquent
 */
class Map extends Model
{
    use HasFactory, UuidTrait;

    protected $table = 'trigger_maps';

    protected $fillable = [
        'user_id',
        'project_id',
        'name',
        'desc',
        'is_enabled',
        'refreshed_at',
        'changed_at',
        'shutdown_delay',
        'shutdown_in',
    ];

    protected $dates = [
        'refreshed_at',
        'changed_at',
        'shutdown_in',
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
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * @return HasMany
     */
    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'map_id');
    }

    /**
     * @return HasMany
     */
    public function groups(): HasMany
    {
        return $this->hasMany(Group::class, 'map_id');
    }

    /**
     * @return HasMany
     */
    public function histories(): HasMany
    {
        return $this->hasMany(History::class, 'map_id');
    }
}
