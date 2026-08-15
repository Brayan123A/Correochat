<?php

session_start();

require_once "../config/conexion.php";


/*
=========================================================
VERIFICAR SESIÓN
=========================================================
*/

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../auth/login.php");
    exit;
}


$id_usuario = $_SESSION["id_usuario"];


/*
=========================================================
OBTENER USUARIO
=========================================================
*/

$consulta = $conexion->prepare("
    SELECT
        id_usuario,
        nombre,
        apellido,
        usuario,
        correo,
        foto_perfil,
        portada,
        biografia,
        ciudad,
        fecha_registro
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


if ($resultado->num_rows === 0) {
    session_destroy();

    header("Location: ../auth/login.php");
    exit;
}


$usuario = $resultado->fetch_assoc();


/*
=========================================================
DATOS
=========================================================
*/

$nombre_completo = trim(
    $usuario["nombre"] . " " . ($usuario["apellido"] ?? "")
);

$foto_perfil = !empty($usuario["foto_perfil"])
    ? "../uploads/perfiles/" . $usuario["foto_perfil"]
    : "../assets/img/iconos/default-user.png";

$portada = !empty($usuario["portada"])
    ? "../uploads/portadas/" . $usuario["portada"]
    : "../assets/img/fondos/default-cover.jpg";


/*
=========================================================
CONTAR AMIGOS
=========================================================
*/

$consulta_amigos = $conexion->prepare("
    SELECT COUNT(*) AS total
    FROM amistades
    WHERE id_usuario1 = ?
       OR id_usuario2 = ?
");

$consulta_amigos->bind_param(
    "ii",
    $id_usuario,
    $id_usuario
);

$consulta_amigos->execute();

$total_amigos = $consulta_amigos
    ->get_result()
    ->fetch_assoc()["total"];


/*
=========================================================
CONTAR SEGUIDORES
=========================================================
*/

$consulta_seguidores = $conexion->prepare("
    SELECT COUNT(*) AS total
    FROM seguidores
    WHERE id_seguido = ?
");

$consulta_seguidores->bind_param(
    "i",
    $id_usuario
);

$consulta_seguidores->execute();

$total_seguidores = $consulta_seguidores
    ->get_result()
    ->fetch_assoc()["total"];


/*
=========================================================
CONTAR SIGUIENDO
=========================================================
*/

$consulta_siguiendo = $conexion->prepare("
    SELECT COUNT(*) AS total
    FROM seguidores
    WHERE id_usuario = ?
");

$consulta_siguiendo->bind_param(
    "i",
    $id_usuario
);

$consulta_siguiendo->execute();

$total_siguiendo = $consulta_siguiendo
    ->get_result()
    ->fetch_assoc()["total"];


/*
=========================================================
CONTAR PUBLICACIONES
=========================================================
*/

$consulta_publicaciones = $conexion->prepare("
    SELECT COUNT(*) AS total
    FROM publicaciones
    WHERE id_usuario = ?
      AND estado = 'activo'
");

$consulta_publicaciones->bind_param(
    "i",
    $id_usuario
);

$consulta_publicaciones->execute();

$total_publicaciones = $consulta_publicaciones
    ->get_result()
    ->fetch_assoc()["total"];

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
        <?php echo htmlspecialchars($nombre_completo); ?>
        | CorreoChat
    </title>


    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }


        body {
            font-family:
                Arial,
                Helvetica,
                sans-serif;

            background: #f0f2f5;
            color: #1c1e21;
        }


        /* ==============================================
           NAVBAR
        ============================================== */

        .navbar {

            height: 64px;

            background: white;

            display: flex;
            align-items: center;

            padding: 0 25px;

            border-bottom: 1px solid #ddd;

            position: sticky;
            top: 0;

            z-index: 100;
        }


        .logo {

            color: #1877f2;

            font-size: 27px;

            font-weight: bold;

            text-decoration: none;
        }


        .navbar-derecha {

            margin-left: auto;

            display: flex;

            gap: 10px;
        }


        .nav-btn {

            width: 40px;
            height: 40px;

            border-radius: 50%;

            background: #f0f2f5;

            display: flex;

            justify-content: center;
            align-items: center;

            text-decoration: none;

            color: #333;

            font-size: 19px;
        }


        /* ==============================================
           PERFIL
        ============================================== */

        .perfil-container {

            max-width: 1050px;

            margin: auto;
        }


        .portada {

            height: 340px;

            background:
                linear-gradient(
                    135deg,
                    #1877f2,
                    #6a5acd
                );

            border-radius: 0 0 12px 12px;

            overflow: hidden;

            position: relative;
        }


        .portada img {

            width: 100%;
            height: 100%;

            object-fit: cover;
        }


        .perfil-info {

            background: white;

            padding: 0 35px 25px;

            position: relative;

            border-radius: 0 0 12px 12px;

            box-shadow:
                0 2px 8px rgba(0,0,0,.08);
        }


        .foto-container {

            position: relative;

            margin-top: -75px;

            width: 150px;
            height: 150px;
        }


        .foto-perfil {

            width: 150px;
            height: 150px;

            border-radius: 50%;

            object-fit: cover;

            border: 5px solid white;

            background: #eee;
        }


        .datos-perfil {

            margin-top: 10px;
        }


        .datos-perfil h1 {

            font-size: 28px;

            margin-bottom: 4px;
        }


        .usuario {

            color: #65676b;

            margin-bottom: 12px;
        }


        .biografia {

            max-width: 650px;

            line-height: 1.5;

            margin-bottom: 10px;
        }


        .informacion {

            color: #65676b;

            margin-bottom: 20px;
        }


        .estadisticas {

            display: flex;

            gap: 35px;

            border-top: 1px solid #eee;

            padding-top: 18px;
        }


        .estadistica {

            text-align: center;
        }


        .estadistica strong {

            display: block;

            font-size: 20px;

            color: #1c1e21;
        }


        .estadistica span {

            font-size: 13px;

            color: #65676b;
        }


        .acciones {

            margin-left: auto;

            margin-top: -45px;

            display: flex;

            justify-content: flex-end;

            gap: 10px;
        }


        .btn {

            border: none;

            padding: 11px 18px;

            border-radius: 7px;

            font-weight: bold;

            cursor: pointer;

            text-decoration: none;
        }


        .btn-editar {

            background: #1877f2;

            color: white;
        }


        .btn-editar:hover {

            background: #166fe5;
        }


        .btn-salir {

            background: #e4e6eb;

            color: #1c1e21;
        }


        /* ==============================================
           CONTENIDO
        ============================================== */

        .contenido {

            margin-top: 20px;

            background: white;

            padding: 30px;

            border-radius: 12px;

            box-shadow:
                0 2px 8px rgba(0,0,0,.06);
        }


        .contenido h2 {

            margin-bottom: 10px;
        }


        .contenido p {

            color: #65676b;
        }


        /* ==============================================
           MÓVIL
        ============================================== */

        @media (max-width: 700px) {

            .navbar {

                padding: 0 15px;
            }


            .logo {

                font-size: 23px;
            }


            .perfil-container {

                width: 100%;
            }


            .portada {

                height: 220px;

                border-radius: 0;
            }


            .perfil-info {

                padding: 0 18px 20px;

                border-radius: 0;
            }


            .foto-container {

                width: 110px;
                height: 110px;

                margin-top: -55px;
            }


            .foto-perfil {

                width: 110px;
                height: 110px;
            }


            .datos-perfil h1 {

                font-size: 23px;
            }


            .acciones {

                margin: 15px 0 0;

                justify-content: flex-start;
            }


            .estadisticas {

                justify-content: space-between;

                gap: 10px;
            }


            .estadistica strong {

                font-size: 17px;
            }


            .estadistica span {

                font-size: 12px;
            }


            .contenido {

                border-radius: 0;

                margin-top: 10px;
            }

        }

    </style>

</head>


<body>


<!-- ==============================================
     NAVBAR
============================================== -->

<header class="navbar">

    <a
        href="../inicio/index.php"
        class="logo"
    >
        CorreoChat
    </a>


    <div class="navbar-derecha">

        <a
            href="../notificaciones/index.php"
            class="nav-btn"
        >
            🔔
        </a>


        <a
            href="../chat/index.php"
            class="nav-btn"
        >
            💬
        </a>


        <a
            href="../configuracion/index.php"
            class="nav-btn"
        >
            ☰
        </a>

    </div>

</header>


<!-- ==============================================
     PERFIL
============================================== -->

<main class="perfil-container">


    <!-- PORTADA -->

    <div class="portada">

        <img
            src="<?php echo htmlspecialchars($portada); ?>"
            alt="Portada"
            onerror="this.style.display='none';"
        >

    </div>


    <!-- INFORMACIÓN -->

    <section class="perfil-info">


        <!-- FOTO -->

        <div class="foto-container">

            <img
                src="<?php echo htmlspecialchars($foto_perfil); ?>"
                alt="Foto de perfil"
                class="foto-perfil"
                onerror="this.src='../assets/img/iconos/default-user.png';"
            >

        </div>


        <!-- DATOS -->

        <div class="datos-perfil">

            <h1>

                <?php
                echo htmlspecialchars(
                    $nombre_completo
                );
                ?>

            </h1>


            <div class="usuario">

                @<?php
                echo htmlspecialchars(
                    $usuario["usuario"]
                );
                ?>

            </div>


            <?php if (!empty($usuario["biografia"])): ?>

                <div class="biografia">

                    <?php
                    echo nl2br(
                        htmlspecialchars(
                            $usuario["biografia"]
                        )
                    );
                    ?>

                </div>

            <?php else: ?>

                <div class="biografia">

                    Este usuario todavía no ha agregado
                    una biografía.

                </div>

            <?php endif; ?>


            <?php if (!empty($usuario["ciudad"])): ?>

                <div class="informacion">

                    📍
                    <?php
                    echo htmlspecialchars(
                        $usuario["ciudad"]
                    );
                    ?>

                </div>

            <?php endif; ?>


            <div class="informacion">

                📅 Miembro desde
                <?php
                echo date(
                    "d/m/Y",
                    strtotime(
                        $usuario["fecha_registro"]
                    )
                );
                ?>

            </div>

        </div>


        <!-- ACCIONES -->

        <div class="acciones">

            <a
                href="editar.php"
                class="btn btn-editar"
            >
                Editar perfil
            </a>


            <a
                href="../auth/logout.php"
                class="btn btn-salir"
            >
                Cerrar sesión
            </a>

        </div>


        <!-- ESTADÍSTICAS -->

        <div class="estadisticas">


            <div class="estadistica">

                <strong>
                    <?php
                    echo $total_publicaciones;
                    ?>
                </strong>

                <span>
                    Publicaciones
                </span>

            </div>


            <div class="estadistica">

                <strong>
                    <?php
                    echo $total_amigos;
                    ?>
                </strong>

                <span>
                    Amigos
                </span>

            </div>


            <div class="estadistica">

                <strong>
                    <?php
                    echo $total_seguidores;
                    ?>
                </strong>

                <span>
                    Seguidores
                </span>

            </div>


            <div class="estadistica">

                <strong>
                    <?php
                    echo $total_siguiendo;
                    ?>
                </strong>

                <span>
                    Siguiendo
                </span>

            </div>

        </div>

    </section>


    <!-- CONTENIDO -->

    <section class="contenido">

        <h2>
            Publicaciones
        </h2>

        <p>
            Aquí aparecerán las publicaciones de
            <?php
            echo htmlspecialchars($nombre_completo);
            ?>.
        </p>

    </section>


</main>


</body>

</html>