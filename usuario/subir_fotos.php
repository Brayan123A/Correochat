<?php

require_once "../includes/verificar_sesion.php";
require_once "../config/conexion.php";

$id_usuario = $_SESSION["id_usuario"];

$mensaje = "";
$tipo_mensaje = "";


/* =========================================================
   FUNCIÓN PARA SUBIR IMAGEN
========================================================= */

function subirImagen($archivo, $carpeta, $prefijo, $id_usuario)
{
    if (
        !isset($archivo) ||
        $archivo["error"] !== UPLOAD_ERR_OK
    ) {
        return [
            "success" => false,
            "message" => "No se seleccionó ninguna imagen."
        ];
    }


    /* =====================================================
       TAMAÑO MÁXIMO: 5 MB
    ===================================================== */

    $maximo = 5 * 1024 * 1024;

    if ($archivo["size"] > $maximo) {
        return [
            "success" => false,
            "message" => "La imagen no puede superar los 5 MB."
        ];
    }


    /* =====================================================
       COMPROBAR QUE SEA UNA IMAGEN
    ===================================================== */

    $informacion = getimagesize(
        $archivo["tmp_name"]
    );

    if ($informacion === false) {
        return [
            "success" => false,
            "message" => "El archivo seleccionado no es una imagen válida."
        ];
    }


    /* =====================================================
       TIPOS PERMITIDOS
    ===================================================== */

    $tipos_permitidos = [
        "image/jpeg" => "jpg",
        "image/png"  => "png",
        "image/webp" => "webp"
    ];

    $tipo = $informacion["mime"];

    if (!isset($tipos_permitidos[$tipo])) {
        return [
            "success" => false,
            "message" => "Solo se permiten imágenes JPG, PNG o WEBP."
        ];
    }


    $extension = $tipos_permitidos[$tipo];


    /* =====================================================
       CREAR NOMBRE ÚNICO
    ===================================================== */

    $nombre_archivo =
        $prefijo . "_" .
        $id_usuario . "_" .
        time() . "_" .
        bin2hex(random_bytes(4)) .
        "." .
        $extension;


    /* =====================================================
       CREAR CARPETA SI NO EXISTE
    ===================================================== */

    if (!is_dir($carpeta)) {

        mkdir(
            $carpeta,
            0755,
            true
        );
    }


    $ruta =
        $carpeta .
        $nombre_archivo;


    /* =====================================================
       GUARDAR ARCHIVO
    ===================================================== */

    if (
        !move_uploaded_file(
            $archivo["tmp_name"],
            $ruta
        )
    ) {

        return [
            "success" => false,
            "message" => "No se pudo guardar la imagen."
        ];
    }


    return [
        "success" => true,
        "file" => $nombre_archivo
    ];
}


