<html style="padding:0px;margin:0px;border:1px solid red;">
  <head>
    <title></title>
    <style>
      

        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 30px;
        }

      .text-center{text-align: center;}
      .c-smart{color:#1083D6;}
      .c-gray{color:#3f3e3e;}
      .c-gray2{color:#2C2A2A;}
      .c-red{color:#E81010;}
      .c-green{color:#28B105;}
      .c-black{color:#2C2A2A;}
      .s-10{font-size: 10px;}
      .s-8{font-size: 8px;}
      .s-14{font-size: 14px;}
      .s-12{font-size: 11px;}
      .s-10{font-size: 10px;}
      .s-16{font-size: 16px;}
      .s-17{font-size: 17px;}
      .s-22{font-size: 30px;}
      .m-t-5{margin-top: 5px;}
      .m-b-5{margin-bottom: 10px;}
      .b-t-red{border-top:2px solid #D62222;}
      .b-t-smart{border-top:5px solid #2C2A2A;}
      .p-r-10{padding-right:10px;}
    </style>
  </head>
  <body>
      <table width="100%" >
          <tr>
            <td style="width:130px;">
              <img src='{{ public_path("storage/logo.png") }}'  style='width: 130px;height:50px;'>
            </td>
            <th class="c-smart s-14 text-center">RECIBO DE PAGO</th>
            <th class="text-center">FOLIO : <span class="c-red s-17">SN00{{$id}}</span>
              <div class="s-8">{{ $fecha }}</div>
              <div class="s-8">{{ $hora }}</div>
            </th>
          </tr>

          <tr>
            <td colspan="3" class="b-t-red"></td>
          </tr>

          <tr>
            <td colspan="3" class="b-t-smart"></td>
          </tr>

           <tr>
            <td colspan="2" class="s-14">
                <div class="c-gray2 m-t-5"><strong class="p-r-10 s-12">NOMBRE DEL CLIENTE:</strong>
                    <span class="c-black s-10">{{ $row->cliente }}</span>
                </div>
                <div class="c-gray2 m-t-5"><strong class="p-r-10 s-12">COMUNIDAD:</strong>
                  <span class="c-black s-10">{{ $row->comunidad }}</span>
              </div>
                <div class="c-gray2 m-t-5"><strong class="p-r-10 s-12">MES DE PAGO:</strong>
                    <span class="c-black s-10">{{ $row->mes }}</span>
                </div>
                <div class="c-gray2 m-t-5"><strong class="p-r-10 s-12">AÑO DE PAGO:</strong>
                    <span class="c-black s-10">{{ $row->anio }}</span>
                </div>
                <div class="c-gray2 m-t-5"><strong class="p-r-10 s-12">TIPO DE PAGO:</strong>
                    <span class="c-black s-10">{{ $row->tipo_pago }}</span>
                </div>
                <div class="m-t-5 c-gray2"><strong class="p-r-10 s-12">PAGO A DESTIEMPO:</strong> <span class="c-black s-10">${{ $row->recargo }}</span></div>
                <div class="m-t-5 c-gray2"><strong class="p-r-10 s-12">DESCUENTO:</strong> <span class="c-black s-10">${{ $row->descuento }}</span></div>
                <div class="m-t-5 c-gray2"><strong class="p-r-10 s-12">SUBTOTAL:</strong> <span class="c-black s-10">${{ $row->subtotal }}</span></div>
                <div class="m-t-5 c-gray2"><strong class="p-r-10 s-12">TOTAL:</strong>  <strong class="c-smart s-17">${{ $row->total }}</strong></div>
            </td>
            <th>
              <div  class="c-smart text-center s-22">PAGADO</div>
            </th>
          </tr>

          <tr>
            <td colspan="3" class="text-center c-gray s-12">
                <div>¡¡¡GRACIAS POR SU PREFERENCIA!!! </div>
                <div>Soporte y atención a clientes</div>
                <table width="100%">
                  <tr>
                    <th class="s-10 c-gray text-center">TEXCALTITLAN</th>
                    <th class="s-10 c-gray text-center">ALMOLOYA DE ALQ</th>
                    <th class="s-10 c-gray text-center">COATEPEC HARINAS</th>
                  </tr>
                  <tr>
                    <td>
                      <div class="s-10 c-gray text-center">722 680 4742</div>
                      <div class="s-10 c-gray text-center">716 263 5469</div>
                      <div class="s-10 c-gray text-center">722 598 8169</div>
                    </td>
                    <td>
                      <div class="s-10 c-gray text-center">722 530 2757</div>
                      <div class="s-10 c-gray text-center">716 144 7320</div>
                      <div class="s-10 c-gray text-center">722 408 3377</div>
                    </td>
                    <td>
                      <div class="s-10 c-gray text-center">722 535 6863</div>
                    </td>
                  </tr>
                </table>
                <div>En horario de Lunes a Sábado de 9:00 a 19:00 </div>
                <div>Conserve este tiket para cualquier aclaración.  </div>
                <div> Este documento NO es un comprobante fiscal si lo requiere solicitelo.</div>
            </td>
          </tr>

          <tr>
            <th colspan="3" class="text-center s-12">Para transferencia o depósito bancario</th>
          </tr>

          <tr>
            <td colspan="3" class="text-center c-gray s-10">
              <table width="100%">
                  <tr>
                    <th class="s-10 c-gray text-center">Banco BBVA</th>
                    <th class="s-10 c-gray text-center">Bancoppel</th>
                  </tr>
                  <tr>
                    <td>
                      <div class="s-10 c-gray text-center">Número de tarjeta </div>
                      <div class="text-center"><strong class="s-10 c-gray ">1203668010</strong></div>
                    </td>
                    <td>
                      <div class="s-10 c-gray text-center">Número de tarjeta </div>
                      <div class="text-center"><strong class="s-10 c-gray text-center">4169 1606 1192 9994</strong></div>
                    </td>
                  </tr>
                </table>

            </td>
          </tr>

      </table>

  </body>
</html>

