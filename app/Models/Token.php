<?php

namespace App\Models;

use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Contracts\HasAbilities;

/**
 * Class Token
 *
 * @package App\Models\Account
 * @property string $id
 * @property string $tokenable_type
 * @property string $tokenable_id
 * @property string $ip
 * @property string $agent
 * @property array  $abilities
 * @property string $token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $last_used_at
 * @property Model  $tokenable
 * @method static \Illuminate\Database\Eloquent\Builder|Token newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Token newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Token query()
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereAbilities($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereLastUsedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereTokenableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereTokenableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Token extends Model implements HasAbilities
{
    use HasFactory, UuidTrait;

    protected $table = 'morph_tokens';

    protected $casts = [
        'abilities'    => 'json',
        'last_used_at' => 'datetime',
    ];

    protected $fillable = [
        'abilities',
        'ip',
        'agent',
        'token',
    ];

    protected $hidden = [
        'token',
    ];

    /*
     * Relations
     */

    /**
     * Get the tokenable model that the access token belongs to.
     *
     * @return MorphTo
     */
    public function tokenable(): MorphTo
    {
        return $this->morphTo('tokenable');
    }

    /*
     * Static functions
     */

    /**
     * Find the token instance matching the given token.
     *
     * @param string $token
     * @return static
     */
    public static function findToken($token)
    {
        try {
            [$id, $token] = explode('|', $token, 2);

            /** @var self $instance */
            $instance = static::query()
                ->where('id', $id)
                ->first();

            if ($instance) {
                return hash_equals($instance->token, hash('sha256', $token)) ? $instance : null;
            }
        } catch (Exception $exception) {
            Log::error($exception);
        }

        return null;
    }

    /*
     * Methods
     */

    /**
     * Determine if the token has a given ability.
     *
     * @param string $ability
     * @return bool
     */
    public function can($ability)
    {
        return in_array('*', $this->abilities) ||
            array_key_exists($ability, array_flip($this->abilities));
    }

    /**
     * Determine if the token is missing a given ability.
     *
     * @param string $ability
     * @return bool
     */
    public function cant($ability)
    {
        return !$this->can($ability);
    }
}
