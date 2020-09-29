<?php

namespace App\Repositories\Google\AdWords;

use App\Exceptions\MessageException;
use App\Models\Marketing\Account as SocialProjectModel;
use App\Models\Marketing\Project as ProjectModel;
use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\Common\AdsBuilder;
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
     * @param ProjectModel $project
     * @throws MessageException
     */
    public function setAccount(ProjectModel $project)
    {
        if (!$project->account) {
            throw new MessageException('Account required', 'Can\'t get a list of marketing campaigns. Account required');
        }

        if ($project->account->provider_name != SocialProjectModel::PROVIDER_NAME_GOOGLE) {
            throw new MessageException('This account does not support here');
        }

        $this->sessionBuilder = $this->getSessionBuilder($project);
    }

    /**
     * @param ProjectModel $project
     * @return AdWordsSessionBuilder|AdsBuilder
     */
    private function getSessionBuilder(ProjectModel $project)
    {
        $configuration = $this->getConfiguration($project);

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
     * @param ProjectModel $account
     * @return Configuration
     */
    protected function getConfiguration(ProjectModel $account)
    {
        $configuration = [
            'ADWORDS' => [
                /*
                 * Required AdWords API properties. Details can be found at:
                 * https://developers.google.com/adwords/api/docs/guides/basic-concepts#soap_and_xml
                 */

                'developerToken'   => $account->parameters['developer_token'],
                'clientCustomerId' => $account->parameters['account_id'],

                /*
                 * Optional. Set a friendly application name identifier.
                 */

                'userAgent' => config('app.name'),

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

                'clientId'     => config('services.google.client_id', null),
                'clientSecret' => config('services.google.client_secret', null),
                'refreshToken' => $account->account->refresh_token,

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
