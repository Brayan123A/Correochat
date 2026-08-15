<?php

session_start();

require_once "../config/conexion.php";


/*
=========================================================
ELIMINAR SESIÓN DE BASE DE DATOS
=========================================================
*/

if (isset($_SESSION["id_usuario"])) {

    $id_usuario = $_SESSION["id_usuario"];

    $eliminar = $conexion->prepare("
        DELETE FROM sesiones
        WHERE id_usuario = ?
    ");

    $eliminar->bind_param(
        "i",
        $id_usuario
    );

    $eliminar->execute();
}


/*
=========================================================
DESTRUIR SESIÓN
=========================================================
*/

$_SESSION = [];

if (ini_get("session.use_cookies")) {

    $parametros = session_get_cookie_params();

    setcookie(
        session_name(),
        "",
        time() - 42000,
        $parametros["path"],
        $parametros["domain"],
        $parametros["secure"],
        $parametros["httponly"]
    );
}

session_destroy();


/*
=========================================================
VOLVER AL LOGIN
=========================================================
*/

header("Location: login.php");

exit;