<?php

namespace App\Models\Access;

use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as PermissionBase;

/**
 * Class Permission
 *
 * @package App\Models\Access
 * @property integer          $id
 * @property string           $name
 * @property string           $guard_name
 * @property Carbon           $created_at
 * @property Carbon           $updated_at
 * @property Collection<Role> $roles
 */
class Permission extends PermissionBase
{
    use HasFactory, UuidTrait;
}
