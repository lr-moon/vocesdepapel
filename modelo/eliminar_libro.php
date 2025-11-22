<?php

include ('../controlador/conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id_libro = $_GET['id'];
}

$query = "DELETE FROM libro WHERE id_libro = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_libro);

if (!$stmt->execute()) { //Si no se pudo eliminar el libro
    echo "<script>alert('Error al eliminar el libro: " . $stmt->error . "'); window.location.href='../vista/admin_login.php';</script>";
} 

$stmt->close();
$conn->close();
echo "<script>alert('Libro eliminado correctamente'); window.location.href='../vista/admin_login.php';</script>";
?>
