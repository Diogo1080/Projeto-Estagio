<?php
	include_once './ligacao.php';

	$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

	//Converter a data e hora para o formato da Base de Dados
	$data_start = str_replace('/', '-', $dados['dt_inicio']);
	$data_start_conv = date("Y-m-d H:i:s", strtotime($data_start));

	$data_end = str_replace('/', '-', $dados['dt_fim']);
	$data_end_conv = date("Y-m-d H:i:s", strtotime($data_end));

	$update_event = $con->prepare("UPDATE treinos SET titulo=?, cor=?, dt_inicio=?, dt_fim=? WHERE id_treino=?");
	$update_event->bind_Param('ssssi', $dados['titulo'],$dados['cor'],$data_start_conv,$data_end_conv,$dados['id']);

	if ($update_event->execute()) {
	    $retorna = '<div class="alert alert-success" role="alert">Treino editado com sucesso!</div>';
	} else {
	    $retorna = '<div class="alert alert-danger" role="alert">Erro: Treino não foi editado com sucesso!</div>';
	}

	echo ($retorna);
?>