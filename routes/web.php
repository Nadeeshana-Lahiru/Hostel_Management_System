<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminDashboardController; 

// Login and Logout Routes
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// NEW FORGOT PASSWORD ROUTES (for the modal workflow)
Route::post('forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::post('verify-otp', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'verifyOtp'])->name('password.verifyOtp');
Route::post('reset-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'resetPassword'])->name('password.update');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // This is where all your admin-panel routes will go
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    // Add other admin routes for wardens, students etc. here later

    // Add this line for Warden Management
    Route::resource('wardens', \App\Http\Controllers\Admin\WardenController::class);

    // Add this line for Hostel Management
    Route::resource('hostels', \App\Http\Controllers\Admin\HostelController::class);
    Route::get('hostels/room/{room}', [\App\Http\Controllers\Admin\HostelController::class, 'showRoomDetails'])->name('hostels.showRoomDetails');
    // ADD THIS NEW ROUTE for assigning a warden
    Route::patch('hostels/{hostel}/assign-warden', [\App\Http\Controllers\Admin\HostelController::class, 'assignWarden'])->name('hostels.assignWarden');

    // Add this line for Student Management
    Route::resource('students', \App\Http\Controllers\Admin\StudentController::class);

    // Routes for Room Allocation Process
    Route::get('room-allocation', [\App\Http\Controllers\Admin\RoomAllocationController::class, 'index'])->name('allocations.index');
    Route::get('room-allocation/{hostel}', [\App\Http\Controllers\Admin\RoomAllocationController::class, 'showHostelRooms'])->name('allocations.showHostelRooms');
    Route::get('room-allocation/room/{room}', [\App\Http\Controllers\Admin\RoomAllocationController::class, 'showAllocationForm'])->name('allocations.showAllocationForm');
    Route::post('room-allocation/room/{room}', [\App\Http\Controllers\Admin\RoomAllocationController::class, 'assignStudent'])->name('allocations.assignStudent');
    Route::patch('allocations/reassign/{student}', [\App\Http\Controllers\Admin\RoomAllocationController::class, 'reassignStudent'])->name('allocations.reassignStudent');

    // ...WITH THIS NEW ONE for the final confirmation step.
    Route::post('allocations/reassign-confirm/{student}/{new_room}', [\App\Http\Controllers\Admin\RoomAllocationController::class, 'confirmReassign'])->name('allocations.confirmReassign');

    // Routes for Complaint Management
    Route::get('complaints', [\App\Http\Controllers\Admin\ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('complaints/{complaint}', [\App\Http\Controllers\Admin\ComplaintController::class, 'show'])->name('complaints.show');
    Route::post('complaints/{complaint}', [\App\Http\Controllers\Admin\ComplaintController::class, 'update'])->name('complaints.update');

    // Routes for Feedback Management
    Route::get('feedback', [\App\Http\Controllers\Admin\FeedbackController::class, 'index'])->name('feedback.index');
    Route::get('feedback/questions', [\App\Http\Controllers\Admin\FeedbackController::class, 'questions'])->name('feedback.questions');
    Route::post('feedback/questions', [\App\Http\Controllers\Admin\FeedbackController::class, 'storeQuestion'])->name('feedback.storeQuestion');
    Route::patch('feedback/questions/{question}', [\App\Http\Controllers\Admin\FeedbackController::class, 'updateQuestion'])->name('feedback.updateQuestion');
    Route::delete('feedback/questions/{question}', [\App\Http\Controllers\Admin\FeedbackController::class, 'destroyQuestion'])->name('feedback.destroyQuestion');

    // Add this route for sending messages from the dashboard
    Route::post('messages', [\App\Http\Controllers\Admin\MessageController::class, 'store'])->name('messages.store');

    // Routes for Settings
    Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings/send-otp', [\App\Http\Controllers\Admin\SettingsController::class, 'sendOtp'])->name('settings.sendOtp');
    Route::post('settings/verify-otp', [\App\Http\Controllers\Admin\SettingsController::class, 'verifyOtp'])->name('settings.verifyOtp'); // New route
    Route::post('settings/change-password', [\App\Http\Controllers\Admin\SettingsController::class, 'changePassword'])->name('settings.changePassword');
});

// START: WARDEN ROUTES
Route::prefix('warden')->name('warden.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Warden\DashboardController::class, 'index'])->name('dashboard');

    // Hostels (Read-Only for Warden)
    Route::get('/hostels', [\App\Http\Controllers\Warden\HostelController::class, 'index'])->name('hostels.index');
    Route::get('/hostels/{hostel}', [\App\Http\Controllers\Warden\HostelController::class, 'show'])->name('hostels.show');
    Route::get('/hostels/room/{room}', [\App\Http\Controllers\Warden\HostelController::class, 'showRoomDetails'])->name('hostels.showRoomDetails');

    // Students (Full CRUD)
    Route::resource('students', \App\Http\Controllers\Warden\StudentController::class);
    
    // Room Allocation
    Route::get('room-allocation', [\App\Http\Controllers\Warden\RoomAllocationController::class, 'index'])->name('allocations.index');
    Route::get('room-allocation/{hostel}', [\App\Http\Controllers\Warden\RoomAllocationController::class, 'showHostelRooms'])->name('allocations.showHostelRooms');
    Route::get('room-allocation/room/{room}', [\App\Http\Controllers\Warden\RoomAllocationController::class, 'showAllocationForm'])->name('allocations.showAllocationForm');
    Route::post('room-allocation/room/{room}', [\App\Http\Controllers\Warden\RoomAllocationController::class, 'assignStudent'])->name('allocations.assignStudent');
    // ADD THIS NEW ROUTE for the final re-assignment step
    Route::post('allocations/reassign-confirm/{student}/{new_room}', [\App\Http\Controllers\Warden\RoomAllocationController::class, 'confirmReassign'])->name('allocations.confirmReassign');

    // Complaints
    Route::get('complaints', [\App\Http\Controllers\Warden\ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('complaints/{complaint}', [\App\Http\Controllers\Warden\ComplaintController::class, 'show'])->name('complaints.show');
    Route::post('complaints/{complaint}', [\App\Http\Controllers\Warden\ComplaintController::class, 'update'])->name('complaints.update');

    // Feedback
    Route::get('feedback', [\App\Http\Controllers\Warden\FeedbackController::class, 'index'])->name('feedback.index');
    Route::get('feedback/questions', [\App\Http\Controllers\Warden\FeedbackController::class, 'questions'])->name('feedback.questions');
    Route::post('feedback/questions', [\App\Http\Controllers\Warden\FeedbackController::class, 'storeQuestion'])->name('feedback.storeQuestion');
    Route::patch('feedback/questions/{question}', [\App\Http\Controllers\Warden\FeedbackController::class, 'updateQuestion'])->name('feedback.updateQuestion');
    Route::delete('feedback/questions/{question}', [\App\Http\Controllers\Warden\FeedbackController::class, 'destroyQuestion'])->name('feedback.destroyQuestion');

    // Settings
    Route::get('settings', [\App\Http\Controllers\Warden\SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings/send-otp', [\App\Http\Controllers\Warden\SettingsController::class, 'sendOtp'])->name('settings.sendOtp');
    Route::post('settings/verify-otp', [\App\Http\Controllers\Warden\SettingsController::class, 'verifyOtp'])->name('settings.verifyOtp'); // New route
    Route::post('settings/change-password', [\App\Http\Controllers\Warden\SettingsController::class, 'changePassword'])->name('settings.changePassword');

    // Message Center
    Route::post('messages', [\App\Http\Controllers\Warden\MessageController::class, 'store'])->name('messages.store');
});
// END: WARDEN ROUTES

// START: STUDENT ROUTES
Route::prefix('student')->name('student.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');

    // My Room
    Route::get('/room', [\App\Http\Controllers\Student\RoomController::class, 'index'])->name('room.index');
    Route::get('/roommate/{student}', [\App\Http\Controllers\Student\RoomController::class, 'showRoommate'])->name('room.showRoommate');

    // Complaints
    Route::get('/complaints', [\App\Http\Controllers\Student\ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/create', [\App\Http\Controllers\Student\ComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/complaints', [\App\Http\Controllers\Student\ComplaintController::class, 'store'])->name('complaints.store');

    // Feedback
    Route::get('/feedback', [\App\Http\Controllers\Student\FeedbackController::class, 'index'])->name('feedback.index');
    Route::post('/feedback', [\App\Http\Controllers\Student\FeedbackController::class, 'store'])->name('feedback.store');

    // Settings
    Route::get('settings', [\App\Http\Controllers\Student\SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings/send-otp', [\App\Http\Controllers\Student\SettingsController::class, 'sendOtp'])->name('settings.sendOtp');
    Route::post('settings/verify-otp', [\App\Http\Controllers\Student\SettingsController::class, 'verifyOtp'])->name('settings.verifyOtp');
    Route::post('settings/change-password', [\App\Http\Controllers\Student\SettingsController::class, 'changePassword'])->name('settings.changePassword');
});
// END: STUDENT ROUTES