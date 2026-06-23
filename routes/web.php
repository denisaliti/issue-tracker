<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;

Route::resource('projects', ProjectController::class);
Route::resource('projects.issues', IssueController::class);
Route::resource('tags', TagController::class)->only(['index', 'store']);

Route::post('issues/{issue}/tags/{tag}/attach', [IssueController::class, 'attachTag'])->name('issues.tags.attach');
Route::delete('issues/{issue}/tags/{tag}/detach', [IssueController::class, 'detachTag'])->name('issues.tags.detach');

Route::post('issues/{issue}/comments', [CommentController::class, 'store'])->name('issues.comments.store');
Route::get('issues/{issue}/comments', [CommentController::class, 'index'])->name('issues.comments.index');
Route::delete('issues/{issue}/comments/{comment}', [CommentController::class, 'destroy'])->name('issues.comments.destroy');
Route::put('issues/{issue}/comments/{comment}', [CommentController::class, 'update'])->name('issues.comments.update');

Route::post('issues/{issue}/users/{user}/attach', [IssueController::class, 'attachUser'])->name('issues.users.attach');
Route::delete('issues/{issue}/users/{user}/detach', [IssueController::class, 'detachUser'])->name('issues.users.detach');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/', function () {
    return redirect()->route('projects.index');
});

require __DIR__.'/auth.php';