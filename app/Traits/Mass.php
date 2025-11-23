<?php
namespace App\Traits;

trait Mass
{
    public static function getInsertAuditLogs($request, $note )
    {
        $data = array('module' => $request->segment(1),'task' => $request->segment(2),'iduser'	=> \Auth::user()->id,'ipaddress'	=> $request->getClientIp(),'note'	=> $note);
		\DB::table( 'ui_logs')->insert($data);
    }
}