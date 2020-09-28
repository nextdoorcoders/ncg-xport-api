<?php

use App\Http\Controllers\Account\UserController;
use App\Http\Controllers\Geo\LocationController;
use App\Http\Controllers\Geo\TimezoneController;
use App\Http\Controllers\Google\CampaignController as GoogleCampaignController;
use App\Http\Controllers\Marketing\Account\FacebookController;
use App\Http\Controllers\Marketing\Account\GoogleController;
use App\Http\Controllers\Marketing\AccountController;
use App\Http\Controllers\Marketing\CampaignController;
use App\Http\Controllers\Marketing\OrganizationController;
use App\Http\Controllers\Marketing\ProjectController;
use App\Http\Controllers\Trigger\ConditionController;
use App\Http\Controllers\Trigger\GroupController;
use App\Http\Controllers\Trigger\MapController;
use App\Http\Controllers\Trigger\VendorController;
use App\Http\Controllers\Vendor\CurrencyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix' => 'accounts',
], function () {
    Route::group([
        'prefix' => 'auth',
    ], function () {
        Route::post('login', [UserController::class, 'login'])->middleware('guest:api');
        Route::delete('logout', [UserController::class, 'logout'])->middleware('auth:api');
        Route::post('register', [UserController::class, 'register'])->middleware('guest:api');

        Route::group([
            'middleware' => 'guest:api',
            'prefix'     => 'forgot',
        ], function () {
            Route::post('send-code', [UserController::class, 'forgotSendCode']);
            Route::post('confirm-code', [UserController::class, 'forgotConfirmCode']);
        });
    });

    Route::group([
        'middleware' => 'auth:api',
        'prefix'     => 'users',
    ], function () {
        Route::get('', [UserController::class, 'allUsers']);

        Route::group([
            'prefix' => 'user-{user}',
        ], function () {
            Route::get('', [UserController::class, 'readUser']);
        });

        Route::group([
            'prefix' => 'current',
        ], function () {
            Route::get('', [UserController::class, 'readCurrentUser']);
            Route::put('', [UserController::class, 'updateCurrentUser']);
        });
    });
});

Route::group([
    'middleware' => 'auth:api',
    'prefix'     => 'geo',
], function () {
    Route::group([
        'prefix' => 'locations',
    ], function () {
        Route::get('', [LocationController::class, 'allLocations']);
        Route::post('', [LocationController::class, 'createLocation']);

        Route::group([
            'prefix' => 'location-{location}',
        ], function () {
            Route::get('', [LocationController::class, 'readLocation']);
            Route::put('', [LocationController::class, 'updateLocation']);
            Route::delete('', [LocationController::class, 'deleteLocation']);

            Route::get('vendors', [LocationController::class, 'readVendors']);
        });

        Route::get('vendors', [LocationController::class, 'readVendors']);
    });

    Route::group([
        'prefix' => 'timezones',
    ], function () {
        Route::get('', [TimezoneController::class, 'allTimezones']);
    });
});

Route::group([
    'middleware' => 'auth:api',
    'prefix'     => 'marketing',
], function () {
    Route::group([
        'prefix' => 'accounts',
    ], function () {
        Route::get('', [AccountController::class, 'allAccounts'])->middleware('auth:api');

        Route::get('facebook', [FacebookController::class, 'linkToProvider']);
        Route::post('facebook/callback', [FacebookController::class, 'handleProviderCallback']);

        Route::get('google', [GoogleController::class, 'linkToProvider']);
        Route::post('google/callback', [GoogleController::class, 'handleProviderCallback']);
    });

    Route::group([
        'prefix' => 'projects',
    ], function () {
        Route::get('', [ProjectController::class, 'allProjects']);
        Route::post('', [ProjectController::class, 'createProject']);

        Route::group([
            'prefix' => 'project-{project}',
        ], function () {
            Route::get('', [ProjectController::class, 'readProject']);
            Route::put('', [ProjectController::class, 'updateProject']);
            Route::delete('', [ProjectController::class, 'deleteProject']);

            Route::group([
                'prefix' => 'campaigns',
            ], function () {
                Route::get('', [CampaignController::class, 'allCampaigns']);
                Route::post('', [CampaignController::class, 'createCampaign']);

                Route::group([
                    'prefix' => 'campaign-{campaign}',
                ], function () {
                    Route::get('', [CampaignController::class, 'readCampaign']);
                    Route::put('', [CampaignController::class, 'updateCampaign']);
                    Route::delete('', [CampaignController::class, 'deleteCampaign']);
                });
            });
        });
    });

    Route::group([
        'prefix' => 'organizations',
    ], function () {
        Route::get('', [OrganizationController::class, 'allOrganizations']);
        Route::post('', [OrganizationController::class, 'createOrganization']);

        Route::group([
            'prefix' => 'organization-{organization}',
        ], function () {
            Route::get('', [OrganizationController::class, 'readOrganization']);
            Route::put('', [OrganizationController::class, 'updateOrganization']);
            Route::delete('', [OrganizationController::class, 'deleteOrganization']);
        });
    });
});

