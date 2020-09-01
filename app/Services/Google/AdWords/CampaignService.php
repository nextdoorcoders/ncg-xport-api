<?php

namespace App\Services\Google\AdWords;

use App\Exceptions\MessageException;
use App\Models\Marketing\Account as AccountModel;
use App\Models\Marketing\Campaign;
use App\Repositories\Google\AdWords\CampaignRepository;

class CampaignService
{
    protected CampaignRepository $campaignRepository;

    /**
     * CampaignService constructor.
     *
     * @param CampaignRepository $campaignRepository
     */
    public function __construct(CampaignRepository $campaignRepository)
    {
        $this->campaignRepository = $campaignRepository;
    }

    /**
     * @param AccountModel $account
     * @return \Illuminate\Support\Collection
     * @throws MessageException
     */
    public function allCampaigns(AccountModel $account)
    {
        $this->campaignRepository->setAccount($account);

        $campaigns = $this->campaignRepository->paginate();

        $existedCampaigns = Campaign::query()
            ->whereIn('campaign_id', $campaigns->pluck('id'))
            ->get()
            ->pluck('id', 'campaign_id')
            ->toArray();

        $campaigns->each(function ($campaign) use ($existedCampaigns) {
            $campaign->campaign_imported = array_key_exists($campaign->id, $existedCampaigns);

            if ($campaign->campaign_imported === true) {
                $campaign->xport_campaign_id = $existedCampaigns[$campaign->id];
            }
        });

        return $campaigns;
    }
}
