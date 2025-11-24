<html style="padding:0px;margin:0px;border:1px solid red;">
  <head>
    <title></title>
    <style>
      

        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 30px;
        }

        .my-table {
                border: 0px solid #000000;
                border-collapse: collapse;
            }
        .my-table td,
        .my-table th {
            border: 1px solid #000000;
            border-collapse: collapse;
            padding: 2px 3px;
            font-family: Inter, sans-serif;
            font-size:7px;
        }
        .my-table .blanco td,
        .my-table .blanco th {
            border-left:1px solid #000000;
            border-right:1px solid #000000;
            border-bottom:1px solid #000000;
            border-top:0px solid #000000;
        }
        .my-table .blanco-last td,
        .my-table .blanco-last th {
            border-left:1px solid #000000;
            border-right:1px solid #000000;
            border-bottom:0px solid #000000;
            border-top:0px solid #000000;
        }
        .my-table .no-borders {
            border-left:0px solid #000000;
            border-right:0px solid#000000;
            border-bottom:0px solid #000000;
            border-top:0px solid #000000;
        }

      .text-center{text-align: center;}
      .text-right{text-align: right;}
      .c-smart{color:#1083D6;}
      .c-gray{color:#3f3e3e;}
      .c-gray2{color:#2C2A2A;}
      .c-red{color:#963636;}
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
      .b-t-red{border-top:2px solid #963636;}
      .b-t-smart{border-top:5px solid #2C2A2A;}
      .p-r-10{padding-right:10px;}
      .bg-dark{background:#5a5a5a;color:#ffffff}
    </style>
  </head>
  <body>
      
      <table width="100%" >
          <tr>
            <td style="width:130px;">
              <img src='{{ public_path("mass/images/logo/imcufide.png") }}'  style='width: 190px;height:50px;'>
            </td>
            <th class="c-gray2 s-14 text-center">COMPROBANTE DE SUSCRIPCIÓN</th>
            <th class="text-center">FOLIO : <span class="c-red s-17">{{ $folio }}</span>
              <div class="s-8">{{ $row->fi_formateada }}</div>
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
                <div class="c-gray2 m-t-5"><strong class="p-r-10 s-12">Nombre del nadador:</strong>
                    <span class="c-black s-10">{{ $row->nombre }}</span>
                </div>
                <div class="c-gray2 m-t-5"><strong class="p-r-10 s-12">CURP:</strong>
                    <span class="c-black s-10">{{ $row->curp }}</span>
                </div>
                <div class="c-gray2 m-t-5"><strong class="p-r-10 s-12">Plan contrato:</strong>
                  <span class="c-black s-10">{{ $row->plan }}</span>
                </div>
                <div class="c-gray2 m-t-5"><strong class="p-r-10 s-12">Tipo de pago:</strong>
                    <span class="c-black s-10">{{ $row->pago }}</span>
                </div>
                <div class="c-gray2 m-t-5"><strong class="p-r-10 s-12">Fecha inicio:</strong>
                    <span class="c-black s-10">{{ $row->fi_formateada }}</span>
                </div>
                <div class="c-gray2 m-t-5"><strong class="p-r-10 s-12">Fecha fin:</strong>
                    <span class="c-black s-10">{{ $row->ff_formateada }}</span>
                </div>
                <div class="c-gray2 m-t-5"><strong class="p-r-10 s-12">Total de visitas permitidas por plan:</strong>
                    <span class="c-black s-10">{{ $row->max_visitas_mes }}</span>
                </div>
            </td>
            <th>
              <div  class="c-smart text-center s-22">PAGADO</div>
            </th>
          </tr>
      </table>

      <br>

      <table width="100%" class="my-table">
        <tr>
          <th class="text-center bg-dark">#</th>
          <th class="text-center bg-dark">Estatus</th>
          <th class="text-center bg-dark">Fechas reservada</th>
          <th class="text-center bg-dark">Horarios</th>
        </tr>
        @foreach ($rows as $r)
          <tr>
            <td class="text-center">{{ $j++ }}</td>
            <td class="text-center">Reservado</td>
            <td class="text-center">{{ $r->fecha_formateada }}</td>
            <td class="text-center">{{ $r->time_start .' - ' .$r->time_end }}</td>
          </tr>
        @endforeach
          <tr>
            <th colspan="3" class="text-right">Total:</th>
            <th class="text-center">${{ $row->monto }}</th>
          </tr>
      </table>

        <table width="100%" >
          <tr>
            <td colspan="3" class="text-center c-gray s-12">
                <br>
                <div><strong>IMCUFIDE Valle de Bravo</strong> </div>
                <div><strong>2025 - 2027</strong> </div>
                <div>Conserve este tiket para cualquier aclaración.  </div>
                <div> Este documento NO es un comprobante fiscal si lo requiere solicitelo.</div>
            </td>
          </tr>
      </table>

  </body>
</html>

