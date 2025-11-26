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
              <li class="breadcrumb-item active"><i class="ses-text-muted">Seleccionar horario</i></li>
          </ol>
      </nav>
    </div>

    <div class="row">
      <div class="col-12">
        <a href="{{ route($pageModule.'.index',['page' => request()->page]) }}" class="btn btn-sm btn-outline-secondary rounded-pill">
            <i class="fa fa-arrow-left me-1"></i> Regresar
        </a>
      </div>
    </div>
  
    <div class="row mt-2">
      <div class="row">
        <div class="col-8">
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
        </div>
        <div class="col-4">
        </div>

        <div class="col-12">


<form action="{{ route($pageModule.'.temporal',['id' => $id, 'idm' => $idm, 'idy' => $idy,'page' => request()->page]) }}" method="POST">
@csrf
  <main class="row">
    <div class="col-12">

      <div class="sbox">
        <div class="sbox-title d-flex justify-content-between align-items-center">
          <h5 class="mb-0">
            <i class="bi bi-calendar3 me-1"></i>
            Calendario de horarios - {{ \Carbon\Carbon::create($year, $month)->translatedFormat('F Y') }}
          </h5>

         
          <div  style="display: none">
             {{-- Navegación simple de mes --}}
          @php
            $prev = \Carbon\Carbon::create($year, $month)->subMonth();
            $next = \Carbon\Carbon::create($year, $month)->addMonth();
          @endphp
            <a href="{{ request()->fullUrlWithQuery(['month' => $prev->month, 'year' => $prev->year]) }}"
              class="btn btn-sm btn-outline-secondary">
              &laquo; Mes anterior
            </a>
            <a href="{{ request()->fullUrlWithQuery(['month' => $next->month, 'year' => $next->year]) }}"
              class="btn btn-sm btn-outline-secondary">
              Siguiente mes &raquo;
            </a>
          </div>
        </div>

        <div class="sbox-content">

          <div class="table-responsive">
            <table class="table table-bordered table-calendar align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th class="text-center">Lunes</th>
                  <th class="text-center">Martes</th>
                  <th class="text-center">Miércoles</th>
                  <th class="text-center">Jueves</th>
                  <th class="text-center">Viernes</th>
                  <th class="text-center">Sábado</th>
                  <th class="text-center">Domingo</th>
                </tr>
              </thead>
              <tbody>
                @foreach($weeks as $week)
                  <tr>
            @foreach($week as $day)
              @php
                  $date     = $day['date'];
                  $inMonth  = $day['in_month'];
                  $horarios = $day['horarios'];
                  $max = $day['aforo_maximo'];
              @endphp

      <td class="p-1 align-top {{ $inMonth ? '' : 'ses-out-month' }}">
          <div class="d-flex justify-content-between mb-1">
              <span class="ses-calendar-day-number">
                  {{ $date->format('d') }}
              </span>
          </div>
          @if(!$inMonth)
              {{-- Día fuera del mes actual --}}
              <div class="text-muted ses-calendar-out-label">
                  Fuera de mes
              </div>
          @else
              @if($horarios->isNotEmpty())
                  @foreach($horarios as $h)

                  @php
                    $ocupados    = $h['ocupados'];
                    $disponibles = $h['disponibles'];

                    if ($disponibles <= 0) {
                        $estadoClase  = 'horario-danger';
                        $estadoTexto  = 'Sin disponibilidad';
                        $estadoIcon   = 'bi-x-circle';
                        $badgeClase   = 'badge-light-danger';
                        $clickable    = false;
                    } elseif ($disponibles <= 2) {
                        $estadoClase  = 'horario-warning';
                        $estadoTexto  = 'Quedan pocos lugares';
                        $estadoIcon   = 'bi-exclamation-triangle';
                        $badgeClase   = 'badge-light-warning';
                        $clickable    = true;
                    } else {
                        $estadoClase  = 'horario-success';
                        $estadoTexto  = 'Lugares disponibles';
                        $estadoIcon   = 'bi-check-circle';
                        $badgeClase   = 'badge-light-success';
                        $clickable    = true;
                    }
                @endphp

                        <label class="horario-pill w-100 {{ $estadoClase }} {{ $clickable ? '' : 'horario-disabled' }}" style="margin-bottom:0px;">
                            @if($clickable)
                              <input type="checkbox"
                                name="idplan_horario[]"
                                value='@json(["idplan_horario" => $h['idplan_horario'], "fecha" => $date->format("Y-m-d")])'
                                class="d-none">
                            @endif

                            <div class="horario-content">
                                <div class="d-flex justify-content-between" >
                                    <div>
                                        <div class="ses-fs-xs">
                                            <small>{{ \Carbon\Carbon::parse($h['time_start'])->format('h:i A') }}</small>
                                        </div>
                                    </div>

                                    <div class="text-end">
                                      <span class="badge ses-slot-badge {{ $badgeClase }}">
                                        {{ $h['ocupados'] }}/{{ $max }}
                                      </span>
                                    </div>
                                </div>
                            </div>
                        </label>
                    @endforeach
                @else
                    <div class="text-muted ses-calendar-empty">Sin clases</div>
                @endif
            @endif
                    </td>
                @endforeach
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

        </div>
      </div>

    </div>
  </main>

  <div class="row">
     <div class="col-12 mb-6 text-center">
      <button type="submit" name="save" class="btn btn-lg btn-primary ses-text-white"><i class="bi bi-calendar-plus me-2"></i> Asignar horario</button>
    </div>
  </div>
    
