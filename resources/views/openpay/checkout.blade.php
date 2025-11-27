@extends('layouts.pago')

@section('content')

    <style>
      
        .card {
            padding: 15px 25px;
            border-radius: 12px;
            box-shadow: 0px 4px 15px rgba(0,0,0,0.1);
            animation: fadeIn 0.4s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
            color: #444;
        }
        input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d1d1;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        input:focus {
            border-color: #009ef7;
            box-shadow: 0 0 3px rgba(0,158,247,0.4);
            outline: none;
        }

        button {
            width: 100%;
            background: #009ef7;
            color: white;
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 5px;
        }

        button:hover {
            background: #007ad1;
        }

        .success {
            background: #dff8e1;
            color: #2b7a37;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            border-left: 4px solid #3bb54a;
        }

        .error {
            background: #ffe0e0;
            color: #b30000;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            border-left: 4px solid #dd3c3c;
        }
        .m-b-md{margin-bottom:15px;}
    </style>

            <div class="row mb-3 mt-3">
                <div class="col-12 text-center">
                    <a href="{{ route($pageModule .'.pagar') }}" >
                        << Cancelar acción
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="error">
                    @foreach($errors->all() as $error)
                        • {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            <form id="payment-form" action="{{ route('openpay.charge') }}" method="POST">
            @csrf

            <div class="card">
                <label class="mt-3">Nadador</label>
                <div class="m-b-md ses-text-blue">{{ $row->nombre }}</div>

                <label>Nivel</label>
                <div class="m-b-md ses-text-blue">{{ $row->nivel }} <i>({{ $row->plan }})</i></div>
           
                <table class="table table-bordered">
                    <tr>
                        <th class="text-center">Fechas reservadas</th>
                    </tr>
                    @foreach($rowsFechas as $f)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($f->fecha)->translatedFormat('j \d\e F \d\e Y') }}</td>
                        </tr>
                    @endforeach
                </table>

                <table class="table table-bordered">
                    <tr>
                        <th class="text-center" colspan="2">Monto a pagar</th>
                    </tr>
                    <tr>
                        <td width="30%" class="text-right">Subtotal:</td>
                        <th class="text-black">${{ $data['precio'] }}</th>
                    </tr>
                    <tr>
                        <td width="30%" class="text-right">Descuento aplicado:</td>
                        <td><strong class="text-black">${{ $data['descuento'] }}</strong> - {{ $row->desc_descuento }} <i>({{ $row->descuento }}%)</i></td>
                    </tr>
                    <tr>
                        <td width="30%" class="text-right">Monto a pagar</td>
                        <th class="text-black">${{ $data['total'] }} MXN</th>
                    </tr>
                </table>

                <label>Correo electrónico</label>
                <input type="email" name="email" value="{{ $row->titular_email }}" placeholder="Correo electrónico">
            </div>

            <div class="card mt-3">

                    <img src="{{ asset('mass/images/logo/openpay.png') }}" alt="Openpay by BBVA">

                    <h3 class="text-center">Pago con tarjeta de crédito o débito</h3>

                    <input type="hidden" name="token_id" id="token_id">
                    <input type="hidden" name="device_session_id" id="device_session_id">
                    <input type="hidden" name="curp" value="{{ $curp }}">
                    <input type="hidden" name="time" value="{{ $time }}">
                    <input type="hidden" name="idy" value="{{ $idy }}">
                    <input type="hidden" name="idm" value="{{ $idm }}">
                    
                    <label>Nombre del titular</label>
                    <input type="text"  data-openpay-card="holder_name" placeholder="Como aparece en la tarjeta" required>
                
                    <label>Número de tarjeta</label>
                    <input type="text" data-openpay-card="card_number" placeholder="" required>

                    <div class="row">
                        <div class="col-12">
                            <label>Fecha de expiración</label>
                        </div>
                        <div class="col-6">
                            <input type="text" data-openpay-card="expiration_month" placeholder="Mes" required>
                        </div>
                        <div class="col-6">
                            <input type="text" data-openpay-card="expiration_year" placeholder="Año" required>
                        </div>
                    </div>
                    
                    <label>Código de seguridad</label>
                    <input type="text" data-openpay-card="cvv2" placeholder="3 dígitos" required>

                    <button id="pay-button">
                        <span class="btn-text">Pagar ahora</span>
                        <span class="btn-spinner d-none"></span>
                    </button>
            </div>
            
            </form>



{{-- JS oficial de Openpay --}}
<script src="https://js.openpay.mx/openpay.v1.min.js"></script>
<script src="https://js.openpay.mx/openpay-data.v1.min.js"></script>

<script>
    OpenPay.setId('{{ $merchantId }}');
    OpenPay.setApiKey('{{ $publicKey }}');
    OpenPay.setSandboxMode({{ $production ? 'false' : 'true' }});

    const deviceSessionId = OpenPay.deviceData.setup(
        "payment-form",
        "device_session_id"
    );

    const form = document.getElementById('payment-form');
    const payButton = document.getElementById('pay-button');
    const btnText = payButton.querySelector('.btn-text');
    const btnSpinner = payButton.querySelector('.btn-spinner');

    const setLoading = (loading) => {
        if (loading) {
            payButton.disabled = true;
            payButton.classList.add('is-loading');
            btnText.textContent = 'Procesando pago...';
            btnSpinner.classList.remove('d-none');
        } else {
            payButton.disabled = false;
            payButton.classList.remove('is-loading');
            btnText.textContent = 'Pagar ahora';
            btnSpinner.classList.add('d-none');
        }
    };

    const successCallback = function(response) {
        const tokenId = response.data.id;
        document.getElementById('token_id').value = tokenId;
        form.submit();
    };

    const errorCallback = function(response) {
        let message = response.data?.description || response.message || "Error desconocido";
        alert("Error al generar token: " + message);
        setLoading(false);
    };

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        setLoading(true);

        OpenPay.token.extractFormAndCreate(
            form,
            successCallback,
            errorCallback
        );
    });
</script>

<style>
    #pay-button {
    position: relative;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    gap: 6px;
}

#pay-button .btn-spinner {
    font-size: 0.9rem;
    opacity: 0.9;
}

#pay-button.is-loading {
    opacity: 0.7;
    cursor: not-allowed;
}
</style>
@stop
