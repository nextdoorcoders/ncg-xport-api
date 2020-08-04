<?php

namespace App\Models\Account;

use App\Models\Traits\UserTrait;
use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Worker
 *
 * @package App\Models\Account
 * @property string $id
 * @property string $name
 * @property string $role
 * @property string $email
 * @property string $password
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $last_login_at
 * @property Carbon $last_seen_at
 */
class Worker extends Model
{
    use UserTrait, UuidTrait;

    const ROLE_ADMIN = 'admin';
    const ROLE_MODERATOR = 'moderator';

    const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_MODERATOR,
    ];

    protected $table = 'account_workers';

    protected $fillable = [
        'name',
        'role',
        'email',
        'password',
        'last_login_at',
        'last_seen_at',
    ];

    protected $hidden = [
        'password',
    ];

    protected $appends = [
        'is_online',
    ];
}
