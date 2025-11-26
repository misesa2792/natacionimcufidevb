 <table width="100%" >
          <tr>
            <td style="width:130px;">
              <img src='{{ public_path("mass/images/logo/natacion.png") }}'  style='width: 130px;height:50px;'>
            </td>
            <th class="c-gray2 s-14 text-center">
              <div class="s-16 c-red">ORDEN DE PAGO</div>
              <div>ALBERCA SEMIOLÍMPICA VALLE DE BRAVO</div>
            </th>
            <th class="text-center">
              <img src='{{ public_path("mass/images/logo/imcufide.png") }}'  style='width: 160px;height:40px;'>
            </th>
          </tr>
          <tr>
            <td colspan="2"></td>
            <td class="text-center"><div class="s-8">{{ $row->fi_formateada }}</div>
            </td>
          </tr>

          <tr>
            <td colspan="3" class="b-t-red"></td>
          </tr>

          <tr>
            <td colspan="3" class="b-t-smart"></td>
          </tr>

           <tr>
            <td colspan="2" class="s-14">
                <div class="c-gray2 m-t-5"><strong class="p-r-10 s-12">Nombre del alumno:</strong>
                    <span class="c-black s-10">{{ $row->nombre }}</span>
                </div>
                <div class="c-gray2 m-t-5"><strong class="p-r-10 s-12">CURP:</strong>
                    <span class="c-black s-10">{{ $row->curp }}</span>
                </div>
                <div class="c-gray2 m-t-5"><strong class="p-r-10 s-12">Nivel:</strong>
                  <span class="c-black s-10">{{ $row->nivel }} <i>({{ $row->plan }})</i></span>
                </div>
            </td>
            <th>
                <div  class="c-red text-center s-22">FOLIO</div>
                <span class="s-17">{{ $folio }}</span>
            </th>
          </tr>
      </table>

      <br>

      <table width="100%" class="my-table">
        <tr>
          <th class="text-center bg-dark">#</th>
          <th class="text-center bg-dark">Estatus</th>
          <th class="text-center bg-dark" colspan="2">Fechas reservadas</th>
          <th class="text-center bg-dark" width="100">Horarios</th>
        </tr>
        @foreach ($rows as $r)
          <tr>
            <td class="text-center">{{ $j++ }}</td>
            <td class="text-center">Reservado</td>
            <td class="text-center" colspan="2">{{ $r->fecha_formateada }}</td>
            <td class="text-center" >{{ $r->time_start .' - ' .$r->time_end }}</td>
          </tr>
        @endforeach
          
      </table>

      <table width="100%" class="my-table">
        <tr>
          <td>

            @if($title == 'si')
                  <div>
                    <strong class="s-10">OPCIONES DE PAGO:</strong>
                  </div>
                      <ul >
                          <li class="s-8">
                              Coordinación de Ingresos: La Casona, Porfirio Díaz #100, Valle de Bravo
                          </li>
                          <li class="s-8">
                              Ventanilla: <strong>ÚNICAMENTE EN CASONA</strong>
                          </li>
                      </ul>
                      <p class="s-8">ATENCIÓN: LOS PAGOS DEBERÁN REALIZARSE ÚNICAMENTE EN LA CASONA EN LA 
                                  COORDINACIÓN DE INGRESOS. TRANSFERENCIAS BANCARIAS NO SERÁN ACEPTADAS 
                                  COMO MÉTODO DE PAGO.</p>
                      
                <div>
                    <strong class="s-10">DETALLE DEL PAGO:</strong>
                </div>

                <p>Concepto: {{ $row->nombre }} - Mensualidad Alberca Mes de {{ $row->mes }} de {{ $row->year }}</p>
                <p>Fecha solicitud: Del 1º al 10 de {{ $row->mes }}</p>
                <p>Beneficiario: Municipio de Valle de Bravo</p>
            @endif

          </td>
          <td width="100">
              <table width="100%" class="my-table">
                <tr>
                  <th class="text-right" style="border:0px;">Subtotal:</th>
                  <td class="text-center">
                    <div class="s-10">${{ $row->monto_general }}</div>
                  </td>
                </tr>
                <tr>
                  <th class="text-right" style="border:0px;">Descuento:</th>
                  <td class="text-center">
                    <div class="s-10">${{ $row->descuento }}</div>
                  </td>
                </tr>
                <tr>
                  <th class="text-right" style="border:0px;">Total a pagar:</th>
                  <th class="text-center">
                    <div class="s-10">${{ $row->monto }}</div>
                  </th>
                </tr>
              </table>
          </td>
        </tr>
      </table>

