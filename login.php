<?php

session_start();
require_once "config/conexion.php";

$mensaje = "";

if (isset($_GET["registro"]) && $_GET["registro"] === "exitoso") {
    $mensaje = "Cuenta creada correctamente. Ahora inicia sesión.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $identificador = trim($_POST["identificador"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($identificador === "" || $password === "") {

        $mensaje = "Completa todos los campos.";

    } else {

        $consulta = $conexion->prepare(
            "SELECT id_usuario, nombre, apellido, usuario,
                    email, password, foto_perfil, estado
             FROM usuarios
             WHERE usuario = ? OR email = ?
             LIMIT 1"
        );

        $consulta->bind_param(
            "ss",
            $identificador,
            $identificador
        );

        $consulta->execute();

        $resultado = $consulta->get_result();

        if ($resultado->num_rows === 1) {

            $usuario = $resultado->fetch_assoc();

            if ($usuario["estado"] !== "activo") {

                $mensaje = "Esta cuenta no está disponible.";

            } elseif (
                password_verify(
                    $password,
                    $usuario["password"]
                )
            ) {

                session_regenerate_id(true);

                $_SESSION["id_usuario"] = $usuario["id_usuario"];
                $_SESSION["nombre"] = $usuario["nombre"];
                $_SESSION["usuario"] = $usuario["usuario"];
                $_SESSION["foto_perfil"] = $usuario["foto_perfil"];

                $insertar_sesion = $conexion->prepare(
                    "INSERT INTO sesiones (id_usuario)
                     VALUES (?)"
                );

                $insertar_sesion->bind_param(
                    "i",
                    $usuario["id_usuario"]
                );

                $insertar_sesion->execute();
                $insertar_sesion->close();

                header("Location: index.php");
                exit;

            } else {

                $mensaje = "Usuario/correo o contraseña incorrectos.";
            }

        } else {

            $mensaje = "Usuario/correo o contraseña incorrectos.";
        }

        $consulta->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Iniciar sesión | CorreoChat</title>

    <link rel="stylesheet" href="assets/css/auth.css">
</head>

<body>

<div class="auth-container">

    <div class="auth-card">

        <!-- LOGO -->
        <div class="logo">
            <div class="logo-icon">C</div>
            <h1>CorreoChat</h1>
        </div>

        <!-- TITULO -->
        <div class="form-header">
            <h2>Iniciar sesión</h2>

            <p>
                Inicia sesión para continuar en CorreoChat
            </p>
        </div>

        <!-- FORMULARIO -->
        <form method="POST" class="auth-form">

            <div class="form-group">

                <label for="identificador">
                    Usuario o correo electrónico
                </label>

                <input
                    type="text"
                    id="identificador"
                    name="identificador"
                    placeholder="Ingresa tu usuario o correo"
                    required
                >

            </div>

            <div class="form-group">

                <label for="password">
                    Contraseña
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Ingresa tu contraseña"
                    required
                >

            </div>

            <div class="forgot-password">
                <a href="recuperar_password.php">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>

            <button type="submit">
                Iniciar sesión
            </button>

        </form>

        <!-- REGISTRO -->

        <div class="register-link">

            <span>
                ¿No tienes una cuenta?
            </span>

            <a href="registro.php">
                Crear cuenta
            </a>

        </div>

    </div>

</div>

</body>

</html>