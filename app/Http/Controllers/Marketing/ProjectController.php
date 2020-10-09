<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Http\Requests\Marketing\Project as ProjectRequest;
use App\Http\Resources\Marketing\Project as ProjectResource;
use App\Http\Resources\Marketing\ProjectCollection;
use App\Http\Resources\Trigger\MapCollection;
use App\Models\Account\User as UserModel;
use App\Models\Marketing\Project as ProjectModel;
use App\Services\Marketing\ProjectService;
use Exception;
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
     * @param ProjectRequest $request
     * @return ProjectResource
     */
    public function createProject(ProjectRequest $request)
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
     * @param ProjectRequest $request
     * @param ProjectModel   $project
     * @return ProjectResource
     * @throws Exception
     */
    public function updateProject(ProjectRequest $request, ProjectModel $project)
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
     * @return MapCollection
     */
    public function readMaps(ProjectModel $project)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->projectService->readMaps($project, $user);

        return new MapCollection($response);
    }
}
