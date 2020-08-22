<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Http\Resources\Marketing\Group as GroupResource;
use App\Http\Resources\Marketing\GroupCollection;
use App\Models\Account\User;
use App\Models\Marketing\Group as GroupModel;
use App\Models\Marketing\Project as ProjectModel;
use App\Services\Marketing\GroupService;
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
     * @param ProjectModel $project
     * @return GroupCollection
     */
    public function allGroups(ProjectModel $project)
    {
        /** @var User $user */
        $user = auth()->user();

        $response = $this->groupService->allGroups($project, $user);

        return new GroupCollection($response);
    }

    /**
     * @param Request      $request
     * @param ProjectModel $project
     * @return GroupResource
     */
    public function createGroup(Request $request, ProjectModel $project)
    {
        /** @var User $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->groupService->createGroup($project, $user, $data);

        return new GroupResource($response);
    }

    /**
     * @param ProjectModel $project
     * @param GroupModel   $group
     * @return GroupResource
     */
    public function readGroup(ProjectModel $project, GroupModel $group)
    {
        /** @var User $user */
        $user = auth()->user();

        $response = $this->groupService->readGroup($project, $group, $user);

        return new GroupResource($response);
    }

    /**
     * @param Request      $request
     * @param ProjectModel $project
     * @param GroupModel   $group
     * @return GroupResource
     */
    public function updateGroup(Request $request, ProjectModel $project, GroupModel $group)
    {
        /** @var User $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->groupService->updateGroup($project, $group, $user, $data);

        return new GroupResource($response);
    }

    /**
     * @param ProjectModel  $project
     * @param GroupModel $group
     * @return Response
     * @throws Exception
     */
    public function deleteGroup(ProjectModel $project, GroupModel $group)
    {
        /** @var User $user */
        $user = auth()->user();

        $this->groupService->deleteGroup($project, $group, $user);

        return response()->noContent();
    }
}
