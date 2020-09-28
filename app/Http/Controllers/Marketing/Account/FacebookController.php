<?php

namespace App\Http\Controllers\Marketing\Account;

use App\Http\Controllers\Marketing\AccountController;
use App\Models\Marketing\Account as AccountModel;

class FacebookController extends AccountController
{
    protected ?string $provider = AccountModel::PROVIDER_NAME_FACEBOOK;
}
