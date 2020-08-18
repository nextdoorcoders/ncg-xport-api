<?php

namespace App\Http\Controllers\Account;

use App\Exceptions\MessageException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Account\SocialAccount\SocialAccountCollection;
use App\Models\Account\User as UserModel;
use App\Services\Account\SocialAuth as SocialAuthService;
use App\Services\Account\User as UserService;
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
    public function index()
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->socialAuthService->getAllSocialAccounts($user);

        return new SocialAccountCollection($response);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function redirectToProvider(Request $request)
    {
        $response = $this->socialAuthService->redirectToProvider($this->provider, $this->scopes, $this->with);

        return $response->redirect();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
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

            // Return Bearer token if user are not logged in
            $response = $this->userService->generateBearerToken($account, $request->getClientIp(), $request->userAgent());
        } catch (Exception $exception) {
            throw $exception;
        }

        return redirect()->to(env('APP_SPA_URL') . '/social-account/' . $response['token_type'] . '/' . $response['access_token']);
    }
}
