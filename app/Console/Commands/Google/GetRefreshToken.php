<?php

namespace App\Console\Commands\Google;

use App\Services\Google\AuthorizationService;
use Exception;
use Illuminate\Console\Command;

class GetRefreshToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:get_refresh_token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new refresh token for Google Ads API';

    protected $authorizationService;

    /**
     * Create a new command instance.
     *
     * @param AuthorizationService $authorizationService
     */
    public function __construct(AuthorizationService $authorizationService)
    {
        parent::__construct();

        $this->authorizationService = $authorizationService;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $scopes = $this->scopes();

        $oauth2 = $this->authorizationService->oAuth2(null, null, implode(' ', $scopes));

        $this->line('Log into the Google account and visit the following URL:');
        $this->line($oauth2->buildFullAuthorizationUri());

        $code = $this->ask('Insert the code you received from Google:');

        try {
            $authToken = $this->authorizationService->fetchAuthToken($oauth2, $code);
        } catch (Exception $exception) {
            $this->error($exception->getMessage());

            return;
        }

        $this->line('Your refresh token is:');
        $this->line($authToken['refresh_token']);
    }

    /**
     * Select scopes
     *
     * @return array
     */
    private function scopes()
    {
        $products = [
            'AdWords API'    => AuthorizationService::ADWORDS_API_SCOPE,
            'Ad Manager API' => AuthorizationService::AD_MANAGER_API_SCOPE,
        ];

        $scopes = [];

        foreach ($products as $product => $scope) {
            if (!$this->confirm(sprintf('Would you like to activate %s?', $product), true)) {
                continue;
            }

            array_push($scopes, $scope);
        }

        if (!count($scopes)) {
            $this->error('You have to select at least one scope');

            return $this->scopes();
        }

        return $scopes;
    }
}
