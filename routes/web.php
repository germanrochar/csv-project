<?php

use App\Http\Controllers\ContactsCsvScannerController;
use App\Http\Controllers\ImportContactsController;
use App\Http\Controllers\ImportJobsController;
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

Route::get('/', function () {
    return view('imports.contacts');
});

// Scanners
Route::post('/scan/csv', [ContactsCsvScannerController::class, 'index']);

// Imports
Route::post('/imports/contacts/csv', [ImportContactsController::class, 'store']);

// Import Jobs
Route::get('/import/jobs', [ImportJobsController::class, 'index']);
