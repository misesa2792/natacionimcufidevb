<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Planhorario extends Model
{
	protected $table = 'ses_plan_horario';
    protected $primaryKey = 'idplan_horario'; 

    protected $fillable = [
        'idplan',
        'dia_semana',
        'aforo_maximo',
        'time_start',
        'time_end'
    ];

	public $timestamps = false;
 
}
