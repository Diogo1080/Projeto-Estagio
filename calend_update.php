<?php
	include_once './ligacao.php';

	$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

	//Converter a data e hora para o formato da Base de Dados
	$data_start = str_replace('/', '-', $dados['start']);
	$data_start_conv = date("Y-m-d H:i:s", strtotime($data_start));

	$data_end = str_replace('/', '-', $dados['end']);
	$data_end_conv = date("Y-m-d H:i:s", strtotime($data_end));

	$update_event = $con->prepare("UPDATE `treinos` SET `titulo`=?,`dt_inicio`=?,`dt_fim`=? WHERE `id_treino`=?");
	$update_equipa_treino = $con->prepare("UPDATE `equipa_treinos` SET `presente`=?,`justificacao`=? WHERE `id_treino`=? AND `id_atleta`=? AND `id_equipa`=?");

	$retorna = '<div class="alert alert-danger" role="alert">Erro: Treino não foi atualizado!</div>';

	if (
		$update_event->bind_Param('sssi',$dados['title'],$data_start_conv,$data_end_conv,$dados['treino_id'])&&
		$update_event->execute()
	) {
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
				$update_equipa_treino->bind_Param("iiiii",$presente,$justificacao,$dados['treino_id'],$dados['id_atleta'][$i],$dados['equipa']) &&
				$update_equipa_treino->execute()
			)) {
				$erro=1;						
			}

		}
		if (!isset($erro)) {
			$retorna = '<div class="alert alert-success" role="alert">Treino atualizado com sucesso!</div>';
		}
	}
	echo ($retorna);
?>