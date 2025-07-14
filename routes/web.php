<?php

use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\BreedController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\HistoryController;
use App\Http\Controllers\Admin\Invoices_detailController;
use App\Http\Controllers\Admin\InvoicesController;
use App\Http\Controllers\Admin\PetController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SpeciesController;
use App\Http\Controllers\Admin\VeterinaryController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified', 'can:dashboard.view'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::prefix('admin')->middleware(['auth'])->group(function () {
    // Clientes
    Route::resource('cliente', CustomerController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->names('admin.customer')
        ->middleware('can:admin.customer.index');
    Route::get('cliente/consultar-dni', [CustomerController::class, 'consultarDni'])
        ->name('admin.customer.consultar-dni')->middleware('can:admin.customer.consultar-dni');
    Route::get('cliente/export-pdf', [CustomerController::class, 'exportPdf'])
        ->name('admin.customer.export-pdf')->middleware('can:admin.customer.export-pdf');
    Route::get('cliente/export-excel', [CustomerController::class, 'exportExcel'])
        ->name('admin.customer.export-excel')->middleware('can:admin.customer.export-excel');

    // Empleados
    Route::resource('empleado', EmployeeController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->names('admin.employee')->middleware('can:admin.employee.index');
    Route::get('empleado/consultar-dni', [EmployeeController::class, 'consultarDni'])
        ->name('admin.employee.consultar-dni')->middleware('can:admin.employee.consultar-dni');
    Route::get('empleado/export-pdf', [EmployeeController::class, 'exportPdf'])
        ->name('admin.employee.export-pdf')->middleware('can:admin.employee.export-pdf');
    Route::get('empleado/export-excel', [EmployeeController::class, 'exportExcel'])
        ->name('admin.employee.export-excel')->middleware('can:admin.employee.export-excel');

    // Especies
    Route::resource('especie', SpeciesController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->names('admin.species')->middleware('can:admin.species.index');
    Route::get('especie/export-pdf', [SpeciesController::class, 'exportPdf'])
        ->name('admin.species.export-pdf')->middleware('can:admin.species.export-pdf');
    Route::get('especie/export-excel', [SpeciesController::class, 'exportExcel'])
        ->name('admin.species.export-excel')->middleware('can:admin.species.export-excel');

    // Servicios
    Route::resource('servicio', ServiceController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->names('admin.service')->middleware('can:admin.service.index');
    Route::get('servicio/export-pdf', [ServiceController::class, 'exportPdf'])
        ->name('admin.service.export-pdf')->middleware('can:admin.service.export-pdf');
    Route::get('servicio/export-excel', [ServiceController::class, 'exportExcel'])
        ->name('admin.service.export-excel')->middleware('can:admin.service.export-excel');

    // Productos
    Route::resource('producto', ProductController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->names('admin.product')->middleware('can:admin.product.index');
    Route::get('producto/export-pdf', [ProductController::class, 'exportPdf'])
        ->name('admin.product.export-pdf')->middleware('can:admin.product.export-pdf');
    Route::get('producto/export-excel', [ProductController::class, 'exportExcel'])
        ->name('admin.product.export-excel')->middleware('can:admin.product.export-excel');

    // Razas
    Route::resource('raza', BreedController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->names('admin.breed')->middleware('can:admin.breed.index');
    Route::get('raza/export-pdf', [BreedController::class, 'exportPdf'])
        ->name('admin.breed.export-pdf')->middleware('can:admin.breed.export-pdf');
    Route::get('raza/export-excel', [BreedController::class, 'exportExcel'])
        ->name('admin.breed.export-excel')->middleware('can:admin.breed.export-excel');

    // Veterinarios
    Route::resource('veterinarios', VeterinaryController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->names('admin.veterinary')->middleware('can:admin.veterinary.index');
    Route::get('veterinario/export-pdf', [VeterinaryController::class, 'exportPdf'])
        ->name('admin.veterinary.export-pdf')->middleware('can:admin.veterinary.export-pdf');
    Route::get('veterinario/export-excel', [VeterinaryController::class, 'exportExcel'])
        ->name('admin.veterinary.export-excel')->middleware('can:admin.veterinary.export-excel');

    // Mascotas
    Route::resource('mascotas', PetController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->names('admin.pet')->middleware('can:admin.pet.index');
    Route::get('mascota/export-pdf', [PetController::class, 'exportPdf'])
        ->name('admin.pet.export-pdf')->middleware('can:admin.pet.export-pdf');
    Route::get('mascota/export-excel', [PetController::class, 'exportExcel'])
        ->name('admin.pet.export-excel')->middleware('can:admin.pet.export-excel');

    // Citas
    Route::resource('citas', AppointmentController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->names('admin.appointment')->middleware('can:admin.appointment.index');
    Route::get('citas/export-pdf', [AppointmentController::class, 'exportPdf'])
        ->name('admin.appointment.export-pdf')->middleware('can:admin.appointment.export-pdf');
    Route::get('citas/export-excel', [AppointmentController::class, 'exportExcel'])
        ->name('admin.appointment.export-excel')->middleware('can:admin.appointment.export-excel');

    // Historial médico
    Route::resource('historial-medico', HistoryController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->names('admin.history')->middleware('can:admin.history.index');
    Route::get('historial-medico/export-pdf', [HistoryController::class, 'exportPdf'])
        ->name('admin.history.export-pdf')->middleware('can:admin.history.export-pdf');
    Route::get('historial-medico/export-excel', [HistoryController::class, 'exportExcel'])
        ->name('admin.history.export-excel')->middleware('can:admin.history.export-excel');

    // Facturas
    Route::resource('factura', InvoicesController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->names('admin.invoices')->middleware('can:admin.invoices.index');
    Route::get('factura/export-pdf', [InvoicesController::class, 'exportPdf'])
        ->name('admin.invoices.export-pdf')->middleware('can:admin.invoices.export-pdf');
    Route::get('factura/export-excel', [InvoicesController::class, 'exportExcel'])
        ->name('admin.invoices.export-excel')->middleware('can:admin.invoices.export-excel');

    // Detalle de factura
    Route::resource('detalle-factura', Invoices_detailController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->names('admin.invoices_detail')->middleware('can:admin.invoices_detail.index');
    Route::get('detalle-factura/export-pdf', [Invoices_detailController::class, 'exportPdf'])
        ->name('admin.invoices_detail.export-pdf')->middleware('can:admin.invoices_detail.export-pdf');
    Route::get('detalle-factura/export-excel', [Invoices_detailController::class, 'exportExcel'])
        ->name('admin.invoices_detail.export-excel')->middleware('can:admin.invoices_detail.export-excel');
});


require __DIR__.'/auth.php';

