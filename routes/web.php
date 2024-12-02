<?php

use App\Livewire\Dashboard;
use App\Livewire\HumanResource\Structure\EmployeeInfo;
use App\Livewire\HumanResource\Structure\Employees;
use App\Livewire\HumanResource\COE;
use App\Livewire\HumanResource\Leave;
use App\Livewire\HumanResource\Payslip;
use App\Livewire\HumanResource\ServiceRecords;
use App\Livewire\HumanResource\Statistics;
use App\Livewire\Misc\ComingSoon;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {

    // Dashboard
    Route::group(['middleware' => ['role:Admin|Employee']], function () {
        Route::redirect('/', '/dashboard');
        Route::get('/dashboard', Dashboard::class)->name('dashboard');
    });

    // Human Resource - Employee Structure
    Route::group(['middleware' => ['role:Admin|HR']], function () {
        Route::prefix('structure')->group(function () {
            Route::get('/employees', Employees::class)->name('structure-employees');
            Route::get('/employee/{employee_id?}', EmployeeInfo::class)->name('structure-employees-info');
        });

        Route::get('/coe', COE::class)->name('coe');
        Route::get('/leave', Leave::class)->name('leave');
        Route::get('/payslip', Payslip::class)->name('payslip');
        Route::get('/serviceRecords', ServiceRecords::class)->name('serviceRecords');
    });

    // Statistics
    Route::group(['middleware' => ['role:Employee|Admin|HR']], function () {
        Route::get('/statistics', Statistics::class)->name('statistics');
    });

    // Coming Soon Page for Reports
    Route::group(['middleware' => ['role:Employee|Admin|AM|HR']], function () {
        Route::get('/assets/reports', ComingSoon::class)->name('reports');
    });

    // Attachments
    Route::get('/request/{id}/download', [Dashboard::class, 'downloadAttachment'])->name('download.attachment');
    Route::get('/request/{id}/view', [Dashboard::class, 'viewAttachment'])->name('view.attachment');
});
