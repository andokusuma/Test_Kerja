<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::controller(InvoiceController::class)->group(function(){

    Route::get("/", 'index')->name("invoice.all");
    Route::post("/store-invoice", 'storeInvoice')->name("invoice.store");
    Route::get("/detail-invoice/{id}", 'detailInvoice')->name("invoice.show.detail");
    Route::get("/delete/invoice/{id}", 'deleteInvoice')->name("invoice.delete");
    Route::post("/update-detail-invoice/{id}", 'updateDetailInvoice')->name("invoice.detail.update");
});
