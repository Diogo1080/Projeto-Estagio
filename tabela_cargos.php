<?php 
	include 'ligacao.php';
	//Inicia as variaveis com os valores.
		$num_pagina = $_POST['num_pagina'];
		$procura = "%{$_POST['procura']}%";
		$registos_por_pagina = 20;
		$offset = ($num_pagina-1) * $registos_por_pagina;

	//Busca o total de registos que existem com os valores dados
		$total_registos=$con->prepare("SELECT * FROM `cargos` WHERE cargo like ?");
		$total_registos->bind_param("s",$procura);
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
		$cargos=$con->prepare("SELECT * FROM `cargos` WHERE cargo like ? LIMIT $offset,$registos_por_pagina");
		$cargos->bind_param("s",$procura);
		$cargos->execute();
		$resultado=$cargos->get_result();
		echo '
			<div>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Cargo</th>
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
								<td>'.$linha['cargo'].'</td>
								<td><button class="btn btn-default" onclick="window.location.href=\'cargos.php?id_cargo='.$linha['id_cargo'].'\'">Selecionar</button></td>
							</tr>
							';
						}
					}
		echo '		</tbody>
				</table>
			</div>
		';
?>