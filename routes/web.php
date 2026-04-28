<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminComplaintController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminRegisterController;
use App\Http\Controllers\AdminAnnouncementController;
use App\Http\Controllers\MessageController;

Route::get('/', function () {
    return view('admin.login');
});


Route::get('/admin/login',[AdminAuthController::class,'showLogin']);

Route::post('/admin/login',[AdminAuthController::class,'login']);

Route::get('/admin/dashboard',function(){

    // if(!session('admin_id')){
    //     return redirect('/admin/login');
    // }

    return view('admin.dashboard');

});

Route::get('/admin/logout',[AdminAuthController::class,'logout']);

Route::get('/admin/dashboard',[AdminDashboardController::class,'index']);

Route::get('/admin/register',[AdminRegisterController::class,'showRegister']);

Route::post('/admin/register',[AdminRegisterController::class,'register']);

Route::get('/admin/complaints',[AdminComplaintController::class,'index']);

Route::post('/admin/message',[MessageController::class,'send']);

Route::post('/admin/update-status',[AdminComplaintController::class,'updateStatus']);


Route::get('/admin/announcements',[AdminAnnouncementController::class,'index']);

Route::post('/admin/announcement',[AdminAnnouncementController::class,'store']);


Route::get('/admin/messages/{id}', [MessageController::class, 'getByComplaint']);
Route::post('/admin/message', [MessageController::class, 'send']);

Route::post('/admin/announcement/delete', [AdminAnnouncementController::class, 'delete']);

Route::post('/admin/delete-complaint', [AdminComplaintController::class, 'delete']);