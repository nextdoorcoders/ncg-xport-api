<?php

namespace App\Http\Controllers\Account;

use App\Exceptions\MessageException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\Login as LoginRequest;
use App\Http\Requests\Account\Logout as LogoutRequest;
use App\Http\Requests\Account\Register as RegisterRequest;
use App\Http\Resources\MessageResource;
use App\Http\Resources\Users\AccessToken as AccessTokenResource;
use App\Models\Account\User as UserModel;
use App\Services\Account\User as UserService;
use Exception;
use Illuminate\Http\JsonResponse;

class AccountController extends Controller
{
    protected UserService $userService;

    /**
     * UserController constructor.
     *
     * @param UserService $user
     */
    public function __construct(UserService $user)
    {
        $this->userService = $user;
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse|object
     * @throws MessageException
     */
    public function login(LoginRequest $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');
        $device = $request->get('device', $request->userAgent());
        $abilities = $request->get('abilities', ['*']);

        $response = $this->userService->login($email, $password, $device, $abilities);

        return new AccessTokenResource($response);
    }

    /**
     * @param LogoutRequest $request
     * @return MessageResource
     * @throws Exception
     */
    public function logout(LogoutRequest $request)
    {
        /** @var UserModel $user */
        $user = auth('api')->user();

        $this->userService->logout($user);

        return new MessageResource('Сессия успешно закрыта', null);
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse|object
     * @throws Exception
     */
    public function register(RegisterRequest $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');
        $name = $request->get('name');
        $language = app()->getLocale();

        $this->userService->register($email, $password, $name, $language);

        return new MessageResource('Регистрация завершена', 'Воспользуйтесь формой входа что-бы войти в систему');
    }
}
