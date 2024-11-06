<?php

use App\Http\Controllers\MidtransController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', 'HomeController@index')
    ->name('home');

Route::get('/detail/{slug}', 'DetailController@index')
    ->name('detail');

Route::post('/checkout/{id}', 'CheckoutController@process')
    ->name('checkout_process')
    ->middleware(['auth', 'verified']);

Route::get('/checkout/{id}', 'CheckoutController@index')
    ->name('checkout')
    ->middleware(['auth', 'verified']);

Route::post('/checkout/create/{detail_id}', 'CheckoutController@create')
    ->name('checkout-create')
    ->middleware(['auth', 'verified']);

Route::get('/checkout/remove/{detail_id}', 'CheckoutController@remove')
    ->name('checkout-remove')
    ->middleware(['auth', 'verified']);

Route::get('/checkout/confirm/{id}', 'CheckoutController@success')
    ->name('checkout-success')
    ->middleware(['auth', 'verified']);

Route::prefix('admin')
    ->namespace('Admin')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('/', 'DashboardController@index')
            ->name('dashboard');

        Route::resource('travel-package', 'TravelPackageController');
        Route::resource('gallery', 'GalleryController');
        Route::resource('transaction', 'TransactionController');
    });

Auth::routes(['verify' => true]);

// Midtrans
Route::post('/midtrans/notification', [MidtransController::class, 'notificationHandler'])->name('midtrans.notification');
Route::get('/midtrans/finish', [MidtransController::class, 'finishRedirect'])->name('midtrans.finish');
Route::get('/midtrans/unfinish', [MidtransController::class, 'unfinishRedirect'])->name('midtrans.unfinish');
Route::get('/midtrans/error', [MidtransController::class, 'errorRedirect'])->name('midtrans.error');
Route::get('/midtrans/mailtest', [MidtransController::class, 'testEmail'])->name('midtrans.mailtest');

Route::get('/test-email', function () {
    $details = [
        'title' => 'Test Email dari Laravel',
        'body' => 'Ini adalah email percobaan untuk mengecek fungsi email SMTP di Laravel.',
    ];

    Mail::raw($details['body'], function ($message) {
        $message->to('briliantfikri@gmail.com')
            ->subject('Percobaan SMTP Laravel');
    });

    return 'Email telah dikirim. Silakan periksa inbox atau Mailtrap Anda.';
});
