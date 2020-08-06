<?php

namespace App\Http\Controllers\Account;

use App\Exceptions\MessageException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Account\SocialAccount\RedirectToProvider;
use App\Http\Resources\Account\SocialAccount\SocialAccountCollection;
use App\Http\Resources\Users\AccessToken as AccessTokenResource;
use App\Services\Account\SocialAuth as SocialAuthService;
use App\Services\Account\User as UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\FacebookProvider;
use Laravel\Socialite\Two\GoogleProvider;

class SocialAuthController extends Controller
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function redirectToProvider(Request $request)
    {
        $response = $this->socialAuthService->redirectToProvider($this->provider, $this->scopes, $this->with);

        return (new RedirectToProvider($response))
            ->response()
            ->setStatusCode(Response::HTTP_FOUND);
    }

    /**
     * @param Request $request
     * @return SocialAccountCollection|AccessTokenResource
     * @throws MessageException
     */
    public function handleProviderCallback(Request $request)
    {
        if ($this->provider == null) {
            throw new MessageException('Unknown social provider');
        }

        /** @var FacebookProvider|GoogleProvider $driver */
        $driver = Socialite::driver($this->provider);

        $driver->stateless();

        $userData = $driver->user();

        try {
            $user = $this->socialAuthService->getOrCreateUser($request, $userData, $this->provider);
        } catch (Exception $exception) {
            throw $exception;
        }

        if (!auth()->check()) {
            // Return Bearer token if user are not logged in
            $response = $this->userService->generateBearerToken($user, $request->getClientIp(), $request->userAgent());

            return new AccessTokenResource($response);
        }

        // Return full list of attached social accounts
        $response = $user->socialAccounts()->get();

        return new SocialAccountCollection($response);
    }
}
