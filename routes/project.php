<?php

use App\Http\Controllers\Admin\ProjectManagement\ProjectController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth']], function () {
    Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::post('projects/store', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('projects/show', [ProjectController::class, 'show'])->name('projects.show');
    Route::post('projects/update', [ProjectController::class, 'update'])->name('projects.update');
    Route::post('projects/update-status', [ProjectController::class, 'updateStatus'])->name('projects.update-status');
    Route::post('projects/delete', [ProjectController::class, 'destroy'])->name('projects.destroy');
});
