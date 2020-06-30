<?php
	include_once('../ligacao.php');
?>
<!DOCTYPE html>
<html lang="pt">
	<head>
		<meta charset="utf-8">
		<title>Relatorios</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="css/bootstrap.min.css" rel="stylesheet">
	<head>
	<body>
		<?php
			if(isset($_POST['nome'])){
				$pesquisar = $_POST['nome'];

				//Selecionar os itens da página
				$result_msg_contatos = "SELECT contribuintes.id_contribuinte, contribuintes.nome, contribuintes.sexo, contribuintes.dt_nasc, contribuintes.tipo_contribuinte FROM contribuintes INNER JOIN atletas ON contribuintes.id_contribuinte = atletas.id_contribuinte WHERE contribuintes.nome LIKE '%$pesquisar%' LIMIT 30";
				$resultado_msg_contatos = mysqli_query($con , $result_msg_contatos);		

			}else{
				//Verificar se esta sendo passado na URL a página atual, senão é atribuido a pagina
				$pagina=(isset($_GET['pagina'])) ? $_GET['pagina'] : 1;
				
				//Selecionar todos os itens da tabela 
				$result_msg_contato = "SELECT contribuintes.id_contribuinte, contribuintes.nome, contribuintes.sexo, contribuintes.dt_nasc, contribuintes.tipo_contribuinte FROM contribuintes INNER JOIN atletas ON contribuintes.id_contribuinte = atletas.id_contribuinte WHERE contribuintes.tipo_contribuinte = 'Atleta' ";
				$resultado_msg_contato = mysqli_query($con , $result_msg_contato);				
				
				//Contar o total de itens
				$total_msg_contatos = mysqli_num_rows($resultado_msg_contato);
				
				//Quantidade de itens por página
				$quantidade_pg = 20;
				
				//calcular o número de páginas 
				$num_pagina = ceil($total_msg_contatos/$quantidade_pg);
				
				//calcular o inicio	
				$inicio = ($quantidade_pg*$pagina)-$quantidade_pg;
				
				//Selecionar os itens da página
				$result_msg_contatos = "SELECT contribuintes.id_contribuinte, contribuintes.nome, contribuintes.sexo, contribuintes.dt_nasc, contribuintes.tipo_contribuinte FROM contribuintes INNER JOIN atletas ON contribuintes.id_contribuinte = atletas.id_contribuinte limit $inicio, $quantidade_pg";
				$resultado_msg_contatos = mysqli_query($con , $result_msg_contatos);
				$total_msg_contatos = mysqli_num_rows($resultado_msg_contatos);
			}
		?>
		<div class="container theme-showcase" role="main">
			<div class="page-header">
				<h1>Lista de Jogadores</h1>
			</div>
			<form class="form-horizontal" method="POST" action="">
				<div class="form-group">
					<label class="col-sm-2 control-label">Nome</label>
					<div class="col-sm-8">
						<input type="text" name="nome" class="form-control" id="inputEmail3" placeholder="Nome do Usuários" value="">
					</div>
					<div class="col-sm-2">
						<button type="submit" class="btn btn-info">Pesquisar</button>
					</div>
				</div>
			</form>	<hr>
			<form method="POST" action="gerar_planilha_especifica.php">
				<div class="row espaco">
					<div class="pull-right">					
						<!--<a href="form_contato.php"><button type='button' class='btn btn-sm btn-success'>Outro</button></a>-->
						<a href="relatoriosgeral.php"><button type='button' class='btn btn-sm btn-danger'>Relatório Geral</button></a>
						<input type="submit" value="Relatório Especifico" class='btn btn-sm btn-warning'>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<table class="table">
							<thead>
								<tr>
									<th class="text-center">PDF</th>
									<th class="text-center">Id</th>
									<th class="text-center">Nome</th>
									<th class="text-center">Sexo</th>
									<th class="text-center">Data de Nascimento</th>
									<!--<th class="text-center">Ação</th>-->
								</tr>
							</thead>
							<tbody>
								<?php while($row_msg_contatos = mysqli_fetch_assoc($resultado_msg_contatos)){?>
									<tr>
										<?php $id = $row_msg_contatos["id_contribuinte"]; ?>
										<td class="text-center">
											<?php echo "<input type='radio' name='msg_contato[$id]' value='1'" ?>
										</td>
										<td class="text-center"><?php echo $row_msg_contatos["id_contribuinte"]; ?></td>
										<td class="text-center"><?php echo $row_msg_contatos["nome"]; ?></td>
										<td class="text-center"><?php echo $row_msg_contatos["sexo"]; ?></td>
										<td class="text-center"><?php echo $row_msg_contatos["dt_nasc"]; ?></td>
										<!--<td class="text-center">								
											<a href="#">
												<span class="glyphicon glyphicon-eye-open text-primary" aria-hidden="true"></span>
											</a>
											<a href="#">
												<span class="glyphicon glyphicon-pencil text-warning" aria-hidden="true"></span>
											</a>
											<a href="#">
												<span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span>
											</a>
										</td>-->
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</form>
			<?php
			if(!isset($_POST['nome'])){
				//Verificar pagina anterior e posterior
				$pagina_anterior = $pagina - 1;
				$pagina_posterior = $pagina + 1;
				?>
				<nav class="text-center">
					<ul class="pagination">
						<li>
							<?php 
								if($pagina_anterior != 0){
									?><a href="administrativo.php?link=50&pagina=<?php echo $pagina_anterior; ?>" aria-label="Previous">
										<span aria-hidden="true">&laquo;</span>
									</a><?php
								}else{
									?><span aria-hidden="true">&laquo;</span><?php
								}
							?>
						</li>
						<?php
							//Apresentar a paginação
							for($i = 1; $i < $num_pagina + 1; $i++){
								?>
									<li><a href="administrativo.php?link=50&pagina=<?php echo $i; ?>">
										<?php echo $i; ?>
									</a></li>
								<?php
							}
						?>
						<li>
							<?php 
								if($pagina_posterior <= $num_pagina){
									?><a href="administrativo.php?link=50&pagina=<?php echo $pagina_posterior; ?>" aria-label="Next">
										<span aria-hidden="true">&raquo;</span>
									</a><?php
								}else{
									?><span aria-hidden="true">&raquo;</span><?php
								}
							?>
						</li>
					</ul>
				</nav>
			<?php } ?>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>
