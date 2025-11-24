<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Niveles extends Model
{
	protected $table = 'ses_plan';
    protected $primaryKey = 'idplan'; 

    protected $fillable = [
        'active',
        'nombre',
        'descripcion',
        'precio',
        'duracion_dias',
        'max_visitas_mes'
    ];

	public $timestamps = false;

    public static function listData($request)
	{
		$perPage = $request['nopagina']; // default

        $query = DB::table('ses_plan as n')
            ->select([
                'n.idplan as id',
                'n.active',
                'n.nombre as plan',
                'descripcion',
                'precio',
                'duracion_dias',
                'max_visitas_mes'
            ])
            ->orderBy('n.idplan', 'asc');

        if (!empty($request['name']) && trim($request['name']) !== '') {
			$query->where('n.nombre', 'like', '%'.trim($request['name']).'%');
		}
		return $query->paginate($perPage)->appends($request);
	}
    public static function listHorarioPlan($id)
	{
        return DB::table('ses_plan_horario')
            ->select([
                'idplan_horario as id',
                'dia_semana',
                'aforo_maximo',
                DB::raw('DATE_FORMAT(time_start, "%h:%i %p") as time_start'),
                DB::raw('DATE_FORMAT(time_end, "%h:%i %p") as time_end'),
            ])
            ->where('idplan', $id)
            ->get();
	}
 
}
