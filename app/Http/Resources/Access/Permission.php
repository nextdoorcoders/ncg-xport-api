<?php

namespace App\Http\Resources\Access;

use App\Http\Resources\Traits\ResourceTrait;
use App\Models\Access\Permission as PermissionModel;
use Illuminate\Http\Resources\Json\JsonResource;

class Permission extends JsonResource
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
        /** @var PermissionModel $resource */
        $resource = $this->resource;

        $permission = explode(' ', $resource->name);
        $subject = array_pop($permission);

        $response = [
            'id'      => $resource->id,
            'name'    => $resource->name,
            'action'  => implode(' ', $permission),
            'subject' => $subject,
        ];

        if ($resource->relationLoaded('roles')) {
            $response = array_merge($response, [
                'roles' => new RoleCollection($resource->roles),
            ]);
        }

        return $response;
    }
}
