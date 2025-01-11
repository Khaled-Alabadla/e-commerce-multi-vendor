<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\ProductsController;
use App\Http\Controllers\Dashboard\ProfilesController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\CategoriesController;
use App\Http\Controllers\Dashboard\RolesController;

Route::middleware(['auth:admins'])->as('dashboard.')->prefix('admin/dashboard')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])
        ->middleware(['auth', 'verified'])->name('index');

    Route::get('/profile', [ProfilesController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfilesController::class, 'update'])->name('profile.update');

    Route::get('categories/trash', [CategoriesController::class, 'trash'])->name('categories.trash');
    Route::put('categories/{category}/restore', [CategoriesController::class, 'restore'])->name('categories.restore');
    Route::delete('categories/{category}/force-delete', [CategoriesController::class, 'force_delete'])->name('categories.force_delete');

    Route::get('products/trash', [ProductsController::class, 'trash'])->name('products.trash');
    Route::put('products/{product}/restore', [ProductsController::class, 'restore'])->name('products.restore');
    Route::delete('products/{product}/force-delete', [ProductsController::class, 'force_delete'])->name('products.force_delete');

    Route::resource('categories', CategoriesController::class);
    Route::resource('products', ProductsController::class);
    Route::resource('roles', RolesController::class);
});
