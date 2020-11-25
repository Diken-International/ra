@component('mail::message')
<div style="text-align: right; padding: 15px 0">
    <img src="{{asset('/images/diken.png')}}" alt="Logotipo Diken" width="100" />
</div>

# Hola, {{ $client['name'] }}.
## Tu mantenimiento ha sido terminado.

Un mantenimiento ha sido realizado por nuestro equipo tecnico CSS.

Con el fin de mejorar nuestro servicio te invitamos a llenar nuestra encuesta.

@component('mail::table')
    | Producto       | Tipo de servicio         |
    | ------------- |:-------------:|
    @foreach($service_reports as $service)
        | {{ $service->product_name }}      | {{ type_activity($service->service_activity) }} |
    @endforeach
@endcomponent

@component('mail::button', ['url' => $url])
Encuesta de servicio
@endcomponent

Gracias,<br>
{{ config('app.name') }}

@endcomponent
