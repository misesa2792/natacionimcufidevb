


@extends('layouts.app')

@section('content')

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
                    <a href="{{ route($pageModule.'.index',['page' => request()->page]) }}" class="btn btn-sm btn-primary rounded-pill ses-text-white">
                        <i class="bi bi-cash-coin"></i> Registrar pagos
                    </a>
                    <a href="{{ route($pageModule.'.reportepagos') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                        <i class="bi bi-bar-chart-line-fill"></i> Gráfica de pagos
                    </a>
                    <a href="{{ route($pageModule.'.historialpagos') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
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
                                <a href="{{ route($pageModule.'.index') }}" class="btn btn-xs btn-white"><i class="fa-solid fa-eraser"></i> Limpiar</a>
                            </div>
                        </div>
                    </form>

                    @php
                        $nowMonth = now()->month; // 1-12
                        $nowYear  = now()->year;  // 2025, etc.
                    @endphp

                    @if ($pagination->count())
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm align-middle mb-0 ses-table-pagos">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" width="40">#</th>
                                        <th>Alumno</th>
                                        <th>Nivel/Descuento</th>
                                        @foreach($meses as $mes)
                                            <th class="text-center ses-th-mes">{{ $mes }}</th>
                                        @endforeach
                                        {{-- <th class="text-center">Acción</th> si luego la usas --}}
                                    </tr>
                                </thead>

                                <tbody>
                                @foreach ($pagination as $v)
                                    <tr>
                                        <td class="text-center fw-bold">{{ ++$j }}</td>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $v['nombre'] }}</div>
                                        </td>
                                        <td>
                                            <div class="text-secondary">{{ $v['nivel'] }} (<i>{{ $v['plan'] }}</i>) </div>
                                            <div class="mt-1"><small>{{ $v['descuento'] }}</small> </div>
                                        </td>

                                        @foreach($meses as $idmes => $mes)
                                            <td class="text-center">
                                                @php
                                                    $pagado       = isset($v['rowsPagos'][$idmes]);
                                                    $isCurrentYear = ($year == $nowYear); // si usas año real
                                                    $isPastMonth   = $isCurrentYear && ($idmes < $nowMonth);
                                                @endphp

                                                @if($isPastMonth)
                                                    {{-- Mes pasado del año actual: solo consulta, no permite pagar --}}
                                                    @if($pagado)
                                                         <a href="{{ route($pageModule . '.detail', ['ids' => $v['rowsPagos'][$idmes]['id'], 'page' => request()->page ]) }}"
                                                            class="ses-pill ses-pill-past text-decoration-none"
                                                        target="_blank">
                                                            <i class="bi bi-pencil-fill"></i> Registrado
                                                        </a>
                                                    @else
                                                        <span class="ses-pill ses-pill-gray" title="No se puede pagar meses anteriores">
                                                            <i class="bi bi-lock-fill me-1"></i> Cerrado
                                                        </span>
                                                    @endif

                                                @else
                                                    {{-- Mes actual o futuro: aquí sí permites pago / ver pago --}}
                                                    @if($pagado)

                                                        @if($v['rowsPagos'][$idmes]['active'] == 2)
                                                            <a href="{{ route($pageModule . '.detail', ['ids' => $v['rowsPagos'][$idmes]['id'], 'page' => request()->page ]) }}"
                                                            class="ses-pill ses-pill-past text-decoration-none">
                                                            <i class="bi bi-pencil-fill"></i> Registrado
                                                            </a>
                                                        @else 
                                                            <a href="{{ route($pageModule . '.detail', ['ids' => $v['rowsPagos'][$idmes]['id'], 'page' => request()->page ]) }}"
                                                            class="ses-pill ses-pill-paid text-decoration-none">
                                                            <i class="bi bi-cash-coin me-1"></i> Pagado
                                                            </a>
                                                        @endif

                                                        
                                                    @else
                                                        @if(!empty($v['plan']))
                                                            <a href="{{ route($pageModule . '.view', ['id' => $v['id'], 'idy' => $idyear, 'idm' => $idmes, 'page' => request()->page ]) }}"
                                                            class="ses-pill ses-pill-pending text-decoration-none">
                                                                <i class="bi bi-plus-circle me-1"></i> Pagar
                                                            </a>
                                                        @else 
                                                            Sin Nivel
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>
                                        @endforeach
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


                </div>

                 <div class="row mt-3">
                    <div class="col-12 text-right">
                        {{ $pagination->appends(request()->query())->onEachSide(2)->links('pagination::bootstrap-5') }}
                    </div>
                </div>
                
            </div>
        </div>
    </main>


@push('css')
<style>
.ses-table-pagos thead th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: .03em;
        white-space: nowrap;
    }

    .ses-th-mes {
        min-width: 75px;
    }

    .ses-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.15rem 0.55rem;
        border-radius: 999px;
        font-size: 0.70rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .ses-pill-gray {
         background-color: #f2f2f2;
        color: #6c757d;
        border: 1px solid #d6d6d6;
    }

    .ses-pill-paid {
        background-color: #e8f7ee;
        color: #198754;
        border: 1px solid #c5ead5;
    }

    .ses-pill-pending {
        background-color: #fde8e8;
        color: #c0392b;
        border: 1px solid #f5c2c2;
    }

    .ses-badge-nivel {
        font-size: 0.70rem;
        font-weight: 600;
        padding: .25rem .55rem;
    }
    .ses-pill-past-paid {
    background-color: #eceff4;
    color: #2980b9;
    border: 1px solid #d0d7e2;
    font-size: 0.70rem;
    border-radius: 999px;
    padding: 0.15rem 0.55rem;
}

.ses-pill-past {
    background-color: #f5e6d8;
    color: #b35b16;
    border: 1px solid #e8c9a5;
    font-size: 0.70rem;
    border-radius: 999px;
    padding: 0.15rem 0.55rem;
}

    @media (max-width: 1200px) {
        .ses-th-mes {
            min-width: 60px;
            font-size: 0.7rem;
        }
    }

</style>
@endpush


@stop
