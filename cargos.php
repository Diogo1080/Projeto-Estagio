<?php 
	//Prepara a ligação
		require ('ligacao.php');
	//Se um contribuinte estiver selecionado prepara os dados do mesmo
		if (isset($_GET['id_cargo'])) {
			//prepara o select do contribuinte
				$contibuintes_select=$con->prepare("SELECT * FROM cargos WHERE id_cargo=?");
			//Prepara os dados para o select
				$contibuintes_select->bind_param("i",$_GET['id_cargo']);
			//Executa a query
				$contibuintes_select->execute();
			//Busca os resultados
				$resultado=$contibuintes_select->get_result();
			//Coloca na variavel linha um array com os valores
				$linha=$resultado->fetch_assoc();
		}

	if (isset($_POST['insert'])) {
		//prepara o insert do cargo 
			$insert_cargo=$con->prepare("INSERT INTO `cargos` (`cargo`) VALUES (?)");
		//Prepara os dados para o insert
			$insert_cargo->bind_param("s",$_POST['cargo']);
		//Executa a query
			$insert_cargo->execute();
		//Busca o id do cargo
			$id_cargo=$insert_cargo->insert_id;

		//Confirma se a query funcionou
		if ($insert_cargo->affected_rows<1) {
			$insert_cargo->close();
			?>
				<script type="text/javascript">
					alert("Ocurreu algo não esperado.");
				</script>
			<?php
		}else{
			$insert_cargo->close();
			?>
				<script type="text/javascript">
					alert("Dados inseridos com sucesso.");
					window.location.href="cargos.php?id_cargo=<?php echo $id_cargo ?>";
				</script>
			<?php
		}
	}

	if (isset($_POST['update'])) {
		//prepara o insert do cargo
			$update_cargo=$con->prepare("UPDATE `cargos` SET `cargo`=? WHERE `id_cargo`=?");
		//Prepara os dados para o insert
			$update_cargo->bind_param("si",$_POST['cargo'],$_GET['id_cargo']);
		//Executa a query
			$update_cargo->execute();
		//Busca o id do cargo
			$id_cargo=$_GET['id_cargo'];
		//Confirma se a query funcionou
		if ($update_cargo->affected_rows<1) {
			$update_cargo->close();
			?>
				<script type="text/javascript">
					alert("Ocurreu algo não esperado.")
				</script>
			<?php
		}else{
			$update_cargo->close();
			?>
				<script type="text/javascript">
					alert("Dados inseridos com sucesso.")
					window.location.href="cargos.php?id_cargo=<?php echo $id_cargo ?>"
				</script>
			<?php
		}
	}
?>
<!DOCTYPE html>
<html lang="pt">
	<head>
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		<script src="//code.jquery.com/jquery.min.js"></script>
		<script src="toastr/toastr.js"></script>
		<script src="//code.jquery.com/jquery.min.js"></script>
		<title>Cargos</title>
	</head>
	<body>
		<?php require ('nav.php'); ?>
		<div>
			<form method="POST" enctype="multipart/form-data">
				<!--form de inserir o cargo-->
				<div>
					<!--Form secundario-->
						<h1>Cargos</h1>
						<div>
							<label>Nome do cargo:
								<input name="cargo" value="<?php
										if (isset($_GET['id_cargo'])) {
											echo($linha['cargo']);
										} elseif (isset($_POST['insert']) || isset($_POST['update'])) {
											echo($_POST['cargo']);
										}
									?>"><br>
                            </label>
						</div>
				</div>
				<div>
					<?php if (isset($_GET['id_cargo'])) {?>
						<input type="submit" name="update" value="Atualizar">
					<?php } else {?>
						<input type="submit" name="insert" value="Inserir">
					<?php } ?>
				</div>
			</form>
		</div>
		<div>
			<h1>Tabela</h1>
			<div>
				<label>Procura:
                    <input onkeyup="definir_procura(this.value);tabela_cargos(num_pagina,procura)">
                </label>
            </div>
			<div id="tabela_cargos"></div>
			<div>
				<button type="button" class="w3-btn page_btn" onclick="first_page();tabela_cargos(num_pagina,procura)">
					<<
				</button>
				<button type="button" class="w3-btn page_btn" onclick="prev_page();tabela_cargos(num_pagina,procura)">
					<
				</button>
				<button type="button" class="w3-btn page_btn" onclick="next_page();tabela_cargos(num_pagina,procura)">
					>
				</button>
				<button type="button" class="w3-btn page_btn" onclick="last_page();tabela_cargos(num_pagina,procura)">
					>>
				</button>	
			</div>
		</div>
	</body>
</html>
<script type="text/javascript">
	let procura = ''
	let num_pagina = 1
	function tabela_cargos(num_pagina,procura) {
		$.post(
			'tabela_cargos.php', 
			{
				'num_pagina': num_pagina,
				'procura':procura
			}, 
			(response) => {
				let resposta=response.split("«")
				total_num_paginas=resposta[0]
				$('#tabela_cargos').html(resposta[1])
			}
		)
	}
	function definir_procura(value) {
		procura = value
	}
	function first_page() {
		num_pagina = 1
	}

	function prev_page() {
		if (num_pagina>1) {
			num_pagina--
		}
	}

	function next_page() {
		if (num_pagina<total_num_paginas) {
			num_pagina++
		}
	}

	function last_page() {
		num_pagina = total_num_paginas
	}

	tabela_cargos(num_pagina, procura)
</script>
