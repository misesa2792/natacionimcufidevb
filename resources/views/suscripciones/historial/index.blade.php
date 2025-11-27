


@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <main class="row">
        <div class="col-md-12">

            <div class="page-header">
                <div class="page-title">
                    <h4 class="text-blue-900"> <strong>{{ $pageTitle }}</strong> <small class="text-gray-400"><i>{{ $pageNote }}</i></small></h4>
                </div>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ URL::to('dashboard') }}"> <i class="fa fa-home"></i> </a></li>
                        <li class="breadcrumb-item">
                            <a href="{{ route($pageModule.'.index') }}" class="text-decoration-none"><i>{{ $pageTitle }}</i></a>
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="row mb-2">
                <div class="col-12">
                    <a href="{{ route($pageModule.'.index',['page' => request()->page]) }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                        <i class="bi bi-cash-coin"></i> Registrar pagos
                    </a>
                    <a href="{{ route($pageModule.'.reportepagos',['page' => request()->page]) }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                        <i class="bi bi-bar-chart-line-fill"></i> Gráfica de pagos
                    </a>
                    <a href="{{ route($pageModule.'.historialpagos') }}" class="btn btn-sm btn-primary rounded-pill ses-text-white">
                        <i class="bi bi-cash-stack"></i> Historial de pagos
                    </a>
                </div>
            </div>

            <div class="sbox">
                <div class="sbox-title">
                    <h5><i class="fa fa-table"></i> <strong>{{ $pageTitle }}</strong></h5>
                    <div class="sbox-tools">
                       
                    </div>
                </div>
                <div class="sbox-content">

                    <form method="GET" class="mb-2">


                        <div class="row ">
                            <div class="col-10">
                                <div class="ses-text-muted fw-bold">Nombre</div>
                                <input type="text" name="nombre" class="form-control" placeholder="Ingresa nombre">
                            </div>
                             <div class="col-auto">
                                <div class="ses-text-muted fw-bold">Paginación</div>
                                <select name="nopagina" class="form-control form-select-sm ses-fs-xs"
                                    onchange="this.form.submit()">
                                    @foreach ([10, 25, 50, 100] as $n)
                                        <option value="{{ $n }}" @selected(request('nopagina', 5) == $n)>
                                            {{ $n }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <div class="ses-text-muted fw-bold">Buscar</div>
                                <button type="submit" class="btn btn-xs btn-white">
                                    <i class="fa-solid fa-search"></i> Buscar
                                </button>
                            </div>
                            <div class="col-auto">
                                <div class="ses-text-muted fw-bold">limpiar</div>
                                <a href="{{ route($pageModule.'.historialpagos') }}" class="btn btn-xs btn-white"><i class="fa-solid fa-eraser"></i> Limpiar</a>
                            </div>
                        </div>
                    </form>

                    @if ($pagination->count())
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm align-middle mb-0 ses-table-pagos">
                            <thead class="table-light">
                                <tr class="text-muted text-uppercase small">
                                    <th class="text-center" width="40">#</th>
                                    <th>Alumno</th>
                                    <th>Plan</th>
                                    <th class="text-center">Mensualidad</th>
                                    <th class="text-center">Fecha pago</th>
                                    <th class="text-center">Estatus</th>
                                    <th class="text-center">Tipo de pago</th>
                                    <th>Tipo de descuento</th>
                                    <th class="text-end text-right">Descuento</th>
                                    <th class="text-end text-right">Subtotal</th>
                                    <th class="text-end text-right">Total</th>
                                </tr>
                            </thead>

                            <tbody>
                            @foreach ($pagination as $v)
                                @php
                                    $rowClass = $v->active == 1 ? 'sus-row-paid' : 'sus-row-pending';
                                @endphp
                                <tr class="{{ $rowClass }}">
                                    <td class="text-center fw-semibold">{{ ++$j }}</td>
                                    <td class="fw-semibold text-dark">{{ $v->alumno }}</td>
                                    <td class="text-muted">
                                        {{ $v->nivel }}
                                        <div><em class="text-gray-600">{{ $v->plan }}</em></div>
                                    </td>
                                    <td class="text-center">{{ $v->mes }}</td>
                                    <td class="text-center">{{ $v->fecha_pago }}</td>
                                    <td class="text-center">
                                        @if($v->active == 1)
                                            <span class="badge rounded-pill bg-success ses-badge-soft">
                                                <i class="bi bi-cash-stack me-1"></i> Pagado
                                            </span>
                                        @else 
                                            <span class="badge rounded-pill bg-warning text-dark ses-badge-soft">
                                                <i class="bi bi-hourglass-split me-1"></i> Pendiente
                                            </span>
                                            <div><i>Sin evidencia</i></div>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $v->tipo_pago }}</td>
                                    <td class="text-muted">{{ $v->desc_descuento }} {{ $v->porc_descuento }}%</td>
                                    <td class="text-end text-right small text-danger">${{ number_format($v->descuento, 2) }}</td>
                                    <td class="text-end text-right small">${{ number_format($v->monto_general, 2) }}</td>
                                    <td class="text-end text-right fw-bold text-dark">${{ number_format($v->monto_pagado, 2) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="border rounded text-center text-muted py-5">
                        <p class="mb-3">No hay registros para mostrar.</p>
                    </div>
                @endif

                <div class="row mt-3">
                    <div class="col-12 text-right">
                        {{ $pagination->appends(request()->query())->onEachSide(2)->links('pagination::bootstrap-5') }}
                    </div>
                </div>
                

                </div>

               
            </div>



        </div>
    </main>

<style>
.ses-table-pagos tbody tr:hover {
    background-color: #f9fbff;
}
/* Fila pagada / pendiente */
.sus-row-paid {
    background-color: #f5fff7;
}
.sus-row-pending {
    background-color: #fff9e6;
}
/* Suavizar badges de estatus */
.ses-badge-soft {
    padding: 0.25rem 0.75rem;
    font-size: 0.70rem;
}
/* Opcional: bordes redondeados a la tabla */
.ses-table-pagos {
    border-radius: 10px;
    overflow: hidden;
}
</style>

@stop
