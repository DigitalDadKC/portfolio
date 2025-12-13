<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\DatatableController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\ClauseController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ContractController;
use App\Http\Controllers\Admin\OutreachController;
use App\Http\Controllers\Estimating\JobController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Estimating\AdminController;
use App\Http\Controllers\Invoicing\InvoiceController;
use App\Http\Controllers\Invoicing\ProductController;
use App\Http\Controllers\Estimating\CustomerController;
use App\Http\Controllers\Estimating\ProposalController;
use App\Http\Controllers\Masterformat\DivisionController;
use App\Http\Controllers\Admin\InvoiceController as AdminInvoiceController;
use App\Http\Controllers\Admin\ServiceController;

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
    Route::resource('/invoices', AdminInvoiceController::class, ['as' => 'admin']);
    Route::get('/invoices/send/{client_invoice}', [AdminInvoiceController::class, 'sendInvoice'])->name('admin.invoices.send');
    Route::resource('/clients', ClientController::class);
    Route::post('/features/sort', [FeatureController::class, 'sort'])->name('features.sort');
    Route::post('/projects/sort', [ProjectController::class, 'sort'])->name('projects.sort');
    Route::resource('/contracts', ContractController::class)->except(['show']);
    Route::get('/contracts/browser', [ContractController::class, 'browser'])->name('contracts.browser');
    Route::post('/contracts/send', [ClientController::class, 'send'])->name('contracts.send');
    Route::resource('/services', ServiceController::class);
});

// CHECKOUT
Route::post('/checkout', [AdminInvoiceController::class, 'checkout'])->name('checkout');
Route::get('/success', [AdminInvoiceController::class, 'success'])->name('checkout.success');
Route::post('/cancel', [AdminInvoiceController::class, 'cancel'])->name('checkout.cancel');
Route::post('/webhook', [AdminInvoiceController::class, 'webhook'])->name('checkout.webhook');

// jobs
Route::resource('estimating/jobs', JobController::class);

// proposal
Route::post('proposals/{job}', [ProposalController::class, 'store'])->name('proposals.store');
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
Route::post('companies/update/{company?}', [AdminController::class, 'updateCompany'])->name('companies.update');

// MASTERFORMAT
Route::resource('/masterformat', DivisionController::class);

// INVOICING
Route::resource('/invoices', InvoiceController::class);
Route::get('invoices/download-pdf/{invoice}', [InvoiceController::class, 'downloadPDF'])->name('invoices.downloadPDF');
Route::get('invoices/browser-pdf/{invoice}', [InvoiceController::class, 'browserPDF'])->name('invoices.browserPDF');
Route::resource('/products', ProductController::class);

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

Route::get('autocomplete', [AddressController::class, 'index']);

Route::patch('/users/{user}', [AdminController::class, 'update_user'])->name('users.update');

require __DIR__ . '/auth.php';

