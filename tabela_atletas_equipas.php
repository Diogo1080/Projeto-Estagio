<?php 
	include 'ligacao.php';
	//Inicia as variaveis com os valores.
		$num_pagina = $_POST['num_pagina'];
		$procura = "%{$_POST['procura']}%";
		$registos_por_pagina = 20;
		$offset = ($num_pagina-1) * $registos_por_pagina;
		$total=0;

	//Busca o total de registos que existem com os valores dados
		if ($_POST['equipa']=="T" || $_POST['equipa']=="C" || $_POST['equipa']=="S") {
			$atletas=$con->prepare("
				SELECT atletas.id_atleta,contribuintes.nome FROM contribuintes 
				INNER JOIN atletas ON contribuintes.id_contribuinte=atletas.id_contribuinte 
				INNER JOIN atletas_escaloes ON atletas.id_atleta=atletas_escaloes.id_atleta
			");
		}else{
			$atletas=$con->prepare("
				SELECT atletas.id_atleta,contribuintes.nome,atletas_escaloes.id_escalao FROM contribuintes 
				INNER JOIN atletas ON contribuintes.id_contribuinte=atletas.id_contribuinte 
				INNER JOIN atletas_escaloes ON atletas.id_atleta=atletas_escaloes.id_atleta
				WHERE atletas_escaloes.id_escalao=?
			");
			$atletas->bind_param('i',$_POST['equipa']);
		}

		$equipa_atleta=$con->prepare("
			SELECT atletas_equipas.*,equipas.nome FROM atletas_equipas INNER JOIN equipas ON atletas_equipas.id_equipa=equipas.id_equipa WHERE atletas_equipas.id_atleta=? ORDER BY atletas_equipas.data_atribuicao DESC limit 1 
		");

		$atletas->execute();
		$resultado=$atletas->get_result();
		while ($linha=$resultado->fetch_assoc()) {

			$equipa_atleta->bind_param('i',$linha['id_atleta']);
			$equipa_atleta->execute();

			$resultado_equipa=$equipa_atleta->get_result();
			$linha_equipa=$resultado_equipa->fetch_assoc();
			
			if ($_POST['equipa']=="T"){
				$total+=1;
			}elseif ($_POST['equipa']=="S") {
				if ($equipa_atleta->affected_rows==0 || $linha_equipa['atual']==0) {
					$total+=1;
				}
			}elseif ($_POST['equipa']=="C"){
				if ($linha_equipa['atual']<>0) {
					$total+=1;
				}
			}else{
				if ($linha_equipa['atual']<>0) {
					$total+=1;
				}
			}
		}

		$total_num_paginas = ceil($total / $registos_por_pagina);
		echo $total_num_paginas."«";

	//Busca consuante a variavel $registos_por_pagina o conteudo dos registos.
		if ($_POST['equipa']=="T" || $_POST['equipa']=="C" || $_POST['equipa']=="S") {
			$atletas=$con->prepare("
				SELECT atletas.id_atleta,contribuintes.nome FROM contribuintes 
				INNER JOIN atletas ON contribuintes.id_contribuinte=atletas.id_contribuinte 
				INNER JOIN atletas_escaloes ON atletas.id_atleta=atletas_escaloes.id_atleta
				LIMIT $offset,$registos_por_pagina
			");
		}else{
			$atletas=$con->prepare("
				SELECT atletas.id_atleta,contribuintes.nome,atletas_escaloes.id_escalao FROM contribuintes 
				INNER JOIN atletas ON contribuintes.id_contribuinte=atletas.id_contribuinte 
				INNER JOIN atletas_escaloes ON atletas.id_atleta=atletas_escaloes.id_atleta
				WHERE atletas_escaloes.id_escalao=?
				LIMIT $offset,$registos_por_pagina
			");
			$atletas->bind_param('i',$_POST['equipa']);
		}

		$atletas->execute();
		$resultado=$atletas->get_result();

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
				if ($total==0) {
					echo '<td colspan="100%">Nenhum registo encontrado.</td>';
					exit;
				}else{
					while ($linha=$resultado->fetch_assoc()) {

						$equipa_atleta->bind_param('i',$linha['id_atleta']);
						$equipa_atleta->execute();

						$resultado_equipa=$equipa_atleta->get_result();
						$linha_equipa=$resultado_equipa->fetch_assoc();
						echo '<tr>';
							if ($_POST['equipa']=="T"){
								echo '<td>'.$linha['nome'].'</td>';
								if ($linha_equipa['atual']<>0) {
									echo '<td>'.$linha_equipa['nome'].'</td>';
								}else{
									echo '<td>Não têm equipa</td>';
								}
								echo '<td>';
									if (isset($_SESSION['array_atletas'])) {
										if (in_array($linha['id_atleta'], $_SESSION['array_atletas'])) {
											echo '<input checked type="checkbox" onclick="selecionar_atleta(\'0\',\''.$linha['id_atleta'].'\',\''.$linha['nome'].'\');">';
										}else{
											echo '<input type="checkbox" onclick="selecionar_atleta(\'1\',\''.$linha['id_atleta'].'\',\''.$linha['nome'].'\');">';
										}
									}else{
										echo '<input type="checkbox" onclick="selecionar_atleta(\'1\',\''.$linha['id_atleta'].'\',\''.$linha['nome'].'\');">';
									}
								echo '</td>';
							}elseif ($_POST['equipa']=="S") {
								if ($equipa_atleta->affected_rows==0 || $linha_equipa['atual']==0) {
									echo '<td>'.$linha['nome'].'</td>';
									if ($linha_equipa['atual']<>0) {
										echo '<td>'.$linha_equipa['nome'].'</td>';
									}else{
										echo '<td>Não têm equipa</td>';
									}
									echo '<td>';
										if (isset($_SESSION['array_atletas'])) {
											if (in_array($linha['id_atleta'], $_SESSION['array_atletas'])) {
												echo '<input checked type="checkbox" onclick="selecionar_atleta(\'0\',\''.$linha['id_atleta'].'\',\''.$linha['nome'].'\');">';
											}else{
												echo '<input type="checkbox" onclick="selecionar_atleta(\'1\',\''.$linha['id_atleta'].'\',\''.$linha['nome'].'\');">';
											}
										}else{
											echo '<input type="checkbox" onclick="selecionar_atleta(\'1\',\''.$linha['id_atleta'].'\',\''.$linha['nome'].'\');">';
										}
									echo '</td>';
								}
							}elseif ($_POST['equipa']=="C"){
								if ($linha_equipa['atual']<>0) {
									echo '<td>'.$linha['nome'].'</td>';
									echo '<td>'.$linha_equipa['nome'].'</td>';
									echo '<td>';
										if (isset($_SESSION['array_atletas'])) {
											if (in_array($linha['id_atleta'], $_SESSION['array_atletas'])) {
												echo '<input checked type="checkbox" onclick="selecionar_atleta(\'0\',\''.$linha['id_atleta'].'\',\''.$linha['nome'].'\');">';
											}else{
												echo '<input type="checkbox" onclick="selecionar_atleta(\'1\',\''.$linha['id_atleta'].'\',\''.$linha['nome'].'\');">';
											}
										}else{
											echo '<input type="checkbox" onclick="selecionar_atleta(\'1\',\''.$linha['id_atleta'].'\',\''.$linha['nome'].'\');">';
										}
									echo '</td>';
								}
							}else{
								if ($linha_equipa['atual']<>0) {
									echo '<td>'.$linha['nome'].'</td>';
									echo '<td>'.$linha_equipa['nome'].'</td>';
									echo '<td>';
										if (isset($_SESSION['array_atletas'])) {
											if (in_array($linha['id_atleta'], $_SESSION['array_atletas'])) {
												echo '<input checked type="checkbox" onclick="selecionar_atleta(\'0\',\''.$linha['id_atleta'].'\',\''.$linha['nome'].'\');">';
											}else{
												echo '<input type="checkbox" onclick="selecionar_atleta(\'1\',\''.$linha['id_atleta'].'\',\''.$linha['nome'].'\');">';
											}
										}else{
											echo '<input type="checkbox" onclick="selecionar_atleta(\'1\',\''.$linha['id_atleta'].'\',\''.$linha['nome'].'\');">';
										}
									echo '</td>';
								}
							}
						echo '</tr>';
					}
				}              
?>