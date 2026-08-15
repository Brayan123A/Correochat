<?php

require_once "../includes/verificar_sesion.php";
require_once "../config/conexion.php";

$id_usuario = $_SESSION["id_usuario"];

$consulta = $conexion->prepare("
    SELECT
        id_usuario,
        nombre,
        apellido,
        usuario,
        email,
        foto_perfil,
        foto_portada,
        fecha_registro
    FROM usuarios
    WHERE id_usuario = ?
    LIMIT 1
");

$consulta->bind_param("i", $id_usuario);
$consulta->execute();

$resultado = $consulta->get_result();

if ($resultado->num_rows !== 1) {
    header("Location: ../cerrar_sesion.php");
    exit;
}

$perfil = $resultado->fetch_assoc();

$nombre_completo = trim(
    $perfil["nombre"] . " " . ($perfil["apellido"] ?? "")
);

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= htmlspecialchars($nombre_completo) ?> | CorreoChat
    </title>

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

    <link
        rel="stylesheet"
        href="../assets/css/responsive.css"
    >

</head>

<body>

<?php include "../includes/navbar.php"; ?>


<main class="profile-page">

    <!-- ==============================================
         CABECERA
    =============================================== -->

    <section class="profile-header">

        <?php if (!empty($perfil["foto_portada"])): ?>

            <div
                class="cover-photo"
                style="
                    background-image:
                    url('../uploads/portadas/<?= htmlspecialchars($perfil["foto_portada"]) ?>');
                "
            ></div>

        <?php else: ?>

            <div class="cover-photo"></div>

        <?php endif; ?>


        <!-- FOTO DE PERFIL -->

        <div class="profile-photo-container">

            <?php if (!empty($perfil["foto_perfil"])): ?>

                <img
                    src="../uploads/perfiles/<?= htmlspecialchars($perfil["foto_perfil"]) ?>"
                    alt="Foto de perfil"
                    class="profile-photo"
                >

            <?php else: ?>

                <img
                    src="../assets/img/default.png"
                    alt="Foto de perfil"
                    class="profile-photo"
                >

            <?php endif; ?>

        </div>


        <!-- INFORMACIÓN -->

        <div class="profile-info">

            <h1>
                <?= htmlspecialchars($nombre_completo) ?>
            </h1>

            <p class="username">
                @<?= htmlspecialchars($perfil["usuario"]) ?>
            </p>

            <p class="member-since">

                Miembro de CorreoChat desde

                <?= date(
                    "d/m/Y",
                    strtotime($perfil["fecha_registro"])
                ) ?>

            </p>


            <!-- BOTONES -->

            <div class="profile-actions">

                <a
                    href="editar_perfil.php"
                    class="profile-btn primary"
                >
                    ✏️ Editar perfil
                </a>

                <a
                    href="subir_fotos.php"
                    class="profile-btn secondary"
                >
                    📷 Cambiar fotos
                </a>

            </div>

        </div>

    </section>


    <!-- ==============================================
         ESTADÍSTICAS
    =============================================== -->

    <section class="profile-stats">

        <div class="stat">

            <strong>0</strong>

            <span>
                Publicaciones
            </span>

        </div>


        <div class="stat">

            <strong>0</strong>

            <span>
                Amigos
            </span>

        </div>


        <div class="stat">

            <strong>0</strong>

            <span>
                Reacciones
            </span>

        </div>

    </section>


    <!-- ==============================================
         PUBLICACIONES
    =============================================== -->

    <section class="profile-content">

        <div class="profile-card">

            <div class="section-title">

                <h2>
                    Publicaciones
                </h2>

            </div>


            <div class="empty-profile">

                <div class="empty-icon">
                    📝
                </div>

                <h3>
                    Todavía no tienes publicaciones
                </h3>

                <p>
                    Cuando publiques algo,
                    aparecerá aquí.
                </p>

            </div>

        </div>

    </section>

</main>

</body>

</html>