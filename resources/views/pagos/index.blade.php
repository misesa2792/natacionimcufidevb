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
                            <a href="{{ route('pagos.index') }}" class="text-decoration-none"><i>{{ $pageTitle }}</i></a>
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="container sbox">
                <div class="sbox-title">
                    <h5><i class="fa fa-table"></i> <strong>{{ $pageTitle }}</strong></h5>
                    <div class="sbox-tools"></div>
                </div>
                <div class="sbox-content">

                    <form method="GET" class="mb-2">


                        <div class="row ">
                            <div class="col-10">
                                <div class="ses-text-muted fw-bold">Año</div>
                                <select name="idyear" class="form-control">
                                    <option value="5" @selected(request('idyear') == 5)>2025</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <input type="hidden" name="nopagina" value="12">
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
                        <table class="table table-striped table-hover bg-white">
                            <thead>
                                <tr>
                                    <th width="30">#</th>
                                    <th class="text-center" width="40">Estatus</th>
                                    <th class="text-left">Mes</th>
                                    <th class="text-center">Importe</th>
                                    <th class="text-center">Tipo de pago</th>
                                    <th class="text-center">Fecha Pago</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                                @foreach ($pagination as $v)
                                    <tr>
                                        <td class="text-center">{{ ++$j }}</td>
                                        <td class="text-center">
                                            @if($v['ide'] == 1)
                                                <span class="badge badge-danger">Sin pagar</span>
                                            @elseif($v['ide'] == 2)
                                                <span class="badge badge-success">Pagado</span>
                                            @elseif($v['ide'] == 3)
                                                <span class="badge badge-warning">Receso</span>
                                            @elseif($v['ide'] == 4)
                                                <span class="badge badge-secondary">No aplica</span>
                                            @elseif($v['ide'] == 5)
                                                <span class="badge badge-dark">Condonación</span>
                                            @endif
                                        </td>
                                        <td>{{ $v['mes'] }}</td>
                                        <td class="text-center">{{ $v['total'] }}</td>
                                        <td class="text-center">{{ $v['tipo_pago'] }}
                                            @if(!empty($v['code']))
                                                <div><strong>Referencia: </strong>{{ $v['code'] }}</div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div><span>{{ $v['rg_fecha'] }}</span><div>
                                            <div><i>{{ $v['rg_hora'] }}</i><div>
                                        </td>
                                        <td class="text-center ">
                                            @if($v['ide'] == 1)
                                                <a href="{{ route('pagos.view', $v['idpagos']) }}" class="btn btn-xs btn-outline-danger">
                                                    <i class="bi bi-cash-coin"></i> Realizar Pago
                                                </a>
                                            @else
                                                <a href="{{ route('pagos.pdf', $v['idpagos']) }}" class="btn btn-xs btn-outline-success" target="_blank">
                                                    <i class="bi bi-cash-coin"></i> Descargar PDF
                                                </a>
                                            @endif
                                        </td>
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
            </div>


        </div>

        
    
    </main>



@stop
