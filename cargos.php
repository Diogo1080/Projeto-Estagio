<?php 
	//Prepara a ligação
		require ('ligacao.php');
		$is_treinador=0;
		$get_login=0;
	//Se um cargo estiver selecionado prepara os dados do mesmo
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
		if (isset($_POST['is_treinador'])) {
			$is_treinador=1;
		}
		if (isset($_POST['get_login'])) {
			$get_login=1;
		}
		//prepara o insert do cargo 
			$insert_cargo=$con->prepare("INSERT INTO `cargos` (`cargo`,`is_treinador`,`get_login`) VALUES (?,?,?)");
		//Prepara os dados para o insert
			$insert_cargo->bind_param("sii",$_POST['cargo'],$is_treinador,$get_login);
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
		if (isset($_POST['is_treinador'])) {
			$is_treinador=1;
		}
		if (isset($_POST['get_login'])) {
			$get_login=1;
		}
		//prepara o insert do cargo
			$update_cargo=$con->prepare("UPDATE `cargos` SET `cargo`=?,`is_treinador`=?,`get_login`=? WHERE `id_cargo`=?");
		//Prepara os dados para o insert
			$update_cargo->bind_param("siii",$_POST['cargo'],$is_treinador,$get_login,$_GET['id_cargo']);
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
					alert("Dados atualizados com sucesso.")
					window.location.href="cargos.php?id_cargo=<?php echo $id_cargo ?>"
				</script>
			<?php
		}
	}
?>

<html lang="en">
  <?php include('head.php'); ?>
  <body>
    <div class="container">
      <?php include('navbar_dashboard.php'); ?>

      <!-- Tables cargos -->
      <div class="col-sm-12">
        <div class="card"style="margin-top: 30px">
          <div class="card-header"> 
            <div class="row">
              <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">
                <h3 class="panel-title">Lista de Cargos</h3>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6" align="right">
                <a href="#" name="add" id="add_button" class="btn btn-default btn-xs" >Novo Cargo</a>      
              </div>
            </div>
          </div>
          <div class="row card-header">
            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-6 ">
                <input class="form-control mr-sm-2" type="search" placeholder="Pesquisa" aria-label="Search" onkeyup="definir_procura(this.value);tabela_cargos(num_pagina,procura)">
            </div> 
            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-6 ">
				<form method="POST" enctype="multipart/form-data">
					<!--form de inserir o cargo-->
					<div>
						<!--Form secundario-->
								<input class="form-control mr-sm-2" placeholder="Nome do cargo" name="cargo" value="<?php
										if (isset($_GET['id_cargo'])) {
											echo($linha['cargo']);
										} elseif (isset($_POST['insert']) || isset($_POST['update'])) {
											echo($_POST['cargo']);
										}
									?>">

						<label>Treinador:
							<input name="is_treinador" id="is_treinador" type="checkbox"><br>
						</label>

						<label>Login:
							<input name="get_login" id="get_login" type="checkbox"><br>
						</label>
						<div>				
							<?php if (isset($_GET['id_cargo'])) {?>
								<input class="btn btn-default" type="submit" name="update" value="Atualizar">
							<?php } else {?>
								<input class="btn btn-default" type="submit" name="insert" value="Inserir">
							<?php } ?>
							<button class="btn btn-default" type="button" onclick="window.location.href='cargos.php'">Limpar</button>
						</div>
					</div>
				</form>
			</div>
		</div>

          <div class="card-body"  id="tabela_cargos"></div>

          <div class="row card-header">
            <div class="col-lg-6 col-md-4 col-sm-6 col-xs-6">
			<button type="button" class="btn btn-default" onclick="first_page();tabela_cargos(num_pagina,procura);">
					<<
				</button>
				<button type="button" class="btn btn-default" onclick="prev_page();tabela_cargos(num_pagina,procura);">
					<
				</button>
				<button type="button" class="btn btn-default" onclick="next_page();tabela_cargos(num_pagina,procura);">
					>
				</button>
				<button type="button" class="btn btn-default" onclick="last_page();tabela_cargos(num_pagina,procura);">
					>>
				</button>
            </div>
            
          </div>
        </div>
      </div>
    </div>
   

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
	<script src="//code.jquery.com/jquery.min.js"></script>
	<script src="toastr/toastr.js"></script>					
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
			function(response) {
				let resposta=response.split("«")
				total_num_paginas=resposta[0]
				$('#tabela_cargos').html(resposta[1]);
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
<?php
	if (isset($_GET['id_cargo'])) {
		if ($linha['is_treinador']==1) {
			?>
			<script type="text/javascript">
				document.getElementById('is_treinador').checked=true;
			</script>
			<?php
		}
		if ($linha['get_login']==1) {
			?>
			<script type="text/javascript">
				document.getElementById('get_login').checked=true;
			</script>
			<?php
		}
	}
?>