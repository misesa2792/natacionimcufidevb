<?php
    use App\Http\Controllers\OpenpayPaymentController;
    use App\Http\Controllers\AccesoController;

    Route::get('/openpay/checkout', [OpenpayPaymentController::class, 'showCheckout'])->name('openpay.checkout');
    Route::post('/openpay/pay', [OpenpayPaymentController::class, 'charge'])->name('openpay.charge');


    Route::controller(AccesoController::class)->prefix('acceso')->name('acceso.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/pagar', 'pagar')->name('pagar');
        Route::post('/search', 'search')->name('search');
        Route::get('/openpay/{id}', 'openpay')->name('openpay');


        Route::get('/horario', 'horario')->name('horario');
        Route::post('/update', 'update')->name('update');
        Route::get('/success/{id}', 'success')->name('success');

        Route::get('/registrar', 'registrar')->name('registrar');
        Route::post('/store', 'store')->name('store');
    });