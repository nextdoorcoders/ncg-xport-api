<?php

namespace App\Services\Marketing;

use App\Models\Account\User as UserModel;
use App\Models\Marketing\Account as AccountModel;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class AccountService
{
    /**
     * @param UserModel $user
     * @return Collection
     */
    public function allAccounts(UserModel $user)
    {
        return $user->accounts()
            ->with('socialAccount')
            ->get();
    }

    /**
     * @param UserModel $user
     * @param array     $data
     * @return AccountModel
     */
    public function createAccount(UserModel $user, array $data)
    {
        /** @var AccountModel $account */
        $account = $user->accounts()
            ->create($data);

        return $this->readAccount($account, $user);
    }

    /**
     * @param AccountModel $account
     * @param UserModel    $user
     * @return AccountModel|null
     */
    public function readAccount(AccountModel $account, UserModel $user)
    {
        return $account->fresh('socialAccount');
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

        return $this->readAccount($account, $user);
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
