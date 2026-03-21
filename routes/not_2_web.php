<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\RelativeController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

// Redirect '/' to Dashboard if logged in
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : view('welcome');
});

// Dashboard route (requires authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Super Admin & KKDA Admin routes
    Route::middleware('role:Super Admin,KKDA Admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('roles', UserRoleController::class)->except(['show']);
        Route::get('works/index', [WorkController::class, 'index'])->name('works.index');
        Route::get('work/status/{status}', [WorkController::class, 'get_work_by_status'])->name('works.status');
        Route::get('works/dashboard', [WorkController::class, 'dashboard'])->name('works.dashboard');
    });

    // Work management routes for authorized roles
    Route::middleware('role:Super Admin,KKDA Admin,In-Charge')->group(function () {
        Route::resource('works', WorkController::class)->except(['show']);
    });

    // Show work to all authenticated users
    Route::get('works/myworks', [WorkController::class, 'myWorks'])->name('works.myWorks');
    Route::get('works/{work}', [WorkController::class, 'show'])->name('works.show');
    
    // Relatives routes
    Route::prefix('works/{work}')->group(function () {
        Route::get('/relatives/create', [RelativeController::class, 'create'])->name('relatives.create');
        Route::post('/relatives', [RelativeController::class, 'store'])->name('relatives.store');
    });
    Route::delete('/relatives/{relative}', [RelativeController::class, 'destroy'])->name('relatives.destroy');
    
    // Inspections routes for authorized roles
    Route::middleware('role:Super Admin,KKDA Admin,Surveyor,Reporter,Checker')->group(function () {
        Route::prefix('works/{workId}')->group(function () {
            Route::get('/inspections/create', [InspectionController::class, 'create'])->name('inspections.create');
            Route::post('/inspections', [InspectionController::class, 'store'])->name('inspections.store');
        });
        Route::get('inspections/{inspection}', [InspectionController::class, 'show'])->name('inspections.show');
        Route::get('inspections/{inspection}/edit', [InspectionController::class, 'edit'])->name('inspections.edit');
        Route::put('inspections/{inspection}', [InspectionController::class, 'update'])->name('inspections.update');
    });

    // Reports routes
    Route::middleware('role:Super Admin,KKDA Admin,Surveyor,Reporter,Checker')->group(function () {
        Route::get('reports/create/{workId}', [ReportController::class, 'create'])->name('reports.create');
        Route::get('reports/{id}', [ReportController::class, 'show'])->name('reports.show');
    });

    // Documents routes
    Route::prefix('documents')->group(function () {
        Route::get('/create/{work_id}', [DocumentController::class, 'create'])->name('documents.create');
        Route::post('/store/{work_id}', [DocumentController::class, 'store'])->name('documents.store');
        Route::delete('/destroy/{id}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    });

    // Role-specific work listings
    Route::get('/works/reporter', [WorkController::class, 'worksForReporter'])->name('works.reporter');
    Route::get('/works/bank-branch', [WorkController::class, 'worksForBankBranch'])->name('works.bankBranch');
    Route::get('/works/surveyor', [WorkController::class, 'worksAsSurveyor'])->name('works.surveyor');
});

// Load authentication routes if available
if (file_exists(__DIR__.'/auth.php')) {
    require __DIR__.'/auth.php';
}