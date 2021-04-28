<?php

namespace App\Models\Trigger;

use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class History
 *
 * @package App\Models\Trigger
 * @property string  $id
 * @property string  $map_id
 * @property boolean $is_enabled
 * @property string  $imprint
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 * @property-read \App\Models\Trigger\Map $map
 * @method static \Illuminate\Database\Eloquent\Builder|History newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|History newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|History query()
 * @method static \Illuminate\Database\Eloquent\Builder|History whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|History whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|History whereImprint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|History whereIsEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|History whereMapId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|History whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class History extends Model
{
    use HasFactory, UuidTrait;

    protected $table = 'trigger_maps_histories';

    protected $fillable = [
        'map_id',
        'is_enabled',
        'imprint',
    ];

    protected $casts = [
        'imprint' => 'json',
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
