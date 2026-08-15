<?php

session_start();

require_once "../config/conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: registro.php");
    exit;
}

/*
=========================================================
RECIBIR DATOS
=========================================================
*/

$nombre = trim($_POST["nombre"] ?? "");
$apellido = trim($_POST["apellido"] ?? "");
$usuario = trim($_POST["usuario"] ?? "");
$correo = trim($_POST["correo"] ?? "");
$password = $_POST["password"] ?? "";
$confirmar_password = $_POST["confirmar_password"] ?? "";


/*
=========================================================
VALIDACIONES
=========================================================
*/

if (
    $nombre === "" ||
    $usuario === "" ||
    $correo === "" ||
    $password === "" ||
    $confirmar_password === ""
) {
    die("Todos los campos obligatorios deben estar completos.");
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    die("El correo electrónico no es válido.");
}

if (strlen($password) < 8) {
    die("La contraseña debe tener al menos 8 caracteres.");
}

if ($password !== $confirmar_password) {
    die("Las contraseñas no coinciden.");
}

if (!preg_match("/^[A-Za-z0-9_.]+$/", $usuario)) {
    die("El nombre de usuario solamente puede contener letras, números, guion bajo y punto.");
}


/*
=========================================================
COMPROBAR CORREO Y USUARIO
=========================================================
*/

$consulta = $conexion->prepare("
    SELECT id_usuario
    FROM usuarios
    WHERE correo = ? OR usuario = ?
    LIMIT 1
");

$consulta->bind_param(
    "ss",
    $correo,
    $usuario
);

$consulta->execute();

$resultado = $consulta->get_result();

if ($resultado->num_rows > 0) {

    $usuario_existente = $resultado->fetch_assoc();

    /*
    Comprobamos exactamente cuál está repetido.
    */

    $consulta_correo = $conexion->prepare("
        SELECT id_usuario
        FROM usuarios
        WHERE correo = ?
        LIMIT 1
    ");

    $consulta_correo->bind_param("s", $correo);
    $consulta_correo->execute();

    if ($consulta_correo->get_result()->num_rows > 0) {
        die("El correo electrónico ya está registrado.");
    }

    die("El nombre de usuario ya está ocupado.");
}


/*
=========================================================
PROTEGER CONTRASEÑA
=========================================================
*/

$password_hash = password_hash(
    $password,
    PASSWORD_DEFAULT
);


/*
=========================================================
INSERTAR USUARIO
=========================================================
*/

$insertar = $conexion->prepare("
    INSERT INTO usuarios (
        nombre,
        apellido,
        usuario,
        correo,
        password
    )
    VALUES (?, ?, ?, ?, ?)
");

$insertar->bind_param(
    "sssss",
    $nombre,
    $apellido,
    $usuario,
    $correo,
    $password_hash
);


/*
=========================================================
GUARDAR
=========================================================
*/

if ($insertar->execute()) {

    $id_usuario = $conexion->insert_id;

    /*
    Guardamos el usuario en sesión.
    */

    $_SESSION["id_usuario"] = $id_usuario;
    $_SESSION["usuario"] = $usuario;
    $_SESSION["nombre"] = $nombre;

    header("Location: ../inicio/index.php");
    exit;

} else {

    die(
        "No se pudo crear la cuenta: " .
        $conexion->error
    );
}