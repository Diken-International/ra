<html>
    <head>
        <title>Carta de recepción</title>
        <link rel="stylesheet"
              href="https://cdnjs.cloudflare.com/ajax/libs/tachyons/4.11.1/tachyons.min.css"
              integrity="sha512-d0v474klOFSF7qD9WDvyRxAvXaWSxCHDZdnBSZQjo8BpVr6vpjwAgqetpqkKP38DzlOzdVPaLVnzzW1Ba8wB9w=="
              crossorigin="anonymous" />
        <style>
            h2{
              margin-top: 0px;
            }
        </style>
    </head>
    <body class="system-sans-serif">
    <div class="center mw5 mw7 hidden ba mv4">
      <h1 class="f4 bg-near-black  mv0 pv2 ph3 bg-blue white tc">CARTA DE RECEPCIÓN Y LIBERACIÓN.</h1>
      <div class="pa3 bt">
        <p class="f6 f5-ns lh-copy mv0 tj">
            Por este medio hago constar que el equipo que se detalla a continuacion se encuentra en muy buena calidad y
            que esta bajo resguardo de la empresa <b>DIKEN INTERNATIONAL</b> dicho equipo con los servicios acordados.
            <br>
            La descripcion del equipo, se detalla a continuacion.
        </p>
      </div>
      <div class="pa3">
          <h2 class="blue">EQUIPO</h2>
          <div class="pt1">
              <div class="dib w-30 b">Nombre del equipo:</div>
              <div class="dib w-60">{{ isset($product_user->product->name) ? $product_user->product->name : 'Nombre del equipo' }}</div>
          </div>
          <div class="pv1">
              <div class="dib w-30 b">Número de serie:</div>
              <div class="dib w-60">{{ isset($product_user->serial_number) ? $product_user->serial_number : 'Número de serie' }}</div>
          </div>
          <div class="pt3 tc">
              <div class="dib w-20 b ba pa1">Ayuda Visual</div>
              <div class="dib w-30 b ba pa1">Manual de Equipo</div>
              <div class="dib w-20 b ba pa1">Carta Garantia</div>
              <div class="dib w-20 b ba pa1">Capacitación</div>
          </div>
          <div class="pt1 tc">
              <div class="dib w-20">{{ (in_array('Ayuda Visual',$services)) ? 'SI' : 'NO', true }}</div>
              <div class="dib w-30">{{ (in_array('Manual de Equipo',$services)) ? 'SI' : 'NO', true }}</div>
              <div class="dib w-20">{{ (in_array('Carta Garantia',$services)) ? 'SI' : 'NO', true }}</div>
              <div class="dib w-20">{{ (in_array('Capacitación',$services)) ? 'SI' : 'NO', true }}</div>
          </div>
      </div>
      <div class="pa3">
          <h2 class="blue">PRUEBA DE FUNCIONALIDAD</h2>
          <div class="pt1">
              <div class="dib w-30">E - EXCELENTE</div>
              <div class="dib w-30">B = BUENA</div>
              <div class="dib w-30">R = REGULAR</div>
          </div>
          <div class="pt3">
              <div class="dib w-30 b">Prueba de funcionalidad</div>
              <div class="dib w-30">{{ $functionality }}</div>
          </div>
          <div class="pt2">
              <div class="dib w-30 b">Prueba de espuma</div>
              <div class="dib w-30">{{ $foam }}</div>
          </div>
          <div class="pv3">
              <p>Mediante el presente documento se realiza la entraga formal de los equipos que se indican en el punto 1
              "EQUIPO" para el cumplimiento de las actividades laborales del FUNCIONARIO RESPONSABLE, quien declara
              recepción de los mismos en buen estado y se compromete a cuidar de los recurso y hacer uso de ellos para
              los fines establecidos.</p>
              <p>Fecha de entrega: <b> {{ \Carbon\Carbon::now() }}</b> </p>
          </div>
      </div>
      <div class="pa3">
          <h2 class="blue">ENTREGA</h2>
          <div class="pv2">
              <div class="dib w-50 b">Representante CSS by DIKEN</div>
              <div class="dib w-40 b">Validación del Cliente</div>
          </div>
          <div class="pt1">
              <div class="dib w-50 bg-black-05" style="height: 50px">Firma</div>
              <div class="dib w-40 bg-black-05" style="height: 50px">Firma</div>
          </div>
          <div class="pt1">
              <div class="dib w-50 bg-black-05" style="height: 50px">Nombre</div>
              <div class="dib w-40 bg-black-05" style="height: 50px">Nombre: {{ isset($product_user->client->name) ? $product_user->client->name : '' }}</div>
          </div>
      </div>
    </div>
    </body>
</html>
