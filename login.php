<!DOCTYPE html>
<html lang="es">
<head>
    <base href="/Promotian1/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Promotian</title>
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
            max-width: 900px; 
            margin: 20px auto 40px auto;
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
            overflow: hidden;
            min-height: 500px;
        }


        .panel-izquierdo {
            width: 45%;
            background-image: url('assets/img/fondo-registro.jpg');
            background-size: cover;
            background-position: center;
        }

        .panel-derecho {
            width: 55%;
            padding: 50px 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .panel-derecho h1 {
            font-size: 28px;
            color: #20314b;
            margin: 0 0 5px 0;
        }

        .panel-derecho > p {
            color: #666;
            margin-bottom: 30px;
            font-size: 15px;
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

        .olvide-contrasena {
            text-align: right;
            margin-top: -10px;
            margin-bottom: 15px;
        }

        .olvide-contrasena a {
            font-size: 13px;
            color: #d32f2f;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .olvide-contrasena a:hover {
            text-decoration: underline;
        }

        .link-registro {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: #666;
        }

        .link-registro a {
            color: #20314b;
            text-decoration: none;
            font-weight: bold;
        }

        .alerta {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }
        .alerta-exito { background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alerta-error { background-color: #fee2e2; color: #991b1b; border: 1px solid #f87171; }

        @media (max-width: 768px) {
            .top-bar { padding: 20px; }
            .logo-centrado img { height: 40px; }
            .auth-wrapper { flex-direction: column; margin: 0 20px 40px 20px; }
            .panel-izquierdo { display: none; }
            .panel-derecho { width: 100%; box-sizing: border-box; padding: 40px 30px; }
        }
    </style>
</head>
<body>

    <div class="top-bar">
        <a href="/Promotian1/" class="btn-regresar">
            <span style="font-size: 20px;">&larr;</span> Regresar
        </a>
        <a href="/Promotian1/" class="logo-centrado">
            <img src="assets/img/logo.png" alt="Logo Promotian">
        </a>
    </div>

    <div class="auth-wrapper">

        <div class="panel-izquierdo"></div>


        <div class="panel-derecho">
            <h1>¡Bienvenido de nuevo!</h1>
            <p>Inicia sesión para descubrir lo mejor de tu comunidad.</p>

            <?php
            if (isset($_GET['exito']) && $_GET['exito'] == 'registro_completado') {
                echo '<div class="alerta alerta-exito">¡Registro exitoso! Ahora puedes iniciar sesión.</div>';
            }
            if (isset($_GET['error'])) {
                if ($_GET['error'] == 'incorrectos') echo '<div class="alerta alerta-error">El correo o la contraseña son incorrectos.</div>';
                elseif ($_GET['error'] == 'vacios') echo '<div class="alerta alerta-error">Por favor, completa todos los campos.</div>';
            }
            ?>

            <form action="procesos/procesar_login.php" method="POST">
                <div class="form-group">
                    <label>Correo electrónico</label>
                    <input type="email" name="correo" placeholder="tucorreo@ejemplo.com" required>
                </div>

                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="contrasena" placeholder="Tu contraseña" required>
                </div>

                <div class="olvide-contrasena">
                    <a href="recuperar_contrasena.php">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="btn-submit">Iniciar Sesión</button>
            </form>

            <div class="link-registro">
                ¿No tienes una cuenta? <a href="registro">Regístrate aquí</a>
            </div>
        </div>
    </div>

</body>
</html>
