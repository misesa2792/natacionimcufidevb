@extends('layouts.app')

@section('content')



<main class="row ">
  <div class="col-12">
  
    <div class="row mt-3">
        <div class="col-12">

        @foreach ($rowsNiveles as $v)
            <a href="{{ route($pageModule . '.calendario', ['idy' => $idy, 'idm' => $idm, 'idn' =>  $v->id ]) }}" 
              class="btn btn-xs  {{ $idn == $v->id ? 'btn-outline-primary' : 'btn-white' }}"><i class="bi bi-calendar3"></i> {{ $v->nivel }}</a>
        @endforeach

  <main class="row mt-3">
    <div class="col-12">

      <div class="sbox">
        <div class="sbox-title d-flex justify-content-between align-items-center">
          <h5 class="mb-0">
            <i class="bi bi-calendar3 me-1"></i>
            Calendario de clases - {{ \Carbon\Carbon::create($year, $month)->translatedFormat('F Y') }}
          </h5>

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

                        <label class="horario-pill w-100 {{ $estadoClase }}  js-open-modal" 
                          data-idph="{{ $h['idplan_horario'] }}"
                          data-fecha="{{ $date->format("Y-m-d") }}"
                           style="margin-bottom:0px;">
                            @if($clickable)
                              <input type="radio"
                                name="idplan_horario[]"
                                value='@json(["idplan_horario" => $h['idplan_horario'], "fecha" => $date->format("Y-m-d")])'
                                class="d-none js-radio-horario">
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


        </div>


    </div>

  </div>
       

</main>

<!-- Modal selección de horario -->
<div class="modal fade" id="modalHorario" tabindex="-1" aria-labelledby="modalHorarioLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-top modal-lg ">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="modalHorarioLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body" id="ses-modal-body">
      
      </div>

      <div class="modal-footer">
        
      </div>

    </div>
  </div>
</div>

@push('js')
<script>
  $(function () {

      $(document).on('click', '.js-open-modal', function () {

          const idplan = $(this).data('idph');
          const fecha  = $(this).data('fecha');

          // Abrir modal (Bootstrap)
          $('#modalHorario').modal('show');
          $('#modalHorarioLabel').html('Lista de alumnos - Fecha ' + fecha);
        
          loadInfo(idplan, fecha);
      });

      function loadInfo(idplan, fecha){
        axios.get("{{ route($pageModule.'.info') }}", {
              params: {
                  idplan_horario: idplan,
                  fecha: fecha
              }
          })
          .then((resp) => {
              $("#ses-modal-body").empty().append(resp.data);
          })
          .catch((err) => {
          });
      }

      $(document).on('click', '.js-asistencia', function () {

    const idreserva = $(this).data('id');
    const estado    = $(this).data('estado');
    const idplan    = $(this).data('idplan');
    const fecha     = $(this).data('fecha');
    const alumno     = $(this).data('alumno');

    // Mensajes según estado
    const estadoTxt = estado == 2 ? "confirmar asistencia" : "marcar como NO asistió";
    const estadoBadge = estado == 2 ? "success" : "error";

    Swal.fire({
        title: "¿Deseas " + estadoTxt + "?",
        text: alumno,
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Sí, continuar",
        cancelButtonText: "Cancelar",
        confirmButtonColor: "#16A34A",
        cancelButtonColor: "#6B7280",
    }).then((result) => {
        if (result.isConfirmed) {

            axios.post("{{ route($pageModule.'.asistencia') }}", {
                id: idreserva,
                std: estado
            })
            .then((resp) => {
              let row = resp.data;
              
              if(row.status == 'success'){
                Swal.fire({
                    title: row.message,
                    text: resp.data.message,
                    icon: estadoBadge,
                    toast: true,
                    position: "top-end",
                    timer: 1500,
                    showConfirmButton: false,
                    background: "#ffffff",
                    iconColor: estadoBadge === "success" ? "#16A34A" : "#DC2626",
                });
                loadInfo(idplan, fecha);
              }
               
            })
            .catch((err) => {
                Swal.fire({
                    title: "Error",
                    text: "No se pudo guardar la asistencia.",
                    icon: "error"
                });
            });

        }

    });

});

  });
</script>
@endpush

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
