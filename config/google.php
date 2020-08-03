<?php

return [
    'ADWORDS' => [
        /*
         * Required AdWords API properties. Details can be found at:
         * https://developers.google.com/adwords/api/docs/guides/basic-concepts#soap_and_xml
         */

        'developerToken'   => env('GOOGLE.ADWORDS.DEVELOPER_TOKEN', null),
        'clientCustomerId' => env('GOOGLE.ADWORDS.CLIENT_CUSTOMER_ID', null),

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

        'clientId'     => env('GOOGLE.OAUTH2.CLIENT_ID', null),
        'clientSecret' => env('GOOGLE.OAUTH2.CLIENT_SECRET', null),
        'refreshToken' => env('GOOGLE.OAUTH2.REFRESH_TOKEN', null),

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
