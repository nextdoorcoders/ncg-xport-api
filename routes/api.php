<?php

use App\Http\Controllers\Access\PermissionController;
use App\Http\Controllers\Access\RoleController;
use App\Http\Controllers\Account\ContactController;
use App\Http\Controllers\Account\UserController;
use App\Http\Controllers\Geo\LocationController;
use App\Http\Controllers\Geo\TimezoneController;
use App\Http\Controllers\Google\CampaignController as GoogleCampaignController;
use App\Http\Controllers\Marketing\Account\FacebookController as AccountFacebookController;
use App\Http\Controllers\Marketing\Account\GoogleController as AccountGoogleController;
use App\Http\Controllers\Marketing\AccountController;
use App\Http\Controllers\Marketing\CampaignController;
use App\Http\Controllers\Marketing\OrganizationController;
use App\Http\Controllers\Marketing\ProjectController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\Trigger\ConditionController;
use App\Http\Controllers\Trigger\GroupController;
use App\Http\Controllers\Trigger\MapController;
use App\Http\Controllers\Trigger\VendorController;
use App\Http\Controllers\Vendor\CurrencyController;
use App\Http\Controllers\Vendor\MediaSyncController;
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

        Route::post('switch/{victim}', [UserController::class, 'switch'])->middleware('auth:api');

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

            Route::get('permissions', [UserController::class, 'readCurrentUserPermissions']);
            Route::get('roles', [UserController::class, 'readCurrentUserRoles']);
        });
    });

    Route::group([
        'middleware' => 'auth:api',
        'prefix'     => 'contacts',
    ], function () {
        Route::get('', [ContactController::class, 'allContacts']);
        Route::post('', [ContactController::class, 'createContact']);

        Route::group([
            'prefix' => 'contact-{contact}',
        ], function () {
            Route::get('', [ContactController::class, 'readContact']);
            Route::put('', [ContactController::class, 'updateContact']);
            Route::delete('', [ContactController::class, 'deleteContact']);
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
    'prefix'     => 'access',
], function () {
    Route::group([
        'prefix' => 'permissions'
    ], function () {
        Route::get('', [PermissionController::class, 'allPermissions']);
        Route::post('', [PermissionController::class, 'createPermission']);

        Route::group([
            'prefix' => 'permission-{permission}',
        ], function () {
            Route::get('', [PermissionController::class, 'readPermission']);
            Route::put('', [PermissionController::class, 'updatePermission']);
            Route::delete('', [PermissionController::class, 'deletePermission']);

            Route::group([
                'prefix' => 'role-{role}'
            ], function () {
                Route::post('', [PermissionController::class, 'assignRole']);
                Route::delete('', [PermissionController::class, 'removeRole']);
            });
        });
    });

    Route::group([
        'prefix' => 'roles'
    ], function () {
        Route::get('', [RoleController::class, 'allRoles']);
        Route::post('', [RoleController::class, 'createRoles']);

        Route::group([
            'prefix' => 'role-{role}',
        ], function () {
            Route::get('', [RoleController::class, 'readRoles']);
            Route::put('', [RoleController::class, 'updateRoles']);
            Route::delete('', [RoleController::class, 'deleteRoles']);

            Route::group([
                'prefix' => 'permission-{permission}'
            ], function () {
                Route::post('', [RoleController::class, 'givePermissionTo']);
                Route::delete('', [RoleController::class, 'revokePermissionTo']);
            });
        });
    });
});

Route::group([
    'prefix' => 'marketing',
], function () {
    Route::group([
        'prefix' => 'accounts',
    ], function () {
        Route::get('', [AccountController::class, 'allAccounts'])->middleware('auth:api');

        Route::get('facebook', [AccountFacebookController::class, 'linkToProvider']);
        Route::post('facebook/callback', [AccountFacebookController::class, 'handleProviderCallback']);

        Route::get('google', [AccountGoogleController::class, 'linkToProvider']);
        Route::post('google/callback', [AccountGoogleController::class, 'handleProviderCallback']);

        Route::group([
            'middleware' => 'auth:api',
            'prefix'     => 'account-{account}',
        ], function () {
            Route::delete('', [AccountController::class, 'deleteAccount']);
        });
    });

    Route::group([
        'middleware' => 'auth:api',
        'prefix'     => 'projects',
    ], function () {
        Route::get('', [ProjectController::class, 'allProjects']);
        Route::post('', [ProjectController::class, 'createProject']);

        Route::group([
            'prefix' => 'project-{project}',
        ], function () {
            Route::get('', [ProjectController::class, 'readProject']);
            Route::put('', [ProjectController::class, 'updateProject']);
            Route::delete('', [ProjectController::class, 'deleteProject']);

            Route::get('maps', [ProjectController::class, 'readMaps']);
        });
    });

    Route::group([
        'middleware' => 'auth:api',
        'prefix'     => 'organizations',
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

    Route::group([
        'middleware' => 'auth:api',
        'prefix'     => 'campaigns',
    ], function () {
        Route::get('', [CampaignController::class, 'allCampaigns']);

        Route::get('map-{map}', [CampaignController::class, 'allMapCampaigns']);
        Route::post('map-{map}', [CampaignController::class, 'createMapCampaign']);

        Route::group([
            'prefix' => 'campaign-{campaign}',
        ], function () {
            Route::get('', [CampaignController::class, 'readCampaign']);
            Route::put('', [CampaignController::class, 'updateCampaign']);
            Route::delete('', [CampaignController::class, 'deleteCampaign']);
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
            Route::post('replicate', [ConditionController::class, 'replicateCondition']);
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
    'prefix'     => 'storages',
], function () {
    Route::post('', [StorageController::class, 'uploadFile']);

    Route::group([
        'prefix' => 'storage-{store}',
    ], function () {
        Route::delete('', [StorageController::class, 'deleteFile']);
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

    Route::group([
        'prefix' => 'media-syncs',
    ], function () {
        Route::post('', [MediaSyncController::class, 'createMediaSync']);
    });
});

Route::group([
    'middleware' => 'auth:api',
    'prefix'     => 'google',
], function () {
    Route::group([
        'prefix' => 'map-{map}',
    ], function () {
        Route::get('campaigns', [GoogleCampaignController::class, 'allGoogleCampaigns']);
    });
});
