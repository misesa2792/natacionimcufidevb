@extends('layouts.app')

@section('content')

<main class="row">
  <div class="col-12">
    <div class="page-header">
      <div class="page-title">
          <h4 class="text-blue-900">
            <strong>{{ $pageTitle }}</strong>
            <small class="text-gray-400"><i>{{ $pageNote }}</i></small>
          </h4>
      </div>
      
      <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="{{ URL::to('dashboard') }}">
                  <i class="fa fa-home s-18"></i>
                </a>
              </li>
              <li class="breadcrumb-item">
                  <a href="{{ route($pageModule.'.index') }}" class="text-decoration-none">
                    <i>{{ $pageTitle }}</i>
                  </a>    
              </li>
              <li class="breadcrumb-item active">
                <i class="ses-text-muted">Seleccionar horario</i>
              </li>
          </ol>
      </nav>
    </div>

    <div class="row">
      <div class="col-12 mb-2">
        <a href="{{ route($pageModule.'.index',['page' => request()->page]) }}"
           class="btn btn-sm btn-outline-secondary rounded-pill">
            <i class="fa fa-arrow-left me-1"></i> Regresar
        </a>
      </div>
    </div>
  
    <div class="row mt-2">
      <div class="col-8">
        <div class="sbox">
          <div class="sbox-title ses-text-muted">
              <h5><i class="fa fa-table"></i> <strong> Alumno</strong></h5>
          </div>
          <div class="sbox-content"> 

              <div class="mb-3">
                <div class="row">
                  <div class="col-3 text-end ses-text-muted">Nombre completo:</div>
                  <div class="col-9 ses-text-blue">{{ $row->nombre }}</div>
                </div>
              </div>

              <div class="mb-3">
                <div class="row">
                  <div class="col-3 text-end ses-text-muted">Plan:</div>
                  <div class="col-3">
                    <strong>{{ $row->nivel }}</strong>
                    <i>({{ $row->plan }})</i>
                  </div>
                  <div class="col-3 text-end ses-text-muted">Precio:</div>
                  <div class="col-3"><strong>${{ $row->precio }}</strong></div>
                </div>
              </div>

              <div class="mb-3">
                <div class="row">
                  <div class="col-3 text-end ses-text-muted">Mes a pagar:</div>
                  <div class="col-3">{{ $mes }}</div>
                  <div class="col-3 text-end ses-text-muted">Máximo de visitas al mes:</div>
                  <div class="col-3">{{ $row->max_visitas_mes }}</div>
                </div>
              </div>

          </div>
        </div>
      </div>

      <div class="col-4">
        {{-- aquí puedes meter un resumen, leyenda de colores, etc. --}}
      </div>

      <div class="col-12 mt-2">
        <form action="{{ route($pageModule.'.temporal',['id' => $id, 'idm' => $idm, 'idy' => $idy,'page' => request()->page]) }}"
              method="POST">
          @csrf

          <main class="row">
            <div class="col-12">
              <div class="sbox">
                <div class="sbox-title d-flex justify-content-between align-items-center">
                  <h5 class="mb-0">
                    <i class="bi bi-calendar3 me-1"></i>
                    Calendario de horarios -
                    {{ \Carbon\Carbon::create($year, $month)->translatedFormat('F Y') }}
                  </h5>

                  <div style="display:none">
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
                    <table class="table table-bordered table-calendar ses-gcal-table align-middle mb-0">
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
                                  $max      = $day['aforo_maximo'];

                                  $isToday  = $date->isToday();
                              @endphp

                              <td class="ses-gcal-cell
                                  @if(!$inMonth) ses-gcal-out @endif
                                  @if($date->isWeekend()) ses-gcal-weekend @endif
                                  {{ $isToday ? 'ses-gcal-today' : '' }}
                              ">
                                <div class="ses-gcal-day">
                                  <div class="ses-gcal-day-header">
                                    <span class="ses-gcal-day-number">{{ $date->format('d') }}</span>

                                    @if($isToday)
                                      <span class="ses-gcal-chip-hoy">Hoy</span>
                                    @endif
                                  </div>

                                  <div class="ses-gcal-day-body">
                                    @if(!$inMonth)
                                      <div class="ses-gcal-out-text">Fuera de mes</div>
                                    @else
                                      @if($horarios->isNotEmpty())
                                        @foreach($horarios as $h)
                                          @php
                                            $ocupados    = $h['ocupados'];
                                            $disponibles = $h['disponibles'];

                                            if ($disponibles <= 0) {
                                                $estadoClase  = 'horario-danger';
                                                $estadoTexto  = 'Sin disponibilidad';
                                                $badgeClase   = 'badge-light-danger';
                                                $clickable    = false;
                                            } elseif ($disponibles <= 2) {
                                                $estadoClase  = 'horario-warning';
                                                $estadoTexto  = 'Quedan pocos lugares';
                                                $badgeClase   = 'badge-light-warning';
                                                $clickable    = true;
                                            } else {
                                                $estadoClase  = 'horario-success';
                                                $estadoTexto  = 'Lugares disponibles';
                                                $badgeClase   = 'badge-light-success';
                                                $clickable    = true;
                                            }
                                          @endphp

                                          <label
                                            class="ses-gcal-event {{ $estadoClase }} {{ $clickable ? '' : 'ses-gcal-disabled' }}"
                                            title="{{ $estadoTexto }}">
                                            
                                            @if($clickable)
                                              <input type="checkbox"
                                                     name="idplan_horario[]"
                                                     value='@json(["idplan_horario" => $h["idplan_horario"], "fecha" => $date->format("Y-m-d")])'
                                                     class="d-none js-horario-check">
                                            @endif

                                            <div class="ses-gcal-event-content">
                                              <span class="ses-gcal-event-time">
                                                {{ \Carbon\Carbon::parse($h['time_start'])->format('h:i A') }}
                                              </span>
                                              <span class="ses-gcal-event-badge {{ $badgeClase }}">
                                                {{ $h['ocupados'] }}/{{ $max }}
                                              </span>
                                            </div>
                                          </label>
                                        @endforeach
                                      @else
                                        <div class="ses-gcal-empty">Sin clases</div>
                                      @endif
                                    @endif
                                  </div>
                                </div>
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

          <div class="row mt-3">
            <div class="col-12 text-center">
              <button type="submit" name="save"
                      class="btn btn-lg btn-primary ses-text-white">
                <i class="bi bi-calendar-plus me-2"></i> Asignar horario
              </button>
            </div>
          </div>
        </form>
      </div>

    </div>
  </div>
