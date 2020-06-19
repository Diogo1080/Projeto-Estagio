<?php 
	include 'ligacao.php';
	//Inicia as variaveis com os valores.
		$num_pagina = $_POST['num_pagina'];
		$procura = "%{$_POST['procura']}%";
		$registos_por_pagina = 20;
		$offset = ($num_pagina-1) * $registos_por_pagina;
		if (empty($_POST['tipo'])) {
			$tipo="%%";
		}else{
			$tipo=$_POST['tipo'];
		}
	//Busca o total de registos que existem com os valores dados
		$total_registos=$con->prepare("SELECT count(id_contribuinte) as total FROM `contribuintes` WHERE (nome like ? OR cc like ? OR nif like ?) AND (tipo_contribuinte like ?) ");
		$total_registos->bind_param("ssss",$procura,$procura,$procura,$tipo);
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
		$cargos=$con->prepare("SELECT * FROM `contribuintes` WHERE (nome like ? OR cc like ? OR nif like ?) AND (tipo_contribuinte like ?) LIMIT $offset,$registos_por_pagina");
		$cargos->bind_param("ssss",$procura,$procura,$procura,$tipo);
		$cargos->execute();
		$resultado=$cargos->get_result();
		echo '
			<div class="table-responsive">
				<table border class="table table-bordered">
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
								<td><button class="btn btn-default" onclick="window.location.href=\'contribuintes.php?id_contribuinte='.$linha['id_contribuinte'].'\'">Selecionar</button></td>
							</tr>
							';
						}
					}
		echo '		</tbody>
				</table>
			</div>
		';
?>