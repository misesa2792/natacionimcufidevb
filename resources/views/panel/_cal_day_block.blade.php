<div class="ses-gcal-day">
    <div class="ses-gcal-day-header d-none d-md-flex">
        {{-- ESTA CABECERA SOLO SE USA EN DESKTOP,EN MOBILE YA TENEMOS OTRA EN LA TARJETA --}}
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
                        $ocupados    = $h['ocupados'];
                        $disponibles = $h['disponibles'];

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
                        title="{{ $estadoTexto }}"
                    >
                        @if ($clickable)
                            <input type="radio"
                                name="idplan_horario[]"
                                value='@json([
                                    "idplan_horario" => $h["idplan_horario"],
                                    "fecha"          => $date->format("Y-m-d"),
                                ])'
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
