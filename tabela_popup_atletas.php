<?php 
	include 'ligacao.php';
	//Inicia as variaveis com os valores.
		$num_pagina = $_POST['num_pagina'];
		$procura = "%{$_POST['procura']}%";
		$registos_por_pagina = 20;
		$offset = ($num_pagina-1) * $registos_por_pagina;

	//Busca o total de registos que existem com os valores dados
		$total_registos=$con->prepare("SELECT * FROM `contribuintes` INNER JOIN atletas ON contribuintes.id_contribuinte=atletas.id_atleta WHERE `nome` like ? OR cc like ? OR nif like ? AND tipo_contribuinte='Atleta' AND `id_enc_edu`=null ");
		$total_registos->bind_param("sss",$procura,$procura,$procura);
		$total_registos->execute();

		$t_registos=$total_registos->num_rows();
		if ($t_registos=='') {
			$total=0;
		}else{
			$total=$t_registos;
		}
		$total_num_paginas = ceil($total / $registos_por_pagina);
		echo $total_num_paginas."«";

		$total_registos->close();
	//Busca consuante a variavel $registos_por_pagina o conteudo dos registos.
		$atletas=$con->prepare("SELECT * FROM `contribuintes` INNER JOIN atletas ON contribuintes.id_contribuinte=atletas.id_atleta WHERE `nome` like ? OR `cc` like ? OR `nif` like ? AND tipo_contribuinte='Atleta' AND `id_enc_edu`=null ");
		$atletas->bind_param("sss",$procura,$procura,$procura);
		$atletas->execute();
		$resultado=$atletas->get_result();
		echo '
			<div class="table-responsive">
				<table border class="table table-striped table-sm">
					<thead>
						<tr>
							<th>Nome</th>
							<th>CC</th>
							<th>NIF</th>
							<th>Selecionar</th>
						</tr>
					</thead>
					<tbody>';
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
								<td>'.$linha['cc'].'</td>
								<td>'.$linha['nif'].'</td>
								<td>
									<button onclick="esconder_modal();selecionar_atleta(\''.$linha['id_contribuinte'].'\',\''.$linha['nome'].'\',\''.$linha['cc'].'\');">Selecionar</button>
								</td>
							</tr>
							';
						}
					}
		echo '		</tbody>
				</table>
			</div>
		';

?>