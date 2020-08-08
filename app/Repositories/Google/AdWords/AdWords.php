<?php

namespace App\Repositories\Google\AdWords;

use App\Exceptions\MessageException;
use App\Models\Account\SocialAccount as SocialAccountModel;
use App\Models\Marketing\Company as CompanyModel;
use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\Common\Configuration;
use Google\AdsApi\Common\OAuth2TokenBuilder;

abstract class AdWords
{
    protected AdWordsServices $services;

    protected AdWordsSessionBuilder $sessionBuilder;

    /**
     * AdWords constructor.
     *
     * @param AdWordsServices $services
     */
    public function __construct(AdWordsServices $services)
    {
        $this->services = $services;
    }

    /**
     * @param CompanyModel $campaign
     * @throws MessageException
     */
    public function setCampaign(CompanyModel $campaign)
    {
        if ($campaign->socialAccount->provider_name != SocialAccountModel::PROVIDER_NAME_GOOGLE) {
            throw new MessageException('This social account does not support here');
        }

        $this->sessionBuilder = $this->getSessionBuilder($campaign);
    }

    /**
     * @param CompanyModel $compaign
     * @return AdWordsSessionBuilder|\Google\AdsApi\Common\AdsBuilder
     */
    private function getSessionBuilder(CompanyModel $compaign)
    {
        $configuration = $this->getConfiguration($compaign);

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

    /**
     * @param CompanyModel $campaign
     * @return Configuration
     */
    protected function getConfiguration(CompanyModel $campaign)
    {
        $configuration = [
            'ADWORDS' => [
                /*
                 * Required AdWords API properties. Details can be found at:
                 * https://developers.google.com/adwords/api/docs/guides/basic-concepts#soap_and_xml
                 */

                'developerToken'   => $campaign->parameters['developer_token'],
                'clientCustomerId' => $campaign->parameters['campaign_id'],

                /*
                 * Optional. Set a friendly application name identifier.
                 */

                'userAgent' => env('GOOGLE.ADWORDS.USER_AGENT', env('APP_NAME', null)),

                /*
                 * Optional additional AdWords API settings.
                 */

                // 'endpoint' => 'https://adwords.google.com/',
                // 'isPartialFailure' => false,

                /*
                 * Optional setting for utility usage tracking in the user agent in requests.
                 * Defaults to true.
                 */

                // 'includeUtilitiesInUserAgent' => true,
            ],

            'ADWORDS_REPORTING' => [
                /*
                 * Optional reporting settings.
                 */

                // 'isSkipReportHeader'  => false,
                // 'isSkipColumnHeader'  => false,
                // 'isSkipReportSummary' => false,
                // 'isUseRawEnumValues'  => false,
            ],

            'OAUTH2' => [
                /*
                 * Required OAuth2 credentials. Uncomment and fill in the values for the
                 * appropriate flow based on your use case. See the README for guidance:
                 * https://github.com/googleads/googleads-php-lib/blob/master/README.md#getting-started
                 */

                /*
                 * For installed application or web application flow.
                 */

                'clientId'     => env('GOOGLE_CLIENT_ID', null),
                'clientSecret' => env('GOOGLE_CLIENT_SECRET', null),
                'refreshToken' => $campaign->socialAccount->refresh_token,

                /*
                 * For service account flow.
                 */

                // 'jsonKeyFilePath'   => 'INSERT_ABSOLUTE_PATH_TO_OAUTH2_JSON_KEY_FILE_HERE',
                // 'scopes'            => 'https://www.googleapis.com/auth/adwords',
                // 'impersonatedEmail' => "INSERT_EMAIL_OF_ACCOUNT_TO_IMPERSONATE_HERE",
            ],

            'SOAP' => [
                /*
                 * Optional SOAP settings. See SoapSettingsBuilder.php for more information.
                 */

                // 'compressionLevel' => null,
            ],

            'CONNECTION' => [
                /*
                 * Optional proxy settings to be used by requests.
                 * If you don't have username and password, just specify host and port.
                 */

                // 'proxy' => 'protocol://user:pass@host:port',

                /*
                 * Enable transparent HTTP gzip compression for all reporting requests.
                 */

                // 'enableReportingGzip' => false,
            ],

            'LOGGING' => [
                /*
                 * Optional logging settings.
                 */

                'soapLogFilePath'             => storage_path('logs/google/soap.log'),
                'soapLogLevel'                => 'INFO',
                'reportDownloaderLogFilePath' => storage_path('logs/google/report-downloader.log'),
                'reportDownloaderLogLevel'    => 'INFO',
                'batchJobsUtilLogFilePath'    => storage_path('logs/google/bjutil.log'),
                'batchJobsUtilLogLevel'       => 'INFO',
            ],
        ];

        return new Configuration($configuration);
    }
}
