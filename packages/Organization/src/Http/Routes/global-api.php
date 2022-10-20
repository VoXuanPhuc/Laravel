<?php

use Encoda\Organization\Http\Controllers\Tenant\OrganizationSettingController;
use Illuminate\Support\Facades\Route;

Route::group( ['prefix' => '/identity/api/v1/' ] , function() {

    Route::get('/configs', [
        'as' => 'organization.configs',
        'uses' =>  OrganizationSettingController::class. '@getConfigsByDomain'
    ]);
});
