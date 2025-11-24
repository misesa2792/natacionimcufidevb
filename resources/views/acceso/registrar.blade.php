@extends('layouts.pago')

@section('content')

  <div class="row mb-3 mt-3">
      <div class="col-12 text-center">
          <a href="{{ route($pageModule .'.pagar') }}" >
              << Cancelar acción
          </a>
      </div>
  </div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
      flatpickr('.date', {
        dateFormat: 'Y-m-d',
        maxDate: 'today',
        locale: 'es'
      });
    });
</script>

<style>
.alert-soft-warning {
    background: #fff8e1;
    border-left: 4px solid #f4b400;
    padding: 14px 16px;
    border-radius: 8px;
    font-size: 15px;
    color: #7a5a00;
}
</style>

   <div class="alert-soft-warning mt-3">
    <strong class="d-block mb-1">Importante</strong>

    <p class="mb-1">
        Todos los campos del formulario son obligatorios para completar el registro del nadador.
    </p>

    <p class="mb-0">
        La <strong>CURP del nadador</strong> será clave para realizar pagos en línea, consultar suscripciones
        activas y revisar los horarios asignados.
    </p>

      <p class="mb-0">
        <strong>IMCUFIDE Valle de Bravo</strong> verificará la autenticidad de la información proporcionada.  
        En caso de detectar datos incorrectos o ficticios, el registro podrá invalidarse.  
    </p>
</div>


    <form action="{{ route($pageModule.'.store') }}" method="POST">
       @csrf
    <div class="row mt-3">

        <div class="col-12">
            <div class="sbox">
              <div class="sbox-title ses-text-muted">
                  <h5><i class="fa fa-table"></i> <strong> Agregar nuevo Nadador</strong></h5>
              </div>
              <div class="sbox-content"> 

                  <div class="mb-3">
                    <label class="form-label fw-bold ses-text-muted">Nombre del Nadador :</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-control" placeholder="Ingresa nombre completo" required>
                  </div>
                   
                  <div class="row">
                    <div class="col-12">
                      <div class="mb-3">
                        <label class="form-label fw-bold ses-text-muted">CURP:</label>
                        <input type="text" name="curp" value="{{ old('curp') }}" class="form-control" placeholder="Ingresa CURP - 18 dígitos" required>
                      </div>
                    </div>
                    
                    <div class="col-12">
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
                <h5><i class="fa fa-table"></i> <strong> Nivel Nadador</strong></h5>
            </div>
            <div class="sbox-content"> 
                 
              <div class="mb-3">
                <label class="form-label fw-bold ses-text-muted">Nivel del nadador:</label>
                <select name="idplan" class="form-control js-select2" required>
                  <option value="">--Selecciona el nivel del nadador--</option>
                  @foreach($rowsPlan as $v)
                    <option value="{{ $v->id }}" @selected(old('idplan') == $v->id)>{{ $v->plan.' ['.$v->descripcion.']'.' - Total de visitas: '.$v->max_visitas_mes.' - Precio: $'.$v->precio }}</option>
                  @endforeach
                </select>
              </div>

              
              
            </div>
          </div>

          <div class="mt-5 mb-5 text-center">
                <button type="submit" name="save" class="btn btn-primary btn-lg px-4 fw-semibold shadow-sm ses-text-white"><i class="bi bi-floppy"></i> Registrar Nadador</button>
          </div>

        </div>
    </div>
        </form>
@section('plugins.Select2', true)
<script>
    document.addEventListener('DOMContentLoaded', function () {
      if (window.jQuery && $.fn.select2) {
        $('.js-select2').select2();
      }
    });
</script>
@stop
