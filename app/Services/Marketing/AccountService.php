<?php

namespace App\Services\Marketing;

use App\Models\Account\SocialAccount as SocialAccountModel;
use App\Models\Account\User as UserModel;
use App\Models\Marketing\Account as AccountModel;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class AccountService
{
    /**
     * @param UserModel $user
     * @return Collection
     */
    public function allAccounts(UserModel $user)
    {
        return $user->accounts()
            ->get();
    }

    /**
     * @param SocialAccountModel $socialAccount
     * @param UserModel          $user
     * @return Collection
     */
    public function allAccountsOfSocialAccount(SocialAccountModel $socialAccount, UserModel $user)
    {
        return $socialAccount->accounts()
            ->get();
    }

    /**
     * @param UserModel $user
     * @param array     $data
     * @return Model
     */
    public function createAccount(UserModel $user, array $data)
    {
        return $user->accounts()
            ->create($data);
    }

    /**
     * @param AccountModel $account
     * @param UserModel    $user
     * @return AccountModel
     */
    public function readAccount(AccountModel $account, UserModel $user)
    {
        return $account;
    }

    /**
     * @param AccountModel $account
     * @param UserModel    $user
     * @param array        $data
     * @return AccountModel|null
     */
    public function updateAccount(AccountModel $account, UserModel $user, array $data)
    {
        $account->fill($data);
        $account->save();

        return $account->fresh();
    }

    /**
     * @param AccountModel $account
     * @param UserModel    $user
     * @throws Exception
     */
    public function deleteAccount(AccountModel $account, UserModel $user)
    {
        try {
            $account->delete();
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
