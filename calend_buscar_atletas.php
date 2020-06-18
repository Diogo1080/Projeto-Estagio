<?php
	require 'ligacao.php';
	if (empty($_POST['id_equipa'])) {
		echo "";
	}else{	
		$atletas=$con->prepare("SELECT * FROM atletas_equipas INNER JOIN atletas ON atletas_equipas.id_atleta=atletas.id_atleta INNER JOIN contribuintes ON atletas.id_contribuinte=contribuintes.id_contribuinte WHERE id_equipa=?");
		$atletas->bind_param("i",$_POST['id_equipa']);
		$atletas->execute();
		$atletas=$atletas->get_result();
		while ($linha=$atletas->fetch_assoc()) {
			echo '
				<table border>
					<thead>
						<tr>
							<th>Foto</th>
							<th>Nome do atleta</th>
							<th>Presença</th>
							<th>Justificação</th>
						<tr>
					</thead>
					<tbody>
						<tr>
							<td><img height="100" width="100" src="data:image/jpeg;base64,'.base64_encode($linha["foto"]).'" alt=""></td>
							<td>'.$linha['nome'].'</td>
							<td><input type="checkbox" name="presenca[]"></td>
							<td><input type="checkbox" name="justificacao[]"></td>
						</tr>
					</tbody>
				</table>
			';
		}
	}