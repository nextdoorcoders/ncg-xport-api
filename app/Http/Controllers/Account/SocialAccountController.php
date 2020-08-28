<?php

namespace App\Http\Controllers\Account;

use App\Exceptions\MessageException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Account\AccessToken;
use App\Http\Resources\Account\SocialAccountLink;
use App\Http\Resources\Account\SocialAccountCollection;
use App\Http\Resources\MessageResource;
use App\Models\Account\User as UserModel;
use App\Services\Account\SocialAccountService as SocialAuthService;
use App\Services\Account\UserService as UserService;
use Exception;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\FacebookProvider;
use Laravel\Socialite\Two\GoogleProvider;
use Laravel\Socialite\Two\User as UserSocialite;

class SocialAccountController extends Controller
{
    protected ?string $provider = null;

    protected array $scopes = [];

    protected array $with = [];

    protected SocialAuthService $socialAuthService;

    protected UserService $userService;

    /**
     * SocialAuthController constructor.
     *
     * @param SocialAuthService $socialAccountService
     * @param UserService       $userService
     */
    public function __construct(SocialAuthService $socialAccountService, UserService $userService)
    {
        $this->socialAuthService = $socialAccountService;

        $this->userService = $userService;
    }

    /**
     * @return SocialAccountCollection
     */
    public function allSocialAccounts()
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->socialAuthService->allSocialAccounts($user);

        return new SocialAccountCollection($response);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function linkToProvider(Request $request)
    {
        $response = $this->socialAuthService->redirectToProvider($this->provider, $this->scopes, $this->with);

        return new SocialAccountLink([
            'link' => $response->redirect()->getTargetUrl(),
        ]);
    }

    /**
     * @param Request $request
     * @return AccessToken|MessageResource
     * @throws MessageException
     */
    public function handleProviderCallback(Request $request)
    {
        if ($this->provider == null) {
            throw new MessageException('Unknown social provider');
        }

        /** @var UserModel $user */
        $user = auth()->user();

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

                return new AccessToken($response, 'Social account is assigned to the profile');
            }
        } catch (Exception $exception) {
            throw $exception;
        }

        return new MessageResource('Social account is assigned to the profile');
    }
}
