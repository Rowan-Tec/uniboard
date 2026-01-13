<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\LostFoundController;
use App\Http\Controllers\MessageController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    // Notices Stats
    $totalNotices = \App\Models\Notice::count();
    $approvedNotices = \App\Models\Notice::where('is_approved', true)->count();
    $pendingNotices = \App\Models\Notice::where('is_approved', false)->count();

    // Lost & Found Stats (Global)
    $totalLostItems = \App\Models\LostFoundItem::lost()->count();
    $totalFoundItems = \App\Models\LostFoundItem::found()->count();

    // Personal Lost & Found (for staff)
    $userLostItems = \App\Models\LostFoundItem::where('user_id', $user->id)->where('type', 'lost')->count();
    $userFoundItems = \App\Models\LostFoundItem::where('user_id', $user->id)->where('type', 'found')->count();

    // Personal Notices (for staff)
    $userTotalNotices = \App\Models\Notice::where('user_id', $user->id)->count();

    return view('dashboard', compact(
        'totalNotices', 'approvedNotices', 'pendingNotices',
        'totalLostItems', 'totalFoundItems',
        'userTotalNotices', 'userLostItems', 'userFoundItems'
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


Route::middleware('auth')->group(function () {
    Route::get('/lost-found', [LostFoundController::class, 'index'])->name('lostfound.index');
    Route::post('/lost-found', [LostFoundController::class, 'store'])->name('lostfound.store');
    Route::get('/lost-found/{item}', [LostFoundController::class, 'show'])->name('lostfound.show');
    Route::patch('/lost-found/{item}/resolve', [LostFoundController::class, 'resolve'])->name('lostfound.resolve');
});


Route::middleware('auth')->group(function () {
    Route::get('/messages', [MessageController::class, 'inbox'])->name('messages.inbox');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [MessageController::class, 'send'])->name('messages.send');
});
Route::post('/messages/{user}/typing', [MessageController::class, 'typing'])->name('messages.typing');

require __DIR__.'/auth.php';
