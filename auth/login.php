<?php

session_start();

if (isset($_SESSION["id_usuario"])) {
    header("Location: ../inicio/index.php");
    exit;
}

$error = $_SESSION["error_login"] ?? "";
unset($_SESSION["error_login"]);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Iniciar sesión | CorreoChat</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            background: #f2f4f7;
            font-family: Arial, Helvetica, sans-serif;

            display: flex;
            justify-content: center;
            align-items: center;

            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
        }

        .logo {
            text-align: center;
            margin-bottom: 22px;
        }

        .logo h1 {
            color: #1877f2;
            font-size: 40px;
            margin-bottom: 6px;
        }

        .logo p {
            color: #606770;
            font-size: 15px;
        }

        .login-card {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;

            box-shadow:
                0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .login-card h2 {
            text-align: center;
            color: #1c1e21;
            margin-bottom: 24px;
        }

        .campo {
            margin-bottom: 16px;
        }

        .campo label {
            display: block;
            margin-bottom: 7px;

            font-size: 14px;
            font-weight: bold;
            color: #333;
        }

        .campo input {
            width: 100%;

            padding: 14px;

            border: 1px solid #ccd0d5;
            border-radius: 8px;

            font-size: 15px;
            outline: none;
        }

        .campo input:focus {
            border-color: #1877f2;

            box-shadow:
                0 0 0 2px rgba(24, 119, 242, 0.12);
        }

        .boton {
            width: 100%;

            padding: 14px;

            border: none;
            border-radius: 8px;

            background: #1877f2;
            color: white;

            font-size: 16px;
            font-weight: bold;

            cursor: pointer;
        }

        .boton:hover {
            background: #166fe5;
        }

        .recuperar {
            text-align: center;
            margin-top: 18px;
        }

        .recuperar a {
            color: #1877f2;
            text-decoration: none;
            font-size: 14px;
        }

        .separador {
            border: none;
            border-top: 1px solid #dddfe2;
            margin: 25px 0;
        }

        .crear-cuenta {
            display: block;

            width: fit-content;

            margin: auto;

            padding: 12px 20px;

            background: #42b72a;
            color: white;

            text-decoration: none;

            border-radius: 8px;

            font-weight: bold;
        }

        .crear-cuenta:hover {
            background: #36a420;
        }

        .error {
            background: #ffebe9;
            color: #b42318;

            border: 1px solid #f5c2c0;

            padding: 12px;

            border-radius: 8px;

            margin-bottom: 18px;

            font-size: 14px;

            text-align: center;
        }

        @media (max-width: 480px) {

            .login-card {
                padding: 22px;
            }

            .logo h1 {
                font-size: 34px;
            }

        }

    </style>

</head>

<body>

<div class="login-container">

    <div class="logo">

        <h1>CorreoChat</h1>

        <p>
            Conecta. Comparte. Conversa.
        </p>

    </div>


    <div class="login-card">

        <h2>Iniciar sesión</h2>


        <?php if ($error !== ""): ?>

            <div class="error">

                <?php
                echo htmlspecialchars($error);
                ?>

            </div>

        <?php endif; ?>


        <form
            action="validar_login.php"
            method="POST"
        >

            <div class="campo">

                <label for="correo">
                    Correo electrónico o usuario
                </label>

                <input
                    type="text"
                    id="correo"
                    name="correo"
                    placeholder="Correo o usuario"
                    required
                    autocomplete="username"
                >

            </div>


            <div class="campo">

                <label for="password">
                    Contraseña
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Tu contraseña"
                    required
                    autocomplete="current-password"
                >

            </div>


            <button
                type="submit"
                class="boton"
            >
                Iniciar sesión
            </button>

        </form>


        <div class="recuperar">

            <a href="recuperar.php">
                ¿Olvidaste tu contraseña?
            </a>

        </div>


        <hr class="separador">


        <a
            href="registro.php"
            class="crear-cuenta"
        >
            Crear una cuenta
        </a>

    </div>

</div>

</body>

</html>