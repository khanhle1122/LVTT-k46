<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\DuAnController;
use App\Http\Controllers\Backend\ClientController;
use App\Http\Controllers\Backend\NhanVienController;
use App\Http\Controllers\Backend\NotificationController;
use App\Http\Controllers\Backend\TaskController;
use App\Http\Controllers\Backend\TaiLieuController;
use App\Http\Controllers\Backend\SearchController;



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

Route::middleware(['auth','role:admin'])->group(function(){
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin_dashboard');
});

Route::get('/staff/dashboard', function () {
    return view('staff.staff_dashboard');
})->name('staff_dashboard');

Route::get('/leader/dashboard', function () {
    return view('leader.leader_dashboard');
})->name('leader_dashboard');

Route::get('/supervisor/dashboard', function () {
    return view('supervisor.supervisor_dashboade');
})->name('supervisor_dashboard');




Route::get('/danhmuc', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/taichinh', function () {
    return view('taichinh');
})->middleware(['auth', 'verified'])->name('taichinh');
Route::get('/baocao', function () {
    return view('baocao');
})->middleware(['auth', 'verified'])->name('baocao');
Route::get('/tailieu', function () {
    return view('tailieu');
})->middleware(['auth', 'verified'])->name('tailieu');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/project',[DuAnController::class, 'viewDA'])->name('project');
    Route::get('/project',[DuAnController::class, 'viewDA'])->name('project');

    Route::post('/project/add-project',[DuAnController::class, 'StoreProject'])->name('project.store');
    Route::get('/project/{id}', [DuAnController::class, 'toggleStar'])->name('project.toggleStar');
    Route::delete('/project',[DuAnController::class, 'deleteProject'])->name('delete-project');
    Route::post('/project/edit', [DuAnController::class, 'editProject'])->name('edit.project');

    Route::get('/task/{id}',[TaskController::class, 'viewtask'])->name('view.task');
    Route::get('/project/search', [DuAnController::class, 'search'])->name('project.search');
    Route::get('/document',[TaiLieuController::class, 'viewDocument'])->name('view.document');
    Route::post('/task/add',[TaskController::class, 'addDoSon'])->name('add.do');
    Route::post('/task/file',[TaskController::class, 'addFile'])->name('add.file');
    Route::post('/task/add-task',[TaskController::class, 'addTask'])->name('task.store');
    Route::get('/task/gantt/{id}',[TaskController::class, 'showGanttChart'])->name('gantt-chart');


});
Route::middleware('auth')->group(function () {
    Route::get('/search', [SearchController::class, 'search'])->name('search');

});


Route::middleware('auth')->group(function () {
    Route::get('/employees',[NhanVienController::class, 'index'])->name('nhansu');
    Route::delete('/employees',[NhanVienController::class, 'deleteEmploye'])->name('delete-user');
    Route::post('/send-notification', [NotificationController::class, 'sendNotification'])->name('send.notification');
    Route::get('/notifications/{userId}', [NotificationController::class, 'showNotifications'])->name('show.notifications');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('mark.notification.read');
    Route::post('/employees/store', [NhanVienController::class, 'store'])->name('store.employee');
    Route::get('/employees/search', [NhanVienController::class, 'search'])->name('users.search');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('mark.notification.read');
    Route::post('/employees/edit', [NhanVienController::class, 'editEmployee'])->name('edit.employe');



});

require __DIR__.'/auth.php';
