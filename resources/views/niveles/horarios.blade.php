@extends('layouts.app')

@section('content')



<main class="row">
  <div class="col-12">
    <div class="page-header">
      <div class="page-title">
          <h4 class="text-blue-900"> <strong>{{ $pageTitle }}</strong> <small class="text-gray-400"><i>{{ $pageNote }}</i></small></h4>
      </div>
      
      <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ URL::to('dashboard') }}"> <i class="fa fa-home s-18"></i> </a></li>
              <li class="breadcrumb-item">
                  <a href="{{ route($pageModule.'.index') }}" class="text-decoration-none"><i>{{ $pageTitle }}</i></a>    
              </li>
              <li class="breadcrumb-item active"><i class="ses-text-muted">Agregar</i></li>
          </ol>
      </nav>
    </div>

    <div class="row">
      <div class="col-12">
        <div class="mt-3">
          <button type="button"
              onclick="location.href='{{ route($pageModule.'.index') }}'" class="btn btn-sm btn-white">
              <i class="fa fa-arrow-circle-left"></i> Regresar
            </button>
        </div>
      </div>
    </div>


    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="error">
            @foreach($errors->all() as $error)
                • {{ $error }}<br>
            @endforeach
        </div>
    @endif

  
      <div class="row mt-3">

        <div class="col-12">
          <table class="table table-bordered bg-white">
            <tr>
              @for($i = 1; $i <= 7; $i++)
              <th class="text-center">
                @if($i == 1) Lunes
                @elseif($i == 2) Martes
                @elseif($i == 3) Miércoles
                @elseif($i == 4) Jueves
                @elseif($i == 5) Viernes
                @elseif($i == 6) Sábado
                @elseif($i == 7) Domingo
                @endif
              </th>
              <th width="30">
                <a href="{{ route($pageModule . '.asignar', ['id' => $id, 'dia' => $i]) }}"
                    class="btn btn-xs btn-primary ses-text-white">
                    <i class="bi bi-calendar-plus"></i>
                </a>
              </th>
              @endfor
            
            </tr>
            <tr>
              @for($i = 1; $i <= 7; $i++)
              <td colspan="2">
                @if(isset($rowsHorario[$i]))
                    <table class="table table-bordered">
                      <tr>
                        <th class="text-center">Horario</th>
                        <th class="text-center">Aforo Máximo</th>
                      </tr>
                      @foreach ($rowsHorario[$i] as $h)
                          <tr>
                            <td class="text-center">{{ $h['start'].' - '.$h['end'] }}</td>
                            <td class="text-center">{{ $h['max'] }}</td>
                          </tr>
                      @endforeach
                    </table>
                  @else 
                    <div class="row text-center mt-3">
                      <i class="bi bi-calendar-x fa-3x text-muted"></i>
                      <h5 class="mt-3 text-muted">
                          Sin Horario
                      </h5>
                    </div>
                  @endif
              </td>
              @endfor
            </tr>
          </table>
        </div>
          
      </div>
  </div>
       

</main>

@stop
