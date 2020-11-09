<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Laravel PDF</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  </head>
  <body>
    <h2 class="mb-3">Customer List</h2>
    <table class="table table-bordered">
    <thead>
      <tr>
        <th>Name</th>
        <th>E-mail</th>
        <th>Phone</th>
        <th>DOB</th>
      </tr>
      </thead>
      <tbody>
       <tr>
        <td>{{ $number->serial_number }}</td>
        <td>
          <div class="visible-print text-center">
              <img src="data:image/png;base64, {{ base64_encode(QrCode::format('png')->size(100)->generate("$number->serial_number")) }} ">
          </div>
        </td>
       </tr> 
      </tbody>
    </table>
  </body>
</html>