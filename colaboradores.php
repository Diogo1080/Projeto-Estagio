<?php 
	require ('ligacao.php');
	
	if ($_SESSION['permissao']==3) {
		header("location: index.php");
	}elseif($_SESSION['permissao']==2 && (!isset($_GET['id_colaborador']) || $_GET['id_colaborador']<>$_SESSION['id'])){
		header("location: colaboradores.php?id_colaborador=".$_SESSION['id']);
	}

	if (isset($_POST['insert'])) {
		$querry=$con->prepare("INSERT INTO `recursos_humanos`(`foto`,`num_recurso_humano`, `password`, `nome`, `sexo`, `dt_nasc`, `morada`, `localidade`, `freguesia`, `concelho`, `cp`, `email`, `telemovel`, `telefone`, `cc`, `nif`, `salario`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

		$cp=explode('-',$_POST['cp']);
		$cp=$cp[0].$cp[1];

		$foto=NULL;
		$hashed_password=NULL;
		$num_colaborador=NULL;

		if (isset($_POST['password'])) {
			if (!empty($_POST['password'])) {
				$hashed_password=password_hash($_POST['password'], PASSWORD_DEFAULT);
			}else{
				$hashed_password=password_hash('1234', PASSWORD_DEFAULT);
			}
		}
		if (isset($_POST['num_colaborador'][1])) {
			$num_colaborador=$_POST['num_colaborador'][0].$_POST['num_colaborador'][1];
		}

		$querry->bind_param("bsssssssssisiiiii",$foto,$num_colaborador,$hashed_password,$_POST['nome'],$_POST['sexo'],$_POST['dt_nasc'],$_POST['morada'],$_POST['localidade'],$_POST['freguesia'],$_POST['concelho'],$cp,$_POST['email'],$_POST['telemovel'],$_POST['telefone'],$_POST['cc'],$_POST['nif'],$_POST['salario']);

		//foto
			if (!is_uploaded_file($_FILES["foto"]["tmp_name"])){
				if ($_POST['sexo']=="Masculino") {
					$querry->send_long_data(0,file_get_contents("fotos/Male_user.png"));
				}else{
					$querry->send_long_data(0,file_get_contents("fotos/Female_user.png"));
				}
			}else{
				$querry->send_long_data(0,file_get_contents($_FILES["foto"]["tmp_name"]));
			}

		$querry->execute();

		$id=$querry->insert_id;

		$ficheiros=$con->prepare("INSERT INTO `ficheiros`(`id_recurso_humano`,`nome`, `extencao`, `filesize`, `ficheiro`) VALUES ($id,?,?,?,?)");
		$content=NULL;

		//Insert cargos
			$insert_cargos=$con->prepare("INSERT INTO `cargos_recursos`(`id_cargo`, `id_recurso_humano`) VALUES (?,?)");
			for ($i=0; $i < count($_POST['cargo']); $i++) { 
				$insert_cargos->bind_param("ii",$_POST['cargo'][$i],$id);
				$insert_cargos->execute();
			}

		//Insert se for treinador de qualquer genero.
			if (isset($_POST['clubes_anteriores'])) {
				$insert_treinador=$con->prepare("INSERT INTO `treinadores`(`id_treinador`, `clubles_anteriores`) VALUES(?,?)");
				$insert_treinador->bind_param("is",$id,$_POST['clubes_anteriores']);
				$insert_treinador->execute();

				if (isset($_FILES["Certificado_desportivo"]['tmp_name'])) {
					if (is_uploaded_file($_FILES["Certificado_desportivo"]['tmp_name'])){
						$filename = "Certificado_desportivo_".$_POST['cc'];
						$tmpname = $_FILES["Certificado_desportivo"]['tmp_name'];
						$file_size = $_FILES["Certificado_desportivo"]['size'];
						$file_type = $_FILES["Certificado_desportivo"]['type'];
						$ext = pathinfo($filename, PATHINFO_EXTENSION);

						$ficheiros->bind_param('ssib',$filename,$file_type,$file_size,$content);
						$ficheiros->send_long_data(3,file_get_contents($_FILES["Certificado_desportivo"]["tmp_name"]));
						$ficheiros->execute();
					}
				}
			}
		//Insert dos ficheiros
			//registo criminal
				if (isset($_FILES["registo_criminal"]["tmp_name"])) {
					if (is_uploaded_file($_FILES["registo_criminal"]['tmp_name'])) {
						$filename = "Registo_criminal_".$_POST['cc'];
						$tmpname = $_FILES["registo_criminal"]['tmp_name'];
						$file_size = $_FILES["registo_criminal"]['size'];
						$file_type = $_FILES["registo_criminal"]['type'];
						$ext = pathinfo($filename, PATHINFO_EXTENSION);

						$ficheiros->bind_param('ssib',$filename,$file_type,$file_size,$content);
						$ficheiros->send_long_data(3,file_get_contents($_FILES["registo_criminal"]["tmp_name"]));
						$ficheiros->execute();
					}
				}
			//certificado academico
				if (isset($_FILES["certificado_academico"]["tmp_name"])) {		
					if (is_uploaded_file($_FILES["certificado_academico"]["tmp_name"])) {
						$filename = "Certificado_academico_".$_POST['cc'];
						$tmpname = $_FILES["certificado_academico"]['tmp_name'];
						$file_size = $_FILES["certificado_academico"]['size'];
						$file_type = $_FILES["certificado_academico"]['type'];
						$ext = pathinfo($filename, PATHINFO_EXTENSION);

						$ficheiros->bind_param('ssib',$filename,$file_type,$file_size,$content);
						$ficheiros->send_long_data(3,file_get_contents($_FILES["certificado_academico"]["tmp_name"]));
						$ficheiros->execute();
					}
				}
			//certificado sbv dae
				if (isset($_FILES["certificado_sbv_dae"]["tmp_name"])) {
					if (is_uploaded_file($_FILES["certificado_sbv_dae"]["tmp_name"])) {
						$filename = "Certificado_sbv_dae_".$_POST['cc'];
						$tmpname = $_FILES["certificado_sbv_dae"]['tmp_name'];
						$file_size = $_FILES["certificado_sbv_dae"]['size'];
						$file_type = $_FILES["certificado_sbv_dae"]['type'];
						$ext = pathinfo($filename, PATHINFO_EXTENSION);

						$ficheiros->bind_param('ssib',$filename,$file_type,$file_size,$content);
						$ficheiros->send_long_data(3,file_get_contents($_FILES["certificado_sbv_dae"]["tmp_name"]));
						$ficheiros->execute();
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

						$ficheiros->bind_param('ssib',$filename,$file_type,$file_size,$content);
						$ficheiros->send_long_data(3,file_get_contents($_FILES["certificado_direcao"]["tmp_name"]));
						$ficheiros->execute();
					}
				}

			$ficheiros->close();

		if ($querry->affected_rows<1) {
			$erro=1;
		}
		if (isset($erro)) {
			?>
				<script type="text/javascript">
					alert("Ocurreu algo não esperado.");
				</script>
			<?php
		}else{
			?>
				<script type="text/javascript">
					alert("inserido com sucesso.");
					window.location.href="colaboradores.php?id_colaborador="+<?php echo $id; ?>
				</script>
			<?php	
		}
		$querry->close();
	}

	if (isset($_POST['update'])) {
		if ($_SESSION['permissao']==2) {

			$hashed_password=password_hash($_POST['password'], PASSWORD_DEFAULT);
			
			$querry=$con->prepare("UPDATE `recursos_humanos` SET `password`=? WHERE `id_recurso_humano`=?");
			$querry->bind_param("si",$hashed_password,$_SESSION['id']);
		}else{
			$foto=NULL;
			$hashed_password=NULL;
			$num_colaborador=NULL;

			$cp=explode('-',$_POST['cp']);
			$cp=$cp[0].$cp[1];

			if (isset($_POST['num_colaborador'][1])) {
				$num_colaborador=$_POST['num_colaborador'][0].$_POST['num_colaborador'][1];
			}

			$querry=$con->prepare("UPDATE `recursos_humanos` SET `foto`=?,`num_recurso_humano`=?,`nome`=?,`sexo`=?,`dt_nasc`=?,`morada`=?,`localidade`=?,`freguesia`=?,`concelho`=?,`cp`=?,`email`=?,`telemovel`=?,`telefone`=?,`cc`=?,`nif`=?,`salario`=? WHERE `id_recurso_humano`=?");

			$querry->bind_param("bssssssssisiiiiii",$foto,$num_colaborador,$_POST['nome'],$_POST['sexo'],$_POST['dt_nasc'],$_POST['morada'],$_POST['localidade'],$_POST['freguesia'],$_POST['concelho'],$cp,$_POST['email'],$_POST['telemovel'],$_POST['telefone'],$_POST['cc'],$_POST['nif'],$_POST['salario'],$_POST['id_colaborador']);

			if (isset($_POST['password'])) {
				if (!empty($_POST['password'])) {
					$hashed_password=password_hash($_POST['password'], PASSWORD_DEFAULT);

					$querry=$con->prepare("UPDATE `recursos_humanos` SET `foto`=?,`num_recurso_humano`=?,`password`=?,`nome`=?,`sexo`=?,`dt_nasc`=?,`morada`=?,`localidade`=?,`freguesia`=?,`concelho`=?,`cp`=?,`email`=?,`telemovel`=?,`telefone`=?,`cc`=?,`nif`=?,`salario`=? WHERE `id_recurso_humano`=?");

					$querry->bind_param("bsssssssssisiiiiii",$foto,$num_colaborador,$hashed_password,$_POST['nome'],$_POST['sexo'],$_POST['dt_nasc'],$_POST['morada'],$_POST['localidade'],$_POST['freguesia'],$_POST['concelho'],$cp,$_POST['email'],$_POST['telemovel'],$_POST['telefone'],$_POST['cc'],$_POST['nif'],$_POST['salario'],$_POST['id_colaborador']);
				}
			}


			//foto
				if (is_uploaded_file($_FILES["foto"]["tmp_name"])){
					$querry->send_long_data(0,file_get_contents($_FILES["foto"]["tmp_name"]));
				}else{
					$select_foto=$con->prepare("SELECT foto FROM `recursos_humanos` WHERE `id_recurso_humano`=?");
					$select_foto->bind_param("i",$_POST['id_colaborador']);
					$select_foto->execute();
					$resultado=$select_foto->get_result();
					$linha=$resultado->fetch_assoc();
					$querry->send_long_data(0,$linha['foto']);
					$select_foto->close();
				}

			//Insert cargos
				$delete_cargos=$con->prepare("DELETE FROM `cargos_recursos` WHERE id_recurso_humano=? ");
				$delete_cargos->bind_param("i",$_POST['id_colaborador']);
				$delete_cargos->execute();
				$delete_cargos->close();

				$insert_cargos=$con->prepare("INSERT INTO `cargos_recursos`(`id_cargo`, `id_recurso_humano`) VALUES (?,?)");
				for ($i=0; $i < count($_POST['cargo']); $i++) { 
					$insert_cargos->bind_param("ii",$_POST['cargo'][$i],$_POST['id_colaborador']);
					$insert_cargos->execute();
					//Check se é treinador
				}

			$ficheiros_update=$con->prepare("UPDATE `ficheiros` SET `nome`=?,`extencao`=?,`filesize`=?,`ficheiro`=? WHERE `id_recurso_humano`=? AND nome LIKE ?");
			$ficheiros_insert=$con->prepare("INSERT INTO `ficheiros`(`id_recurso_humano`,`nome`, `extencao`, `filesize`, `ficheiro`) VALUES ($_POST[id_colaborador],?,?,?,?)");
			$ficheiros_select=$con->prepare("SELECT * FROM `ficheiros` WHERE id_recurso_humano=$_POST[id_colaborador]");
			$ficheiros_select->execute();
			$resultado=$ficheiros_select->get_result();

			//Update se for treinador de qualquer genero.
				if (isset($_POST['clubes_anteriores'])) {
					$update_treinador=$con->prepare("UPDATE `treinadores` SET `clubles_anteriores`=? WHERE `id_treinador`=?");
					$update_treinador->bind_param("si",$_POST['clubes_anteriores'],$_POST['id_colaborador']);
					$update_treinador->execute();

					if (isset($_FILES["Certificado_desportivo"]['tmp_name'])) {
						if (is_uploaded_file($_FILES["Certificado_desportivo"]['tmp_name'])) {

							$filename = "Certificado_desportivo_".$_POST['cc'];
							$tmpname = $_FILES["Certificado_desportivo"]['tmp_name'];
							$file_size = $_FILES["Certificado_desportivo"]['size'];
							$file_type = $_FILES["Certificado_desportivo"]['type'];
							$ext = pathinfo($filename, PATHINFO_EXTENSION);

							while ($linha=$resultado->fetch_assoc()) {
								if (strpos($linha['nome'],'Certificado_desportivo')!==false){
									$Certificado_desportivo="Certificado_desportivo";
									$ficheiros_update->bind_param('ssibis',$filename,$file_type,$file_size,$content,$_POST['id_colaborador'],$Certificado_desportivo);
									$ficheiros_update->send_long_data(3,file_get_contents($_FILES["Certificado_desportivo"]["tmp_name"]));	
									$ficheiros_update->execute();
									$done=1;			
								}
							}
							if (!isset($done)) {
								$ficheiros_insert->bind_param('ssib',$filename,$file_type,$file_size,$content);
								$ficheiros_insert->send_long_data(3,file_get_contents($_FILES["Certificado_desportivo"]["tmp_name"]));	
								$ficheiros_insert->execute();
							}else{
								unset($done);
							}
						}
					}
				}

			//Update dos ficheiros	
				//registo criminal
					if (isset($_FILES["registo_criminal"]['tmp_name'])) {

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
					}
				//certificado academico
					if (isset($_FILES["certificado_academico"]["tmp_name"])) {
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
					}
				//certificado sbv dae
					if (isset($_FILES["certificado_sbv_dae"]["tmp_name"])){
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
		}
		$querry->execute();
		if ($querry->affected_rows<0) {
			?>
				<script type="text/javascript">
					alert("Ocurreu algo não esperado.");
				</script>
			<?php
		}elseif($querry->affected_rows==0){
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
		$querry->close();
	}
?>
<!DOCTYPE html>
<html lang="pt">
	<head>
		<?php include('head.php'); ?>
		<title>Colaboradores</title>
	</head>
	<body>
		<div class="container">
			<?php include('navbar_dashboard.php'); ?>

			<center style=" margin-top:25px;"><h1>Inserir Colaborador</h1></center> 

			<!-- Fetches -->
			<?php 
				if (isset($_GET['id_colaborador'])) {
					$recursos_humanos=$con->prepare("SELECT * FROM recursos_humanos WHERE id_recurso_humano=?");
					$recursos_humanos->bind_param("i",$_GET['id_colaborador']);
					$recursos_humanos->execute();
					$resultado=$recursos_humanos->get_result();
					$linha=$resultado->fetch_assoc();
				}
			?>

			<!-- Inicio do Form -->
			<form method="POST" enctype="multipart/form-data">
				<input hidden name="id_colaborador" value="<?php if (isset($_GET['id_colaborador'])) {echo($linha['id_recurso_humano']);}?>">

				<div class="card" style=" margin-top:25px;">
					<h5 class="card-header">Informações Básicas</h5>
					<div class="card-body">
						<div class="row">
							<div class="col-md-4">         
							<!-- Inserir Foto -->
								<div class="form-group d-flex justify-content-center">
									<img id="foto_place" src="<?php 
									if (isset($_GET['id_colaborador'])){
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
									?>" alt="Foto do contribuinte" height="220" width="210"><br>

								</div>
								<div class="row">
									<div class="col-md-1"></div>
									<div class="col-md-10">
										<label class="custom-file-label">Escolher a foto</label>
											<input class="custom-file-input" type="file" id="foto" name="foto" accept="image/png, image/jpeg"><br>
									</div>
								</div>
							</div>
							<div class="col-md-8">
								<div class="form-row">
							<!-- Nome -->
									<div class="form-group col-md-6">
										<label for="inputEmail4">Nome</label>
										<input required class="form-control" maxlength="60" name="nome" onkeypress="return soletras(event)" value="<?php 
										if (isset($_GET['id_colaborador'])) {
										echo($linha['nome']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
										echo($_POST['nome']);
										} 
										?>">
									</div>
							<!-- Cartão de Cidadão -->
									<div class="form-group col-md-6">
										<label for="inputPassword4">CC</label>
										<input required type="text" class="form-control" name="cc" minlength="8" maxlength="8" onkeypress="return sonumeros(event)" value="<?php 
										if (isset($_GET['id_colaborador'])) {
										echo($linha['cc']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
										echo($_POST['cc']);
										} 
										?>">
									</div>
								</div>
								<div class="form-row">
							<!-- Sexo -->
									<div class="form-group col-md-6">
										<label for="inputPassword4">Sexo</label>
										<select id="sexo" class="form-control" name="sexo" onchange="mudar_imagem()">
											<option value="Masculino">Masculino</option>
											<option value="Feminino">Feminino</option>
										</select>
									</div>
							<!-- Data de Nascimento -->
									<div class="form-group col-md-6">
										<label for="inputEmail4">Data de Nascimento</label>
											<input required class="form-control" type="date" name="dt_nasc" max="<?php echo date('Y-m-d',mktime(00,00,00, 12, 31,$thisyear)); ?>" value="<?php
													if (isset($_GET['id_colaborador'])) {
													echo($linha['dt_nasc']);
													}elseif (isset($_POST['insert']) || isset($_POST['update'])){
													echo($_POST['dt_nasc']);
													}else{
													echo date('Y-m-d',mktime(0,0,0, 12, 31,$thisyear));
													}
													?>">
									</div>
								</div>
								<div class="form-row">
							<!-- Nif -->
									<div class="form-group col-md-6">
										<label for="inputEmail4">NIF</label>
											<input required class="form-control" name="nif" minlength="9" maxlength="9" onkeypress="return sonumeros(event)" value="<?php 
											if (isset($_GET['id_colaborador'])) {
											echo($linha['nif']);
											}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['nif']);
											} 
											?>"><br>
									</div>
							<!-- Salario -->
									<div class="form-group col-md-6">
										<label for="inputEmail4">Salário</label>
										<div class="input-group">
											<div class="input-group-prepend">
												<div class="input-group-text">€</div>
											</div>
											<input class="form-control" name="salario" onkeypress="return sonumeros(event)" value="<?php 
												if (isset($_GET['id_colaborador'])) {
												echo($linha['salario']);
												}elseif (isset($_POST['insert']) || isset($_POST['update'])){
												echo($_POST['salario']);
												} 
												?>">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>         
				</div>

				<div class="card" style=" margin-top:25px;">
					<h5 class="card-header">Cargos</h5>
					<div class="card-body">
						<div class="form-row">
							<div class="form-group col-md-12">
							<label for="inputPassword4">Tipo de Utilizador</label><br> 
							<?php 
								//busca todos os cargos existentes na tabela cargos.
								$cargos = $con->prepare("SELECT * FROM cargos");
								$cargos->execute();
								$resultado=$cargos->get_result();
								if($resultado->num_rows === 0){ 
									echo "Não existem cargos disponiveis.<br>";
								}else{
									while ($linha_cargo=$resultado->fetch_assoc()) { 
										if(!isset($_GET['id_colaborador'])){ ?>
											<div class="form-check form-check-inline">
												<label class="form-check-label" for="inlineCheckbox<?php echo($linha_cargo['id_cargo']); ?>">
													<input class="form-check-input cargo <?php 
														if ($linha_cargo['get_login']==1) {
														echo"get_login ";
														}
														if ($linha_cargo['is_treinador']==1){
														echo "is_treinador ";
														}?>" onclick="<?php 
														if ($linha_cargo['is_treinador']==1){
														echo "toogle_treinador_campos();";
														}
														if ($linha_cargo['get_login']==1) {
														echo"toogle_login_campos();";
														}?>" value="<?php echo($linha_cargo['id_cargo']) ?>" type="checkbox" id="inlineCheckbox<?php echo($linha_cargo['id_cargo']); ?>" name="cargo[]">
													<?php echo($linha_cargo['cargo']); ?>
												</label>
											</div>
										<?php }else{
											$cargo_recurso=$con->prepare("SELECT * FROM cargos_recursos WHERE id_cargo=? AND id_recurso_humano=?");
											$cargo_recurso->bind_param("ii",$linha_cargo['id_cargo'],$linha['id_recurso_humano']);
											$cargo_recurso->execute();
											$resultado_tabela=$cargo_recurso->get_result();
											if($resultado_tabela->num_rows === 0){ ?>
												<div class="form-check form-check-inline">
													<label class="form-check-label" for="inlineCheckbox<?php echo($linha_cargo['id_cargo']); ?>">
														<input class="form-check-input cargo <?php 
															if ($linha_cargo['get_login']==1) {
															echo"get_login ";
															}
															if ($linha_cargo['is_treinador']==1){
															echo "is_treinador ";
															}?>" onclick="<?php 
															if ($linha_cargo['is_treinador']==1){
															echo "toogle_treinador_campos();";
															}
															if ($linha_cargo['get_login']==1) {
															echo"toogle_login_campos();";
															}?>" value="<?php echo($linha_cargo['id_cargo']) ?>" type="checkbox" id="inlineCheckbox<?php echo($linha_cargo['id_cargo']); ?>" name="cargo[]">
														<?php echo($linha_cargo['cargo']); ?>
													</label>
												</div>
											<?php }else{
												if ($linha_cargo['is_treinador']==1){
													$is_treinador=1;
												} ?>
												<div class="form-check form-check-inline">
													<label class="form-check-label" for="inlineCheckbox<?php echo($linha_cargo['id_cargo']); ?>">
														<input checked class="form-check-input cargo <?php 
															if ($linha_cargo['get_login']==1) {
															echo"get_login ";
															}
															if ($linha_cargo['is_treinador']==1){
															echo "is_treinador ";
															}?>" onclick="<?php 
															if ($linha_cargo['is_treinador']==1){
															echo "toogle_treinador_campos();";
															}
															if ($linha_cargo['get_login']==1) {
															echo"toogle_login_campos();";
															}?>" value="<?php echo($linha_cargo['id_cargo']) ?>" type="checkbox" id="inlineCheckbox<?php echo($linha_cargo['id_cargo']); ?>" name="cargo[]">
														<?php echo($linha_cargo['cargo']); ?>
													</label>
												</div>
											<?php }
										}
									}
								} ?>
							</div>
						</div>
					</div>
				</div>

				<div class="card" id="login_campos" style="margin-top:25px;display: none;">
					<h5 class="card-header">Campos de login</h5>
					<div class="card-body">
						<div id="login_treinador">
							<label>Numero de treinador:</label>
							<input id="login_campo_treinador" hidden name="num_colaborador[]" disabled class="input_login" value="T">
								<input disabled value="T">
						</div>
						<div id="login_colaborador">
							<label>Numero de colaborador:</label>
							<input id="login_campo_colaborador" hidden name="num_colaborador[]" disabled class="input_login" value="C">
							<input disabled value="C">
						</div>
						<input onkeypress="return sonumeros(event)" value="<?php if(isset($is_treinador)){$num=explode("T",$linha['num_recurso_humano']);echo (end($num));} ?>" disabled class="input_login" name="num_colaborador[]">
						<div>
							<?php if (isset($is_treinador)) {?>
								<label>Definir nova palavra-passe:<input id="password" type="password" disabled class="input_login" name="password"></label>
							<?php }else{ ?>
								<label>Palavra-passe:<input id="password" type="password" disabled class="input_login" name="password"></label>
							<?php } ?>
						</div>
					</div>
				</div>

				<div class="card" id="treinador_campos" style="margin-top:25px;display: none;">
					<h5 class="card-header">Campos do treinador</h5>
					<div class="card-body">
						<div>
		            		<?php if (isset($is_treinador)) {
								$treinador=$con->prepare("SELECT * FROM `treinadores` WHERE id_treinador=?");
								$treinador->bind_param("i",$linha['id_recurso_humano']);
								$treinador->execute();
								$resultado_treinador=$treinador->get_result();
								$linha_treinador=$resultado_treinador->fetch_assoc();

								$ficheiros=$con->prepare("SELECT * FROM `ficheiros` WHERE id_recurso_humano=$_GET[id_colaborador]");
								$ficheiros->execute();
								$resultado=$ficheiros->get_result();
								while ($linha_ficheiro=$resultado->fetch_assoc()){
									if (strpos($linha_ficheiro['nome'],'Certificado_desportivo')!==false) { ?>
		                      			<label>Atualizar certificado desportivo:</label>
										<input type="file" name="Certificado_desportivo" accept=".pdf,.doc">
										<a href="download_ficheiro.php?id_ficheiro=<?php echo($linha_ficheiro['id_ficheiro']); ?>"> 
											<label>Download do registo criminal</label>
										</a>
										<?php 
										$done=1;
										break;
									}
								}
								if (!isset($done)) { ?>
									<label>Certificado_desportivo:</label>
										<input type="file" name="Certificado_desportivo" accept=".pdf,.doc">
								<?php }else{
									unset($done);
								}
							}else{ ?>
								<label>Certificado_desportivo:</label>
								<input type="file" name="Certificado_desportivo" accept=".pdf,.doc">
							<?php } ?>
						</div>
						<div>
							<label>Clubes anteriores:<textarea disabled class="input_treinador" name="clubes_anteriores"><?php if (isset($is_treinador)) {echo $linha_treinador['clubles_anteriores'];} ?></textarea></label>
						</div>
					</div>
				</div>

				<div class="card" style=" margin-top:25px;">
					<h5 class="card-header">Informações de Contacto</h5>
					<div class="card-body">
						<!-- Morada -->
						<div class="form-group">
							<label for="inputAddress">Morada</label>
							<input required type="text" class="form-control" placeholder="Insira a sua Morada"name="morada" onkeypress="return moradacheck(event)"  value="<?php 
							if (isset($_GET['id_colaborador'])) {
							echo($linha['morada']);
							}elseif (isset($_POST['insert']) || isset($_POST['update'])){
							echo($_POST['morada']);
							} 
							?>">
						</div>
						<!-- Localidade -->
						<div class="form-group">
							<label for="inputAddress2">Localidade</label>
							<input required type="text" class="form-control" placeholder="Insira a sua Localidade" name="localidade" onkeypress="return soletras(event)" value="<?php 
							if (isset($_GET['id_colaborador'])) {
							echo($linha['localidade']);
							}elseif (isset($_POST['insert']) || isset($_POST['update'])){
							echo($_POST['localidade']);
							} 
							?>">
						</div>
						<!-- Concelho, Freguesia, Codigo Postal -->
							<div class="form-row">
							<!-- Concelho -->
								<div class="form-group col-md-6">
									<label for="inputCity">Concelho</label>
									<input required type="text" class="form-control" name="concelho" maxlength="60" onkeypress="return soletras(event)" value="<?php 
									if (isset($_GET['id_colaborador'])) {
									echo($linha['concelho']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
									echo($_POST['concelho']);
									} 
									?>">
								</div>
							<!-- Freguesia -->
								<div class="form-group col-md-4">
									<label for="inputState">Freguesia</label>
									<input required type="text" class="form-control" name="freguesia" maxlength="60" onkeypress="return soletras(event)" value="<?php 
									if (isset($_GET['id_colaborador'])) {
									echo($linha['freguesia']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
									echo($_POST['freguesia']);
									} 
									?>">
								</div>
							<!-- Codigo Postal -->
								<div class="form-group col-md-2">
									<label for="inputZip">Código-Postal</label>
									<input required type="text" class="form-control" id="cp" name="cp" minlength="8" maxlength="8" onkeypress="return sonumeros(event)" value="<?php 
									if (isset($_GET['id_colaborador'])) {
									echo($linha['cp']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
									echo($_POST['cp']);
									} 
									?>">
								</div>
							</div>
						<!-- Email -->
						<div class="form-group">
							<label for="inputAddress2">E-Mail</label>
							<input required type="E-Mail" class="form-control" placeholder="Insira o seu E-Mail" minlength="3" maxlength="60" name="email" value="<?php 
								if (isset($_GET['id_colaborador'])) {
								echo($linha['email']);
								}elseif (isset($_POST['insert']) || isset($_POST['update'])){
								echo($_POST['email']);
								} 
								?>">
						</div>

						<div class="form-row">
						<!-- Telemovel -->
							<div class="form-group col-md-6">
								<label for="inputEmail4">Telemóvel</label>
								<input required type="text" class="form-control" name="telemovel" minlength="9" maxlength="9" onkeypress="return sonumeros(event)" value="<?php 
									if (isset($_GET['id_colaborador'])) {
									echo($linha['telemovel']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
									echo($_POST['telemovel']);
									} 
									?>">
							</div>

						<!-- Telefone -->
							<div class="form-group col-md-6">
								<label for="telefone">Telefone</label>
								<input required type="text" class="form-control" id="telefone" name="telefone" minlength="9" maxlength="9" onkeypress="return sonumeros(event)" value="<?php 
									if (isset($_GET['id_colaborador'])) {
									echo($linha['telefone']);
									}elseif (isset($_POST['insert']) || isset($_POST['update'])){
									echo($_POST['telefone']);
									} 
									?>">
							</div>
						</div>        
					</div>
				</div>

				<div class="card" style=" margin-top:25px;">
					<h5 class="card-header">Ficheiros Relevantes</h5>
				  	<div class="card-body">
					    <label for="exampleInputEmail1">Registo Criminal</label>
					    <div class="input-group mb-3">
					      <div class="custom-file">
					        <?php 
					          if (isset($_GET['id_colaborador'])) {
					            $ficheiros=$con->prepare("SELECT * FROM `ficheiros` WHERE id_recurso_humano=$_GET[id_colaborador]");
					            $ficheiros->execute();
					            $resultado=$ficheiros->get_result();
					            while ($linha_ficheiro=$resultado->fetch_assoc()){
					              if (strpos($linha_ficheiro['nome'],'Registo_criminal')!==false) {
					                ?>
					                  <label class="custom-file-label">Atualizar registo Criminal:</label>
					                  <input class="custom-file-input" type="file" name="Registo_criminal" accept=".pdf,.doc">
					                  </div>
					                  <div class="input-group-append">
					                    <span class="input-group-text" id="inputGroupFileAddon02">
					                    <a href="download_ficheiro.php?id_ficheiro=<?php echo($linha_ficheiro['id_ficheiro']); ?>"> 
					                        Download
					                    </a>
					                    </span>
					                  </div>
					                <?php 
					                $done=1;
					                break;
					              }
					            }
					            if (!isset($done)) {
					              ?>
					                <label class="custom-file-label">Atualizar registo Criminal:</label>
					                <input class="custom-file-input" type="file" name="Registo_criminal" accept=".pdf,.doc">
					                </div>
					              <?php
					            }else{
					              unset($done);
					            }
					          }else{
					            ?>
					              <label class="custom-file-label">Atualizar registo Criminal:</label>
					              <input class="custom-file-input" type="file" name="Registo_criminal" accept=".pdf,.doc">
					              </div>
					            <?php
					          } 
					        ?>
					    </div>
					    <hr>
					    <label for="exampleInputEmail1">Certificado Académico</label>
					    <div class="input-group mb-3">
					      <div class="custom-file">
					        <?php 
					          if (isset($_GET['id_colaborador'])) {
					            $ficheiros=$con->prepare("SELECT * FROM `ficheiros` WHERE id_recurso_humano=$_GET[id_colaborador]");
					            $ficheiros->execute();
					            $resultado=$ficheiros->get_result();
					            while ($linha_ficheiro=$resultado->fetch_assoc()){
					              if (strpos($linha_ficheiro['nome'],'Certificado_academico')!==false) {
					                ?>
					                  <label class="custom-file-label">Atualizar certificado academico:</label>
					                  <input class="custom-file-input" type="file" name="Certificado_academico" accept=".pdf,.doc">
					                  </div>
					                  <div class="input-group-append">
					                    <span class="input-group-text" id="inputGroupFileAddon02">
					                    <a href="download_ficheiro.php?id_ficheiro=<?php echo($linha_ficheiro['id_ficheiro']); ?>"> 
					                        Download
					                    </a>
					                    </span>
					                  </div>
					                <?php 
					                $done=1;
					                break;
					              }
					            }
					            if (!isset($done)) {
					              ?>
					                 <label class="custom-file-label">Atualizar certificado academico:</label>
					                <input class="custom-file-input" type="file" name="Certificado_academico" accept=".pdf,.doc">
					                </div>
					              <?php
					            }else{
					              unset($done);
					            }
					          }else{
					            ?>
					               <label class="custom-file-label">Atualizar certificado academico:</label>
					              <input class="custom-file-input" type="file" name="Certificado_academico" accept=".pdf,.doc">
					              </div>
					            <?php
					          } 
					        ?>
					    </div>
					    <label for="exampleInputEmail1">Certificado SBV DAE</label>
					    <div class="input-group mb-3">
					      <div class="custom-file">
					        <?php 
					          if (isset($_GET['id_colaborador'])) {
					            $ficheiros=$con->prepare("SELECT * FROM `ficheiros` WHERE id_recurso_humano=$_GET[id_colaborador]");
					            $ficheiros->execute();
					            $resultado=$ficheiros->get_result();
					            while ($linha_ficheiro=$resultado->fetch_assoc()){
					              if (strpos($linha_ficheiro['nome'],'Certificado_sbv_dae')!==false) {
					                ?>
					                  <label class="custom-file-label">Atualizar certificado sbv dae:</label>
					                  <input class="custom-file-input" type="file" name="Certificado_sbv_dae" accept=".pdf,.doc">
					                  </div>
					                  <div class="input-group-append">
					                    <span class="input-group-text" id="inputGroupFileAddon02">
					                    <a href="download_ficheiro.php?id_ficheiro=<?php echo($linha_ficheiro['id_ficheiro']); ?>"> 
					                        Download
					                    </a>
					                    </span>
					                  </div>
					                <?php 
					                $done=1;
					                break;
					              }
					            }
					            if (!isset($done)) {
					              ?>
					                <label class="custom-file-label">Atualizar certificado sbv dae:</label>
					                <input class="custom-file-input" type="file" name="Certificado_sbv_dae" accept=".pdf,.doc">
					                </div>
					              <?php
					            }else{
					              unset($done);
					            }
					          }else{
					            ?>
					              <label class="custom-file-label">Atualizar certificado sbv dae:</label>
					              <input class="custom-file-input" type="file" name="Certificado_sbv_dae" accept=".pdf,.doc">
					              </div>
					            <?php
					          } 
					        ?>
					    </div>
					  </div>
					</div>
				</div>
				<div class="d-flex justify-content-center" style=" margin-top:25px;">
					<div class="alert alert-primary">
						<?php if (!isset($_GET['id_colaborador'])) { ?>
							<input type="submit" class="btn btn-default" name="insert" value="Inserir dados">
						<?php }else{ ?>
							<input type="submit" class="btn btn-default" name="update" value="Atualizar dados">
						<?php } ?>
						<button type="button" class="btn btn-default" onclick="window.location.href='colaboradores.php'">Limpar</button>
					</div>
				</div>
			</form>
		</div>    
	</body>
</html>

<!--Faz upload da foto para mostrar no site temporariamente-->
<script type="text/javascript">
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

	let checkbox_get_login=document.getElementsByClassName("get_login")
  	let inputs_get_login=document.getElementsByClassName("input_login")
  	let is_get_login=0

	let checkbox_is_treinador=document.getElementsByClassName("is_treinador")
	let inputs_is_treinador=document.getElementsByClassName("input_treinador")
	let is_treinador=0

	function toogle_login_campos() {
	    for (var i = checkbox_get_login.length - 1; i >= 0; i--) {
	      is_get_login=0
	      if (checkbox_get_login[i].checked==true){
	        is_get_login=1
	        break
	      }
	    }
		if (is_get_login==1) {
			document.getElementById("login_campos").style.display="block"

			<?php if (!isset($_GET['id_colaborador'])) { ?>
				document.getElementById("password").value="1234"
			<?php } ?>
			
			if (is_treinador==0) {
				document.getElementById("login_colaborador").style.display="inline-block"
				document.getElementById("login_treinador").style.display="none"
				for (i = inputs_get_login.length - 1; i >= 0; i--) {
					inputs_get_login[i].disabled = false
					inputs_get_login[i].required = true
				}
				document.getElementById("login_campo_treinador").disabled=true
				document.getElementById("login_campo_colaborador").disabled=false
			}else{
				document.getElementById("login_treinador").style.display="inline-block"
				document.getElementById("login_colaborador").style.display="none"
				for (i = inputs_get_login.length - 1; i >= 0; i--) {
					inputs_get_login[i].disabled = false
					inputs_get_login[i].required = true
				}
				document.getElementById("login_campo_treinador").disabled=false
				document.getElementById("login_campo_colaborador").disabled=true
			}
			document.getElementById("password").required=false
		}else{
			document.getElementById("login_campos").style.display="none";	
			for (i = inputs_get_login.length - 1; i >= 0; i--) {
				inputs_get_login[i].disabled = true
				inputs_get_login[i].required = false
			}
		}
	}

	function toogle_treinador_campos() {
		for (var i = checkbox_is_treinador.length - 1; i >= 0; i--) {
			is_treinador=0
			if (checkbox_is_treinador[i].checked==true){
				is_treinador=1
				break
			}
		}

		if (is_treinador==1) {
			document.getElementById("treinador_campos").style.display="block"
			for (i = inputs_is_treinador.length - 1; i >= 0; i--) {
				inputs_is_treinador[i].disabled = false
			}
			for (i = inputs_is_treinador.length - 1; i >= 0; i--) {
				inputs_is_treinador[i].required = true
			}
		}else{
			document.getElementById("treinador_campos").style.display="none"; 
			for (i = inputs_is_treinador.length - 1; i >= 0; i--) {
				inputs_is_treinador[i].disabled = true
			}
			for (var i = inputs_is_treinador.length - 1; i >= 0; i--) {
				inputs_is_treinador[i].required = false
			}   
		}
	}

	  toogle_treinador_campos()
	  toogle_login_campos()
	//Confirmações
		function sonumeros(e) {
		    let charCode = e.charCode ? e.charCode : e.keyCode
		    // charCode 8 = backspace   
		    // charCode 9 = tab
		    if (charCode !== 8 && charCode !== 9) {
		        // charCode 48 equivale a 0   
		        // charCode 57 equivale a 9
		        if (charCode < 48 || charCode > 57) {
		            return false
		        }
		    }
		}

		function soletras(evt){
			evt = (evt) ? evt : window.event
			var charCode = (evt.wich) ? evt.which: evt.keyCode
			return (charCode === 32) || (charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) || (charCode >= 192 && charCode <= 255)
		}


		function moradacheck(evt) {
			let confirmar = letras_numeros(evt)
			
			if (!confirmar) {
				return false
			} else {
				return true
			}
		}

		$(document).ready(() => {
			$("#cp").mask('0000-000', {reverse: true})
		})
</script>
<?php
	if (!isset($_GET['id_colaborador'])) {
		?>
		<script>
			//Função de escolher a imagem consuante o sexo
			function mudar_imagem(){
				if ((document.getElementById("foto").value === '')) {
					if (document.getElementById('sexo').value === "Masculino") {
						document.getElementById('foto_place').src="fotos/Male_user.png"
					} else {
						document.getElementById('foto_place').src="fotos/Female_user.png"
					}
				}
			}
		</script>
		<?php
	} else {
		if (isset($is_treinador)) {
			?><script type="text/javascript">toogle_treinador_campos()</script><?php
		}
		?>
			<script>
				//Escolher o sexo 
					if ("<?php echo ($linha['sexo']); ?>" === "Masculino") {
						document.getElementById("sexo").options.selectedIndex = 0
					}
					if ("<?php echo ($linha['sexo']); ?>" === "Feminino") {
						document.getElementById("sexo").options.selectedIndex = 1
					}
			</script>
		<?php 
	}
	if ($_SESSION['permissao']==2) {
		?>
			<script type="text/javascript">
				let todos_inputs=document.getElementsByTagName("input");
				for (var i = 0; i < todos_inputs.length; i++) {
					todos_inputs[i].disabled=true
				}
				todos_inputs=document.getElementsByTagName("select");
				for (var i = 0; i < todos_inputs.length; i++) {
					todos_inputs[i].disabled=true
				}
				todos_inputs=document.getElementsByTagName("textarea");
				for (var i = 0; i < todos_inputs.length; i++) {
					todos_inputs[i].disabled=true
				}
				document.getElementById("password").required=true
				document.getElementById("password").disabled=false
				document.getElementById("update").disabled=false
			</script>
		<?php
	}
?>
