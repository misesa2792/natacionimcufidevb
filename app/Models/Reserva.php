<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
}
