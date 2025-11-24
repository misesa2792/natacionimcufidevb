<?php

namespace App\Http\Controllers;

use App\Models\Reserva;

use Illuminate\Http\Request;

class AsistenciasController extends Controller
{
    protected $data = [];	
    protected $model;	
	public $module = 'asistencias';
    public static int $perpage = 10;

    public function __construct(Reserva $model)
    {
        $this->model = $model;

        $this->data = ['pageTitle'	=> 	"Control de asistencias",
                        'pageNote'	    =>  "Lista de nadadores",
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
		$this->data['j'] = ($page * $nopage) - $nopage;
        $this->data['pagination'] = $rows;
        return view($this->module.'.index',$this->data);
    }
    public function checkin(Request $request)
    {
        $this->data['id'] = $request->id;
        return view($this->module.'.checkin',$this->data);
    }
    public function store(Request $request)
    {
        $plan = $this->model->find($request->id);
        
        if($plan){
            $plan->update(['active' => $request->std]);
        }

        return redirect()
               ->route($this->module.'.index', ['page' => $request->page])
            ->with('messagetext','Asistencia registrada correctamente.')
            ->with('msgstatus','success');

    }
}
