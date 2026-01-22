<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\LostFoundController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PushController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Dashboard (all roles)
    Route::get('/dashboard', function () {
        $user = auth()->user();

        // Notices Stats
        $totalNotices = \App\Models\Notice::count();
        $approvedNotices = \App\Models\Notice::where('is_approved', true)->count();
        $pendingNotices = \App\Models\Notice::where('is_approved', false)->count();

        // Lost & Found Stats (Global)
        $totalLostItems = \App\Models\LostFoundItem::lost()->count();
        $totalFoundItems = \App\Models\LostFoundItem::found()->count();

        // Personal Stats
        $userTotalNotices = \App\Models\Notice::where('user_id', $user->id)->count();
        $userLostItems = \App\Models\LostFoundItem::where('user_id', $user->id)->where('type', 'lost')->count();
        $userFoundItems = \App\Models\LostFoundItem::where('user_id', $user->id)->where('type', 'found')->count();

        // Only fetch users for admin
        $users = null;
        if ($user->role === 'admin') {
            $users = \App\Models\User::latest()->paginate(15);
        }

        return view('dashboard', compact(
            'totalNotices', 'approvedNotices', 'pendingNotices',
            'totalLostItems', 'totalFoundItems',
            'userTotalNotices', 'userLostItems', 'userFoundItems',
            'users'
        ));
    })->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notices (all users)
    Route::prefix('notices')->group(function () {
        Route::get('/', [NoticeController::class, 'index'])->name('notices.index');
        Route::get('/create', [NoticeController::class, 'create'])->name('notices.create');
        Route::post('/', [NoticeController::class, 'store'])->name('notices.store');
        Route::get('/{notice}', [NoticeController::class, 'show'])->name('notices.show');

        // User can move their own notice to trash
        Route::post('/{notice}/trash', [NoticeController::class, 'trash'])->name('notices.trash');

        // User's personal trash bin
        Route::get('/trash', [NoticeController::class, 'trashBin'])->name('notices.trash');
    });

    // Admin-only notice management (protected by policy)
    Route::prefix('admin/notices')->group(function () {
        Route::get('/approval', [NoticeController::class, 'approval'])->name('notices.approval');
        Route::post('/{notice}/approve', [NoticeController::class, 'approve'])->name('notices.approve');
        Route::post('/{notice}/reject', [NoticeController::class, 'reject'])->name('notices.reject');
        Route::get('/{notice}/edit', [NoticeController::class, 'edit'])->name('notices.edit');
        Route::patch('/{notice}', [NoticeController::class, 'update'])->name('notices.update');
        Route::delete('/{notice}', [NoticeController::class, 'destroy'])->name('notices.destroy');
    });

    // Lost & Found
    Route::get('/lost-found', [LostFoundController::class, 'index'])->name('lostfound.index');
    Route::post('/lost-found', [LostFoundController::class, 'store'])->name('lostfound.store');
    Route::get('/lost-found/{item}', [LostFoundController::class, 'show'])->name('lostfound.show');
    Route::patch('/lost-found/{item}/resolve', [LostFoundController::class, 'resolve'])->name('lostfound.resolve');

    // Messages
    Route::get('/messages', [MessageController::class, 'inbox'])->name('messages.inbox');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [MessageController::class, 'send'])->name('messages.send');
    Route::get('/messages/{user}/poll', [MessageController::class, 'poll'])->name('messages.poll');
    Route::post('/messages/{user}/typing', [MessageController::class, 'typing'])->name('messages.typing');

    // Push Notifications
    Route::post('/push/subscribe', [PushController::class, 'subscribe'])->name('push.subscribe');
    Route::post('/push/unsubscribe', [PushController::class, 'unsubscribe'])->name('push.unsubscribe');

    // Notifications (all users)
    Route::get('/notifications', [NotificationController::class, 'all'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');

    // Admin User Management
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::patch('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
});

require __DIR__.'/auth.php';