<?php

namespace App\Services\Account;

use App\Exceptions\MessageException;
use App\Models\Account\User as UserModel;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

class User
{
    /**
     * @param string $email
     * @param string $password
     * @param string $device
     * @param array  $abilities
     * @return array
     * @throws MessageException
     */
    public function login(string $email, string $password, string $device, array $abilities)
    {
        /** @var UserModel $user */
        $user = UserModel::query()
            ->where('email', $email)
            ->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw new MessageException('The provided credentials are incorrect.');
        }

        $accessToken = Str::random(80);

        $user->tokens()->create([
            'name'      => $device,
            'token'     => hash('sha256', $accessToken),
            'abilities' => $abilities,
        ]);

        $user->touchLastLogin();

        return [
            'token_type'   => 'Bearer',
            'access_token' => $accessToken,
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
     * @param string $email
     * @param string $password
     * @param string $name
     * @param string $language
     * @return UserModel
     * @throws Exception
     */
    public function register(string $email, string $password, string $name, string $language)
    {
        try {
            DB::beginTransaction();

            /** @var UserModel $user */
            $user = app(UserModel::class);

            $user->fill([
                'name'     => $name,
                'language' => $language,
                'email'    => $email,
                'password' => $password,
            ]);

            $user->save();

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            throw $exception;
        }

        return $user;
    }
}
