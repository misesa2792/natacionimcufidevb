<?php
    use App\Http\Controllers\UsuariosController;
    use App\Http\Controllers\NadadoresController;
    use App\Http\Controllers\SuscripcionesController;
    use App\Http\Controllers\NivelesController;

    Route::controller(UsuariosController::class)->prefix('usuarios')->name('usuarios.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
    });

    Route::controller(NadadoresController::class)->prefix('nadadores')->name('nadadores.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
    });

    Route::controller(SuscripcionesController::class)->prefix('suscripciones')->name('suscripciones.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create/{id}', 'create')->name('create');
        Route::get('/view/{id}', 'view')->name('view');
        Route::get('/horario/{id}/ids/{ids}', 'horario')->name('horario');
        Route::post('/store/{id}', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}/ids/{ids}', 'update')->name('update');
    });

    Route::controller(NivelesController::class)->prefix('niveles')->name('niveles.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/horarios/{id}', 'horarios')->name('horarios');
        Route::post('/update/{id}', 'update')->name('update');
    });

    
