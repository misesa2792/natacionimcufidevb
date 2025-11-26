<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Nivel extends Model
{
	protected $table = 'ses_niveles';
    protected $primaryKey = 'idniveles'; 

    protected $fillable = [
        'active',
        'descripcion',
        'aforo_maximo'
    ];

	public $timestamps = false;

    public static function listData($request)
	{
		$perPage = $request['nopagina']; // default

        $query = DB::table('ses_niveles')
            ->select([
                'idniveles as id',
                'active',
                'descripcion as nivel',
                'aforo_maximo'
            ])
            ->orderBy('idniveles', 'asc');

        if (!empty($request['name']) && trim($request['name']) !== '') {
			$query->where('descripcion', 'like', '%'.trim($request['name']).'%');
		}
		return $query->paginate($perPage)->appends($request);
	}
    public static function listHorarioPlan($id)
	{
        return DB::table('ses_plan_horario')
            ->select([
                'idplan_horario as id',
                'dia_semana',
                DB::raw('DATE_FORMAT(time_start, "%h:%i %p") as time_start'),
                DB::raw('DATE_FORMAT(time_end, "%h:%i %p") as time_end'),
            ])
            ->where('idniveles', $id)
            ->get();
	}
 
 
}
