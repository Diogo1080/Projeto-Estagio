<?php 
	require ('ligacao.php');

	if (isset($_POST['insert'])) {
		$querry=$con->prepare("INSERT INTO `recursos_humanos`(`nome`, `sexo`, `dt_nasc`, `morada`, `localidade`, `freguesia`, `concelho`, `CP`, `email`, `telemovel`, `CC`, `NIF`,`salario`, `foto`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

		$foto=NULL;

		$querry->bind_param("sssssssisiiiib",$_POST['nome'],$_POST['sexo'],$_POST['dt_nasc'],$_POST['morada'],$_POST['localidade'],$_POST['freguesia'],$_POST['concelho'],$_POST['cp'],$_POST['email'],$_POST['telemovel'],$_POST['cc'],$_POST['nif'],$_POST['salario'],$foto);

		//foto
			if (!is_uploaded_file($_FILES["foto"]["tmp_name"])){
				if ($_POST['sexo']=="Masculino") {
					$querry->send_long_data(13,file_get_contents("fotos/Male_user.png"));
				}else{
					$querry->send_long_data(13,file_get_contents("fotos/Female_user.png"));
				}
			}else{
				$querry->send_long_data(13,file_get_contents($_FILES["foto"]["tmp_name"]));
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
		//Insert se for treinador
			if (isset($_POST['num_treinador'])) {
				$insert_treinador=$con->prepare("INSERT INTO `treinadores`(`id_treinador`, `num_treinador`, `password`, `clubles_anteriores`) VALUES(?,?,?,?)");
				$hashed_password=password_hash($_POST['password'], PASSWORD_DEFAULT);
				$num_treinador=$_POST['num_treinador'][0].$_POST['num_treinador'][1];
				$insert_treinador->bind_param("isss",$id,$num_treinador,$hashed_password,$_POST['clubes_anteriores']);
				$insert_treinador->execute();

				$filename = "Certificado_desportivo_".$_POST['cc'];
				$tmpname = $_FILES["Certificado_desportivo"]['tmp_name'];
				$file_size = $_FILES["Certificado_desportivo"]['size'];
				$file_type = $_FILES["Certificado_desportivo"]['type'];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);

				$ficheiros->bind_param('ssib',$filename,$file_type,$file_size,$content);
				$ficheiros->send_long_data(3,file_get_contents($_FILES["Certificado_desportivo"]["tmp_name"]));
				$ficheiros->execute();
			}
		//Insert dos ficheiros
			//registo criminal
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
			//certificado academico
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
			//certificado sbv dae
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
		$querry->close();
	}

	if (isset($_POST['update'])) {

		$querry=$con->prepare("UPDATE `recursos_humanos` SET `nome`=?,`sexo`=?,`dt_nasc`=?,`morada`=?,`localidade`=?,`freguesia`=?,`concelho`=?,`CP`=?,`email`=?,`telemovel`=?,`CC`=?,`NIF`=?,`salario`=?,`foto`=? 
			WHERE `id_recurso_humano`=?");

		$foto=NULL;

		$querry->bind_param("sssssssisiiiibi",$_POST['nome'],$_POST['sexo'],$_POST['dt_nasc'],$_POST['morada'],$_POST['localidade'],$_POST['freguesia'],$_POST['concelho'],$_POST['cp'],$_POST['email'],$_POST['telemovel'],$_POST['cc'],$_POST['nif'],$_POST['salario'],$foto,$_POST['id_colaborador']);

		//foto
			if (is_uploaded_file($_FILES["foto"]["tmp_name"])){
				$querry->send_long_data(13,file_get_contents($_FILES["foto"]["tmp_name"]));
			}else{
				$select_foto=$con->prepare("SELECT foto FROM `recursos_humanos` WHERE `id_recurso_humano`=?");
				$select_foto->bind_param("i",$_POST['id_colaborador']);
				$select_foto->execute();
				$resultado=$select_foto->get_result();
				$linha=$resultado->fetch_assoc();
				$querry->send_long_data(13,$linha['foto']);
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
<html>
	<?php include('head.php'); ?>
	<head>
		<script src="//code.jquery.com/jquery.min.js"></script>
		<script src="toastr/toastr.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>
		<title>Colaboradores</title>
	</head>
	<body>



	<div class="container">
	      <?php include('navbar_dashboard.php'); ?>


	      <center style=" margin-top:25px;"><h1>Inserir Colaborador</h1></center> 

	      <div class="col-sm-12">
			<!-- ^Trabalha as fetches I think -->
		  <div>
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

	      	<div class="card"style="margin-top: 30px">
	      	  <div class="card-header"> 
	      	    <h3 class="panel-title">Informações Básicas</h3>
	      	  </div>
	      	  <div class="card-body">
	      	    

				<?php if (isset($_GET['id_colaborador'])) { ?>
					<input name="id_colaborador" hidden value="<?php echo $linha['id_recurso_humano']; ?>">
				<?php } ?>
				<div>
					<img id="foto_place" src="
						<?php 
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
						?>" alt="Foto do colaborador" height="200" width="200"><br>
					<label>Escolher a foto</label>
						<input type="file" id="foto" name="foto" accept="image/png, image/jpeg"><br>
				</div>
				<div>
					<label>Cargos:</label><br>
						<?php 
							//busca todos os cargos existentes na tabela cargos.
							$cargos = $con->prepare("SELECT * FROM cargos");
							$cargos->execute();
							$resultado=$cargos->get_result();

							//Confirma se existem cargos.
							if($resultado->num_rows === 0){ 
								echo "Não existem cargos disponiveis.<br>";
							}else{
								//Se um humano nao tiver selecionado.
								if (!isset($_GET['id_colaborador'])) {
									//Escreve os cargos e as respetivas checkboxes.
									while ($linha_cargo=$resultado->fetch_assoc()) { 
										?>
											<label>
												<?php 
													echo($linha_cargo['cargo']);
													if (strpos($linha_cargo['cargo'],'reinador')!==false) {
														?>
															<input onclick="toogle_treinador_campos();" value="<?php echo($linha_cargo['id_cargo']) ?>" type="checkbox" id="<?php echo($linha_cargo['id_cargo']); ?>" name="cargo[]">
														<?php
													}else{
														?>
															<input type="checkbox" value="<?php echo($linha_cargo['id_cargo']) ?>" id="<?php echo($linha_cargo['id_cargo']); ?>" name="cargo[]">
														<?php
													}
												?>
											</label><br>
										<?php
									}
								}else{
									//se tiver selecionado verifica quais os cargos daquela pessoa e faz check das mesmas.
									while ($linha_cargo=$resultado->fetch_assoc()) { ?>
										<label>
											<?php
												$cargo_recurso=$con->prepare("SELECT * FROM cargos_recursos WHERE id_cargo=? AND id_recurso_humano=?");
												$cargo_recurso->bind_param("ii",$linha_cargo['id_cargo'],$linha['id_recurso_humano']);
												$cargo_recurso->execute();
												$resultado_tabela=$cargo_recurso->get_result();
												
												echo($linha_cargo['cargo']);
												if($resultado_tabela->num_rows === 0){ 
													if (strpos($linha_cargo['cargo'],'reinador')!==false) {
														?>
															<input onclick="toogle_treinador_campos()" value="<?php echo($linha_cargo['id_cargo']) ?>" type="checkbox" id="<?php echo($linha_cargo['id_cargo']); ?>" name="cargo[]">
														<?php
													}else{
														?>
															<input type="checkbox" value="<?php echo($linha_cargo['id_cargo']) ?>" id="<?php echo($linha_cargo['id_cargo']); ?>" name="cargo[]">
														<?php
													}
												}else{
													if (strpos($linha_cargo['cargo'],'reinador')!==false) {	
														$is_treinador=1;											
														?>
															<input checked onclick="toogle_treinador_campos();" value="<?php echo($linha_cargo['id_cargo']) ?>" type="checkbox" id="<?php echo($linha_cargo['id_cargo']); ?>" name="cargo[]">
														<?php
													}else{
														?>
															<input checked type="checkbox" value="<?php echo($linha_cargo['id_cargo']) ?>" id="<?php echo($linha_cargo['id_cargo']); ?>" name="cargo[]">
														<?php
													}
												}
											?>
										</label><br>
									<?php
									}
								}	
							}
						?>
				</div>
				<?php 
					if (isset($is_treinador)) {
						$treinador=$con->prepare("SELECT * FROM `treinadores` WHERE id_treinador=?");
						$treinador->bind_param("i",$linha['id_recurso_humano']);
						$treinador->execute();
						$resultado_treinador=$treinador->get_result();
						$linha_treinador=$resultado_treinador->fetch_assoc();

						$ficheiros=$con->prepare("SELECT * FROM `ficheiros` WHERE id_recurso_humano=$_GET[id_colaborador]");
						$ficheiros->execute();
						$resultado=$ficheiros->get_result();
					}
				?>
				<div id="treinador_campos" style="display: none;">
					<div>
						<label>Numero de treinador:</label><input disabled value="T"><input hidden name="num_treinador[]" disabled class="disable" value="T"><input onkeypress="return sonumeros(event)" value="<?php if(isset($is_treinador)){$num=explode("T",$linha_treinador['num_treinador']);echo (end($num));} ?>" disabled class="disable required" name="num_treinador[]">
					</div>
					<div>
						<label>Palavra-passe:</label><input disabled class="disable required" name="password">
					</div>
					<div>
						<?php 
							if (isset($is_treinador)) {
								$ficheiros=$con->prepare("SELECT * FROM `ficheiros` WHERE id_recurso_humano=$_GET[id_colaborador]");
								$ficheiros->execute();
								$resultado=$ficheiros->get_result();
								while ($linha_ficheiro=$resultado->fetch_assoc()){
									if (strpos($linha_ficheiro['nome'],'Certificado_desportivo')!==false) {
										?>
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
								if (!isset($done)) {
									?>
										<label>Certificado_desportivo:</label>
										<input type="file" name="Certificado_desportivo" accept=".pdf,.doc">
									<?php
								}else{
									unset($done);
								}
							}else{
								?>
									<label>Certificado_desportivo:</label>
									<input type="file" name="Certificado_desportivo" accept=".pdf,.doc">
								<?php
							} 
						?>
					</div>
					<div>
						<label>Clubes anteriores:</label><textarea disabled class="disable" name="clubes_anteriores"><?php if (isset($is_treinador)) {echo $linha_treinador['clubles_anteriores'];} ?></textarea>
					</div>
				</div>
				<div>
					<label>Salario:</label>
						<input name="salario" onkeypress="return sonumeros(event)" value="<?php 
								if (isset($_GET['id_colaborador'])) {
									echo($linha['salario']);
								}elseif (isset($_POST['insert']) || isset($_POST['update'])){
									echo($_POST['salario']);
								} 
							?>">€<br>
				</div>
				<div>
					<label>Nome:</label>
						<input name="nome" onkeypress="return soletras(event)" value="<?php 
								if (isset($_GET['id_colaborador'])) {
									echo($linha['nome']);
								}elseif (isset($_POST['insert']) || isset($_POST['update'])){
									echo($_POST['nome']);
								} 
							?>"><br>			
				</div>
				<div>
					<label>CC:</label>
						<input name="cc" maxlength="9" onkeypress="return sonumeros(event)" value="<?php 
								if (isset($_GET['id_colaborador'])) {
									echo($linha['cc']);
								}elseif (isset($_POST['insert']) || isset($_POST['update'])){
									echo($_POST['cc']);
								} 
							?>"><br>
				</div>
				<div>
					<label>NIF:</label>
						<input name="nif" maxlength="9" onkeypress="return sonumeros(event)" value="<?php 
								if (isset($_GET['id_colaborador'])) {
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
						<?php 
							$thisyear=date('Y',strtotime('-17 years'));
						?>
						<input type="date" name="dt_nasc" max="<?php echo date('Y-m-d',mktime(00,00,00, 12, 31,$thisyear)); ?>" value="<?php 
								if (isset($_GET['id_colaborador'])) {
									echo($linha['dt_nasc']);
								}elseif (isset($_POST['insert']) || isset($_POST['update'])){
									echo($_POST['dt_nasc']);
								}else{
									echo date('Y-m-d',mktime(0,0,0, 12, 31,$thisyear));
								}
							?>"><br>
				</div>


	      	  </div>
	      	</div>

	      	<div class="card"style="margin-top: 30px">
	      	  <div class="card-header"> 
	      	    <h3 class="panel-title">Informações de Contacto</h3>
	      	  </div>
	      	  <div class="card-body">
	      	    
				<div>
					<label>Morada:</label>
						<input name="morada" onkeypress="return moradacheck(event)"  value="<?php 
								if (isset($_GET['id_colaborador'])) {
									echo($linha['morada']);
								}elseif (isset($_POST['insert']) || isset($_POST['update'])){
									echo($_POST['morada']);
								} 
							?>"><br>
				</div>
				<div>
					<label>Localidade:</label>
						<input name="localidade" onkeypress="return soletras(event)" value="<?php 
								if (isset($_GET['id_colaborador'])) {
									echo($linha['localidade']);
								}elseif (isset($_POST['insert']) || isset($_POST['update'])){
									echo($_POST['localidade']);
								} 
							?>"><br>
				</div>
				<div>
					<label>Freguesia:</label>
						<input name="freguesia" onkeypress="return soletras(event)" value="<?php 
								if (isset($_GET['id_colaborador'])) {
									echo($linha['freguesia']);
								}elseif (isset($_POST['insert']) || isset($_POST['update'])){
									echo($_POST['freguesia']);
								} 
							?>"><br>
				</div>
				<div>
					<label>Concelho:</label>
						<input name="concelho" onkeypress="return soletras(event)" value="<?php 
								if (isset($_GET['id_colaborador'])) {
									echo($linha['concelho']);
								}elseif (isset($_POST['insert']) || isset($_POST['update'])){
									echo($_POST['concelho']);
								} 
							?>"><br>
				</div>
				<div>
					<label>CP:</label>
						<input id="cp" name="cp" maxlength="8" onkeypress="return codigo_postalcheck(event)" value="<?php 
								if (isset($_GET['id_colaborador'])) {
									echo($linha['cp']);
								}elseif (isset($_POST['insert']) || isset($_POST['update'])){
									echo($_POST['cp']);
								} 
							?>"><br>
				</div>
				<div>
					<label>Email:</label>
						<input name="email" onkeypress="return emailcheck(event)" value="<?php 
								if (isset($_GET['id_colaborador'])) {
									echo($linha['email']);
								}elseif (isset($_POST['insert']) || isset($_POST['update'])){
									echo($_POST['email']);
								} 
							?>"><br>
				</div>
				<div>
					<label>Telemovel:</label>
						<input name="telemovel" maxlength="9" onkeypress="return sonumeros(event)" value="<?php 
								if (isset($_GET['id_colaborador'])) {
									echo($linha['telemovel']);
								}elseif (isset($_POST['insert']) || isset($_POST['update'])){
									echo($_POST['telemovel']);
								} 
							?>"><br>
				</div>

	      	  </div>
	      	</div>

	      	<div class="card"style="margin-top: 30px">
	      	  <div class="card-header"> 
	      	    <h3 class="panel-title">Ficheiros Relevantes</h3>
	      	  </div>
	      	  <div class="card-body">
	      	    
				<div>
					<?php 
						if (isset($_GET['id_colaborador'])) {
							$ficheiros=$con->prepare("SELECT * FROM `ficheiros` WHERE id_recurso_humano=$_GET[id_colaborador]");
							$ficheiros->execute();
							$resultado=$ficheiros->get_result();
							while ($linha_ficheiro=$resultado->fetch_assoc()){
								if (strpos($linha_ficheiro['nome'],'Registo_criminal')!==false) {
									?>
										<label>Atualizar registo criminal:</label>
										<input type="file" name="registo_criminal" accept=".pdf,.doc">
										<a href="download_ficheiro.php?id_ficheiro=<?php echo($linha_ficheiro['id_ficheiro']); ?>"> 
											<label>Download do registo criminal</label>
										</a>
									<?php	
									$done=1;
									break;
								}
							}
							if (!isset($done)) {
								?>
									<label>Registo criminal:</label>
									<input type="file" name="registo_criminal" accept=".pdf,.doc">
								<?php
							}else{
								unset($done);
							}
						}else{
							?>
								<label>Registo criminal:</label>
								<input type="file" name="registo_criminal" accept=".pdf,.doc">
							<?php
						} 
					?>
					<br>
				</div>
				<div>
					<?php 
						if (isset($_GET['id_colaborador'])) {
							$ficheiros=$con->prepare("SELECT * FROM `ficheiros` WHERE id_recurso_humano=$_GET[id_colaborador]");
							$ficheiros->execute();
							$resultado=$ficheiros->get_result();
							while ($linha_ficheiro=$resultado->fetch_assoc()){
								if (strpos($linha_ficheiro['nome'],'Certificado_academico')!==false) {
									?>
										<label>Atualizar certificado academico:</label>
										<input type="file" name="certificado_academico" accept=".pdf,.doc">
										<a href="download_ficheiro.php?id_ficheiro=<?php echo($linha_ficheiro['id_ficheiro']); ?>">  
											<label>Download do registo criminal</label>
										</a>
									<?php	
									$done=1;
									break;
								}
							}
							if (!isset($done)) {
								?>
									<label>Certificado academico:</label>
									<input type="file" name="certificado_academico" accept=".pdf,.doc">
								<?php
							}else{
								unset($done);
							}
						}else{
							?>
								<label>Certificado academico:</label>
								<input type="file" name="certificado_academico" accept=".pdf,.doc">
							<?php
						} 
					?>
					<br>
				</div>
				<div>
					<?php 
						if (isset($_GET['id_colaborador'])) {
							$ficheiros=$con->prepare("SELECT * FROM `ficheiros` WHERE id_recurso_humano=$_GET[id_colaborador]");
							$ficheiros->execute();
							$resultado=$ficheiros->get_result();
							while ($linha_ficheiro=$resultado->fetch_assoc()){
								if (strpos($linha_ficheiro['nome'],'Certificado_sbv_dae')!==false) {
									?>
										<label>Atualizar certificado sbv/dae:</label>
										<input type="file" name="certificado_sbv_dae" accept=".pdf,.doc">
										<a href="download_ficheiro.php?id_ficheiro=<?php echo($linha_ficheiro['id_ficheiro']); ?>">  
											<label>Download do certificado academico</label>
										</a>
									<?php	
									$done=1;
									break;
								}
							}
							if (!isset($done)) {
								?>
									<label>Certificado sbv/dae:</label>
									<input type="file" name="certificado_sbv_dae" accept=".pdf,.doc">
								<?php
							}else{
								unset($done);
							}
						}else{
							?>
								<label>Certificado sbv/dae:</label>
								<input type="file" name="certificado_sbv_dae" accept=".pdf,.doc">
							<?php
						} 
					?>
					<br>
				</div>

	      	  </div>
	      	</div>

	      </div>
	</div>
			<div class="d-flex justify-content-center">
				<div class="button_insert">
					<?php if (isset($_GET['id_colaborador'])) {?>
						<input type="submit" class="btn btn-default" name="update" value="Atualizar">
					<?php }else{?>
						<input type="submit" class="btn btn-default" name="insert" value="Inserir">
					<?php } ?>
				</div>
			</div>
			</form>
		</div>
	</body>
</html>

<!--Faz upload da foto para mostrar no site temporariamente-->
<script type="text/javascript">
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
</script>
<script type="text/javascript">
	function toogle_treinador_campos(){
		var inputs_required=document.getElementsByClassName("required");
		var inputs_disable=document.getElementsByClassName("disable");
		if (document.getElementById("treinador_campos").style.display=="none") {
			document.getElementById("treinador_campos").style.display="block";
			for (var i = inputs_disable.length - 1; i >= 0; i--) {
				inputs_disable[i].disabled=false;
			}
			for (var i = inputs_required.length - 1; i >= 0; i--) {
				inputs_required[i].required=true;
			}
		}else{
			document.getElementById("treinador_campos").style.display="none";	
			for (var i = inputs_disable.length - 1; i >= 0; i--) {
				inputs_disable[i].disabled=true;
			}
			for (var i = inputs_required.length - 1; i >= 0; i--) {
				inputs_required[i].required=false;
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

	$(document).ready(function () { 
        var $campo = $("#cp");
        $campo.mask('00000-000', {reverse: true});
    });
</script>
<?php
	if (!isset($_GET['id_colaborador'])) {
		?>
		<script>
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
		</script>
		<?php
	}else{
		if (isset($is_treinador)) {
			?><script type="text/javascript">toogle_treinador_campos()</script><?php
		}
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
