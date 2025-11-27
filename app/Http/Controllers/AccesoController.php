<?php

namespace App\Http\Controllers;

use App\Models\Suscripciones;
use App\Models\Reserva;
use App\Models\Year;
use App\Models\Nadadores;
use App\Models\Reservatemporal;
use Carbon\Carbon;

use App\Services\SecureTokenService;
use App\Services\UtilsService;

use Carbon\CarbonPeriod;

use Illuminate\Http\Request;

class AccesoController extends Controller
{
    protected $data = [];	
    protected $model;	
	public $module = 'acceso';
    protected SecureTokenService $secureToken;
    protected UtilsService $utils;

    public function __construct(Suscripciones $model, SecureTokenService $secureToken, UtilsService $utils)
    {
        $this->model = $model;
        $this->secureToken = $secureToken;
        $this->utils = $utils;

        $this->data = ['pageTitle'	=> 	"Suscripciones",
                        'pageNote'	    =>  "Lista de suscripciones",
                        'pageModule'    => $this->module
                    ];
    }

    public function index(Request $request)
    {
        return view($this->module.'.index',$this->data);
    }
    public function pagar(Request $request)
    {
        return view($this->module.'.index',$this->data);
    }
    public function registrar(Request $request)
    {
        $this->data['rowsGenero'] = Nadadores::catalogoGenero();
        $this->data['rowsParentesco'] = Nadadores::catalogoParentesco();
        $this->data['rowsPlan'] = Nadadores::catalogoPlan();
        return view($this->module.'.registrar',$this->data);
    }
    public function store(Request $request, $id = 0)
    {
        $validated = $request->validate([
            'nombre'            => 'required',
            'fecha_nacimiento'  => 'required',
            'edad'              => 'required|integer|max:99',
            'idgenero'          => 'required',
            'domicilio'         => 'required',
            'curp'              => 'required|size:18',
            'idplan'            => 'required',
            'titular_nombre'     => 'required',
            'titular_telefono'   => 'required',
            'titular_email'      => 'required',
            'titular_domicilio'  => 'required',
            'idparentesco'       => 'required',
        ]);

        $validated['active'] = 1;
        $validated['nombre'] = strtoupper($request->nombre);
        $validated['curp'] = strtoupper($request->curp);

        $existe = Nadadores::where('curp', $validated['curp'])->exists();
        if($existe){
             return back()
                        ->withErrors("El nadador con la CURP {$validated['curp']} ya se encuentra registrado en el sistema.")
                        ->withInput();
        }

        Nadadores::create($validated);

        return redirect()
            ->route($this->module.'.index')
            ->with('messagetext','Nadador registrado exitosamente')
            ->with('msgstatus','success');
    }
    public function search(Request $request)
    {
        $data = $request->validate([
            'curp'              => 'required|size:18'
        ]);

        $row = $this->model->nadadorSearchCurpID(strtoupper($request->curp));
        if($row){
            //Se busca el año actual
            $year = Year::where("numero", Carbon::now()->format('Y'))->first();
            if($year){
                $this->data['pagos'] = $this->dataPagosAlumno($row->id, $year->idyear);
                $this->data['meses']= $this->utils->listaMeses();
                $this->data['curp'] = $row->curp;
                $this->data['row'] = $row;
                $this->data['idyear'] = $year->idyear;
                $this->data['year'] = $year->numero;
                return view($this->module.'.view',$this->data);
            }
        }else{
             return redirect()
            ->route($this->module.'.index')
            ->with('messagetext','CURP no encontrada, valida que este escrita correctamente!')
            ->with('msgstatus','error');
        }
    }
    private function dataPagosAlumno($id, $idyear){
		$meses = [];
		foreach ($this->model->pagosPorMes($id, $idyear) as $r) {
			$meses[$r->idmes] = ['id'           => $r->id, 
                                'idtipo_pago'   => $r->idtipo_pago,
                                'active'        => $r->active
                            ];
		}
		return $meses;
    }
    public function informacion(Request $request)
    {
        $row = $this->model->nadadorSearchCurpID(strtoupper($request->curp));
        if($row){

            $fechas = $this->model->fechasSuscripcion($row->id, $request->idy, $request->idm);
            if($fechas->count() > 0){
                $this->data['row'] = $row;
                $this->data['rowsFechas'] = $fechas;
                return view($this->module.'.vista',$this->data);
            }
            $month   = $request->idm;
            $years = Year::find($request->idy);
            $year = $years->numero;
            // Horarios existentes por día de la semana (1=Lunes ... 7=Domingo)
            $horarios = $this->model->horariosNiveles($row->idniveles);
            // Rango que cubre todas las semanas del mes
            $firstOfMonth = Carbon::create($year, $month, 1);
            $start = $firstOfMonth->copy()->startOfWeek(Carbon::MONDAY);
            $end   = $firstOfMonth->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

            $days = [];
            $current = $start->copy();

            while ($current <= $end) {
                $isoDow = $current->dayOfWeekIso; // 1..7
                if($horarios->get($isoDow)){
                    $collectHorarios = $this->calcularDisponibilidad($horarios->get($isoDow), $current->toDateString(), $row->aforo_maximo);
                }else{
                    $collectHorarios = collect();
                }

                $days[] = [
                    'date'          => $current->copy(),
                    'in_month'      => $current->month == $month,
                    'horarios'      => $collectHorarios,
                    'aforo_maximo'  => $row->aforo_maximo
                ];

                $current->addDay();
            }
            // Partimos en semanas de 7 días
            $weeks = collect($days)->chunk(7);
            $this->data['weeks'] = $weeks;

            $years = Year::find($request->idy);
            $this->data['row'] = $row;
            $this->data['year'] = $years->numero;
            $this->data['idy'] = $request->idy;
            $this->data['month'] = $request->idm;
            $this->data['id'] = $row->id;
            $this->data['idm'] = $request->idm;
            $this->data['curp'] = $request->curp;
            return view($this->module.'.elegirhorario',$this->data);
        }
    }
    public function temporal(Request $request)
    {
        if (empty($request->idplan_horario)) {
            return back()
                    ->withErrors('No seleccionaste ningún horario. Selecciona horarios para completar tu registro.');
        }
        $row = $this->model->nadadorSearchCurpID(strtoupper($request->curp));
        if($row){
        $total = count($request->idplan_horario);

        if ($total > $row->max_visitas_mes) {
           return back()
                    ->withErrors('Has superado el número de visitas permitido por tu plan. Selecciona en total '. $row->max_visitas_mes . ' horarios para completar tu registro.');
        }else if($total < $row->max_visitas_mes){
            return back()
                    ->withErrors(["Seleccionaste {$total} horarios, pero tu plan solo permite {$row->max_visitas_mes} visitas."]);
        }

        // ID que agrupa esta selección de días
        $time = time();
        $fechas = collect($request->idplan_horario)->map(function ($json) {
            return json_decode($json, true); // array con id y fecha
        });

        foreach ($fechas as $fecha) {
            Reservatemporal::create(
                [
                    'time'      => $time,
                    'idnadador' => $row->id,
                    'fecha'     => $fecha['fecha'],
                    'idplan_horario' => $fecha['idplan_horario']
                ]
            );
        }
        
        return redirect()
            ->route($this->module.'.openpay', ['idm' => $request->idm, 'idy' => $request->idy, 'time' => $time,'curp' => $request->curp])
            ->with('messagetext','Horario seleccionado correctamente, procede a realziar el pago.')
            ->with('msgstatus','success');
        }
    }
     private function calcularDisponibilidad($data, $fecha, $aforo_maximo){
         return $data->map(function ($v) use ($fecha, $aforo_maximo) {
                $ocupados = $this->model->totalReservasPorFecha($v->idplan_horario,$fecha);
                $disponibles = max($aforo_maximo - $ocupados, 0);
                return [
                    'idplan_horario' => $v->idplan_horario,
                    'dia_semana'     => $v->dia_semana,
                    'time_start'     => $v->time_start,
                    'ocupados'       => $ocupados,
                    'disponibles'    => $disponibles,
                    'fecha'          => $fecha,
                ];
            });
    }
    public function openpay(Request $request)
    {
        $row = $this->model->nadadorSearchCurpID(strtoupper($request->curp));
        if($row){
            $this->data['id'] = $row->id;
            $this->data['idy'] = $request->idy;
            $this->data['idm'] = $request->idm;
            $this->data['time'] = $request->time;
            $this->data['curp'] = $request->curp;
            $this->data['row'] = $row;
            $this->data['data'] = $this->utils->calcularDescuento($row->precio, $row->descuento);
            $this->data['rowsFechas'] = $this->model->fechasTemporales($row->id, $request->time);
            $this->data['merchantId'] = config('openpay.merchant_id');
            $this->data['publicKey'] = config('openpay.public_key');
            $this->data['production'] = config('openpay.production');
            return view('openpay.checkout', $this->data); 
        }
    }
   /* public function horario(Request $request)
    {
        $jsonToken = $this->secureToken->decode($request->token);
        if (!$jsonToken) {
            abort(403, 'Token inválido o manipulado');
        }

        $suscripcion = $this->model->find($jsonToken['ids']);
        // Rango de fechas
        $period = CarbonPeriod::create($suscripcion->fecha_inicio, $suscripcion->fecha_fin);
        // Lista de días
        $dias = collect();

        foreach ($period as $date) {
            $no_dia_semana = $date->dayOfWeekIso;
            $fecha = $date->toDateString();
            //Convertimos a collection
            $rows_horario = collect($this->dataHorarioPlan($suscripcion->idplan, $no_dia_semana, $fecha));
            //Validar que la fecha contega registros la coleccion
            if ($rows_horario->isNotEmpty()) {
                $dias->push([
                    'fecha' => $fecha,              // 2025-11-22
                    'dia_nombre' => $date->translatedFormat('l'),  // sábado, domingo, etc.
                    //'dia_corto'  => $date->translatedFormat('D'),  // sáb, dom, etc.
                    'dia_numero' => $date->dayOfWeekIso,
                    'rows_horario' => $rows_horario
                ]);
            }
        }   
        $this->data['id'] = 0;//idnadador
        $this->data['ids'] = $jsonToken['ids'];
        $this->data['token'] = $request->token;
        $this->data['max_visitas_mes'] = $suscripcion->max_visitas_mes;
        $this->data['rowsHorarios'] = $dias;
        return view($this->module.'.horarios',$this->data);
    }*/
    public function update(Request $request)
    {
        $jsonToken = $this->secureToken->decode($request->token);
        if (!$jsonToken) {
            abort(403, 'Token inválido o manipulado');
        }

        $suscripcion = $this->model->find($jsonToken['ids']);

        if (empty($request->idplan_horario)) {
            return back()->withErrors('No seleccionaste ningún horario. Selecciona en total ' . $suscripcion->max_visitas_mes . ' horarios para completar tu registro.');
        }

        $total = count($request->idplan_horario);

        if ($total > $suscripcion->max_visitas_mes) {
           return back()->withErrors('Has superado el número de visitas permitido por tu plan. Selecciona en total '. $suscripcion->max_visitas_mes . ' horarios para completar tu registro.');
        }else if($total < $suscripcion->max_visitas_mes){
            return back()->withErrors([
                "Seleccionaste {$total} horarios, pero tu plan solo permite {$suscripcion->max_visitas_mes} visitas."
            ]);
        }

        $fechas = collect($request->idplan_horario)->map(function ($json) {
            return json_decode($json, true);
        });

        foreach ($fechas as $fecha) {
            Reserva::create([
                            'idsuscripcion'  => $jsonToken['ids'],
                            'idplan_horario' => $fecha['id'],
                            'fecha'          => $fecha['fecha'],
                            'active'         => 1
                        ]
            );
        }

        //Actualizo el campo activo a 2 que es con horario asignado
        $suscripcion->update(['active' => 2]);

        return redirect()
            ->route($this->module.'.index')
            ->with('messagetext','Horario asignado correctamente')
            ->with('msgstatus','success');
    }
     public function success(Request $request,$id)
    {
        $this->data['token'] = $request->token;
        $this->data['charge_id'] = $id;
        return view($this->module.'.success',$this->data);
    }
    private function dataHorarioPlan($idplan, $dias_semana, $fecha){
        $data = [];
        foreach ($this->model->listHorarioPlan($idplan, $dias_semana) as $v) {
           $ocupados = $this->model->totalReservasPorFecha($v->id, $fecha);
           $disponibles = max($v->aforo_maximo - $ocupados,0);
           $data[] = ['id'          => $v->id,
                    'dia_semana'    => $v->dia_semana,
                    'aforo_maximo'  => $v->aforo_maximo,
                    'time_start'    => $v->time_start,
                    'time_end'      => $v->time_end,
                    'ocupados'      => $ocupados,
                    'disponibles'   => $disponibles,
                ];
        }
        return $data;
    }
}
