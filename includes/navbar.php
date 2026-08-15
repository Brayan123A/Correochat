<?php

/* =========================================================
   CONEXIÓN
========================================================= */

require_once __DIR__ . "/../config/conexion.php";


/* =========================================================
   USUARIO ACTUAL
========================================================= */

$id_usuario = $_SESSION["id_usuario"] ?? 0;

$nombre_navbar = $_SESSION["nombre"] ?? "Usuario";

$foto_navbar = "";


if ($id_usuario > 0) {

    $consulta_navbar = $conexion->prepare("
        SELECT
            nombre,
            foto_perfil
        FROM usuarios
        WHERE id_usuario = ?
        LIMIT 1
    ");

    $consulta_navbar->bind_param(
        "i",
        $id_usuario
    );

    $consulta_navbar->execute();

    $resultado_navbar =
        $consulta_navbar->get_result();

    if ($resultado_navbar->num_rows === 1) {

        $datos_navbar =
            $resultado_navbar->fetch_assoc();

        $nombre_navbar =
            $datos_navbar["nombre"];

        $foto_navbar =
            $datos_navbar["foto_perfil"] ?? "";
    }

    $consulta_navbar->close();
}


/* =========================================================
   RUTAS
========================================================= */

/*
   Si navbar.php está incluido desde:

   /Correochat/index.php
   usamos:

   ./


   Si está incluido desde:

   /Correochat/usuario/perfil.php
   usamos:

   ../
*/

$pagina_actual =
    $_SERVER["PHP_SELF"];

if (strpos($pagina_actual, "/usuario/") !== false) {

    $base = "../";

} else {

    $base = "";
}


/* =========================================================
   FOTO DE PERFIL
========================================================= */

if (!empty($foto_navbar)) {

    $ruta_foto_navbar =
        $base .
        "uploads/perfiles/" .
        htmlspecialchars($foto_navbar);

} else {

    $ruta_foto_navbar =
        $base .
        "assets/img/default.png";
}

?>


<nav class="navbar">


    <!-- =====================================================
         LOGO
    ====================================================== -->

    <div class="navbar-left">

        <a
            href="<?= $base ?>index.php"
            class="navbar-logo"
        >

            <span class="logo-mini">
                C
            </span>

            <span class="logo-text">
                CorreoChat
            </span>

        </a>

    </div>



    <!-- =====================================================
         MENÚ CENTRAL
    ====================================================== -->

    <div class="navbar-center">


        <a
            href="<?= $base ?>index.php"
            class="nav-item active"
        >

            🏠

            <span>
                Inicio
            </span>

        </a>


        <a
            href="#"
            class="nav-item"
        >

            👥

            <span>
                Amigos
            </span>

        </a>


        <a
            href="#"
            class="nav-item"
        >

            💬

            <span>
                Chat
            </span>

        </a>


        <a
            href="#"
            class="nav-item"
        >

            🔔

            <span>
                Notificaciones
            </span>

        </a>


    </div>



    <!-- =====================================================
         PARTE DERECHA
    ====================================================== -->

    <div class="navbar-right">


        <!-- PERFIL -->

        <a
            href="<?= $base ?>usuario/perfil.php"
            class="profile-button"
        >

            <img
                src="<?= $ruta_foto_navbar ?>"
                alt="Foto de perfil"
            >


            <span>

                <?= htmlspecialchars($nombre_navbar) ?>

            </span>

        </a>



        <!-- CERRAR SESIÓN -->

        <a
            href="<?= $base ?>cerrar_sesion.php"
            class="logout-button"
        >

            Salir

        </a>


    </div>


</nav>