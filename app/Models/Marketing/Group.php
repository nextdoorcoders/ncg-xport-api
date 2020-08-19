<?php

namespace App\Models\Marketing;

use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Group
 *
 * @package App\Models\Marketing
 * @property string                $id
 * @property string                $project_id
 * @property string                $name
 * @property string                $desc
 * @property Carbon                $created_at
 * @property Carbon                $updated_at
 * @property Project               $project
 * @property Collection<Condition> $conditions
 */
class Group extends Model
{
    use UuidTrait;

    protected $table = 'marketing_groups';

    protected $fillable = [
        'name',
        'desc',
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

    /**
     * @return HasMany
     */
    public function conditions(): HasMany
    {
        return $this->hasMany(Condition::class, 'group_id');
    }
}
