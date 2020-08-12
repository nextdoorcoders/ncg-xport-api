<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Account\SocialAccount;
use App\Models\Account\User;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function allCampaigns()
    {
        /** @var User $user */
        $user = auth()->user();

        $response = $user->campaigns()->get();

        return response()->json($response);
    }

    public function socialAccountAllCampaigns(SocialAccount $socialAccount)
    {
        $response = $socialAccount->campaigns()
            ->get();

        return response()->json($response);
    }

    public function socialAccountCreateCampaign(SocialAccount $socialAccount, Request $request)
    {
        $data = $request->only([
            'name',
            'desc',
            'parameters',
        ]);

        return $socialAccount->campaigns()
            ->create($data);
    }
}
