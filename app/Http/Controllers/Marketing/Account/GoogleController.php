<?php

namespace App\Http\Controllers\Marketing\Account;

use App\Http\Controllers\Marketing\AccountController;
use App\Models\Marketing\Account as AccountModel;

class GoogleController extends AccountController
{
    protected ?string $provider = AccountModel::PROVIDER_NAME_GOOGLE;

    protected array $scopes = [
        'https://www.googleapis.com/auth/adwords',
        'https://www.googleapis.com/auth/dfp',
    ];

    protected array $with = [
        'access_type' => 'offline',
        'prompt'      => 'consent',
    ];
}
