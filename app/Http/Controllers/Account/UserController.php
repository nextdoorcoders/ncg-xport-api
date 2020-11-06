<?php

namespace App\Http\Controllers\Account;

use App\Exceptions\MessageException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\ForgotConfirmCode as ForgotConfirmCodeRequest;
use App\Http\Requests\Account\ForgotSendCode as ForgotSendCodeRequest;
use App\Http\Requests\Account\Login as LoginRequest;
use App\Http\Requests\Account\Logout as LogoutRequest;
use App\Http\Requests\Account\Register as RegisterRequest;
use App\Http\Requests\Account\User as UserRequest;
use App\Http\Resources\Access\PermissionCollection;
use App\Http\Resources\Access\RoleCollection;
use App\Http\Resources\Account\User as UserResource;
use App\Http\Resources\Account\UserCollection;
use App\Http\Resources\DataResource;
use App\Http\Resources\MessageResource;
use App\Models\Account\Language as LanguageModel;
use App\Models\Account\User as UserModel;
use App\Services\Account\UserService as UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    protected UserService $userService;

    /**
     * UserController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
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

        $ip = $request->getClientIp();
        $agent = $request->userAgent();
        $abilities = $request->get('abilities', ['*']);

        $response = $this->userService->login($email, $password, $ip, $agent, $abilities);

        return new DataResource($response);
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

        return new MessageResource(null, null);
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

        $countryId = $request->get('country_id', null);
        $languageCode = app()->getLocale() ?? LanguageModel::LANGUAGE_BY_DEFAULT;

        /** @var LanguageModel $language */
        $language = LanguageModel::query()
            ->where('code', $languageCode)
            ->first();

        $this->userService->register($email, $password, $name, $language);

        return (new MessageResource('Регистрация завершена', 'Воспользуйтесь формой входа что-бы войти в систему'))
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @param ForgotSendCodeRequest $request
     * @return MessageResource
     * @throws MessageException
     */
    public function forgotSendCode(ForgotSendCodeRequest $request)
    {
        $email = $request->get('email');

        $this->userService->forgotSendCode($email);

        return new MessageResource('Success');
    }

    /**
     * @param ForgotConfirmCodeRequest $request
     * @return MessageResource
     * @throws MessageException
     */
    public function forgotConfirmCode(ForgotConfirmCodeRequest $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');
        $code = $request->get('code');

        $this->userService->forgotConfirmCode($email, $password, $code);

        return new MessageResource('Success');
    }

    /**
     * @return UserCollection
     */
    public function allUsers()
    {
        $response = $this->userService->allUsers();

        return new UserCollection($response);
    }

    /**
     * @param UserModel $user
     * @return UserResource
     */
    public function readUser(UserModel $user)
    {
        $response = $this->userService->readUser($user);

        return new UserResource($response);
    }

    /**
     * @return UserResource
     */
    public function readCurrentUser()
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->userService->readUser($user);

        return new UserResource($response);
    }

    /**
     * @param UserRequest $request
     * @return UserResource
     */
    public function updateCurrentUser(UserRequest $request)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $data = $request->only([
            'name',
            'email',
            'password',
        ]);

        $response = $this->userService->updateUser($user, $data);

        return new UserResource($response, 'Successfully updated', 'Your account information has been saved');
    }

    /**
     * @return PermissionCollection
     */
    public function readCurrentUserPermissions()
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->userService->readUserPermissions($user);

        return new PermissionCollection($response);
    }

    /**
     * @return RoleCollection
     */
    public function readCurrentUserRoles()
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->userService->readUserRoles($user);

        return new RoleCollection($response);
    }
}
