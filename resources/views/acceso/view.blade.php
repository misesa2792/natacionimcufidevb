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

              </div>
            </div>

          </div>


          @php
              $now          = \Carbon\Carbon::now();
              $year         = $now->year;
              $currentMonth = $now->month;
          @endphp

        <div class="col-12">

          <div class="sbox">
            <div class="sbox-title ses-text-muted">
                <div class="row">
                  <div class="col-8">
                    <h5>
                        <i class="bi bi-calendar3 me-1"></i>
                        <strong> Calendario de meses</strong>
                    </h5>
                  </div>
                  <div class="col-4 text-right ses-year-label"><strong>{{ $year }}</strong></div>
                </div>
            </div>

            <div class="sbox-content">
              @php
                  $nowMonth = now()->month; // 1-12
                  $nowYear  = now()->year;  // 2025, etc.
              @endphp
              <div class="row g-2 ses-month-grid">
                  @foreach($meses as $idmes => $mes)
                      @php
                          $monthDate = \Carbon\Carbon::create($year, $idmes, 1);
                          $pagado = isset($pagos[$idmes]);
                          $isCurrent = $idmes === $currentMonth;
                          $isCurrentYear = ($year == $nowYear); // si usas año real
                          $isPastMonth   = $isCurrentYear && ($idmes < $nowMonth);
                      @endphp
                      <div class="col-4 col-md-3">
                         @if($isPastMonth)
                            @if($pagado)
                                <a href="{{ route($pageModule . '.informacion',['curp' => $row->curp, 'idy' => $idyear,'idm' => $idmes]) }}"
                                  class="ses-month-card-success {{ $isCurrent ? 'is-current' : '' }}">
                                  <span class="ses-month-number">{{ str_pad($idmes, 2, '0', STR_PAD_LEFT) }}</span>
                                  <span class="ses-month-name text-capitalize">{{ $mes }}</span>
                                  <div>
                                    <i class="bi bi-cash-coin me-1 ses-month-name"></i> 
                                    <span class="ses-month-name text-capitalize">Pagado</span>
                                  </div>
                                </a>
                            @else
                                <span class="ses-month-card" title="No se puede pagar meses anteriores">
                                   <span class="ses-month-number">{{ str_pad($idmes, 2, '0', STR_PAD_LEFT) }}</span>
                                    <span class="text-capitalize">{{ $mes }}</span>
                                    <div class="ses-month-name "><i class="bi bi-lock-fill me-1"></i> Cerrado</div>
                                </span>
                            @endif
                         @else 

                            @if($pagado)
                                <a href="{{ route($pageModule . '.informacion',['curp' => $row->curp, 'idy' => $idyear,'idm' => $idmes]) }}"
                                  class="ses-month-card-success {{ $isCurrent ? 'is-current' : '' }}">
                                  <span class="ses-month-number">{{ str_pad($idmes, 2, '0', STR_PAD_LEFT) }}</span>
                                  <span class="ses-month-name text-capitalize">{{ $mes }}</span>
                                  <div>
                                    <i class="bi bi-cash-coin me-1 ses-month-name"></i> 
                                    <span class="ses-month-name text-capitalize">Pagado</span>
                                  </div>
                                </a>
                            @else
                                <a href="{{ route($pageModule . '.informacion',['curp' => $row->curp, 'idy' => $idyear,'idm' => $idmes]) }}"
                                class="ses-month-card-danger {{ $isCurrent ? 'is-current' : '' }}">
                                  <span class="ses-month-number">{{ str_pad($idmes, 2, '0', STR_PAD_LEFT) }}</span>
                                  <span class="ses-month-name text-capitalize">{{ $mes }}</span>
                                  <div>
                                    <i class="bi bi-cash-coin me-1 ses-month-name"></i> 
                                    <span class="ses-month-name text-capitalize">Pagar</span>
                                  </div>
                              </a>
                            @endif
                                
                            
                         @endif
                      </div>
                  @endforeach
              </div>
          </div>
      </div>


        </div>

      </div>

    </div>

  </div>
       

</main>

<style>
/* ===== MINI CALENDARIO DE MESES ===== */

.ses-year-label {
    font-size: 1rem;
    font-weight: 700;
    color: #111827;
}

.ses-year-sub {
    font-size: 0.75rem;
}

/* Contenedor de los meses */
.ses-month-grid {
    margin-top: 4px;
}
.ses-month-card-danger {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
    gap: 2px;
    width: 100%;
    padding: 8px 10px;
    border-radius: 10px;
    border: 1px solid #fd9999;
    background: #f7c2c2;
    text-decoration: none;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    transition: background 0.15s ease, box-shadow 0.15s ease,
                transform 0.12s ease, border-color 0.15s ease;
}
.ses-month-card-success {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
    gap: 2px;
    width: 100%;
    padding: 8px 10px;
    border-radius: 10px;
    border: 1px solid #8aec7d;
    background: #c8f7c2;
    text-decoration: none;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    transition: background 0.15s ease, box-shadow 0.15s ease,
                transform 0.12s ease, border-color 0.15s ease;
}
/* Tarjeta de mes */
.ses-month-card {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
    gap: 2px;
    width: 100%;
    padding: 8px 10px;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
    background: #f9fafb;
    text-decoration: none;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    transition: background 0.15s ease, box-shadow 0.15s ease,
                transform 0.12s ease, border-color 0.15s ease;
}

.ses-month-card:hover {
    background: #eff6ff;
    border-color: #bfdbfe;
    box-shadow: 0 3px 6px rgba(37, 99, 235, 0.15);
    transform: translateY(-1px);
}

/* Número grande del mes */
.ses-month-number {
    font-size: 0.95rem;
    font-weight: 700;
    color: #111827;
}

/* Nombre del mes */
.ses-month-name {
    font-size: 0.75rem;
    color: #6b7280;
}






















  .badge {
    display: inline-block;
    padding: 2px 5px;
    font-size: 10px;
    font-weight: 600;
    border-radius: 12px;
    color: #fff;
    letter-spacing: .3px;
}

.badge-primary {
    background-color: #0069d9;
}

.badge-success {
    background-color: #28a745;
}

.badge-dark {
    background-color: #343a40;
}

/* Opcional: efectos */
.badge-primary,
.badge-success,
.badge-dark {
    box-shadow: 0 1px 3px rgba(0,0,0,0.15);
}

</style>

@stop
