<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Reporte de Eqipos</title>
  </head>
  <style type="text/css">
    h1{
      margin-top: 50px;
    }
    .qr{
      margin-left: 99px;
    }
    .foot{
      padding-top: 50px;
      margin-left: 400px;
    }
  </style>
  <body>
    <h1>
        <center>
          {{ $number->name }}
        </center>
    </h1>
    <br>
    <table>
      <tbody>
       <tr>
        <td>
          <div class="visible-print text-center qr">
              <img src="data:image/png;base64, {{ base64_encode(QrCode::format('png')->size(500)->generate(" $number->serial_number")) }} ">
          </div>
        </td>
       </tr>
      </tbody>
    </table>
    <br>
    <br>
    <h2>INFORMACIÓN</h2>
    <p><b>Numero de serie:</b> {{ $number->serial_number }}</p>
    <p><b>Empresa o Compañia:</b> {{ $number->company_name }}</p>
    <p><b>Tipo de Equipo:</b>
      @if ($number->product_type == 'own')
        Prestado
      @else
        Comodato
      @endif
    </p>
    <p class="foot">Información generada por <b>diken.mx</b></p>
  </body>
</html>
