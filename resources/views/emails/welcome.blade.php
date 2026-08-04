<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a Devstagram</title>
</head>
<body style="margin: 0; background: #f3f4f6; font-family: Arial, sans-serif; color: #1f2937;">
    <div style="max-width: 600px; margin: 40px auto; padding: 32px; background: #ffffff; border-radius: 12px;">
        <h1 style="margin-top: 0; color: #0284c7;">¡Bienvenido a Devstagram!</h1>

        <p>Hola, {{ $user->name }}.</p>

        <p>
            Tu usuario <strong>{{ $user->username }}</strong> se creó correctamente.
            Ya puedes publicar imágenes, comentar y buscar otros perfiles.
        </p>

        <p style="margin-bottom: 0; color: #6b7280;">
            Este correo confirma que el envío de mensajes de Devstagram funciona correctamente.
        </p>
    </div>
</body>
</html>
