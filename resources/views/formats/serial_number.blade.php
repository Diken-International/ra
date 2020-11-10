<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Laravel PDF</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  </head>
  <body>
    <h2 class="mb-3">
      <center>
        {{ $number->name }}
      </center>  
    </h2>
    <br>
    <table class="table">
      <tbody>
       <tr>
        <td>
          <div class="visible-print text-center">
              <img src="data:image/png;base64, {{ base64_encode(QrCode::format('png')->size(500)->generate("$number->serial_number")) }} ">
          </div>
        </td>
       </tr> 
      </tbody>
    </table>
    <h2>Informacion del Equipo</h2>
    <br>
    <br>
    <h3>Numero de serie: {{ $number->serial_number }}</h3>
    <h3>Empresa o Compañia: {{ $number->Bussines }}</h3>
  </body>
</html>