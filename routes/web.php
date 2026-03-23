<?php



use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\RelativeController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\RoleWiseController;
use App\Http\Controllers\AccountController;
// Redirect '/' to Dashboard if logged in
Route::get('/', function () {
    // echo("expired license key");
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
    
});





// Dashboard route (requires authentication)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Profile routes (only for logged-in users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Impersonation stop route (accessible when impersonating)
    Route::post('impersonate/stop', [UserController::class, 'stopImpersonating'])->name('impersonate.stop');
});
Route::get('/works/reporter', [WorkController::class, 'worksForReporter'])->name('works.reporter');
Route::get('/works/bank-branch', [WorkController::class, 'worksForBankBranch'])->name('works.bankBranch');
Route::get('/works/surveyor', [WorkController::class, 'worksAsSurveyor'])->name('works.surveyor');
Route::get('/works/checking', [WorkController::class, 'worksAsChecking'])->name('works.checking');
Route::get('/works/delivery', [WorkController::class, 'worksAsDelivery'])->name('works.delivery');

// Ensure role middleware is properly registered in Laravel 12
Route::middleware(['auth'])->group(function () {
    Route::middleware('role:Super Admin,KKDA Admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::post('users/{user}/impersonate', [UserController::class, 'impersonate'])->name('users.impersonate');
        Route::post('users/{user}/toggle-login', [UserController::class, 'toggleLogin'])->name('users.toggleLogin');
        Route::resource('roles', UserRoleController::class)->except(['show']);
        Route::get('works/index', [WorkController::class, 'index'])->name('works.index');
        Route::get('works/export', [WorkController::class, 'export'])->name('works.export');
        Route::get('work/status', [WorkController::class, 'get_work_by_status'])->name('works.status');
        Route::get('works/dashboard', [WorkController::class, 'dashboard'])->name('works.dashboard');
        Route::get('role-wise', [RoleWiseController::class, 'index'])->name('role-wise.index');
        Route::get('role-wise/stats/{roleName}', [RoleWiseController::class, 'getRoleStats'])->name('role-wise.stats');
    });

    Route::middleware('role:Super Admin,KKDA Admin,Accountant')->group(function () {
        Route::get('account', [AccountController::class, 'index'])->name('account.index');
        Route::post('works/{work}/mark-billing-done', [AccountController::class, 'markBillingDone'])->name('works.markBillingDone');
        Route::patch('works/{work}/billing', [AccountController::class, 'updateBilling'])->name('works.updateBilling');
    });
    
    // Status update route - accessible to multiple roles (permission checked in controller)
    Route::post('works/{work}/update-status', [WorkController::class, 'updateStatus'])->name('works.updateStatus');
    Route::post('works/{work}/advance-status', [WorkController::class, 'advanceStatus'])->name('works.advanceStatus');
    Route::post('works/{work}/start-reporting', [WorkController::class, 'startReporting'])->name('works.startReporting');
    Route::post('works/{work}/end-reporting', [WorkController::class, 'endReporting'])->name('works.endReporting');
    Route::post('works/{work}/start-checking', [WorkController::class, 'startChecking'])->name('works.startChecking');
    Route::post('works/{work}/end-checking', [WorkController::class, 'endChecking'])->name('works.endChecking');
    // Toggle routes - accessible to all roles except Bank Branch (permission checked in controller)
    Route::post('works/{work}/toggle-printed', [WorkController::class, 'togglePrinted'])->name('works.togglePrinted');
    Route::post('works/{work}/toggle-vdn', [WorkController::class, 'toggleVdn'])->name('works.toggleVdn');
    Route::post('works/{work}/toggle-result', [WorkController::class, 'toggleResult'])->name('works.toggleResult');
    
    // Debug route for testing password reset
    Route::get('debug-password-reset/{user}', function($user) {
        return response()->json([
            'user_id' => $user,
            'route_exists' => true,
            'method' => 'POST',
            'url' => route('users.reset-password', $user)
        ]);
    })->name('debug.password-reset');
});

