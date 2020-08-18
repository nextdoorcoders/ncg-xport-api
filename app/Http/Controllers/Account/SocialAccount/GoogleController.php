<?php

namespace App\Http\Controllers\Account\SocialAccount;

use App\Http\Controllers\Account\SocialAccountController;
use App\Models\Account\SocialAccount as SocialAccountModel;

class GoogleController extends SocialAccountController
{
    protected ?string $provider = SocialAccountModel::PROVIDER_NAME_GOOGLE;

    protected array $scopes = [
        'https://www.googleapis.com/auth/adwords',
        'https://www.googleapis.com/auth/dfp',
    ];

    protected array $with = [
        'access_type' => 'offline',
        'prompt'      => 'consent',
    ];
}
