<?php

require_once "config/conexion.php";

echo "<h1>CorreoChat</h1>";

if ($conexion->ping()) {
    echo "<p style='color: green;'>✓ Conexión a la base de datos correcta.</p>";
} else {
    echo "<p style='color: red;'>✗ Error de conexión.</p>";
}

echo "<p>Base de datos: correochat</p>";