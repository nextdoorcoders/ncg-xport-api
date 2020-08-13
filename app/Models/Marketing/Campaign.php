<?php

namespace App\Models\Marketing;

use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Campaign
 *
 * @package App\Models\Marketing
 * @property string            $id
 * @property string            $account_id
 * @property string            $name
 * @property string            $desc
 * @property Carbon            $start_at
 * @property Carbon            $end_at
 * @property Carbon            $created_at
 * @property Carbon            $updated_at
 * @property Account           $account
 * @property Collection<Group> $groups
 */
class Campaign extends Model
{
    use UuidTrait;

    protected $table = 'marketing_campaigns';

    protected $fillable = [
        'name',
        'desc',
        'start_at',
        'end_at',
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
    public function groups(): HasMany
    {
        return $this->hasMany(Group::class, 'campaign_id');
    }
}
