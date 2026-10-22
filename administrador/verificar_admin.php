
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (
    !isset($_SESSION["id_usuario"]) ||
    !isset($_SESSION["rol"]) ||
    $_SESSION["rol"] !== "Administrador"
) {
    header("Location: ../iniciodesesion.php");
    exit();
}

?>
