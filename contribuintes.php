<?php 
	//Prepara a ligação
		require ('ligacao.php');

		unset($_SESSION['array_atletas']);
	//Se um contribuinte estiver selecionado prepara os dados do mesmo
		if (isset($_GET['id_contribuinte'])) {
		//prepara o select do contribuinte
			$contibuintes_select=$con->prepare("SELECT * FROM contribuintes WHERE id_contribuinte=?");
		//Prepara os dados para o select
			$contibuintes_select->bind_param("i",$_GET['id_contribuinte']);
		//Executa a query
			$contibuintes_select->execute();
		//Busca os resultados
			$resultado=$contibuintes_select->get_result();
		//Coloca na variavel linha um array com os valores
			$linha=$resultado->fetch_assoc();

			if ($linha['tipo_contribuinte']=="Atleta") {
				//prepara o select do contribuinte
					$contibuintes_select=$con->prepare("SELECT * FROM contribuintes INNER JOIN atletas ON contribuintes.id_contribuinte=atletas.id_contribuinte WHERE contribuintes.id_contribuinte=?");
				//Prepara os dados para o select
					$contibuintes_select->bind_param("i",$_GET['id_contribuinte']);
				//Executa a query
					$contibuintes_select->execute();
				//Busca os resultados
					$resultado=$contibuintes_select->get_result();
				//Coloca na variavel linha um array com os valores
					$linha=$resultado->fetch_assoc();
			}
		}

	if (isset($_POST['insert'])) {
		//prepara o insert do contribuinte
			$insert_contribuinte=$con->prepare("INSERT INTO `contribuintes`(`foto`,`num_socio`, `cc`, `nif`, `telemovel`, `telefone`, `cp`, `receber_email`,`tipo_contribuinte`,`morada`, `localidade`, `freguesia`, `concelho`, `nome`, `sexo`, `email`, `metodo_pagamento`, `dt_nasc`, `mensalidade_valor`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

		//inicia variaveis "dummy" para a inserção de ficheiros(foto)
			$null=NULL;
			$foto=NULL;

		//Busca variaveis
			if (isset($_POST['receber_email'])) {
				$receber_email=1;
			}else{
				$receber_email=0;
			}

		//Para cada tipo de contribuinte faz algo diferente:
			if ($_POST['tipo_contribuinte']=="Sócio") {
				//Prepara os dados para insert do Sócio.
					$insert_contribuinte->bind_param("biiiiiiissssssssssd",$foto,$_POST['num_socio'],$_POST['cc'],$_POST['nif'],$_POST['telemovel'],$_POST['telefone'],$_POST['cp'],$receber_email,$_POST['tipo_contribuinte'],$_POST['morada'],$_POST['localidade'],$_POST['freguesia'],$_POST['concelho'],$_POST['nome'],$_POST['sexo'],$_POST['email'],$_POST['metodo_pagamento'],$_POST['dt_nasc'],$_POST['mensalidade_valor']);
				//Verifica se tem foto se sim coloca-a se nao coloca-a pelo sexo.
					if (is_uploaded_file($_FILES["foto"]["tmp_name"])){
						$insert_contribuinte->send_long_data(0,file_get_contents($_FILES["foto"]["tmp_name"]));
					}else{
						if ($_POST['sexo']=="Masculino") {
							$insert_contribuinte->send_long_data(0,file_get_contents("fotos/Male_user.png"));
						}else{
							$insert_contribuinte->send_long_data(0,file_get_contents("fotos/Female_user.png"));
						}
					}
				//Executa a query.
					$insert_contribuinte->execute();
				//Busca o id do sócio
					$id_contribuinte=$insert_contribuinte->insert_id;
			}elseif($_POST['tipo_contribuinte']=="Atleta"){
				//prepara as variaveis
					$metodo_pagamento="No clube";		
				//Prepara os dados para insert do Atleta.
					$insert_contribuinte->bind_param("biiiiiiissssssssssd",$foto,$null,$_POST['cc'],$_POST['nif'],$_POST['telemovel'],$_POST['telefone'],$_POST['cp'],$receber_email,$_POST['tipo_contribuinte'],$_POST['morada'],$_POST['localidade'],$_POST['freguesia'],$_POST['concelho'],$_POST['nome'],$_POST['sexo'],$_POST['email'],$metodo_pagamento,$_POST['dt_nasc'],$_POST['mensalidade_valor_atleta']);

				//Verifica se tem foto se sim coloca-a se nao coloca-a pelo sexo.
					if (is_uploaded_file($_FILES["foto"]["tmp_name"])){
						$insert_contribuinte->send_long_data(0,file_get_contents($_FILES["foto"]["tmp_name"]));
					}else{
						if ($_POST['sexo']=="Masculino") {
							$insert_contribuinte->send_long_data(0,file_get_contents("fotos/Male_user.png"));
						}else{
							$insert_contribuinte->send_long_data(0,file_get_contents("fotos/Female_user.png"));
						}
					}

				//Executa a query
					$insert_contribuinte->execute();

				//Busca o id do Atleta
					$id_contribuinte=$insert_contribuinte->insert_id;

				//Verifica se o enc_educação foi selecionado/inserido
					if (isset($_POST['insert_enc_edu'])) {
					//Se foi selecionado só busca o id do enc_edu
						if (!empty($_POST['id_enc'])) {
							$id_contribuinte_enc=$_POST['id_enc'];
					//Se foi inserido tem de inserir na tabela contribuintes o enc_educação 
						}else{
							//Verifica se o enc_edu quer receber emails do clube
								if (isset($_POST['receber_email_enc'])) {
									$receber_email_enc=1;
								}else{
									$receber_email_enc=0;
								}

							//Prepara os dados para insert do enc_edu.
								$insert_contribuinte->bind_param("biiiiiiissssssssssd",$foto,$null,$_POST['cc_enc'],$_POST['nif_enc'],$_POST['telemovel_enc'],$_POST['telefone_enc'],$_POST['cp_enc'],$receber_email_enc,$_POST['tipo_enc'],$_POST['morada_enc'],$_POST['localidade_enc'],$_POST['freguesia_enc'],$_POST['concelho_enc'],$_POST['nome_enc'],$_POST['sexo_enc'],$_POST['email_enc'],$null,$_POST['dt_nasc_enc'],$null,);

							//Verifica se tem foto se sim coloca-a se nao coloca-a pelo sexo.
								if (is_uploaded_file($_FILES["foto_enc"]["tmp_name"])){
									$insert_contribuinte->send_long_data(0,file_get_contents($_FILES["foto_enc"]["tmp_name"]));
								}else{
									if ($_POST['sexo']=="Masculino") {
										$insert_contribuinte->send_long_data(0,file_get_contents("fotos/Male_user.png"));
									}else{
										$insert_contribuinte->send_long_data(0,file_get_contents("fotos/Female_user.png"));
									}
								}

							//Executa a query
								$insert_contribuinte->execute();

							//Busca o id do enc_edu
								$id_contribuinte_enc=$insert_contribuinte->insert_id;
						}

				//Se nao foi inserido o id do enc_edu, o mesmo é Null.
					}else{
						$id_contribuinte_enc=NULL;
					}

				//Verifica se a joia foi paga ou nao
					if (isset($_POST['joia'])) {
						$joia=1;
					}else{
						$joia=0;
					}

				//prepara o insert do atleta
					$atletas_insert=$con->prepare("INSERT INTO `atletas`(`id_contribuinte`, `id_enc_edu`, `valor_joia`, `joia`) VALUES (?,?,?,?)");

				//Prepara os dados para insert do Atleta.
					$atletas_insert->bind_param("iiii",$id_contribuinte,$id_contribuinte_enc,$_POST['valor_joia'],$joia);
				//Executa a query
					$atletas_insert->execute();
				//Fecha a query
					$atletas_insert->close();
			}elseif($_POST['tipo_contribuinte']=="Encarregado de educação"){
				//Prepara os dados para insert do Enc_edu.
					$insert_contribuinte->bind_param("biiiiiiissssssssssd",$foto,$null,$_POST['cc'],$_POST['nif'],$_POST['telemovel'],$_POST['telefone'],$_POST['cp'],$receber_email,$_POST['tipo_contribuinte'],$_POST['morada'],$_POST['localidade'],$_POST['freguesia'],$_POST['concelho'],$_POST['nome'],$_POST['sexo'],$_POST['email'],$null,$_POST['dt_nasc'],$null);
				//Verifica se tem foto se sim coloca-a se nao coloca-a pelo sexo.
					if (is_uploaded_file($_FILES["foto"]["tmp_name"])){
						$insert_contribuinte->send_long_data(0,file_get_contents($_FILES["foto"]["tmp_name"]));
					}else{
						if ($_POST['sexo']=="Masculino") {
							$insert_contribuinte->send_long_data(0,file_get_contents("fotos/Male_user.png"));
						}else{
							$insert_contribuinte->send_long_data(0,file_get_contents("fotos/Female_user.png"));
						}
					}
				//Executa a query.
					$insert_contribuinte->execute();
				//Prepara o update da tabela atletas
					$update_atletas=$con->prepare("UPDATE `atletas` SET `id_enc_edu`=? WHERE `id_contribuinte`=?");
				//Prepara os dados para insert do Enc_edu na tabela dos atletas.
					$update_atletas->bind_param("ii",$id_contribuinte,$_POST['required_edu_id']);
				//Executa a query
					$update_atletas->execute();
				//Fecha a query
					$update_atletas->close();
			}

			print_r($insert_contribuinte);

			//Confirma se a query funcionou
			if ($insert_contribuinte->affected_rows<1) {
				$insert_contribuinte->close();
				?>
					<script type="text/javascript">
						alert("Ocurreu algo não esperado.");
					</script>
				<?php
			}else{
				$insert_contribuinte->close();
				?>
					<script type="text/javascript">
						alert("Dados inseridos com sucesso.");
						//window.location.href="contribuintes.php?id_contribuinte=<?php echo $id_contribuinte ?>";
					</script>
				<?php
			}
		}

	if (isset($_POST['update'])) {
		//Busca o id do contribuinte
			$id_contribuinte=$_GET['id_contribuinte'];
		//prepara o update do contribuinte
			$update_contribuinte=$con->prepare("UPDATE `contribuintes` SET `foto`=?, `num_socio`=?, `cc`=?, `nif`=?, `telemovel`=?, `telefone`=?, `cp`=?, `receber_email`=?, `tipo_contribuinte`=?, `morada`=?, `localidade`=?, `freguesia`=?, `concelho`=?, `nome`=?, `sexo`=?, `email`=?, `metodo_pagamento`=?, `dt_nasc`=?, `mensalidade_valor`=? WHERE `id_contribuinte`=?");

		//inicia variaveis "dummy" para a inserção de ficheiros(foto)
			$null=NULL;
			$foto=NULL;

		//Busca variaveis
			if (isset($_POST['receber_email'])) {
				$receber_email=1;
			}else{
				$receber_email=0;
			}

		//Para cada tipo de contribuinte faz algo diferente:
			if ($_POST['tipo_contribuinte']=="Sócio") {
				//Prepara os dados para update do Sócio.
					$update_contribuinte->bind_param("biiiiiiissssssssssdi",$foto,$_POST['num_socio'],$_POST['cc'],$_POST['nif'],$_POST['telemovel'],$_POST['telefone'],$_POST['cp'],$receber_email,$linha['tipo_contribuinte'],$_POST['morada'],$_POST['localidade'],$_POST['freguesia'],$_POST['concelho'],$_POST['nome'],$_POST['sexo'],$_POST['email'],$_POST['metodo_pagamento'],$_POST['dt_nasc'],$_POST['mensalidade_valor'],$_POST['id_contribuinte']);
				//Verifica se tem foto se sim coloca-a se nao coloca-a pelo sexo.
					if (is_uploaded_file($_FILES["foto"]["tmp_name"])){
						$update_contribuinte->send_long_data(0,file_get_contents($_FILES["foto"]["tmp_name"]));
					}else{
						if ($_POST['sexo']=="Masculino") {
							$update_contribuinte->send_long_data(0,file_get_contents("fotos/Male_user.png"));
						}else{
							$update_contribuinte->send_long_data(0,file_get_contents("fotos/Female_user.png"));
						}
					}
				//Executa a query.
					$update_contribuinte->execute();
			}elseif($_POST['tipo_contribuinte']=="Atleta"){
				//prepara as variaveis
					$metodo_pagamento="No clube";		

				//Prepara os dados para update do Atleta.
					$update_contribuinte->bind_param("biiiiiiissssssssssdi",$foto,$null,$_POST['cc'],$_POST['nif'],$_POST['telemovel'],$_POST['telefone'],$_POST['cp'],$receber_email,$linha['tipo_contribuinte'],$_POST['morada'],$_POST['localidade'],$_POST['freguesia'],$_POST['concelho'],$_POST['nome'],$_POST['sexo'],$_POST['email'],$metodo_pagamento,$_POST['dt_nasc'],$_POST['mensalidade_valor_atleta'],$_POST['id_contribuinte']);

				//Verifica se tem foto se sim coloca-a se nao coloca-a pelo sexo.
					if (is_uploaded_file($_FILES["foto"]["tmp_name"])){
						$update_contribuinte->send_long_data(0,file_get_contents($_FILES["foto"]["tmp_name"]));
					}else{
						if ($_POST['sexo']=="Masculino") {
							$update_contribuinte->send_long_data(0,file_get_contents("fotos/Male_user.png"));
						}else{
							$update_contribuinte->send_long_data(0,file_get_contents("fotos/Female_user.png"));
						}
					}

				//Executa a query
					$update_contribuinte->execute();
				print_r($linha['id_enc_edu']);
				//Verifica se o enc_educação foi selecionado/inserido
					if (isset($linha['id_enc_edu'])) {
					//Se foi selecionado só busca o id do enc_edu
						if (isset($_POST['id_enc'])) {
							$id_contribuinte_enc=$_POST['id_enc'];
					//Se foi inserido tem de inserir na tabela contribuintes o enc_educação 
						}else{
							//Verifica se o enc_edu quer receber emails do clube
								if (isset($_POST['receber_email_enc'])) {
									$receber_email_enc=1;
								}else{
									$receber_email_enc=0;
								}
							//prepara o insert do enc_edu
								$insert_contribuinte=$con->prepare("INSERT INTO `contribuintes`(`foto`,`num_socio`, `cc`, `nif`, `telemovel`, `telefone`, `cp`, `receber_email`,`tipo_contribuinte`,`morada`, `localidade`, `freguesia`, `concelho`, `nome`, `sexo`, `email`, `metodo_pagamento`, `dt_nasc`, `mensalidade_valor`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
							//Prepara os dados para insert do enc_edu.
								$insert_contribuinte->bind_param("biiiiiiissssssssssd",$foto,$null,$_POST['cc_enc'],$_POST['nif_enc'],$_POST['telemovel_enc'],$_POST['telefone_enc'],$_POST['cp_enc'],$receber_email_enc,$_POST['tipo_enc'],$_POST['morada_enc'],$_POST['localidade_enc'],$_POST['freguesia_enc'],$_POST['concelho_enc'],$_POST['nome_enc'],$_POST['sexo_enc'],$_POST['email_enc'],$null,$_POST['dt_nasc_enc'],$null);

							//Verifica se tem foto se sim coloca-a se nao coloca-a pelo sexo.
								if (is_uploaded_file($_FILES["foto_enc"]["tmp_name"])){
									$insert_contribuinte->send_long_data(0,file_get_contents($_FILES["foto_enc"]["tmp_name"]));
								}else{
									if ($_POST['sexo']=="Masculino") {
										$insert_contribuinte->send_long_data(0,file_get_contents("fotos/Male_user.png"));
									}else{
										$insert_contribuinte->send_long_data(0,file_get_contents("fotos/Female_user.png"));
									}
								}

							//Executa a query
								$insert_contribuinte->execute();

							//Busca o id do enc_edu
								$id_contribuinte_enc=$insert_contribuinte->insert_id;
						}

				//Se nao foi inserido o id do enc_edu, o mesmo é Null.
					}else{
						$id_contribuinte_enc=NULL;
					}

				//Verifica se a joia foi paga ou nao
					if (isset($_POST['joia'])) {
						$joia=1;
					}else{
						$joia=0;
					}

				//prepara o insert do atleta
					$atletas_insert=$con->prepare("INSERT INTO `atletas`(`id_contribuinte`, `id_enc_edu`, `valor_joia`, `joia`) VALUES (?,?,?,?)");

				//Prepara os dados para insert do Atleta.
					$atletas_insert->bind_param("iiii",$id_contribuinte,$id_contribuinte_enc,$_POST['valor_joia'],$joia);
					print_r($atletas_insert);
				//Executa a query
					$atletas_insert->execute();
				//Fecha a query
					$atletas_insert->close();
			}elseif($_POST['tipo_contribuinte']=="Encarregado de educação"){
				//Prepara os dados para insert do Enc_edu.
					$update_contribuinte->bind_param("biiiiiiissssssssssdi",$foto,$null,$_POST['cc'],$_POST['nif'],$_POST['telemovel'],$_POST['telefone'],$_POST['cp'],$receber_email,$_POST['tipo_contribuinte'],$_POST['morada'],$_POST['localidade'],$_POST['freguesia'],$_POST['concelho'],$_POST['nome'],$_POST['sexo'],$_POST['email'],$null,$_POST['dt_nasc'],$null,$_POST['id_contribuinte']);
				//Verifica se tem foto se sim coloca-a se nao coloca-a pelo sexo.
					if (is_uploaded_file($_FILES["foto"]["tmp_name"])){
						$update_contribuinte->send_long_data(0,file_get_contents($_FILES["foto"]["tmp_name"]));
					}else{
						if ($_POST['sexo']=="Masculino") {
							$update_contribuinte->send_long_data(0,file_get_contents("fotos/Male_user.png"));
						}else{
							$update_contribuinte->send_long_data(0,file_get_contents("fotos/Female_user.png"));
						}
					}
				//Executa a query.
					$update_contribuinte->execute();
				//Limpa os valores do id do enc_edu da tabela atletas
					$query=mysqli_query($con,"UPDATE `atletas` SET `id_enc_edu`= NULL WHERE `id_enc_edu`='$id_contribuinte'");
					if (isset($_POST['required_edu_id'])){
						//Prepara o update da tabela atletas
							$update_atletas=$con->prepare("UPDATE `atletas` SET `id_enc_edu`=? WHERE `id_contribuinte`=?");
							for ($i=0; $i < sizeof($_POST['required_edu_id']); $i++) { 
								//Prepara os dados para insert do Enc_edu na tabela dos atletas.
								$update_atletas->bind_param("ii",$id_contribuinte,$_POST['required_edu_id'][$i]);
								//Executa a query
								$update_atletas->execute();
							}
						//Fecha a query
							$update_atletas->close();
					}
			}

			if ($update_contribuinte->affected_rows<0) {
				?>
					<script type="text/javascript">
						alert("Ocurreu algo não esperado.");
					</script>
				<?php
			}else{
				?>
					<script type="text/javascript">
						alert("Atualizado com sucesso.");
						window.location.href="Contribuintes.php?id_contribuinte=<?php echo $id_contribuinte ?>"
					</script>
				<?php	
			}
			$update_contribuinte->close();
		}
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		<script src="//code.jquery.com/jquery.min.js"></script>
		<script src="toastr/toastr.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>
		<title>Contribuintes</title>
	</head>
	<body>
		<!--Popup dos enc edu-->
			<div id="popup_tabela_enc_edu" class="w3-modal" style="padding:3%;display: none;">
				<div class="w3-modal-content" style="width:90%;">
					<header class="w3-container" style="background-color:#3dc4c4;"> 
						<span onclick="esconder_modal_enc_edu()" class="w3-button w3-display-topright">&times;</span>
						<h2 style="text-align:center;font-size: 30;color:white;font-weight:bold; font-family:'Arial';">
							Tabela dos Encarregados de educação
						</h2>
					</header>
					<div class="w3-container">
						<div style="position: relative;overflow: auto; height: 85%">
							<input name="tabela_enc_edu_procura" onkeyup="definir_procura_enc_edu(this.value);first_page();atualizar_tabela_popup_enc_edu(num_pagina,this.value);">
							<div id="tabela_enc_edu">
							</div>
						</div>  
					</div>
					<footer class="w3-container" style="padding:4px;background-color:#3dc4c4;">  
						<center>
							<button type="button" class="w3-btn page_btn" onclick="first_page();atualizar_tabela_popup_enc_edu(num_pagina,procura); ">
								<<
							</button>
							<button type="button" class="w3-btn page_btn" onclick="prev_page();atualizar_tabela_popup_enc_edu(num_pagina,procura);">
								<
							</button>
							<button type="button" class="w3-btn page_btn" onclick="next_page();atualizar_tabela_popup_enc_edu(num_pagina,procura);">
								>
							</button>
							<button type="button" class="w3-btn page_btn" onclick="last_page();atualizar_tabela_popup_enc_edu(num_pagina,procura);">
								>>
							</button>	
						</center>
					</footer>
				</div>
			</div>
		<!--Popup dos atletas-->
			<div id="popup_tabela_atletas" class="w3-modal" style="padding:3%;display: none;">
				<div class="w3-modal-content" style="width:90%;">
					<header class="w3-container" style="background-color:#3dc4c4;"> 
						<span onclick="esconder_modal_atletas()" class="w3-button w3-display-topright">&times;</span>
						<h2 style="text-align:center;font-size: 30;color:white;font-weight:bold; font-family:'Arial';">
							Tabela dos atletas
						</h2>
					</header>
					<div class="w3-container">
						<div style="position: relative;overflow: auto; height: 85%">
							<input name="tabela_atletas_procura" onkeyup="definir_procura_atletas(this.value);first_page();atualizar_tabela_popup_atletas(num_pagina,this.value);">
							<div id="tabela_atletas">
							</div>
						</div>  
					</div>
					<footer class="w3-container" style="padding:4px;background-color:#3dc4c4;">  
						<center>
							<button type="button" class="w3-btn page_btn" onclick="first_page();atualizar_tabela_popup_atletas(num_pagina,procura); ">
								<<
							</button>
							<button type="button" class="w3-btn page_btn" onclick="prev_page();atualizar_tabela_popup_atletas(num_pagina,procura);">
								<
							</button>
							<button type="button" class="w3-btn page_btn" onclick="next_page();atualizar_tabela_popup_atletas(num_pagina,procura);">
								>
							</button>
							<button type="button" class="w3-btn page_btn" onclick="last_page();atualizar_tabela_popup_atletas(num_pagina,procura);">
								>>
							</button>	
						</center>
					</footer>
				</div>
			</div>

		<!-- Ligação aos links e config da Head -->
		<?php include('head.php'); ?>
		<div>




		<!-- Começa aqui o form -->
		<div class="container">

			<!-- Conexão da navbar -->
			<?php include('navbar_dashboard.php'); ?>

			<center style=" margin-top:25px;"><h1>Inserir Contribuintes</h1></center>
			<form method="POST" enctype="multipart/form-data">
			<!-- Conteúdo da página -->
			<div class="card" style=" margin-top:25px;">

				<!-- Titulo + Botões  -->
				<div class="card-header">
					<h3 class="panel-title">Informações Básicas</h3>
				</div>

				<!-- Tabelas / Forms / TUDO -->
				<div class="card-body">

				<div>
					<h1>Contribuinte</h1>
					<?php if (isset($_GET['id_contribuinte'])) {	?>
							<input name="id_contribuinte" hidden value="<?php echo $linha['id_contribuinte']; ?>">
					<?php } ?>
					<div>
						<img id="foto_place" src="<?php 
								if (isset($_GET['id_contribuinte'])){
									echo 'data:image/jpeg;base64,'.base64_encode($linha["foto"]);
								}elseif (isset($_POST['insert']) or isset($_POST['update'])){
									if($_POST['sexo']=='Masculino'){
										echo("fotos/Male_user.png");
									}else{
										echo("fotos/Female_user.png");
									}
								}else{
									echo"fotos/Male_user.png";
								} 
							?>" alt="Foto do contribuinte" height="200" width="200"><br>
						<label>Escolher a foto</label>
							<input type="file" id="foto" name="foto" accept="image/png, image/jpeg"><br>
					</div>
					<div>
						<label>Tipo:</label>
						<?php if (isset($_GET['id_contribuinte'])) { ?>
							<input hidden name="tipo_contribuinte" value="<?php echo($linha['tipo_contribuinte']); ?>">
						<?php }?>
							<select id="tipo_contribuinte" name="tipo_contribuinte" required onchange="mostrar_campos(this.value);">
								<option disabled selected value> -- Escolher uma opção -- </option>
								<option>Sócio</option>
								<option>Atleta</option>
								<option>Encarregado de educação</option>
							</select>
					</div>
					<!--Sócio-->
						<div id="container_socio" style="display: none">
							<div>
								<label>Numero de socio:</label>
									<input class="input_socio" name="num_socio" value="<?php 
										if (isset($_GET['id_contribuinte'])) {
											echo($linha['num_socio']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['num_socio']);
										} 
									?>">
							</div>
							<div>
								<label>Valor quota: </label>
									<input class="input_socio" name="mensalidade_valor" onkeypress="return sonumeros(event)" value="<?php 
										if (isset($_GET['id_contribuinte'])) {
											echo($linha['mensalidade_valor']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['mensalidade_valor']);
										} 
									?>">
							</div>
							<div>
								<label>Metodo de pagamento</label>
								<select class="input_socio" id="metodo_pagamento" name="metodo_pagamento" class="">
									<option disabled selected value> -- Escolher uma opção -- </option>
									<option>Domicilio</option>
									<option>No clube</option>
								</select>
							</div>
						</div>
					<!--Atleta-->
						<div id="container_atleta" style="display: none">
							<?php 
								if (isset($_GET['id_contribuinte'])) {
									$atleta=$con->prepare("SELECT * FROM atletas WHERE id_contribuinte=?");
									$atleta->bind_param("i",$linha['id_contribuinte']);
									$atleta->execute();
									$resultado=$atleta->get_result();
									$linha_atleta=$resultado->fetch_assoc();
								}
							?>
							<div>
								<label>Valor mensalidade: </label>
									<input id="valor_mensalidade" class="input_atleta" name="mensalidade_valor_atleta" onkeypress="return sonumeros(event)" value="<?php 
										if (isset($_GET['id_contribuinte']) AND $linha['tipo_contribuinte']=="Atleta") {
											echo($linha['mensalidade_valor']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['mensalidade_valor_atleta']);
										} 
									?>">
							</div>
							<div>
								<label>Valor joia:</label>
									<input id="valor_joia" class="input_atleta" name="valor_joia" onkeypress="return sonumeros(event)" value="<?php 
										if (isset($_GET['id_contribuinte']) AND $linha['tipo_contribuinte']=="Atleta") {
											echo($linha_atleta['valor_joia']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['valor_joia']);
										} 
									?>">
							</div>
							<div>
								<label>Pagou joia:</label>
									<input id="pagou_joia" name="joia" type="checkbox">
							</div>
								<div>
									<?php if (!isset($_GET['id_contribuinte'])) {?>
										<label>Escolher/Inserir encarregado de educação do atleta</label>
									<?php }else{ ?>
										<label>Encarregado de educação:</label>
									<?php } ?>
										<input id="insert_enc_edu" name="insert_enc_edu" type="checkbox" onchange="mostrar_inputs_enc_edu();">
								</div>
							
						</div>
					<!--Encarregado de educação-->
						<div id="container_enc_edu" style="display: none">
							<div>
								<label>Associar atleta a este encarregado de educação</label>
								<button type="button" onclick="mostrar_modal_atletas();atualizar_tabela_popup_atletas(1,'');">
									Selecionar
								</button>
								<button type="button" onclick="limpar_inputs_enc_edu();">limpar</button>
							</div>

							<br>
							<div id="lista_atletas_enc">
								<label>Lista de atletas associados a este encarregado de educação</label>	
								<div class="containers_dinamicos">
									
								<?php 
								//Querry para ir buscar os ids dos atletas do enc_edu
									if (isset($_GET['id_contribuinte'])) {
											$atletas=$con->prepare("SELECT contribuintes.nome,contribuintes.cc,atletas.id_contribuinte FROM contribuintes INNER JOIN atletas ON contribuintes.id_contribuinte=atletas.id_contribuinte WHERE atletas.id_enc_edu=?");
											$atletas->bind_param("i",$linha['id_contribuinte']);
											$atletas->execute();
											$resultado=$atletas->get_result();
											while ($linha_atleta=$resultado->fetch_assoc()) {
												?>
										<div>
											<input hidden id="required_edu_id" name="required_edu_id[]" class="input_enc required_edu" value="<?php 
														if (isset($_GET['id_contribuinte'])) {
															echo($linha_atleta['id_contribuinte']);
														}elseif (isset($_POST['insert']) || isset($_POST['update'])){
															echo($_POST['mensalidade_valor_atleta']);
														} 
													?>">
										</div>
										<div>
											<label>Nome do atleta:</label>
												<input readonly required id="required_edu_nome" class="input_enc required_edu" value="<?php 
													if (isset($_GET['id_contribuinte'])) {
														echo($linha_atleta['nome']);
													}elseif (isset($_POST['insert']) || isset($_POST['update'])){
														echo($_POST['mensalidade_valor_atleta']);
													} 
												?>">
										</div>
										<div>
											<label>CC do atleta:</label>
												<input readonly required id="required_edu_cc" class=" input_enc required_edu" value="<?php 
													if (isset($_GET['id_contribuinte'])) {
														echo($linha_atleta['cc']);
													}elseif (isset($_POST['insert']) || isset($_POST['update'])){
														echo($_POST['mensalidade_valor_atleta']);
													} 
												?>">
										</div>
										<br>
										<?php 	}
									}
								?>
								</div>					
							</div>
						</div>
					<!--Form principal-->
						<div>
							<label>Nome:</label>
								<input required name="nome" onkeypress="return soletras(event)" value="<?php 
										if (isset($_GET['id_contribuinte'])) {
											echo($linha['nome']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['nome']);
										} 
									?>"><br>			
						</div>
						<div>
							<label>CC:</label>
								<input required name="cc" maxlength="8" onkeypress="return sonumeros(event)" value="<?php 
										if (isset($_GET['id_contribuinte'])) {
											echo($linha['cc']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['cc']);
										} 
									?>"><br>
						</div>
						<div>
							<label>NIF:</label>
								<input required name="nif" maxlength="9" onkeypress="return sonumeros(event)" value="<?php 
										if (isset($_GET['id_contribuinte'])) {
											echo($linha['nif']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['nif']);
										} 
									?>"><br>
						</div>
						<div>
							<label>Sexo:</label>
								<select id="sexo" name="sexo" onchange="mudar_imagem()">
									<option value="Masculino">Masculino</option>
									<option value="Feminino">Feminino</option>
								</select><br>
						</div>
						<div>
							<label>Data de nascimento:</label>
								<input required type="date" name="dt_nasc" value="<?php 
										if (isset($_GET['id_contribuinte'])) {
											echo($linha['dt_nasc']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['dt_nasc']);
										} 
									?>"><br>
						</div>
						<div>
							<label>Receber emails sobre o clube:</label>
								<input type="checkbox" name="receber_email">
						</div>
				</div>
				<!--form de inserir o enc_educação-->
				<div id="enc_edu_atleta" style="display: none">
					<?php
						if ((isset($_GET['id_contribuinte'])) AND ($linha['tipo_contribuinte']=="Atleta")) {
							$enc_edu_atleta=$con->prepare("SELECT * FROM contribuintes WHERE id_contribuinte=?");
							$enc_edu_atleta->bind_param("i",$linha['id_enc_edu']);
							$enc_edu_atleta->execute();
							$resultado_atleta=$enc_edu_atleta->get_result();
							$linha_enc=$resultado_atleta->fetch_assoc();
						}
					?>
					<!--Form secundario-->
						<h1>Encarregado de educação</h1>
						<div>
							<label>Escolher o encarregado de educação</label>
							<button onclick="mostrar_modal_enc_edu();atualizar_tabela_popup_enc_edu('1','');" type="button">Selecionar</button>
						</div>

						<input id="id_contribuinte_enc" name="id_enc" hidden value="<?php 
								if ((isset($_GET['id_contribuinte'])) AND ($linha['tipo_contribuinte']=="Atleta")) { 
									echo $linha_enc['id_contribuinte'];
								} 
							?>">
						<input name="tipo_enc" hidden value="Encarregado de educação">
						<div>
							<img id="foto_place_enc" src="<?php 
									if ((isset($_GET['id_contribuinte'])) AND ($linha['tipo_contribuinte']=="Atleta")) {
										echo 'data:image/jpeg;base64,'.base64_encode($linha_enc["foto"]);
									}elseif (isset($_POST['insert']) or isset($_POST['update'])){
										if($_POST['sexo']=='Masculino'){
											echo("fotos/Male_user.png");
										}else{
											echo("fotos/Female_user.png");
										}
									}else{
										echo"fotos/Male_user.png";
									} 
								?>" alt="Foto do contribuinte" height="200" width="200"><br>
							<label>Escolher a foto</label>
								<input type="file" id="foto_enc" name="foto_enc" class="input_enc" accept="image/png, image/jpeg"><br>
						</div>
						<div>
							<label>Nome:</label>
								<input id="nome_contribuinte_enc" class="input_enc required" name="nome_enc" onkeyup="soletras(this)" value="<?php 
										if ((isset($_GET['id_contribuinte'])) AND ($linha['tipo_contribuinte']=="Atleta")) {
											echo($linha_enc['nome']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['nome_enc']);
										} 
									?>"><br>			
						</div>
						<div>
							<label>CC:</label>
								<input id="cc_contribuinte_enc" class="input_enc required" name="cc_enc" onkeypress="return sonumeros(event)" value="<?php 
										if ((isset($_GET['id_contribuinte'])) AND ($linha['tipo_contribuinte']=="Atleta")) {
											echo($linha_enc['cc']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['cc_enc']);
										} 
									?>"><br>
						</div>
						<div>
							<label>NIF:</label>
								<input id="nif_contribuinte_enc" class="input_enc required" name="nif_enc" onkeypress="return sonumeros(event)" value="<?php 
										if ((isset($_GET['id_contribuinte'])) AND ($linha['tipo_contribuinte']=="Atleta")) {
											echo($linha_enc['nif']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['nif_enc']);
										} 
									?>"><br>
						</div>
						<div>
							<label>Morada:</label>
								<input id="morada_contribuinte_enc" class="input_enc required" name="morada_enc" onkeypress="return sonumeros(event)" value="<?php 
										if ((isset($_GET['id_contribuinte'])) AND ($linha['tipo_contribuinte']=="Atleta")) {
											echo($linha_enc['morada']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['morada_enc']);
										} 
									?>"><br>
						</div>
						<div>
							<label>Localidade:</label>
								<input id="localidade_contribuinte_enc" class="input_enc required" name="localidade_enc" onkeypress="return soletras(event)" value="<?php 
										if ((isset($_GET['id_contribuinte'])) AND ($linha['tipo_contribuinte']=="Atleta")) {
											echo($linha_enc['localidade']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['localidade_enc']);
										} 
									?>"><br>
						</div>
						<div>
							<label>Freguesia:</label>
								<input id="freguesia_contribuinte_enc" class="input_enc required" name="freguesia_enc" onkeypress="return soletras(event)" value="<?php 
										if ((isset($_GET['id_contribuinte'])) AND ($linha['tipo_contribuinte']=="Atleta")) {
											echo($linha_enc['freguesia']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['freguesia_enc']);
										} 
									?>"><br>
						</div>
						<div>
							<label>Concelho:</label>
								<input id="concelho_contribuinte_enc" class="input_enc required" name="concelho_enc" onkeypress="return soletras(event)" value="<?php 
										if ((isset($_GET['id_contribuinte'])) AND ($linha['tipo_contribuinte']=="Atleta")) {
											echo($linha_enc['concelho']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['concelho_enc']);
										} 
									?>"><br>
						</div>
						<div>
							<label>CP:</label>
								<input id="cp" name="cp_enc" maxlength="8" onkeypress="return codigo_postalcheck(event)" value="<?php 
										if ((isset($_GET['id_contribuinte'])) AND ($linha['tipo_contribuinte']=="Atleta")) {
											echo($linha_enc['cp']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['cp_enc']);
										} 
									?>"><br>
						</div>
						<div>
							<label>Email:</label>
								<input id="email_contribuinte_enc" class="input_enc required" name="email_enc" onkeyup="emailcheck(this)" value="<?php 
										if ((isset($_GET['id_contribuinte'])) AND ($linha['tipo_contribuinte']=="Atleta")) {
											echo($linha_enc['email']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['email_enc']);
										} 
									?>"><br>
						</div>
						<div>
							<label>Telemovel:</label>
								<input id="telemovel_contribuinte_enc" class="input_enc required" name="telemovel_enc" maxlength="9" onkeypress="return sonumeros(event)" value="<?php 
										if ((isset($_GET['id_contribuinte'])) AND ($linha['tipo_contribuinte']=="Atleta")) {
											echo($linha_enc['telemovel']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['telemovel_enc']);
										} 
									?>"><br>
						</div>
						<div>
							<label>Telefone:</label>
								<input id="telefone_contribuinte_enc" class="input_enc required" name="telefone_enc" maxlength="9" onkeypress="return sonumeros(event)" value="<?php 
										if ((isset($_GET['id_contribuinte'])) AND ($linha['tipo_contribuinte']=="Atleta")) {
											echo($linha_enc['telefone']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['telefone_enc']);
										} 
									?>"><br>
						</div>
						<div>
							<label>Sexo:</label>
								<select id="sexo_contribuinte_enc" name="sexo_enc" class="input_enc" onchange="mudar_imagem_enc()">
									<option value="Masculino">Masculino</option>
									<option value="Feminino">Feminino</option>
								</select><br>
						</div>
						<div>
							<label>Data de nascimento:</label>
								<input id="dt_nasc_contribuinte_enc" class="input_enc required" type="date" name="dt_nasc_enc" value="<?php 
										if ((isset($_GET['id_contribuinte'])) AND ($linha['tipo_contribuinte']=="Atleta")) {
											echo($linha_enc['dt_nasc']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['dt_nasc_enc']);
										} 
									?>"><br>
						</div>
						<div>
							<label>Receber emails sobre o clube:</label>
								<input id="receber_emails_contribuinte_enc" class="input_enc" type="checkbox" name="receber_email_enc">
						</div>
				</div>

				</div>
			</div>

			<!-- Conteúdo da página -->
			<div class="card" style=" margin-top:25px;">

				<!-- Titulo + Botões  -->
				<div class="card-header">
					<h3 class="panel-title">Informações de Contacto</h3>
				</div>

				<!-- Tabelas / Forms / TUDO -->
				<div class="card-body">

				<div>
							<label>Morada:</label>
								<input required name="morada" onkeypress="return moradacheck(event)" value="<?php 
										if (isset($_GET['id_contribuinte'])) {
											echo($linha['morada']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['morada']);
										} 
									?>"><br>
						</div>
						<div>
							<label>Localidade:</label>
								<input required name="localidade" onkeypress="return soletras(event)" value="<?php 
										if (isset($_GET['id_contribuinte'])) {
											echo($linha['localidade']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['localidade']);
										} 
									?>"><br>
						</div>
						<div>
							<label>Freguesia:</label>
								<input required name="freguesia" onkeypress="return soletras(event)" value="<?php 
										if (isset($_GET['id_contribuinte'])) {
											echo($linha['freguesia']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['freguesia']);
										} 
									?>"><br>
						</div>
						<div>
							<label>Concelho:</label>
								<input required name="concelho" onkeypress="return soletras(event)" value="<?php 
										if (isset($_GET['id_contribuinte'])) {
											echo($linha['concelho']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['concelho']);
										} 
									?>"><br>
						</div>
						<div>
							<label>CP:</label>
								<input required id="cp" name="cp" maxlength="8" onkeypress="return codigo_postalcheck(event)" value="<?php 
										if (isset($_GET['id_contribuinte'])) {
											echo($linha['cp']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['cp']);
										} 
									?>"><br>
						</div>
						<div>
							<label>Email:</label>
								<input required name="email" onkeypress="return emailcheck(event)" value="<?php 
										if (isset($_GET['id_contribuinte'])) {
											echo($linha['email']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['email']);
										} 
									?>"><br>
						</div>
						<div>
							<label>Telemovel:</label>
								<input required name="telefone" maxlength="9" onkeypress="return sonumeros(event)" value="<?php 
										if (isset($_GET['id_contribuinte'])) {
											echo($linha['telemovel']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['telemovel']);
										} 
									?>"><br>
						</div>
						<div>
							<label>Telefone:</label>
								<input required name="telemovel" maxlength="9" onkeypress="return sonumeros(event)" value="<?php 
										if (isset($_GET['id_contribuinte'])) {
											echo($linha['telefone']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['telefone']);
										} 
									?>"><br>
						</div>

				</div>
			</div>

				<div class="d-flex justify-content-center">				
					<?php if (isset($_GET['id_contribuinte'])) {?>
						<input class="btn btn-default" type="submit" name="update" value="Atualizar">
					<?php }else{?>
						<input class="btn btn-default" type="submit" name="insert" value="Inserir">
					<?php } ?>
					<button class="btn btn-default" type="button" onclick="window.location.href ='contribuintes.php'">Limpar</button>
				</div>

		</div>

			<!-- <form method="POST" enctype="multipart/form-data"> -->
			</form>
		</div>
	</body>
</html>
<script type="text/javascript">
	var x=0;
	//controle popup_atletas
		var num_pagina=1;

		function atualizar_tabela_popup_atletas(num_pagina,procura){
			tabela_popup_atletas(num_pagina,procura);
		}

		function tabela_popup_atletas(num_pagina,procura){
			$.post(
				'tabela_popup_atletas.php', 
				{
					'num_pagina': num_pagina,
					'procura':procura
				}, 
				function(response) {
					var resposta=response.split("«");
					total_num_paginas=resposta[0];
					$('#tabela_atletas').html(resposta[1]);
				}
			)
		}
		function definir_procura_atletas(value){
			procura=value;
		}
		function first_page(){
			num_pagina=1;
		}

		function prev_page(){
			if (num_pagina>1) {
				num_pagina--;
			}
		}

		function next_page(){
			if (num_pagina<total_num_paginas) {
				num_pagina++;
			}
		}

		function last_page(){
			num_pagina=total_num_paginas;
		}

		function mostrar_modal_atletas(){
			document.getElementById('popup_tabela_atletas').style.display='block';
		}

		function esconder_modal_atletas(){
			document.getElementById('popup_tabela_atletas').style.display='none';
		}

	//controle popup_enc_edu
		function atualizar_tabela_popup_enc_edu(num_pagina,procura){
			tabela_popup_enc_edu(num_pagina,procura);
		}

		function tabela_popup_enc_edu(num_pagina,procura){
			$.post(
				'tabela_popup_enc_edu.php', 
				{
					'num_pagina': num_pagina,
					'procura':procura
				}, 
				function(response) {
					var resposta=response.split("«");
					total_num_paginas=resposta[0];
					$('#tabela_enc_edu').html(resposta[1]);
				}
			)
		}
		function definir_procura_enc_edu(value){
			procura=value;
		}

		function mostrar_modal_enc_edu(){
			document.getElementById('popup_tabela_enc_edu').style.display='block';
		}

		function esconder_modal_enc_edu(){
			document.getElementById('popup_tabela_enc_edu').style.display='none';
		}

	//variaveis dos inputs
		var inputs_enc=document.getElementsByClassName("input_enc");
		var inputs_socio=document.getElementsByClassName("input_socio");
		var inputs_atleta=document.getElementsByClassName("input_atleta");

		function selecionar_enc_edu(id_contribuinte,nome,cc,nif,morada,localidade,freguesia,concelho,cp,email,telemovel,telefone,sexo,dt_nasc,receber_email){
			alert(id_contribuinte);
			for (var i = inputs_enc.length - 1; i >= 0; i--) {
				inputs_enc[i].disabled=false;
			}
			$.post(
				'buscar_img_contribuinte.php', 
				{
					'id_contribuinte':id_contribuinte
				}, 
				function(response) {
					document.getElementById('foto_place_enc').src='data:image/jpeg;base64,'+response;
				}
			)
			document.getElementById("id_contribuinte_enc").value=id_contribuinte;
			document.getElementById("nome_contribuinte_enc").value=nome;
			document.getElementById("cc_contribuinte_enc").value=cc;
			document.getElementById("nif_contribuinte_enc").value=nif;
			document.getElementById("morada_contribuinte_enc").value=morada;
			document.getElementById("localidade_contribuinte_enc").value=localidade;
			document.getElementById("freguesia_contribuinte_enc").value=freguesia;
			document.getElementById("concelho_contribuinte_enc").value=concelho;
			document.getElementById("cp_contribuinte_enc").value=cp;
			document.getElementById("email_contribuinte_enc").value=email;
			document.getElementById("telemovel_contribuinte_enc").value=telemovel;
			document.getElementById("telefone_contribuinte_enc").value=telefone;
			document.getElementById("dt_nasc_contribuinte_enc").value=dt_nasc;
			if (sexo=="Masculino") {
				document.getElementById("sexo_contribuinte_enc").options.selectedIndex="0";
			}else{
				document.getElementById("sexo_contribuinte_enc").options.selectedIndex="1";	
			}
			if (receber_email=="1") {
				document.getElementById("receber_emails_contribuinte_enc").checked=true;
			}
			for (var i = inputs_enc.length - 1; i >= 0; i--) {
				inputs_enc[i].disabled=true;	
			}
		}

		function selecionar_atleta(acao,id,nome,cc,num_pagina,procura) {
			$.post(
				'selecionar_atleta.php', 
				{
					'acao': acao,
					'id':id
				}, 
				function() {
					atualizar_tabela_popup_atletas(num_pagina,procura);
					if (acao=="0") {
						document.getElementById("container"+id+"").remove();
					}else{
						x++;
						$('#container_enc_edu').append('<div id="container'+id+'" class="containers_dinamicos"><div><input hidden id="required_edu_id'+x+'" name="required_edu_id[]" class="input_enc required_edu" value="'+id+'"></div><div><label>Nome do atleta:</label><input required id="input_enc required_edu_nome'+x+'" readonly class="input_enc required_edu" value="'+nome+'"></div><div><label>CC do atleta:</label><input required id="input_enc required_edu_cc'+x+'" readonly class="input_enc required_edu" value="'+cc+'"><button type="button" name="remove" id="'+x+'" onclick="selecionar_atleta(0,'+id+','+nome+','+cc+','+num_pagina+','+procura+');document.getElementById(\'container'+id+'\').remove();" class="btn btn_remove">Remover</button></div><br></div>');
					}	
				}
			)
		}

		function limpar_inputs_enc_edu(){
			var campos=document.getElementsByClassName("containers_dinamicos");
			for (var i = campos.length - 1; i >= 0; i--) {
				campos[i].remove();
			}
			$.post(
				'selecionar_atleta.php', 
				{
					'acao': 10,
					'id':0
				}, 
				function() {}
			)
		}

	function inputs(value,funcao){
		if (value=="socio") {
			if (funcao=="1") {
				for (var i = inputs_socio.length - 1; i >= 0; i--) {
					inputs_socio[i].required=true;
					inputs_socio[i].value="";
				}
			}else{
				for (var i = inputs_socio.length - 1; i >= 0; i--) {
					inputs_socio[i].required=false;
					inputs_socio[i].value="";
				}
			}
		}
		if (value=="enc") {
			required=document.getElementsByClassName("required");
			if (funcao=="1") {
				for (var i = required.length - 1; i >= 0; i--) {
					required[i].disabled=false;
					required[i].required=true;
					required[i].value="";
				}
			}else{
				for (var i = required.length - 1; i >= 0; i--) {
					required[i].required=false;
					required[i].value="";
				}	
			}

		}
		if (value=="atleta") {
			if (funcao=="1") {
				for (var i = inputs_atleta.length - 1; i >= 0; i--) {
					inputs_atleta[i].required=true;
					inputs_atleta[i].value="";
				}
			}else{
				for (var i = inputs_atleta.length - 1; i >= 0; i--) {
					inputs_atleta[i].required=false;
					inputs_atleta[i].value="";
				}
			}
		}
	}

	//Mostra ou esconde o enc_edu limpando os inputs
		function mostrar_inputs_enc_edu(){
			if (document.getElementById("enc_edu_atleta").style.display=="none") {
				document.getElementById("enc_edu_atleta").style.display="block";
				inputs("enc",1);
			}else{
				document.getElementById("enc_edu_atleta").style.display="none";
				inputs("enc",0);
			}
		}

	//Faz upload da foto para mostrar no site temporariamente
		function readURL(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();

				reader.onload = function(e) {
					$('#foto_place').attr('src', e.target.result);
				}
				reader.readAsDataURL(input.files[0]);
			}
		}
		$("#foto").change(function() {
			readURL(this);
		});

	//Faz upload da foto do encarregado de educação para mostrar no site temporariamente
		function readURL_enc(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();

				reader.onload = function(e) {
					$('#foto_place_enc').attr('src', e.target.result);
				}
				reader.readAsDataURL(input.files[0]);
			}
		}

		$("#foto_enc").change(function() {
			readURL_enc(this);
		});

	//Função de escolher a imagem consuante o sexo
		function mudar_imagem(){
			if ((document.getElementById("foto").value=='')) {
				if (document.getElementById('sexo').value=="Masculino") {
					document.getElementById('foto_place').src="fotos/Male_user.png"
				}else{
					document.getElementById('foto_place').src="fotos/Female_user.png"
				}
			}
		}

	//Função de escolher a imagem do encarregado de educação consuante o sexo
		function mudar_imagem_enc(){
			if ((document.getElementById("foto_enc").value=='')) {
				if (document.getElementById('sexo_contribuinte_enc').value=="Masculino") {
					document.getElementById('foto_place_enc').src="fotos/Male_user.png"
				}else{
					document.getElementById('foto_place_enc').src="fotos/Female_user.png"
				}
			}
		}

		function sonumeros(e) {
	        var charCode = e.charCode ? e.charCode : e.keyCode;
	        // charCode 8 = backspace   
	        // charCode 9 = tab
	        if (charCode != 8 && charCode != 9) {
	            // charCode 48 equivale a 0   
	            // charCode 57 equivale a 9
	            if (charCode < 48 || charCode > 57) {
	                return false;
	            }
	        }
	    }

		function soletras(evt){
			evt = (evt) ? evt : window.event;
			var charCode = (evt.wich) ? evt.which: evt.keyCode;
			if ((charCode==32) || (charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) || (charCode >= 192 && charCode <= 255)) {
				return true;
			}
				return false;
		}

		function nomecheck(evt){
			//verifica se tem 9 digitos
			if (document.getElementById("nome").value.length==40) { 
				toastr.error('O nome só pode ter 40 caracteres');
				return false;
			};
			
			var confirmar=soletras(evt)
			
			if (confirmar==false) {
				toastr.error('O nome só pode conter letras');
				return false
			}
				return true;  
		};

		var isactive=false;
		function emailcheck() {

			if (!(/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/.test(form_atleta.email.value))){
				if (isactive==true) {
					toastr.clear();
					isactive=false	
				}
				toastr.error('Endereço de email invalido');

			}else{

				if (isactive==false) {
					toastr.clear();
					isactive=true
				}
				toastr.success('Endereço de email valido');

			}
		}

		function moradacheck(evt){
			//verifica se tem 9 digitos
			if (document.getElementById("morada").value.length==60) {
				toastr.error('A morada só pode ter 60 caracteres');
				return false;
			}

			var confirmar=letras_numeros(evt);
			
			if (confirmar==false) {
				toastr.error('A morada só pode conter letras numeros e caracteres como º');
				return false
			}else{
				return true
			};  
		};

		function codigo_postalcheck(evt){
			//verifica se tem 9 digitos
			if (document.getElementById("codigo_postal").value.length==7) {
				toastr.error('O codigo postal só pode ter 7 caracteres');
				return false;
			}

			var confirmar=sonumeros(evt);
			if (confirmar==false) {
				toastr.error('O código postal só pode conter numeros');
				return false;
			}else{
				return true
			};  
		};

		function telemovelcheck(evt){
			//verifica se tem 9 digitos
			if (document.getElementById("telemovel").value.length==9) {
				toastr.error('O número de telemóvel só pode ter 9 caracteres');
				return false;
			};
			//verifica se é numero ou não
			var confirmar=sonumeros(evt);
			if (confirmar==false) {
				toastr.error('O número de telemóvel só pode conter numeros');
				return false
			}else{
				return true
			}
		};

		function telefonecheck(evt){
			//verifica se tem 9 digitos
			if (document.getElementById("telefone").value.length==9) {
				toastr.error('O número de telefone só pode ter 9 caracteres');
				return false;
			};
			//verifica se é numero ou não
			var confirmar=sonumeros(evt);
			if (confirmar==false) {
				toastr.error('O número de telefone só pode conter numeros');
				return false
			}else{
				return true
			}
		};

		$(document).ready(() => {
	        let $campo = $("#cp")
	        $campo.mask('0000-000', {reverse: true})
	    })

	function mostrar_campos(tipo){
		if (tipo=="Sócio") {
			inputs("socio",1);
			inputs("enc",0);
			inputs("atleta",0);
			limpar_inputs_enc_edu()
			document.getElementById("container_socio").style.display="block";
			document.getElementById("container_atleta").style.display="none";
			document.getElementById("container_enc_edu").style.display="none";
			document.getElementById("enc_edu_atleta").style.display="none";
		}
		//Mostra os inputs do atleta
		if (tipo=="Atleta") {
			inputs("socio",0);
			inputs("enc",0);
			inputs("atleta",1);
			limpar_inputs_enc_edu()

			document.getElementById("container_socio").style.display="none";
			document.getElementById("container_atleta").style.display="block";
			document.getElementById("container_enc_edu").style.display="none";	
			document.getElementById("insert_enc_edu").checked="";
		}
		if (tipo=="Encarregado de educação") {
			inputs("socio",0);
			inputs("enc",0);
			document.getElementById("container_socio").style.display="none";
			document.getElementById("container_atleta").style.display="none";
			document.getElementById("container_enc_edu").style.display="block";
			document.getElementById("enc_edu_atleta").style.display="none";
		}
	}
