<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transacciones;

class TransaccionesController extends Controller
{
    protected $data = [];	
    protected $model;	
	public $module = 'transacciones';
    public static int $perpage = 10;

    public function __construct(Transacciones $model)
    {
        $this->model = $model;

        $this->data = ['pageTitle'	=> 	"Transacciones",
                        'pageNote'	    =>  "Lista de transacciones",
                        'pageModule'    => $this->module
                    ];
    }
     public function index(Request $request)
    {
        $nopage = $request->integer('nopagina', static::$perpage);
        $page = $request->integer('page', 1);
        
        $request['nopagina'] = $nopage;

        $rows =  $this->model->listData($request->all());
		$this->data['j'] = ($page * $nopage) - $nopage;
        $this->data['pagination'] = $rows;
        return view($this->module.'.index',$this->data);
    }
}
