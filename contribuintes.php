<?php 
	require ('ligacao.php');

	if (isset($_POST['insert'])) {
		//prepara o insert do contribuinte
		$contribuintes=$con->prepare("INSERT INTO `contribuintes`(`num_socio`, `cc`, `nif`, `telemovel`, `telefone`, `cp`, `receber_email`, `tipo_contribuinte`, `morada`, `localidade`, `freguesia`, `concelho`, `nome`, `sexo`, `email`, `metodo_pagamento`, `dt_nasc`, `mensalidade_valor`, `foto`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

		//inicia variaveis "dummy" para a inserção de ficheiros(foto)
		$null=NULL;
		$foto=NULL;
		$foto_enc=NULL;

		//Busca variaveis
		if (isset($_POST['receber_email'])) {
			$receber_email=1;
		}else{
			$receber_email=0;
		}
		if (isset($_POST['receber_email_enc'])) {
			$receber_email_enc=1;
		}else{
			$receber_email_enc=0;
		}
		if (isset($_POST['num_socio'])) {
			$num_socio=$_POST['num_socio'];
		}else{
			$num_socio=NULL;
		}

		// coloca as variveis nos placeholders(?) da querry em questao.
		$contribuintes->bind_param("iiiiiiissssssssssdb",$num_socio,$_POST['cc'],$_POST['nif'],$_POST['telemovel'],$_POST['telefone'],$_POST['cp'],$receber_email,$_POST['tipo_contribuinte'],$_POST['morada'],$_POST['localidade'],$_POST['freguesia'],$_POST['concelho'],$_POST['nome'],$_POST['sexo'],$_POST['email'],$_POST['metodo_pagamento'],$_POST['dt_nasc'],$_POST['mensalidade_valor'],$foto);

		//foto
			if (!is_uploaded_file($_FILES["foto"]["tmp_name"])){
				if ($_POST['sexo']=="Masculino") {
					$contribuintes->send_long_data(18,file_get_contents("fotos/Male_user.png"));
				}else{
					$contribuintes->send_long_data(18,file_get_contents("fotos/Female_user.png"));
				}
			}else{
				$contribuintes->send_long_data(18,file_get_contents($_FILES["foto"]["tmp_name"]));
			}
		//insere na tabela contribuintes
		$contribuintes->execute();
		//busca o id do contribuinte
		$id_contribuinte=$contribuintes->insert_id;

		if (isset($_POST['valor_joia'])) {
			if (isset($_POST['joia'])) {
				$joia=1;
			}else{
				$joia=0;
			}
			//prepara a querry insert na tabela dos atletas
			$atletas=$con->prepare("INSERT INTO `atletas`(`id_contribuinte`, `id_enc_edu`, `valor_joia`, `joia` VALUES (?,?,?,?)");

			// coloca as variveis nos placeholders(?) da querry em questao(Encarregado de educação).
			$contribuintes->bind_param("iiiiiiissssssssssdb",$num_socio,$_POST['cc_enc'],$_POST['nif_enc'],$_POST['telemovel_enc'],$_POST['telefone_enc'],$_POST['cp_enc'],$receber_email_enc,$_POST['tipo_contribuinte_enc'],$_POST['morada_enc'],$_POST['localidade_enc'],$_POST['freguesia_enc'],$_POST['concelho_enc'],$_POST['nome_enc'],$_POST['sexo_enc'],$_POST['email_enc'],$null,$_POST['dt_nasc_enc'],$null,$foto_enc);
			//insere na tabela contribuintes
			$contribuintes->execute();
			//Busca o id do enc_edu
			$id_contribuinte_enc=$contribuintes->insert_id;
			// coloca as variveis nos placeholders(?) da querry em questao
			$atletas->bind_param("iiii",$id_contribuinte,$id_contribuinte_enc,$_POST['valor_joia'],$joia);
			//insere na tabela atletas
			$atletas->execute();
			//foto do encarregado de educação
				if (!is_uploaded_file($_FILES["foto_enc"]["tmp_name"])){
					if ($_POST['sexo']=="Masculino") {
						$contribuintes->send_long_data(18,file_get_contents("fotos/Male_user.png"));
					}else{
						$contribuintes->send_long_data(18,file_get_contents("fotos/Female_user.png"));
					}
				}else{
					$contribuintes->send_long_data(18,file_get_contents($_FILES["foto_enc"]["tmp_name"]));
				}
		}

		print_r($contribuintes);
		if ($contribuintes->affected_rows<1) {
			?>
				<script type="text/javascript">
					alert("Ocurreu algo não esperado.");
				</script>
			<?php
		}else{
			?>
				<script type="text/javascript">
					alert("inserido com sucesso.");
				</script>
			<?php	
		}
		$contribuintes->close();
	}

	if (isset($_POST['update'])) {

		$contribuintes=$con->prepare("UPDATE `contibuintes` SET `nome`=?,`sexo`=?,`dt_nasc`=?,`morada`=?,`localidade`=?,`freguesia`=?,`concelho`=?,`CP`=?,`email`=?,`telemovel`=?,`CC`=?,`NIF`=?,`salario`=?,`foto`=? 
			WHERE `id_recurso_humano`=?");

		$foto=NULL;

		$contribuintes->bind_param("sssssssisiiiibi",$_POST['nome'],$_POST['sexo'],$_POST['dt_nasc'],$_POST['morada'],$_POST['localidade'],$_POST['freguesia'],$_POST['concelho'],$_POST['cp'],$_POST['email'],$_POST['telemovel'],$_POST['cc'],$_POST['nif'],$_POST['salario'],$foto,$_POST['id_colaborador']);

		//foto
			if (is_uploaded_file($_FILES["foto"]["tmp_name"])){
				$contribuintes->send_long_data(13,file_get_contents($_FILES["foto"]["tmp_name"]));
			}else{
				$select_foto=$con->prepare("SELECT foto FROM `contibuintes` WHERE `id_recurso_humano`=?");
				$select_foto->bind_param("i",$_POST['id_colaborador']);
				$select_foto->execute();
				$resultado=$select_foto->get_result();
				$linha=$resultado->fetch_assoc();
				$contribuintes->send_long_data(13,$linha['foto']);
				$select_foto->close();
			}

		//Update dos ficheiros	
			$ficheiros_update=$con->prepare("UPDATE `ficheiros` SET `nome`=?,`extencao`=?,`filesize`=?,`ficheiro`=? WHERE `id_recurso_humano`=? AND nome LIKE ?");
			$ficheiros_insert=$con->prepare("INSERT INTO `ficheiros`(`id_recurso_humano`,`nome`, `extencao`, `filesize`, `ficheiro`) VALUES ($_POST[id_colaborador],?,?,?,?)");
			$ficheiros_select=$con->prepare("SELECT * FROM `ficheiros` WHERE id_recurso_humano=$_POST[id_colaborador]");
			$ficheiros_select->execute();
			$resultado=$ficheiros_select->get_result();
			//registo criminal
				if (is_uploaded_file($_FILES["registo_criminal"]['tmp_name'])) {

					$filename = "Registo_criminal_".$_POST['cc'];
					$tmpname = $_FILES["registo_criminal"]['tmp_name'];
					$file_size = $_FILES["registo_criminal"]['size'];
					$file_type = $_FILES["registo_criminal"]['type'];
					$ext = pathinfo($filename, PATHINFO_EXTENSION);

					while ($linha=$resultado->fetch_assoc()) {
						if (strpos($linha['nome'],'Registo_criminal')!==false){
							$registo_criminal="registo_criminal";
							$ficheiros_update->bind_param('ssibis',$filename,$file_type,$file_size,$content,$_POST['id_colaborador'],$registo_criminal);
							$ficheiros_update->send_long_data(3,file_get_contents($_FILES["registo_criminal"]["tmp_name"]));	
							$ficheiros_update->execute();
							$done=1;			
						}
					}
					if (!isset($done)) {
						$ficheiros_insert->bind_param('ssib',$filename,$file_type,$file_size,$content);
						$ficheiros_insert->send_long_data(3,file_get_contents($_FILES["registo_criminal"]["tmp_name"]));	
						$ficheiros_insert->execute();
					}else{
						unset($done);
					}
				}
			//certificado academico
				if (is_uploaded_file($_FILES["certificado_academico"]["tmp_name"])) {

					$filename = "Certificado_academico_".$_POST['cc'];
					$tmpname = $_FILES["certificado_academico"]['tmp_name'];
					$file_size = $_FILES["certificado_academico"]['size'];
					$file_type = $_FILES["certificado_academico"]['type'];
					$ext = pathinfo($filename, PATHINFO_EXTENSION);

					while ($linha=$resultado->fetch_assoc()) {
						if (strpos($linha['nome'],'Certificado_academico')!==false){
							$certificado_academico="certificado_academico";
							$ficheiros_update->bind_param('ssibis',$filename,$file_type,$file_size,$content,$_POST['id_colaborador'],$certificado_academico);
							$ficheiros_update->send_long_data(3,file_get_contents($_FILES["certificado_academico"]["tmp_name"]));	
							$ficheiros_update->execute();
							$done=1;			
						}
					}
					if (!isset($done)) {
						$ficheiros_insert->bind_param('ssib',$filename,$file_type,$file_size,$content);
						$ficheiros_insert->send_long_data(3,file_get_contents($_FILES["certificado_academico"]["tmp_name"]));	
						$ficheiros_insert->execute();
					}else{
						unset($done);
					}
				}
			//certificado sbv dae
				if (is_uploaded_file($_FILES["certificado_sbv_dae"]["tmp_name"])) {

					$filename = "Certificado_sbv_dae_".$_POST['cc'];
					$tmpname = $_FILES["certificado_sbv_dae"]['tmp_name'];
					$file_size = $_FILES["certificado_sbv_dae"]['size'];
					$file_type = $_FILES["certificado_sbv_dae"]['type'];
					$ext = pathinfo($filename, PATHINFO_EXTENSION);

					while ($linha=$resultado->fetch_assoc()) {
						if (strpos($linha['nome'],'Certificado_sbv_dae')!==false){
							$certificado_sbv_dae="certificado_sbv_dae";
							$ficheiros_update->bind_param('ssibis',$filename,$file_type,$file_size,$content,$_POST['id_colaborador'],$certificado_sbv_dae);
							$ficheiros_update->send_long_data(3,file_get_contents($_FILES["certificado_sbv_dae"]["tmp_name"]));	
							$ficheiros_update->execute();
							$done=1;			
						}
					}
					if (!isset($done)) {
						$ficheiros_insert->bind_param('ssib',$filename,$file_type,$file_size,$content);
						$ficheiros_insert->send_long_data(3,file_get_contents($_FILES["certificado_sbv_dae"]["tmp_name"]));	
						$ficheiros_insert->execute();
					}else{
						unset($done);
					}
				}
			//certificado direcao
				if (isset($_FILES["certificado_direcao"]["tmp_name"])){
					if(is_uploaded_file($_FILES["certificado_direcao"]["tmp_name"])) {

						$filename = "Certificado_direcao_".$_POST['cc'];
						$tmpname = $_FILES["certificado_direcao"]['tmp_name'];
						$file_size = $_FILES["certificado_direcao"]['size'];
						$file_type = $_FILES["certificado_direcao"]['type'];
						$ext = pathinfo($filename, PATHINFO_EXTENSION);

						while ($linha=$resultado->fetch_assoc()) {
							if (strpos($linha['nome'],'Certificado_direcao')!==false){
								$certificado_direcao="certificado_direcao";
								$ficheiros_update->bind_param('ssibis',$filename,$file_type,$file_size,$content,$_POST['id_colaborador'],$certificado_direcao);
								$ficheiros_update->send_long_data(3,file_get_contents($_FILES["certificado_direcao"]["tmp_name"]));	
								$ficheiros_update->execute();
								$done=1;			
							}
						}
						if (!isset($done)) {
							$ficheiros_insert->bind_param('ssib',$filename,$file_type,$file_size,$content);
							$ficheiros_insert->send_long_data(3,file_get_contents($_FILES["certificado_direcao"]["tmp_name"]));	
							$ficheiros_insert->execute();
						}else{
							unset($done);
						}
					}
				}	
		$ficheiros_insert->close();
		$ficheiros_update->close();
		$ficheiros_select->close();
		$contribuintes->execute();
		if ($contribuintes->affected_rows<0) {
			?>
				<script type="text/javascript">
					alert("Ocurreu algo não esperado.");
				</script>
			<?php
		}elseif($contribuintes->affected_rows==0){
			?>
				<script type="text/javascript">
					alert("Não existe alteração do registo.");
				</script>
			<?php
		}else{
			?>
				<script type="text/javascript">
					alert("Atualizado com sucesso.");
				</script>
			<?php	
		}
		$contribuintes->close();
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		<script src="//code.jquery.com/jquery.min.js"></script>
		<script src="toastr/toastr.js"></script>
		<script src="//code.jquery.com/jquery.min.js"></script>
		<title>Contribuintes</title>
	</head>
	<body>
		<div id="popup_tabela_atletas" class="w3-modal" style="padding:3%;display: none;">
			<div class="w3-modal-content" style="width:90%;">
				<header class="w3-container" style="background-color:#3dc4c4;"> 
					<span onclick="esconder_modal()" class="w3-button w3-display-topright">&times;</span>
					<h2 style="text-align:center;font-size: 30;color:white;font-weight:bold; font-family:'Arial';">
						Tabela dos atletas
					</h2>
				</header>
				<div class="w3-container">
					<div style="position: relative;overflow: auto; height: 85%">
						<input name="tabela_atletas_procura" onkeypress="definir_procura(this.value);first_page();atualizar_tabela_popup(num_pagina,this.value);">
						<div id="tabela_atletas">
						</div>
					</div>  
				</div>
				<footer class="w3-container" style="padding:4px;background-color:#3dc4c4;">  
					<center>
						<button type="button" class="w3-btn page_btn" onclick="first_page();atualizar_tabela_popup(num_pagina,procura); ">
							<<
						</button>
						<button type="button" class="w3-btn page_btn" onclick="prev_page();atualizar_tabela_popup(num_pagina,procura);">
							<
						</button>
						<button type="button" class="w3-btn page_btn" onclick="next_page();atualizar_tabela_popup(num_pagina,procura);">
							>
						</button>
						<button type="button" class="w3-btn page_btn" onclick="last_page();atualizar_tabela_popup(num_pagina,procura);">
							>>
						</button>	
					</center>
				</footer>
			</div>
		</div>
		<?php require ('nav.php'); ?>
		<div>
			<?php 
				if (isset($_GET['id_contribuinte'])) {
					$contibuintes=$con->prepare("SELECT * FROM contribuintes WHERE id_contribuinte=?");
					$contibuintes->bind_param("i",$_GET['id_contribuinte']);
					$contibuintes->execute();
					$resultado=$contibuintes->get_result();
					$linha=$resultado->fetch_assoc();
				}
			?>
			<form method="POST" enctype="multipart/form-data">
				<div>
					<h1>Contibuinte</h1>
					<?php if (isset($_GET['id_contibuinte'])) { ?>
						<input name="id_contribuinte" hidden value="<?php echo $linha['id_contribuinte']; ?>">
					<?php } ?>
					<div>
						<img id="foto_place" src="<?php 
								if (isset($_GET['id_contibuinte'])){
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
							<select name="tipo_contribuinte" required onchange="mostrar_campos(this.value);">
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
									if (isset($_GET['id_contibuinte'])) {
										echo($linha['num_socio']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
										echo($_POST['num_socio']);
									} 
								?>">
						</div>
						<div>
							<label>Valor quota: </label>
								<input class="input_socio" name="mensalidade_valor" value="<?php 
									if (isset($_GET['id_contibuinte'])) {
										echo($linha['mensalidade_valor']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
										echo($_POST['mensalidade_valor']);
									} 
								?>">
						</div>
					</div>
					<!--Atleta-->
					<div id="container_atleta" style="display: none">
						<?php 
							if (isset($_GET['id_contibuinte'])) {
								$atleta=$con->prepare("SELECT * FROM atletas WHERE id_contribuinte=?");
								$atleta->bind_param("i",$linha['id_contribuinte']);
								$atleta->execute();
								$resultado=$atleta->get_result();
								$linha_atleta=$resultado->fetch_assoc();
							}
						?>
						<div>
							<label>Valor mensalidade: </label>
								<input class="input_atleta" name="mensalidade_valor" value="<?php 
									if (isset($_GET['id_contibuinte'])) {
										echo($linha['mensalidade_valor']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
										echo($_POST['mensalidade_valor']);
									} 
								?>">
						</div>
						<div>
							<label>Valor joia:</label>
								<input class="input_atleta" name="valor_joia" value="<?php 
									if (isset($_GET['id_contibuinte'])) {
										echo($linha_atleta['valor_joia']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
										echo($_POST['valor_joia']);
									} 
								?>">
						</div>
						<div>
							<label>Pagou joia:</label>
								<input name="joia" type="checkbox" value="<?php 
									if (isset($_GET['id_contibuinte'])) {
										echo($linha['joia']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
										echo($_POST['joia']);
									} 
								?>">
						</div>
						<div>
							<label>Inserir encarregado de educação do atleta</label>
								<input id="insert_enc_edu" type="checkbox" onchange="mostrar_inputs_enc_edu();">
						</div>
					</div>
					<!--Encarregado de educação-->
					<div id="container_enc_edu" style="display: none">
						<div>
							<label>Atleta deste encarregado de educação</label>
							<button type="button" onclick="mostrar_modal();atualizar_tabela_popup(1,'');">
								Selecionar
							</button>
						</div>
						<div>
							<label>Nome do atleta selecionado:</label>
								<input id="input_enc_edu_nome" class="input_enc_edu" disabled>
						</div>
						<div>
							<label>CC do atleta selecionado:</label>
								<input id="input_enc_edu_cc" class="input_enc_edu" disabled>
						</div>
					</div>
					<div>
						<label>Nome:</label>
							<input required name="nome" value="<?php 
									if (isset($_GET['id_contibuinte'])) {
										echo($linha['nome']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
										echo($_POST['nome']);
									} 
								?>"><br>			
					</div>
					<div>
						<label>CC:</label>
							<input required name="cc" value="<?php 
									if (isset($_GET['id_contibuinte'])) {
										echo($linha['cc']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
										echo($_POST['cc']);
									} 
								?>"><br>
					</div>
					<div>
						<label>NIF:</label>
							<input required name="nif" value="<?php 
									if (isset($_GET['id_contibuinte'])) {
										echo($linha['nif']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
										echo($_POST['nif']);
									} 
								?>"><br>
					</div>
					<div>
						<label>Morada:</label>
							<input required name="morada" value="<?php 
									if (isset($_GET['id_contibuinte'])) {
										echo($linha['morada']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
										echo($_POST['morada']);
									} 
								?>"><br>
					</div>
					<div>
						<label>Localidade:</label>
							<input required name="localidade" value="<?php 
									if (isset($_GET['id_contibuinte'])) {
										echo($linha['localidade']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
										echo($_POST['localidade']);
									} 
								?>"><br>
					</div>
					<div>
						<label>Freguesia:</label>
							<input required name="freguesia" value="<?php 
									if (isset($_GET['id_contibuinte'])) {
										echo($linha['freguesia']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
										echo($_POST['freguesia']);
									} 
								?>"><br>
					</div>
					<div>
						<label>Concelho:</label>
							<input required name="concelho" value="<?php 
									if (isset($_GET['id_contibuinte'])) {
										echo($linha['concelho']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
										echo($_POST['concelho']);
									} 
								?>"><br>
					</div>
					<div>
						<label>CP:</label>
							<input required name="cp" value="<?php 
									if (isset($_GET['id_contibuinte'])) {
										echo($linha['cp']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
										echo($_POST['cp']);
									} 
								?>"><br>
					</div>
					<div>
						<label>Email:</label>
							<input name="email" value="<?php 
									if (isset($_GET['id_contibuinte'])) {
										echo($linha['email']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
										echo($_POST['email']);
									} 
								?>"><br>
					</div>
					<div>
						<label>Telemovel:</label>
							<input name="telefone" value="<?php 
									if (isset($_GET['id_contibuinte'])) {
										echo($linha['telemovel']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
										echo($_POST['telemovel']);
									} 
								?>"><br>
					</div>
					<div>
						<label>Telefone:</label>
							<input name="telemovel" value="<?php 
									if (isset($_GET['id_contibuinte'])) {
										echo($linha['telefone']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
										echo($_POST['telefone']);
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
									if (isset($_GET['id_contibuinte'])) {
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
				<div id="enc_edu_atleta" style="display: none">
					<h1>Encarregado de educação</h1>
					<?php if (isset($_GET['id_contibuinte'])) { ?>
						<input name="id_contribuinte" hidden value="<?php echo $linha['id_contribuinte']; ?>">
					<?php } ?>
					<input name="tipo_contribuinte_enc" hidden value="Encarregado de educação">
					<div>
						<img id="foto_place_enc" src="<?php 
								if (isset($_GET['id_contibuinte'])){
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
							<input type="file" id="foto_enc" name="foto_enc" accept="image/png, image/jpeg"><br>
					</div>
					<div>
						<label>Nome:</label>
							<input class="input_enc" name="nome_enc" value="<?php 
									if (isset($_GET['id_contibuinte'])) {
										echo($linha['nome']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
										echo($_POST['nome']);
									} 
								?>"><br>			
					</div>
					<div>
						<label>CC:</label>
							<input class="input_enc" name="cc_enc" value="<?php 
									if (isset($_GET['id_contibuinte'])) {
										echo($linha['cc']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
										echo($_POST['cc']);
									} 
								?>"><br>
					</div>
					<div>
						<label>NIF:</label>
							<input class="input_enc" name="nif_enc" value="<?php 
									if (isset($_GET['id_contibuinte'])) {
										echo($linha['nif']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
										echo($_POST['nif']);
									} 
								?>"><br>
					</div>
					<div>
						<label>Morada:</label>
							<input class="input_enc" name="morada_enc" value="<?php 
									if (isset($_GET['id_contibuinte'])) {
										echo($linha['morada']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
										echo($_POST['morada']);
									} 
								?>"><br>
					</div>
					<div>
						<label>Localidade:</label>
							<input class="input_enc" name="localidade_enc" value="<?php 
									if (isset($_GET['id_contibuinte'])) {
										echo($linha['localidade']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
										echo($_POST['localidade']);
									} 
								?>"><br>
					</div>
					<div>
						<label>Freguesia:</label>
							<input class="input_enc" name="freguesia_enc" value="<?php 
									if (isset($_GET['id_contibuinte'])) {
										echo($linha['freguesia']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
										echo($_POST['freguesia']);
									} 
								?>"><br>
					</div>
					<div>
						<label>Concelho:</label>
							<input class="input_enc" name="concelho_enc" value="<?php 
									if (isset($_GET['id_contibuinte'])) {
										echo($linha['concelho']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
										echo($_POST['concelho']);
									} 
								?>"><br>
					</div>
					<div>
						<label>CP:</label>
							<input class="input_enc" name="cp_enc" value="<?php 
									if (isset($_GET['id_contibuinte'])) {
										echo($linha['cp']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
										echo($_POST['cp']);
									} 
								?>"><br>
					</div>
					<div>
						<label>Email:</label>
							<input name="email_enc" value="<?php 
									if (isset($_GET['id_contibuinte'])) {
										echo($linha['email']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
										echo($_POST['email']);
									} 
								?>"><br>
					</div>
					<div>
						<label>Telemovel:</label>
							<input name="telefone_enc" value="<?php 
									if (isset($_GET['id_contibuinte'])) {
										echo($linha['telemovel']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
										echo($_POST['telemovel']);
									} 
								?>"><br>
					</div>
					<div>
						<label>Telefone:</label>
							<input name="telemovel_enc" value="<?php 
									if (isset($_GET['id_contibuinte'])) {
										echo($linha['telefone']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
										echo($_POST['telefone']);
									} 
								?>"><br>
					</div>
					<div>
						<label>Sexo:</label>
							<select id="sexo_enc" name="sexo_enc" onchange="mudar_imagem_enc()">
								<option value="Masculino">Masculino</option>
								<option value="Feminino">Feminino</option>
							</select><br>
					</div>
					<div>
						<label>Data de nascimento:</label>
							<input class="input_enc" type="date" name="dt_nasc_enc" value="<?php 
									if (isset($_GET['id_contibuinte'])) {
										echo($linha['dt_nasc']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
										echo($_POST['dt_nasc']);
									} 
								?>"><br>
					</div>
					<div>
						<label>Receber emails sobre o clube:</label>
							<input type="checkbox" name="receber_email_enc">
					</div>
				</div>
				<div>
					<?php if (isset($_GET['id_contibuinte'])) {?>
						<input type="submit" name="update" value="Atualizar">
					<?php }else{?>
						<input type="submit" name="insert" value="Inserir">
					<?php } ?>
				</div>
			</form>
		</div>
	</body>
</html>
<?php
	if (isset($_GET['id_contibuinte'])) {
		?>
		<script>
			//Escolher o sexo
				if ("<?php echo ($linha['sexo']); ?>"=="Masculino") {
					document.getElementById("sexo").options.selectedIndex=0;
				};
				if ("<?php echo ($linha['sexo']); ?>"=="Feminino") {
					document.getElementById("sexo").options.selectedIndex=1;
				};
		</script>
		<?php
	}
?>
<script type="text/javascript">
	var num_pagina=1;

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
	function definir_procura(value){
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

	// Função de atualizar a tabela com ajax
	function atualizar_tabela_popup(num_pagina,procura){
		tabela_popup_atletas(num_pagina,procura);
	}
	
	function mostrar_modal(){
		document.getElementById('popup_tabela_atletas').style.display='block';
	}
	function esconder_modal(){
		document.getElementById('popup_tabela_atletas').style.display='none';
	}
	//Fim do controle dos popups
	//variaveis dos inputs
	var inputs_enc=document.getElementsByClassName("input_enc");
	var inputs_socio=document.getElementsByClassName("input_socio");
	var inputs_atleta=document.getElementsByClassName("input_atleta");
	function inputs(value,funcao){
		if (value="socio") {
			if (funcao="1") {
				for (var i = inputs_socio.length - 1; i >= 0; i--) {
					inputs_socio[i].required="true";
					inputs_socio[i].value="";
				}
			}else{
				for (var i = inputs_socio.length - 1; i >= 0; i--) {
					inputs_socio[i].required="false";
					inputs_socio[i].value="";
				}
			}
		}
		if (value="enc") {
			if (funcao="1") {
				for (var i = inputs_enc.length - 1; i >= 0; i--) {
					inputs_enc[i].required="true";
					inputs_enc[i].value="";
				}
			}else{
				for (var i = inputs_enc.length - 1; i >= 0; i--) {
					inputs_enc[i].required="false";
					inputs_enc[i].value="";
				}	
			}

		}
		if (value="atleta") {
			if (funcao="1") {
				for (var i = inputs_atleta.length - 1; i >= 0; i--) {
					inputs_atleta[i].required="true";
					inputs_atleta[i].value="";
				}
			}else{
				for (var i = inputs_atleta.length - 1; i >= 0; i--) {
					inputs_atleta[i].required="false";
					inputs_atleta[i].value="";
				}
			}
		}
	}
	//Mostra ou esconde o enc_edu
	function mostrar_inputs_enc_edu(){
		if (document.getElementById("enc_edu_atleta").style.display=="none") {
			document.getElementById("enc_edu_atleta").style.display="block";

		}else{
			document.getElementById("enc_edu_atleta").style.display="none";

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
			if (document.getElementById('sexo_enc').value=="Masculino") {
				document.getElementById('foto_place_enc').src="fotos/Male_user.png"
			}else{
				document.getElementById('foto_place_enc').src="fotos/Female_user.png"
			}
		}
	}


	function mostrar_campos(tipo){
		if (tipo=="Sócio") {

			for (var i = inputs_enc.length - 1; i >= 0; i--) {
				inputs_enc[i].required="false";
				inputs_enc[i].value="";
			}
			document.getElementById("container_socio").style.display="block";
			document.getElementById("container_atleta").style.display="none";
			document.getElementById("container_enc_edu").style.display="none";
			document.getElementById("enc_edu_atleta").style.display="none";
		}
		//Mostra os inputs do atleta
		if (tipo=="Atleta") {
			for (var i = inputs_atleta.length - 1; i >= 0; i--) {
				inputs_atleta[i].required="true";
				inputs_atleta[i].value="";
			}

			document.getElementById("container_socio").style.display="none";
			document.getElementById("container_atleta").style.display="block";
			document.getElementById("container_enc_edu").style.display="none";	
			document.getElementById("insert_enc_edu").checked="";
		}
		if (tipo=="Encarregado de educação") {
			for (var i = inputs_enc.length - 1; i >= 0; i--) {
				inputs_enc[i].required="false";
				inputs_enc[i].value="";
			}
			document.getElementById("container_socio").style.display="none";
			document.getElementById("container_atleta").style.display="none";
			document.getElementById("container_enc_edu").style.display="block";
			document.getElementById("enc_edu_atleta").style.display="none";
		}
	}
</script>