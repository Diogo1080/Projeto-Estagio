<?php
	include_once './ligacao.php';
	$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
	if (isset($dados['treino_id'])) {
		//Converter a data e hora para o formato da Base de Dados
		$data_start = str_replace('/', '-', $dados['start']);
		$data_start_conv = date("Y-m-d H:i:s", strtotime($data_start));

		$data_end = str_replace('/', '-', $dados['end']);
		$data_end_conv = date("Y-m-d H:i:s", strtotime($data_end));

		$insert_event = $con->prepare("INSERT INTO treinos (titulo, is_cancelado, dt_inicio, dt_fim) VALUES (?,0,?,?)");
		$insert_equipa_treino = $con->prepare("INSERT INTO `equipa_treinos`(`id_equipa`, `id_atleta`, `id_treino`, `presente`, `justificacao`) VALUES (?,?,?,?,?)");

		$retorna = '<div class="alert alert-danger" role="alert">Erro: Treino não foi inserido com sucesso!</div>';

		if (
			$insert_event->bind_Param('sss', $dados['title'],$data_start_conv,$data_end_conv)&&
			$insert_event->execute()
		) {
			$id_treino=$insert_event->insert_id;
			for ($i=0; $i < count($dados['id_atleta']); $i++) {
				$presente=0;
				$justificacao=0;
				if (isset($dados['presenca'][$dados['id_atleta'][$i]])) {
					$presente=1;
				}
				if (isset($dados['justificacao'][$dados['id_atleta'][$i]])) {
					$justificacao=1;
				}
				if (!(
					$insert_equipa_treino->bind_Param("iiiii",$dados['equipa'],$dados['id_atleta'][$i],$id_treino,$presente,$justificacao) &&
					$insert_equipa_treino->execute()
				)) {
					$erro=1;						
				}
			}
			if (!isset($erro)) {
				$retorna = '<div class="alert alert-success" role="alert">Treino inserido com sucesso!</div>';
			}
		}
	}else{
		//Converter a data e hora para o formato da Base de Dados
		$data_start = str_replace('/', '-', $dados['start']);
		$data_start_conv = date("Y-m-d H:i:s", strtotime($data_start));

		$data_end = str_replace('/', '-', $dados['end']);
		$data_end_conv = date("Y-m-d H:i:s", strtotime($data_end));

		$insert_event = $con->prepare("INSERT INTO jogos (titulo, dt_inicio, dt_fim) VALUES (?,?,?)");
		$insert_equipa_treino = $con->prepare("INSERT INTO `equipa_convocados`(`id_equipa`, `id_atleta`, `id_jogo`, `presente`, `justificacao`) VALUES (?,?,?,?,?)");

		$retorna = '<div class="alert alert-danger" role="alert">Erro: Treino não foi inserido com sucesso!</div>';

		if (
			$insert_event->bind_Param('sss', $dados['title'],$data_start_conv,$data_end_conv)&&
			$insert_event->execute()
		) {
			$id_treino=$insert_event->insert_id;
			for ($i=0; $i < count($dados['id_atleta']); $i++) {
				$presente=0;
				$justificacao=0;
				if (isset($dados['presenca'][$dados['id_atleta'][$i]])) {
					$presente=1;
				}
				if (isset($dados['justificacao'][$dados['id_atleta'][$i]])) {
					$justificacao=1;
				}
				if (!(
					$insert_equipa_treino->bind_Param("iiiii",$dados['equipa'],$dados['id_atleta'][$i],$id_treino,$presente,$justificacao) &&
					$insert_equipa_treino->execute()
				)) {
					$erro=1;						
				}
			}
			if (!isset($erro)) {
				$retorna = '<div class="alert alert-success" role="alert">Treino inserido com sucesso!</div>';
			}
		}
	}
	echo ($retorna);
?>