<?php 
	include 'ligacao.php';
	//Inicia as variaveis com os valores.
		$num_pagina = $_POST['num_pagina'];
		$procura = "%{$_POST['procura']}%";
		$registos_por_pagina = 20;
		$offset = ($num_pagina-1) * $registos_por_pagina;
		$total=0;

	//Busca o total de registos que existem com os valores dados
		if ($_POST['equipa']=="T" || $_POST['equipa']=="C") {
			$total_registos=$con->prepare("SELECT count(contribuintes.id_contribuinte) as total FROM `contribuintes` 
				INNER JOIN atletas ON contribuintes.id_contribuinte=atletas.id_contribuinte 
				INNER JOIN atletas_equipas ON atletas.id_atleta=atletas_equipas.id_atleta
				INNER JOIN equipas ON atletas_equipas.id_equipa=equipas.id_equipa
				WHERE (contribuintes.nome like ? OR contribuintes.cc like ? OR contribuintes.nif like ?) ");
			$total_registos->bind_param("sss",$procura,$procura,$procura);
			$total_registos->execute();

			$t_registos=$total_registos->get_result();
			$linha=$t_registos->fetch_assoc();
			
			$total+=$linha['total'];
			$total_registos->close();
		}
		if ($_POST['equipa']=="T" || $_POST['equipa']=="S") {
			$total_registos=$con->prepare("SELECT count(contribuintes.id_contribuinte) as total FROM `contribuintes` 
			INNER JOIN atletas ON contribuintes.id_contribuinte=atletas.id_contribuinte
			WHERE NOT EXISTS(
			SELECT equipas.nome as nome_equipa,atletas.id_atleta,contribuintes.* FROM `contribuintes` 
				INNER JOIN atletas ON contribuintes.id_contribuinte=atletas.id_contribuinte
				INNER JOIN atletas_equipas ON atletas.id_atleta=atletas_equipas.id_atleta
				INNER JOIN equipas ON atletas_equipas.id_equipa=equipas.id_equipa 
			) AND (contribuintes.nome like ? OR contribuintes.cc like ? OR contribuintes.nif like ?) 
			");

			$total_registos->bind_param("sss",$procura,$procura,$procura);
			$total_registos->execute();

			$t_registos=$total_registos->get_result();
			$linha=$t_registos->fetch_assoc();

			$total+=$linha['total'];
			$total_registos->close();
		}
		$total_num_paginas = ceil($total / $registos_por_pagina);
		echo $total_num_paginas."«";


	//Busca consuante a variavel $registos_por_pagina o conteudo dos registos.
			
		//LIMITAR POR EQUIPA
			$atletas=$con->prepare("SELECT equipas.nome as nome_equipa,atletas.id_atleta,contribuintes.* FROM `contribuintes` 
				INNER JOIN atletas ON contribuintes.id_contribuinte=atletas.id_contribuinte
				INNER JOIN atletas_equipas ON atletas.id_atleta=atletas_equipas.id_atleta
				INNER JOIN equipas ON atletas_equipas.id_equipa=equipas.id_equipa 
				AND (contribuintes.nome like ? OR contribuintes.cc like ? OR contribuintes.nif like ?)
				AND (equipas.id_equipa=?)
				LIMIT $offset,$registos_por_pagina");
			$atletas->bind_param("sssi",$procura,$procura,$procura,$_POST['equipa']);
		if ($_POST['equipa']=="T") {
		//TODOS OS ATLETAS
			$atletas=$con->prepare("SELECT equipas.nome as nome_equipa,atletas.id_atleta,contribuintes.* FROM `contribuintes` 
				INNER JOIN atletas ON contribuintes.id_contribuinte=atletas.id_contribuinte
				INNER JOIN atletas_equipas ON atletas.id_atleta=atletas_equipas.id_atleta
				INNER JOIN equipas ON atletas_equipas.id_equipa=equipas.id_equipa 
				WHERE (contribuintes.nome like ? OR contribuintes.cc like ? OR contribuintes.nif like ?)
				UNION ALL
				SELECT null as nome_equipa,atletas.id_atleta,contribuintes.* FROM `contribuintes` 
				INNER JOIN atletas ON contribuintes.id_contribuinte=atletas.id_contribuinte
				WHERE NOT EXISTS(
					SELECT equipas.nome as nome_equipa,atletas.id_atleta,contribuintes.* FROM `contribuintes` 
				    INNER JOIN atletas ON contribuintes.id_contribuinte=atletas.id_contribuinte
				    INNER JOIN atletas_equipas ON atletas.id_atleta=atletas_equipas.id_atleta
					INNER JOIN equipas ON atletas_equipas.id_equipa=equipas.id_equipa 
				) AND  (contribuintes.nome like ? OR contribuintes.cc like ? OR contribuintes.nif like ?)
				LIMIT $offset,$registos_por_pagina");
			$atletas->bind_param("ssssss",$procura,$procura,$procura,$procura,$procura,$procura);
		}elseif($_POST['equipa']=="C"){
		//TODOS OS ATLETAS QUE TÊM EQUIPA
			$atletas=$con->prepare("SELECT equipas.nome as nome_equipa,atletas.id_atleta,contribuintes.* FROM `contribuintes` 
				INNER JOIN atletas ON contribuintes.id_contribuinte=atletas.id_contribuinte
				INNER JOIN atletas_equipas ON atletas.id_atleta=atletas_equipas.id_atleta
				INNER JOIN equipas ON atletas_equipas.id_equipa=equipas.id_equipa 
				WHERE (contribuintes.nome like ? OR contribuintes.cc like ? OR contribuintes.nif like ?)
				LIMIT $offset,$registos_por_pagina");
			$atletas->bind_param("sss",$procura,$procura,$procura);
		}elseif($_POST['equipa']=="S"){
		//TODOS OS ATLETAS QUE NÃO TÊM EQUIPA
			$atletas=$con->prepare("SELECT null as nome_equipa,atletas.id_atleta,contribuintes.* FROM `contribuintes` 
				INNER JOIN atletas ON contribuintes.id_contribuinte=atletas.id_contribuinte
				WHERE NOT EXISTS(
					SELECT equipas.nome as nome_equipa,atletas.id_atleta,contribuintes.* FROM `contribuintes` 
				    INNER JOIN atletas ON contribuintes.id_contribuinte=atletas.id_contribuinte
				    INNER JOIN atletas_equipas ON atletas.id_atleta=atletas_equipas.id_atleta
					INNER JOIN equipas ON atletas_equipas.id_equipa=equipas.id_equipa 
				) AND  (contribuintes.nome like ? OR contribuintes.cc like ? OR contribuintes.nif like ?)
				LIMIT $offset,$registos_por_pagina");
			$atletas->bind_param("sss",$procura,$procura,$procura);
		}
		

		$atletas->execute();
		$resultado=$atletas->get_result();
		$atletas->close();

		echo '
			<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th scope="col">Nome</th>
							<th scope="col">Equipa atual</th>
							<th scope="col">Selecionar</th>
						</tr>
					</thead>
                ';
                if ($resultado->num_rows==0) {
						echo '
							<tr>
								<td colspan="100%">Nenhum registo encontrado.</td>
							</tr>
						';
					}else{
						while ($linha=$resultado->fetch_assoc()) {
							echo '
							<tr>
								<td>'.$linha['nome'].'</td>
								<td>'.$linha['nome_equipa'].'</td>
								<td>';
									if (isset($_SESSION['array_atletas'])) {
										if (in_array($linha['id_atleta'], $_SESSION['array_atletas'])) {
											echo '<input checked type="checkbox" onclick="selecionar_atleta(\'0\',\''.$linha['id_atleta'].'\',\''.$linha['nome'].'\');">';
										}else{
											echo '<input type="checkbox" onclick="selecionar_atleta(\'1\',\''.$linha['id_atleta'].'\',\''.$linha['nome'].'\');">';
										}
									}else{
										echo '<input type="checkbox" onclick="selecionar_atleta(\'1\',\''.$linha['id_atleta'].'\',\''.$linha['nome'].'\');">';
									}
								echo '</td>
							</tr>
							';
						}
					}
				echo '
				</tbody>
			</table>
				';
                
?>