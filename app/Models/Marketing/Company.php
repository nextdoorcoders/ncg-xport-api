<?php

namespace App\Models\Marketing;

use App\Models\Account\SocialAccount;
use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Company
 *
 * @package App\Models\Marketing
 * @property string              $id
 * @property string              $social_account_id
 * @property string              $name
 * @property string              $desc
 * @property string              $parameters
 * @property Carbon              $created_at
 * @property Carbon              $updated_at
 * @property SocialAccount       $socialAccount
 * @property Collection<Project> $projects
 */
class Company extends Model
{
    use UuidTrait;

    protected $table = 'marketing_companies';

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
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'company_id');
    }
}
