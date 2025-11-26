@extends('layouts.app')

@section('content')
<main class="row mb-6">
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
              <li class="breadcrumb-item active"><i class="ses-text-muted">Agregar nuevo alumno</i></li>
          </ol>
      </nav>
    </div>

    <div class="row">
      <div class="col-12">
        <a href="{{ route($pageModule.'.index', ['page' => request()->page]) }}" class="btn btn-sm btn-outline-secondary rounded-pill">
            <i class="fa fa-arrow-left me-1"></i> Regresar
        </a>
      </div>
    </div>
  
  
    <form action="{{ route($pageModule.'.store') }}" method="POST">
       @csrf
    <div class="row mt-2">

      <div class="col-8">
            <div class="sbox">
              <div class="sbox-title ses-text-muted">
                  <h5><i class="fa fa-table"></i> <strong> Datos Alumno</strong></h5>
              </div>
              <div class="sbox-content"> 

                  <div class="row">
                    <div class="col-8">
                      <div class="mb-3">
                        <label class="form-label fw-bold ses-text-muted">Nombre completo:</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-control" placeholder="Ingresa nombre completo" required>
                      </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                          <label class="form-label fw-bold ses-text-muted">CURP:</label>
                          <input type="text" name="curp" value="{{ old('curp') }}" class="form-control" placeholder="Ingresa CURP - 18 dígitos" required>
                        </div>
                    </div>
                  </div>
                   
                  <div class="row">
                    <div class="col-8">
                        <div class="mb-3">
                          <label class="form-label fw-bold ses-text-muted">Teléfono de emergencia:</label>
                          <input type="text" name="telefono_emergencia" value="{{ old('telefono_emergencia') }}" class="form-control" placeholder="Ingresa teléfono de emergencia" required>
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
                        <label class="form-label fw-bold ses-text-muted">Edad:</label>
                        <input type="number" name="edad" value="{{ old('edad') }}" class="form-control" placeholder="Ingresa edad" required>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="mb-3">
                        <label class="form-label fw-bold ses-text-muted">Género:</label>
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
                    <textarea name="domicilio" cols="3" rows="3" class="form-control" placeholder="Ingresa domicilio" required>{{ old('domicilio') }}</textarea>
                  </div>

                  <div class="mb-3">
                    <label class="form-label fw-bold ses-text-muted">Comentarios adicionales:</label>
                    <textarea name="comentarios" cols="3" rows="3" class="form-control" placeholder="Ingresa domicilio">{{ old('comentarios') }}</textarea>
                  </div>

              </div>
            </div>

          <div class="sbox">
            <div class="sbox-title ses-text-muted">
                <h5><i class="fa fa-table"></i> <strong> Datos titular responsable del alumno</strong></h5>
            </div>
            <div class="sbox-content"> 
                <div class="row">
                  <div class="col-6">
                    <div class="mb-3">
                      <label class="form-label fw-bold ses-text-muted">Nombre completo:</label>
                      <input type="text" name="titular_nombre" value="{{ old('titular_nombre') }}" class="form-control" placeholder="Ingresa nombre completo" required>
                    </div>
                  </div>
                  <div class="col-6">
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

                <div class="row">
                  <div class="col-6">
                      <div class="mb-3">
                        <label class="form-label fw-bold ses-text-muted">Teléfono:</label>
                        <input type="text" name="titular_telefono" value="{{ old('titular_telefono') }}" class="form-control" placeholder="Ingresa teléfono" required>
                      </div>
                  </div>
                  <div class="col-6">
                      <div class="mb-3">
                        <label class="form-label fw-bold ses-text-muted">Correo Electrónico:</label>
                        <input type="text" name="titular_email" value="{{ old('titular_email') }}" class="form-control" placeholder="Ingresa correo electrónico" required>
                      </div>
                  </div>
                </div>

            </div>
          </div>
      </div>
      <div class="col-4">

        <div class="sbox mb-3">
            <div class="sbox-title ses-text-muted">
                <h5><i class="fa fa-table"></i> <strong> Nivel con plna del alumno</strong></h5>
            </div>
            <div class="sbox-content"> 
                 
              <div class="mb-3">
                <label class="form-label fw-bold ses-text-danger">NOTA IMPORTANTE:</label>
                <p>Debido al proceso de migración al nuevo sistema, es indispensable seleccionar un plan, ya que éste determina el nivel del alumno con el precio y será tomado en los pagos en automático.</p>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold ses-text-muted">Nivel con plan del alumno:</label>
                <select name="idplan" class="form-control js-select2" required>
                  <option value="">--Selecciona el nivel del alumno--</option>
                  @foreach($rowsPlan as $v)
                    <option value="{{ $v->id }}" @selected(old('idplan') == $v->id)>{{ $v->nivel.' ('.$v->plan.') - Total de visitas al mes: '.$v->max_visitas_mes.' - Precio: $'.$v->precio }}</option>
                  @endforeach
                </select>
              </div>
            </div>
        </div>

        <div class="sbox mb-3">
            <div class="sbox-title ses-text-muted">
                <h5><i class="fa fa-table"></i> <strong> Descuento Alumno</strong></h5>
            </div>
            <div class="sbox-content"> 
                 
              <div class="mb-3">
                  <label class="form-label fw-bold ses-text-danger">NOTA IMPORTANTE:</label>
                  <p>
                      Si el alumno cuenta con algún descuento, selecciónalo en este apartado.  
                      Esta configuración es fundamental para que la <strong>pasarela de pago</strong> utilizada desde la aplicación móvil calcule correctamente el importe a pagar.
                      <br><br>
                      En el sistema web, dentro del módulo de pagos, será posible aplicar o ajustar un descuento diferente si así se requiere; sin embargo, es importante mantener esta información correctamente registrada desde el inicio para evitar inconsistencias en los cobros.
                  </p>
              </div>


              <div class="mb-3">
                <label class="form-label fw-bold ses-text-muted">Nivel con plan del alumno:</label>
                <select name="iddescuento" class="form-control js-select2" required>
                  <option value="1">--Selecciona descuento--</option>
                  @foreach($rowsDescuentos as $v)
                    <option value="{{ $v->id }}" @selected(old('iddescuento') == $v->id)>{{ $v->descripcion.' ('.$v->descuento.'%) ' }}</option>
                  @endforeach
                </select>
              </div>

              <div class="row mt-4 text-center">
                <div class="col-12">
                  <button type="submit" name="save" class="btn btn-sm btn-primary ses-text-white"><i class="fa fa-save"></i> Guardar</button>
                </div>
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
