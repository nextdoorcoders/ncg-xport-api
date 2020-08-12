<?php

namespace App\Http\Controllers\Account;

use App\Exceptions\MessageException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\Login as LoginRequest;
use App\Http\Requests\Account\Logout as LogoutRequest;
use App\Http\Requests\Account\Register as RegisterRequest;
use App\Http\Resources\Account\User\User as UserResource;
use App\Http\Resources\MessageResource;
use App\Http\Resources\Account\AccessToken as AccessTokenResource;
use App\Models\Account\Language as LanguageModel;
use App\Models\Account\User as UserModel;
use App\Models\Geo\Country as CountryModel;
use App\Services\Account\User as UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

        $ip = $request->getClientIp();
        $agent = $request->userAgent();
        $abilities = $request->get('abilities', ['*']);

        $response = $this->userService->login($email, $password, $ip, $agent, $abilities);

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

        $countryId = $request->get('country_id', null);
        $languageCode = app()->getLocale() ?? LanguageModel::LANGUAGE_BY_DEFAULT;

        /** @var CountryModel $country */
        $country = CountryModel::query()
            ->where('id', $countryId)
            ->first();

        /** @var LanguageModel $language */
        $language = LanguageModel::query()
            ->where('code', $languageCode)
            ->first();

        $this->userService->register($email, $password, $name, $language, $country);

        return (new MessageResource('Регистрация завершена', 'Воспользуйтесь формой входа что-бы войти в систему'))
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @return UserResource
     */
    public function user(Request $request)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->userService->user($user);

        return new UserResource($response);
    }
}
