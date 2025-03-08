<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\PhotoController;


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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello', function () {
    return 'World';
});

// Menggunakan {name} sebagai parameter agar sesuai dengan fungsi
Route::get('/user/{name}', function ($name) {
    return 'Nama saya ' . $name;
});


// Route dengan dua parameter
Route::get('/posts/{post}/comments/{comment}', function ($postId, $commentId) {
    return 'Pos ke-' . $postId . " Komentar ke-: " . $commentId;
});

// Route dengan parameter opsional
Route::get('/user/{name?}', function ($name = 'John') {
    return 'Nama saya ' . $name;
});

Route::get('/greeting', function () { 
    return view('hello', ['name' => 'Faishal']); 
    }); 


    Route::resource('photos', PhotoController::class)->only([ 
        'index', 'show' 
        ]); 
    Route::resource('photos', PhotoController::class)->except([ 
        'create', 'store', 'update', 'destroy' 
        ]);

Route::get('/hello', [WelcomeController::class, 'hello']);
Route::get('/', [PageController::class, 'index']);
Route::get('/about', [PageController::class, 'about']);
Route::get('/articles/{id}', [PageController::class, 'articles']);
Route::resource('photos', PhotoController::class);
