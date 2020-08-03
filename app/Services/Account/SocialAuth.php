<?php

namespace App\Services\Account;

use App\Models\Account\SocialAccount as SocialAccountModel;
use App\Models\Account\User as UserModel;
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
     * @return UserModel|mixed
     * @throws \Exception
     */
    public function getOrCreateUser(Request $request, ProviderUser $providerUser, string $providerName)
    {
        /** @var SocialAccountModel $account */
        $account = SocialAccountModel::query()
            ->with([
                'user',
            ])
            ->where('provider_id', $providerUser->getId())
            ->where('provider_name', $providerName)
            ->first();

        if ($account) {
            $account->last_login_at = now();
            $account->save();

            return $account->user;
        }

        // If user already logged in

        try {
            DB::beginTransaction();

            /** @var UserModel $user */
            $user = $request->user();

            if (!$user) {
                if (empty($providerUser->getEmail())) {
                    abort(Response::HTTP_BAD_GATEWAY, 'Registration with this provider is not possible. Please, use another provider');
                }

                // Find registered user
                $user = UserModel::where('email', $providerUser->getEmail())
                    ->first();

                if (!$user) {
                    // If user are not registered
                    $user = UserModel::create([
                        'name'     => $providerUser->getName(),
                        'email'    => $providerUser->getEmail(),
                        'password' => UserModel::getRandomPassword(),
                    ]);
                }
            }

            $user->socialAccounts()->create([
                'provider_id'   => $providerUser->getId(),
                'provider_name' => $providerName,
                'email'         => $providerUser->getEmail(),
                'last_login_at' => now(),
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            throw $exception;
        }

        return $user;
    }

    /**
     * @param string $provider
     * @return array
     */
    public function redirectToProvider(string $provider): array
    {
        if ($provider == null) {
            abort(Response::HTTP_BAD_GATEWAY, 'Unknown social provider');
        }

        /** @var FacebookProvider|GoogleProvider $driver */
        $driver = Socialite::driver($provider);

        $driver->stateless();

        return [
            'redirectTo' => $driver->redirect()->getTargetUrl(),
        ];
    }
}
