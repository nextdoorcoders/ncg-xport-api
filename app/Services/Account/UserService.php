<?php

namespace App\Services\Account;

use App\Exceptions\MessageException;
use App\Models\Account\Language as LanguageModel;
use App\Models\Marketing\Account as AccountModel;
use App\Models\Account\User as UserModel;
use App\Models\Token;
use App\Services\Sender\EmailService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

class UserService
{
    protected EmailService $emailService;

    /**
     * UserService constructor.
     *
     * @param EmailService $emailService
     */
    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * @param string $email
     * @param string $password
     * @param string $ip
     * @param string $agent
     * @param array  $abilities
     * @return array
     * @throws MessageException
     */
    public function login(string $email, string $password, string $ip, string $agent, array $abilities)
    {
        /** @var UserModel $user */
        $user = UserModel::query()
            ->where('email', $email)
            ->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw new MessageException('The provided credentials are incorrect.');
        }

        return $this->generateBearerToken($user, $ip, $agent, $abilities);
    }

    /**
     * @param UserModel $user
     * @param string    $ip
     * @param string    $agent
     * @param array     $abilities
     * @return array
     */
    public function generateBearerToken(UserModel $user, string $ip, string $agent, array $abilities = ['*'])
    {
        $accessToken = Str::random(96);

        /** @var Token $token */
        $token = $user->tokens()
            ->create([
                'ip'        => $ip,
                'agent'     => $agent,
                'token'     => hash('sha256', $accessToken),
                'abilities' => $abilities,
            ]);

        $user->touchLastLogin();

        return [
            'token_type'   => 'Bearer',
            'access_token' => sprintf('%s|%s', $token->id, $accessToken),
        ];
    }

    /**
     * @param UserModel $user
     * @throws Exception
     */
    public function logout(UserModel $user)
    {
        /** @var PersonalAccessToken $token */
        $token = $user->currentAccessToken();

        $token->delete();
    }

    /**
     * @param string        $email
     * @param string        $password
     * @param string        $name
     * @param LanguageModel $language
     * @return UserModel
     * @throws Exception
     */
    public function register(
        string $email,
        string $password,
        string $name,
        LanguageModel $language
    ) {
        try {
            DB::beginTransaction();

            /** @var UserModel $user */
            $user = app(UserModel::class);

            $user->fill([
                'language_id' => $language->id ?? null,
                'name'        => $name,
                'email'       => $email,
                'password'    => $password,
            ]);

            $user->save();

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            throw $exception;
        }

        return $user;
    }

    /**
     * @param string $email
     * @throws MessageException
     */
    public function forgotSendCode(string $email): void
    {
        /** @var UserModel $user */
        $user = UserModel::query()
            ->where('email', $email)
            ->first();

        if (!$user) {
            /** @var AccountModel $account */
            $account = AccountModel::query()
                ->with('user')
                ->where('email', $email)
                ->first();

            if (!$account) {
                throw new MessageException('User is not found', 'Please check your email and try again');
            }

            $user = $account->user;
        }

        $code = UserModel::getPasswordResetCode();

        $user->password_reset_code = $code;
        $user->save();

        $this->emailService->passwordReset($user, $code);
    }

    /**
     * @param string $email
     * @param string $password
     * @param string $code
     * @throws MessageException
     */
    public function forgotConfirmCode(string $email, string $password, string $code)
    {
        /** @var UserModel $user */
        $user = UserModel::query()
            ->where('email', $email)
            ->first();

        if (!$user) {
            /** @var AccountModel $account */
            $account = AccountModel::query()
                ->with('user')
                ->where('email', $email)
                ->first();

            if (!$account) {
                throw new MessageException('User is not found', 'Please check your email and try again');
            }

            $user = $account->user;
        }

        if ($user->password_reset_code != $code) {
            throw new MessageException('The code does not match', 'Please check code from the email and try again');
        }

        $user->password = $password;
        $user->password_reset_code = null;
        $user->save();
    }

    public function allUsers()
    {
        return UserModel::query()
            ->get();
    }

    /**
     * @param UserModel $user
     * @return UserModel
     */
    public function readUser(UserModel $user)
    {
        $user->load([
            'language',
        ]);

        return $user->refresh();
    }

    /**
     * @param UserModel $user
     * @param array     $data
     * @return UserModel
     */
    public function updateUser(UserModel $user, array $data)
    {
        $user->fill($data);
        $user->save();

        return $this->readUser($user);
    }
}
