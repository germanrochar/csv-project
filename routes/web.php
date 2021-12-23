<?php

use App\Http\Controllers\ContactsCsvScannerController;
use App\Http\Controllers\CsvUploadsController;
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


Route::post('/scan/csv', [ContactsCsvScannerController::class, 'index']);
