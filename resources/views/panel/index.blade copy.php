@extends('layouts.app')

@section('content')

    <main class="row ">
        <div class="col-12">

            <div class="row mt-3">
                <div class="col-12">

                    @foreach ($rowsNiveles as $v)
                        <a href="{{ route($pageModule . '.calendario', ['idy' => $idy, 'idm' => $idm, 'idn' => $v->id]) }}"
                            class="btn btn-xs  {{ $idn == $v->id ? 'btn-outline-primary' : 'btn-white' }}"><i
                                class="bi bi-calendar3"></i> {{ $v->nivel }}</a>
                    @endforeach

                    <main class="row mt-3">
                        <div class="col-12">

                            <div class="sbox">
                                <div class="sbox-title d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        Calendario de clases -
                                        {{ \Carbon\Carbon::create($year, $month)->translatedFormat('F Y') }}
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
                @foreach ($weeks as $week)
                  <tr>
                    @foreach ($week as $day)
                      @php
                        $date = $day['date'];
                        $inMonth = $day['in_month'];
                        $horarios = $day['horarios'];
                        $max = $day['aforo_maximo'];

                        $isToday = $date->isToday(); 
                      @endphp

  <td class="ses-gcal-cell
        @if(!$inMonth) ses-gcal-out @endif
        @if($date->isWeekend()) ses-gcal-weekend @endif
        {{ $isToday ? ' ses-gcal-today' : '' }}
    ">
    <div class="ses-gcal-day">

        <div class="ses-gcal-day-header">
            <span class="ses-gcal-day-number">{{ $date->format('d') }}</span>

            @if($isToday)
                <span class="ses-gcal-chip-hoy">Hoy</span>
            @endif
        </div>

        <div class="ses-gcal-day-body">
            @if (!$inMonth)
                <div class="ses-gcal-out-text">Fuera de mes</div>
            @else
                @if ($horarios->isNotEmpty())
                    @foreach ($horarios as $h)
                        @php
                            $ocupados     = $h['ocupados'];
                            $disponibles  = $h['disponibles'];

                            if ($disponibles <= 0) {
                                $estadoClase = 'horario-danger';
                                $estadoTexto = 'Sin disponibilidad';
                                $badgeClase  = 'badge-light-danger';
                                $clickable   = false;
                            } elseif ($disponibles <= 2) {
                                $estadoClase = 'horario-warning';
                                $estadoTexto = 'Quedan pocos lugares';
                                $badgeClase  = 'badge-light-warning';
                                $clickable   = true;
                            } else {
                                $estadoClase = 'horario-success';
                                $estadoTexto = 'Lugares disponibles';
                                $badgeClase  = 'badge-light-success';
                                $clickable   = true;
                            }
                        @endphp

                        <label
                            class="ses-gcal-event {{ $estadoClase }} js-open-modal"
                            data-idph="{{ $h['idplan_horario'] }}"
                            data-fecha="{{ $date->format('Y-m-d') }}"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="{{ $estadoTexto }}">
                            @if ($clickable)
                                <input type="radio"
                                    name="idplan_horario[]"
                                    value='@json(['idplan_horario' => $h['idplan_horario'], 'fecha' => $date->format('Y-m-d')])'
                                    class="d-none js-radio-horario">
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
            $(function() {
              activarTooltips();
              function activarTooltips() {
                  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                  tooltipTriggerList.map(function (tooltipTriggerEl) {
                      return new bootstrap.Tooltip(tooltipTriggerEl);
                  });
              }

                $(document).on('click', '.js-open-modal', function() {

                    const idplan = $(this).data('idph');
                    const fecha = $(this).data('fecha');

                    // Abrir modal (Bootstrap)
                    $('#modalHorario').modal('show');
                    $('#modalHorarioLabel').html('Lista de alumnos - Fecha ' + fecha);

                    loadInfo(idplan, fecha);
                });

                function loadInfo(idplan, fecha) {
                    axios.get("{{ route($pageModule . '.info') }}", {
                            params: {
                                idplan_horario: idplan,
                                fecha: fecha
                            }
                        })
                        .then((resp) => {
                            $("#ses-modal-body").empty().append(resp.data);
                        })
                        .catch((err) => {});
                }

                $(document).on('click', '.js-asistencia', function() {

                    const idreserva = $(this).data('id');
                    const estado = $(this).data('estado');
                    const idplan = $(this).data('idplan');
                    const fecha = $(this).data('fecha');
                    const alumno = $(this).data('alumno');

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

                            axios.post("{{ route($pageModule . '.asistencia') }}", {
                                    id: idreserva,
                                    std: estado
                                })
                                .then((resp) => {
                                    let row = resp.data;

                                    if (row.status == 'success') {
                                        Swal.fire({
                                            title: row.message,
                                            text: resp.data.message,
                                            icon: estadoBadge,
                                            toast: true,
                                            position: "top-end",
                                            timer: 1500,
                                            showConfirmButton: false,
                                            background: "#ffffff",
                                            iconColor: estadoBadge === "success" ?
                                                "#16A34A" : "#DC2626",
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
  /* ====== MODO GOOGLE CALENDAR ====== */
.table-calendar tbody td:hover {
    background: #eef6ff !important;
    box-shadow: inset 0 0 0 2px #bfdbfe;
}
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

/* leve énfasis fines de semana */
.table-calendar thead th:nth-child(6),
.table-calendar thead th:nth-child(7) {
    color: #ef4444;
}

.table-calendar tbody td {
    border: 1px solid #e5e7eb;
    padding: 0;
    vertical-align: top;
}

/* Contenedor del día (card) */
.ses-gcal-cell {
    background: #ffffff;
}

.ses-gcal-day {
    min-height: 88px;
    padding: 4px 6px 6px;
    display: flex;
    flex-direction: column;
}

/* Día fuera de mes */
.ses-gcal-out .ses-gcal-day {
    background: #f9fafb;
    color: #9ca3af;
}

/* Fines de semana: ligero tinte */
.ses-gcal-weekend:not(.ses-gcal-out) .ses-gcal-day {
    background: #faf5ff;
}

/* Hoy: bordecito azul */
.ses-gcal-today .ses-gcal-day {
    box-shadow: inset 0 0 0 2px rgba(37, 99, 235, 0.5);
    background: #eff6ff;
}

/* Cabecera del día */
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

/* Chip "Hoy" */
.ses-gcal-chip-hoy {
    font-size: 0.6rem;
    padding: 2px 8px;
    border-radius: 999px;
    background: #dbeafe;
    color: #1d4ed8;
    font-weight: 600;
}

/* Textos base */
.ses-gcal-day-body {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.ses-gcal-out-text,
.ses-gcal-empty {
    font-size: 0.7rem;
    color: #9ca3af;
}

/* ====== Eventos (horarios) estilo Google ====== */

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
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06);
    transition: background 0.15s ease, box-shadow 0.15s ease, transform 0.12s ease;
}

/* tiempo + badge */
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

/* Colores por estado (left border + badge) */
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

/* Hover tipo Google */
.ses-gcal-event:hover .ses-gcal-event-content {
    background: #eff6ff;
    box-shadow: 0 3px 6px rgba(15, 23, 42, 0.15);
    transform: translateY(-1px);
}

/* Desactiva hover si no es clickable (sin lugares) */
.horario-danger:not(.horario-success):not(.horario-warning) .ses-gcal-event-content {
    opacity: 0.85;
    cursor: default;
}

/* Ajuste general para que no se vea pesado */
.table-calendar tbody tr:last-child td {
    border-bottom: 1px solid #e5e7eb;
}


    </style>

@stop
