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
              onclick="location.href='{{ route($pageModule.'.index') }}'" class="btn btn-sm btn-white">
              <i class="fa fa-arrow-circle-left"></i> Regresar
            </button>
        </div>
      </div>
    </div>
  
    <div class="row mt-3">
      <div class="row">
        <div class="col-6">

            <div class="sbox">
              <div class="sbox-title ses-text-muted">
                  <h5><i class="fa fa-table"></i> <strong> Plan del Nadador</strong></h5>
              </div>
              <div class="sbox-content"> 

                <h4 class="text-center text-primary">{{ $row->plan }}</h4>
                  
              </div>
            </div>

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
                      <div class="col-3">{{ $row->curp }}</div>
                      <div class="col-3 text-right ses-text-muted">Fecha de nacimiento:</div>
                      <div class="col-3">{{ $row->fecha_nacimiento }}</div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <div class="row">
                      <div class="col-3 text-right ses-text-muted">Genero:</div>
                      <div class="col-3">{{ $row->genero }}</div>
                      <div class="col-3 text-right ses-text-muted">Edad:</div>
                      <div class="col-3">{{ $row->edad }}</div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <div class="row">
                      <div class="col-3 text-right ses-text-muted">Domicilio:</div>
                      <div class="col-9">{{ $row->domicilio }}</div>
                    </div>
                  </div>

              </div>
            </div>

            <div class="sbox">
              <div class="sbox-title ses-text-muted">
                  <h5><i class="fa fa-table"></i> <strong> Datos Titular a cargo del nadador</strong></h5>
              </div>
              <div class="sbox-content"> 

                  <div class="mb-3">
                    <div class="row">
                      <div class="col-3 text-right ses-text-muted">Nombre:</div>
                      <div class="col-3">{{ $row->titular_nombre }}</div>
                      <div class="col-3 text-right ses-text-muted">Parentesco con el titular:</div>
                      <div class="col-3">{{ $row->parentesco }}</div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <div class="row">
                      <div class="col-3 text-right ses-text-muted">Teléfono:</div>
                      <div class="col-3">{{ $row->titular_telefono }}</div>
                      <div class="col-3 text-right ses-text-muted">Correo Electrónico:</div>
                      <div class="col-3">{{ $row->titular_email }}</div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <div class="row">
                      <div class="col-3 text-right ses-text-muted">Domicilio:</div>
                      <div class="col-9">{{ $row->titular_domicilio }}</div>
                    </div>
                  </div>
              </div>
            </div>

          
          </div>

        <div class="col-6">
          
          <div class="sbox">
            <div class="sbox-title ses-text-muted">
                <h5><i class="fa fa-table"></i> <strong> Suscripciones </strong></h5>
            </div>
            <div class="sbox-content"> 
                @if($rowsSuscripciones->isNotEmpty())
                 <table class="table">
                    <tr>
                      <th class="text-center" width="60">Estatus</th>
                      <th>Plan contratado</th>
                      <th class="text-center">Tipo de pago</th>
                      <th class="text-center">Fecha Inicio</th>
                      <th class="text-center">Fecha Fin</th>
                      <th class="text-center">Total de visitas permitidas por plan</th>
                      <th class="text-center" width="30%">Horario Asignado</th>
                    </tr>
                    @foreach($rowsSuscripciones as $v)
                      <tr>
                        <td class="text-center {{ $v['estado'] == 'ACTIVA' ? 'table-success' : 'table-danger' }}">{{ $v['estado'] }}</td>
                        <td>{{ $v['plan'] }}</td>
                        <td class="text-center">{{ $v['pago'] }}</td>
                        <td class="text-center">{{ $v['fi'] }}</td>
                        <td class="text-center">{{ $v['ff'] }}</td>
                        <td class="text-center">{{ $v['max_visitas'] }}</td>
                        <td class="py-2">
                            @if($v['rows_fechas']->isNotEmpty())
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
                                      <td class="text-center">{{ $e->time_start .' '.$e->time_end }}</td>
                                    </tr>
                                  @endforeach
                                </table>
                              @else 

                                @if($v['estado'] == 'ACTIVA')
                                  <div class="row">
                                    <div class="col-12 text-center">
                                      <a href="{{ route($pageModule . '.horario', ['id' => $id, 'ids' => $v['id']]) }}"
                                        class="btn btn-outline-primary btn-xs px-4"
                                        style="border-width:2px;">
                                          <i class="bi bi-calendar-x"></i> Asignar Horario
                                      </a>
                                    </div>
                                  </div>
                                @endif

                              @endif
                        </td>
                      </tr>
                    @endforeach
                 </table>
                @else 
                   <div class="p-4 text-center">
                      <i class="fa fa-folder-open fa-3x text-muted"></i>

                      <h5 class="mt-3 text-muted">
                          El nadador no cuenta con ninguna suscripción
                      </h5>

                      <a href="{{ route($pageModule . '.create', $id) }}" class="btn btn-outline-primary btn-md mt-4">
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

                        <a href="{{ route($pageModule . '.create', $id) }}"
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

@stop
