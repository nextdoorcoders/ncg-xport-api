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
        'namespace' => 'SocialAccount',
        'prefix'    => 'social-account',
    ], function () {
        Route::get('facebook', 'FacebookController@redirectToProvider');
        Route::get('facebook/callback', 'FacebookController@handleProviderCallback');

        Route::get('google', 'GoogleController@redirectToProvider');
        Route::get('google/callback', 'GoogleController@handleProviderCallback');
    });
});

Route::group([
    'middleware' => 'auth:api',
    'namespace'  => 'Marketing',
    'prefix'     => 'marketing',
], function () {
    Route::group([
        'prefix' => 'campaigns',
    ], function () {
        Route::get('/', 'CampaignController@allCompanies');

        Route::group([
            'prefix' => 'social-account-{socialAccount}',
        ], function () {
            Route::get('', 'CampaignController@socialAccountAllCompanies');
            Route::post('', 'CampaignController@socialAccountCreateCompany');
        });
    });

    Route::group([
        'prefix' => 'projects/campaign-{campaign}',
    ], function () {
        Route::get('', 'ProjectController@campaignAllProjects');
        Route::post('', 'ProjectController@campaignCreateProject');
    });
});

Route::group([
    'middleware' => 'auth:api',
    'namespace'  => 'Geo',
    'prefix'     => 'geo',
], function () {
    Route::get('countries', 'CountryController@countries');
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
            'prefix' => 'campaigns/campaign-{campaign}',
        ], function () {
            Route::get('', 'CampaignController@index');
        });
    });
});