Route::group([
    'middleware' => 'auth:api',
    'prefix'     => 'trigger',
], function () {
    Route::group([
        'prefix' => 'maps',
    ], function () {
        Route::get('', [MapController::class, 'allMaps']);
        Route::post('', [MapController::class, 'createMap']);

        Route::group([
            'prefix' => 'map-{map}',
        ], function () {
            Route::get('', [MapController::class, 'readMap']);
            Route::put('', [MapController::class, 'updateMap']);
            Route::delete('', [MapController::class, 'deleteMap']);

            Route::post('replicate', [MapController::class, 'replicateMap']);

            Route::get('projects', [MapController::class, 'readProjects']);

            Route::group([
                'prefix' => 'conditions',
            ], function () {
                Route::get('', [MapController::class, 'readConditions']);
                Route::put('', [MapController::class, 'updateConditions']);
            });
        });
    });

    Route::group([
        'prefix' => 'groups',
    ], function () {
        Route::group([
            'prefix' => 'map-{map}',
        ], function () {
            Route::get('', [GroupController::class, 'allGroups']);
            Route::post('', [GroupController::class, 'createGroup']);
        });

        Route::group([
            'prefix' => 'group-{group}',
        ], function () {
            Route::get('', [GroupController::class, 'readGroup']);
            Route::put('', [GroupController::class, 'updateGroup']);
            Route::delete('', [GroupController::class, 'deleteGroup']);
        });
    });

    Route::group([
        'prefix' => 'conditions',
    ], function () {
        Route::group([
            'prefix' => 'group-{group}',
        ], function () {
            Route::get('', [ConditionController::class, 'allConditions']);
            Route::post('', [ConditionController::class, 'createCondition']);
        });

        Route::group([
            'prefix' => 'condition-{condition}',
        ], function () {
            Route::get('', [ConditionController::class, 'readCondition']);
            Route::put('', [ConditionController::class, 'updateCondition']);
            Route::delete('', [ConditionController::class, 'deleteCondition']);
        });
    });

    Route::group([
        'prefix' => 'vendors',
    ], function () {
        Route::get('', [VendorController::class, 'allVendors']);
        Route::post('', [VendorController::class, 'createVendor']);

        Route::group([
            'prefix' => 'vendor-{vendor}',
        ], function () {
            Route::get('', [VendorController::class, 'readVendor']);
            Route::put('', [VendorController::class, 'updateVendor']);
            Route::delete('', [VendorController::class, 'deleteVendor']);
        });
    });
});

Route::group([
    'middleware' => 'auth:api',
    'prefix'     => 'vendor',
], function () {
    Route::group([
        'prefix' => 'currencies',
    ], function () {
        Route::get('', [CurrencyController::class, 'allCurrencies']);
    });
});

Route::group([
    'middleware' => 'auth:api',
    'prefix'     => 'google',
], function () {
    Route::group([
        'prefix' => 'project-{project}',
    ], function () {
        Route::get('campaigns', [GoogleCampaignController::class, 'allGoogleCampaigns']);
    });
});
