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
        <div class="mt-3">
          <button type="button"
              onclick="location.href='{{ route($pageModule.'.view', $id) }}'" class="btn btn-sm btn-white">
              <i class="fa fa-arrow-circle-left"></i> Regresar
            </button>
        </div>
      </div>
    </div>


    <div class="row mt-4">
        <div class="col-12">
            <div class="plan-header bg-white border rounded-3 shadow-sm px-5 py-4 position-relative">

                {{-- Badge WhatsApp --}}
                <span class="position-absolute top-0 start-0 translate-middle-y ms-3 badge bg-success text-uppercase"
                    style="font-size: 11px;">
                    <i class="bi bi-whatsapp"></i> WhatsApp
                </span>

                {{-- Icono principal --}}
                <div class="text-center mb-3">
                    <div class="link-card-icon">
                        <i class="fa fa-link"></i>
                    </div>
                </div>

                <h2 class="fw-bold text-center mb-2 text-primary">
                    Enlace para asignar horario
                </h2>

                <p class="text-muted text-center mb-4 ses-fs-base">
                    Copia y comparte este enlace por <strong>WhatsApp</strong> para que el usuario pueda
                    ingresar y elegir su horario directamente desde su celular.
                </p>

                <div class="input-group mb-2">
                    <input type="text" class="form-control form-control-lg" id="linkHorario"
                        value="{{ $link_horario }}" readonly>
                    <button class="btn btn-outline-primary" type="button" onclick="copiarLink()">
                        <i class="bi bi-clipboard-check"></i> Copiar
                    </button>
                </div>

                <div class="text-end">
                    <a href="{{ $link_horario }}" target="_blank" class="small text-muted text-decoration-none">
                        <i class="fa fa-external-link-alt me-1"></i> Abrir enlace en nueva pestaña
                    </a>
                </div>

            </div>
        </div>
    </div>

  </div>
</main>

<style>
    .link-card-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: #e8f3ff;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        font-size: 30px;
        color: #0d6efd;
    }
</style>

<script>
    function copiarLink() {
        const input = document.getElementById('linkHorario');
        input.select();
        input.setSelectionRange(0, 99999); // iOS fix

        document.execCommand('copy'); // MÉTODO COMPATIBLE

        Swal.fire({
            icon: 'success',
            title: 'Link copiado',
            text: 'Ahora puedes pegarlo en WhatsApp.',
            timer: 1800,
            showConfirmButton: false,
            position: 'top-end'
        });
    }
</script>

@stop
