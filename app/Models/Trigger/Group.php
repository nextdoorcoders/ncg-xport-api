<?php

namespace App\Models\Trigger;

use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Group
 *
 * @package App\Models\Trigger
 * @property string                $id
 * @property string                $map_id
 * @property string                $name
 * @property string                $desc
 * @property integer               $order_index
 * @property boolean               $is_enabled
 * @property Carbon                $refreshed_at
 * @property Carbon                $changed_at
 * @property Carbon                $created_at
 * @property Carbon                $updated_at
 * @property Map                   $map
 * @property Collection<Condition> $conditions
 * @property-read int|null $conditions_count
 * @method static \Illuminate\Database\Eloquent\Builder|Group newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group query()
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereChangedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereIsEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereMapId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereOrderIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereRefreshedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Group extends Model
{
    use HasFactory, UuidTrait;

    protected $table = 'trigger_groups';

    protected $fillable = [
        'map_id',
        'name',
        'desc',
        'order_index',
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
    public function map(): BelongsTo
    {
        return $this->belongsTo(Map::class, 'map_id');
    }

    /**
     * @return HasMany
     */
    public function conditions(): HasMany
    {
        return $this->hasMany(Condition::class, 'group_id');
    }
}
