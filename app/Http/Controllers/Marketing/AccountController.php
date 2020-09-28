<?php

namespace App\Http\Controllers\Marketing;

use App\Exceptions\MessageException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Marketing\AccountCollection;
use App\Http\Resources\DataResource;
use App\Http\Resources\MessageResource;
use App\Models\Account\User as UserModel;
use App\Services\Marketing\AccountService as SocialAuthService;
use App\Services\Account\UserService as UserService;
use Exception;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\FacebookProvider;
use Laravel\Socialite\Two\GoogleProvider;
use Laravel\Socialite\Two\User as UserSocialite;

class AccountController extends Controller
{
    protected ?string $provider = null;

    protected array $scopes = [];

    protected array $with = [];

    protected SocialAuthService $socialAuthService;

    protected UserService $userService;

    /**
     * SocialAuthController constructor.
     *
     * @param SocialAuthService $accountService
     * @param UserService       $userService
     */
    public function __construct(SocialAuthService $accountService, UserService $userService)
    {
        $this->socialAuthService = $accountService;

        $this->userService = $userService;
    }

    /**
     * @return AccountCollection
     */
    public function allAccounts()
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->socialAuthService->allAccounts($user);

        return new AccountCollection($response);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function linkToProvider(Request $request)
    {
        $response = $this->socialAuthService->redirectToProvider($this->provider, $this->scopes, $this->with);

        return new DataResource([
            'link' => $response->redirect()->getTargetUrl(),
        ]);
    }

    /**
     * @param Request $request
     * @return DataResource|MessageResource
     * @throws MessageException
     */
    public function handleProviderCallback(Request $request)
    {
        if ($this->provider == null) {
            throw new MessageException('Unknown provider');
        }

        /** @var UserModel $user */
        $user = auth()->user();

        try {
            /** @var FacebookProvider|GoogleProvider $driver */
            $driver = Socialite::driver($this->provider);
            $driver->stateless();

            /** @var UserSocialite $userData */
            $userData = $driver->user();

            $locale = app()->getLocale();

            try {
                $account = $this->socialAuthService->getOrCreateUser($user, $this->provider, $userData, $locale);

                if (!$user) {
                    // Return Bearer token if user are not logged in
                    $response = $this->userService->generateBearerToken($account, $request->getClientIp(), $request->userAgent());

                    return new DataResource($response, 'Account is assigned to the profile');
                }
            } catch (Exception $exception) {
                throw $exception;
            }

            return new MessageResource('Account is assigned to the profile');
        } catch (Exception $exception) {
            throw new MessageException('Something happened', 'Failed to get profile information. Please try again later');
        }
    }
}
