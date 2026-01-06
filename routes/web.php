<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoticeController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    // Global stats (for admin)
    $totalNotices = \App\Models\Notice::count();
    $approvedNotices = \App\Models\Notice::where('is_approved', true)->count();
    $pendingNotices = \App\Models\Notice::where('is_approved', false)->count();
    $totalUsers = \App\Models\User::count();

    // Personal stats (for staff)
    $userTotalNotices = \App\Models\Notice::where('user_id', $user->id)->count();
    $userApprovedNotices = \App\Models\Notice::where('user_id', $user->id)->where('is_approved', true)->count();
    $userPendingNotices = \App\Models\Notice::where('user_id', $user->id)->where('is_approved', false)->count();

    return view('dashboard', compact(
        'totalNotices', 'approvedNotices', 'pendingNotices', 'totalUsers',
        'userTotalNotices', 'userApprovedNotices', 'userPendingNotices'
    ));
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware('auth')->group(function () {
    Route::get('/notices', [NoticeController::class, 'index'])->name('notices.index');
    Route::post('/notices', [NoticeController::class, 'store'])->name('notices.store');

    Route::get('/admin/notices/approval', [NoticeController::class, 'approval'])->name('notices.approval');
    Route::post('/notices/{notice}/approve', [NoticeController::class, 'approve'])->name('notices.approve');
    Route::post('/notices/{notice}/reject', [NoticeController::class, 'reject'])->name('notices.reject');
});


require __DIR__.'/auth.php';
