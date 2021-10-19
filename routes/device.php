<?php

use App\Http\Controllers\API\Device\AuthController;
use App\Http\Controllers\API\Device\ContactGroupController;
use App\Http\Controllers\API\Device\GroupController;
use App\Http\Controllers\API\Device\ContactController;
use App\Http\Controllers\API\Device\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum','validate.user']], function() {
    
                Route::post('contact-groups',[ContactGroupController::class, 'store'])
        ->name('contactGroup.store')
        ->middleware(['permission:create_contactgroup']);
                                Route::get('contact-groups',[ContactGroupController::class, 'index'])
        ->name('contact-groups.index')
        ->middleware(['permission:read_contactgroup']);
        Route::get('contact-groups/{contactGroup}',[ContactGroupController::class, 'show'])
        ->name('contactGroup.show')
        ->middleware(['permission:read_contactgroup']);
                                            Route::put('contact-groups/{contactGroup}',[ContactGroupController::class, 'update'])
        ->name('contactGroup.update')
        ->middleware(['permission:update_contactgroup']);
                                        Route::delete('contact-groups/{contactGroup}',[ContactGroupController::class, 'delete'])
        ->name('contactGroup.delete')
        ->middleware(['permission:delete_contactgroup']);
                                        Route::post('contact-groups/bulk-create',[ContactGroupController::class, 'bulkStore'])
        ->name('contactGroup.bulk.add')
        ->middleware(['permission:create_contactgroup']);
                                        Route::post('contact-groups/bulk-update',[ContactGroupController::class, 'bulkUpdate'])
        ->name('contactGroup.bulk.update')
        ->middleware(['permission:update_contactgroup']);
                                
                Route::post('groups',[GroupController::class, 'store'])
        ->name('group.store')
        ->middleware(['permission:create_group']);
                                Route::get('groups',[GroupController::class, 'index'])
        ->name('groups.index')
        ->middleware(['permission:read_group']);
        Route::get('groups/{group}',[GroupController::class, 'show'])
        ->name('group.show')
        ->middleware(['permission:read_group']);
                                            Route::put('groups/{group}',[GroupController::class, 'update'])
        ->name('group.update')
        ->middleware(['permission:update_group']);
                                        Route::delete('groups/{group}',[GroupController::class, 'delete'])
        ->name('group.delete')
        ->middleware(['permission:delete_group']);
                                        Route::post('groups/bulk-create',[GroupController::class, 'bulkStore'])
        ->name('group.bulk.add')
        ->middleware(['permission:create_group']);
                                        Route::post('groups/bulk-update',[GroupController::class, 'bulkUpdate'])
        ->name('group.bulk.update')
        ->middleware(['permission:update_group']);
                                
                Route::post('contacts',[ContactController::class, 'store'])
        ->name('contact.store')
        ->middleware(['permission:create_contact']);
                                Route::get('contacts',[ContactController::class, 'index'])
        ->name('contacts.index')
        ->middleware(['permission:read_contact']);
        Route::get('contacts/{contact}',[ContactController::class, 'show'])
        ->name('contact.show')
        ->middleware(['permission:read_contact']);
                                            Route::put('contacts/{contact}',[ContactController::class, 'update'])
        ->name('contact.update')
        ->middleware(['permission:update_contact']);
                                        Route::delete('contacts/{contact}',[ContactController::class, 'delete'])
        ->name('contact.delete')
        ->middleware(['permission:delete_contact']);
                                        Route::post('contacts/bulk-create',[ContactController::class, 'bulkStore'])
        ->name('contact.bulk.add')
        ->middleware(['permission:create_contact']);
                                        Route::post('contacts/bulk-update',[ContactController::class, 'bulkUpdate'])
        ->name('contact.bulk.update')
        ->middleware(['permission:update_contact']);
                                
                Route::post('users',[UserController::class, 'store'])
        ->name('user.store')
        ->middleware(['permission:create_user']);
                                Route::get('users',[UserController::class, 'index'])
        ->name('users.index')
        ->middleware(['permission:read_user']);
        Route::get('users/{user}',[UserController::class, 'show'])
        ->name('user.show')
        ->middleware(['permission:read_user']);
                                            Route::put('users/{user}',[UserController::class, 'update'])
        ->name('user.update')
        ->middleware(['permission:update_user']);
                                        Route::delete('users/{user}',[UserController::class, 'delete'])
        ->name('user.delete')
        ->middleware(['permission:delete_user']);
                                        Route::post('users/bulk-create',[UserController::class, 'bulkStore'])
        ->name('user.bulk.add')
        ->middleware(['permission:create_user']);
                                        Route::post('users/bulk-update',[UserController::class, 'bulkUpdate'])
        ->name('user.bulk.update')
        ->middleware(['permission:update_user']);
                            
});

    
    
    
    

Route::group(['middleware' => ['auth:sanctum','validate.user']], function () {
Route::put('change-password',[AuthController::class, 'changePassword'])
->name('change.password');
});

Route::post('register',[AuthController::class, 'register'])
->name('register');
Route::post('login',[AuthController::class, 'login'])
->name('login');
Route::post('forgot-password', [AuthController::class, 'forgotPassword'])
->name('forgot.password');
Route::post('validate-otp',[AuthController::class, 'validateResetPasswordOtp'])
->name('reset.password.validate.otp');
Route::put('reset-password',[AuthController::class, 'resetPassword'])
->name('reset.password');

