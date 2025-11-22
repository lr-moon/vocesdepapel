<?php 

include 'conexion.php';

$genero = $_GET['genero'];

$genero = 'Romance';

$sql = "SELECT 
        l.titulo,
        l.autor,
        g.nombre_genero,
        lg.prioridad
    FROM libro l
    JOIN libro_genero lg ON l.id_libro = lg.id_libro
    JOIN genero g ON lg.id_genero = g.id_genero
    WHERE g.nombre_genero = ?
    ORDER BY lg.prioridad;
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $genero);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $libros[] = $row;
    }
} else {
    echo "No se encontraron libros para el genero especificado.";
}
//TODO: 
//Los libros estan guardados en un arreglo, pero iran en la seccion 
//principal los que tengan prioridad 1, y asi seguiran.

//Una duda como puedo utilizar toda esta logica como una funcion de 
// otro archivo, para que por ejemplo somanete mande a llamar a la funcion
// y ponga el resultado como un ECHO y se genere toda una lista con informacion ?

$stmt->close();
$conn->close();
