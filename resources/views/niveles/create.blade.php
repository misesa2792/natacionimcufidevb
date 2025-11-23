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
  
    <form action="{{ route($pageModule.'.store') }}" method="POST">
    @csrf
    <div class="container mt-3">

        <div class="col-12">
            <div class="sbox">
                <div class="sbox-title ses-text-muted">
                    <h5><i class="fa fa-table"></i> <strong>Registrar nuevo plan o nivel</strong></h5>
                </div>

                <div class="sbox-content">
                    <div class="mb-3">
                        <label class="form-label fw-bold ses-text-muted">Nombre del plan/nivel:</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-control" placeholder="Escribe el nombre del plan" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold ses-text-muted">Precio:</label>
                        <input type="text" name="precio" value="{{ old('precio') }}" class="form-control" placeholder="Ejemplo: 250.00" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold ses-text-muted">Visitas máximas incluidas:</label>
                        <input type="number" name="max_visitas_mes" value="{{ old('max_visitas_mes') }}" class="form-control" placeholder="Número de visitas permitidas" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold ses-text-muted">
                            Duración del plan (días):
                        </label>
                        <small class="text-muted d-block mb-1">
                            Define la vigencia en días. Por defecto son 30.  
                            Por ejemplo: si el usuario tiene 3 visitas y el plan dura 30 días, podrá programarlas dentro de esos 30 días desde su fecha de inicio.
                        </small>
                        <input type="number" name="duracion_dias" value="30" class="form-control" placeholder="Duración en días" required>
                    </div>

                    <div class="mt-3 text-center">
                      <button type="button"
                          onclick="location.href='{{ route($pageModule.'.index') }}'" class="btn btn-sm btn-white">
                          <i class="fa fa-arrow-circle-left"></i> Cancelar
                        </button>
                      <button type="submit" name="save" class="btn btn-sm btn-primary ses-text-white"><i class="fa fa-save"></i> Guardar</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>

  </div>
       

</main>
@section('plugins.Select2', true)
<script>
    document.addEventListener('DOMContentLoaded', function () {
      if (window.jQuery && $.fn.select2) {
        $('.js-select2').select2();
      }
    });
</script>
@stop
