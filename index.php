<?php

require_once "includes/verificar_sesion.php";
require_once "config/conexion.php";


/* =========================================================
   USUARIO ACTUAL
========================================================= */

$id_usuario = $_SESSION["id_usuario"];


/* =========================================================
   OBTENER DATOS
========================================================= */

$consulta = $conexion->prepare("
    SELECT
        nombre,
        foto_perfil
    FROM usuarios
    WHERE id_usuario = ?
    LIMIT 1
");

$consulta->bind_param(
    "i",
    $id_usuario
);

$consulta->execute();

$resultado = $consulta->get_result();

$usuario = $resultado->fetch_assoc();

$consulta->close();


/* =========================================================
   DATOS
========================================================= */

$nombre =
    $usuario["nombre"]
    ?? $_SESSION["nombre"]
    ?? "Usuario";


$foto_perfil =
    $usuario["foto_perfil"]
    ?? "";


/* =========================================================
   RUTA FOTO
========================================================= */

if (!empty($foto_perfil)) {

    $ruta_foto =
        "uploads/perfiles/" .
        htmlspecialchars($foto_perfil);

} else {

    $ruta_foto =
        "assets/img/default.png";
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

    <title>
        Inicio | CorreoChat
    </title>


    <!-- CSS PRINCIPAL -->

    <link
        rel="stylesheet"
        href="assets/css/style.css"
    >


    <!-- NAVBAR -->

    <link
        rel="stylesheet"
        href="assets/css/navbar.css"
    >


    <!-- RESPONSIVE -->

    <link
        rel="stylesheet"
        href="assets/css/responsive.css"
    >

</head>


<body>


<!-- =====================================================
     NAVBAR
====================================================== -->

<?php include "includes/navbar.php"; ?>



<!-- =====================================================
     LAYOUT PRINCIPAL
====================================================== -->

<main class="social-layout">


    <!-- =================================================
         SIDEBAR IZQUIERDO
    ================================================== -->

    <aside class="sidebar left-sidebar">


        <!-- PERFIL DEL USUARIO -->

        <a
            href="usuario/perfil.php"
            class="sidebar-user"
        >

            <img
                src="<?= $ruta_foto ?>"
                alt="Foto de perfil"
            >


            <div>

                <strong>

                    <?= htmlspecialchars($nombre) ?>

                </strong>


                <span>

                    Ver mi perfil

                </span>

            </div>

        </a>



        <!-- MENÚ -->

        <nav class="sidebar-menu">


            <a
                href="index.php"
                class="active"
            >

                <span>🏠</span>

                Inicio

            </a>


            <a href="#">

                <span>👥</span>

                Amigos

            </a>


            <a href="#">

                <span>💬</span>

                Mensajes

            </a>


            <a href="#">

                <span>🔔</span>

                Notificaciones

            </a>


            <a href="#">

                <span>⚙️</span>

                Configuración

            </a>


        </nav>


    </aside>



    <!-- =================================================
         CONTENIDO CENTRAL
    ================================================== -->

    <section class="main-content">


        <!-- =============================================
             BIENVENIDA
        ============================================== -->

        <div class="welcome-card">


            <h1>

                ¡Hola,
                <?= htmlspecialchars($nombre) ?>! 👋

            </h1>


            <p>

                Bienvenido a CorreoChat.

            </p>


        </div>



        <!-- =============================================
             CREAR PUBLICACIÓN
        ============================================== -->

        <div class="crear-post-card">


            <!-- PARTE SUPERIOR -->

            <div class="crear-post-top">


                <img
                    src="<?= $ruta_foto ?>"
                    alt="Foto de perfil"
                >


                <a
                    href="publicaciones/crear.php"
                    class="crear-post-input"
                >

                    ¿Qué estás pensando,
                    <?= htmlspecialchars($nombre) ?>?

                </a>


            </div>



            <!-- OPCIONES -->

            <div class="crear-post-options">


                <a
                    href="publicaciones/crear.php"
                >

                    <span>📷</span>

                    Foto

                </a>


                <a
                    href="publicaciones/crear.php"
                >

                    <span>🎥</span>

                    Video

                </a>


                <a
                    href="publicaciones/crear.php"
                >

                    <span>✍️</span>

                    Publicación

                </a>


            </div>


        </div>



        <!-- =============================================
             MENSAJE DE DESARROLLO
        ============================================== -->

        <div class="coming-card">


            <div class="coming-icon">

                🚀

            </div>


            <h2>

                Tu red social está tomando forma

            </h2>


            <p>

                Aquí aparecerán tus publicaciones,
                historias y novedades de tus amigos.

            </p>


        </div>



    </section>



    <!-- =================================================
         SIDEBAR DERECHO
    ================================================== -->

    <aside class="sidebar right-sidebar">


        <!-- INFORMACIÓN -->

        <div class="side-card">


            <h3>

                CorreoChat

            </h3>


            <p>

                Conecta, comparte y conversa.

            </p>


        </div>



        <!-- PRÓXIMAMENTE -->

        <div class="side-card">


            <h3>

                Próximamente

            </h3>


            <ul>


                <li>

                    📸 Publicaciones

                </li>


                <li>

                    ❤️ Reacciones

                </li>


                <li>

                    👥 Amigos

                </li>


                <li>

                    🎵 Historias con música

                </li>


                <li>

                    💬 Chat

                </li>


            </ul>


        </div>


    </aside>


</main>



</body>

</html>