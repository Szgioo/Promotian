<!DOCTYPE html>
<html lang="es">
<head>
    <base href="/Promotian1/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta - Promotian</title>
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

        .btn-regresar:hover {
            color: #d32f2f;
        }
 
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
            height: 65px; 
            width: auto;
            object-fit: contain;
            display: block;
        }

        .registro-wrapper {
            display: flex;
            max-width: 1000px;
            margin: 20px auto 40px auto;
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
            overflow: hidden;
            min-height: 600px;
        }
 
        .panel-izquierdo {
            width: 40%;
            background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.6)), url('assets/img/fondo-registro.jpg');
            background-size: cover;
            background-position: center;
            padding: 50px 40px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .panel-izquierdo h2 {
            font-size: 32px;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .panel-izquierdo p {
            font-size: 16px;
            margin-bottom: 40px;
            opacity: 0.9;
        }

        .beneficios {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .beneficios li {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 15px;
            font-weight: 500;
        }

        .beneficios li::before {
            content: '✓';
            background-color: rgba(255,255,255,0.2);
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 12px;
        }

        .panel-derecho {
            width: 60%;
            padding: 50px 60px;
        }

        .panel-derecho h1 {
            font-size: 26px;
            color: #20314b;
            margin: 0 0 5px 0;
        }

        .panel-derecho > p {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-col {
            flex: 1;
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

        .btn-submit:hover {
            background-color: #b72525;
        }

        .alerta {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alerta-error { background-color: #fee2e2; color: #991b1b; border: 1px solid #f87171; }

        @media (max-width: 768px) {
            .top-bar { padding: 20px; }
            .logo-centrado img { height: 40px; } 
            .registro-wrapper { flex-direction: column; margin: 0 20px 40px 20px; }
            .panel-izquierdo, .panel-derecho { width: 100%; box-sizing: border-box; }
            .panel-derecho { padding: 30px; }
            .form-row { flex-direction: column; gap: 0; }
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

    <div class="registro-wrapper">
 
        <div class="panel-izquierdo">
            <h2>Únete a<br>Promotian hoy</h2>
            <p>Regístrate gratis y empieza a conectar con tu comunidad local.</p>
            
            <ul class="beneficios">
                <li>Perfil de negocio gratis</li>
                <li>Alcance en tu comunidad</li>
                <li>Gestión fácil de publicaciones</li>
                <li>Reseñas y valoraciones</li>
            </ul>
        </div>

        <div class="panel-derecho">
            <h1>Crear cuenta</h1>
            <p>Completa el formulario para empezar</p>

            <?php
            if (isset($_GET['error'])) {
                if ($_GET['error'] == 'correo_existe') echo '<div class="alerta alerta-error">Este correo ya está registrado. Intenta iniciar sesión.</div>';
                elseif ($_GET['error'] == 'pass_no_coincide') echo '<div class="alerta alerta-error">Las contraseñas no coinciden.</div>';
                elseif ($_GET['error'] == 'bd') echo '<div class="alerta alerta-error">Hubo un problema de conexión. Inténtalo más tarde.</div>';
            }
            ?>

            <form action="procesos/procesar_registro.php" method="POST">
                
                <div class="form-row">
                    <div class="form-col">
                        <label>Nombre</label>
                        <input type="text" name="nombre" placeholder="Tu nombre" required>
                    </div>
                    <div class="form-col">
                        <label>Apellido</label>
                        <input type="text" name="apellido" placeholder="Tu apellido" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Correo electrónico</label>
                    <input type="email" name="correo" placeholder="tucorreo@ejemplo.com" required>
                </div>

                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="tel" name="telefono" placeholder="+52 228 000 0000" required>
                </div>

                <div class="form-group">
                    <label>Ciudad / Municipio</label>
                    <input type="text" name="ciudad" placeholder="Ej. Chilchotla, Puebla" required>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <label>Contraseña</label>
                        <input type="password" name="contrasena" placeholder="Mínimo 8 caracteres" required>
                    </div>
                    <div class="form-col">
                        <label>Confirmar contraseña</label>
                        <input type="password" name="confirmar_contrasena" placeholder="Repite tu contraseña" required>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Registrarme</button>
            </form>
        </div>
    </div>

</body>
</html>
