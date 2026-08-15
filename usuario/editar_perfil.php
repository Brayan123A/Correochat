<?php

require_once "../includes/verificar_sesion.php";
require_once "../config/conexion.php";

$id_usuario = $_SESSION["id_usuario"];

$mensaje = "";

$consulta = $conexion->prepare("
    SELECT nombre, apellido, usuario, email
    FROM usuarios
    WHERE id_usuario = ?
");

$consulta->bind_param("i", $id_usuario);
$consulta->execute();

$resultado = $consulta->get_result();
$usuario = $resultado->fetch_assoc();


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre"] ?? "");
    $apellido = trim($_POST["apellido"] ?? "");
    $nuevo_usuario = trim($_POST["usuario"] ?? "");

    if ($nombre === "" || $nuevo_usuario === "") {

        $mensaje = "El nombre y usuario son obligatorios.";

    } else {

        $actualizar = $conexion->prepare("
            UPDATE usuarios
            SET nombre = ?,
                apellido = ?,
                usuario = ?
            WHERE id_usuario = ?
        ");

        $actualizar->bind_param(
            "sssi",
            $nombre,
            $apellido,
            $nuevo_usuario,
            $id_usuario
        );

        if ($actualizar->execute()) {

            $_SESSION["nombre"] = $nombre;
            $_SESSION["usuario"] = $nuevo_usuario;

            header("Location: perfil.php");
            exit;

        } else {

            $mensaje = "No fue posible actualizar el perfil.";
        }

        $actualizar->close();
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

    <title>Editar perfil | CorreoChat</title>

    <link
        rel="stylesheet"
        href="../assets/css/style.css"
    >

    <link
        rel="stylesheet"
        href="../assets/css/navbar.css"
    >

    <link
        rel="stylesheet"
        href="../assets/css/perfil.css"
    >

</head>

<body>

<?php include "../includes/navbar.php"; ?>

<main class="profile-page">

    <div class="profile-card">

        <div class="section-title">

            <h2>
                Editar perfil
            </h2>

        </div>

        <?php if ($mensaje !== ""): ?>

            <div class="mensaje error">
                <?= htmlspecialchars($mensaje) ?>
            </div>

        <?php endif; ?>

        <form method="POST" class="edit-profile-form">

            <div class="form-group">

                <label>
                    Nombre
                </label>

                <input
                    type="text"
                    name="nombre"
                    value="<?= htmlspecialchars($usuario["nombre"]) ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label>
                    Apellido
                </label>

                <input
                    type="text"
                    name="apellido"
                    value="<?= htmlspecialchars($usuario["apellido"] ?? "") ?>"
                >

            </div>

            <div class="form-group">

                <label>
                    Nombre de usuario
                </label>

                <input
                    type="text"
                    name="usuario"
                    value="<?= htmlspecialchars($usuario["usuario"]) ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label>
                    Correo electrónico
                </label>

                <input
                    type="email"
                    value="<?= htmlspecialchars($usuario["email"]) ?>"
                    disabled
                >

            </div>

            <div class="profile-actions">

                <button
                    type="submit"
                    class="profile-btn primary"
                >
                    Guardar cambios
                </button>

                <a
                    href="perfil.php"
                    class="profile-btn secondary"
                >
                    Cancelar
                </a>

            </div>

        </form>

    </div>

</main>

</body>

</html>