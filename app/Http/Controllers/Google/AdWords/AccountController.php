<?php

namespace App\Http\Controllers\Google\AdWords;

use App\Http\Controllers\Controller;
use App\Services\Google\AdWords\AccountService;
use Illuminate\Http\Request;

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $response = $this->accountService->index();

        return response()
            ->json($response);
    }
}
