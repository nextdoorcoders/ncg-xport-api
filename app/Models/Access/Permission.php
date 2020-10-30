<?php

namespace App\Models\Access;

use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Permission as PermissionBase;

class Permission extends PermissionBase
{
    use HasFactory, UuidTrait;

    /**
     * A permission belongs to some users of the model associated with its guard.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            getModelForGuard($this->attributes['guard_name']),
            config('permission.table_names.model_has_permissions'),
            'permission_id',
            config('permission.column_names.model_morph_key')
        );
    }
}
