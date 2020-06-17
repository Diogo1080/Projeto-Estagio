<?php 
	require("ligacao.php");
	$querry = $con->prepare("SELECT * FROM `ficheiros` WHERE id_ficheiro = '$_GET[id_ficheiro]'");
	$querry->execute();
	$resultado=$querry->get_result();
	$linha=$resultado->fetch_assoc();

	header("Content-length: $linha[filesize]");
	header("Content-type: $linha[extencao]");
	header("Content-Disposition: attachment; filename=$linha[nome]");
	ob_clean();
	flush();
	echo $linha['ficheiro'];
	exit;
?>