<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\JobController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Admin\BusinessController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\DatatableController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\Masterformat\DivisionController;

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

Route::get('/', [WelcomeController::class, 'welcome'])->name('home');
Route::post('/contact', ContactController::class)->name('contact');
Route::get('/datatable', [DatatableController::class, 'index'])->name('datatable.index');
Route::get('/datatable/export', [DatatableController::class, 'export'])->name('export');
Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio');
Route::get('/proposals', [ProposalController::class, 'index'])->name('proposals.index');

// proposal
Route::post('proposals/{job}/{proposal?}', [ProposalController::class, 'create'])->name('proposals');
Route::get('proposals/edit/{proposal}', [ProposalController::class, 'edit'])->name('proposals.edit');
Route::put('proposals/update/{proposal}', [ProposalController::class, 'update'])->name('proposals.update');
Route::delete('proposals/delete/{proposal}', [ProposalController::class, 'destroy'])->name('proposals.destroy');
// Route::post('proposals/update/{proposal}/{pdf}', [ProposalController::class, 'update'])->name('proposals.update');

// scopes
Route::post('proposals/scopes/create/{proposal}', [ProposalController::class, 'createScope'])->name('scopes.create');
Route::put('proposals/scopes/update/{scope}', [ProposalController::class, 'updateScope'])->name('scopes.update');
Route::delete('proposals/scopes/delete/{scope}', [ProposalController::class, 'destroyScope'])->name('scopes.destroy');

// lines
Route::post('proposals/scopes/lines/create/{scope}', [ProposalController::class, 'createLine'])->name('lines.create');
Route::put('proposals/scopes/lines/update/{line}', [ProposalController::class, 'updateLine'])->name('lines.update');
Route::delete('proposals/scopes/lines/delete/{line}', [ProposalController::class, 'destroyLine'])->name('lines.destroy');

Route::get('proposals/download-pdf/{proposal}', [ProposalController::class, 'downloadPDF'])->name('downloadPDF.pdf');
Route::get('proposals/browser-pdf/{proposal}', [ProposalController::class, 'browserPDF'])->name('browserPDF.pdf');
Route::get('/estimating', [JobController::class, 'index'])->name('estimating.index');
Route::get('estimating/create', [JobController::class, 'create'])->name('estimating.create');
Route::post('estimating/store', [JobController::class, 'store'])->name('estimating.store');
Route::get('estimating/edit/{job}', [JobController::class, 'edit'])->name('estimating.edit');
Route::patch('estimating/update/{job}', [JobController::class, 'update'])->name('estimating.update');
Route::get('estimating/report', [JobController::class, 'report'])->name('estimating.report');
Route::resource('/features', FeatureController::class);
Route::resource('/company', CompanyController::class);
Route::resource('/masterformat', DivisionController::class);

// 2 METHODS OF CREATING SYMLINKS
// Route::get('/link', function () {
//     Artisan::call('storage:link');
// });

// Route::get('/link', function () {
//    $target = '/home/dh_2gujjy/raleighgroesbeck-app/storage/app/public';
//    $shortcut = '/home/dh_2gujjy/raleighgroesbeck.com/storage';
//    symlink($target, $shortcut);
// });

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::resource('/skills', SkillController::class);
    Route::resource('/projects', ProjectController::class);
    Route::resource('/businesses', BusinessController::class);
    Route::post('/projects/sort', [ProjectController::class, 'sort'])->name('projects.sort');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


    Route::patch('/users/{user}', [CompanyController::class, 'update_user'])->name('users.update');

require __DIR__ . '/auth.php';
