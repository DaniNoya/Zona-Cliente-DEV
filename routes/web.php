<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Models\User;

Route::get('/', function () {
    if (Auth::check()) {
        return view('home');
    }
    return redirect()->route('login');
});

Route::get('admin/pedidos', function () {
    return view('pedidos');
})->name('pedidos');

Route::get('admin/users', function () {
    return view('users');
})->name('users');

Route::post('admin/users', [App\Http\Controllers\UserController::class, 'store'])->name('users.store');
Route::put('admin/users/{user}', [App\Http\Controllers\UserController::class, 'updateUser'])->name('users.update');
Route::delete('admin/users/{user}', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');

Route::get('admin/proveedores', function () {
    return view('providers');
})->name('proveedores');

Route::get('admin/productos', function () {
    return view('products');
})->name('productos');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\UserController::class, 'profile'])->name('profile');
    Route::post('/profile/update-picture', [App\Http\Controllers\UserController::class, 'updateProfilePicture'])->name('profile.update-picture');
    Route::put('/profile', [App\Http\Controllers\UserController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\UserController::class, 'updatePassword'])->name('profile.password');
});
