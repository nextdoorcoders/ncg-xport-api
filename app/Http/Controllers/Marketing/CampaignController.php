<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Account\SocialAccount;
use App\Models\Account\User;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function allCompanies()
    {
        /** @var User $user */
        $user = auth()->user();

        $response = $user->companies()->get();

        return response()->json($response);
    }

    public function socialAccountAllCompanies(SocialAccount $socialAccount)
    {
        $response = $socialAccount->companies()
            ->get();

        return response()->json($response);
    }

    public function socialAccountCreateCompany(SocialAccount $socialAccount, Request $request)
    {
        $data = $request->only([
            'name',
            'desc',
            'parameters',
        ]);

        return $socialAccount->companies()
            ->create($data);
    }
}
