<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pagos;
use Illuminate\View\View;

use Barryvdh\DomPDF\Facade\Pdf;

class PagosController extends Controller
{
    protected $data = [];	
    protected $model;	
	public $module = 'pagos';
    public static int $perpage = 10;

    public function __construct(Pagos $model)
    {
        $this->model = $model;

        $this->data = ['pageTitle'	=> 	"Mensualidades",
                        'pageNote'	    =>  "Lista de mensualidades",
                        'pageModule'    => $this->module
                    ];
    }

    public function index(Request $request)
    {
        $iduser = auth()->check() ? auth()->user()->iduser : 0;
        
        $nopage = $request->integer('nopagina', static::$perpage);
        $page = $request->integer('page', 1);
        $idyear = $request->integer('idyear', 5);
        
        $request['nopagina'] = $nopage;
        $request['idyear'] = $idyear;
        $request['iduser'] = $iduser;

        $rows =  $this->model->listData($request->all());
        // Agrega/transforma campos en cada item de la página actual
        $rows = $rows->transform(function ($row) {
            return [
                'idpagos'       => $row->idpagos,
                'mes'           => $row->mes,
                'total'         => $row->total,
                'recargo'       => $row->recargo,
                'rg_fecha'      => $row->rg_fecha,
                'rg_hora'       => $row->rg_hora,
                'ide'           => $row->ide,
                'estatus'       => $row->estatus,
                'tipo_pago'     => $row->tipo_pago,
                'code'          => $this->model->transactionCode($row->idpagos),
            ];
        });
		$this->data['j'] = ($page * $nopage) - $nopage;
        $this->data['pagination'] = $rows;
        return view($this->module.'.index',$this->data);
    }
    public function view(Request $request, $id): View
    {
        $iduser = auth()->check() ? auth()->user()->iduser : 0;

        $row = $this->model->pagoID($iduser, $id);
        if($row){
            $this->data['id'] = $id;
            $this->data['row'] = $row;
        
            return view('openpay.checkout', [
                'id'         => $id, // idpagos
                'amount'     => $row->plan,
                'cliente'    => $row->cliente,
                'correo'     => $row->correo,
                'nc'         => $row->nc,
                'mes'        => $row->mes,
                'year'       => $row->anio,
                'telefono'   => $row->telefono,
                'velocidad'  => $row->velocidad,
                'tipo'       => ($row->type == 1 ? 'Fibra Óptica' : 'Inalámbrica'),
                'descripcion' => 'Mensualidad '.$row->mes.' '.$row->anio.' - '.$row->nc,
                'merchantId' => config('openpay.merchant_id'),
                'publicKey'  => config('openpay.public_key'),
                'production' => config('openpay.production'),
            ]); 
        }
       
    }
    public function success(Request $request, $id): View
    {
        $this->data['charge_id'] = $id;
        return view($this->module.'.success',$this->data);
    }
    public function pdf(Request $request, $id)
    {
        $iduser = auth()->check() ? auth()->user()->iduser : 0;

        $row = $this->model->pagoID($iduser, $id);
        if($row){
            $this->data['row'] = $row;
            //dd($row);
            $this->data['id'] = $id;
            $this->data['hora'] = date( "h:i a");
            $this->data['fecha'] = $this->getDia().' , '.date('d').' de '.$this->getDataMeses(date('m')).' de '. date('Y');
            // Generar PDF desde una vista Blade
            $pdf = Pdf::loadView($this->module.'.pdf', $this->data)
                    ->setPaper('letter', 'portrait');;
            // Descargar el PDF
            return $pdf->stream('recibo.pdf');
        }
        
    }
    private function getDia(){
		$dia = date("N");
		switch ($dia) {
			case 1: 	$dias = "Lunes"; break;
			case 2: 	$dias = "Martes"; break;
			case 3: 	$dias = "Miercoles"; break;
			case 4: 	$dias = "Jueves"; break;
			case 5: 	$dias = "Viernes"; break;
			case 6: 	$dias = "Sabado"; break;
			case 7: 	$dias = "Domingo"; break;
		}
		return $dias;
	}
    private function getDataMeses($idmes) {
		$data = $this->getIdmes();
		return $data[$idmes];
	}
	private function getIdmes(){
		$data = [
			'1' => 'Enero',
			'2' => 'Febrero',
			'3' => 'Marzo',
			'4' => 'Abril',
			'5' => 'Mayo',
			'6' => 'Junio',
			'7' => 'Julio',
			'8' => 'Agosto',
			'9' => 'Septiembre',
			'10' => 'Octubre',
			'11' => 'Noviembre',
			'12' => 'Diciembre'
		];
		return $data;
	}
}
