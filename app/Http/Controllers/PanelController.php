<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Year;
use App\Models\Nivel;
use App\Models\Suscripciones;
use App\Models\Reserva;
use Carbon\Carbon;

class PanelController extends Controller
{
    protected $data = [];	
    protected $model;	
	public $module = 'panel';
    public static int $perpage = 25;

    public function __construct(Suscripciones $model,)
    {
        $this->model = $model;

        $this->data = ['pageTitle'	=> 	"Panel administrativo",
                        'pageNote'	    =>  "Calendario",
                        'pageModule'    => $this->module
                    ];
    }
    public function index(Request $request)
    {
        $month = now()->month;
        $month = Carbon::now()->month;
        $nivel = Nivel::find(1);
        $idy = 1;
        $idniveles = $nivel->idniveles;
        return redirect()->route($this->module.'.calendario', ['idy' => $idy, 'idm' => $month, 'idn' => $idniveles]);
    }
    public function info(Request $request)
    {
        $this->data['rowsregistros'] = Suscripciones::listSuscripcionesDia($request->idplan_horario, $request->fecha);
        $this->data['idplan_horario'] = $request->idplan_horario;
        $this->data['fecha'] = $request->fecha;
        return view($this->module.'.info',$this->data);
    }
    public function asistencia(Request $request)
    {
        $plan = Reserva::find($request->id);
        
        if($plan){
            $plan->update(['active' => $request->std]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Asistencia registrada correctamente.'
        ]);
    }
    public function calendario(Request $request)
    {
        $nivel = Nivel::find($request->idn);

        $idy = $request->idy;//id año
        $month = $request->idm;//id mes
        $idniveles = $request->idn;//id nivel
        $aforo_maximo = $nivel->aforo_maximo;

        $years = Year::find($idy);
        $year = $years->numero;
        $horarios = $this->model->horariosNiveles($idniveles);
        // Rango que cubre todas las semanas del mes
        $firstOfMonth = Carbon::create($year, $month, 1);

        $start = $firstOfMonth->copy()->startOfWeek(Carbon::MONDAY);
        $end   = $firstOfMonth->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        $days = [];
        $current = $start->copy();

        while ($current <= $end) {
            $isoDow = $current->dayOfWeekIso; // 1..7
            if($horarios->get($isoDow)){
                $collectHorarios = $this->calcularDisponibilidad($horarios->get($isoDow), $current->toDateString(), $aforo_maximo);
            }else{
                $collectHorarios = collect();
            }

            $days[] = [
                'date'          => $current->copy(),
                'in_month'      => $current->month == $month,
                'horarios'      => $collectHorarios,
                'aforo_maximo'  => $aforo_maximo
            ];

            $current->addDay();
        }
        // Partimos en semanas de 7 días
        $weeks = collect($days)->chunk(7);
        $this->data['weeks'] = $weeks;
        $this->data['year'] = $year;
        $this->data['month'] = $month;
        $this->data['id'] = $request->id;
        $this->data['idy'] = $request->idy;
        $this->data['idm'] = $request->idm;
        $this->data['idn'] = $request->idn;
        $this->data['mes'] = $this->nombreMes($request->idm);
        $this->data['rowsNiveles'] = Nivel::all(['idniveles as id','descripcion as nivel']);
        return view($this->module.'.index',$this->data);

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
    private function nombreMes($idm){
        return ucfirst(Carbon::create(null, $idm, 1)->locale('es')->monthName);
    }
}
