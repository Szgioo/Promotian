<!DOCTYPE html>
<html lang="es">
<head>
    <base href="/Promotian1/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Promotian</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f0f2f5;
            color: #333;
        }

        
        .top-bar {
            display: flex;
            align-items: center;
            padding: 30px 50px 20px 50px; 
            background-color: transparent;
            position: relative;
        }

        .btn-regresar {
            text-decoration: none;
            color: #20314b;
            font-size: 15px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: color 0.3s;
            z-index: 10;
        }

        .btn-regresar:hover { color: #d32f2f; }

        .logo-centrado {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            text-decoration: none;
            z-index: 5;
        }

        .logo-centrado img {
            height: 55px;
            width: auto;
            object-fit: contain;
            display: block;
        }

        
        .auth-wrapper {
            display: flex;
            max-width: 500px; 
            margin: 40px auto 40px auto;
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
            overflow: hidden;
            flex-direction: column;
            padding: 50px 60px;
            box-sizing: border-box;
        }

        .auth-wrapper h1 {
            font-size: 28px;
            color: #20314b;
            margin: 0 0 10px 0;
            text-align: center;
        }

        .auth-wrapper > p {
            color: #666;
            margin-bottom: 30px;
            font-size: 15px;
            text-align: center;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #20314b;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e1e5eb;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        input:focus {
            outline: none;
            border-color: #d32f2f;
        }

        .btn-submit {
            width: 100%;
            background-color: #d32f2f;
            color: white;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.3s;
        }

        .btn-submit:hover { background-color: #b72525; }

        .link-login {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: #666;
        }

        .link-login a {
            color: #20314b;
            text-decoration: none;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .top-bar { padding: 20px; }
            .logo-centrado img { height: 40px; }
            .auth-wrapper { margin: 20px; padding: 40px 30px; }
        }
    </style>
</head>
<body>

    <div class="top-bar">
        <a href="login.php" class="btn-regresar">
            <span style="font-size: 20px;">&larr;</span> Regresar
        </a>
        <a href="/Promotian1/" class="logo-centrado">
            <img src="assets/img/logo.png" alt="Logo Promotian">
        </a>
    </div>

    <div class="auth-wrapper">
        <h1>Recuperar Contraseña</h1>
        <p>Ingresa tu correo electrónico y te enviaremos un enlace para que puedas restablecer tu contraseña.</p>

        <form action="javascript:void(0);" onsubmit="alert('Esta es una demostración. El correo de recuperación ha sido \u0022enviado\u0022 (simulado).'); window.location.href='login.php';">
            <div class="form-group">
                <label>Correo electrónico</label>
                <input type="email" name="correo" placeholder="tucorreo@ejemplo.com" required>
            </div>

            <button type="submit" class="btn-submit">Enviar enlace de recuperación</button>
        </form>

        <div class="link-login">
            ¿Recordaste tu contraseña? <a href="login.php">Inicia sesión</a>
        </div>
    </div>

</body>
</html>
