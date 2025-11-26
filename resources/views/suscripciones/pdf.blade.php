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
      
     @include('suscripciones.pdf_body',['title' => 'no'])

    <br>

    <table width="100%">
      <tr>  
        <th width="30%"></th>
        <th>
          RECIBIO:____________________________________________
        </th>
        <th width="30%"></th>
      </tr>
      <tr>
        <td></td>
        <td class="text-center">(Nombre y firma)</td>
        <td></td>
      </tr>
    </table>

    <br>

     @include('suscripciones.pdf_body',['title' => 'si'])

    

  </body>
</html>

