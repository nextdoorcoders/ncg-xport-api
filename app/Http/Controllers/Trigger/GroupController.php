<?php

namespace App\Http\Controllers\Trigger;

use App\Http\Controllers\Controller;
use App\Http\Resources\Trigger\Group as GroupResource;
use App\Http\Resources\Trigger\GroupCollection;
use App\Models\Account\User;
use App\Models\Trigger\Group as GroupModel;
use App\Models\Trigger\Map as MapModel;
use App\Services\Trigger\GroupService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GroupController extends Controller
{
    protected GroupService $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    /**
     * @param MapModel $map
     * @return GroupCollection
     */
    public function allGroups(MapModel $map)
    {
        /** @var User $user */
        $user = auth()->user();

        $response = $this->groupService->allGroups($map, $user);

        return new GroupCollection($response);
    }

    /**
     * @param Request  $request
     * @param MapModel $map
     * @return GroupResource
     */
    public function createGroup(Request $request, MapModel $map)
    {
        /** @var User $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->groupService->createGroup($map, $user, $data);

        return new GroupResource($response);
    }

    /**
     * @param GroupModel $group
     * @return GroupResource
     */
    public function readGroup(GroupModel $group)
    {
        /** @var User $user */
        $user = auth()->user();

        $response = $this->groupService->readGroup($group, $user);

        return new GroupResource($response);
    }

    /**
     * @param Request    $request
     * @param GroupModel $group
     * @return GroupResource
     */
    public function updateGroup(Request $request, GroupModel $group)
    {
        /** @var User $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->groupService->updateGroup($group, $user, $data);

        return new GroupResource($response);
    }

    /**
     * @param GroupModel $group
     * @return Response
     * @throws Exception
     */
    public function deleteGroup(GroupModel $group)
    {
        /** @var User $user */
        $user = auth()->user();

        $this->groupService->deleteGroup($group, $user);

        return response()->noContent();
    }
}
