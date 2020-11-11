<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Reporte de Eqipos</title>
  </head>
  <style type="text/css">
    h1{
      margin-top: 50px;
      /* margin-left: 300px; */
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
    <h2>Informacion del Equipo</h2>
    <br>
    <h3>Numero de serie: {{ $number->serial_number }}</h3>
    <h3>Empresa o Compañia: {{ $number->company_name }}</h3>
    <h3>Tipo de Equipo: (Prestado ò Comodato) 
      @if ($number->product_type == 'own')
        Prestado
      @else
        Comodato   
      @endif 
    </h3>
    <p class="foot">Informaciòn generada por <a href=""><u>diken.mx</u></a></p>
  </body>
</html>