<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;

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

// trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/category/{categoryId}/posts', [PostController::class, 'showByCategory'])->name('post.showByCategory');
//search
Route::get('/search', [PostController::class, 'search'])->name('search');
// detail
Route::get('/detail/{postId}', [PostController::class, 'detail'])->name('post.detail');
// login
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');

// logout
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// register
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::get('/register/verify/{token}', [RegisterController::class, 'verify'])->name('confirm_email');

// group middleware auth
Route::group(['middleware' => 'auth'], function () {
  Route::get('/post', [PostController::class, 'index'])->name('post.index');
  Route::get('/post/create', [PostController::class, 'create'])->name('post.create');
  Route::post('/post', [PostController::class, 'store'])->name('post.store');
  Route::post('/post/upload', [PostController::class, 'upload'])->name('ckeditor.upload');
  // group middleware check post access
  Route::group(['middleware' => 'checkPostAccess'], function () {
    Route::get('/post/{post}/show', [PostController::class, 'show'])->name('post.show');
    Route::get('/post/{post}/edit', [PostController::class, 'edit'])->name('post.edit');
    Route::put('/post/{post}', [PostController::class, 'update'])->name('post.update');
    Route::delete('/post/{post}', [PostController::class, 'destroy'])->name('post.destroy');
  });
  Route::post('/comment/{commentId}',[CommentController::class, 'reply'])->name('comment.reply');
	Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
});
Route::post('/like/{postId}', [LikeController::class, 'addLike'])->middleware('auth')->name('like.add');
Route::get('/like/check/{postId}', [LikeController::class, 'checkLike'])->middleware('auth')->name('like.check');


// admin
//admin dashboard
Route::get('/admin', [AdminHomeController::class, 'index'])->middleware('auth')->middleware('admin')->name('dashboard');
//admin category
Route::get('/admin/category', [CategoryController::class, 'index'])->middleware('auth')->middleware('admin')->name('category.index');
Route::post('/admin/category', [CategoryController::class, 'store'])->middleware('auth')->middleware('admin')->name('category.store');
Route::get('/admin/category/{category}/edit', [CategoryController::class, 'edit'])->middleware('auth')->middleware('admin')->name('category.edit');
Route::put('/admin/category/{category}', [CategoryController::class, 'update'])->middleware('auth')->middleware('admin')->name('category.update');
Route::delete('/admin/category/{category}', [CategoryController::class, 'destroy'])->middleware('auth')->middleware('admin')->name('category.destroy');

