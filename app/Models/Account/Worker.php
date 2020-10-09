<?php

namespace App\Models\Account;

use App\Models\Traits\UserTrait;
use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Worker
 *
 * @package App\Models\Account
 * @property string $id
 * @property string $role
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Worker extends Model
{
    use HasFactory, UserTrait, UuidTrait;

    const ROLE_ADMIN = 'admin';
    const ROLE_MODERATOR = 'moderator';

    const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_MODERATOR,
    ];

    protected $table = 'account_workers';

    protected $fillable = [
        'role',
    ];
}