</form>

        </div>

      </div>

    </div>

  </div>
       

</main>

<style>
  /* Base */
.horario-content {
    cursor: pointer;
    width: auto;
    border: 1px solid #e5e7eb;
    border-left-width: 4px;
    border-radius: 12px;
    padding: 2px 10px;
    background: #ffffff;
    transition: all .18s ease;
    box-shadow: 0 1px 2px rgba(0,0,0,.05);
    position: relative;
    margin: 0px !important;
}

/* Estados */
.horario-success .horario-content {
    border-color: #c8f5d3;
    background: linear-gradient(90deg, #f6fffa 0%, #ffffff 70%);
}
.horario-success::before {
    background: #22c55e;
}

.horario-warning .horario-content {
    border-color: #ffe9c6;
    background: linear-gradient(90deg, #fffaf2 0%, #ffffff 70%);
}
.horario-warning::before {
    background: #f59e0b;
}

.horario-danger .horario-content {
    border-color: #ffd4d4;
    background: linear-gradient(90deg, #fff6f6 0%, #ffffff 70%);
}
.horario-danger::before {
    background: #ef4444;
}

/* Hover solo para disponibles */
.horario-pill:not(.horario-disabled):hover .horario-content {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.12);
}

.horario-pill input:checked + .horario-content {
    border-color: #0d6efd;
    border-left-color: #0d6efd;
    background: #f3f7ff;
}

/* Sin disponibilidad */
.horario-disabled {
    cursor: not-allowed;
    opacity: .85;
}
.horario-disabled .horario-content {
    cursor: not-allowed;
}

/* Textos */
.text-slot {
    color: #0f172a; /* casi negro */
}
.text-status {
    color: #6b7280;
}

/* Badge cupo */
.ses-slot-badge {
    padding: 4px 10px;
    font-size: 0.55rem;
    border-radius: 999px;
    font-weight: 600;
}

/* Tonos suaves de badges (si no usas los de BS5) */
.badge-light-success {
    background: #e6f9ec;
    color: #166534;
}
.badge-light-warning {
    background: #fff4dd;
    color: #92400e;
}
.badge-light-danger {
    background: #ffe5e7;
    color: #b91c1c;
}

/* Cabecera plan (como ya la tenías pero pulida) */
.plan-header {
    background: linear-gradient(135deg, #f8fbff 0%, #ffffff 60%, #f3f7ff 100%);
    border-color: #e1ecff;
}

.plan-header h2 {
    letter-spacing: 0.03em;
}

.plan-header .badge.bg-success,
.plan-header .badge.bg-secondary {
    width: 14px;
    height: 14px;
}

</style>

@stop
