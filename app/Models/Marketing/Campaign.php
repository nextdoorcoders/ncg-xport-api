<?php

namespace App\Models\Marketing;

use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Campaign
 *
 * @package App\Models\Marketing
 * @property string  $id
 * @property string  $project_id
 * @property string  $campaign_id
 * @property string  $name
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 * @property Project $project
 */
class Campaign extends Model
{
    use UuidTrait;

    protected $table = 'marketing_campaigns';

    protected $fillable = [
        'project_id',
        'campaign_id',
        'name',
    ];

    /*
     * Relations
     */

    /**
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
