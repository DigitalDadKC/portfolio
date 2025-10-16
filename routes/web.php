<?php

use Inertia\Inertia;
use App\Mail\SenderMail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Admin\OutreachController;
use App\Http\Controllers\DatatableController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Estimating\JobController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Estimating\AdminController;
use App\Http\Controllers\Invoicing\InvoiceController;
use App\Http\Controllers\Estimating\CustomerController;
use App\Http\Controllers\Estimating\ProposalController;
use App\Http\Controllers\Masterformat\DivisionController;
use App\Http\Controllers\Admin\InvoiceController as AdminInvoiceController;
use App\Mail\OutreachMail;

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
Route::post('/outreach', OutreachController::class)->name('outreach');
Route::get('/datatable', [DatatableController::class, 'index'])->name('datatable.index');
Route::get('/datatable/export', [DatatableController::class, 'export'])->name('export');
Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio');
Route::get('/proposals', [ProposalController::class, 'index'])->name('proposals.index');

// ADMIN ROUTES
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('/skills', SkillController::class);
    Route::resource('/projects', ProjectController::class);
    Route::resource('/features', FeatureController::class);
    // Route::resource('/invoices', AdminInvoiceController::class, ['as' => 'admin']);
    Route::resource('/clients', ClientController::class);
    Route::post('/features/sort', [FeatureController::class, 'sort'])->name('features.sort');
    Route::post('/projects/sort', [ProjectController::class, 'sort'])->name('projects.sort');
});

// jobs
Route::resource('/estimating', JobController::class);
// Route::get('/estimating', [JobController::class, 'index'])->name('estimating.index');
// Route::get('estimating/create', [JobController::class, 'create'])->name('estimating.create');
// Route::post('estimating/store', [JobController::class, 'store'])->name('estimating.store');
// Route::get('estimating/edit/{job}', [JobController::class, 'edit'])->name('estimating.edit');
// Route::patch('estimating/update/{job}', [JobController::class, 'update'])->name('estimating.update');

// proposal
Route::post('proposals/{job}/{proposal?}', [ProposalController::class, 'create'])->name('proposals');
Route::get('proposals/edit/{proposal}', [ProposalController::class, 'edit'])->name('proposals.edit');
Route::put('proposals/update/{proposal}', [ProposalController::class, 'updateProposal'])->name('proposals.update');
Route::delete('proposals/delete/{proposal}', [ProposalController::class, 'destroy'])->name('proposals.destroy');

// scopes
Route::post('proposals/scopes/create/{proposal}', [ProposalController::class, 'createScope'])->name('scopes.create');
Route::put('proposals/scopes/update/{scope}', [ProposalController::class, 'updateScope'])->name('scopes.update');
Route::delete('proposals/scopes/delete/{scope}', [ProposalController::class, 'destroyScope'])->name('scopes.destroy');

// lines
Route::post('proposals/scopes/lines/create/{scope}', [ProposalController::class, 'createLine'])->name('lines.create');
Route::put('proposals/scopes/lines/update/{line}', [ProposalController::class, 'updateLine'])->name('lines.update');
Route::patch('proposals/scopes/lines/sort/{scope}', [ProposalController::class, 'sortLines'])->name('lines.sort');
Route::delete('proposals/scopes/lines/delete/{line}', [ProposalController::class, 'destroyLine'])->name('lines.destroy');

Route::get('proposals/download-pdf/{proposal}', [ProposalController::class, 'downloadPDF'])->name('proposals.downloadPDF');
Route::get('proposals/browser-pdf/{proposal}', [ProposalController::class, 'browserPDF'])->name('proposals.browserPDF');
Route::get('estimating/report', [JobController::class, 'report'])->name('estimating.report');
Route::resource('/admin', AdminController::class);
Route::resource('/customers', CustomerController::class);

// company
Route::post('companies/store', [AdminController::class, 'storeCompany'])->name('companies.store');
Route::post('companies/update/{company}', [AdminController::class, 'updateCompany'])->name('companies.update');

// MASTERFORMAT
Route::resource('/masterformat', DivisionController::class);

// INVOICING
Route::resource('/invoices', InvoiceController::class);
Route::get('invoices/download-pdf/{invoice}', [InvoiceController::class, 'downloadPDF'])->name('invoices.downloadPDF');
Route::get('invoices/browser-pdf/{invoice}', [InvoiceController::class, 'browserPDF'])->name('invoices.browserPDF');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


    Route::patch('/users/{user}', [AdminController::class, 'update_user'])->name('users.update');

require __DIR__ . '/auth.php';

// Route::get('/link', function () {
//     $targetFolder = $_SERVER['DOCUMENT_ROOT'].'/storage/app/public';
//     $linkFolder = $_SERVER['DOCUMENT_ROOT'].'/public/storage';
//     symlink($targetFolder, $linkFolder);
//     echo 'success';
// });

// 2 METHODS OF CREATING SYMLINKS
// Route::get('/link', function () {
//     Artisan::call('storage:link');
//     return 'ok!';
// });
