<?php

namespace App\Services\Google;

use Google\Auth\CredentialsLoader;
use Google\Auth\OAuth2;

class AuthorizationService
{
    /**
     * @var string the OAuth2 scope for the AdWords API
     * @see https://developers.google.com/adwords/api/docs/guides/authentication#scope
     */
    const ADWORDS_API_SCOPE = 'https://www.googleapis.com/auth/adwords';

    /**
     * @var string the OAuth2 scope for the Ad Manger API
     * @see https://developers.google.com/ad-manager/docs/authentication#scope
     */
    const AD_MANAGER_API_SCOPE = 'https://www.googleapis.com/auth/dfp';

    /**
     * @var string the Google OAuth2 authorization URI for OAuth2 requests
     * @see https://developers.google.com/identity/protocols/OAuth2InstalledApp#formingtheurl
     */
    const AUTHORIZATION_URI = 'https://accounts.google.com/o/oauth2/v2/auth';

    /**
     * @var string the redirect URI for OAuth2 installed application flows
     * @see https://developers.google.com/identity/protocols/OAuth2InstalledApp#formingtheurl
     */
    const REDIRECT_URI = 'http://api.ncg.io/api/auth/social-account/google/callback'; // urn:ietf:wg:oauth:2.0:oob

    /**
     * @param string|null $clientId
     * @param string|null $clientSecret
     * @param string|null $scope
     * @return OAuth2
     */
    public function oAuth2(string $clientId = null, string $clientSecret = null, string $scope = null): OAuth2
    {
        $credentials = config('google.OAUTH2');

        $clientId = $clientId ?: $credentials['clientId'];
        $clientSecret = $clientSecret ?: $credentials['clientSecret'];
        $scope = $scope ?: self::ADWORDS_API_SCOPE;

        return new OAuth2([
            'authorizationUri'   => self::AUTHORIZATION_URI,
            'redirectUri'        => self::REDIRECT_URI,
            'tokenCredentialUri' => CredentialsLoader::TOKEN_CREDENTIAL_URI,
            'clientId'           => $clientId,
            'clientSecret'       => $clientSecret,
            'scope'              => $scope,
        ]);
    }

    /**
     * Fetch auth token
     *
     * @param OAuth2 $oAuth2
     * @param string $code
     * @return array
     */
    public function fetchAuthToken(OAuth2 $oAuth2, string $code)
    {
        $oAuth2->setCode($code);

        return $oAuth2->fetchAuthToken();
    }
}
