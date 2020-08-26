<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Http\Resources\Marketing\Project as ProjectResource;
use App\Http\Resources\Marketing\ProjectCollection;
use App\Http\Resources\Marketing\ProjectTriggerCollection;
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
     * @param ProjectModel $project
     * @return ProjectResource
     */
    public function readProject(ProjectModel $project)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->projectService->readProject($project, $user);

        return new ProjectResource($response);
    }

    /**
     * @param Request      $request
     * @param ProjectModel $project
     * @return ProjectResource
     */
    public function updateProject(Request $request, ProjectModel $project)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->projectService->updateProject($project, $user, $data);

        return new ProjectResource($response);
    }

    /**
     * @param ProjectModel $project
     * @return Response
     * @throws Exception
     */
    public function deleteProject(ProjectModel $project)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $this->projectService->deleteProject($project, $user);

        return response()->noContent();
    }

    /**
     * @param ProjectModel $project
     * @return ProjectResource
     */
    public function replicateProject(ProjectModel $project)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->projectService->replicateProject($project, $user);

        return new ProjectResource($response);
    }

    /**
     * @param ProjectModel $project
     * @return ProjectTriggerCollection
     */
    public function allTriggers(ProjectModel $project)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->projectService->allTriggers($project, $user);

        return new ProjectTriggerCollection($response);
    }

    /**
     * @param Request      $request
     * @param ProjectModel $project
     * @return ProjectTriggerCollection
     */
    public function updateTriggers(Request $request, ProjectModel $project)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->projectService->updateTriggers($project, $user, $data);

        return new ProjectTriggerCollection($response);
    }
}
