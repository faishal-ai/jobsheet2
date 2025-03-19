<?php

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [WelcomeController::class, 'index']);
Route::get('/level', [LevelController::class, 'index']);
Route::get('/kategori', [KategoriController::class, 'index']);
Route::get('/user', [UserController::class, 'index']);
Route::get('/user/tambah', [UserController::class, 'tambah']);
Route::post('/user/tambah_simpan', [UserController::class, 'tambah_simpan']);
Route::get('/user/ubah/{id}', [UserController::class, 'ubah']);
Route::put('/user/ubah_simpan/{id}', [UserController::class, 'ubah_simpan']);
Route::get('/user/hapus/{id}', [UserController::class, 'hapus']);




// use App\Http\Controllers\KategoriController;
// use App\Http\Controllers\LevelController;
// use Illuminate\Support\Facades\Route;
// // use App\Http\Controllers\LevelController;
// // use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/level', [LevelController::class, 'index']);
// Route::get('/kategori', [KategoriController::class, 'index']);

// Route::get('/hello', function () {
//     return 'World';
// });

// // Menggunakan {name} sebagai parameter agar sesuai dengan fungsi
// Route::get('/user/{name}', function ($name) {
//     return 'Nama saya ' . $name;
// });


// // Route dengan dua parameter
// Route::get('/posts/{post}/comments/{comment}', function ($postId, $commentId) {
//     return 'Pos ke-' . $postId . " Komentar ke-: " . $commentId;
// });

// // Route dengan parameter opsional
// Route::get('/user/{name?}', function ($name = 'John') {
//     return 'Nama saya ' . $name;
// });

// //  
 

//     Route::resource('photos', PhotoController::class)->only([ 
//         'index', 'show' 
//         ]); 
//     Route::resource('photos', PhotoController::class)->except([ 
//         'create', 'store', 'update', 'destroy' 
//         ]);

// Route::get('/hello', [WelcomeController::class, 'hello']);
// Route::get('/', [PageController::class, 'index']);
// Route::get('/about', [PageController::class, 'about']);
// Route::get('/articles/{id}', [PageController::class, 'articles']);
// Route::resource('photos', PhotoController::class);
// Route::get('/greeting', [WelcomeController::class, 'greeting']);
