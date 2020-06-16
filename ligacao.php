<?php 
	session_start(); 
define('HOST', 'localhost');
define('USER', 'root');
define('PASS', '');
define('DBNAME', 'estrela_azul');

$conn = new PDO('mysql:host=' . HOST . '; dbname=' . DBNAME . ';', USER, PASS);
$con = mysqli_connect("localhost", "root", "" , "estrela_azul");
?>