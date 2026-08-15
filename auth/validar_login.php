<?php

session_start();

require_once "../config/conexion.php";


/*
=========================================================
VERIFICAR MÉTODO
=========================================================
*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: login.php");

    exit;
}


/*
=========================================================
RECIBIR DATOS
=========================================================
*/

$correo = trim($_POST["correo"] ?? "");
$password = $_POST["password"] ?? "";


/*
=========================================================
VALIDAR CAMPOS
=========================================================
*/

if ($correo === "" || $password === "") {

    $_SESSION["error_login"] =
        "Debes completar todos los campos.";

    header("Location: login.php");

    exit;
}


/*
=========================================================
BUSCAR USUARIO
=========================================================
*/

$consulta = $conexion->prepare("
    SELECT
        id_usuario,
        nombre,
        usuario,
        correo,
        password,
        estado,
        rol
    FROM usuarios
    WHERE correo = ?
       OR usuario = ?
    LIMIT 1
");

$consulta->bind_param(
    "ss",
    $correo,
    $correo
);

$consulta->execute();

$resultado = $consulta->get_result();


/*
=========================================================
USUARIO NO ENCONTRADO
=========================================================
*/

if ($resultado->num_rows === 0) {

    $_SESSION["error_login"] =
        "El usuario o contraseña son incorrectos.";

    header("Location: login.php");

    exit;
}


$usuario = $resultado->fetch_assoc();


/*
=========================================================
VERIFICAR ESTADO
=========================================================
*/

if ($usuario["estado"] !== "activo") {

    $_SESSION["error_login"] =
        "Esta cuenta no está disponible.";

    header("Location: login.php");

    exit;
}


/*
=========================================================
VERIFICAR CONTRASEÑA
=========================================================
*/

if (!password_verify($password, $usuario["password"])) {

    $_SESSION["error_login"] =
        "El usuario o contraseña son incorrectos.";

    header("Location: login.php");

    exit;
}


/*
=========================================================
REGENERAR SESIÓN
=========================================================
*/

session_regenerate_id(true);


/*
=========================================================
GUARDAR SESIÓN
=========================================================
*/

$_SESSION["id_usuario"] = $usuario["id_usuario"];
$_SESSION["nombre"] = $usuario["nombre"];
$_SESSION["usuario"] = $usuario["usuario"];
$_SESSION["correo"] = $usuario["correo"];
$_SESSION["rol"] = $usuario["rol"];


/*
=========================================================
ACTUALIZAR ÚLTIMA CONEXIÓN
=========================================================
*/

$actualizar = $conexion->prepare("
    UPDATE usuarios
    SET ultima_conexion = NOW()
    WHERE id_usuario = ?
");

$actualizar->bind_param(
    "i",
    $usuario["id_usuario"]
);

$actualizar->execute();


/*
=========================================================
GUARDAR SESIÓN EN BASE DE DATOS
=========================================================
*/

$token = bin2hex(random_bytes(32));

$ip = $_SERVER["REMOTE_ADDR"] ?? null;

$dispositivo = $_SERVER["HTTP_USER_AGENT"] ?? null;


$guardar_sesion = $conexion->prepare("
    INSERT INTO sesiones (
        id_usuario,
        token,
        ip,
        dispositivo
    )
    VALUES (?, ?, ?, ?)
");

$guardar_sesion->bind_param(
    "isss",
    $usuario["id_usuario"],
    $token,
    $ip,
    $dispositivo
);

$guardar_sesion->execute();


/*
=========================================================
IR AL INICIO
=========================================================
*/

header("Location: ../inicio/index.php");

exit;