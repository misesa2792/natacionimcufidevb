@extends('layouts.app')

@section('content')




<main class="row">
  <div class="col-12">
    <div class="page-header">
      <div class="page-title">
          <h4 class="text-blue-900"> <strong>{{ $pageTitle }}</strong> <small class="text-gray-400"><i>{{ $pageNote }}</i></small></h4>
      </div>
      
      <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ URL::to('dashboard') }}"> <i class="fa fa-home s-18"></i> </a></li>
              <li class="breadcrumb-item">
                  <a href="{{ route($pageModule.'.index') }}" class="text-decoration-none"><i>{{ $pageTitle }}</i></a>    
              </li>
              <li class="breadcrumb-item active"><i class="ses-text-muted">Agregar</i></li>
          </ol>
      </nav>
    </div>

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

                    <h3 class="fw-bold mb-2">Registro exitoso</h3>


                     <div class="row mt-6">
                        <div class="col-12 mt-4">
                            <p class="mb-1 text-muted">Alumno:</p>
                             <h5 class="fw-bold " style="word-break: break-all;">
                                {{ $row->nombre }}
                            </h5>
                        </div>
                    </div>

                    <div class="row mt-6">
                        <div class="col-12 mb-4 mt-4">
                            <p class="mb-1 text-muted">Folio asignado:</p>
                             <h3 class="fw-bold text-success" style="word-break: break-all;">
                                {{ $folio }}
                            </h3>
                        </div>
                    </div>

                    <a href="{{ route($pageModule.'.pdf', ['ids' => $ids]) }}" target="_blank" class="btn btn-outline-primary btn-md px-4">
                        <i class="bi bi-cash-coin"></i> Imprimir Orden de Pago
                    </a>

                    <div class="mt-6">
                        <a href="{{ route($pageModule.'.index') }}">
                            &laquo; Regresar a la página principal
                        </a>
                    </div>
                    
                </div>
            </div>
        </div>

    </div>
</div>


  </div>
</main>
@stop
