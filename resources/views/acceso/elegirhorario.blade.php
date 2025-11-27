@extends('layouts.pago')

@section('content')

<main class="row">
  <div class="col-12">
   
    <div class="text-center">
         <a href="{{ route($pageModule .'.pagar') }}">
            &laquo; Regresar a la página principal
        </a>
    </div>
  
    <div class="row mt-2">

        <div class="col-12">
            <div class="sbox">
                <div class="sbox-title ses-text-muted">
                    <h5><i class="fa fa-table"></i> <strong> Datos Alumno</strong></h5>
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
                            <div class="col-9 ses-text-blue">{{ $row->nivel }} <i>({{ $row->plan }})</i></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-3 text-right ses-text-muted">Máximo de visitas al mes:</div>
                            <div class="col-9 ses-text-blue">{{ $row->max_visitas_mes }}</i></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

      <div class="col-12 mt-2">
        <form action="{{ route('acceso.temporal',['curp' => $curp, 'idm' => $idm, 'idy' => $idy]) }}"
              method="POST">
          @csrf

          <main class="row">
            <div class="col-12">
                <h4 class="text-center">Calendario de horarios - {{ \Carbon\Carbon::create($year, $month)->translatedFormat('F Y') }}</h4>

    {{-- MOBILE: semanas + tarjetas por día (mismo estilo de la otra vista) --}}
    <div class="d-block ses-calendar-mobile">
        @foreach ($weeks as $weekIndex => $week)
            @php
                // ¿Esta semana tiene algún día dentro del mes?
                $hasInMonth = collect($week)->contains(fn($d) => $d['in_month']);
                if (!$hasInMonth) {
                    continue;
                }
                $numSemana = $weekIndex + 1;
            @endphp

            <div class="ses-mobile-week-title">
                Semana {{ $numSemana }}
            </div>

            @foreach ($week as $day)
                @php
                    $date      = $day['date'];
                    $inMonth   = $day['in_month'];
                    $horarios  = $day['horarios'];
                    $max       = $day['aforo_maximo'];
                    $isToday   = $date->isToday();

                    if (!$inMonth) {
                        continue;
                    }

                    $horariosCount    = $horarios->count();
                    $totalDisponibles = $horarios->sum(fn($h) => $h['disponibles']);
                    $sinClases        = $horariosCount === 0;
                @endphp

                <div class="ses-mobile-day-card {{ $isToday ? 'ses-gcal-today is-open' : '' }}">
                    <div class="ses-mobile-day-header">

                        {{-- Fecha --}}
                        <div>
                            <div class="ses-mobile-day-number">
                                {{ $date->format('d') }}
                                <span class="ses-mobile-day-name">
                                    {{ $date->translatedFormat('l') }}
                                </span>
                            </div>
                            <div class="ses-mobile-day-month">
                                {{ $date->translatedFormat('F') }}
                            </div>
                        </div>

                        {{-- Resumen + botón --}}
                        <div class="ses-mobile-day-right text-end">
                            @if($sinClases)
                                <div class="ses-mobile-day-summary ses-summary-empty">
                                    Sin clases
                                </div>
                            @else
                                <div class="ses-mobile-day-summary">
                                    <span class="ses-chip ses-chip-horarios">
                                        {{ $horariosCount }} horario{{ $horariosCount !== 1 ? 's' : '' }}
                                    </span>
                                    <span class="ses-chip ses-chip-libres">
                                        {{ $totalDisponibles }} lugares libres
                                    </span>
                                </div>
                            @endif

                            @if(!$sinClases)
                                <button type="button"
                                    class="btn btn-xs btn-outline-secondary ses-mobile-toggle js-toggle-day">
                                    <span class="js-toggle-text">
                                        {{ $isToday ? 'Ocultar horarios' : 'Ver horarios' }}
                                    </span>
                                    <i class="bi {{ $isToday ? 'bi-chevron-up' : 'bi-chevron-down' }}"></i>
                                </button>
                            @endif

                            @if ($isToday)
                                <span class="ses-gcal-chip-hoy ms-1 d-inline-block">Hoy</span>
                            @endif
                        </div>
                    </div>

                    @if(!$sinClases)
                        <div class="ses-mobile-day-body"
                             @unless($isToday) style="display:none" @endunless>
                            
                            @foreach ($horarios as $h)
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
                                            class="ses-hidden-check js-horario-check">
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

                        </div>
                    @endif
                </div>
            @endforeach
        @endforeach
    </div> {{-- /mobile --}}

            </div>
          </main>

          {{-- BOTÓN FLOTANTE MOBILE --}}
            <div class="ses-floating-counter" id="sesFloatingCounter" style="display:none;">
                <button type="button" class="ses-floating-btn" id="sesFloatingBtn">
                    <span class="ses-floating-count" id="sesFloatingCount">0</span>
                    <span class="ses-floating-text">horarios seleccionados</span>
                </button>
            </div>


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
@section('js')
<script>
  $(function () {
      
     function updateSelectedCounter() {
        const totalChecked = $('.js-horario-check:checked').length;
        const $wrap  = $('#sesFloatingCounter');
        const $count = $('#sesFloatingCount');
        const $text  = $('#sesFloatingText');

        if (totalChecked > 0) {
            $count.text(totalChecked);
            $text.text(totalChecked === 1 ? 'horario seleccionado' : 'horarios seleccionados');
            $wrap.fadeIn(150);
        } else {
            $wrap.fadeOut(150);
        }
    }


    $(document).on('change', '.js-horario-check', function () {
        const $check   = $(this);
        const $content = $check.next('.ses-gcal-event-content');
        console.log("chechekd = " + this.checked);
        // Estilo seleccionado / no seleccionado
        if (this.checked) {
            $content.addClass('ses-gcal-selected');
        } else {
            $content.removeClass('ses-gcal-selected');
        }

        // Sólo aplica lógica extra en MOBILE (dentro de una tarjeta de día)
        const $card = $check.closest('.ses-mobile-day-card');
        if (!$card.length) {
            return; // en desktop no hacemos nada más
        }

        const $body   = $card.find('.ses-mobile-day-body');
        const $toggle = $card.find('.js-toggle-day');

        const anyChecked = $card.find('.js-horario-check:checked').length > 0;

        if (anyChecked) {
            // Forzar abierto y ocultar botón
            $body.show();              // aseguramos que esté visible
            $card.addClass('is-open'); // estado abierto
            $toggle.addClass('d-none'); // quitamos "Ver/Ocultar horarios"
        } else {
            // No hay seleccionados: volvemos a mostrar el botón
            $toggle.removeClass('d-none');

            // Ajustamos el texto según el estado actual
            const isOpen = $card.hasClass('is-open');
            $toggle.find('.js-toggle-text').text(
                isOpen ? 'Ocultar horarios' : 'Ver horarios'
            );
        }
        // Actualizar contador global
        updateSelectedCounter();
    });

    // Botón flotante → hace scroll al botón de Asignar horario
    $(document).on('click', '#sesFloatingBtn', function () {
        const $target = $('#btnAsignarHorario');
        if ($target.length) {
            $('html, body').animate({
                scrollTop: $target.offset().top - 80
            }, 250);
        }
    });

    // Inicializar contador por si vienen horarios ya marcados
    updateSelectedCounter();


      // Toggle de días en mobile
    $(document).on('click', '.js-toggle-day', function () {
        const $btn  = $(this);
        const $card = $btn.closest('.ses-mobile-day-card');
        const $body = $card.find('.ses-mobile-day-body');

        const isOpen = $card.hasClass('is-open');

        $body.slideToggle(150);
        $card.toggleClass('is-open');

        $btn.find('.js-toggle-text').text(
            isOpen ? 'Ver horarios' : 'Ocultar horarios'
        );

        const $icon = $btn.find('i');
        $icon.toggleClass('bi-chevron-down bi-chevron-up');
    });
  });
