<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DahboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OnlineController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AdminsController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FixerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
    return redirect('/login');
});
Route::prefix('cp')->group(function()
{
    Route::get('/dashboard',[DahboardController::class,'index'])->name('dashboard');
    Route::get('/users',[UserController::class,'index'])->name('users');
    Route::post('/users',[UserController::class,'newuser'])->name('new.user');
    Route::post('/users/bulk',[UserController::class,'bulkuser'])->name('new.bulkuser');
    Route::get('/user/active/{username}',[UserController::class,'activeuser'])->name('user.active');
    Route::get('/user/deactive/{username}',[UserController::class,'deactiveuser'])->name('user.deactive');
    Route::get('/user/reset/{username}',[UserController::class,'reset_traffic'])->name('user.reset');
    Route::get('/user/delete/{username}',[UserController::class,'delete'])->name('user.delete');
    Route::post('/user/delete/bulk',[UserController::class,'delete_bulk'])->name('user.delete.bulk');
    Route::post('/user/renewal',[UserController::class,'renewal'])->name('new.renewal');
    Route::get('/user/edit/{username}',[UserController::class,'edit'])->name('user.edit');
    Route::post('/user/edit',[UserController::class,'update'])->name('user.update');
    Route::get('/online',[OnlineController::class,'index'])->name('online');
    Route::get('/online/id/{pid}',[OnlineController::class,'kill_pid'])->name('online.kill.pid');
    Route::get('/online/user/{username}',[OnlineController::class,'kill_user'])->name('online.kill.username');
    Route::get('/checkip',[OnlineController::class,'filtering'])->name('filtering');
    Route::get('/settings',[SettingsController::class,'defualt'])->name('setting');
    Route::get('/settings/{name}',[SettingsController::class,'index'])->name('settings');
    Route::post('/settings/user',[SettingsController::class,'update_multiuser'])->name('settings.multiuser');
    Route::post('/settings/telegram',[SettingsController::class,'update_telegram'])->name('settings.telegram');
    Route::post('/settings/backup',[SettingsController::class,'import_old'])->name('settings.backup.old');
    Route::post('/settings/backup/new',[SettingsController::class,'upload_backup'])->name('settings.backup.upload');
    Route::get('/settings/backup/delete/{name}',[SettingsController::class,'delete_backup'])->name('settings.backup.delete');
    Route::get('/settings/backup/restore/{name}',[SettingsController::class,'restore_backup'])->name('settings.backup.restore');
    Route::post('/settings/backup/make/',[SettingsController::class,'make_backup'])->name('settings.backup.make');
    Route::get('/settings/backup/dl/{name}',[SettingsController::class,'download_backup'])->name('settings.backup.dl');
    Route::post('/settings/api',[SettingsController::class,'insert_api'])->name('settings.api');
    Route::get('/settings/api/renew/{id}',[SettingsController::class,'renew_api'])->name('settings.token.renew');
    Route::get('/settings/api/delete/{id}',[SettingsController::class,'delete_api'])->name('settings.token.delete');
    Route::post('/settings/block',[SettingsController::class,'block'])->name('settings.block');
    Route::post('/settings/fake',[SettingsController::class,'fakeurl'])->name('settings.fakeurl');
    Route::get('/managers',[AdminsController::class,'index'])->name('admins');
    Route::post('/managers',[AdminsController::class,'insert'])->name('admin.new');
    Route::get('/managers/active/{username}',[AdminsController::class,'activeadmin'])->name('admin.active');
    Route::get('/managers/deactive/{username}',[AdminsController::class,'deactiveadmin'])->name('admin.deactive');
    Route::get('/managers/delete/{username}',[AdminsController::class,'deleteadmin'])->name('admin.delete');
    Route::get('/managers/edit/{username}',[AdminsController::class,'edit'])->name('admin.edit');
    Route::post('/manager/update',[AdminsController::class,'update'])->name('admin.update');
    Route::get('/documents',[DocumentController::class,'index'])->name('document');
    Route::get('/logout',[LoginController::class,'logout'])->name('user.logout');


});
Route::prefix('api')->group(function()
{
    Route::get('/{token}/listuser',[ApiController::class,'listuser'])->name('api.listuser');
    Route::get('/{token}/listuser/{sort}',[ApiController::class,'sort_listuser'])->name('api.listuser.sort');
    Route::post('/adduser',[ApiController::class,'add_user'])->name('api.add.user');
    Route::get('/{token}/user/{username}',[ApiController::class,'show_detail'])->name('api.show.detail');
    Route::post('/edituser',[ApiController::class,'edit'])->name('api.user.edit');
    Route::post('/delete',[ApiController::class,'delete_user'])->name('api.user.delete');
    Route::post('/active',[ApiController::class,'active_user'])->name('api.user.active');
    Route::post('/deactive',[ApiController::class,'deactive_user'])->name('api.user.deactive');
    Route::post('/retraffic',[ApiController::class,'retraffic_user'])->name('api.user.retraffic');
    Route::post('/renewal',[ApiController::class,'renewal_user'])->name('api.user.renewal');
});
Route::prefix('fixer')->group(function() {
    Route::get('/exp', [FixerController::class, 'cronexp'])->name('exp');
    Route::get('/multiuser', [FixerController::class, 'multiuser'])->name('multiuser');
});
Auth::routes();
