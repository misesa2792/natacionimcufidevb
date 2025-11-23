@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center mt-5">
    <div class="card shadow-sm p-4 text-center" style="max-width: 450px;">

        {{-- Ícono de éxito --}}
        <div class="mb-3">
            <div style="
                width: 110px;
                height: 110px;
                margin: 0 auto;
                border-radius: 50%;
                background-color: #28a745;
                display: flex;
                align-items: center;
                justify-content: center;">
                <i class="fa fa-check" style="font-size: 55px; color: white;"></i>
            </div>
        </div>

        {{-- Título --}}
        <h2 class="fw-bold mt-3">Pago exitoso</h2>

        {{-- ID del cargo --}}
        <p class="mt-2">
            ID de cargo: <strong>{{ $charge_id }}</strong>
        </p>

        {{-- Botón de regresar --}}
        <a href="{{ route('suscripciones.index') }}" class="btn btn-primary btn-lg mt-3 w-100">
            Regresar
        </a>
    </div>
</div>

<div class="row m-t">
    <div class="col-12 text-center">
        <a href="{{ route('suscripciones.index') }}" >
            << Volver al área de pagos
        </a>
    </div>
</div>

@endsection
