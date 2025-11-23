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

  
      <div class="container mt-3">

            @for($i = 1; $i <= 7; $i++)
          <div class="col-12">
              <div class="sbox">
                <div class="sbox-title ses-text-muted">
                    <h5><i class="fa fa-table"></i> <strong> 
                      @if($i == 1) Lunes
                      @elseif($i == 2) Martes
                      @elseif($i == 3) Miércoles
                      @elseif($i == 4) Jueves
                      @elseif($i == 5) Viernes
                      @elseif($i == 6) Sábado
                      @elseif($i == 7) Domingo
                      @endif  
                    </strong></h5>
                </div>
                <div class="sbox-content" style="min-height:350px;"> 

                   <form action="{{ route($pageModule.'.update',$id) }}" method="POST">
                        @csrf
                      <div class="row mt-3">
                          <input type="hidden" name="dia_semana" value="{{ $i }}" required>

                          <div class="col-3">
                              <div class="mb-3">
                                  <label class="form-label fw-bold ses-text-muted">Hora Inicio:</label>
                                  <input type="time" name="time_start" class="form-control" placeholder="00:00:00" required>
                              </div>
                          </div>

                          <div class="col-3">
                              <div class="mb-3">
                                  <label class="form-label fw-bold ses-text-muted">Aforo Máximo:</label>
                                  <input type="number" name="aforo_maximo"  class="form-control" placeholder="Aforo máximo" required>
                              </div>
                          </div>

                          <div class="col-3">
                              <div class="mb-3">
                                  <label class="form-label fw-bold ses-text-muted">Tiempo:</label>
                                  <select name="tiempo" class="form-control" required>
                                    <option value="60" selected>1 Hora</option>
                                    <option value="30">30 Minutos</option>
                                  </select>
                              </div>
                          </div>

                          <div class="col-3 text-center">
                            <label class="form-label fw-bold ses-text-muted">Guardar</label>
                            <div>
                              <button type="submit" name="save" class="btn btn-sm btn-primary ses-text-white"><i class="fa fa-save"></i></button>
                            </div>
                          </div>
                        </div>
                  </form>

                  @if(isset($rowsHorario[$i]))
                    <table class="table table-bordered">
                      <tr>
                        <th class="text-center">Hora Inicio</th>
                        <th class="text-center">Hora Fin</th>
                        <th class="text-center">Aforo Máximo</th>
                      </tr>
                      @foreach ($rowsHorario[$i] as $h)
                          <tr>
                            <td class="text-center">{{ $h['start'] }}</td>
                            <td class="text-center">{{ $h['end'] }}</td>
                            <td class="text-center">{{ $h['max'] }}
                            </td>
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

                </div>
              </div>
          </div>
          @endfor


          
      </div>
  </div>
       

</main>

@stop
