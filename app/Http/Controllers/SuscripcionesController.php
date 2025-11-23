<?php

namespace App\Http\Controllers;

use App\Models\Suscripciones;
use App\Models\Reserva;

use Illuminate\View\View;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class SuscripcionesController extends Controller
{
    protected $data = [];	
    protected $model;	
	public $module = 'suscripciones';
    public static int $perpage = 10;

    public function __construct(Suscripciones $model)
    {
        $this->model = $model;

        $this->data = ['pageTitle'	=> 	"Suscripciones",
                        'pageNote'	    =>  "Lista de suscripciones",
                        'pageModule'    => $this->module
                    ];
    }
    public function index(Request $request)
    {
        $nopage = $request->integer('nopagina', static::$perpage);
        $page = $request->integer('page', 1);
        $name = $request->input('name', '');

        $request['nopagina'] = $nopage;
        $request['name'] = $name;

        $rows =  $this->model->listData($request->all());

        $rows->getCollection()->transform(function ($row) {
            return [
                'id'            => $row->id,
                'active'        => $row->active,
                'nombre'        => $row->nombre,
                'plan'          => $row->plan,
                'suscripcion'   => $this->model->suscripcionID($row->id),
            ];
        });
		$this->data['j'] = ($page * $nopage) - $nopage;
        $this->data['pagination'] = $rows;
        return view($this->module.'.index',$this->data);
    }
    public function view(Request $request, $id = 0): View
    {
        $row = $this->model->nadadorID($id);
        if($row){
            $rows = $this->model->suscripcionesNadador($id);
            //Cuando la consulta no es una coleccion se trata como map
            $rows = $rows->map(function ($v) {
                return [
                    'id'          => $v->id,
                    'plan'      => $v->plan,
                    'fi'          => $v->fi,
                    'ff'          => $v->ff,
                    'pago'        => $v->pago,
                    'monto'       => $v->monto,
                    'estado'      => $v->estado,
                    'max_visitas' => $v->max_visitas_mes,
                    'rows_fechas' => $this->model->listRegistros($v->id),
                ];
            });
            
            $tieneActiva = $rows->contains(function ($item) {
                return $item['estado'] === 'ACTIVA';
            });

            $this->data['id'] = $id;
            $this->data['tieneActiva'] = $tieneActiva;
            $this->data['rowsSuscripciones'] = $rows;
            $this->data['row'] = $row;
        
            return view($this->module.'.view',$this->data);
        }
    }
    public function create(Request $request, $id = 0): View
    {
        $row = $this->model->nadadorID($id);
        if($row){
            $this->data['id'] = $id;
            $this->data['row'] = $row;
            $this->data['merchantId'] = config('openpay.merchant_id');
            $this->data['publicKey'] = config('openpay.public_key');
            $this->data['production'] = config('openpay.production');
            $this->data['rowsPlanes'] = $this->model->dataPlanes();

             return view('openpay.checkout', $this->data); 

        }
    }
    public function success($id): View
    {
        $this->data['charge_id'] = $id;
        return view($this->module.'.success',$this->data);
    }
    public function horario(Request $request, $id): View
    {
        $suscripcion = $this->model->find($request->ids);
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
        $this->data['id'] = $id;
        $this->data['ids'] = $request->ids;
        $this->data['max_visitas_mes'] = $suscripcion->max_visitas_mes;
        $this->data['rowsHorarios'] = $dias;
        return view($this->module.'.horarios',$this->data);
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

    public function update(Request $request, $id = 0)
    {
        $suscripcion = $this->model->find($request->ids);

        if (empty($request->idplan_horario)) {
            return back()->withErrors(
                'No seleccionaste ningún horario. Selecciona en total ' . $suscripcion->max_visitas_mes . ' horarios para completar tu registro.'
            );
        }

        $total = count($request->idplan_horario);

        if ($total > $suscripcion->max_visitas_mes) {
           return back()->withErrors(
                'Has superado el número de visitas permitido por tu plan. Selecciona en total '. $suscripcion->max_visitas_mes . ' horarios para completar tu registro.'
            );
        }else if($total < $suscripcion->max_visitas_mes){
            return back()->withErrors([
                "Seleccionaste {$total} horarios, pero tu plan solo permite {$suscripcion->max_visitas_mes} visitas."
            ]);
        }

        $fechas = collect($request->idplan_horario)->map(function ($json) {
            return json_decode($json, true);
        });

        foreach ($fechas as $fecha) {
            Reserva::create(
                [
                    'idsuscripcion'  => $request->ids,
                    'idplan_horario' => $fecha['id'],
                    'fecha'          => $fecha['fecha'],
                    'active'         => 1
                ]
            );
        }

        //Actualizo el campo activo a 2 que es con horario asignado
        $row = $this->model->find($request->ids);
        if($row){
            $row->update(['active' => 2]);
        }
        return redirect()
            ->route($this->module.'.view', $id)
            ->with('messagetext','Información guardada correctamente')
            ->with('msgstatus','success');
    }
}
