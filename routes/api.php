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
    'namespace' => 'Google',
    'prefix'    => 'google',
], function () {
    Route::group([
        'namespace' => 'AdWords',
        'prefix'    => 'adwords',
    ], function () {
        Route::get('campaigns', 'CampaignController@index');
    });
});
