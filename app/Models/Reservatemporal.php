<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Reservatemporal extends Model
{
	protected $table = 'ses_reserva_alt';
    protected $primaryKey = 'idreserva_alt'; 

    protected $fillable = [
        'time',
        'idnadador',
        'fecha',
        'idplan_horario'
    ];

}
