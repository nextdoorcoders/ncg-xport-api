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
 * @property string  $account_id
 * @property string  $project_id
 * @property string  $campaign_id
 * @property Carbon  $start_at
 * @property Carbon  $end_at
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 * @property Account $account
 * @property Project $project
 */
class Campaign extends Model
{
    use UuidTrait;

    protected $table = 'marketing_campaigns';

    protected $fillable = [
        'account_id',
        'project_id',
        'campaign_id',
        'start_at',
        'end_at',
    ];

    protected $dates = [
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
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
