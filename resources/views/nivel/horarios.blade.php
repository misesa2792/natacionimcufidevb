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

    <div class="row">
      <div class="col-12">
        <a href="{{ route($pageModule.'.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
            <i class="fa fa-arrow-left me-1"></i> Regresar
        </a>
      </div>
    </div>


      <div class="row">
        <div class="col-12">
            <div class="container">
              <div class="col-12">

                 <form action="{{ route($pageModule.'.update', ['id' => $id]) }}" method="POST">
                  @csrf

                  <table class="table table-bordered bg-white">
                    <tr>
                        <th class="text-center table-secondary">Horario</th>
                        @foreach($diasNombres as $nombreDia)
                            <th class="text-center table-secondary">{{ $nombreDia }}</th>
                        @endforeach
                    </tr>
                    @foreach($matrix as $hora => $dias)
                        <tr>
                            <td class="text-center table-secondary">{{ \Carbon\Carbon::parse($hora)->format('H:i A') }}</td>

                            @for($d = 1; $d <= 7; $d++)
                                <td class="text-center">
                                    @if(!empty($dias[$d]))
                                        <span class="badge bg-success">✔</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            @endfor
                        </tr>
                    @endforeach

                    <tr>
                      <td class="text-center">
                          <input type="time" name="tiempo" class="form-control" required>
                      </td>
                      <td class="text-center"><input type="checkbox" name="horarios[1]"></td>
                      <td class="text-center"><input type="checkbox" name="horarios[2]"></td>
                      <td class="text-center"><input type="checkbox" name="horarios[3]"></td>
                      <td class="text-center"><input type="checkbox" name="horarios[4]"></td>
                      <td class="text-center"><input type="checkbox" name="horarios[5]"></td>
                      <td class="text-center"><input type="checkbox" name="horarios[6]"></td>
                      <td class="text-center"><input type="checkbox" name="horarios[7]"></td>
                      <td width="50" class="text-center">
                        <button type="submit" name="save" class="btn btn-xs btn-primary ses-text-white"><i class="fa fa-save"></i></button>
                      </td>
                    </tr>
                    
              </table>

              </form>

              </div>
            </div>

        </div>
        </div>
          
      </div>
  </div>
       

</main>

@stop