/* =========================================================
   GUARDAR FOTO DE PERFIL
========================================================= */

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["subir_perfil"])
) {

    if (
        !isset($_FILES["foto_perfil"]) ||
        $_FILES["foto_perfil"]["error"] !== UPLOAD_ERR_OK
    ) {

        $mensaje =
            "Selecciona una foto de perfil.";

        $tipo_mensaje =
            "error";

    } else {

        $carpeta =
            "../uploads/perfiles/";


        $resultado =
            subirImagen(
                $_FILES["foto_perfil"],
                $carpeta,
                "perfil",
                $id_usuario
            );


        if ($resultado["success"]) {

            $nueva_foto =
                $resultado["file"];


            /* =========================================
               BUSCAR FOTO ANTERIOR
            ========================================= */

            $consulta =
                $conexion->prepare("
                    SELECT foto_perfil
                    FROM usuarios
                    WHERE id_usuario = ?
                    LIMIT 1
                ");

            $consulta->bind_param(
                "i",
                $id_usuario
            );

            $consulta->execute();

            $resultado_bd =
                $consulta->get_result();

            $usuario =
                $resultado_bd->fetch_assoc();


            /* =========================================
               GUARDAR NUEVA FOTO EN MYSQL
            ========================================= */

            $actualizar =
                $conexion->prepare("
                    UPDATE usuarios
                    SET foto_perfil = ?
                    WHERE id_usuario = ?
                ");

            $actualizar->bind_param(
                "si",
                $nueva_foto,
                $id_usuario
            );


            if ($actualizar->execute()) {

                /* ==============================
                   ELIMINAR FOTO ANTERIOR
                ============================== */

                if (
                    !empty($usuario["foto_perfil"]) &&
                    file_exists(
                        "../uploads/perfiles/" .
                        $usuario["foto_perfil"]
                    )
                ) {

                    unlink(
                        "../uploads/perfiles/" .
                        $usuario["foto_perfil"]
                    );
                }


                $mensaje =
                    "¡Foto de perfil guardada correctamente!";

                $tipo_mensaje =
                    "success";

            } else {

                $mensaje =
                    "La imagen se subió, pero no se pudo guardar en la base de datos.";

                $tipo_mensaje =
                    "error";
            }


            $actualizar->close();
            $consulta->close();

        } else {

            $mensaje =
                $resultado["message"];

            $tipo_mensaje =
                "error";
        }
    }
}


/* =========================================================
   GUARDAR FOTO DE PORTADA
========================================================= */

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["subir_portada"])
) {

    if (
        !isset($_FILES["foto_portada"]) ||
        $_FILES["foto_portada"]["error"] !== UPLOAD_ERR_OK
    ) {

        $mensaje =
            "Selecciona una foto de portada.";

        $tipo_mensaje =
            "error";

    } else {

        $carpeta =
            "../uploads/portadas/";


        $resultado =
            subirImagen(
                $_FILES["foto_portada"],
                $carpeta,
                "portada",
                $id_usuario
            );


        if ($resultado["success"]) {

            $nueva_portada =
                $resultado["file"];


            /* =========================================
               BUSCAR PORTADA ANTERIOR
            ========================================= */

            $consulta =
                $conexion->prepare("
                    SELECT foto_portada
                    FROM usuarios
                    WHERE id_usuario = ?
                    LIMIT 1
                ");

            $consulta->bind_param(
                "i",
                $id_usuario
            );

            $consulta->execute();

            $resultado_bd =
                $consulta->get_result();

            $usuario =
                $resultado_bd->fetch_assoc();


            /* =========================================
               GUARDAR PORTADA EN MYSQL
            ========================================= */

            $actualizar =
                $conexion->prepare("
                    UPDATE usuarios
                    SET foto_portada = ?
                    WHERE id_usuario = ?
                ");

            $actualizar->bind_param(
                "si",
                $nueva_portada,
                $id_usuario
            );


            if ($actualizar->execute()) {

                /* ==============================
                   ELIMINAR PORTADA ANTERIOR
                ============================== */

                if (
                    !empty($usuario["foto_portada"]) &&
                    file_exists(
                        "../uploads/portadas/" .
                        $usuario["foto_portada"]
                    )
                ) {

                    unlink(
                        "../uploads/portadas/" .
                        $usuario["foto_portada"]
                    );
                }


                $mensaje =
                    "¡Foto de portada guardada correctamente!";

                $tipo_mensaje =
                    "success";

            } else {

                $mensaje =
                    "La imagen se subió, pero no se pudo guardar en la base de datos.";

                $tipo_mensaje =
                    "error";
            }


            $actualizar->close();
            $consulta->close();

        } else {

            $mensaje =
                $resultado["message"];

            $tipo_mensaje =
                "error";
        }
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

    <title>
        Cambiar fotos | CorreoChat
    </title>


    <link
        rel="stylesheet"
        href="../assets/css/style.css"
    >

    <link
        rel="stylesheet"
        href="../assets/css/navbar.css"
    >


    <style>

        /* =================================================
           CONTENEDOR PRINCIPAL
        ================================================= */

        .fotos-container {

            width: 100%;

            max-width: 760px;

            margin: 0 auto;

            padding: 35px 20px 60px;
        }


        /* =================================================
           TITULO
        ================================================= */

        .fotos-header {

            margin-bottom: 25px;
        }


        .fotos-header h1 {

            font-size: 28px;

            color: #202124;

            margin-bottom: 8px;
        }


        .fotos-header p {

            color: #70757a;

            font-size: 14px;
        }


        .volver {

            display: inline-block;

            margin-bottom: 18px;

            color: #1877f2;

            text-decoration: none;

            font-size: 14px;

            font-weight: 600;
        }


        .volver:hover {

            text-decoration: underline;
        }


        /* =================================================
           MENSAJE
        ================================================= */

        .mensaje {

            padding: 14px 16px;

            border-radius: 10px;

            margin-bottom: 20px;

            font-size: 14px;

            font-weight: 600;
        }


        .mensaje.success {

            background: #e9f9ef;

            color: #16803c;

            border: 1px solid #b7e8c7;
        }


        .mensaje.error {

            background: #fff0f0;

            color: #d93025;

            border: 1px solid #f4b4b4;
        }


        /* =================================================
           TARJETA
        ================================================= */

        .foto-card {

            background: #ffffff;

            border: 1px solid #e4e6eb;

            border-radius: 16px;

            padding: 25px;

            margin-bottom: 20px;

            box-shadow:
                0 3px 12px
                rgba(0,0,0,.06);
        }


        .foto-card h2 {

            font-size: 19px;

            color: #202124;

            margin-bottom: 8px;
        }


        .foto-card p {

            color: #65676b;

            font-size: 14px;

            line-height: 1.5;

            margin-bottom: 20px;
        }


        /* =================================================
           FORMULARIO
        ================================================= */

        .foto-form {

            display: flex;

            flex-direction: column;

            gap: 15px;
        }


        .archivo {

            width: 100%;

            padding: 14px;

            border: 1px solid #ccd0d5;

            border-radius: 10px;

            background: #f8f9fa;

            cursor: pointer;

            font-size: 14px;
        }


        .archivo:hover {

            border-color: #1877f2;
        }


        /* =================================================
           BOTÓN GUARDAR
        ================================================= */

        .btn-guardar {

            width: 100%;

            border: none;

            border-radius: 10px;

            padding: 14px;

            background: #1877f2;

            color: #ffffff;

            font-size: 15px;

            font-weight: 700;

            cursor: pointer;

            transition: .2s;
        }


        .btn-guardar:hover {

            background: #166fe5;

            transform: translateY(-1px);
        }


        .btn-portada {

            background: #1877f2;
        }


        /* =================================================
           INFORMACIÓN
        ================================================= */

        .info {

            margin-top: 10px;

            font-size: 12px !important;

            color: #8a8d91 !important;

            margin-bottom: 0 !important;
        }


        /* =================================================
           RESPONSIVE
        ================================================= */

        @media (max-width: 600px) {

            .fotos-container {

                padding:
                    25px 12px 40px;
            }


            .foto-card {

                padding: 20px;
            }


            .fotos-header h1 {

                font-size: 24px;
            }

        }

    </style>

</head>


<body>


<?php include "../includes/navbar.php"; ?>


<main class="fotos-container">


    <!-- =================================================
         VOLVER
    ================================================= -->

    <a
        href="perfil.php"
        class="volver"
    >
        ← Volver a mi perfil
    </a>


    <!-- =================================================
         ENCABEZADO
    ================================================= -->

    <div class="fotos-header">

        <h1>
            Fotos de perfil
        </h1>

        <p>
            Personaliza tu perfil de CorreoChat.
        </p>

    </div>


    <!-- =================================================
         MENSAJE
    ================================================= -->

    <?php if ($mensaje !== ""): ?>

        <div
            class="mensaje <?= htmlspecialchars($tipo_mensaje) ?>"
        >

            <?= htmlspecialchars($mensaje) ?>

        </div>

    <?php endif; ?>


    <!-- =================================================
         FOTO DE PERFIL
    ================================================= -->

    <section class="foto-card">

        <h2>
            📷 Foto de perfil
        </h2>

        <p>
            Esta foto aparecerá en tu perfil,
            publicaciones, comentarios y otras
            partes de CorreoChat.
        </p>


        <form
            method="POST"
            enctype="multipart/form-data"
            class="foto-form"
        >

            <input
                type="file"
                name="foto_perfil"
                class="archivo"
                accept=".jpg,.jpeg,.png,.webp"
                required
            >


            <button
                type="submit"
                name="subir_perfil"
                class="btn-guardar"
            >

                💾 Guardar foto de perfil

            </button>

        </form>


        <p class="info">
            Formatos permitidos: JPG, PNG y WEBP.
            Tamaño máximo: 5 MB.
        </p>

    </section>



    <!-- =================================================
         FOTO DE PORTADA
    ================================================= -->

    <section class="foto-card">

        <h2>
            🖼️ Foto de portada
        </h2>

        <p>
            Esta imagen aparecerá en la parte superior
            de tu perfil de CorreoChat.
        </p>


        <form
            method="POST"
            enctype="multipart/form-data"
            class="foto-form"
        >

            <input
                type="file"
                name="foto_portada"
                class="archivo"
                accept=".jpg,.jpeg,.png,.webp"
                required
            >


            <button
                type="submit"
                name="subir_portada"
                class="btn-guardar btn-portada"
            >

                💾 Guardar foto de portada

            </button>

        </form>


        <p class="info">
            Formatos permitidos: JPG, PNG y WEBP.
            Tamaño máximo: 5 MB.
        </p>

    </section>


</main>


</body>

</html>