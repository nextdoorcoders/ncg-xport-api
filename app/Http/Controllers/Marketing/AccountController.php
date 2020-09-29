<?php

namespace App\Http\Controllers\Marketing;

use App\Exceptions\MessageException;
use App\Http\Controllers\Controller;
use App\Http\Resources\DataResource;
use App\Http\Resources\Marketing\AccountCollection;
use App\Http\Resources\MessageResource;
use App\Models\Account\User as UserModel;
use App\Models\Marketing\Account as AccountModel;
use App\Services\Account\UserService as UserService;
use App\Services\Marketing\AccountService;
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

    protected AccountService $accountService;

    protected UserService $userService;

    /**
     * AccountController constructor.
     *
     * @param AccountService $accountService
     * @param UserService    $userService
     */
    public function __construct(AccountService $accountService, UserService $userService)
    {
        $this->accountService = $accountService;

        $this->userService = $userService;
    }

    /**
     * @return AccountCollection
     */
    public function allAccounts()
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->accountService->allAccounts($user);

        return new AccountCollection($response);
    }

    /**
     * @param Request $request
     * @return DataResource
     */
    public function linkToProvider(Request $request)
    {
        $response = $this->accountService->redirectToProvider($this->provider, $this->scopes, $this->with);

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
                $account = $this->accountService->getOrCreateUser($user, $this->provider, $userData, $locale);

                if (!$user) {
                    // Return Bearer token if user are not logged in
                    $response = $this->userService->generateBearerToken($account, $request->getClientIp(), $request->userAgent());

                    return new DataResource($response);
                }
            } catch (Exception $exception) {
                throw $exception;
            }

            return new MessageResource();
        } catch (Exception $exception) {
            throw new MessageException('Something happened', 'Failed to get profile information. Please try again later');
        }
    }

    /**
     * @param AccountModel $account
     * @return \Illuminate\Http\Response
     * @throws Exception
     */
    public function deleteAccount(AccountModel $account)
    {
        $this->accountService->deleteAccount($account);

        return response()->noContent();
    }
}
