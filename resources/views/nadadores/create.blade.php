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
                  <h5><i class="fa fa-table"></i> <strong> Agregar nuevo Nadador</strong></h5>
              </div>
              <div class="sbox-content"> 

                  <div class="mb-3">
                    <label class="form-label fw-bold ses-text-muted">Nombre del Nadador:</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-control" placeholder="Ingresa nombre completo" required>
                  </div>
                   
                  <div class="row">
                    <div class="col-8">
                      <div class="mb-3">
                        <label class="form-label fw-bold ses-text-muted">CURP:</label>
                        <input type="text" name="curp" value="{{ old('curp') }}" class="form-control" placeholder="Ingresa CURP - 18 dígitos" required>
                      </div>
                    </div>
                    
                    <div class="col-4">
                      <div class="mb-3">
                        <label class="form-label fw-bold ses-text-muted">Fecha de nacimiento:</label>
                        <input type="text" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" class="form-control date" placeholder="0000-00-00" required>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-6">
                      <div class="mb-3">
                        <label class="form-label fw-bold ses-text-muted">Edad del Nadador:</label>
                        <input type="number" name="edad" value="{{ old('edad') }}" class="form-control" placeholder="Ingresa edad" required>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="mb-3">
                        <label class="form-label fw-bold ses-text-muted">Género del Nadador:</label>
                        <select name="idgenero" class="form-control js-select2" required>
                          <option value="">--Select Please--</option>
                          @foreach($rowsGenero as $v)
                            <option value="{{ $v->id }}" @selected(old('idgenero') == $v->id)>{{ $v->genero }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <label class="form-label fw-bold ses-text-muted">Domicilio (calle, número y colonia o datos de referencia):</label>
                    <input type="text" name="domicilio" value="{{ old('domicilio') }}" class="form-control" placeholder="Ingresa domicilio" required>
                  </div>

              </div>
            </div>

          <div class="sbox">
            <div class="sbox-title ses-text-muted">
                <h5><i class="fa fa-table"></i> <strong> Datos titular responsable del nadador</strong></h5>
            </div>
            <div class="sbox-content"> 
                <div class="mb-3">
                  <label class="form-label fw-bold ses-text-muted">Nombre completo:</label>
                  <input type="text" name="titular_nombre" value="{{ old('titular_nombre') }}" class="form-control" placeholder="Ingresa nombre completo" required>
                </div>

                <div class="mb-3">
                  <label class="form-label fw-bold ses-text-muted">Teléfono:</label>
                  <input type="text" name="titular_telefono" value="{{ old('titular_telefono') }}" class="form-control" placeholder="Ingresa teléfono" required>
                </div>

                <div class="mb-3">
                  <label class="form-label fw-bold ses-text-muted">Correo Electrónico:</label>
                  <input type="text" name="titular_email" value="{{ old('titular_email') }}" class="form-control" placeholder="Ingresa correo electrónico" required>
                </div>

                <div class="mb-3">
                  <label class="form-label fw-bold ses-text-muted">Domicilio:</label>
                  <input type="text" name="titular_domicilio" value="{{ old('titular_domicilio') }}" class="form-control" placeholder="Ingresa domicilio" required>
                </div>

                <div class="mb-3">
                  <label class="form-label fw-bold ses-text-muted">Parentesco:</label>
                  <select name="idparentesco" class="form-control js-select2" required>
                    <option value="">--Selecciona el parentesco--</option>
                    @foreach($rowsParentesco as $v)
                      <option value="{{ $v->id }}" @selected(old('idparentesco') == $v->id)>{{ $v->parentesco }}</option>
                    @endforeach
                  </select>
                </div>

            </div>
          </div>

          <div class="sbox mb-3">
            <div class="sbox-title ses-text-muted">
                <h5><i class="fa fa-table"></i> <strong> Plan Nadador</strong></h5>
            </div>
            <div class="sbox-content"> 
                 
              <div class="mb-3">
                <label class="form-label fw-bold ses-text-muted">Plan del nadador:</label>
                <select name="idplan" class="form-control js-select2" required>
                  <option value="">--Selecciona el plan del nadador--</option>
                  @foreach($rowsPlan as $v)
                    <option value="{{ $v->id }}" @selected(old('idplan') == $v->id)>{{ $v->plan }}</option>
                  @endforeach
                </select>
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
