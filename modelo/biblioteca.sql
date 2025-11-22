-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2025 at 04:37 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `biblioteca`
--

-- --------------------------------------------------------

--
-- Table structure for table `genero`
--

CREATE TABLE `genero` (
  `id_genero` int(11) NOT NULL,
  `nombre_genero` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genero`
--

INSERT INTO `genero` (`id_genero`, `nombre_genero`, `descripcion`) VALUES
(1, 'Ciencia Ficción', 'Libros sobre futuros imaginarios, tecnología avanzada y espacio'),
(2, 'Fantasía', 'Obras con elementos mágicos y mundos imaginarios'),
(3, 'Romance', 'Historias centradas en relaciones amorosas'),
(4, 'Misterio', 'Narrativas con enigmas por resolver'),
(5, 'Terror', 'Obras diseñadas para causar miedo o suspense'),
(6, 'Biografía', 'Relatos de la vida de personas reales');

-- --------------------------------------------------------

--
-- Table structure for table `libro`
--

CREATE TABLE `libro` (
  `id_libro` int(11) NOT NULL,
  `autor` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `titulo` varchar(200) NOT NULL,
  `ruta_imagen` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `libro`
--

INSERT INTO `libro` (`id_libro`, `autor`, `descripcion`, `titulo`, `ruta_imagen`) VALUES
(1, 'Isaac Asimov', 'Saga sobre la caída de un imperio galáctico', 'Fundación', ''),
(2, 'J.R.R. Tolkien', 'Aventura de Bilbo Bolsón en la Tierra Media', 'El Hobbit', ''),
(3, 'Jane Austen', 'Historia de amor entre Elizabeth Bennet y Mr. Darcy', 'Orgullo y Prejuicio', ''),
(4, 'Frank Herbert', 'En el desértico planeta Arrakis, una familia lucha por controlar la especia melange, la sustancia más valiosa del universo.', 'Duna', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1555447414i/44767458.jpg'),
(5, 'George Orwell', 'En un futuro distópico, Winston Smith desafía el sistema totalitario del Gran Hermano.', '1984', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1657781256i/61439040.jpg'),
(6, 'Gabriel García Márquez', 'La saga de la familia Buendía en el mágico pueblo de Macondo.', 'Cien años de soledad', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1327881361i/320.png'),
(7, 'J.K. Rowling', 'Un niño descubre que es mago y comienza su educación en Hogwarts.', 'Harry Potter y la piedra filosofal', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1550337333i/15881.jpg'),
(8, 'Stephen King', 'Un payaso demoníaco aterroriza a los niños de un pequeño pueblo de Maine.', 'It', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1334416842i/830502.jpg'),
(9, 'Dan Brown', 'Un simbólogo descubre una conspiración que podría cambiar la historia del cristianismo.', 'El código Da Vinci', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1579621267i/968.jpg'),
(10, 'Stephenie Meyer', 'Una joven se enamora de un vampiro en el lluvioso pueblo de Forks.', 'Crepúsculo', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1361039443i/41865.jpg'),
(11, 'Walter Isaacson', 'La biografía autorizada del cofundador de Apple.', 'Steve Jobs', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1511288481i/11084145.jpg'),
(12, 'Ernest Cline', 'En un futuro distópico, jóvenes compiten en un reality virtual por una fortuna.', 'Ready Player One', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1500930947i/9969571.jpg'),
(13, 'George R.R. Martin', 'Familias nobles luchan por el control del Trono de Hierro en Poniente.', 'Juego de tronos', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1273763400i/13496.jpg'),
(14, 'Suzanne Collins', 'En un futuro postapocalíptico, jóvenes son obligados a competir en un reality show mortal.', 'Los juegos del hambre', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1586722975i/2767052.jpg'),
(15, 'Bram Stoker', 'La clásica historia del conde vampiro de Transilvania.', 'Drácula', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1387151694i/17245.jpg'),
(16, 'Gabriel García Márquez', 'Una historia de amor que perdura por más de cincuenta años.', 'El amor en los tiempos del cólera', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1327934453i/9712.jpg'),
(17, 'Arthur Conan Doyle', 'La primera aparición del famoso detective Sherlock Holmes.', 'Sherlock Holmes: Estudio en escarlata', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1733131275i/114814.jpg'),
(18, 'Stephen King', 'Una familia se muda a un hotel aislado donde fuerzas malignas los acechan.', 'El resplandor', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1641398308i/11588.jpg'),
(19, 'Herman Melville', 'La obsesiva persecución de una ballena blanca por el capitán Ahab.', 'Moby Dick', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1327940656i/153747.jpg'),
(20, 'J.R.R. Tolkien', 'Frodo Bolsón emprende un viaje para destruir el Anillo Único.', 'El señor de los anillos: La comunidad del anillo', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1654215925i/3263607.jpg'),
(21, 'Paula Hawkins', 'Una mujer involucrada en la investigación de una desaparición misteriosa.', 'La chica del tren', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1574805688i/22557272.jpg'),
(22, 'Walter Isaacson', 'Biografía completa del genio de la física Albert Einstein.', 'Einstein: Su vida y universo', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1348793833i/10884.jpg'),
(23, 'William Gibson', 'El hacker Case navega por el ciberespacio en un futuro distópico.', 'Neuromante', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1167348726i/22328.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `libro_genero`
--

CREATE TABLE `libro_genero` (
  `id_libro` int(11) NOT NULL,
  `id_genero` int(11) NOT NULL,
  `prioridad` tinyint(4) NOT NULL DEFAULT 1
) ;

--
-- Dumping data for table `libro_genero`
--

INSERT INTO `libro_genero` (`id_libro`, `id_genero`, `prioridad`) VALUES
(1, 1, 1),
(1, 5, 2),
(2, 2, 1),
(3, 3, 1),
(3, 4, 2),
(4, 1, 1),
(4, 4, 2),
(5, 1, 1),
(5, 5, 2),
(6, 2, 1),
(6, 3, 2),
(7, 2, 1),
(7, 4, 2),
(8, 4, 2),
(8, 5, 1),
(9, 4, 1),
(9, 5, 2),
(10, 2, 3),
(10, 3, 1),
(10, 5, 2),
(11, 6, 1),
(12, 1, 1),
(12, 4, 2),
(13, 2, 1),
(13, 4, 2),
(14, 1, 1),
(14, 4, 2),
(15, 3, 2),
(15, 5, 1),
(16, 3, 1),
(16, 6, 2),
(17, 4, 1),
(17, 6, 2),
(18, 4, 2),
(18, 5, 1),
(19, 2, 2),
(19, 6, 1),
(20, 2, 1),
(20, 4, 2),
(21, 3, 2),
(21, 4, 1),
(22, 6, 1),
(23, 1, 1),
(23, 4, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `genero`
--
ALTER TABLE `genero`
  ADD PRIMARY KEY (`id_genero`),
  ADD UNIQUE KEY `nombre_genero` (`nombre_genero`);

--
-- Indexes for table `libro`
--
ALTER TABLE `libro`
  ADD PRIMARY KEY (`id_libro`);

--
-- Indexes for table `libro_genero`
--
ALTER TABLE `libro_genero`
  ADD PRIMARY KEY (`id_libro`,`id_genero`),
  ADD KEY `id_genero` (`id_genero`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `genero`
--
ALTER TABLE `genero`
  MODIFY `id_genero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `libro`
--
ALTER TABLE `libro`
  MODIFY `id_libro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `libro_genero`
--
ALTER TABLE `libro_genero`
  ADD CONSTRAINT `libro_genero_ibfk_1` FOREIGN KEY (`id_libro`) REFERENCES `libro` (`id_libro`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `libro_genero_ibfk_2` FOREIGN KEY (`id_genero`) REFERENCES `genero` (`id_genero`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
