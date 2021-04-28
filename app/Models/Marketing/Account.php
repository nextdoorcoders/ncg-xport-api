<?php

namespace App\Models\Marketing;

use App\Casts\Encrypt;
use App\Models\Account\User;
use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
 * @property-read int|null $projects_count
 * @method static \Illuminate\Database\Eloquent\Builder|Account newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Account newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Account query()
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereProviderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereUserId($value)
 * @mixin \Eloquent
 */
class Account extends Model
{
    use HasFactory, UuidTrait;

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
