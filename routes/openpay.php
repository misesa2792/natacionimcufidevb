<?php
    use App\Http\Controllers\OpenpayPaymentController;

    Route::get('/openpay/checkout', [OpenpayPaymentController::class, 'showCheckout'])->name('openpay.checkout');
    Route::post('/openpay/pay', [OpenpayPaymentController::class, 'charge'])->name('openpay.charge');