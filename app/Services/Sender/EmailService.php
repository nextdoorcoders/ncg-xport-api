<?php

namespace App\Services\Sender;

use App\Mail\Account\PasswordReset;
use App\Models\Account\User as UserModel;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function passwordReset(UserModel $user, string $code)
    {
        return Mail::to($user)
            ->send(new PasswordReset($user, $code));
    }
}
