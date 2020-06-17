<?php
	include_once './ligacao.php';

	$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

	//Converter a data e hora para o formato da Base de Dados
	$data_start = str_replace('/', '-', $dados['start']);
	$data_start_conv = date("Y-m-d H:i:s", strtotime($data_start));

	$data_end = str_replace('/', '-', $dados['end']);
	$data_end_conv = date("Y-m-d H:i:s", strtotime($data_end));

	$insert_event = $con->prepare("INSERT INTO treinos (titulo, cor, dt_inicio, dt_fim) VALUES (?,?,?,?)");
	$insert_event->bind_Param('ssss', $dados['title'],$dados['color'],$data_start_conv,$data_end_conv);
	$insert_equipa_treinos=$con->prepare("INSERT INTO `equipa_treinos`(`id_equipa`, `id_atleta`, `id_treino`, `presente`, `justificacao`) VALUES (?,?,?,?,?)");
	for ($i=0; $i < count($dados['id_atleta']); $i++) { 
		
	}
	while ($dados['id_atleta']) {
		# code...
	}
	$insert_equipa_treinos->bind_Param("iiiii",,,,);
	if ($insert_event->execute()) {
	    $retorna = '<div class="alert alert-success" role="alert">Treino inserido com sucesso!</div>';
	} else {
	    $retorna = '<div class="alert alert-danger" role="alert">Erro: Treino não foi inserido com sucesso!</div>';
	}

	echo ($retorna);