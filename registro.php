<?php

session_start();
require_once "config/conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

 $nombre = trim($_POST["nombre"] ?? "");
    $apellido = trim($_POST["apellido"] ?? "");
    $usuario = trim($_POST["usuario"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirmar = $_POST["confirmar"] ?? "";

    if (
        $nombre === "" ||
        $usuario === "" ||
        $email === "" ||
        $password === ""
    ) {
        $mensaje = "Completa todos los campos obligatorios.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "Introduce un correo electrónico válido.";

    } elseif ($password !== $confirmar) {
        $mensaje = "Las contraseñas no coinciden.";

    } elseif (strlen($password) < 8) {
        $mensaje = "La contraseña debe tener mínimo 8 caracteres.";

    } else {

        $consulta = $conexion->prepare(
            "SELECT id_usuario
             FROM usuarios
             WHERE usuario = ? OR email = ?
             LIMIT 1"
        );

        $consulta->bind_param("ss", $usuario, $email);
        $consulta->execute();

        $resultado = $consulta->get_result();

        if ($resultado->num_rows > 0) {

            $mensaje = "El usuario o correo ya está registrado.";

        } else {

            $password_hash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $insertar = $conexion->prepare(
                "INSERT INTO usuarios
                (nombre, apellido, usuario, email, password)
                VALUES (?, ?, ?, ?, ?)"
            );

            $insertar->bind_param(
                "sssss",
                $nombre,
                $apellido,
                $usuario,
                $email,
                $password_hash
            );

            if ($insertar->execute()) {

                header("Location: login.php?registro=exitoso");
                exit;

            } else {

                $mensaje = "No fue posible crear la cuenta.";
            }

            $insertar->close();
        }

        $consulta->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Crear cuenta | CorreoChat</title>

    <link rel="stylesheet" href="assets/css/auth.css">

</head>

<body>

<div class="auth-container">

    <div class="auth-card">

        <!-- LOGO -->

        <div class="logo">

            <div class="logo-icon">
                C
            </div>

            <h1>
                CorreoChat
            </h1>

        </div>

        <!-- ENCABEZADO -->

        <div class="form-header">

            <h2>
                Crear cuenta
            </h2>

            <p>
                Únete a CorreoChat y comienza a conectar.
            </p>

        </div>

        <!-- MENSAJE -->

        <?php if ($mensaje !== ""): ?>

            <div class="mensaje error">

                <?= htmlspecialchars($mensaje) ?>

            </div>

        <?php endif; ?>

        <!-- FORMULARIO -->

        <form
            method="POST"
            class="auth-form"
        >

            <div class="form-row">

                <div class="form-group">

                    <label for="nombre">
                        Nombre
                    </label>

                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        placeholder="Tu nombre"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="apellido">
                        Apellido
                    </label>

                    <input
                        type="text"
                        id="apellido"
                        name="apellido"
                        placeholder="Tu apellido"
                    >

                </div>

            </div>

            <div class="form-group">

                <label for="usuario">
                    Nombre de usuario
                </label>

                <input
                    type="text"
                    id="usuario"
                    name="usuario"
                    placeholder="Ej. usuario123"
                    required
                >

            </div>

            <div class="form-group">

                <label for="email">
                    Correo electrónico
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="ejemplo@correo.com"
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
                    placeholder="Mínimo 8 caracteres"
                    required
                >

            </div>

            <div class="form-group">

                <label for="confirmar">
                    Confirmar contraseña
                </label>

                <input
                    type="password"
                    id="confirmar"
                    name="confirmar"
                    placeholder="Repite tu contraseña"
                    required
                >

            </div>

            <button type="submit">
                Crear cuenta
            </button>

        </form>

        <!-- INICIAR SESIÓN -->

        <div class="register-link">

            <span>
                ¿Ya tienes una cuenta?
            </span>

            <a href="login.php">
                Iniciar sesión
            </a>

        </div>

    </div>

</div>

</body>

</html>