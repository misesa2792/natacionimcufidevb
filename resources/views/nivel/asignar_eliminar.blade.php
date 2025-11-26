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
              onclick="location.href='{{ route($pageModule.'.horarios', ['id' => $id]) }}'" class="btn btn-sm btn-white">
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
        @php
          function toAMPM($hour) {
              return \Carbon\Carbon::createFromFormat('H:i', $hour)->format('h:i A');
          }
        @endphp
          <div class="col-12">
              <div class="sbox">
                <div class="sbox-title ses-text-muted">
                    <h5><i class="fa fa-table"></i> <strong> 
                     Horarios del día @if($dia == 1) Lunes
                      @elseif($dia == 2) Martes
                      @elseif($dia == 3) Miércoles
                      @elseif($dia == 4) Jueves
                      @elseif($dia == 5) Viernes
                      @elseif($dia == 6) Sábado
                      @elseif($dia == 7) Domingo
                      @endif  
                    </strong></h5>
                </div>
                <div class="sbox-content" style="min-height:350px;"> 

                      <div class="row mt-3">
                          <input type="hidden" name="dia_semana" value="{{ $dia }}" required>
                          <input type="hidden" name="tiempo" value="60" required>


                            <table class="table table-bordered bg-white">
    <thead>
        <tr>
            <th>Escribe el horario</th>
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

        <tr>
            <td class="text-center">
                <input type="time" name="horarios[hora]" class="form-control">
            </td>

              <td class="text-center"><input type="checkbox" name="horarios[1]"></td>
            <td class="text-center"><input type="checkbox" name="horarios[2]"></td>
            <td class="text-center"><input type="checkbox" name="horarios[3]"></td>
            <td class="text-center"><input type="checkbox" name="horarios[4]"></td>
            <td class="text-center"><input type="checkbox" name="horarios[5]"></td>
            <td class="text-center"><input type="checkbox" name="horarios[6]"></td>
            <td class="text-center"><input type="checkbox" name="horarios[7]"></td>
        </tr>

    </tbody>
</table>

                                <div class="table-responsive">
                                    <table class="table table-hover">
                                            <tr>
                                                <th width="50">Activo</th>
                                                <th>Horario</th>
                                            </tr>
                                              @for ($h = 0; $h < 24; $h++)
                                                @php
                                                    $hi24 = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';      // 00:00
                                                    $hf24 = str_pad($h+1, 2, '0', STR_PAD_LEFT) . ':00';    // 01:00

                                                    $hi12 = toAMPM($hi24);     // 12:00 AM, 01:00 AM, etc.
                                                    $hf12 = toAMPM($hf24);     // 01:00 AM, 02:00 AM, etc.
                                                @endphp

                                                <tr>
                                                    <td class="text-center"><input type="checkbox" class="form-check-input" name="time_start[{{ $hi24 }}]"></td>
                                                    <td class="text-left">{{ $hi12.' - '.$hf12 }}</td>
                                                </tr>
                                            @endfor
                                    </table>
                                </div>
                          
                            <div class="row">
                              <div class="col-12 text-center">
                            </div>
                          </div>
                        </div>

                </div>
              </div>
          </div>
          
      </div>
  </div>
       

</main>

@stop
