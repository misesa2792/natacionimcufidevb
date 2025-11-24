<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Reserva extends Model
{
	protected $table = 'ses_reserva';
    protected $primaryKey = 'idreserva'; 

    protected $fillable = [
        'idsuscripcion',
        'idplan_horario',
        'fecha',
        'active'
    ];

    public static function listData($request)
	{
		$perPage = $request['nopagina']; // default

		$query = DB::table('ses_reserva as r')
			->join('ses_plan_horario as ph', 'ph.idplan_horario', '=', 'r.idplan_horario')
			->join('ses_suscripcion as s', 's.idsuscripcion', '=', 'r.idsuscripcion')
			    ->join('ses_nadador as n', 'n.idnadador', '=', 's.idnadador')
			    ->join('ses_plan as p', 'p.idplan', '=', 's.idplan')
			->select([
				'r.idreserva as id',
				'r.fecha',
				'r.active',
                DB::raw('DATE_FORMAT(ph.time_start, "%h:%i %p") as time_start'),
                DB::raw('DATE_FORMAT(ph.time_end, "%h:%i %p") as time_end'),
				'n.nombre as nadador',
				'p.nombre as plan'
			])
			->orderBy('r.idreserva', 'desc');
		
		$rows = $query->paginate($perPage)->appends($request);

        // Formatear fecha en cada resultado
        $rows->getCollection()->transform(function ($row) {
            $row->fecha_formateada = Carbon::parse($row->fecha)
                ->isoFormat('DD [de] MMMM [de] YYYY'); 
            return $row;
        });

        return $rows;

	}
}
