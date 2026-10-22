<?php
// Datos de conexión
$servidor = "localhost";
$usuario  = "root";
$password = ""; 
$base_datos = "agrofuturo";

$conexion = mysqli_connect($servidor, $usuario, $password, $base_datos);

if (!$conexion) {
    die("Error en la conexión: " . mysqli_connect_error());
}

// Recibir datos del formulario
$correo = $_POST['loginCorreo'];
$pass   = $_POST['loginPassword'];

// Insertar datos en la tabla
$sql = "INSERT INTO usuarios (correo, password) VALUES ('$correo', '$pass')";

if (mysqli_query($conexion, $sql)) {
    echo "<script>
            alert('¡Datos de inicio de sesión guardados con éxito!');
            window.location.href='index_principal.php';
          </script>";
} else {
    echo "Error al guardar: " . mysqli_error($conexion);
}

mysqli_close($conexion);
?>