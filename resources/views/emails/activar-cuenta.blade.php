<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Activa tu cuenta</title>
</head>
<body style="font-family: Arial, sans-serif; background-color:#f3f4f6; padding:20px;">

    <div style="
        max-width:600px;
        margin:0 auto;
        background:#ffffff;
        border-radius:10px;
        padding:25px;
        box-shadow:0 2px 6px rgba(0,0,0,0.1);
    ">

        <h2 style="color:#111827; font-size:22px; margin-bottom:10px;">
            Hola, {{ $user->nombre }} {{ $user->apellido }} 👋
        </h2>

        <p style="color:#4b5563; font-size:15px; line-height:1.5;">
            Gracias por registrarte en <strong>Aventones</strong>.
        </p>

        <p style="color:#4b5563; font-size:15px; line-height:1.5;">
            Tu cuenta ha sido creada con el rol de <strong>{{ $user->rol }}</strong>, pero todavía está
            <strong style="color:#dc2626;">pendiente de activación</strong>.
        </p>

        <p style="color:#4b5563; font-size:15px; line-height:1.5;">
            Para activar tu cuenta y comenzar a usar la plataforma, hacé clic en el siguiente botón:
        </p>

        <div style="text-align:center; margin:30px 0;">
            <a href="{{ $url }}"
               style="
                    background:#2563eb;
                    color:#ffffff;
                    padding:12px 24px;
                    text-decoration:none;
                    border-radius:6px;
                    font-weight:bold;
                    font-size:15px;
               ">
               Activar mi cuenta
            </a>
        </div>

        <p style="color:#4b5563; font-size:14px;">
            Si el botón no funciona, podés copiar y pegar este enlace en tu navegador:
        </p>

        <p style="word-break: break-all; font-size:12px; color:#6b7280;">
            {{ $url }}
        </p>

        <hr style="margin:25px 0; border:none; border-top:1px solid #e5e7eb;">

        <p style="font-size:12px; color:#9ca3af;">
            Si vos no creaste esta cuenta, simplemente ignorá este mensaje.
        </p>

    </div>

</body>
</html>
