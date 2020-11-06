<?php

namespace App\Http\Resources\Access;

use App\Http\Resources\Traits\ResourceTrait;
use App\Models\Access\Role as RoleModel;
use Illuminate\Http\Resources\Json\JsonResource;

class Role extends JsonResource
{
    use ResourceTrait;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var RoleModel $resource */
        $resource = $this->resource;

        $response = [
            'id'   => $resource->id,
            'name' => $resource->name,
        ];

        if ($resource->relationLoaded('permissions')) {
            $response = array_merge($response, [
                'permissions' => new PermissionCollection($resource->permissions),
            ]);
        }

        return $response;
    }
}
