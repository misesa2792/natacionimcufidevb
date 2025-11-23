<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'ses_payments';
    protected $primaryKey = 'idpayments'; 

    protected $fillable = [
        'iduser',
        'idsuscripcion',
        'provider',
        'provider_charge_id',
        'status',
        'amount',
        'currency',
        'description',
        'payer_email',
        'raw_payload',
    ];

    // Helper para obtener monto en pesos si guardas en centavos
    public function getAmountInPesosAttribute(): float
    {
        return $this->amount / 100;
    }
}
