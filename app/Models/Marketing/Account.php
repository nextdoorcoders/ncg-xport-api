<?php

namespace App\Models\Marketing;

use App\Casts\Encrypt;
use App\Models\Account\SocialAccount;
use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Project
 *
 * @package App\Models\Marketing
 * @property string               $id
 * @property string               $social_account_id
 * @property string               $project_id
 * @property string               $campaign_id
 * @property string               $name
 * @property array                $parameters
 * @property Carbon               $created_at
 * @property Carbon               $updated_at
 * @property SocialAccount        $socialAccount
 * @property Collection<Campaign> $campaigns
 * @property Collection<Project>  $projects
 */
class Account extends Model
{
    use UuidTrait;

    protected $table = 'marketing_accounts';

    protected $fillable = [
        'social_account_id',
        'project_id',
        'campaign_id',
        'name',
        'parameters',
    ];

    protected $casts = [
        'parameters' => Encrypt::class,
    ];

    /*
     * Relations
     */

    /**
     * @return BelongsTo
     */
    public function socialAccount(): BelongsTo
    {
        return $this->belongsTo(SocialAccount::class, 'social_account_id');
    }

    /**
     * @return HasMany
     */
    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'account_id');
    }

    /**
     * @return BelongsToMany
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'marketing_campaigns', 'account_id', 'project_id')
            ->using(Campaign::class);
    }
}
