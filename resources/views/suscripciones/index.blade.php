@extends('layouts.app')

@section('content')

    <main class="row mt-3">
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


                        @if ($pagination->count())
                        <table class="table table-bordered table-hover bg-white">
                            <thead>
                                <tr>
                                    <th rowspan="2" width="30">#</th>
                                    <th rowspan="2" class="text-center" width="40">Estatus</th>
                                    <th rowspan="2" class="text-center">Nadador</th>
                                    <th rowspan="2" class="text-center">Plan</th>
                                    <th class="text-center" colspan="7">Suscripciones</th>
                                    <th rowspan="2" class="text-center">Acción</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Estatus</th>
                                    <th class="text-center">Plan contratado</th>
                                    <th class="text-center">Tipo de Pago</th>
                                    <th class="text-center">Total de visitas permitidas por plan</th>
                                    <th class="text-center">Fecha Inicio</th>
                                    <th class="text-center">Fecha Fin</th>
                                    <th class="text-center">Monto</th>
                                </tr>
                            </thead>
                             @foreach ($pagination as $v)
                                    <tr>
                                        <td class="text-center">{{ ++$j }}</td>
                                        <td>
                                            @if($v['active'] == 1)
                                                <span class="badge badge-success">Activo</span>
                                            @else
                                                <span class="badge badge-danger">Inactivo</span>
                                            @endif
                                        </td>
                                        <td>{{ $v['nombre'] }}</td>
                                        <td class="text-center">{{ $v['plan'] }}</td>
                                        @if(empty($v['suscripcion']))
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">
                                                <a href="{{ route($pageModule . '.view', $v['id']) }}"
                                                    class="btn btn-xs btn-white">
                                                    Abrir <i class="bi bi-arrow-right"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td class="text-center {{ $v['suscripcion']->estado == 'ACTIVA' ? 'table-success' : 'table-danger' }}">{{ $v['suscripcion']->estado }}</td>
                                            <td class="text-center">{{ $v['suscripcion']->plan }}</td>
                                            <td class="text-center">{{ $v['suscripcion']->pago }}</td>
                                            <td class="text-center">{{ $v['suscripcion']->max_visitas_mes }}</td>
                                            <td class="text-center">{{ $v['suscripcion']->fi }}</td>
                                            <td class="text-center">{{ $v['suscripcion']->ff }}</td>
                                            <td class="text-center">{{ $v['suscripcion']->monto }}</td>
                                            <td class="text-center">
                                                <a href="{{ route($pageModule . '.view', $v['id']) }}"
                                                    class="btn btn-xs btn-white">
                                                    Abrir <i class="bi bi-arrow-right"></i>
                                                </a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                        </table>
                    @else
                        {{-- Vista alternativa sin tabla cuando no hay registros --}}
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

   

@stop
