<?php

namespace App\Models\Access;

use App\Models\Traits\UuidTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as RoleBase;

/**
 * Class Role
 *
 * @package App\Models\Access
 * @property integer                $id
 * @property string                 $name
 * @property string                 $guard_name
 * @property Carbon                 $created_at
 * @property Carbon                 $updated_at
 * @property Collection<Permission> $permissions
 */
class Role extends RoleBase
{
    use HasFactory, UuidTrait;
}
