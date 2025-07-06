<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\TutorialController;
use App\Http\Controllers\NotificationController;

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

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

// Email Verification Routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    
    // Post Routes
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{id}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::get('/my-posts', [PostController::class, 'myPosts'])->name('posts.my-posts');
    
    // Comment Routes
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{id}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::get('/comments/{id}/replies', [CommentController::class, 'getReplies'])->name('comments.replies');
    
    // Like Routes
    Route::post('/likes/toggle', [LikeController::class, 'toggle'])->name('likes.toggle');
    Route::get('/likes', [LikeController::class, 'getLikes'])->name('likes.get');
    
    // Follow Routes
    Route::post('/follow/toggle', [FollowController::class, 'toggle'])->name('follow.toggle');
    Route::get('/follow/suggestions', [FollowController::class, 'suggestions'])->name('follow.suggestions');
    
    // Notification Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::get('/notifications/recent', [NotificationController::class, 'getRecent'])->name('notifications.recent');
    
    // Tutorial Routes
    Route::get('/tutorial', [TutorialController::class, 'index'])->name('tutorial.index');
    Route::post('/tutorial/complete', [TutorialController::class, 'complete'])->name('tutorial.complete');
    Route::post('/tutorial/skip', [TutorialController::class, 'skip'])->name('tutorial.skip');
});

// Public Profile Routes
Route::get('/profile/{username}', [HomeController::class, 'profile'])->name('profile');
Route::get('/profile/{username}/followers', [FollowController::class, 'followers'])->name('profile.followers');
Route::get('/profile/{username}/following', [FollowController::class, 'following'])->name('profile.following');

// Search Routes
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search/autocomplete', [SearchController::class, 'autocomplete'])->name('search.autocomplete');

// Forum Routes
Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
Route::get('/forum/create', [ForumController::class, 'create'])->name('forum.create')->middleware('auth');
Route::post('/forum', [ForumController::class, 'store'])->name('forum.store')->middleware('auth');
Route::get('/forum/{id}', [ForumController::class, 'show'])->name('forum.show');
Route::get('/forum/{id}/edit', [ForumController::class, 'edit'])->name('forum.edit')->middleware('auth');
Route::put('/forum/{id}', [ForumController::class, 'update'])->name('forum.update')->middleware('auth');
Route::delete('/forum/{id}', [ForumController::class, 'destroy'])->name('forum.destroy')->middleware('auth');

// Help Routes
Route::get('/help', [HelpController::class, 'index'])->name('help.index');
Route::get('/help/{slug}', [HelpController::class, 'show'])->name('help.show');

// Admin Routes (for help topics management)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/help', [HelpController::class, 'admin'])->name('admin.help');
    Route::get('/help/create', [HelpController::class, 'create'])->name('admin.help.create');
    Route::post('/help', [HelpController::class, 'store'])->name('admin.help.store');
    Route::get('/help/{id}/edit', [HelpController::class, 'edit'])->name('admin.help.edit');
    Route::put('/help/{id}', [HelpController::class, 'update'])->name('admin.help.update');
    Route::delete('/help/{id}', [HelpController::class, 'destroy'])->name('admin.help.destroy');
});
