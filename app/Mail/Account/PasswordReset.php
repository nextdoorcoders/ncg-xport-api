<?php

namespace App\Mail\Account;

use App\Models\Account\User as UserModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordReset extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected UserModel $user;

    protected string $code;

    /**
     * Create a new message instance.
     *
     * @param UserModel $user
     * @param string    $code
     */
    public function __construct(UserModel $user, string $code)
    {
        $this->user = $user;

        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Password reset. Please, confirm your email')
            ->markdown('mail.account.password-reset', [
                'code' => $this->code,
            ]);
    }
}
