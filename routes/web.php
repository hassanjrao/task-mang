<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupInvitationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskAttachmentsController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskExportController;
use App\Http\Controllers\TaskImportController;
use App\Http\Controllers\UserController;
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

// get log files appear
Route::get('/logs', function () {
    $logFiles = glob(storage_path('logs/*.log'));
    $logFiles = array_map(function ($file) {
        return basename($file);
    }, $logFiles);
    var_dump($logFiles);
})->name('logs.index');


Route::middleware(["auth",'check.group.invites'])->group(function () {

    Route::match(['get', 'post'], '/dashboard', function () {
        return view('dashboard');
    })->name('dashboard.index');

    Route::match(['get'], '/', function () {
        return view('dashboard');
    })->name('dashboard.index');

    Route::resource('profile', ProfileController::class)->only(['index', 'update']);

    Route::get('get-tasks',[TaskController::class, 'getTasks'])->name('tasks.get-tasks');
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

    Route::get('/task-statuses', [TaskController::class, 'statusesWithTaskCount'])->name('tasks.statuses-with-count');

    Route::get('/users/search', [UserController::class, 'search'])->name('users.search');

    Route::get('/group-invitation/{id}/{action}', [GroupInvitationController::class, 'respond'])->name('group.invitation.respond');


    Route::get('/tasks/{task}/export', [TaskExportController::class, 'exportSingle'])->name('tasks.export.single');

    Route::post('/tasks/import', [TaskImportController::class, 'import'])->name('tasks.import');

});
