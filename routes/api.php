<?php

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
    'namespace' => 'Account',
    'prefix'    => 'accounts',
], function () {
    Route::group([
        'prefix' => 'auth',
    ], function () {
        Route::post('login', 'UserController@login')->middleware('guest:api');
        Route::delete('logout', 'UserController@logout')->middleware('auth:api');
        Route::post('register', 'UserController@register')->middleware('guest:api');

        Route::group([
            'middleware' => 'guest:api',
            'prefix'     => 'forgot',
        ], function () {
            Route::post('send-code', 'UserController@forgotSendCode');
            Route::post('confirm-code', 'UserController@forgotConfirmCode');
        });
    });

    Route::group([
        'middleware' => 'auth:api',
        'prefix'     => 'users',
    ], function () {
        Route::get('', 'UserController@allUsers');

        Route::group([
            'prefix' => 'user-{user}',
        ], function () {
            Route::get('', 'UserController@readUser');
        });

        Route::group([
            'prefix' => 'current',
        ], function () {
            Route::get('', 'UserController@readCurrentUser');
            Route::put('', 'UserController@updateCurrentUser');
        });
    });

    Route::group([
        'prefix' => 'social-account',
    ], function () {
        Route::get('', 'SocialAccountController@allSocialAccounts')->middleware('auth:api');

        Route::group([
            'namespace' => 'SocialAccount',
        ], function () {
            Route::get('facebook', 'FacebookController@linkToProvider');
            Route::post('facebook/callback', 'FacebookController@handleProviderCallback');

            Route::get('google', 'GoogleController@linkToProvider');
            Route::post('google/callback', 'GoogleController@handleProviderCallback');
        });
    });
});

Route::group([
    'middleware' => 'auth:api',
    'namespace'  => 'Marketing',
    'prefix'     => 'marketing',
], function () {
    Route::group([
        'prefix' => 'accounts',
    ], function () {
        Route::get('', 'AccountController@allAccounts');
        Route::post('', 'AccountController@createAccount');

        Route::group([
            'prefix' => 'account-{account}',
        ], function () {
            Route::get('', 'AccountController@readAccount');
            Route::put('', 'AccountController@updateAccount');
            Route::delete('', 'AccountController@deleteAccount');
        });
    });

    Route::group([
        'prefix' => 'campaigns',
    ], function () {
        Route::get('', 'CampaignController@allCampaigns');
        Route::post('', 'CampaignController@createCampaign');

        Route::group([
            'prefix' => 'campaign-{campaign}',
        ], function () {
            Route::get('', 'CampaignController@readCampaign');
            Route::put('', 'CampaignController@updateCampaign');
            Route::delete('', 'CampaignController@deleteCampaign');
        });
    });

    Route::group([
        'prefix' => 'projects',
    ], function () {
        Route::get('', 'ProjectController@allProjects');
        Route::post('', 'ProjectController@createProject');

        Route::group([
            'prefix' => 'project-{project}',
        ], function () {
            Route::get('', 'ProjectController@readProject');
            Route::put('', 'ProjectController@updateProject');
            Route::delete('', 'ProjectController@deleteProject');

            Route::post('replicate', 'ProjectController@replicateProject');

            Route::get('campaigns', 'ProjectController@allCampaigns');

            Route::group([
                'prefix' => 'triggers',
            ], function () {
                Route::get('', 'ProjectController@allTriggers');
                Route::put('', 'ProjectController@updateTriggers');
            });
        });
    });

    Route::group([
        'prefix' => 'groups',
    ], function () {
        Route::group([
            'prefix' => 'project-{project}',
        ], function () {
            Route::get('', 'GroupController@allGroups');
            Route::post('', 'GroupController@createGroup');
        });

        Route::group([
            'prefix' => 'group-{group}',
        ], function () {
            Route::get('', 'GroupController@readGroup');
            Route::put('', 'GroupController@updateGroup');
            Route::delete('', 'GroupController@deleteGroup');
        });
    });

    Route::group([
        'prefix' => 'conditions',
    ], function () {
        Route::get('group-{group}', 'ConditionController@allByGroup');
        Route::get('vendor-{vendor}', 'ConditionController@allByVendor');
        Route::post('group-{group}/vendor-location-{vendorLocation}', 'ConditionController@createCondition');

        Route::group([
            'prefix' => 'condition-{condition}',
        ], function () {
            Route::get('', 'ConditionController@readCondition');
            Route::put('', 'ConditionController@updateCondition');
            Route::delete('', 'ConditionController@deleteCondition');
        });
    });

    Route::group([
        'prefix' => 'vendors',
    ], function () {
        Route::get('', 'VendorController@allVendors');
    });

    Route::group([
        'prefix' => 'vendors-location',
    ], function () {
        Route::group([
            'prefix' => 'project-{project}',
        ], function () {
            Route::get('free', 'VendorLocationController@freeProjectVendorsLocation');
            Route::get('busy', 'VendorLocationController@busyProjectVendorsLocation');
        });
    });
});

Route::group([
    'middleware' => 'auth:api',
    'namespace'  => 'Geo',
    'prefix'     => 'geo',
], function () {
    Route::get('countries', 'CountryController@allCountries');
    Route::post('countries', 'CountryController@createCountry');

    Route::get('countries/country-{country}', 'CountryController@readCountry');


    Route::get('states/country-{country}', 'StateController@allByCountry');
    Route::post('states/country-{country}', 'StateController@createState');

    Route::get('states/state-{state}', 'StateController@readState');


    Route::get('cities/state-{state}', 'CityController@allByState');
    Route::post('cities/state-{state}', 'CityController@createCity');

    Route::get('cities/city-{city}', 'CityController@readCity');
});

Route::group([
    'middleware' => 'auth:api',
    'namespace'  => 'Google',
    'prefix'     => 'google',
], function () {
    Route::group([
        'prefix' => 'campaigns/account-{account}',
    ], function () {
        Route::get('', 'CampaignController@allCampaigns');
    });
});
