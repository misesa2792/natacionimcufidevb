<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Payment\Payment;

class OpenpayWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Recibir JSON completo
        $payload = $request->all();
        Log::info('Openpay Webhook recibido', $payload);

        // 2. Validar que viene un tipo de evento
        $type = $payload['type'] ?? null;
        if (!$type) {
            return response()->json(['error' => 'No type provided'], 400);
        }

        // 3. Procesar según tipo
        switch ($type) {

            case 'charge.succeeded':
                $this->updatePaymentStatus($payload, 'completed');
                break;

            case 'charge.failed':
                $this->updatePaymentStatus($payload, 'failed');
                break;

            case 'charge.refunded':
                $this->updatePaymentStatus($payload, 'refunded');
                break;

            default:
                Log::warning('Evento no manejado', ['type' => $type]);
                break;
        }

        // 4. Openpay necesita un 200 OK
        return response()->json(['status' => 'ok']);
    }

    private function updatePaymentStatus(array $payload, string $status)
    {
        $chargeId = $payload['transaction']['id'] ?? null;

        if (!$chargeId) {
            Log::error('Webhook sin ID de cargo');
            return;
        }

        // Buscar pago en tu tabla
        $payment = Payment::where('provider_charge_id', $chargeId)->first();

        if (!$payment) {
            Log::warning('Pago no encontrado en BD', ['charge_id' => $chargeId]);
            return;
        }

        // Actualizar estado
        $payment->status = $status;
        $payment->raw_payload = json_encode($payload);
        $payment->save();

        Log::info("Pago {$chargeId} actualizado a: {$status}");
    }
}
