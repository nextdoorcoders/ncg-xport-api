<?php

namespace App\Http\Controllers\Account\SocialAccount;

use App\Http\Controllers\Account\SocialAuthController;
use App\Models\Account\SocialAccount as SocialAccountModel;

class FacebookController extends SocialAuthController
{
    protected ?string $provider = SocialAccountModel::PROVIDER_NAME_FACEBOOK;
}
