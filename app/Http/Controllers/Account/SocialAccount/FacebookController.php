<?php

namespace App\Http\Controllers\Account\SocialAccount;

use App\Http\Controllers\Account\SocialAuthController;

class FacebookController extends SocialAuthController
{
    protected ?string $provider = 'facebook';
}
