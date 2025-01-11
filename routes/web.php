<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\Homecontroller;
use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\Front\PaymentsController;
use App\Http\Controllers\Front\ProductsController;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\Front\StripeWebhooksController;
use App\Http\Controllers\Front\CurrencyConverterController;
use App\Http\Controllers\Front\OrdersController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

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

Route::group(['prefix' => LaravelLocalization::setLocale()], function () {

    Route::get('/', [Homecontroller::class, 'index'])->name('home');



    Route::name('front.')->group(function () {
        Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
        Route::get('/products/{product:slug}', [ProductsController::class, 'show'])->name('products.show');

        Route::resource('cart', CartController::class);

        Route::get('checkout', [CheckoutController::class, 'create'])->name('checkout.create');
        Route::post('checkout', [CheckoutController::class, 'store']);

        Route::post('/currency', [CurrencyConverterController::class, 'store'])->name('currency.store');
    });

    Route::get('auth/{provider}/redirect', [SocialLoginController::class, 'redirect'])->name('auth.socialite.redirect');
    Route::get('auth/{provider}/callback', [SocialLoginController::class, 'callback'])->name('auth.socialite.callback');

    // Route::get('orders/{order}/pay', [PaymentsController::class, 'create'])
    //     ->name('orders.payment.create');

    Route::post('orders/{order}/pay', [PaymentsController::class, 'store'])
        ->name('orders.payment.checkout');

    Route::get('orders/success', [PaymentsController::class, 'success'])
        ->name('orders.payment.success');

    Route::post('stripe/webhook', [StripeWebhooksController::class, 'webhook'])->name('stripe.webhook');

    Route::get('orders/{order}', [OrdersController::class, 'show'])->name('front.orders.show');



    // require __DIR__ . '/auth.php';
    require __DIR__ . '/dashboard.php';
});
