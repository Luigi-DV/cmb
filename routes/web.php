<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DepartmentStatisticsController;
use App\Http\Controllers\DepartmentProjectController;
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
/*
|--------------------------------------------------------------------------
| Guest User
|
*/
Route::get('/', function (){
    return view('welcome');
})->name('home');

Route::get('/help-center', function (){
    return view('app.help.index');
})->name('help.center');


/**
 * Statistics
 *  Index
 *  Middleware (Statistic Department)
 *      ****
 *      -Resources
 *      -Projects
 *      -Audition
 *      ****
 */

Route::middleware('statisticDepartment')->group(function () {
    //Index
});



/**
 * Department Section
 */
Route::get('{department}/login/', [DepartmentController::class, 'login'])->name('department.auth.login');

//Index
Route::middleware('checkExternalPlanning')->group(function () {
    Route::name('department.')->prefix('{department}')->group(function ($department) {
        Route::get('/dashboard', [DepartmentController::class, 'index'])->name('index');
        /**
         * Department Projects
         */
        Route::get('/projects', [DepartmentProjectController::class, 'index'])
            ->name('projects.index');

        Route::get('/projects/{id}', [DepartmentProjectController::class, 'show'])
            ->name('projects.show');
        /**
         * Department Statistics
         */
        //Index
        Route::get('/statistics', [DepartmentStatisticsController::class, 'index'])
            ->name('statistics.index');
        //Resources
        Route::get('/statistics/resources', [DepartmentStatisticsController::class, 'resources'])
            ->name('statistics.resources');
        //Projects
        Route::get('/statistics/projects', [DepartmentStatisticsController::class, 'projects'])
            ->name('statistics.projects');
        //Audition
        Route::get('/statistics/audition', [DepartmentStatisticsController::class, 'audition'])
            ->name('statistics.audition');
    });
});

/**
 * DOCUMENTATION
 */
Route::get('/docs', function () {
    return view('documentation.index');
})->name('docs');
