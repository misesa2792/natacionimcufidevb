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
        <a href="{{ route($pageModule.'.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
            <i class="fa fa-arrow-left me-1"></i> Regresar
        </a>
      </div>
    </div>
  
  
    <div class="row mt-2">
      <div class="row">
        <div class="col-6">
            <div class="sbox">
              <div class="sbox-title ses-text-muted">
                  <h5><i class="fa fa-table"></i> <strong> Alumno</strong></h5>
              </div>
              <div class="sbox-content"> 

                  <div class="mb-3">
                    <div class="row">
                      <div class="col-3 text-right ses-text-muted">Nombre completo:</div>
                      <div class="col-9 ses-text-blue">{{ $row->nombre }}</div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <div class="row">
                      <div class="col-3 text-right ses-text-muted">Plan:</div>
                      <div class="col-3"><strong>{{ $row->nivel }}</strong> <i>({{ $row->plan }})</i></div>
                      <div class="col-3 text-right ses-text-muted">Precio:</div>
                      <div class="col-3"><strong>${{ $row->precio }}</strong></div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <div class="row">
                      <div class="col-3 text-right ses-text-muted">Mes a pagar:</div>
                      <div class="col-3">{{ $mes }}</div>
                      <div class="col-3 text-right ses-text-muted">Máximo de visitas al mes:</div>
                      <div class="col-3">{{ $row->max_visitas_mes }}</div>
                    </div>
                  </div>

              </div>
            </div>


            <div class="sbox">
              <div class="sbox-title ses-text-muted">
                  <h5><i class="fa fa-table"></i> <strong> Pagar</strong></h5>
              </div>
              <div class="sbox-content"> 

                <form action="{{ route($pageModule.'.ticket',['id' => $id, 'idm' => $idm, 'idy' => $idy, 'time' => $time]) }}" method="POST">
                @csrf
                  <div class="mb-3">
                    <div class="row">
                      <div class="col-3 text-right ses-text-muted">Tipo de pago:</div>
                      <div class="col-9">
                        <select name="idtipo_pago" class="form-control">
                          <option value="2" selected>Ventanilla</option>
                          <option value="1">Transferencia</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <div class="row">
                      <div class="col-3 text-right ses-text-muted">Descuento:</div>
                      <div class="col-9">
                        <select name="iddescuento" class="form-control js-descuento">
                          @foreach ($rowsDescuento as $v)
                          <option value="{{ $v->id }}" data-descuento="{{ $v->descuento }}">{{ $v->descripcion }} ({{ $v->descuento }}%)</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <div class="row">
                      <div class="col-3 text-right ses-text-muted">Descuento aplicado:</div>
                      <div class="col-9">
                        <input type="text" name="descuento" placeholder="Porcentaje" class="form-control js-descuento-aplicado" disabled>
                      </div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <div class="row">
                      <div class="col-3 text-right ses-text-muted">Total a pagar:</div>
                      <div class="col-9">
                        <input type="text" name="total_pagar" placeholder="Total" class="form-control js-total-pagar" disabled>
                      </div>
                    </div>
                  </div>

                  

                <div class="mt-3 text-center">
                  <button type="submit" name="save" class="btn btn-sm btn-primary rounded-pill"><i class="bi bi-cash-coin"></i> Guardar Pago </button>
                </div>
            </form>

              </div>
            </div>

       

        </div>
        <div class="col-6">

            <div class="sbox">
              <div class="sbox-title ses-text-muted">
                  <h5><i class="fa fa-table"></i> <strong> Fechas seleccionadas</strong></h5>
              </div>
              <div class="sbox-content"> 

                   <table class="table table-bordered">
                      <tr>
                        <th width="40">#</th>
                        <th width="70">Estatus</th>
                        <th>Fecha</th>
                      </tr>
                      @foreach($rowsFechas as $key => $v)
                        <tr>
                          <td class="text-center">{{ ++$key }}</td>
                          <td><span class="badge badge-success"><i class="bi bi-calendar-check"></i> Reservado</span></td>
                          <td>{{ \Carbon\Carbon::parse($v->fecha)->translatedFormat('j \d\e F \d\e Y') }}</td>
                        </tr>
                      @endforeach
                    </table>

                  
              </div>
            </div>

          
          </div>

        <div class="col-12">
          


        </div>

      </div>

    </div>

  </div>
       

</main>

@push('js')
<script>
  $(function () {
      // Precio base desde el servidor como número
      const precioBase = {{ (float) $row->precio }}; // ej. 450

      const $selectDescuento    = $('select[name="iddescuento"]');
      const $inputDescuentoView = $('.js-descuento-aplicado');
      const $inputTotalView     = $('.js-total-pagar');

      function actualizarTotales() {
          // SOLO para debug, si quieres:
          // console.log('select actual:', $selectDescuento.val());

          const optionSeleccionada = $selectDescuento.find('option:selected');
          const porcentaje = parseFloat(optionSeleccionada.data('descuento')) || 0;

          const montoDescuento = precioBase * (porcentaje / 100);
          const totalPagar     = precioBase - montoDescuento;

          $inputDescuentoView.val(montoDescuento.toFixed(2));

          $inputTotalView.val(totalPagar.toFixed(2));
      }

      // Evento cambio
      $selectDescuento.on('change', actualizarTotales);

      // Calcular de entrada con el primer descuento
      actualizarTotales();
  });
</script>
@endpush

@stop
