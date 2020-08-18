<?php

namespace App\Http\Controllers\Account\SocialAccount;

use App\Http\Controllers\Account\SocialAccountController;
use App\Models\Account\SocialAccount as SocialAccountModel;

class FacebookController extends SocialAccountController
{
    protected ?string $provider = SocialAccountModel::PROVIDER_NAME_FACEBOOK;
}
