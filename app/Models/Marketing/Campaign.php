<?php

namespace App\Models\Marketing;

use App\Models\Traits\UuidTrait;
use App\Models\Trigger\Map;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Campaign
 *
 * @package App\Models\Marketing
 * @property string  $id
 * @property string  $map_id
 * @property string  $foreign_campaign_id
 * @property string  $name
 * @property boolean $is_enabled
 * @property boolean $is_rate_enabled
 * @property string  $rate_min
 * @property string  $rate_max
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 * @property Map     $map
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign query()
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereForeignCampaignId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereIsEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereIsRateEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereMapId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereRateMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereRateMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Campaign extends Model
{
    use HasFactory, UuidTrait;

    protected $table = 'marketing_campaigns';

    protected $fillable = [
        'map_id',
        'foreign_campaign_id',
        'name',
        'is_enabled',
        'is_rate_enabled',
        'rate_min',
        'rate_max',
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
