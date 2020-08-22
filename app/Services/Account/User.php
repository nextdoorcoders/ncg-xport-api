<?php

namespace App\Services\Account;

use App\Exceptions\MessageException;
use App\Models\Account\Language as LanguageModel;
use App\Models\Account\User as UserModel;
use App\Models\Geo\Country as CountryModel;
use App\Models\Token;
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
     * @param string            $email
     * @param string            $password
     * @param string            $name
     * @param CountryModel|null $country
     * @param LanguageModel     $language
     * @return UserModel
     * @throws Exception
     */
    public function register(
        string $email,
        string $password,
        string $name,
        LanguageModel $language,
        CountryModel $country = null
    ) {
        try {
            DB::beginTransaction();

            /** @var UserModel $user */
            $user = app(UserModel::class);

            $user->fill([
                'country_id'  => $country->id ?? null,
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
     * @param UserModel $user
     * @return UserModel
     */
    public function user(UserModel $user)
    {
        $user->load('language');

        return $user;
    }
}
