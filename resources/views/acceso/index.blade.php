@extends('layouts.pago')

@section('content')

<style>
    .form-label {
        font-weight: 600;
        color: #555;
    }

    .custom-input {
        width: 100%;
        padding: 12px 14px;
        border: 1.5px solid #d3d3d3;
        border-radius: 8px;
        margin-bottom: 15px;
        font-size: 15px;
        background: #fff;
        transition: all 0.2s ease-in-out;
    }

    .custom-input:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 4px rgba(13,110,253,0.35);
        outline: none;
    }

    .sbox {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 18px;
        background: #ffffff;
    }

    .sbox-title h5 {
        font-size: 17px;
        margin-bottom: 0;
    }

    .btn-search {
        padding: 10px 26px;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
    }

    .btn-register {
        padding: 8px 22px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
    }
</style>

<div class="container">
    
    <div class="row mt-3">
        <div class="col-12">
            @if(session('success'))
                <div class="success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="error">
                    @foreach($errors->all() as $error)
                        • {{ $error }}<br>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="mt-4">
            <div class="sbox shadow-sm">
                <div class="ses-text-muted mb-3">
                    <h5><i class="bi bi-person-vcard me-1"></i> <strong>Buscar Nadador</strong></h5>
                    <hr>
                </div>

                <form action="{{ route('acceso.search') }}" method="POST">
                    @csrf

                    <label class="form-label">CURP del Nadador</label>
                    <input type="text" name="curp" placeholder="CURP del Nadador" class="custom-input" required>

                    <div class="mt-3 text-center">
                        <button type="submit" class="btn btn-outline-primary btn-search">
                            <i class="bi bi-search"></i> Buscar nadador
                        </button>
                    </div>
                </form>

                {{-- Bloque para registro de nadador --}}
                <div class="mt-3 text-center">
                    <small class="text-muted d-block mb-2">
                        ¿El nadador aún no está registrado?
                    </small>
                    <a href="{{ route('acceso.registrar') }}" class="btn btn-outline-secondary btn-register">
                        <i class="bi bi-person-plus"></i> Registrar nadador
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@stop
