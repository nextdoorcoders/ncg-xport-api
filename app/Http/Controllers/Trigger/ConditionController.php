<?php

namespace App\Http\Controllers\Trigger;

use App\Exceptions\MessageException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Trigger\ConditionRequest;
use App\Http\Resources\Trigger\Condition as ConditionResource;
use App\Http\Resources\Trigger\ConditionCollection;
use App\Models\Account\User as UserModel;
use App\Models\Trigger\Condition as ConditionModel;
use App\Models\Trigger\Group as GroupModel;
use App\Services\Trigger\ConditionService;
use Exception;
use Illuminate\Http\Response;

class ConditionController extends Controller
{
    protected ConditionService $conditionService;

    /**
     * ConditionController constructor.
     *
     * @param ConditionService $conditionService
     */
    public function __construct(ConditionService $conditionService)
    {
        $this->conditionService = $conditionService;
    }

    /**
     * @param GroupModel $group
     * @return ConditionCollection
     */
    public function allConditions(GroupModel $group)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->conditionService->allByGroup($group, $user);

        return new ConditionCollection($response);
    }

    /**
     * @param ConditionRequest $request
     * @param GroupModel       $group
     * @return ConditionResource
     * @throws MessageException
     */
    public function createCondition(ConditionRequest $request, GroupModel $group)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->conditionService->createCondition($group, $user, $data);

        return new ConditionResource($response);
    }

    /**
     * @param ConditionModel $condition
     * @return ConditionResource
     */
    public function readCondition(ConditionModel $condition)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->conditionService->readCondition($condition, $user);

        return new ConditionResource($response);
    }

    /**
     * @param ConditionRequest $request
     * @param ConditionModel   $condition
     * @return ConditionResource
     */
    public function updateCondition(ConditionRequest $request, ConditionModel $condition)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->conditionService->updateCondition($condition, $user, $data);

        return new ConditionResource($response);
    }

    /**
     * @param ConditionModel $condition
     * @return Response
     * @throws Exception
     */
    public function deleteCondition(ConditionModel $condition)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $this->conditionService->deleteCondition($condition, $user);

        return response()->noContent();
    }

    /**
     * @param ConditionModel $condition
     * @return ConditionResource
     */
    public function replicateCondition(ConditionModel $condition)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->conditionService->replicateCondition($condition, $user);

        return new ConditionResource($response);
    }
}
