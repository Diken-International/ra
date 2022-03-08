@component('mail::message')
<div style="text-align: right; padding: 15px 0">
    <img src="{{asset('/images/diken.png')}}" alt="Logotipo Diken" width="100" />
</div>

# Hola.
## Tu mantenimiento ha sido terminado.

Un mantenimiento ha sido realizado por nuestro equipo tecnico Cleaning Control  System en las instalaciones de {{ $client['company_name'] }}.

Consulta los detalles del mantenimiento realizado por nuestro equipo.
@component('mail::button', ['url' => $url_progress])
    Descargar reporte de servicio
@endcomponent

@component('mail::table')
    | Producto       | Tipo de servicio         |
    | ------------- |:-------------:|
    @foreach($service_reports as $service)
        | {{ $service->product_name }}      | {{ type_activity($service->service_activity) }} |
    @endforeach
@endcomponent

Con el fin de mejorar nuestro servicio te invitamos a llenar nuestra encuesta.

@component('mail::button', ['url' => $url])
    Encuesta de servicio
@endcomponent

Gracias,<br>
{{ config('app.name') }}

@endcomponent
