<?php

namespace App\Models;

use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * Class Token
 *
 * @package App\Models\Account
 * @property string $id
 * @property string $tokenable_type
 * @property string $tokenable_id
 * @property string $name
 * @property string $token
 * @property array  $abilities
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $last_used_at
 * @property Model  $tokenable
 */
class Token extends PersonalAccessToken
{
    use UuidTrait;

    protected $table = 'morph_tokens';
}
