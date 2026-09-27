<?php
$servidor = "localhost";
$usuario = "root";
$contrasena = "";
$nombreBaseDatos = "alumnado";

$conexion = mysqli_connect($servidor, $usuario, $contrasena, "", 3306) or
	die("Problemas de conexión con la base de datos.");

mysqli_set_charset($conexion, "utf8mb4");

$sqlCrearBaseDatos = "CREATE DATABASE IF NOT EXISTS alumnado
	CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci";
mysqli_query($conexion, $sqlCrearBaseDatos) or
	die("No se ha podido crear la base de datos.");

mysqli_select_db($conexion, $nombreBaseDatos) or
	die("No se ha podido seleccionar la base de datos.");

$sqlCrearTabla = "CREATE TABLE IF NOT EXISTS alumnos (
	id INT AUTO_INCREMENT PRIMARY KEY,
	nombre VARCHAR(50) NOT NULL,
	apellidos VARCHAR(100) NOT NULL,
	fecha_nacimiento DATE NOT NULL,
	curso VARCHAR(5) NOT NULL,
	email VARCHAR(100) NOT NULL,
	contrasena VARCHAR(255) NOT NULL
)";
mysqli_query($conexion, $sqlCrearTabla) or
	die("No se ha podido crear la tabla de alumnado.");
?>