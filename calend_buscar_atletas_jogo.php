<?php
	require 'ligacao.php';
	if (!empty($_POST['id_equipa'])) {
		$data_final=explode("(", $_POST['data_final']);
		$data_final=strtotime($data_final[0]);
		$hoje=strtotime("now");

		$atletas=$con->prepare("SELECT DISTINCT contribuintes.nome,contribuintes.foto,atletas.id_atleta,equipas.cor FROM equipas INNER JOIN atletas_equipas ON equipas.id_equipa=atletas_equipas.id_equipa INNER JOIN atletas ON atletas_equipas.id_atleta=atletas.id_atleta INNER JOIN contribuintes ON atletas.id_contribuinte=contribuintes.id_contribuinte WHERE equipas.id_equipa=? and atletas_equipas.atual=1");
		$atletas->bind_param("i",$_POST['id_equipa']);
		$atletas->execute();
		$atletas=$atletas->get_result();
		echo '
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Foto</th>
					<th>Nome do atleta</th>
					';
					if($data_final<$hoje){
						echo '
						<th>Presença</th>
						<th>Justificação</th>
						';
					}
					echo'
				<tr>
			</thead>
			<tbody>';
				while ($linha=$atletas->fetch_assoc()) {
					$cor=$linha['cor'];
					echo'
						<tr>
							<input hidden name="id_atleta[]" value="'.$linha['id_atleta'].'">
							<td><img height="100" width="100" src="data:image/jpeg;base64,'.base64_encode($linha["foto"]).'" alt=""></td>
							<td>'.$linha['nome'].'</td>
							';
							if($data_final<$hoje){
								if(!empty($_POST['id_treino'])){
									$presenca=$con->prepare("SELECT presente,justificacao FROM equipa_convocados WHERE id_jogo=? AND id_atleta=?");
									$presenca->bind_param("ii",$_POST['id_jogo'],$linha['id_atleta']);
									$presenca->execute();
									$presenca=$presenca->get_result();
									$linha_presenca=$presenca->fetch_assoc();
									if ($linha_presenca['presente']==1) {
										echo '<td><input checked type="checkbox" name="presenca['.$linha['id_atleta'].']"></td>';
									}else{
										echo '<td><input type="checkbox" name="presenca['.$linha['id_atleta'].']"></td>';
									}
									if ($linha_presenca['justificacao']==1) {
										echo '<td><input checked type="checkbox" name="justificacao['.$linha['id_atleta'].']"></td>';
									}else{
										echo '<td><input type="checkbox" name="justificacao['.$linha['id_atleta'].']"></td>';
									}
								}else{
									echo '
										<td><input type="checkbox" name="presenca['.$linha['id_atleta'].']"></td>
										<td><input type="checkbox" name="justificacao['.$linha['id_atleta'].']"></td>
									';
								}
							}
							echo '
						</tr>
					';
				}
				echo '			
			</tbody>
		</table>';
	}
	if (isset($cor)) {
		echo '«'.$cor;
	}
?>