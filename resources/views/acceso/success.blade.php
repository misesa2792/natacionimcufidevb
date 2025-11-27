@extends('layouts.pago')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-center mb-5">
            <div class="card shadow-sm" style="max-width: 380px; width: 100%;">
                <div class="card-body text-center p-4">

                    {{-- Icono de éxito --}}
                    <div class="mb-3">
                        <div style="
                            width: 80px;
                            height: 80px;
                            border-radius: 50%;
                            background-color: #22c55e;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            margin: 0 auto;
                        ">
                            <span style="font-size: 40px; color: #fff;">✓</span>
                        </div>
                    </div>

                    <h3 class="fw-bold mb-2">Pago exitoso</h3>

                    {{-- ID de cargo --}}
                    <p class="mb-1 text-muted">ID de cargo:</p>
                    <p class="fw-bold text-primary mb-3" style="word-break: break-all;">
                        {{ $charge_id }}
                    </p>

                    {{-- Mensaje principal --}}
                    <p class="mb-3">
                        Tu pago se ha registrado correctamente.  
                    </p>

                    {{-- Link para regresar --}}
                    <a href="{{ route($pageModule .'.pagar') }}">
                        &laquo; Regresar a la página principal
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
