<?php

namespace App\Http\Controllers;

use App\Services\OpenpayService;

use App\Models\Payment\Payment;
use App\Models\Suscripciones;
use App\Models\Year;
use App\Models\Reserva;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use App\Services\SecureTokenService;
use App\Services\UtilsService;

class OpenpayPaymentController extends Controller
{
    protected $data = [];
    protected SecureTokenService $secureToken;
    protected UtilsService $utils;

    public function __construct(protected OpenpayService $openpay, SecureTokenService $secureToken, UtilsService $utils) {
        $this->secureToken = $secureToken;
        $this->utils = $utils;
    }

    // Muestra el formulario
    /*public function showCheckout(Request $request)
    {
        return view('openpay.checkout', [
            'id'         => $request->id, // idpagos
            'amount'     => 350.00, // monto de ejemplo
            'merchantId' => config('openpay.merchant_id'),
            'publicKey'  => config('openpay.public_key'),
            'production' => config('openpay.production'),
        ]);
    }
    */
    // Recibe el token y hace el cargo
    public function charge(Request $request)
    {
        $request->validate([
            'token_id'          => 'required|string',
            'device_session_id' => 'required|string',
        ]);

        try {
            $row = Suscripciones::nadadorSearchCurpID(strtoupper($request->curp));
            //$row = Suscripciones::nadadorID($request->input('idnadador'));
            if (!$row) {
                return back()->withErrors('ID de nadador no encontrado!');
            }

            $idnadador  = (int) $row->id;
            $idy        = (int) $request->idy;  //idaño
            $idm        = (int) $request->idm; //idmes
            $idplan     = (int) $row->idplan;
            $amount = $this->utils->calcularDescuento($row->precio, $row->descuento);
            //Validar que el usuario no tenga una sesión activa antes de cobrar
            $yaActiva = Suscripciones::validateSubscriptionActive($idnadador, $idy, $idm);
            if ($yaActiva) {
                return back()->withErrors('El nadador ya tiene una suscripción activa.');
            }
            $year = Year::find($idy);
            $mes = $this->utils->buscarMes($idm);
          
            $description = "Mensualidad Alberca {$mes} {$year->numero} - {$row->nombre} - {$row->nivel} ({$row->plan})";
            $charges = $this->openpay->charges();

            $chargeData = [
                'method'            => 'card',
                'source_id'         => $request->input('token_id'),
                'amount'            => $amount['total'],
                'currency'          => 'MXN',
                'description'       => $description,
                'device_session_id' => $request->input('device_session_id'),
                'customer'          => [
                                        'name'          => $row->nombre,
                                        'email'         => $request->email,
                                        'phone_number'  => $row->titular_telefono
                                    ],
                'metadata'          => [
                                        'idnadador'         => $idnadador,
                                        'nombre'            => $row->nombre,
                                        'curp'              => $row->curp,
                                        'fecha_nacimiento'  => $row->fecha_nacimiento
                                    ],
            ];

            $charge = $charges->create($chargeData);

            // Estado que devuelve Openpay, ej. 'completed'
            $status = $charge->status ?? 'completed';

            $idSuscripcion = 0;

            
                DB::beginTransaction();

                    $rowSuscripcion = Suscripciones::create([
                        'active'                 => 2,
                        'idyear'                 => $idy,
                        'idmes'                  => $idm,
                        'idnadador'              => $idnadador,
                        'idplan'                 => $idplan,
                        'fecha_pago'             => Carbon::now()->toDateString(),
                        'hora_pago'              => Carbon::now()->format('H:i:s'),
                        'idtipo_pago'            => 3,
                        'max_visitas_mes'        => $row->max_visitas_mes,
                        'monto_general'          => $amount['precio'],
                        'monto_pagado'           => $amount['total'],
                        'descuento'              => $amount['descuento'],
                        'desc_descuento'         => $row->desc_descuento,
                        'porc_descuento'         => $row->descuento,
                    ]);

                    $idSuscripcion = $rowSuscripcion->idsuscripcion;
                    // Guardar en BD
                    $rowPayment = Payment::create([
                        'idsuscripcion'     => $idSuscripcion,
                        'provider'          => 'openpay',
                        'provider_charge_id'=> $charge->id,
                        'status'            => $status,
                        'amount'            => $amount['total'],
                        'currency'          => 'MXN',
                        'description'       => $description,
                        'payer_email'       => $request->email,
                        'raw_payload'       => json_encode($charge)
                    ]);

                    foreach (Suscripciones::fechasTemporales($row->id, $request->time) as $f) {
                        Reserva::create([
                                    'idsuscripcion'  => $idSuscripcion,
                                    'idplan_horario' => $f->idplan_horario,
                                    'fecha'          => $f->fecha,
                                    'active'         => 1
                                ]);
                    }

                DB::commit();

               $sesToken = $this->secureToken->encode([ 'ids' => $idSuscripcion ]);
                                                  //'idp' => $rowPayment->idpayments,
                                                    //'key' => $charge->id,
                return redirect()->route('acceso.success', ['id' => $charge->id, 'token' => $sesToken]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al cobrar con Openpay', ['message' => $e->getMessage(),]);
            return back()->withErrors('Error al procesar el pago: '.$e->getMessage());
        }
    }
}
