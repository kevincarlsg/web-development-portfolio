<!-- resources/views/emails/send_password.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Tu Contraseña de Acceso</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="margin: 0; padding: 20px; background-color: #f4f4f4;">
        <tr>
            <td>
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; padding: 20px; margin: auto;">
                    <tr>
                        <td style="text-align: center; padding: 20px;">
                            <img src="https://www.debate.com.mx/img/2022/10/14/hasbulla.jpg" alt="Logotipo" style="max-width: 150px; height: auto; margin-bottom: 20px;">
                            <h1 style="color: #333333; font-size: 24px; margin: 0;">Bienvenido a la plataforma</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0 20px;">
                            <p style="color: #666666; font-size: 16px; line-height: 1.5;">
                                Tu cuenta ha sido creada exitosamente.
                            </p>
                            <p style="color: #666666; font-size: 16px; line-height: 1.5;">
                                Para acceder a tu cuenta, utiliza la siguiente contraseña temporal:
                            </p>
                            <p style="font-size: 18px; color: #333333; font-weight: bold; padding: 10px 0; text-align: center; background-color: #f0f0f0; border-radius: 5px;">
                                Contraseña: {{ $password }}
                            </p>
                            <p style="color: #666666; font-size: 16px; line-height: 1.5;">
                                Te recomendamos cambiar esta contraseña después de iniciar sesión para mayor seguridad.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 20px; text-align: center;">
                            <p style="color: #999999; font-size: 14px; margin: 0;">
                                Saludos,<br>
                                El equipo de soporte de HASH
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
