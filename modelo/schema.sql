-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS biblioteca;
USE biblioteca;

-- Tabla libro
CREATE TABLE libro (
    id_libro INT PRIMARY KEY AUTO_INCREMENT,
    autor VARCHAR(100) NOT NULL,
    descripcion TEXT,
    titulo VARCHAR(200) NOT NULL,
    ruta_imagen LONGTEXT NOT NULL
);

-- Tabla genero
CREATE TABLE genero (
    id_genero INT PRIMARY KEY AUTO_INCREMENT,
    nombre_genero VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT
);

-- Tabla libro_genero (tabla intermedia)
CREATE TABLE libro_genero (
    id_libro INT NOT NULL,
    id_genero INT NOT NULL,
    prioridad TINYINT NOT NULL DEFAULT 1,
    PRIMARY KEY (id_libro, id_genero),
    FOREIGN KEY (id_libro) REFERENCES libro(id_libro) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_genero) REFERENCES genero(id_genero) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT chk_prioridad CHECK (prioridad BETWEEN 1 AND 3)
);

-- Tabla login para usuarios del sistema
CREATE TABLE login (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    contraseña VARCHAR(50) NOT NULL,
    correo VARCHAR(50) NOT NULL UNIQUE
);

-- Insertar géneros de ejemplo
INSERT INTO genero (nombre_genero, descripcion) VALUES
('Ciencia Ficción', 'Libros sobre futuros imaginarios, tecnología avanzada y espacio'),
('Fantasía', 'Obras con elementos mágicos y mundos imaginarios'),
('Romance', 'Historias centradas en relaciones amorosas'),
('Misterio', 'Narrativas con enigmas por resolver'),
('Terror', 'Obras diseñadas para causar miedo o suspense'),
('Biografía', 'Relatos de la vida de personas reales');

