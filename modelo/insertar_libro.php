<?php

include ('../controlador/conexion.php');

//Metodos para  insertar el nuevo libro. 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $descripcion = $_POST['descripcion'];
    $ruta_imagen = $_POST['ruta_imagen'];
    //Se declaran de una vez todas las prioridades, ya que 
    //serviran para darle pesos a los generos del libro.
    $prioridad_1 = $_POST['prioridad_1'];
    $prioridad_2 = $_POST['prioridad_2'];
    $prioridad_3 = $_POST['prioridad_3'];
    $id_genero_1 = $_POST['id_genero_1'];
    $id_genero_2 = $_POST['id_genero_2'];
    $id_genero_3 = $_POST['id_genero_3'];
}

$query = "INSERT INTO libro (titulo, autor, descripcion, ruta_imagen) VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($query);
$stmt->bind_param("ssss", $titulo, $autor, $descripcion, $ruta_imagen);

if (!$stmt->execute()) { //Si no se inserta el libro
    echo "Error al insertar el libro: " . $stmt->error;
} 

//Buscar el id del libro insertado
//Se busca ya que el id es autoincremental por lo que se tiene que recuperar.
$query_id_libro = "SELECT id_libro FROM libro WHERE titulo = ?";

$stmt_id_libro = $conn->prepare($query_id_libro);
$stmt_id_libro->bind_param("s", $titulo);
$stmt_id_libro->execute();
$result_id_libro = $stmt_id_libro->get_result();

if ($result_id_libro->num_rows > 0) {
    $row = $result_id_libro->fetch_assoc();
    $id_libro = $row['id_libro'];
} else {
    echo "Error al buscar el id del libro insertado.";
}


//Se guardan en arreglos, para que puedan ser insertados en la tabla libro_genero
$generos = [
    $id_genero_1,
    $id_genero_2,
    $id_genero_3
];

$prioridades = [
    $prioridad_1,
    $prioridad_2,
    $prioridad_3
];

for ($i = 0; $i < 3; $i++) { //Se repite 3 veces para insertar los 3 generos del libro.
    //Se nsertan todos los generos del libro en la tabla libro_genero
    $query_libro_genero = "INSERT INTO libro_genero (id_libro, id_genero, prioridad) VALUES (?, ?, ?)"; 

    $stmt_libro_genero = $conn->prepare($query_libro_genero);
    $stmt_libro_genero->bind_param("iii", $id_libro, $generos[$i], $prioridades[$i]); //Se remplazan los valores de la variable $i, para que se haga sin repetir 3 veces el blucle.

    if (!$stmt_libro_genero->execute()) { //Si no se inserta el libro
        echo "Error al insertar el libro: " . $stmt_libro_genero->error;
    } 
}

$stmt_libro_genero->close();
$stmt->close();
$conn->close();
?>