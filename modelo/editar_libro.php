<?php
include ('../controlador/conexion.php');

//Solo se podran editar la descripcion y la imagen del libro 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descripcion = $_POST['descripcion'];
    $ruta_imagen = $_POST['ruta_imagen'];
    $id_libro = $_POST['id_libro'];
}


$query = "UPDATE libro SET descripcion = ?, ruta_imagen = ? WHERE id_libro = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("ssi", $descripcion, $ruta_imagen, $id_libro);

if (!$stmt->execute()) { //Si no se inserta el libro
    echo "<script>alert('Error al editar el libro: " . $stmt->error . "'); window.location.href='../vista/admin_login.php';</script>";
} 

$stmt->close();
$conn->close();

echo "<script>alert('Libro editado correctamente'); window.location.href='../vista/admin_login.php';</script>";
?>
