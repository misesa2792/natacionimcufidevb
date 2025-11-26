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
        'idniveles',
        'nombre',
        'precio',
        'duracion_dias',
        'max_visitas_mes'
    ];

	public $timestamps = false;

    public static function listData($request)
	{
		$perPage = $request['nopagina']; // default

        $query = DB::table('ses_plan as n')
			->join('ses_niveles as s', 's.idniveles', '=', 'n.idniveles')
            ->select([
                'n.idplan as id',
                'n.active',
                'n.nombre as plan',
                'n.precio',
                's.descripcion as nivel',
                'n.duracion_dias',
                'n.max_visitas_mes'
            ])
            ->orderBy('n.idplan', 'asc');

        if (!empty($request['name']) && trim($request['name']) !== '') {
			$query->where('n.nombre', 'like', '%'.trim($request['name']).'%');
		}
		return $query->paginate($perPage)->appends($request);
	}
   
    public static function catalogoNiveles()
	{
        return DB::table('ses_niveles')
            ->select([
                'idniveles as id',
                'active',
                'descripcion as nivel'
            ])
            ->get();
	}
 
 
}
