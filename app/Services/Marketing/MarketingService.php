<?php

namespace App\Services\Marketing;

use App\Models\Account\User;

class MarketingService
{
    public function campaignsTree(User $user)
    {
        return $user->socialAccounts()
            ->with([
                'accounts' => function ($query) {
                    $query->with([
                        'campaigns'
                    ]);
                }
            ])
            ->get();
    }
}
