<?php

include ('../controlador/conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_libro = $_POST['id_libro'];
}

$query = "DELETE FROM libro WHERE id_libro = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_libro);

if (!$stmt->execute()) { //Si no se inserta el libro
    echo "Error al insertar el libro: " . $stmt->error;
} 

$stmt->close();
$conn->close();
?>