</script>
@endsection

<style>
  /* ====== MOBILE CALENDAR CARDS ====== */
    .ses-calendar-mobile {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .ses-mobile-week-title {
        font-size: 0.8rem;
        font-weight: 700;
        color: #374151;
        border-top: 1px solid #e5e7eb;
        padding-top: 10px;
        margin-top: 10px;
    }

    .ses-mobile-day-card {
        background: #ffffff;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        padding: 8px 10px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06);
    }

    .ses-mobile-day-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 4px;
    }

    .ses-mobile-day-number {
        font-weight: 700;
        font-size: 0.95rem;
        color: #111827;
    }

    .ses-mobile-day-name {
        font-size: 0.8rem;
        color: #6b7280;
        display: block;
    }

    .ses-mobile-day-month {
        font-size: 0.7rem;
        color: #9ca3af;
    }

    .ses-mobile-day-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 2px;
    }

    .ses-mobile-day-summary {
        font-size: 0.75rem;
        color: #4b5563;
        font-weight: 500;
        margin-bottom: 2px;
    }

    .ses-summary-empty {
        color: #dc2626;
        font-weight: 600;
    }

    .ses-mobile-day-body {
        margin-top: 4px;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .ses-mobile-toggle {
        font-size: 0.7rem;
        padding: 2px 8px;
        border-radius: 999px;
        line-height: 1.2;
    }

    .ses-chip {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 999px;
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        margin-left: 4px;
        margin-bottom: 2px;
        font-size: 0.72rem;
        white-space: nowrap;
    }

    .ses-chip-horarios {
        border-color: #bfdbfe;
        background: #eff6ff;
        color: #1d4ed8;
    }

    .ses-chip-libres {
        border-color: #bbf7d0;
        background: #ecfdf3;
        color: #15803d;
    }

    .ses-gcal-day {
        min-height: auto;
        padding: 0;
    }
    .sbox-content{padding:0px;}

    /* ===== BOTÓN FLOTANTE CONTADOR (MOBILE) ===== */
.ses-floating-counter {
    position: fixed;
    right: 12px;
    bottom: 80px; /* arriba del botón azul si se ve en mobile, puedes ajustar */
    z-index: 1050;
}

.ses-floating-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: none;
    border-radius: 999px;
    padding: 8px 14px;
    background: #111827;
    color: #ffffff;
    box-shadow: 0 8px 16px rgba(15,23,42,0.35);
    font-size: 0.78rem;
    font-weight: 500;
}

.ses-floating-btn:active {
    transform: translateY(1px);
    box-shadow: 0 4px 10px rgba(15,23,42,0.35);
}

.ses-floating-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 22px;
    height: 22px;
    border-radius: 999px;
    background: #22c55e;
    color: #052e16;
    font-weight: 700;
    font-size: 0.75rem;
}

.ses-floating-text {
    white-space: nowrap;
}
.ses-hidden-check {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

/*DESKTOP*/
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
