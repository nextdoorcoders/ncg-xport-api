<?php

namespace App\Models\Marketing;

use App\Models\Traits\UuidTrait;
use App\Models\Trigger\Map;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Campaign
 *
 * @package App\Models\Marketing
 * @property string $id
 * @property string $map_id
 * @property string $foreign_campaign_id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Map    $map
 */
class Campaign extends Model
{
    use UuidTrait;

    protected $table = 'marketing_campaigns';

    protected $fillable = [
        'map_id',
        'foreign_campaign_id',
        'name',
    ];

    /*
     * Relations
     */

    /**
     * @return BelongsTo
     */
    public function map(): BelongsTo
    {
        return $this->belongsTo(Map::class, 'map_id');
    }
}