Route::middleware(['auth'])->group(function () {
    Route::middleware('role:Super Admin,KKDA Admin,In-Charge')->group(function () {
        Route::get('works/create', [WorkController::class, 'create'])->name('works.create');
        Route::post('works', [WorkController::class, 'store'])->name('works.store');
        Route::get('works/{work}/edit', [WorkController::class, 'edit'])->name('works.edit');
        Route::put('works/{work}', [WorkController::class, 'update'])->name('works.update');
        Route::post('works/{work}/upload-documents', [WorkController::class, 'documentUpload'])->name('works.uploadDocuments');
        Route::delete('works/{work}', [WorkController::class, 'destroy'])->name('works.destroy');
    });
});
Route::middleware(['auth'])->group(function () {
    // Add the route to show work
    Route::get('works/myworks', [WorkController::class, 'myWorks'])->name('works.myWorks');
    Route::get('works/{work}', [WorkController::class, 'show'])->name('works.show');
    Route::get('/works/{work}/relatives/create', [RelativeController::class, 'create'])->name('relatives.create');
    Route::post('/works/{work}/relatives', [RelativeController::class, 'store'])->name('relatives.store');
    Route::delete('/relatives/{relative}', [RelativeController::class, 'destroy'])->name('relatives.destroy');
    
    // Pincode lookup route
    Route::get('api/pincode/{pincode}', [WorkController::class, 'getPostOfficeByPincode'])->name('api.pincode.lookup');
});

Route::middleware(['auth'])->group(function () {
    Route::middleware('role:Super Admin,KKDA Admin,Surveyor,Reporter,Checker')->group(function () {
        Route::get('inspections/{id}', [InspectionController::class, 'show'])->name('inspections.show');
        Route::get('works/{workId}/inspections/create', [InspectionController::class, 'create'])->name('inspections.create');
        Route::post('works/{workId}/inspections', [InspectionController::class, 'store'])->name('inspections.store');
        Route::get('inspections/{inspection}/edit', [InspectionController::class, 'edit'])->name('inspections.edit');
        Route::put('inspections/{inspection}', [InspectionController::class, 'update'])->name('inspections.update');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::middleware('role:Super Admin,KKDA Admin,Surveyor,Reporter,Checker')->group(function () {
        Route::get('reports/create/{workId}', [ReportController::class, 'create'])->name('reports.create');
        Route::post('/report/store', [ReportController::class, 'store'])->name('report.store');
        Route::get('/reports/{id}/edit', [ReportController::class, 'edit'])->name('reports.edit');
        Route::get('/report/{id}lnb_edit/', [ReportController::class, 'lnb_edit'])->name('report.lnb_edit');
        Route::put('/report/{id}', [ReportController::class, 'update'])->name('report.update');
        Route::put('/report_lnb/{id}', [ReportController::class, 'lnb_update'])->name('report_lnb.update');
        
        Route::get('reports/create_lnb/{workId}', [ReportController::class, 'lnb_create'])->name('reports.lnb_create');
        Route::post('/report/lnb_store', [ReportController::class, 'lnb_store'])->name('report.lnb_store');
        
        Route::get('/report/select/{workId}', [ReportController::class, 'select'])->name('report.select');
        Route::get('/report/select_edit/{reportId}', [ReportController::class, 'select_edit'])->name('report.select_edit');

    });
});




Route::get('/documents/create/{work_id}', [DocumentController::class, 'create'])->name('documents.create');
Route::post('/documents/store/{work_id}', [DocumentController::class, 'store'])->name('documents.store');
Route::delete('/documents/destroy/{id}', [DocumentController::class, 'destroy'])->name('documents.destroy');



// Load authentication routes if available
if (file_exists(__DIR__.'/auth.php')) {
    require __DIR__.'/auth.php';
}
