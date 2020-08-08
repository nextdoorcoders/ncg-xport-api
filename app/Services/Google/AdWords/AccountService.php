<?php

namespace App\Services\Google\AdWords;

use App\Repositories\Google\AdWords\AccountRepository;

class AccountService
{
    protected AccountRepository $accountRepository;

    /**
     * AccountService constructor.
     *
     * @param AccountRepository $accountRepository
     */
    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    /**
     *
     */
    public function index()
    {
        return $this->accountRepository->paginate();
    }
}
