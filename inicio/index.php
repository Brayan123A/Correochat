<?php

session_start();

/*
=========================================================
SI EL USUARIO YA INICIÓ SESIÓN
=========================================================
*/

if (isset($_SESSION["id_usuario"])) {

    header("Location: inicio/index.php");
    exit;

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
        CorreoChat | Conecta. Comparte. Conversa.
    </title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {

            min-height: 100vh;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            background:
                linear-gradient(
                    135deg,
                    #1877f2,
                    #4f46e5
                );

            color: white;

            display: flex;

            justify-content: center;

            align-items: center;

            padding: 25px;

        }


        .contenedor {

            width: 100%;

            max-width: 1050px;

            display: grid;

            grid-template-columns:
                1fr
                420px;

            gap: 70px;

            align-items: center;

        }


        /* ==========================================
           PRESENTACIÓN
        ========================================== */

        .presentacion h1 {

            font-size: 64px;

            margin-bottom: 15px;

            letter-spacing: -2px;

        }


        .presentacion h2 {

            font-size: 28px;

            font-weight: 400;

            margin-bottom: 20px;

        }


        .presentacion p {

            font-size: 18px;

            line-height: 1.6;

            max-width: 570px;

            opacity: .92;

        }


        .caracteristicas {

            margin-top: 30px;

            display: grid;

            grid-template-columns:
                1fr
                1fr;

            gap: 15px;

        }


        .caracteristica {

            background:
                rgba(255,255,255,.12);

            padding: 15px;

            border-radius: 12px;

            backdrop-filter: blur(8px);

        }


        .caracteristica strong {

            display: block;

            margin-bottom: 5px;

        }


        .caracteristica span {

            font-size: 13px;

            opacity: .85;

        }


        /* ==========================================
           TARJETA
        ========================================== */

        .tarjeta {

            background: white;

            color: #1c1e21;

            padding: 32px;

            border-radius: 16px;

            box-shadow:
                0 15px 40px
                rgba(0,0,0,.25);

            text-align: center;

        }


        .tarjeta-logo {

            color: #1877f2;

            font-size: 35px;

            font-weight: bold;

            margin-bottom: 8px;

        }


        .tarjeta p {

            color: #65676b;

            line-height: 1.5;

            margin-bottom: 25px;

        }


        .boton {

            display: block;

            width: 100%;

            padding: 14px;

            border-radius: 9px;

            text-decoration: none;

            font-weight: bold;

            font-size: 16px;

            margin-bottom: 12px;

        }


        .boton-login {

            background: #1877f2;

            color: white;

        }


        .boton-login:hover {

            background: #166fe5;

        }


        .boton-registro {

            background: #42b72a;

            color: white;

        }


        .boton-registro:hover {

            background: #36a420;

        }


        .separador {

            border: none;

            border-top:
                1px solid #ddd;

            margin: 22px 0;

        }


        .privacidad {

            color: #8a8d91;

            font-size: 12px;

            line-height: 1.5;

        }


        /* ==========================================
           MÓVIL
        ========================================== */

        @media (max-width: 800px) {

            body {

                align-items: flex-start;

            }


            .contenedor {

                grid-template-columns: 1fr;

                gap: 35px;

                max-width: 500px;

                margin-top: 30px;

            }


            .presentacion {

                text-align: center;

            }


            .presentacion h1 {

                font-size: 46px;

            }


            .presentacion h2 {

                font-size: 22px;

            }


            .presentacion p {

                font-size: 16px;

            }


            .caracteristicas {

                grid-template-columns:
                    1fr
                    1fr;

            }

        }


        @media (max-width: 450px) {

            .presentacion h1 {

                font-size: 40px;

            }


            .caracteristicas {

                grid-template-columns: 1fr;

            }


            .tarjeta {

                padding: 25px 20px;

            }

        }

    </style>

</head>


<body>


<div class="contenedor">


    <!-- ==========================================
         PRESENTACIÓN
    ========================================== -->

    <section class="presentacion">

        <h1>
            CorreoChat
        </h1>


        <h2>
            Conecta. Comparte. Conversa.
        </h2>


        <p>

            Una nueva forma de conectar con tus amigos,
            compartir momentos, publicar contenido y
            conversar en un solo lugar.

        </p>


        <div class="caracteristicas">


            <div class="caracteristica">

                <strong>
                    👥 Amigos
                </strong>

                <span>
                    Conecta con personas.
                </span>

            </div>


            <div class="caracteristica">

                <strong>
                    📸 Historias
                </strong>

                <span>
                    Comparte momentos.
                </span>

            </div>


            <div class="caracteristica">

                <strong>
                    💬 CorreoChat
                </strong>

                <span>
                    Habla con tus amigos.
                </span>

            </div>


            <div class="caracteristica">

                <strong>
                    🎵 Música
                </strong>

                <span>
                    Comparte tus canciones.
                </span>

            </div>


        </div>

    </section>


    <!-- ==========================================
         TARJETA DE ACCESO
    ========================================== -->

    <section class="tarjeta">


        <div class="tarjeta-logo">

            CorreoChat

        </div>


        <p>

            Inicia sesión para entrar a tu cuenta
            o crea una nueva para comenzar.

        </p>


        <a
            href="auth/login.php"
            class="boton boton-login"
        >

            Iniciar sesión

        </a>


        <a
            href="auth/registro.php"
            class="boton boton-registro"
        >

            Crear una cuenta

        </a>


        <hr class="separador">


        <div class="privacidad">

            Tu información estará protegida y podrás
            controlar quién puede ver tu contenido.

        </div>


    </section>


</div>


</body>

</html>