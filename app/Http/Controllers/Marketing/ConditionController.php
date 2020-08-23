<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Http\Resources\Marketing\Condition as ConditionResponse;
use App\Http\Resources\Marketing\ConditionCollection;
use App\Models\Account\User;
use App\Models\Marketing\Condition as ConditionModel;
use App\Models\Marketing\Group as GroupModel;
use App\Models\Marketing\Vendor as VendorModel;
use App\Services\ConditionService;
use Illuminate\Http\Request;

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
    public function allByGroup(GroupModel $group) {
        /** @var User $user */
        $user = auth()->user();

        $response = $this->conditionService->allByGroup($group, $user);

        return new ConditionCollection($response);
    }

    /**
     * @param VendorModel $vendor
     * @return ConditionCollection
     */
    public function allByVendor(VendorModel $vendor) {
        /** @var User $user */
        $user = auth()->user();

        $response = $this->conditionService->allByVendor($vendor, $user);

        return new ConditionCollection($response);
    }

    /**
     * @param Request     $request
     * @param GroupModel  $group
     * @param VendorModel $vendor
     * @return ConditionResponse
     */
    public function createCondition(Request $request, GroupModel $group, VendorModel $vendor) {
        /** @var User $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->conditionService->createCondition($group, $vendor, $user, $data);

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
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function deleteCondition(ConditionModel $condition) {
        /** @var User $user */
        $user = auth()->user();

        $this->conditionService->deleteCondition($condition, $user);

        return response()->noContent();
    }
}
