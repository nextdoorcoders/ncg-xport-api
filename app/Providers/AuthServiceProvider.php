<?php

namespace App\Providers;

use App\Models\Marketing\Campaign as CampaignModel;
use App\Models\Marketing\Project as ProjectModel;
use App\Models\Token;
use App\Models\Trigger\Map as MapModel;
use App\Policies\Marketing\CampaignPolicy;
use App\Policies\Marketing\ProjectPolicy;
use App\Policies\Trigger\MapPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Sanctum\Sanctum;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        CampaignModel::class => CampaignPolicy::class,
        ProjectModel::class  => ProjectPolicy::class,
        MapModel::class      => MapPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            if ($user->hasRole('supervisor')) {
                return true;
            }
        });
    }

    public function register()
    {
        Sanctum::usePersonalAccessTokenModel(Token::class);

        Sanctum::ignoreMigrations();
    }
}
