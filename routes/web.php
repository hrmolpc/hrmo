<?php

use App\Http\Controllers\language\LanguageController;
use App\Livewire\Assets\Categories;
use App\Livewire\Assets\Inventory;
use App\Livewire\ContactUs;
use App\Livewire\Dashboard;
use App\Livewire\HumanResource\Attendance\Fingerprints;
use App\Livewire\HumanResource\Attendance\Leaves;
use App\Livewire\HumanResource\Discounts;
use App\Livewire\HumanResource\Holidays;
use App\Livewire\HumanResource\COE;
use App\Livewire\HumanResource\ServiceRecords;
use App\Livewire\HumanResource\Leave;
use App\Livewire\HumanResource\Payslip;
use App\Livewire\HumanResource\Messages;
use App\Livewire\HumanResource\Statistics;
use App\Livewire\HumanResource\Structure\Centers;
use App\Livewire\HumanResource\Structure\Departments;
use App\Livewire\HumanResource\Structure\EmployeeInfo;
use App\Livewire\HumanResource\Structure\Employees;
use App\Livewire\HumanResource\Structure\Positions;
use App\Livewire\Misc\ComingSoon;
use App\Livewire\Settings\Users;
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

Route::get('lang/{locale}', [LanguageController::class, 'swap']);

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    // 👉 Dashboard
    Route::group(['middleware' => ['role:Admin|AM|CC|CR|HR']], function () {
        Route::redirect('/', '/dashboard');
        Route::get('/dashboard', Dashboard::class)->name('dashboard');
    });

 
 

    Route::group(['middleware' => ['role:Admin|HR']], function () {
        Route::prefix('structure')->group(function () {
          
       
            Route::get('/employees', Employees::class)->name('structure-employees');
            Route::get('/employee/{id?}', EmployeeInfo::class)->name('structure-employees-info');
        });
    });

    Route::group(['middleware' => ['role:Admin|HR']], function () {
        Route::get('/coe', COE::class)->name('coe');
        Route::get('/leave', Leave::class)->name('leave');
        Route::get('/payslip', Payslip::class)->name('payslip');
        Route::get('/serviceRecords', ServiceRecords::class)->name('serviceRecords');

  
    });

    Route::group(['middleware' => ['role:Admin|HR']], function () {
        Route::get('/statistics', Statistics::class)->name('statistics');
    });

    Route::group(['middleware' => ['role:Admin']], function () {
        Route::prefix('settings')->group(function () {
            Route::get('/users', Users::class)->name('settings-users');
            Route::get('/roles', ComingSoon::class)->name('settings-roles');
            Route::get('/permissions', ComingSoon::class)->name('settings-permissions');
        });
    });

 
    Route::group(['middleware' => ['role:Admin|AM|HR']], function () {
        Route::get('/assets/reports', ComingSoon::class)->name('reports');
    });
});

 

Route::webhooks('/deploy');
