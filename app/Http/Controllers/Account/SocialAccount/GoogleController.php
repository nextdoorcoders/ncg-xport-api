<?php

namespace App\Http\Controllers\Account\SocialAccount;

use App\Http\Controllers\Account\SocialAuthController;

class GoogleController extends SocialAuthController
{
    protected ?string $provider = 'google';

    protected array $scopes = [
        'https://www.googleapis.com/auth/adwords',
        'https://www.googleapis.com/auth/dfp',
    ];

    protected array $with = [
        'access_type' => 'offline',
    ];
}
