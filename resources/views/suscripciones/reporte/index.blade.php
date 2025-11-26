


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
                    <a href="{{ route($pageModule.'.reportepagos',['page' => request()->page]) }}" class="btn btn-sm btn-primary rounded-pill ses-text-white">
                        <i class="bi bi-bar-chart-line-fill"></i> Gráfica de pagos
                    </a>
                    <a href="{{ route($pageModule.'.historialpagos') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                        <i class="bi bi-cash-stack"></i> Historial de pagos
                    </a>
                </div>
            </div>

            
           <div class="row mt-3">

    {{-- PAGOS DEL DÍA --}}
    <div class="col-md-6 mb-3">
        <div class="card shadow-sm border-0 ses-card">
            <div class="card-body">
                <h5 class="text-center fw-bold mb-3">PAGOS DEL DÍA</h5>

                <div class="d-flex justify-content-center align-items-center gap-2 mb-3 flex-wrap">
                    <input type="date"
                           name="fecha_dia"
                           class="form-control form-control-sm w-auto"
                           value="{{ now()->format('Y-m-d') }}">

                    <button class="btn btn-primary btn-sm">
                        <i class="fa fa-search"></i> Buscar
                    </button>

                    <button class="btn btn-outline-danger btn-sm">
                        <i class="fa fa-eye"></i> Ver
                    </button>
                </div>

                <div class="row text-center mb-1 g-2">
                    <div class="col-4">
                        <div class="ses-pill-box">
                            <div class="ses-pill-label">Ventanilla
                                <div><small>Pagado</small></div>
                            </div>
                            <a href="#" class="ses-pill-value">0.00</a>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="ses-pill-box">
                            <div class="ses-pill-label">Transferencia
                                <div><small>Pagado</small></div>
                            </div>
                            <a href="#" class="ses-pill-value">0.00</a>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="ses-pill-box">
                            <div class="ses-pill-label">Pendientes
                                <div><small>Sin evidencia de orden de pago</small></div>
                            </div>
                            <a href="#" class="ses-pill-value">0.00</a>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <span class="ses-total-monto">$ 0.00</span>
                </div>
            </div>
        </div>
    </div>

    {{-- PAGOS DEL MES --}}
    <div class="col-md-6 mb-3">
        <div class="card shadow-sm border-0 ses-card">
            <div class="card-body">
                <h5 class="text-center fw-bold mb-3">PAGOS DEL MES DE</h5>

                <div class="d-flex justify-content-center">
                    <select name="mes" class="form-select form-select-sm w-auto" id="idmes">
                        <option value="1">Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11" selected>Noviembre</option>
                        <option value="12">Diciembre</option>
                    </select>
                </div>

                <div class="row text-center mb-1 g-2">
                    <div class="row text-center mb-2 g-2">
                    <div class="col-4">
                        <div class="ses-pill-box">
                            <div class="ses-pill-label">Ventanilla
                                <div><small>Pagado</small></div>
                            </div>
                            <a href="#" class="ses-pill-value" id="mes_efectivo">0.00</a>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="ses-pill-box">
                            <div class="ses-pill-label">Transferencia
                                <div><small>Pagado</small></div>
                            </div>
                            <a href="#" class="ses-pill-value" id="mes_transferencia">0.00</a>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="ses-pill-box">
                            <div class="ses-pill-label">Pendientes
                                <div><small>Sin evidencia de orden de pago</small></div>
                            </div>
                            <a href="#" class="ses-pill-value" id="mes_pendiente">0.00</a>
                        </div>
                    </div>
                </div>
                </div>

                <div class="text-center">
                    <span class="ses-total-monto" id="mes_total">0.00</span>
                </div>
            </div>
        </div>
    </div>


    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 ses-card">
                <div class="card-body">
                    <h5 class="text-center fw-bold mb-3">Calendario de pagos del mes por día</h5>
                    <canvas id="pagosMesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>



        </div>
    </main>
<style>
    #pagosMesChart {
    height: 300px !important;
}
</style>
    @push('js')
<script>
  $(function () {
    // Declarar primero, antes de cualquier uso
      let pagosChart = null;
      // 1. Inicializar la gráfica
      initChart();

      // 2. Cargar datos iniciales
      loadInfo();

      // 3. Volver a cargar cuando cambie el mes
      $('#idmes').on('change', function () {
          loadInfo();
      });;

      function loadInfo(){
        let idmes = $("#idmes").val();
        $("#mes_efectivo").empty().append("Cargando...");
        $("#mes_transferencia").empty().append("Cargando...");
        $("#mes_pendiente").empty().append("Cargando...");
        $("#mes_total").empty().append("Cargando...");
        
        axios.get("{{ route($pageModule.'.dashboard') }}", {
              params: {
                  idyear: "{{ $idyear }}",
                  idmes: idmes
              }
          })
          .then((resp) => {
            let row = resp.data;
            if(row.status == "success"){
                $("#mes_efectivo").empty().append("$"+row.data.mes_e);
                $("#mes_transferencia").empty().append("$"+row.data.mes_t);
                $("#mes_pendiente").empty().append("$"+row.data.mes_p);
                $("#mes_total").empty().append("$"+row.data.mes_total);
                
                if (row.calendario && pagosChart) {
                   updateChart(row.calendario);
                }
            }
            console.log(resp.data);
          })
          .catch((err) => {
          });
      }
      function updateChart(calendario) {
        if (!pagosChart || !calendario) return;

        pagosChart.data.labels = calendario.labels ?? [];
        pagosChart.data.datasets[0].data = calendario.montos ?? [];
        pagosChart.update();
    }

      function initChart() {
            const canvas = document.getElementById('pagosMesChart');
            const ctx = canvas.getContext('2d');
            // Destruir la gráfica anterior si ya existe
            if (pagosChart !== null) {
                pagosChart.destroy();
            }

          pagosChart = new Chart(ctx, {
              type: 'bar',
              data: {
                  labels: [],
                  datasets: [{
                      label: 'Pagos del mes',
                      data: [],
                      backgroundColor: 'rgba(54, 162, 235, 0.3)',
                      borderColor: 'rgba(54, 162, 235, 1)',
                      borderWidth: 1
                  }]
              },
              options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false, // ayuda a que el tooltip salga aunque no estés justo encima de la barra
                    },
                    plugins: {
                        tooltip: {
                            enabled: true,
                        },
                    },
              }
          });
      }

   

  });
    
</script>
@endpush

<style>
    .ses-card {
    border-radius: 14px;
    background-color: #ffffff;
}

.ses-pill-box {
    background-color: #f7f7f7;
    border-radius: 10px;
    padding: 10px 5px;
}

.ses-pill-label {
    font-size: 0.85rem;
    color: #777;
    margin-bottom: 4px;
}

.ses-pill-value {
    font-weight: 600;
    font-size: 0.90rem;
    color: #007bff;
    text-decoration: none;
}

.ses-pill-value:hover {
    text-decoration: underline;
}

.ses-total-monto {
    font-size: 1.8rem;
    font-weight: 800;
    color: #0ba20b; /* verde */
}

</style>
    
@stop
