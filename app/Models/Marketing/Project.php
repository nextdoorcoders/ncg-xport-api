<?php

namespace App\Models\Marketing;

use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Project
 *
 * @package App\Models\Marketing
 * @property string            $id
 * @property string            $campaign_id
 * @property string            $name
 * @property Carbon            $start_at
 * @property Carbon            $end_at
 * @property Carbon            $created_at
 * @property Carbon            $updated_at
 * @property Campaign          $campaign
 * @property Collection<Group> $groups
 */
class Project extends Model
{
    use UuidTrait;

    protected $table = 'marketing_projects';

    /*
     * Relations
     */

    /**
     * @return BelongsTo
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    /**
     * @return HasMany
     */
    public function groups(): HasMany
    {
        return $this->hasMany(Group::class, 'project_id');
    }
}
