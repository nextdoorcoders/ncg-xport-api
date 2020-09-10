<?php

namespace App\Models\Trigger;

use App\Models\Account\User;
use App\Models\Marketing\Project;
use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Project
 *
 * @package App\Models\Trigger
 * @property string            $id
 * @property string            $user_id
 * @property string            $name
 * @property string            $desc
 * @property boolean           $is_enabled
 * @property Carbon            $refreshed_at
 * @property Carbon            $changed_at
 * @property Carbon            $created_at
 * @property Carbon            $updated_at
 * @property User              $user
 * @property Collection<Group> $groups
 */
class Map extends Model
{
    use UuidTrait;

    protected $table = 'trigger_maps';

    protected $fillable = [
        'user_id',
        'name',
        'desc',
        'is_enabled',
        'refreshed_at',
        'changed_at',
    ];

    protected $dates = [
        'refreshed_at',
        'changed_at',
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
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'map_id');
    }

    /**
     * @return HasMany
     */
    public function groups(): HasMany
    {
        return $this->hasMany(Group::class, 'map_id');
    }
}
