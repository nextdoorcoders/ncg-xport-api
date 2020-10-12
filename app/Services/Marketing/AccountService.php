<?php

namespace App\Services\Marketing;

use App\Exceptions\MessageException;
use App\Models\Account\Language as LanguageModel;
use App\Models\Account\User as UserModel;
use App\Models\Marketing\Account as AccountModel;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\FacebookProvider;
use Laravel\Socialite\Two\GoogleProvider;
use Laravel\Socialite\Two\User as UserSocialite;

class AccountService
{
    /**
     * @param UserModel $user
     * @return Collection
     */
    public function allAccounts(UserModel $user): Collection
    {
        return $user->accounts()
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
            abort(Response::HTTP_BAD_GATEWAY, 'Unknown provider');
        }

        /** @var FacebookProvider|GoogleProvider $driver */
        $driver = Socialite::driver($provider)
            ->scopes($scopes)
            ->with($with);

        $driver->stateless();

        return $driver;
    }

    /**
     * @param null|UserModel $authAccount
     * @param string         $providerName
     * @param UserSocialite  $providerUser
     * @param string         $locale
     * @return UserModel
     * @throws MessageException
     */
    public function getOrCreateUser(?UserModel $authAccount, string $providerName, UserSocialite $providerUser, string $locale)
    {
        if (empty($providerUser->getEmail())) {
            throw new MessageException('Registration with this provider is not possible. Please, use another provider');
        }

        try {
            DB::beginTransaction();

            if ($authAccount) {
                // Если пользователь авторизован
                $user = $authAccount;
            } else {
                // Если пользователь гость

                $account = UserModel::query()
                    ->where('email', $providerUser->getEmail())
                    ->first();

                if ($account) {
                    // If account registered as primary (previously registered without social networks)
                    $user = $account;
                } else {
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

            $user->accounts()->updateOrCreate([
                'provider_id'   => $providerUser->getId(),
                'provider_name' => $providerName,
            ], [
                'email'         => $providerUser->getEmail(),
                'access_token'  => $providerUser->token,
                'refresh_token' => $providerUser->refreshToken,
            ]);

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            throw $exception;
        }

        return $user;
    }

    /**
     * @param AccountModel $account
     * @throws Exception
     */
    public function deleteAccount(AccountModel $account): void
    {
        try {
            $account->delete();
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
