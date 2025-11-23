@extends('layouts.pago')

@section('content')
<main class="row">
  <div class="col-12">

    <form action="{{ route($pageModule.'.update',['token' => $token]) }}" method="POST">
      @csrf
        <div class="row mt-4">

           <div class="col-12">
              <div class="plan-header bg-white border rounded-3 shadow-sm px-5 py-4">
                <h2 class="fw-bold text-center mb-2 text-primary">
                    Elige tus horarios de entrenamiento
                </h2>
                <p class="text-muted text-center mb-3 ses-fs-base">
                    Tu plan incluye <strong>{{ $max_visitas_mes }} días de asignación</strong>. 
                    Selecciona <strong>un horario por día</strong> para aprovechar mejor tu plan 
                    y evitar agotar tus días demasiado rápido.
                </p>

                <div class="d-flex justify-content-center flex-wrap gap-3">
                    <div class="d-flex align-items-center small text-muted">
                        <span class="badge rounded-pill bg-success me-2">&nbsp;</span>
                        Horarios con lugares disponibles
                    </div>
                    <div class="d-flex align-items-center small text-muted">
                        <span class="badge rounded-pill bg-danger me-2">&nbsp;</span>
                        Horarios completos o no disponibles
                    </div>
                </div>
            </div>
        </div>

       <div class="row">
        <div class="col-12 text-center mt-3 mb-3">
             <a href="{{ route($pageModule .'.pagar') }}" >
                &laquo; Regresar a la página principal
            </a>
        </div>
       </div>

      @if(session('success'))
          <div class="success">{{ session('success') }}</div>
      @endif

        <div class="row">
          @foreach($rowsHorarios as $h)
              <div class="col-12">
                <div class="sbox">
                  <div class="sbox-title ses-text-muted">
                      <h5> <i class="bi bi-calendar-week"></i> <strong> {{ $h['fecha'] }} </strong> - {{ $h['dia_nombre'] }}</h5>
                  </div>
                  <div class="sbox-content" style="min-height:auto;"> 

                   @foreach($h['rows_horario'] as $v)
                      <div class="row">
                        <div class="col-12 mb-1">

                          @php
                              $ocupados    = $v['ocupados'];
                              $max         = $v['aforo_maximo'];
                              $disponibles = $v['disponibles'];

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

                          <label class="horario-pill w-100 {{ $estadoClase }} {{ $clickable ? '' : 'horario-disabled' }}">
                              @if($clickable)
                                  <input type="checkbox"
                                        name="idplan_horario[]"
                                        value='{"id": {{ $v['id'] }}, "fecha": "{{ $h['fecha'] }}"}'
                                        class="d-none">
                              @endif

                              <div class="horario-content">
                                  <div class="d-flex justify-content-between">
                                      <div>
                                          <div class="fw-semibold ses-fs-xs text-slot">
                                              {{ $v['time_start'] }} - {{ $v['time_end'] }}
                                          </div>
                                          <div class="ses-fs-xs text-status mt-1">
                                              <i class="bi {{ $estadoIcon }} me-1"></i>
                                              @if($disponibles > 0)
                                                  {{ $estadoTexto }}: {{ $disponibles }}
                                              @else
                                                  {{ $estadoTexto }}
                                              @endif
                                          </div>
                                      </div>

                                      <div class="text-end">
                                          <span class="badge ses-slot-badge {{ $badgeClase }}">
                                              {{ $ocupados }}/{{ $max }}
                                          </span>
                                      </div>
                                  </div>
                              </div>
                          </label>

                        </div>
                      </div>
                  @endforeach

                  </div>
                </div>
              </div>
          @endforeach


          <div class="text-center mb-5 mt-3 ">
                <button type="submit" name="save" class="btn btn-primary btn-lg px-4 fw-semibold shadow-sm"><i class="bi bi-calendar-plus me-2"></i> Asignar horario al nadador</button>
          </div>

        </div>
      
      </div>


    </form>

  </div>
</main>

<style>
  .horario-pill {
    display: inline-block;
}

/* Base */
.horario-content {
    cursor: pointer;
    width: auto;
    border: 1px solid #e5e7eb;
    border-left-width: 4px;
    border-radius: 12px;
    padding: 10px 12px;
    background: #ffffff;
    transition: all .18s ease;
    box-shadow: 0 1px 2px rgba(0,0,0,.05);
    position: relative;
}

/* Color lateral (se sobrescribe por estado) 
.horario-pill::before {
    content: "";
    position: absolute;
    left: 0;
    top: 8px;
    bottom: 8px;
    width: 3px;
    border-radius: 999px;
}
*/
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
    font-size: 0.75rem;
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
