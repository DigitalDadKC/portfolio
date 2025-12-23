<?php

use App\Http\Controllers\Admin\SignWellController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/signwell/webhook', [SignWellController::class, 'handle']);

// Route::get('/get_all_invoices', [InvoiceController::class, 'get_all_invoices'])->name('invoices.index');
// Route::get('/search_invoice', [InvoiceController::class, 'search_invoice'])->name('invoices.search');
// Route::get('/create_invoice', [InvoiceController::class, 'create_invoice'])->name('invoices.create');
// Route::get('/clients', [ClientController::class, 'all_clients'])->name('clients.index');
// Route::get('/products', [ProductController::class, 'all_products'])->name('products.index');
// Route::post('/add_invoice', [InvoiceController::class, 'add_invoice'])->name('invoices.store');
