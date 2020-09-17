<?php

namespace App\Http\Controllers\Trigger;

use App\Http\Controllers\Controller;
use App\Http\Resources\Trigger\Condition as ConditionResponse;
use App\Http\Resources\Trigger\ConditionCollection;
use App\Models\Account\User;
use App\Models\Trigger\Condition as ConditionModel;
use App\Models\Trigger\Group as GroupModel;
use App\Models\Trigger\VendorLocation;
use App\Services\Trigger\ConditionService;
use Exception;
use Illuminate\Http\Request;
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
    public function allConditions(GroupModel $group) {
        /** @var User $user */
        $user = auth()->user();

        $response = $this->conditionService->allByGroup($group, $user);

        return new ConditionCollection($response);
    }

    /**
     * @param Request        $request
     * @param GroupModel     $group
     * @param VendorLocation $vendorLocation
     * @return ConditionResponse
     */
    public function createCondition(Request $request, GroupModel $group) {
        /** @var User $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->conditionService->createCondition($group, $user, $data);

        return new ConditionResponse($response);
    }

    /**
     * @param ConditionModel $condition
     * @return ConditionResponse
     */
    public function readCondition(ConditionModel $condition) {
        /** @var User $user */
        $user = auth()->user();

        $response = $this->conditionService->readCondition($condition, $user);

        return new ConditionResponse($response);
    }

    /**
     * @param Request        $request
     * @param ConditionModel $condition
     * @return ConditionResponse
     */
    public function updateCondition(Request $request, ConditionModel $condition) {
        /** @var User $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->conditionService->updateCondition($condition, $user, $data);

        return new ConditionResponse($response);
    }

    /**
     * @param ConditionModel $condition
     * @return Response
     * @throws Exception
     */
    public function deleteCondition(ConditionModel $condition) {
        /** @var User $user */
        $user = auth()->user();

        $this->conditionService->deleteCondition($condition, $user);

        return response()->noContent();
    }
}
