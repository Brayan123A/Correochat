<?php
session_start();

if (isset($_SESSION["id_usuario"])) {
    header("Location: ../inicio/index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Crear cuenta | CorreoChat</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            min-height: 100vh;
            background: #f2f4f7;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .registro-container {
            width: 100%;
            max-width: 430px;
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo h1 {
            color: #1877f2;
            font-size: 38px;
            font-weight: 700;
        }

        .logo p {
            color: #606770;
            margin-top: 5px;
        }

        .registro-card {
            background: white;
            border-radius: 12px;
            padding: 28px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .registro-card h2 {
            text-align: center;
            color: #1c1e21;
            margin-bottom: 7px;
        }

        .descripcion {
            text-align: center;
            color: #65676b;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .fila {
            display: flex;
            gap: 10px;
        }

        .campo {
            margin-bottom: 15px;
            flex: 1;
        }

        .campo label {
            display: block;
            margin-bottom: 6px;
            color: #333;
            font-size: 14px;
            font-weight: 600;
        }

        .campo input {
            width: 100%;
            padding: 13px;
            border: 1px solid #ccd0d5;
            border-radius: 8px;
            font-size: 15px;
            outline: none;
        }

        .campo input:focus {
            border-color: #1877f2;
            box-shadow: 0 0 0 2px rgba(24, 119, 242, 0.12);
        }

        .boton {
            width: 100%;
            border: none;
            border-radius: 8px;
            padding: 13px;
            background: #1877f2;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 5px;
        }

        .boton:hover {
            background: #166fe5;
        }

        .login {
            text-align: center;
            margin-top: 22px;
            font-size: 14px;
        }

        .login a {
            color: #1877f2;
            text-decoration: none;
            font-weight: bold;
        }

        .terminos {
            margin-top: 18px;
            font-size: 12px;
            line-height: 1.5;
            color: #777;
            text-align: center;
        }

        @media (max-width: 480px) {
            .registro-card {
                padding: 22px;
            }

            .fila {
                flex-direction: column;
                gap: 0;
            }

            .logo h1 {
                font-size: 34px;
            }
        }
    </style>
</head>

<body>

<div class="registro-container">

    <div class="logo">
        <h1>CorreoChat</h1>
        <p>Conecta. Comparte. Conversa.</p>
    </div>

    <div class="registro-card">

        <h2>Crear una cuenta</h2>

        <p class="descripcion">
            Es rápido y fácil.
        </p>

        <form action="validar_registro.php" method="POST">

            <div class="fila">

                <div class="campo">
                    <label for="nombre">Nombre</label>

                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        placeholder="Tu nombre"
                        maxlength="100"
                        required
                    >
                </div>

                <div class="campo">
                    <label for="apellido">Apellido</label>

                    <input
                        type="text"
                        id="apellido"
                        name="apellido"
                        placeholder="Tu apellido"
                        maxlength="100"
                    >
                </div>

            </div>

            <div class="campo">

                <label for="usuario">Nombre de usuario</label>

                <input
                    type="text"
                    id="usuario"
                    name="usuario"
                    placeholder="@usuario"
                    maxlength="50"
                    pattern="[A-Za-z0-9_.]+"
                    required
                >

            </div>

            <div class="campo">

                <label for="correo">Correo electrónico</label>

                <input
                    type="email"
                    id="correo"
                    name="correo"
                    placeholder="correo@ejemplo.com"
                    maxlength="150"
                    required
                >

            </div>

            <div class="campo">

                <label for="password">Contraseña</label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Mínimo 8 caracteres"
                    minlength="8"
                    required
                >

            </div>

            <div class="campo">

                <label for="confirmar_password">
                    Confirmar contraseña
                </label>

                <input
                    type="password"
                    id="confirmar_password"
                    name="confirmar_password"
                    placeholder="Repite tu contraseña"
                    minlength="8"
                    required
                >

            </div>

            <button type="submit" class="boton">
                Crear cuenta
            </button>

        </form>

        <div class="terminos">
            Al crear una cuenta aceptas las condiciones de uso
            y las políticas de privacidad de CorreoChat.
        </div>

        <div class="login">
            ¿Ya tienes una cuenta?
            <a href="login.php">Iniciar sesión</a>
        </div>

    </div>

</div>

</body>
</html>