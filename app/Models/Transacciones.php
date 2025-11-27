<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transacciones extends Model
{
    
	protected $table = 'ses_payments';
    protected $primaryKey = 'idpayments'; 

    public static function listData($request)
	{
		$perPage = $request['nopagina']; // default

		$query = DB::table('ses_payments as p')
			->leftJoin('ses_suscripcion as s', 's.idsuscripcion', '=', 'p.idsuscripcion')
			->select([
				'p.idpayments as id',
				'p.idsuscripcion',
				'p.provider_charge_id',
				'p.status',
				'p.amount',
				'p.currency',
				'p.description',
				'p.created_at as fecha',
			])
			->orderBy('p.idpayments', 'desc');
		// Filtros opcionales (ejemplo)
		/*
		if (!empty($request['nombre']) && trim($request['nombre']) !== '') {
			$query->where('f.descripcion', 'like', '%'.trim($request['nombre']).'%');
		}
		*/
		return $query->paginate($perPage)->appends($request);
	}
    
}
