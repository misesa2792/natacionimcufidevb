@extends('layouts.pago')

@section('content')



<main class="row">
  <div class="col-12">

     <div class="row mt-3">
        <div class="col-12 text-center">
            <a href="{{ route($pageModule .'.pagar') }}" >
                << Regresar a la principal
            </a>
        </div>
    </div>
  
    <div class="row mt-3">
      <div class="row">
        <div class="col-12">

            <div class="sbox">
              <div class="sbox-title ses-text-muted">
                  <h5><i class="fa fa-table"></i> <strong> Datos Nadador</strong></h5>
              </div>
              <div class="sbox-content"> 

                  <div class="mb-3">
                    <div class="row">
                      <div class="col-3 text-right ses-text-muted">Nadador:</div>
                      <div class="col-9 ses-text-blue">{{ $row->nombre }}</div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <div class="row">
                      <div class="col-3 text-right ses-text-muted">CURP:</div>
                      <div class="col-9 ses-text-blue">{{ $row->curp }}</div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <div class="row">
                      <div class="col-3 text-right ses-text-muted">Nivel:</div>
                      <div class="col-9 ses-text-blue">{{ $row->plan }}</div>
                    </div>
                  </div>

              </div>
            </div>

          </div>

        <div class="col-12">
          
          <div class="sbox">
            <div class="sbox-title ses-text-muted">
                <h5><i class="fa fa-table"></i> <strong> Suscripción </strong></h5>
            </div>
            <div class="sbox-content"> 
                @if($rowsSuscripciones->isNotEmpty())

                    @foreach($rowsSuscripciones as $v)

                      <div class="mb-3">
                        <div class="row">
                          <div class="col-4 text-right ses-text-muted">Estatus:</div>
                          <div class="col-8 ses-text-blue">{{ $v['estado'] }}</div>
                        </div>
                      </div>  

                      <div class="mb-3">
                        <div class="row">
                          <div class="col-4 text-right ses-text-muted">Plan contratado:</div>
                          <div class="col-8 ses-text-blue">{{ $v['plan'] }}</div>
                        </div>
                      </div>

                      <div class="mb-3">
                        <div class="row">
                          <div class="col-4 text-right ses-text-muted">Tipo de pago:</div>
                          <div class="col-8 ses-text-blue">{{ $v['pago'] }}</div>
                        </div>
                      </div>

                      <div class="mb-3">
                        <div class="row">
                          <div class="col-4 text-right ses-text-muted">Fecha Inicio:</div>
                          <div class="col-8 ses-text-blue">{{ $v['fi'] }}</div>
                        </div>
                      </div>

                      <div class="mb-3">
                        <div class="row">
                          <div class="col-4 text-right ses-text-muted">Fecha Fin:</div>
                          <div class="col-8 ses-text-blue">{{ $v['ff'] }}</div>
                        </div>
                      </div>

                      <div class="mb-3">
                        <div class="row">
                          <div class="col-4 text-right ses-text-muted">Total de visitas permitidas por plan:</div>
                          <div class="col-8 ses-text-blue">{{ $v['max_visitas'] }}</div>
                        </div>
                      </div>

                      <div class="mb-3">
                       

                        <div class="row mt-3">
                          <div class="col-12 ses-text-blue">

                            @if($v['rows_fechas']->isNotEmpty())

                              <div class="row">
                                <div class="col-12 ses-text-muted">Horario Asignado:</div>
                              </div>
                              
                              <table class="table table-bordered">
                                  <tr>
                                    <th class="text-center">Estatus</th>
                                    <th class="text-center">Fecha reservada</th>
                                    <th class="text-center">Horario</th>
                                  </tr>
                                  @foreach($v['rows_fechas'] as $e)
                                    <tr>
                                      <td class="text-center">
                                        @if($e->active == 1)
                                          <span class="badge badge-primary">Reservado</span>
                                        @elseif($e->active == 2)
                                          <span class="badge badge-success">Utilizado</span>
                                        @elseif($e->active == 3)
                                          <span class="badge badge-dark">No Asistio</span>
                                        @endif
                                      </td>
                                      <td class="text-center">{{ $e->fecha_formateada }}</td>
                                      <td class="text-center">{{ $e->time_start .'-'.$e->time_end }}</td>
                                    </tr>
                                  @endforeach
                                </table>
                              @else 

                                @if($v['estado'] == 'ACTIVA')
                                  <div class="mt-2">
                                      <div class="border rounded-3 p-3 bg-warning bg-opacity-25 d-flex align-items-start">
                                          <div>
                                              <p class="mb-1 fw-semibold">
                                                  Aún no has asignado el horario de tus visitas.
                                              </p>
                                              <p class="mb-2 small text-muted">
                                                  Para completar tu suscripción, solicita en 
                                                  <strong>IMCUFIDE Valle de Bravo</strong> el 
                                                  <strong>link de asignación de horario</strong> y sigue las instrucciones
                                                  que te proporcionen por WhatsApp.
                                              </p>

                                              <p class="mb-0 small">
                                                  Si ya te compartieron el link, ábrelo desde ese medio para elegir
                                                  tus horarios disponibles.
                                              </p>
                                          </div>
                                      </div>
                                  </div>
                                @endif

                              @endif

                          </div>
                        </div>
                      </div>
                    @endforeach
                 </table>
                @else 
                   <div class="p-4 text-center">
                      <i class="fa fa-folder-open fa-3x text-muted"></i>

                      <h5 class="mt-3 text-muted">
                          El nadador no cuenta con ninguna suscripción
                      </h5>

                      <a href="{{ route($pageModule . '.openpay', $row->curp) }}" class="btn btn-outline-primary btn-md mt-4">
                          <i class="fa fa-plus"></i> Crear suscripción
                      </a>
                  </div>
                @endif

            </div>
          </div>


          @if($tieneActiva)
            <div class="card border-success mt-4">
                <div class="card-body text-center">
                    <h5 class="text-success mb-0">
                        <i class="fa fa-check-circle"></i> Suscripción Activa
                    </h5>
                    <small class="text-muted">El nadador tiene una suscripción vigente</small>
                </div>
            </div>
          @else
              @if($rowsSuscripciones->isNotEmpty())
                <div class="card border-danger shadow-sm mt-4">
                    <div class="card-body text-center">

                        <div class="mb-3">
                            <span class="text-danger" style="font-size:20px;">
                                <i class="fa fa-times-circle"></i>
                                El nadador tiene la suscripción vencida
                            </span>
                        </div>

                        <a href="{{ route($pageModule . '.openpay', $row->curp) }}"
                          class="btn btn-outline-primary btn-md px-4 py-2"
                          style="border-width:2px;">
                            <i class="fa fa-plus"></i> Crear suscripción
                        </a>

                    </div>
                </div>
              @endif
          @endif

        </div>

      </div>

    </div>

  </div>
       

</main>

<style>
  .badge {
    display: inline-block;
    padding: 2px 5px;
    font-size: 10px;
    font-weight: 600;
    border-radius: 12px;
    color: #fff;
    letter-spacing: .3px;
}

.badge-primary {
    background-color: #0069d9;
}

.badge-success {
    background-color: #28a745;
}

.badge-dark {
    background-color: #343a40;
}

/* Opcional: efectos */
.badge-primary,
.badge-success,
.badge-dark {
    box-shadow: 0 1px 3px rgba(0,0,0,0.15);
}

</style>

@stop
