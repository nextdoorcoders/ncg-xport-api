<?php

namespace App\Services\Marketing;

use App\Models\Account\User as UserModel;
use App\Models\Marketing\Campaign as CampaignModel;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class CampaignService
{
    /**
     * @param UserModel $user
     * @return Collection
     */
    public function allCampaigns(UserModel $user)
    {
        $accountIds = $user->projects()
            ->get()
            ->pluck('id');

        return CampaignModel::query()
            ->whereIn('account_id', $accountIds)
            ->get();
    }

    /**
     * @param UserModel $user
     * @param array     $data
     * @return CampaignModel
     */
    public function createCampaign(UserModel $user, array $data)
    {
        /** @var CampaignModel $campaign */
        $campaign = CampaignModel::query()
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
        return $campaign->fresh([
            'account',
            'project',
        ]);
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
