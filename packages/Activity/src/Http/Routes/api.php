<?php

use Encoda\Activity\Http\Controllers\Tenant\ActivityController;
use Encoda\Activity\Http\Controllers\Tenant\ApplicationController;
use Encoda\Activity\Http\Controllers\Tenant\DisruptionScenarioController;
use Encoda\Activity\Http\Controllers\Tenant\EquipmentController;
use Encoda\Activity\Http\Controllers\Tenant\RecoveryTimeController;
use Encoda\Activity\Http\Controllers\Tenant\RemoteAccessFactorController;
use Encoda\Activity\Http\Controllers\Tenant\TolerableTimePeriodController;
use Encoda\Activity\Http\Controllers\Tenant\UtilityController;
use Illuminate\Support\Facades\Route;
use Laravel\Lumen\Routing\Router;

Route::group( ['prefix' => '/identity/api/v1/', 'middleware'=> ['auth']] , function() {


    Route::get('/activities', [
        'as' => 'activities.list',
        'uses' => ActivityController::class . '@index'
    ]);


    Route::get('/activities/top', [
        'as' => 'activities.top',
        'uses' => ActivityController::class . '@top'
    ]);

    Route::get('/activities/{uid}', [
        'as' => 'activities.detail',
        'uses' => ActivityController::class . '@detail'
    ]);

    Route::post('/activities', [
        'as' => 'activities.create',
        'uses' => ActivityController::class . '@create'
    ]);

    Route::get('/activities/download/all', [
        'as' => 'activities.download',
        'uses' => ActivityController::class . '@export'
    ]);

    Route::put('/activities/{uid}', [
        'as' => 'activities.update',
        'uses' => ActivityController::class . '@update'
    ]);

    Route::delete('/activities/{uid}', [
        'as' => 'activities.delete',
        'uses' => ActivityController::class . '@delete'
    ]);
    Route::delete('/activities/{uid}/permanent', [
        'as' => 'activities.delete',
        'uses' => ActivityController::class . '@permanentDelete'
    ]);

    //======= ACTIVITY AND RELATIONSHIP =========/
    Route::put('/activities/{activityUid}/remote-access-factors', [
        'as' => 'activities.remote-access-factors-update',
        'uses' => ActivityController::class . '@saveRemoteAccessFactors'
    ]);

    Route::put('/activities/{activityUid}/applications-and-equipments', [
        'as' => 'activities.applications-and-equipments.update',
        'uses' => ActivityController::class . '@saveApplicationsAndEquipments'
    ]);

    Route::put('/activities/{activityUid}/tolerable-period-disruptions', [
        'as' => 'activities.tolerable-period-disruptions-update',
        'uses' => ActivityController::class . '@saveTolerablePeriodDisruptions'
    ]);

    Route::put('/activities/{activityUid}/recovery-time-and-disruption-scenario', [
        'as' => 'activities.recovery-time-and-disruption-scenario.update',
        'uses' => ActivityController::class . '@saveRecoveryTimeAndDisruptionScenario'
    ]);


    // ============== UTILITIES ===============/
    Route::get('/utilities', [
        'as' => 'utilities.list',
        'uses' => UtilityController::class . '@index'
    ]);

    Route::get('/utilities/{uid}', [
        'as' => 'utilities.detail',
        'uses' => UtilityController::class . '@detail'
    ]);

    Route::post('/utilities', [
        'as' => 'utilities.create',
        'uses' => UtilityController::class . '@create'
    ]);

    Route::put('/utilities/{uid}', [
        'as' => 'utilities.update',
        'uses' => UtilityController::class . '@update'
    ]);

    Route::delete('/utilities/{uid}', [
        'as' => 'utilities.delete',
        'uses' => UtilityController::class . '@delete'
    ]);


    // ============= EQUIPMENT ============
    // =========== equipments =========/
    Route::get('/equipments', [
        'as' => 'equipments.list',
        'uses' => EquipmentController::class . '@index'
    ]);

    Route::get('/equipments/{uid}', [
        'as' => 'equipments.detail',
        'uses' => EquipmentController::class . '@detail'
    ]);

    Route::post('/equipments', [
        'as' => 'equipments.create',
        'uses' => EquipmentController::class . '@create'
    ]);

    Route::put('/equipments/{uid}', [
        'as' => 'equipments.update',
        'uses' => EquipmentController::class . '@update'
    ]);

    Route::delete('/equipments/{uid}', [
        'as' => 'equipments.delete',
        'uses' => EquipmentController::class . '@delete'
    ]);

    // =============== APPLICATION ================//
    // =========== applications =========/
    Route::get('/applications', [
        'as' => 'applications.list',
        'uses' => ApplicationController::class . '@index'
    ]);

    Route::get('/applications/{uid}', [
        'as' => 'applications.detail',
        'uses' => ApplicationController::class . '@detail'
    ]);

    Route::post('/applications', [
        'as' => 'applications.create',
        'uses' => ApplicationController::class . '@create'
    ]);

    Route::put('/applications/{uid}', [
        'as' => 'applications.update',
        'uses' => ApplicationController::class . '@update'
    ]);

    Route::delete('/applications/{uid}', [
        'as' => 'applications.delete',
        'uses' => ApplicationController::class . '@delete'
    ]);

    // =============== REMOTE ACCESS FACTOR ================//
    // =========== remote-access-factors =========/
    Route::get('/remote-access-factors', [
        'as' => 'remote-access-factors.list',
        'uses' => RemoteAccessFactorController::class . '@index'
    ]);

    Route::get('/remote-access-factors/{uid}', [
        'as' => 'remote-access-factors.detail',
        'uses' => RemoteAccessFactorController::class . '@detail'
    ]);

    Route::post('/remote-access-factors', [
        'as' => 'remote-access-factors.create',
        'uses' => RemoteAccessFactorController::class . '@create'
    ]);

    Route::put('/remote-access-factors/{uid}', [
        'as' => 'remote-access-factors.update',
        'uses' => RemoteAccessFactorController::class . '@update'
    ]);

    Route::delete('/remote-access-factors/{uid}', [
        'as' => 'remote-access-factors.delete',
        'uses' => RemoteAccessFactorController::class . '@delete'
    ]);

    // =============== TOLERABLE PERIOD DISRUPTION ================//
    // =========== tolerable-time-periods =========/
    Route::get('/tolerable-time-periods/all', [
        'as' => 'tolerable-time-periods.list',
        'uses' => TolerableTimePeriodController::class . '@all'
    ]);

    Route::get('/tolerable-time-periods/{uid}', [
        'as' => 'tolerable-time-periods.detail',
        'uses' => TolerableTimePeriodController::class . '@detail'
    ]);

    Route::post('/tolerable-time-periods', [
        'as' => 'tolerable-time-periods.create',
        'uses' => TolerableTimePeriodController::class . '@create'
    ]);

    Route::put('/tolerable-time-periods/{uid}', [
        'as' => 'tolerable-time-periods.update',
        'uses' => TolerableTimePeriodController::class . '@update'
    ]);

    Route::delete('/tolerable-time-periods/{uid}', [
        'as' => 'tolerable-time-periods.delete',
        'uses' => TolerableTimePeriodController::class . '@delete'
    ]);

    // =============== RECOVERY TIME ================//
    // =========== recovery-times =========/
    Route::get('/recovery-times', [
        'as' => 'recovery-times.list',
        'uses' => RecoveryTimeController::class . '@index'
    ]);

    Route::get('/recovery-times/{uid}', [
        'as' => 'recovery-times.detail',
        'uses' => RecoveryTimeController::class . '@detail'
    ]);

    Route::post('/recovery-times', [
        'as' => 'recovery-times.create',
        'uses' => RecoveryTimeController::class . '@create'
    ]);

    Route::put('/recovery-times/{uid}', [
        'as' => 'recovery-times.update',
        'uses' => RecoveryTimeController::class . '@update'
    ]);

    Route::delete('/recovery-times/{uid}', [
        'as' => 'recovery-times.delete',
        'uses' => RecoveryTimeController::class . '@delete'
    ]);

    // =============== DISRUPTION SCENARIO ================//
    // =========== disruption-scenarios =========/
    Route::get('/disruption-scenarios', [
        'as' => 'disruption-scenarios.list',
        'uses' => DisruptionScenarioController::class . '@index'
    ]);

    Route::get('/disruption-scenarios/{uid}', [
        'as' => 'disruption-scenarios.detail',
        'uses' => DisruptionScenarioController::class . '@detail'
    ]);

    Route::post('/disruption-scenarios', [
        'as' => 'disruption-scenarios.create',
        'uses' => DisruptionScenarioController::class . '@create'
    ]);

    Route::put('/disruption-scenarios/{uid}', [
        'as' => 'disruption-scenarios.update',
        'uses' => DisruptionScenarioController::class . '@update'
    ]);

    Route::delete('/disruption-scenarios/{uid}', [
        'as' => 'disruption-scenarios.delete',
        'uses' => DisruptionScenarioController::class . '@delete'
    ]);
});
