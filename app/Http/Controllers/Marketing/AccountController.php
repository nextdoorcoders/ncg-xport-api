<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Account\SocialAccount as SocialAccountModel;
use App\Models\Account\User as UserModel;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function allAccounts()
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $user->campaigns()->get();

        return response()->json($response);
    }

    /**
     * @param SocialAccountModel $socialAccount
     * @return \Illuminate\Http\JsonResponse
     */
    public function socialAccountAllAccounts(SocialAccountModel $socialAccount)
    {
        $response = $socialAccount->accounts()
            ->get();

        return response()->json($response);
    }

    /**
     * @param SocialAccountModel $socialAccount
     * @param Request            $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function socialAccountAddAccount(SocialAccountModel $socialAccount, Request $request)
    {
        $data = $request->only([
            'name',
            'parameters',
        ]);

        return $socialAccount->accounts()
            ->create($data);
    }
}
