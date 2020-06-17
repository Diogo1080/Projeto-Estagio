<?php
	require 'ligacao.php';
	if (empty($_POST['id_equipa'])) {
		echo "";
	}else{	
		$data_final=strtotime($_POST['data_final']);
		$atletas=$con->prepare("SELECT DISTINCT contribuintes.*,atletas.id_atleta,equipas.cor FROM equipas INNER JOIN atletas_equipas ON equipas.id_equipa=atletas_equipas.id_equipa INNER JOIN atletas ON atletas_equipas.id_atleta=atletas.id_atleta INNER JOIN contribuintes ON atletas.id_contribuinte=contribuintes.id_contribuinte WHERE equipas.id_equipa=?");
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
					if($data_final>date("Y/m/d, H:i:s")){
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
							if($data_final>date("Y/m/d, H:i:s")){
								echo '
									<td><input type="checkbox" name="presenca['.$linha['id_atleta'].']"></td>
									<td><input type="checkbox" name="justificacao['.$linha['id_atleta'].']"></td>
								';
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