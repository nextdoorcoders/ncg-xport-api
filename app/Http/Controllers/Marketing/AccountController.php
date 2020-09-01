<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Http\Resources\Marketing\Account as AccountResource;
use App\Http\Resources\Marketing\AccountCollection;
use App\Models\Account\User as UserModel;
use App\Models\Marketing\Account as AccountModel;
use App\Services\Marketing\AccountService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccountController extends Controller
{
    protected AccountService $accountService;

    /**
     * AccountController constructor.
     *
     * @param AccountService $accountService
     */
    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * @return AccountCollection
     */
    public function allAccounts()
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->accountService->allAccounts($user);

        return new AccountCollection($response);
    }

    /**
     * @param Request $request
     * @return AccountResource
     */
    public function createAccount(Request $request)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->accountService->createAccount($user, $data);

        return new AccountResource($response);
    }

    /**
     * @param AccountModel $account
     * @return AccountResource
     */
    public function readAccount(AccountModel $account)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->accountService->readAccount($account, $user);

        return new AccountResource($response);
    }

    /**
     * @param Request      $request
     * @param AccountModel $account
     * @return AccountResource
     */
    public function updateAccount(Request $request, AccountModel $account)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->accountService->updateAccount($account, $user, $data);

        return new AccountResource($response);
    }

    /**
     * @param AccountModel $account
     * @return Response
     * @throws Exception
     */
    public function deleteAccount(AccountModel $account)
    {
        /** @var UserModel $user */
        $user = auth()->user();


        $this->accountService->deleteAccount($account, $user);

        return response()->noContent();
    }
}