-- Insertar libros iniciales
INSERT INTO libro (titulo, autor, descripcion, ruta_imagen) VALUES
('Fundación', 'Isaac Asimov', 'Saga sobre la caída de un imperio galáctico', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1417900846i/29579.jpg'),
('El Hobbit', 'J.R.R. Tolkien', 'Aventura de Bilbo Bolsón en la Tierra Media', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1546071216i/5907.jpg'),
('Orgullo y Prejuicio', 'Jane Austen', 'Historia de amor entre Elizabeth Bennet y Mr. Darcy', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1320399351i/1885.jpg');

INSERT INTO libro_genero (id_libro, id_genero, prioridad) VALUES
(1, 1, 1), -- Fundación - Ciencia Ficción (principal)
(1, 5, 2), -- Fundación - Terror (secundario)
(2, 2, 1), -- El Hobbit - Fantasía (principal)
(3, 3, 1), -- Orgullo y Prejuicio - Romance (principal)
(3, 4, 2); -- Orgullo y Prejuicio - Misterio (secundario)

-- Insertar 20 nuevos libros con URLs reales de portadas
INSERT INTO libro (titulo, autor, descripcion, ruta_imagen) VALUES
('Duna', 'Frank Herbert', 'En el desértico planeta Arrakis, una familia lucha por controlar la especia melange, la sustancia más valiosa del universo.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1555447414i/44767458.jpg'),
('1984', 'George Orwell', 'En un futuro distópico, Winston Smith desafía el sistema totalitario del Gran Hermano.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1657781256i/61439040.jpg'),
('Cien años de soledad', 'Gabriel García Márquez', 'La saga de la familia Buendía en el mágico pueblo de Macondo.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1327881361i/320.png'),
('Harry Potter y la piedra filosofal', 'J.K. Rowling', 'Un niño descubre que es mago y comienza su educación en Hogwarts.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1550337333i/15881.jpg'),
('It', 'Stephen King', 'Un payaso demoníaco aterroriza a los niños de un pequeño pueblo de Maine.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1334416842i/830502.jpg'),
('El código Da Vinci', 'Dan Brown', 'Un simbólogo descubre una conspiración que podría cambiar la historia del cristianismo.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1579621267i/968.jpg'),
('Crepúsculo', 'Stephenie Meyer', 'Una joven se enamora de un vampiro en el lluvioso pueblo de Forks.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1361039443i/41865.jpg'),
('Steve Jobs', 'Walter Isaacson', 'La biografía autorizada del cofundador de Apple.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1511288481i/11084145.jpg'),
('Ready Player One', 'Ernest Cline', 'En un futuro distópico, jóvenes compiten en un reality virtual por una fortuna.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1500930947i/9969571.jpg'),
('Juego de tronos', 'George R.R. Martin', 'Familias nobles luchan por el control del Trono de Hierro en Poniente.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1273763400i/13496.jpg'),
('Los juegos del hambre', 'Suzanne Collins', 'En un futuro postapocalíptico, jóvenes son obligados a competir en un reality show mortal.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1586722975i/2767052.jpg'),
('Drácula', 'Bram Stoker', 'La clásica historia del conde vampiro de Transilvania.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1387151694i/17245.jpg'),
('El amor en los tiempos del cólera', 'Gabriel García Márquez', 'Una historia de amor que perdura por más de cincuenta años.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1327934453i/9712.jpg'),
('Sherlock Holmes: Estudio en escarlata', 'Arthur Conan Doyle', 'La primera aparición del famoso detective Sherlock Holmes.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1733131275i/114814.jpg'),
('El resplandor', 'Stephen King', 'Una familia se muda a un hotel aislado donde fuerzas malignas los acechan.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1641398308i/11588.jpg'),
('Moby Dick', 'Herman Melville', 'La obsesiva persecución de una ballena blanca por el capitán Ahab.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1327940656i/153747.jpg'),
('El señor de los anillos: La comunidad del anillo', 'J.R.R. Tolkien', 'Frodo Bolsón emprende un viaje para destruir el Anillo Único.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1654215925i/3263607.jpg'),
('La chica del tren', 'Paula Hawkins', 'Una mujer involucrada en la investigación de una desaparición misteriosa.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1574805688i/22557272.jpg'),
('Einstein: Su vida y universo', 'Walter Isaacson', 'Biografía completa del genio de la física Albert Einstein.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1348793833i/10884.jpg'),
('Neuromante', 'William Gibson', 'El hacker Case navega por el ciberespacio en un futuro distópico.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1167348726i/22328.jpg');

-- Insertar relaciones libro_genero con prioridades (usando solo los 6 géneros existentes)
INSERT INTO libro_genero (id_libro, id_genero, prioridad) VALUES
-- Duna - Ciencia Ficción (1) principal, Misterio (4) secundario
(4, 1, 1), (4, 4, 2),

-- 1984 - Ciencia Ficción (1) principal, Terror (5) secundario
(5, 1, 1), (5, 5, 2),

-- Cien años de soledad - Fantasía (2) principal, Romance (3) secundario
(6, 2, 1), (6, 3, 2),

-- Harry Potter - Fantasía (2) principal, Misterio (4) secundario
(7, 2, 1), (7, 4, 2),

-- It - Terror (5) principal, Misterio (4) secundario
(8, 5, 1), (8, 4, 2),

-- El código Da Vinci - Misterio (4) principal, Terror (5) secundario
(9, 4, 1), (9, 5, 2),

-- Crepúsculo - Romance (3) principal, Terror (5) secundario, Fantasía (2) terciario
(10, 3, 1), (10, 5, 2), (10, 2, 3),

-- Steve Jobs - Biografía (6) principal
(11, 6, 1),

-- Ready Player One - Ciencia Ficción (1) principal, Misterio (4) secundario
(12, 1, 1), (12, 4, 2),

-- Juego de tronos - Fantasía (2) principal, Misterio (4) secundario
(13, 2, 1), (13, 4, 2),

-- Los juegos del hambre - Ciencia Ficción (1) principal, Misterio (4) secundario
(14, 1, 1), (14, 4, 2),

-- Drácula - Terror (5) principal, Romance (3) secundario
(15, 5, 1), (15, 3, 2),

-- El amor en los tiempos del cólera - Romance (3) principal, Biografía (6) secundario
(16, 3, 1), (16, 6, 2),

-- Sherlock Holmes - Misterio (4) principal, Biografía (6) secundario
(17, 4, 1), (17, 6, 2),

-- El resplandor - Terror (5) principal, Misterio (4) secundario
(18, 5, 1), (18, 4, 2),

-- Moby Dick - Biografía (6) principal, Aventura (usando Fantasía 2 como aproximación)
(19, 6, 1), (19, 2, 2),

-- El señor de los anillos - Fantasía (2) principal, Misterio (4) secundario
(20, 2, 1), (20, 4, 2),

-- La chica del tren - Misterio (4) principal, Romance (3) secundario
(21, 4, 1), (21, 3, 2),

-- Einstein - Biografía (6) principal
(22, 6, 1),

-- Neuromante - Ciencia Ficción (1) principal, Misterio (4) secundario
(23, 1, 1), (23, 4, 2);

-- Insertar usuarios de ejemplo
INSERT INTO login (nombre, usuario, contraseña, correo) VALUES
('Administrador Principal', 'admin', '1234', 'admin@biblioteca.com'),
('Juan Pérez', 'juan', 'juan123', 'juan@email.com'),
('María García', 'maria', 'maria456', 'maria@email.com'),
('Carlos López', 'carlos', 'carlos789', 'carlos@email.com');

-- Consultas de ejemplo
-- Libros con sus géneros y prioridad
SELECT 
    l.titulo,
    l.autor,
    g.nombre_genero,
    lg.prioridad
FROM libro l
JOIN libro_genero lg ON l.id_libro = lg.id_libro
JOIN genero g ON lg.id_genero = g.id_genero
ORDER BY l.titulo, lg.prioridad;

-- Géneros principales de cada libro
SELECT 
    l.titulo,
    l.autor,
    g.nombre_genero as genero_principal
FROM libro l
JOIN libro_genero lg ON l.id_libro = lg.id_libro
JOIN genero g ON lg.id_genero = g.id_genero
WHERE lg.prioridad = 1;