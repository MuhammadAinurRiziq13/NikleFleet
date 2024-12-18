<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MineController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SubmissionReservationController;
use App\Http\Controllers\VehicleController;
use App\Models\VehicleReservations;
use Illuminate\Support\Facades\Route;

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

//ajax
Route::get('/employee_id', [ReservationController::class, 'getEmployeesData']);
Route::get('/get-vehicles', [ReservationController::class, 'getVehicles']);
Route::get('/get-available-approvers', [ReservationController::class, 'getAvailableApprovers']);
Route::get('/get-regions', [ReservationController::class, 'getRegions']);
Route::get('/get-mines', [ReservationController::class, 'getMines']);
Route::get('/get-approver-cabang', [ReservationController::class, 'getApproverCabang']);
Route::get('/get-approver-pusat', [ReservationController::class, 'getApproverPusat']);


Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::Post('/login', [AuthController::class, 'authenticate']);
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::prefix('dashboard')->middleware('role:admin|approver')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
});

Route::prefix('vehicle')->middleware('role:admin|approver')->group(function () {
    Route::get('/', [VehicleController::class, 'index']);
    Route::post('/list', [VehicleController::class, 'list']);
    Route::middleware('role:admin')->group(function () {
        Route::get('/create', [VehicleController::class, 'create']);
        Route::post('/', [VehicleController::class, 'store']);
        Route::get('/{id}', [VehicleController::class, 'show']);
        Route::delete('/{id}', [VehicleController::class, 'destroy']);
        Route::get('/{id}/edit', [VehicleController::class, 'edit']);
    });
    Route::put('/{id}', [VehicleController::class, 'update']);
});

Route::prefix('mine')->middleware('role:admin|approver')->group(function () {
    Route::get('/', [MineController::class, 'index']);
    Route::post('/list', [MineController::class, 'list']);
    Route::middleware('role:admin')->group(function () {
        Route::get('/create', [MineController::class, 'create']);
        Route::post('/', [MineController::class, 'store']);
        Route::get('/{id}', [MineController::class, 'show']);
        Route::delete('/{id}', [MineController::class, 'destroy']);
        Route::get('/{id}/edit', [MineController::class, 'edit']);
    });
    Route::put('/{id}', [MineController::class, 'update']);
});

Route::prefix('region')->middleware('role:admin|approver')->group(function () {
    Route::get('/', [RegionController::class, 'index']);
    Route::post('/list', [RegionController::class, 'list']);
    Route::middleware('role:admin')->group(function () {
        Route::get('/create', [RegionController::class, 'create']);
        Route::post('/', [RegionController::class, 'store']);
        Route::get('/{id}', [RegionController::class, 'show']);
        Route::delete('/{id}', [RegionController::class, 'destroy']);
        Route::get('/{id}/edit', [RegionController::class, 'edit']);
    });
    Route::put('/{id}', [RegionController::class, 'update']);
});
Route::prefix('employee')->middleware('role:admin|approver')->group(function () {
    Route::get('/', [EmployeeController::class, 'index']);
    Route::post('/list', [EmployeeController::class, 'list']);

    Route::middleware('role:admin')->group(function () {
        Route::get('/create', [EmployeeController::class, 'create']);
        Route::post('/', [EmployeeController::class, 'store']);
        Route::get('/{id}', [EmployeeController::class, 'show']);
        Route::delete('/{id}', [EmployeeController::class, 'destroy']);
        Route::get('/{id}/edit', [EmployeeController::class, 'edit']);
    });
    Route::put('/{id}', [EmployeeController::class, 'update']);
});

Route::prefix('submission-reservation')->middleware('role:approver')->group(function () {
    Route::get('/', [SubmissionReservationController::class, 'index']);
    Route::post('/list', [SubmissionReservationController::class, 'list']);
    Route::get('/{id}/proses', [SubmissionReservationController::class, 'proses']);
    Route::put('/{id}', [SubmissionReservationController::class, 'update']);
    Route::get('/{id}', [SubmissionReservationController::class, 'show']);
});

Route::prefix('reservation')->middleware('role:admin|approver')->group(function () {
    Route::get('/export', [ReservationController::class, 'export']);
    Route::get('/', [ReservationController::class, 'index']);
    Route::post('/list', [ReservationController::class, 'list']);

    Route::middleware('role:admin')->group(function () {
        Route::get('/create', [ReservationController::class, 'create']);
        Route::post('/', [ReservationController::class, 'store']);
        Route::delete('/{id}', [ReservationController::class, 'destroy']);
        Route::get('/{id}/edit', [ReservationController::class, 'edit']);
        Route::put('/{id}', [ReservationController::class, 'update']);
    });
    Route::get('/{id}', [ReservationController::class, 'show']);
});