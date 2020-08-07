<?php

namespace App\Repositories\Google\AdWords;

use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\Common\Configuration;
use Google\AdsApi\Common\OAuth2TokenBuilder;

abstract class AdWords
{
    protected AdWordsServices $services;

    protected AdWordsSessionBuilder $sessionBuilder;

    public function __construct(AdWordsServices $adWordsServices, AdWordsSessionBuilder $adWordsSessionBuilder)
    {
        $this->services = $this->getAdWordsServices();

        $this->sessionBuilder = $this->getAdWordsSessionBuilder();
    }

    /**
     * @return AdWordsServices
     */
    private function getAdWordsServices()
    {
        return new AdWordsServices();
    }

    /**
     * @return AdWordsSessionBuilder|\Google\AdsApi\Common\AdsBuilder
     */
    private function getAdWordsSessionBuilder()
    {
        $configuration = new Configuration(config('google'));

        // Generate a refreshable OAuth2 credential for authentication.
        $oAuth2Credential = (new OAuth2TokenBuilder())
            ->from($configuration)
            ->build();

        // Construct an API session configured from a properties file and the
        // OAuth2 credentials above.
        return (new AdWordsSessionBuilder())
            ->from($configuration)
            ->withOAuth2Credential($oAuth2Credential);
    }
}
