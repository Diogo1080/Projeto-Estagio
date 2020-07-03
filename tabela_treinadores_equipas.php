<?php 
	include 'ligacao.php';
	//Inicia as variaveis com os valores.
		$num_pagina = $_POST['num_pagina'];
		$procura = "%{$_POST['procura']}%";
		$registos_por_pagina = 20;
		$offset = ($num_pagina-1) * $registos_por_pagina;
	//Busca o total de registos que existem com os valores dados
		$total_registos=$con->prepare("SELECT count(recursos_humanos.id_recurso_humano) as total FROM `recursos_humanos` 
			INNER JOIN cargos_recursos ON recursos_humanos.id_recurso_humano=cargos_recursos.id_recurso_humano 
			INNER JOIN cargos ON cargos_recursos.id_cargo=cargos.id_cargo 
			WHERE (nome like ? OR cc like ? OR nif like ?) AND is_treinador=1 ");
		$total_registos->bind_param("sss",$procura,$procura,$procura);
		$total_registos->execute();

		$t_registos=$total_registos->get_result();
		$linha=$t_registos->fetch_assoc();
		
		if ($linha['total']==0) {
			$total=0;
		}else{
			$total=$linha['total'];
		}
		$total_num_paginas = ceil($total / $registos_por_pagina);
		echo $total_num_paginas."«";

		$total_registos->close();
	//Busca consuante a variavel $registos_por_pagina o conteudo dos registos.
		$cargos=$con->prepare("SELECT recursos_humanos.* FROM `recursos_humanos` 
			INNER JOIN cargos_recursos ON recursos_humanos.id_recurso_humano=cargos_recursos.id_recurso_humano
			INNER JOIN cargos ON cargos_recursos.id_cargo=cargos.id_cargo
			WHERE (nome like ? OR cc like ? OR nif like ?) AND is_treinador=1
			LIMIT $offset,$registos_por_pagina");
		$cargos->bind_param("sss",$procura,$procura,$procura);
		$cargos->execute();
		$resultado=$cargos->get_result();
		echo '
			<table class="table table-hover table-bordered">
				<thead>
					<tr>
						<th scope="col">Nome</th>
						<th scope="col">Selecionar</th>
					</tr>
				</thead>
				<tbody>
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
							<td><input name="treinador" value="'.$linha['id_recurso_humano'].'" type="radio"></td>
						</tr>
						';
					}
				}
				echo '
				</tbody>
			</table>
				';
                
?>