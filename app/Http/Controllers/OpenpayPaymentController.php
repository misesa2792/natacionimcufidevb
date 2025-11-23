<?php

namespace App\Http\Controllers;

use App\Services\OpenpayService;

use App\Models\Payment\Payment;
use App\Models\Suscripciones;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;

use App\Services\SecureTokenService;

class OpenpayPaymentController extends Controller
{
    protected $data = [];
    protected SecureTokenService $secureToken;

    public function __construct(protected OpenpayService $openpay, SecureTokenService $secureToken) {
        $this->secureToken = $secureToken;
    }

    // Muestra el formulario
    public function showCheckout(Request $request)
    {
        return view('openpay.checkout', [
            'id'         => $request->id, // idpagos
            'amount'     => 350.00, // monto de ejemplo
            'merchantId' => config('openpay.merchant_id'),
            'publicKey'  => config('openpay.public_key'),
            'production' => config('openpay.production'),
        ]);
    }

    // Recibe el token y hace el cargo
    public function charge(Request $request)
    {
        $request->validate([
            'token_id'          => 'required|string',
            'device_session_id' => 'required|string',
            'idnadador'         => 'required',
        ]);

        try {
            $row = Suscripciones::nadadorID($request->input('idnadador'));
            if (!$row) {
                return back()->withErrors('ID de nadador no encontrado!');
            }

            $idnadador   = (int) $request->input('idnadador');
            $idplan      = $row->idplan;
            $amountPesos = round($row->precio, 2);// si guardas en centavos en BD:

            //Validar que el usuario no tenga una sesión activa antes de cobrar
            $yaActiva = Suscripciones::validateSubscriptionActive($idnadador);
            if ($yaActiva) {
                return back()->withErrors('El nadador ya tiene una suscripción activa.');
            }

            $description = "Suscripción {$row->plan} - Nadador: {$row->nombre}";

            $charges = $this->openpay->charges();

            $chargeData = [
                'method'            => 'card',
                'source_id'         => $request->input('token_id'),
                'amount'            => $amountPesos,
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
                // Usuario autenticado (si tienes login), si no, null
                $userId = auth()->id() ?? 0;

                //Se crea la suscripción 
                $base = now();      // ya con timezone corregido
                $fecha_inicio = $base->toDateString();
                $fecha_fin    = $base->copy()->addDays($row->duracion_dias)->toDateString();

                $idSuscripcion = 0;

                DB::beginTransaction();

                    $rowSuscripcion = Suscripciones::create([
                        'idnadador'              => $idnadador,
                        'idplan'                 => $idplan,
                        'fecha_inicio'           => $fecha_inicio,
                        'fecha_fin'              => $fecha_fin,
                        'active'                 => 1,
                        'idtipo_pago'            => 1,
                        'monto'                  => $amountPesos,
                        'max_visitas_mes'        => $row->max_visitas_mes
                    ]);

                    $idSuscripcion = $rowSuscripcion->idsuscripcion;
                
                    // Guardar en BD
                    $rowPayment = Payment::create([
                        'iduser'            => $userId,
                        'idsuscripcion'     => $idSuscripcion,
                        'provider'          => 'openpay',
                        'provider_charge_id'=> $charge->id,
                        'status'            => $status,
                        'amount'            => $amountPesos,
                        'currency'          => $charge->currency ?? 'MXN',
                        'description'       => $description,
                        'payer_email'       => $request->email,
                        'raw_payload'       => json_encode($charge)
                    ]);
                DB::commit();

               $sesToken = $this->secureToken->encode([
                                                    'ids' => $idSuscripcion,
                                                    'idp' => $rowPayment->idpayments,
                                                    'key' => $charge->id,
                                                ]);

                return redirect()->route('acceso.success', ['id' => $charge->id, 'token' => $sesToken]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al cobrar con Openpay', ['message' => $e->getMessage(),]);
            return back()->withErrors('Error al procesar el pago: '.$e->getMessage());
        }
    }
}
