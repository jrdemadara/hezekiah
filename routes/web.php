<?php

use App\Http\Controllers\AddMemberController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\BagController;
use App\Http\Controllers\CashoutController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EWalletController;
use App\Http\Controllers\NetworkController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\ReferralCodeController;
use App\Http\Controllers\UserProfileController;
use App\Models\Product;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $products = Product::select('id', 'name', 'description', 'price', 'image_url')
        ->where('id', '!=', 4)
        ->get();

    return Inertia::render('Welcome', [
        'canLogin'       => Route::has('login'),
        'canRegister'    => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION,
        'products'       => $products,
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

    Route::get('/network', [NetworkController::class, 'index'])->name('network');

    Route::get('/order', [OrderController::class, 'index'])->name('order.index');
    Route::patch('/order', [OrderController::class, 'store'])->name('order.store');

    Route::get('/user-profile', [UserProfileController::class, 'index'])->name('profile.index');

    Route::get('/address', [AddressController::class, 'index'])->name('address.index');
    Route::post('/address', [AddressController::class, 'store'])->name('address.save');
    Route::patch('/address', [AddressController::class, 'changeDefault'])->name('address.change');

    Route::post('/bag-sync', [BagController::class, 'sync'])->name('bag.sync');
    Route::post('/bag-add', [BagController::class, 'add'])->name('bag.add');
    Route::delete('/bag-remove', [BagController::class, 'remove'])->name('bag.remove');
    Route::patch('/bag-increment', [BagController::class, 'increment'])->name('bag.increment');
    Route::patch('/bag-decrement', [BagController::class, 'decrement'])->name('bag.decrement');

    Route::get('/referral-code', [ReferralCodeController::class, 'index'])->name('referral-code.index');
    Route::post('/referral-code', [ReferralCodeController::class, 'checkReferralCode'])->name('referral-code.check');

    Route::get('/add-member', [AddMemberController::class, 'index'])->name('add-member.index');
    Route::post('/add-member', [AddMemberController::class, 'store'])->name('add-member.store');

    Route::get('/cashout', [CashoutController::class, 'index'])->name('cashout.index');
    Route::post('/cashout', [CashoutController::class, 'save'])->name('cashout.save');
    Route::get('/cashout-history', [CashoutController::class, 'history'])->name('cashout.history');

    Route::get('/e-wallet', [EWalletController::class, 'index'])->name('e-wallet.index');
    Route::post('/e-wallet', [EWalletController::class, 'store'])->name('e-wallet.store');

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
