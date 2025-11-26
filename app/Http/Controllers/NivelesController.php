<?php

namespace App\Http\Controllers;

use App\Models\Niveles;

use Illuminate\Http\Request;
use Illuminate\View\View;


class NivelesController extends Controller
{
    protected $data = [];	
    protected $model;	
	public $module = 'niveles';
    public static int $perpage = 10;

    public function __construct(Niveles $model)
    {
        $this->model = $model;

        $this->data = ['pageTitle'	=> 	"Planes",
                        'pageNote'	    =>  "Lista de planes",
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
    public function create(Request $request): View
    {
        $this->data['rowsNiveles'] = $this->model->catalogoNiveles();
        return view($this->module.'.create',$this->data);
    }
    public function edit(Request $request): View
    {
        $this->data['row'] = $this->model->find($request->id);
        $this->data['rowsNiveles'] = $this->model->catalogoNiveles();
        $this->data['id'] = $request->id;
        return view($this->module.'.edit',$this->data);
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'idniveles'        => 'required',
            'nombre'           => 'required|string|max:255',
            'precio'           => 'required|numeric',
            'max_visitas_mes'  => 'required|integer|max:30',
        ]);
        $data['active'] = 1;
        $data['duracion_dias'] = 30;

        $plan = Niveles::create($data);

        return redirect()
            ->route($this->module.'.index')
            ->with('messagetext','El plan se registró correctamente.')
            ->with('msgstatus','success');
    }
    public function guardar(Request $request)
    {
        $data = $request->validate([
            'idniveles'        => 'required',
            'nombre'           => 'required|string|max:255',
            'precio'           => 'required|numeric',
            'max_visitas_mes'  => 'required|integer|max:30',
        ]);

        $plan = Niveles::find($request->id);
        
        if($plan){
            $plan->update($data);
        }

        return redirect()
            ->route($this->module.'.index', [ 'page' => $request->page])
            ->with('messagetext','El plan se actualizó correctamente.')
            ->with('msgstatus','success');

    }
   
    
}
