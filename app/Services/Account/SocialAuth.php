<?php

namespace App\Services\Account;

use App\Exceptions\MessageException;
use App\Models\Account\Language as LanguageModel;
use App\Models\Account\SocialAccount as SocialAccountModel;
use App\Models\Account\User as UserModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Contracts\User as ProviderUser;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\FacebookProvider;
use Laravel\Socialite\Two\GoogleProvider;

class SocialAuth
{
    /**
     * @param Request      $request
     * @param ProviderUser $providerUser
     * @param string       $providerName
     * @return UserModel
     * @throws MessageException
     */
    public function getOrCreateUser(Request $request, ProviderUser $providerUser, string $providerName)
    {
        if (empty($providerUser->getEmail())) {
            throw new MessageException('Registration with this provider is not possible. Please, use another provider');
        }

        try {
            DB::beginTransaction();

            /** @var SocialAccountModel $account */
            $account = SocialAccountModel::query()
                ->with([
                    'user',
                ])
                ->where('provider_id', $providerUser->getId())
                ->where('provider_name', $providerName)
                ->first();

            /** @var UserModel $user */
            if ($account) {
                // If social account already exists
                $user = $account->user;
            } else {
                // If social account not exist - check users
                $user = UserModel::query()
                    ->where('email', $providerUser->getEmail())
                    ->first();

                if (!$user) {
                    // If user are not registered
                    /** @var LanguageModel $language */
                    $language = LanguageModel::query()
                        ->where('code', $request->getLocale())
                        ->orWhere('code', LanguageModel::LANGUAGE_BY_DEFAULT)
                        ->first();

                    $user = UserModel::query()
                        ->create([
                            'language_id' => $language->id,
                            'name'        => $providerUser->getName(),
                            'email'       => $providerUser->getEmail(),
                            'password'    => UserModel::getRandomPassword(),
                        ]);
                }
            }

            $user->socialAccounts()->updateOrCreate([
                'provider_id'   => $providerUser->getId(),
                'provider_name' => $providerName,
            ], [
                'email'         => $providerUser->getEmail(),
                'access_token'  => $providerUser->token,
                'refresh_token' => $providerUser->refreshToken,
                'last_login_at' => now(),
            ]);


            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            throw $exception;
        }

        return $user;
    }

    /**
     * @param string $provider
     * @param array  $scopes
     * @param array  $with
     * @return array
     */
    public function redirectToProvider(string $provider, array $scopes = [], array $with = []): array
    {
        if ($provider == null) {
            abort(Response::HTTP_BAD_GATEWAY, 'Unknown social provider');
        }

        /** @var FacebookProvider|GoogleProvider $driver */
        $driver = Socialite::driver($provider)
            ->scopes($scopes)
            ->with($with);

        $driver->stateless();

        return [
            'redirectTo' => $driver->redirect()->getTargetUrl(),
        ];
    }
}
