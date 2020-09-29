<?php

namespace App\Models\Marketing;

use App\Casts\Encrypt;
use App\Models\Account\User;
use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Account
 *
 * @package App\Models\Account
 * @property string              $id
 * @property string              $user_id
 * @property string              $provider_id
 * @property string              $provider_name
 * @property string              $email
 * @property string              $access_token
 * @property string              $refresh_token
 * @property Carbon              $created_at
 * @property Carbon              $updated_at
 * @property User                $user
 * @property Collection<Project> $projects
 */
class Account extends Model
{
    use UuidTrait;

    const PROVIDER_NAME_GOOGLE = 'google';
    const PROVIDER_NAME_FACEBOOK = 'facebook';

    protected $table = 'marketing_accounts';

    protected $fillable = [
        'user_id',
        'provider_id',
        'provider_name',
        'email',
        'access_token',
        'refresh_token',
    ];

    protected $hidden = [
        'provider_id',
        'access_token',
        'refresh_token',
    ];

    protected $casts = [
        'access_token'  => Encrypt::class,
        'refresh_token' => Encrypt::class,
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
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'account_id');
    }
}
