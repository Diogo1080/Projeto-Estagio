<?php 
	require 'ligacao.php';

	if ($_SESSION['permissao']<>1) {
		header("location : dashboard.php");
	}
	if ($_GET['acao']==0) {
		$buscar_escalao=$con->prepare("SELECT id_escalao FROM equipas WHERE  `id_equipa`=? ");
		$buscar_escalao->bind_param("i",$_GET['id_equipa']);
		$buscar_escalao->execute();
		$buscar_escalao=$buscar_escalao->get_result();
		$buscar_escalao=$buscar_escalao->fetch_assoc();

		$desativar_equipa=$con->prepare("UPDATE `equipas` SET `estado`=0 WHERE `id_equipa`=? ");
		$desativar_equipa->bind_param("i",$_GET['id_equipa']);
		$desativar_equipa->execute();

		$equipa_atletas=$con->prepare("UPDATE `atletas_equipas` SET `atual`=0 WHERE `id_equipa`=?");
		$equipa_atletas->bind_param("i",$_GET['id_equipa']);
		$equipa_atletas->execute();

		$escalao_equipa=$con->prepare("UPDATE `atletas_escaloes` SET `atual`=0 WHERE `id_escalao`=?");
		$escalao_equipa->bind_param("i",$buscar_escalao['id_escalao']);
		$escalao_equipa->execute();

		$treinadores_equipa=$con->prepare("UPDATE `treinadores_equipas` SET `atual`=0 WHERE `id_equipa`=?");
		$treinadores_equipa->bind_param("i",$_GET['id_equipa']);
		$treinadores_equipa->execute();

		header("location: listar_equipas.php");
	}else{
		$ativar_equipa=$con->prepare("UPDATE `equipas` SET `estado`=1 WHERE `id_equipa`=? ");
		$ativar_equipa->bind_param("i",$_GET['id_equipa']);
		$ativar_equipa->execute();
		header("location: equipa.php?id_equipa=".$_GET['id_equipa']);
	}
?>