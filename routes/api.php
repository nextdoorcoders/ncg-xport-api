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
    'prefix'    => 'auth',
], function () {
    Route::post('login', 'AccountController@login')->middleware('guest:api');
    Route::delete('logout', 'AccountController@logout')->middleware('auth:api');
    Route::post('register', 'AccountController@register')->middleware('guest:api');

    Route::get('user', 'AccountController@user')->middleware('auth:api');

    Route::group([
        'prefix' => 'social-account',
    ], function () {
        Route::get('', 'SocialAccountController@index')->middleware('auth:api');

        Route::group([
            'namespace' => 'SocialAccount',
        ], function () {
            Route::get('facebook', 'FacebookController@redirectToProvider');
            Route::get('facebook/callback', 'FacebookController@handleProviderCallback');

            Route::get('google', 'GoogleController@redirectToProvider');
            Route::get('google/callback', 'GoogleController@handleProviderCallback');
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
        Route::get('social-account-{socialAccount}', 'AccountController@allAccountsOfSocialAccount');

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
        'prefix' => 'campaigns/account-{account}',
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
        });
    });

    Route::group([
        'prefix' => 'groups/project-{project}',
    ], function () {
        Route::get('', 'GroupController@allGroups');
        Route::post('', 'GroupController@createGroup');

        Route::group([
            'prefix' => 'group-{group}',
        ], function () {
            Route::get('', 'GroupController@readGroup');
            Route::put('', 'GroupController@updateGroup');
            Route::delete('', 'GroupController@deleteGroup');
        });
    });

    Route::group([
        'prefix' => 'vendors',
    ], function () {
        Route::get('', 'VendorController@allVendors');

        Route::group([
            'prefix' => 'project-{project}',
        ], function () {
            Route::get('free-vendors', 'VendorController@freeProjectVendors');
            Route::get('busy-vendors', 'VendorController@busyProjectVendors');
        });
    });
});

Route::group([
    'middleware' => 'auth:api',
    'namespace'  => 'Geo',
    'prefix'     => 'geo',
], function () {
    Route::get('countries', 'CountryController@index');
    Route::post('countries', 'CountryController@create');

    Route::get('states/country-{country}', 'StateController@index');
    Route::post('states/country-{country}', 'StateController@create');

    Route::get('cities/state-{state}', 'CityController@index');
    Route::post('cities/state-{state}', 'CityController@create');
});

Route::group([
    'middleware' => 'auth:api',
    'namespace'  => 'Google',
    'prefix'     => 'google',
], function () {
    Route::group([
        'namespace' => 'AdWords',
        'prefix'    => 'adwords',
    ], function () {
        Route::group([
            'prefix' => 'campaigns/account-{account}',
        ], function () {
            Route::get('', 'CampaignController@index');
        });
    });
});
