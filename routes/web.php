<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\BagController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\ReferralCodeController;
use App\Http\Controllers\UserProfileController;
use App\Models\Product;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $products = Product::select('id', 'name', 'description', 'price', 'image_url')->get();

    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'products' => $products,
    ]);
})->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/qrcode', [QRCodeController::class, 'index'])->name('qrcode');

    Route::get('/user-profile', [UserProfileController::class, 'index'])->name('profile.index');

    Route::get('/address', [AddressController::class, 'index'])->name('address.index');
    Route::post('/address', [AddressController::class, 'store'])->name('address.save');

    Route::post('/bag-sync', [BagController::class, 'sync'])->name('bag.sync');
    Route::post('/bag-add', [BagController::class, 'add'])->name('bag.add');
    Route::delete('/bag-remove', [BagController::class, 'remove'])->name('bag.remove');
    Route::patch('/bag-increment', [BagController::class, 'increment'])->name('bag.increment');
    Route::patch('/bag-decrement', [BagController::class, 'decrement'])->name('bag.decrement');

    Route::get('/referral-code', [ReferralCodeController::class, 'index'])->name('referral-code.index');
    Route::post('/referral-code', [ReferralCodeController::class, 'checkReferralCode'])->name('referral-code.check');

    // Only referred user can access this routes
    Route::middleware(['mustReferred'])->group(function () {
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

    });

    // Error pages
    Route::get('/error-403', function () {
        return Inertia::render('Errors/403');
    })->name('error.403');

});

Route::get('/bag', [BagController::class, 'index'])->name('bag');

require __DIR__ . '/auth.php';
