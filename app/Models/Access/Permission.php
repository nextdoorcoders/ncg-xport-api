<?php

namespace App\Models\Access;

use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as PermissionBase;

class Permission extends PermissionBase
{
    use HasFactory, UuidTrait;
}
