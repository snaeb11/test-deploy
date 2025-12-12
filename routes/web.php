<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdviserManagementController;
use App\Http\Controllers\DownloadableFormController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\CheckController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InventoryExportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgramManagementController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\UserAccountsController;
use App\Imports\InventoryImport;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

// shows sql errors on laravel.log
DB::listen(function ($query) {
    Log::channel('single')->debug($query->sql . ' | ' . json_encode($query->bindings));
});

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Home route (accessible to both guests and logged-in users)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Downloads page
Route::get('/downloads', function () {
    return view('layouts.landing.index', ['page' => 'downloads']);
})->name('downloads');

// Public API for downloadable forms (used by landing popups)
Route::get('/downloadable-forms/{category}', [DownloadableFormController::class, 'getByCategory']);

// Search results for landing table
Route::get('/search', [InventoryController::class, 'search'])->name('search');

// Guest routes (Login, Signup)
Route::middleware('guest')->group(function () {
    // Authentication
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');

    // Registration
    Route::get('/signup', [SignupController::class, 'showRegistrationForm'])->name('signup');
    Route::post('/signup', [SignupController::class, 'store'])->name('signup.store');

    // Email verification
    Route::post('/verify-email-code', [VerificationController::class, 'verifyCode'])->name('verify.code');
    Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

    // Forgot Password
    Route::get('/forgot-password', function () {
        return redirect()->route('login');
    })->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    // Reset Password
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.reset.update');

    Auth::routes(['verify' => true]);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    // Admin-side routes
    Route::get('/admin/dashboard', [ProfileController::class, 'showAdminDashboard'])->name('admin.dashboard');
    Route::put('/admin/update-profile', [ProfileController::class, 'updateAdminProfile'])->name('admin.profile.update');
    Route::post('/admin/users/create', [UserAccountsController::class, 'store'])->name('admin.store');
    Route::get('/admin/users/can-add', [UserAccountsController::class, 'canAddAdmin'])->name('admin.canAdd');
    Route::get('/admin/users/{user}/permissions', [UserAccountsController::class, 'getUserPermissions']);
    Route::post('/admin/users/{user}/update-permissions', [UserAccountsController::class, 'updatePermissions']);
    Route::get('/test-permission-check', function () {
        $user = auth()->user();
        $permissions = array_map('trim', explode(',', $user->permissions));

        return [
            'direct_check' => in_array('edit-permissions', $permissions),
            'strict_check' => in_array('edit-permissions', $permissions, true),
            'types' => array_map('gettype', $permissions),
        ];
    });
    Route::put('/inventories/{inventory}', [InventoryController::class, 'update'])->name('inventories.update');
    // ignore his ass ^^
    Route::get('/submission/filtersSubs', [SubmissionController::class, 'filtersSubs']);
    Route::get('/submission/filtersHistory', [SubmissionController::class, 'filtersHistory']);
    Route::get('/submission/data', [SubmissionController::class, 'getSubmissionData']);
    Route::get('/submission/history', [SubmissionController::class, 'history']);
    Route::post('/submission/{id}/reject', [SubmissionController::class, 'reject']);
    Route::post('/submission/{id}/approve', [SubmissionController::class, 'approve'])->name('submission.approve');
    Route::get('/submissions/{id}/view', [SubmissionController::class, 'viewFile'])->name('submissions.view');
    Route::get('/submissions/{id}/download', [SubmissionController::class, 'download'])->name('submissions.download');
    Route::post('/inventory/store', [InventoryController::class, 'store'])->name('inventory.store');
    Route::get('/inventory/{id}/view', [InventoryController::class, 'viewFileInv'])->name('inventory.view');
    Route::get('/inventory/check-duplicate-title', [InventoryController::class, 'checkDuplicateTitle'])->name('inventory.check-duplicate-title');
    Route::get('/inventory/filtersInv', [InventoryController::class, 'FiltersInv']);
    Route::get('/inventory/data', [InventoryController::class, 'getInventoryData']);
    Route::post('/inventory/import-excel', function (Request $request) {
        $request->validate(['file' => 'required|file|mimes:xlsx']);

        try {
            Excel::import(new InventoryImport(), $request->file('file'));

            // Log successful import
            $uploaded = $request->file('file');
            UserActivityLog::log(auth()->user(), UserActivityLog::ACTION_INVENTORY_IMPORTED, 'inventories', null, [
                'filename' => $uploaded?->getClientOriginalName(),
                'mime' => $uploaded?->getClientMimeType(),
                'size' => $uploaded?->getSize(),
                'driver' => 'excel',
            ]);

            return response()->json(['message' => 'Import completed']);
        } catch (ValidationException $e) {
            // Collect all validation errors
            $failures = $e->failures();
            $messages = [];

            foreach ($failures as $failure) {
                // Row number (1-based) and attribute error
                $messages[] = "Row {$failure->row()}: {$failure->attribute()} - {$failure->errors()[0]}";
            }

            return response()->json(
                [
                    'message' => 'Import failed',
                    'errors' => $messages,
                ],
                422,
            );
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    });
    Route::get('/admin/inventory/export-docx', [InventoryController::class, 'exportInventoriesDocx'])->name('inventory.export.docx');
    Route::get('/inventory/export/excel', [InventoryExportController::class, 'excel'])->name('inventory.export.excel');
    Route::get('/admin/inventory/export-pdf', [InventoryController::class, 'exportInventoriesPdf'])->name('inventory.export.pdf');
    Route::get('/users/data', [UserAccountsController::class, 'getAllUsers']);
    Route::get('/logs/data', [AdminController::class, 'getLogsData']);

    // Programs management API
    Route::get('/admin/programs', [ProgramManagementController::class, 'index']);
    Route::post('/admin/programs', [ProgramManagementController::class, 'store']);
    Route::put('/admin/programs/{program}', [ProgramManagementController::class, 'update']);
    Route::delete('/admin/programs/{program}', [ProgramManagementController::class, 'destroy']);

    // Advisers management API
    Route::get('/admin/advisers', [AdviserManagementController::class, 'index']);
    Route::post('/admin/advisers', [AdviserManagementController::class, 'store']);
    Route::put('/admin/advisers/{adviser}', [AdviserManagementController::class, 'update']);
    Route::delete('/admin/advisers/{adviser}', [AdviserManagementController::class, 'destroy']);

    // Downloadable forms management API
    Route::get('/admin/downloadable-forms', [DownloadableFormController::class, 'index']);
    Route::post('/admin/downloadable-forms', [DownloadableFormController::class, 'store']);
    Route::put('/admin/downloadable-forms/{downloadableForm}', [DownloadableFormController::class, 'update']);
    Route::delete('/admin/downloadable-forms/{downloadableForm}', [DownloadableFormController::class, 'destroy']);

    Route::prefix('admin')->group(function () {
        Route::get('backup/download', [BackupController::class, 'download'])->name('admin.backup.download');
        Route::post('backup/restore', [BackupController::class, 'restore'])->name('admin.backup.restore');
        Route::post('backup/reset', [BackupController::class, 'backupAndReset'])->name('admin.backup.reset');
    });

    // User-side routes
    Route::get('/user/dashboard', [ProfileController::class, 'showUserDashboard'])->name('user.dashboard');
    Route::put('/user/update-profile', [ProfileController::class, 'update'])->name('profile.update');

    // Faculty-side routes
    Route::get('/faculty/dashboard', [ProfileController::class, 'showFacultyDashboard'])->name('faculty.dashboard');
    Route::put('/faculty/update-profile', [ProfileController::class, 'updateFacultyProfile'])->name('faculty.profile.update');
    Route::post('/user/deactivate', [ProfileController::class, 'deactivate_account'])->name('account.deactivate');
    Route::get('/submissions/pending', [SubmissionController::class, 'pending'])->name('submissions.pending');
    Route::get('/forms/pending', [SubmissionController::class, 'pendingForms'])->name('forms.pending');
    Route::get('/forms/history', [SubmissionController::class, 'formsHistoryAdmin'])->name('forms.history');
    // Forms history for faculty uses this endpoint; keep as-is
    Route::get('/submissions/history', [SubmissionController::class, 'show_submission_history'])->name('submissions.history');
    // User thesis submissions history (from submissions table)
    Route::get('/user/submissions/history', [SubmissionController::class, 'userThesisHistory'])->name('user.submissions.history');
    Route::post('/submit-thesis', [SubmissionController::class, 'submitThesis'])->name('thesis.submit');
    Route::post('/submit-form', [SubmissionController::class, 'submitForm'])->name('form.submit');
    Route::get('/submissions/{id}/resubmit-data', [SubmissionController::class, 'getResubmitData'])->name('submissions.resubmit-data');
    Route::get('/forms/{id}/resubmit-data', [SubmissionController::class, 'getFormResubmitData'])->name('forms.resubmit-data');
    Route::post('/submissions/{id}/resubmit', [SubmissionController::class, 'resubmitThesis'])->name('thesis.resubmit');
    Route::post('/forms/{id}/resubmit', [SubmissionController::class, 'resubmitForm'])->name('form.resubmit');
    Route::post('/check-duplicate-title', [SubmissionController::class, 'checkDuplicateTitle'])->name('thesis.check-duplicate-title');
    Route::get('/submissions/{submission}/download', [SubmissionController::class, 'download'])->name('submissions.download');
    Route::get('/submissions/{id}/downloadMan', [SubmissionController::class, 'downloadManuscript']);
    Route::get('/forms/{id}/view', [SubmissionController::class, 'viewFacultyForm'])->name('forms.view');
    Route::get('/forms/{id}/admin-view', [SubmissionController::class, 'viewFacultyFormAdmin'])->name('forms.admin-view');
    Route::get('/forms/{id}/download', [SubmissionController::class, 'downloadForm'])->name('forms.download');
    Route::post('/forms/{id}/approve', [SubmissionController::class, 'approveForm'])->name('forms.approve');
    Route::post('/forms/{id}/reject', [SubmissionController::class, 'rejectForm'])->name('forms.reject');
    Route::post('/forms/{id}/forward', [SubmissionController::class, 'forwardForm'])->name('forms.forward');
    Route::put('/password/update', [PasswordController::class, 'update_password'])->name('password.update');
    Route::delete('/forms/{id}', [SubmissionController::class, 'deleteForm'])->name('forms.delete');

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Check Routes
|--------------------------------------------------------------------------
*/

Route::get('/check', [CheckController::class, 'index'])->name('check');
Route::get('/button', [CheckController::class, 'button'])->name('check.button');
Route::get('/user', [CheckController::class, 'user'])->name('check.user');

// ======================================================================================================================================
Route::get('/check', [CheckController::class, 'showRegistrationForm'])->name('check');

// nyehehehehe
Route::get('/inventory/{id}/download', [InventoryController::class, 'download'])->name('inventory.download');
