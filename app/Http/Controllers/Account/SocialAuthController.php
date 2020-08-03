<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Resources\Account\SocialAccount\RedirectToProvider;
use App\Services\Account\SocialAuth as SocialAuthService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\FacebookProvider;
use Laravel\Socialite\Two\GoogleProvider;

class SocialAuthController extends Controller
{
    protected $provider = null;

    protected $socialAuthService;

    public function __construct(SocialAuthService $socialAccountService)
    {
        $this->socialAuthService = $socialAccountService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function redirectToProvider(Request $request)
    {
        $response = $this->socialAuthService->redirectToProvider($this->provider);

        return (new RedirectToProvider($response))
            ->response()
            ->setStatusCode(Response::HTTP_FOUND);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function handleProviderCallback(Request $request)
    {
        dd($request->all());

        if ($this->provider == null) {
            abort(Response::HTTP_BAD_GATEWAY, 'Unknown social provider');
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

        // TODO: make user authorization

        $user->touchLastLogin();

//        return new SocialAccountCollection();
    }
}
