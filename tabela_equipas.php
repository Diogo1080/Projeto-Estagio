<?php 
	include 'ligacao.php';
	//Inicia as variaveis com os valores.
		$num_pagina = $_POST['num_pagina'];
		$procura = "%{$_POST['procura']}%";
		$registos_por_pagina = 20;
		$offset = ($num_pagina-1) * $registos_por_pagina;
	//Busca o total de registos que existem com os valores dados

	if ($_POST['escalao']=="T") {
		$total_registos=$con->prepare("SELECT count(id_equipa) as total FROM `equipas`INNER JOIN escaloes ON equipas.id_escalao=escaloes.id_escalao WHERE equipas.nome like ?");
		$total_registos->bind_param("s",$procura);
	}else{	
		$total_registos=$con->prepare("SELECT count(id_equipa) as total FROM `equipas`INNER JOIN escaloes ON equipas.id_escalao=escaloes.id_escalao WHERE equipas.nome like ? AND escaloes.id_escalao=? ");
		$total_registos->bind_param("si",$procura,$_POST['escalao']);
	}

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
		if ($_POST['escalao']=="T") {
			$equipas=$con->prepare("SELECT equipas.*,escaloes.escalao FROM `equipas`INNER JOIN escaloes ON equipas.id_escalao=escaloes.id_escalao WHERE equipas.nome like ? LIMIT $offset,$registos_por_pagina");
			$equipas->bind_param("s",$procura);
		}else{
			$equipas=$con->prepare("SELECT equipas.*,escaloes.escalao FROM `equipas`INNER JOIN escaloes ON equipas.id_escalao=escaloes.id_escalao WHERE equipas.nome like ? AND escaloes.id_escalao=? LIMIT $offset,$registos_por_pagina");
			$equipas->bind_param("si",$procura,$_POST['escalao']);

		}
		$equipas->execute();
		$resultado=$equipas->get_result();
		echo '
			<div class="card-body">
				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th scope="col">Nome</th>
							<th scope="col">Escalao</th>
							';
							if ($_SESSION['permissao']==1) {
								echo '
									<th scope="col">Estado</th>
									<th scope="col">Ativar/desativar</th>
								';
							}
							echo '
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
								<td>'.$linha['escalao'].'</td>';
							if ($_SESSION['permissao']==1) {
								echo '
									<td>';
										if ($linha['estado']==1) {
											echo "Ativa";
										}else{
											echo "Desativada";
										}
										echo '
									</td>
									<td>';
									if ($linha['estado']==1) {
										$acao='0';
									}else{
										$acao='1';
									}
									echo '
										<button onclick="if (confirm(\'Tem acerteza que quer desativar a equipa '.$linha['nome'].'?\nEsta ação irá remover os atletas atuais desta equipa. \')) {window.location.href=\'ativar_desativar_equipas.php?id_equipa='.$linha['id_equipa'].'&acao='.$acao.'\'}" class="btn btn-danger">'; 
										if ($linha['estado']==1) {
											echo "Desativar";
										}else{
											echo "Ativar";
										}
									echo '
										</button>
									</td>
								';

							}
							echo '
								<td><button class="btn btn-default" onclick="window.location.href=\'equipa.php?id_equipa='.$linha['id_equipa'].'\'">Selecionar</button></td>
							</tr>
							';
						}
					}
				echo '
</tbody>
</table>
</div>
				';
                
?>