</script>
<?php
	if (isset($_GET['id_contribuinte'])) {
		//Busca o sexo do contribuinte
		if ($linha['sexo']=="Masculino") { 
			?><script type="text/javascript">document.getElementById("sexo").options.selectedIndex=0;</script><?php
		}else{
			?><script type="text/javascript">document.getElementById("sexo").options.selectedIndex=1;</script><?php
		}
		//Se o contribuinte for socio
		if ($linha['tipo_contribuinte']=="Sócio") {
			//Seleciona o metodo de pagamento que o Sócio selecionou
			if ($linha['metodo_pagamento']=="Domicilio") {
				?><script type="text/javascript">document.getElementById("metodo_pagamento").options.selectedIndex=1;</script><?php
			}else{
				?><script type="text/javascript">document.getElementById("metodo_pagamento").options.selectedIndex=2;</script><?php
			}
			?>
				<script type="text/javascript">
					//Escolhe o tipo no dropbox input e desativa-o
					document.getElementById("tipo_contribuinte").options.selectedIndex=1;
					document.getElementById("tipo_contribuinte").disabled=true;
					//mostra os campos do socio
					document.getElementById("container_socio").style.display="block";
					document.getElementById("container_atleta").style.display="none";
					document.getElementById("container_enc_edu").style.display="none";
					document.getElementById("enc_edu_atleta").style.display="none";
				</script>
			<?php
		}elseif ($linha['tipo_contribuinte']=="Atleta") {
			//Seleciona se pagou a joia 
			if ($linha['joia']==1) {
				?><script type="text/javascript">document.getElementById("pagou_joia").checked=true;</script><?php
			}
			//Se o atleta tiver um enc_edu mostra os inputs do enc_edu desativados e seleciona a checkbox
			if ($linha['id_enc_edu']) {
				?>
					<script type="text/javascript">
						document.getElementById("insert_enc_edu").checked=true;
						document.getElementById("insert_enc_edu").disabled=true;
						document.getElementById("enc_edu_atleta").style.display="block";
					</script>
				<?php
				if ($linha_enc['sexo']=="Masculino") { 
					?><script type="text/javascript">document.getElementById("sexo_contribuinte_enc").options.selectedIndex="0";</script><?php
				}else{
					?><script type="text/javascript">document.getElementById("sexo_contribuinte_enc").options.selectedIndex="1";</script><?php
				}
				?>
					<script type="text/javascript">
						for (var i = inputs_enc.length - 1; i >= 0; i--) {
							inputs_enc[i].disabled=true;	
						}
					</script>
				<?php
			}
			?>
				<script type="text/javascript">
					document.getElementById("tipo_contribuinte").options.selectedIndex=2;
					document.getElementById("tipo_contribuinte").disabled=true;
			
					document.getElementById("container_socio").style.display="none";
					document.getElementById("container_atleta").style.display="block";
					document.getElementById("container_enc_edu").style.display="none";
				</script>
			<?php
		}else{
			?>
				<script type="text/javascript">
					document.getElementById("tipo_contribuinte").options.selectedIndex=3;
					document.getElementById("tipo_contribuinte").disabled=true;
					document.getElementById("container_socio").style.display="none";
					document.getElementById("container_atleta").style.display="none";
					document.getElementById("container_enc_edu").style.display="block";
				</script>
			<?php
		}
	}elseif (isset($_POST['insert']) || isset($_POST['update'])){
		if ($_POST['tipo_contribuinte']=="Sócio") {
			if ($_POST['metodo_pagamento']=="Domicilio") {
				?><script type="text/javascript">document.getElementById("metodo_pagamento").options.selectedIndex=1;</script><?php
			}else{
				?><script type="text/javascript">document.getElementById("metodo_pagamento").options.selectedIndex=2;</script><?php
			}
			?>
				<script type="text/javascript">
					document.getElementById("tipo_contribuinte").options.selectedIndex=1;
					document.getElementById("container_socio").style.display="block";
					document.getElementById("container_atleta").style.display="none";
					document.getElementById("container_enc_edu").style.display="none";
					document.getElementById("enc_edu_atleta").style.display="none";
				</script>
			<?php
		}elseif ($_POST['tipo_contribuinte']=="Atleta") {
			if (isset($_POST['insert_enc_edu'])) {
				?><script type="text/javascript">
					document.getElementById("insert_enc_edu").checked = true;
					document.getElementById("enc_edu_atleta").style.display = "block";
				</script><?php
			}
			?>
				<script type="text/javascript">
					document.getElementById("tipo_contribuinte")options.selectedIndex=2;
					document.getElementById("container_socio").style.display="none";
					document.getElementById("container_atleta").style.display="block";
					document.getElementById("container_enc_edu").style.display="none";
				</script>
			<?php
		}else{
			?>
				<script type="text/javascript">
					document.getElementById("tipo_contribuinte")options.selectedIndex=3;
					document.getElementById("input_enc required_edu_id").value="<?php echo($_POST['input_enc required_edu_id']) ?>";
					document.getElementById("input_enc required_edu_nome").value="<?php echo($_POST['input_enc required_edu_nome']) ?>";
					document.getElementById("input_enc required_edu_cc").value="<?php echo($_POST['input_enc required_edu_cc']) ?>";
					document.getElementById("container_socio").style.display="none";
					document.getElementById("container_atleta").style.display="none";
					document.getElementById("container_enc_edu").style.display="block";
				</script>
			<?php
		}
	}
?>