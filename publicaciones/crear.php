<?php

require_once "../includes/verificar_sesion.php";
require_once "../config/conexion.php";

$id_usuario = $_SESSION["id_usuario"];

$mensaje = "";
$tipo = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $contenido = trim($_POST["contenido"] ?? "");

    $imagen = null;
    $video = null;


    /* =====================================================
       VALIDAR CONTENIDO
    ===================================================== */

    if ($contenido === "" && empty($_FILES["imagen"]["name"]) && empty($_FILES["video"]["name"])) {

        $mensaje = "Escribe algo o selecciona una imagen/video.";
        $tipo = "error";

    } else {


        /* =================================================
           IMAGEN
        ================================================= */

        if (
            isset($_FILES["imagen"]) &&
            $_FILES["imagen"]["error"] === UPLOAD_ERR_OK
        ) {

            $permitidos = [
                "image/jpeg" => "jpg",
                "image/png" => "png",
                "image/webp" => "webp"
            ];

            $tipo_imagen =
                mime_content_type($_FILES["imagen"]["tmp_name"]);


            if (!isset($permitidos[$tipo_imagen])) {

                $mensaje = "La imagen debe ser JPG, PNG o WEBP.";
                $tipo = "error";

            } elseif ($_FILES["imagen"]["size"] > 5 * 1024 * 1024) {

                $mensaje = "La imagen no puede superar los 5 MB.";
                $tipo = "error";

            } else {

                $extension =
                    $permitidos[$tipo_imagen];

                $nombre_imagen =
                    "publicacion_" .
                    $id_usuario . "_" .
                    time() . "_" .
                    bin2hex(random_bytes(4)) .
                    "." .
                    $extension;


                $carpeta =
                    "../uploads/publicaciones/";


                if (!is_dir($carpeta)) {
                    mkdir($carpeta, 0755, true);
                }


                if (
                    move_uploaded_file(
                        $_FILES["imagen"]["tmp_name"],
                        $carpeta . $nombre_imagen
                    )
                ) {

                    $imagen = $nombre_imagen;

                } else {

                    $mensaje =
                        "No se pudo guardar la imagen.";

                    $tipo = "error";
                }
            }
        }


        /* =================================================
           VIDEO
        ================================================= */

        if (
            $tipo !== "error" &&
            isset($_FILES["video"]) &&
            $_FILES["video"]["error"] === UPLOAD_ERR_OK
        ) {

            $permitidos_video = [
                "video/mp4" => "mp4",
                "video/webm" => "webm",
                "video/ogg" => "ogv"
            ];


            $tipo_video =
                mime_content_type(
                    $_FILES["video"]["tmp_name"]
                );


            if (!isset($permitidos_video[$tipo_video])) {

                $mensaje =
                    "El video debe ser MP4, WEBM u OGG.";

                $tipo = "error";

            } elseif (
                $_FILES["video"]["size"] >
                50 * 1024 * 1024
            ) {

                $mensaje =
                    "El video no puede superar los 50 MB.";

                $tipo = "error";

            } else {

                $extension =
                    $permitidos_video[$tipo_video];


                $nombre_video =
                    "video_" .
                    $id_usuario . "_" .
                    time() . "_" .
                    bin2hex(random_bytes(4)) .
                    "." .
                    $extension;


                $carpeta =
                    "../uploads/videos/";


                if (!is_dir($carpeta)) {
                    mkdir($carpeta, 0755, true);
                }


                if (
                    move_uploaded_file(
                        $_FILES["video"]["tmp_name"],
                        $carpeta . $nombre_video
                    )
                ) {

                    $video =
                        $nombre_video;

                } else {

                    $mensaje =
                        "No se pudo guardar el video.";

                    $tipo = "error";
                }
            }
        }


        /* =================================================
           GUARDAR PUBLICACIÓN
        ================================================= */

        if ($tipo !== "error") {

            $guardar =
                $conexion->prepare("
                    INSERT INTO publicaciones
                    (
                        id_usuario,
                        contenido,
                        imagen,
                        video
                    )
                    VALUES (?, ?, ?, ?)
                ");


            $guardar->bind_param(
                "isss",
                $id_usuario,
                $contenido,
                $imagen,
                $video
            );


            if ($guardar->execute()) {

                header(
                    "Location: ../index.php"
                );

                exit;

            } else {

                $mensaje =
                    "No se pudo guardar la publicación.";

                $tipo = "error";
            }


            $guardar->close();
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
        Crear publicación | CorreoChat
    </title>

    <link
        rel="stylesheet"
        href="../assets/css/style.css"
    >

    <link
        rel="stylesheet"
        href="../assets/css/navbar.css"
    >

</head>


<body>


<?php include "../includes/navbar.php"; ?>


<main class="crear-publicacion">


    <a
        href="../index.php"
        class="volver"
    >
        ← Volver al inicio
    </a>


    <div class="publicacion-card">


        <div class="publicacion-header">

            <h1>
                Crear publicación
            </h1>

            <p>
                Comparte algo con tus amigos.
            </p>

        </div>


        <?php if ($mensaje !== ""): ?>

            <div class="mensaje <?= $tipo ?>">

                <?= htmlspecialchars($mensaje) ?>

            </div>

        <?php endif; ?>


        <form
            method="POST"
            enctype="multipart/form-data"
            class="publicacion-form"
        >


            <textarea
                name="contenido"
                placeholder="¿Qué estás pensando?"
                rows="6"
            ></textarea>


            <div class="archivo-grupo">

                <label>
                    📷 Imagen
                </label>

                <input
                    type="file"
                    name="imagen"
                    accept=".jpg,.jpeg,.png,.webp"
                >

            </div>


            <div class="archivo-grupo">

                <label>
                    🎥 Video
                </label>

                <input
                    type="file"
                    name="video"
                    accept=".mp4,.webm,.ogg"
                >

            </div>


            <button
                type="submit"
                class="btn-publicar"
            >

                🚀 Publicar

            </button>


        </form>

    </div>

</main>


</body>

</html>