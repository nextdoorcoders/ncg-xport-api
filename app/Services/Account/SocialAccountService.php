<?php

namespace App\Services\Account;

use App\Exceptions\MessageException;
use App\Models\Account\Language as LanguageModel;
use App\Models\Account\SocialAccount as SocialAccountModel;
use App\Models\Account\User as UserModel;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\FacebookProvider;
use Laravel\Socialite\Two\GoogleProvider;
use Laravel\Socialite\Two\User as UserSocialite;

class SocialAccountService
{
    /**
     * @param UserModel $user
     * @return Collection
     */
    public function allSocialAccounts(UserModel $user): Collection
    {
        return $user->socialAccounts()
            ->get();
    }

    /**
     * @param string $provider
     * @param array  $scopes
     * @param array  $with
     * @return FacebookProvider|GoogleProvider
     */
    public function redirectToProvider(string $provider, array $scopes = [], array $with = [])
    {
        if ($provider == null) {
            abort(Response::HTTP_BAD_GATEWAY, 'Unknown social provider');
        }

        /** @var FacebookProvider|GoogleProvider $driver */
        $driver = Socialite::driver($provider)
            ->scopes($scopes)
            ->with($with);

        $driver->stateless();

        return $driver;
    }

    /**
     * @param UserModel     $authAccount
     * @param string        $providerName
     * @param UserSocialite $providerUser
     * @param string        $locale
     * @return UserModel
     * @throws MessageException
     */
    public function getOrCreateUser($authAccount, string $providerName, UserSocialite $providerUser, string $locale)
    {
        if (empty($providerUser->getEmail())) {
            throw new MessageException('Registration with this provider is not possible. Please, use another provider');
        }

        try {
            DB::beginTransaction();

            /** @var SocialAccountModel $socialAccount */
            $socialAccount = SocialAccountModel::query()
                ->with([
                    'user',
                ])
                ->where('provider_id', $providerUser->getId())
                ->where('provider_name', $providerName)
                ->first();

            /** @var UserModel $user */
            if ($socialAccount) {
                // If social account already exists
                $user = $socialAccount->user;
            } else {
                // If social account not exist - check users
                $account = UserModel::query()
                    ->where('email', $providerUser->getEmail())
                    ->first();

                if ($account) {
                    // If account registered as primary (previously registered without social networks)
                    $user = $account;
                } else {
                    if ($authAccount) {
                        // If user already authorized
                        $user = $authAccount;
                    } else {
                        // If user are not registered and not authorized
                        /** @var LanguageModel $language */
                        $language = LanguageModel::query()
                            ->where('code', $locale)
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
}
