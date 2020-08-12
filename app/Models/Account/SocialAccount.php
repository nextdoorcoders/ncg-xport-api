<?php

namespace App\Models\Account;

use App\Models\Marketing\Campaign;
use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class SocialAccount
 *
 * @package App\Models\Account
 * @property string               $id
 * @property string               $user_id
 * @property string               $provider_id
 * @property string               $provider_name
 * @property string               $email
 * @property string               $access_token
 * @property string               $refresh_token
 * @property Carbon               $created_at
 * @property Carbon               $updated_at
 * @property Carbon               $last_login_at
 * @property User                 $user
 * @property Collection<Campaign> $campaigns
 */
class SocialAccount extends Model
{
    use UuidTrait;

    const PROVIDER_NAME_GOOGLE = 'google';
    const PROVIDER_NAME_FACEBOOK = 'facebook';

    protected $table = 'account_socials';

    protected $fillable = [
        'user_id',
        'provider_id',
        'provider_name',
        'email',
        'access_token',
        'refresh_token',
        'last_login_at',
    ];

    protected $hidden = [
        'provider_id',
        'access_token',
        'refresh_token',
    ];

    /*
     * Relations
     */

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return HasMany
     */
    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'social_account_id');
    }
}
