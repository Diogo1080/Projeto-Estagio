<?php 
	include('ligacao.php');
	$query=$con->prepare("SELECT * FROM `contribuintes` WHERE `id_contribuinte`=? ");
	$query->bind_param("s",$_POST['id_contribuinte']);
	$query->execute();
	$resultado=$query->get_result();
	$linha=$resultado->fetch_assoc();
	echo base64_encode($linha['foto']);
?>