<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reservas pendientes por gestionar</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f3f4f6; padding:20px;">

<div style="max-width:600px; margin:0 auto; background:#ffffff; border-radius:10px; padding:20px;">

    <h2 style="color:#111827; margin-bottom:10px;">
        Hola, {{ $chofer->nombre ?? $chofer->name }} 👋
    </h2>

    <p style="color:#4b5563; font-size:14px; line-height:1.5;">
        Este es un recordatorio automático de <strong>Aventones</strong>.
        Actualmente tenés <strong>{{ $reservas->count() }}</strong> reserva(s)
        en estado <strong>PENDIENTE</strong> que llevan varios minutos sin respuesta.
    </p>

    <p style="color:#4b5563; font-size:14px; line-height:1.5;">
        Te recomendamos revisarlas y aceptarlas o rechazarlas cuanto antes
        para brindar una mejor experiencia a tus pasajeros.
    </p>

    <h3 style="margin-top:20px; color:#111827; font-size:16px;">Detalle de reservas pendientes:</h3>

    <ul style="font-size:13px; color:#374151; padding-left:18px;">
        @foreach ($reservas as $reserva)
            @php
                $ride = $reserva->ride;
            @endphp

            <li style="margin-bottom:12px;">
                <strong>Ride:</strong> {{ $ride->titulo ?? 'Sin título' }}<br>
                <strong>Origen:</strong> {{ $ride->lugar_salida ?? 'N/A' }}
                &nbsp; | &nbsp;
                <strong>Destino:</strong> {{ $ride->lugar_llegada ?? 'N/A' }}<br>
                <strong>Fecha y hora del ride:</strong>
                {{ $ride->fecha_hora ?? 'N/A' }}<br>
                <strong>Reserva creada:</strong>
                {{ optional($reserva->created_at)->format('d/m/Y H:i') }}
            </li>
        @endforeach
    </ul>

    <p style="margin-top:25px; font-size:12px; color:#9ca3af;">
        Este mensaje se generó automáticamente. No respondas a este correo.
    </p>

</div>

</body>
</html>
