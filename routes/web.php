<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskAttachmentsController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();



Route::middleware(["auth"])->group(function () {

    Route::match(['get', 'post'], '/dashboard', function () {
        return view('dashboard');
    })->name('dashboard.index');

      Route::match(['get'], '/', function () {
        return view('dashboard');
    })->name('dashboard.index');

    Route::resource('profile', ProfileController::class)->only(['index', 'update']);

    Route::resource('tasks', TaskController::class);
    Route::put('tasks/{task}/update-priority', [TaskController::class, 'updatePriority'])->name('tasks.update-priority');
    Route::put('tasks/{task}/update-status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');

    Route::post('/attachments/upload', [TaskAttachmentsController::class, 'upload'])->name('attachments.upload');
    Route::post('/attachments/revert', [TaskAttachmentsController::class, 'revert'])->name('attachments.revert');
    Route::post('/attachments/remove', [TaskAttachmentsController::class, 'remove'])->name('attachments.remove');

    Route::resource('groups', GroupController::class);
    Route::post('groups/{group}/join', [GroupController::class, 'join'])->name('groups.join');
    Route::post('groups/{group}/leave', [GroupController::class, 'leave'])->name('groups.leave');
    // your groups
    Route::get('your-groups', [GroupController::class, 'yourGroups'])->name('groups.your-groups');
});
