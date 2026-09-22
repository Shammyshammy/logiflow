<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\ShipmentEventController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;

// -------------------------
// Public routes
// -------------------------
Route::get('/', [TrackingController::class, 'index'])->name('home');
Route::get('/track', [TrackingController::class, 'index'])->name('tracking.index');
Route::post('/track', [TrackingController::class, 'search'])->name('tracking.search');
Route::get('/track/{trackingNumber}', [TrackingController::class, 'show'])->name('tracking.show');
Route::get('/book', [BookingController::class, 'show'])->name('booking.show');
Route::post('/book', [BookingController::class, 'store'])->name('booking.store');
Route::get('/book/success/{trackingNumber}', [BookingController::class, 'success'])->name('booking.success');

// -------------------------
// Authenticated routes
// -------------------------
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin & staff management routes
    Route::middleware('role:admin,staff')->group(function () {
        Route::resource('customers', CustomerController::class);
        Route::resource('drivers', DriverController::class);
        Route::resource('vehicles', VehicleController::class);
        Route::resource('shipments', ShipmentController::class);
        Route::post('shipments/{shipment}/events', [ShipmentEventController::class, 'store'])
             ->name('shipments.events.store');
             Route::post('shipments/{shipment}/assign', [ShipmentController::class, 'assign'])
     ->name('shipments.assign');
Route::post('shipments/{shipment}/unassign', [ShipmentController::class, 'unassign'])
     ->name('shipments.unassign');
     Route::get('shipments/export/csv', [ShipmentController::class, 'exportCsv'])->name('shipments.export.csv');
     Route::get('shipments/{shipment}/pdf', [ShipmentController::class, 'exportPdf'])->name('shipments.pdf');
Route::get('shipments/export/pdf/all', [ShipmentController::class, 'exportAllPdf'])->name('shipments.export.all.pdf');

Route::get('activity', [ActivityLogController::class, 'index'])->name('activity.index');
    });

    // Driver routes
Route::middleware('role:driver')->group(function () {
    Route::get('driver/shipments/{shipment}', [ShipmentController::class, 'driverShow'])
         ->name('driver.shipments.show');
    Route::post('driver/shipments/{shipment}/status', [ShipmentEventController::class, 'driverUpdate'])
         ->name('driver.shipments.status');
});

Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::post('notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
Route::post('notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');

});

require __DIR__.'/auth.php';