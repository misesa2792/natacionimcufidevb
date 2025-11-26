<?php
    use App\Http\Controllers\UsuariosController;
    use App\Http\Controllers\NadadoresController;
    use App\Http\Controllers\SuscripcionesController;
    use App\Http\Controllers\NivelesController;
    use App\Http\Controllers\NivelController;
    use App\Http\Controllers\TransaccionesController;
    use App\Http\Controllers\AsistenciasController;
    use App\Http\Controllers\PanelController;

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
        Route::get('/edit', 'edit')->name('edit');
        Route::post('/update', 'update')->name('update');
        Route::post('/upload', 'upload')->name('upload');
    });
    Route::controller(AsistenciasController::class)->prefix('asistencias')->name('asistencias.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/checkin', 'checkin')->name('checkin');
        Route::post('/store', 'store')->name('store');
    });

    Route::controller(SuscripcionesController::class)->prefix('suscripciones')->name('suscripciones.')->group(function () {
        Route::get('/', 'index')->name('index');
        //Route::get('/create/{id}', 'create')->name('create');
        Route::get('/view', 'view')->name('view');
        Route::get('/horario/{id}/ids/{ids}', 'horario')->name('horario');
        Route::get('/link', 'link')->name('link');
        Route::get('/pdf', 'pdf')->name('pdf');
        Route::post('/store', 'store')->name('store');
        Route::post('/update/{id}/ids/{ids}', 'update')->name('update');
        Route::post('/temporal', 'temporal')->name('temporal');
        Route::get('/pagar', 'pagar')->name('pagar');
        Route::post('/ticket', 'ticket')->name('ticket');
        Route::get('/ordenpago', 'ordenpago')->name('ordenpago');
        Route::get('/detail', 'detail')->name('detail');
        Route::post('/upload', 'upload')->name('upload');
        Route::get('/reportepagos', 'reportepagos')->name('reportepagos');
        Route::get('/dashboard', 'dashboard')->name('dashboard');
    });

    Route::controller(NivelesController::class)->prefix('niveles')->name('niveles.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::get('/edit', 'edit')->name('edit');
        Route::post('/store', 'store')->name('store');
        Route::post('/update', 'update')->name('update');
        Route::post('/guardar', 'guardar')->name('guardar');
    });

    Route::controller(NivelController::class)->prefix('nivel')->name('nivel.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit', 'edit')->name('edit');
        Route::post('/guardar', 'guardar')->name('guardar');
        Route::get('/horarios', 'horarios')->name('horarios');
        Route::get('/asignar', 'asignar')->name('asignar');
        Route::post('/update', 'update')->name('update');
    });

    Route::controller(TransaccionesController::class)->prefix('transacciones')->name('transacciones.')->group(function () {
        Route::get('/', 'index')->name('index');
    });

     Route::controller(PanelController::class)->prefix('panel')->name('panel.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/calendario', 'calendario')->name('calendario');
        Route::get('/info', 'info')->name('info');
        Route::post('/asistencia', 'asistencia')->name('asistencia');
    });

    
