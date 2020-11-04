<?php

namespace App\Models\Access;

use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as RoleBase;

class Role extends RoleBase
{
    use HasFactory, UuidTrait;
}
