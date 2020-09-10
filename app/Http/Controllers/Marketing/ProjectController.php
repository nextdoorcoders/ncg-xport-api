<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Http\Resources\Marketing\Project as ProjectResource;
use App\Http\Resources\Marketing\ProjectCollection;
use App\Models\Account\User as UserModel;
use App\Models\Marketing\Project as ProjectModel;
use App\Services\Marketing\ProjectService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    protected ProjectService $projectService;

    /**
     * ProjectController constructor.
     *
     * @param ProjectService $projectService
     */
    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * @return ProjectCollection
     */
    public function allProjects()
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->projectService->allProjects($user);

        return new ProjectCollection($response);
    }

    /**
     * @param Request $request
     * @return ProjectResource
     */
    public function createProject(Request $request)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->projectService->createProject($user, $data);

        return new ProjectResource($response);
    }

    /**
     * @param ProjectModel $account
     * @return ProjectResource
     */
    public function readProject(ProjectModel $account)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->projectService->readProject($account, $user);

        return new ProjectResource($response);
    }

    /**
     * @param Request      $request
     * @param ProjectModel $account
     * @return ProjectResource
     */
    public function updateProject(Request $request, ProjectModel $account)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->projectService->updateProject($account, $user, $data);

        return new ProjectResource($response);
    }

    /**
     * @param ProjectModel $account
     * @return Response
     * @throws Exception
     */
    public function deleteProject(ProjectModel $account)
    {
        /** @var UserModel $user */
        $user = auth()->user();


        $this->projectService->deleteProject($account, $user);

        return response()->noContent();
    }
}