</main>

{{-- JS para que al hacer click en el label se marque el checkbox y se vea seleccionado --}}
@push('js')
<script>
  $(function () {
      // toggle visual de selección (por si quieres reforzar)
      $(document).on('change', '.js-horario-check', function () {
          const content = $(this).next('.ses-gcal-event-content');
          if (this.checked) {
              content.addClass('ses-gcal-selected');
          } else {
              content.removeClass('ses-gcal-selected');
          }
      });
  });
</script>
@endpush

<style>
/* ====== BASE TABLA CALENDARIO (mismo estilo pro) ====== */
.table-calendar {
    border-collapse: separate;
    border-spacing: 0;
    font-size: 0.78rem;
}
.table-calendar thead th {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    padding: 6px 8px;
    background: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
    border-top: none;
}
.table-calendar thead th:nth-child(6),
.table-calendar thead th:nth-child(7) {
    color: #ef4444; /* fin de semana */
}
.table-calendar tbody td {
    border: 1px solid #e5e7eb;
    padding: 0;
    vertical-align: top;
}
.table-calendar tbody tr:last-child td {
    border-bottom: 1px solid #e5e7eb;
}
.table-calendar tbody td:hover {
    background: #eef6ff !important;
    box-shadow: inset 0 0 0 2px #bfdbfe;
}

/* ====== CELDAS / DÍAS ====== */
.ses-gcal-cell {
    background: #ffffff;
}
.ses-gcal-day {
    min-height: 88px;
    padding: 4px 6px 6px;
    display: flex;
    flex-direction: column;
}
.ses-gcal-out .ses-gcal-day {
    background: #f9fafb;
    color: #9ca3af;
}
.ses-gcal-weekend:not(.ses-gcal-out) .ses-gcal-day {
    background: #faf5ff;
}
.ses-gcal-today .ses-gcal-day {
    background: #f0fdf4 !important;         /* verde MUY suave */
    box-shadow: inset 0 0 0 2px #16a34a80;  /* contorno verde */
}
/* Header del día */
.ses-gcal-day-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2px;
}
.ses-gcal-day-number {
    font-weight: 600;
    font-size: 0.8rem;
    color: #111827;
}
.ses-gcal-chip-hoy {
    font-size: 0.6rem;
    padding: 2px 8px;
    border-radius: 999px;
    background: #3da864;
    color: #ffffff;
    font-weight: 600;
}
.ses-gcal-today .ses-gcal-day-number {
    color: #15803d;
    font-weight: 700;
}
/* Body del día */
.ses-gcal-day-body {
     display: flex;
    flex-direction: column;
    gap: 4px !important; /* más aire */
    padding-bottom: 4px;
}
.ses-gcal-out-text,
.ses-gcal-empty {
    font-size: 0.7rem;
    color: #9ca3af;
}

/* ====== EVENTOS / HORARIOS ====== */
.ses-gcal-event {
    display: block;
    cursor: pointer;
    margin-bottom: 2px;
}
.ses-gcal-event-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 6px;
    padding: 2px 6px;
    font-size: 0.7rem;
    border-left-width: 4px;
    border-left-style: solid;
    background: #ffffff;
    box-shadow: 0 1px 2px rgba(15,23,42,0.06);
    transition: background 0.15s ease, box-shadow 0.15s ease, transform 0.12s ease;
}
.ses-gcal-event-time {
    color: #111827;
    font-weight: 500;
}
.ses-gcal-event-badge {
    padding: 2px 6px;
    border-radius: 999px;
    font-size: 0.65rem;
    font-weight: 600;
}

/* Colores por estado */
.horario-success .ses-gcal-event-content {
    border-left-color: #22c55e;
}
.horario-warning .ses-gcal-event-content {
    border-left-color: #f59e0b;
}
.horario-danger .ses-gcal-event-content {
    border-left-color: #ef4444;
}

/* Badges suaves */
.badge-light-success {
    background: #dcfce7;
    color: #166534;
}
.badge-light-warning {
    background: #fef3c7;
    color: #92400e;
}
.badge-light-danger {
    background: #fee2e2;
    color: #b91c1c;
}

/* Hover */
.ses-gcal-event:hover .ses-gcal-event-content {
    background: #f0f9ff !important;
    box-shadow: 0 3px 10px rgba(25, 113, 194, 0.18);
    transform: translateY(-1.5px);
}

/* Horario sin lugares (deshabilitado) */
.ses-gcal-disabled {
    cursor: not-allowed;
}
.ses-gcal-disabled .ses-gcal-event-content {
    opacity: 0.75;
    box-shadow: none;
}

/* Seleccionado (checkbox checked) */
.ses-gcal-event input:checked + .ses-gcal-event-content,
.ses-gcal-selected {
    background: #e8f0ff !important;
    border-left-color: #1d4ed8 !important;
    box-shadow: 0 0 0 2px rgba(29, 78, 216, 0.25);
    transform: scale(1.01);
}

</style>

@stop
