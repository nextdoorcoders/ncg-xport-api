<?php

namespace App\Services\Marketing;

use App\Models\Account\User as UserModel;
use App\Models\Marketing\Account as AccountModel;
use App\Models\Marketing\Campaign as CampaignModel;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class CampaignService
{
    /**
     * @param AccountModel $account
     * @param UserModel    $user
     * @return Collection
     */
    public function allCampaigns(AccountModel $account, UserModel $user)
    {
        return $account->campaigns()
            ->get();
    }

    /**
     * @param AccountModel $account
     * @param UserModel    $user
     * @param array        $data
     * @return CampaignModel
     */
    public function createCampaign(AccountModel $account, UserModel $user, array $data)
    {
        /** @var CampaignModel $campaign */
        $campaign = $account->campaigns()
            ->create($data);

        return $this->readCampaign($campaign, $user);
    }

    /**
     * @param CampaignModel $campaign
     * @param UserModel     $user
     * @return CampaignModel|null
     */
    public function readCampaign(CampaignModel $campaign, UserModel $user)
    {
        return $campaign->fresh();
    }

    /**
     * @param CampaignModel $campaign
     * @param UserModel     $user
     * @param array         $data
     * @return CampaignModel|null
     */
    public function updateCampaign(CampaignModel $campaign, UserModel $user, array $data)
    {
        $campaign->fill($data);
        $campaign->save();

        return $this->readCampaign($campaign, $user);
    }

    /**
     * @param CampaignModel $campaign
     * @param UserModel     $user
     * @throws Exception
     */
    public function deleteCampaign(CampaignModel $campaign, UserModel $user)
    {
        try {
            $campaign->delete();
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
