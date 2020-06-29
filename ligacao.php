<?php 
	session_start(); 
	$con = mysqli_connect("10.6.0.3", "root", "" , "estrela_azul");
	if (!isset($_SESSION['permissao'])) {
		header('Location: index.php');
	}
?>