@extends('layouts.pago')

@section('content')



<main class="row">
  <div class="col-12">

     <div class="row mt-3">
        <div class="col-12 text-center">
            <a href="{{ route($pageModule .'.pagar') }}" >
                << Regresar a la principal
            </a>
        </div>
    </div>
  
    <div class="row mt-3">
      <div class="row">
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


                <table class="table table-bordered">
                    <tr>
                        <th class="text-center" colspan="2">Fechas reservadas</th>
                    </tr>
                    @foreach($rowsFechas as $f)
                        <tr>
                            <td class="text-center">
                              @if($f->active == 1)
                                  <span class="badge badge-primary"><i class="bi bi-calendar-check"></i> Reservado</span>
                              @elseif($f->active == 2)
                                  <span class="badge badge-success"><i class="bi bi-calendar-check"></i> Visitado</span>
                              @elseif($f->active == 3)
                                  <span class="badge badge-danger"><i class="bi bi-calendar-check"></i> No Asistio</span>
                              @endif
                            </td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($f->fecha)->translatedFormat('j \d\e F \d\e Y') }}</td>
                        </tr>
                    @endforeach
                </table>

              </div>
            </div>

          </div>

        </div>

      </div>

    </div>

  </div>
       

</main>
<style>
  /* Reservado */
.badge-primary {
    background-color: #0d6efd1a; /* Azul suave */
    color: #0d6efd;
    border: 1px solid #0d6efd40;
    padding: 4px 10px;
    border-radius: 15px;
    font-weight: 600;
    font-size: 0.85rem;
    display: inline-flex;
    align-items: center;
}

/* Visitado */
.badge-success {
    background-color: #1987541a; /* Verde suave */
    color: #198754;
    border: 1px solid #19875440;
    padding: 4px 10px;
    border-radius: 15px;
    font-weight: 600;
    font-size: 0.85rem;
    display: inline-flex;
    align-items: center;
}

/* No asistió */
.badge-danger {
    background-color: #dc35451a; /* Rojo suave */
    color: #dc3545;
    border: 1px solid #dc354540;
    padding:4px 10px;
    border-radius: 15px;
    font-weight: 600;
    font-size: 0.85rem;
    display: inline-flex;
    align-items: center;
}

</style>

@stop
