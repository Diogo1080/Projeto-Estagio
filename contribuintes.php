<?php 
	require ('ligacao.php');

	if (isset($_POST['insert'])) {
		//prepara o insert do contribuinte
		$contribuintes=$con->prepare("INSERT INTO `contribuintes`(`num_socio`, `cc`, `nif`, `telemovel`, `telefone`, `cp`, `receber_email`, `tipo_contribuinte`, `morada`, `localidade`, `freguesia`, `concelho`, `nome`, `sexo`, `email`, `metodo_pagamento`, `dt_nasc`, `mensalidade_valor`, `foto`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

		//inicia variaveis "dummy" para a inserção de ficheiros(foto)
		$null=NULL;
		$foto=NULL;
		$foto_enc=NULL;
		print_r($_POST);

		//Busca variaveis
		if (isset($_POST['receber_email'])) {
			$receber_email=1;
		}else{
			$receber_email=0;
		}
		if (empty($_POST['metodo_pagamento'])) {
			$metodo_pagamento="No clube";
		}else{
			$metodo_pagamento=$_POST['metodo_pagamento'];
		}
		if ($_POST['tipo_contribuinte']=="Sócio") {
			$num_socio=$_POST['num_socio'];
		}else{	
			$num_socio=NULL;
		}
		// coloca as variveis nos placeholders(?) da querry em questao.
		$contribuintes->bind_param("iiiiiiissssssssssdb",$num_socio,$_POST['cc'],$_POST['nif'],$_POST['telemovel'],$_POST['telefone'],$_POST['cp'],$receber_email,$_POST['tipo_contribuinte'],$_POST['morada'],$_POST['localidade'],$_POST['freguesia'],$_POST['concelho'],$_POST['nome'],$_POST['sexo'],$_POST['email'],$_POST['metodo_pagamento'],$_POST['dt_nasc'],$mensalidade_valor,$foto);

		if (!empty($_POST['mensalidade_valor'])) {
			$mensalidade_valor=$_POST['mensalidade_valor'];
		}elseif(!empty($_POST['mensalidade_valor_atleta'])){
			$mensalidade_valor=$_POST['mensalidade_valor_atleta'];
		}else{
			$mensalidade_valor=NULL;
		}
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

		if ($_POST['tipo_contribuinte']=="Atleta") {
			if (isset($_POST['joia'])) {
				$joia=1;
			}else{
				$joia=0;
			}
			if (!empty($_POST['nome_enc'])) {
				if (isset($_POST['receber_email_enc'])) {
					$receber_email_enc=1;
				}else{
					$receber_email_enc=0;
				}
				// coloca as variveis nos placeholders(?) da querry em questao(Encarregado de educação).
				$contribuintes->bind_param("iiiiiiissssssssssdb",$null,$_POST['cc_enc'],$_POST['nif_enc'],$_POST['telemovel_enc'],$_POST['telefone_enc'],$_POST['cp_enc'],$receber_email_enc,$_POST['tipo_contribuinte_enc'],$_POST['morada_enc'],$_POST['localidade_enc'],$_POST['freguesia_enc'],$_POST['concelho_enc'],$_POST['nome_enc'],$_POST['sexo_enc'],$_POST['email_enc'],$null,$_POST['dt_nasc_enc'],$null,$foto_enc);
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
				//insere na tabela contribuintes
				$contribuintes->execute();
				//Busca o id do enc_edu
				$id_contribuinte_enc=$contribuintes->insert_id;
			}else{
				$id_contribuinte_enc=NULL;
			}
			//prepara a querry insert na tabela dos atletas
			$atletas=$con->prepare("INSERT INTO `atletas`(`id_contribuinte`, `id_enc_edu`, `valor_joia`, `joia`) VALUES (?,?,?,?)");
			// coloca as variveis nos placeholders(?) da querry em questao
			$atletas->bind_param("iiii",$id_contribuinte,$id_contribuinte_enc,$_POST['valor_joia'],$joia);
			//insere na tabela atletas
			$atletas->execute();
			$atletas->close();
		}
		if ($_POST['tipo_contribuinte']=="Encarregado de educação") {
			$atletas=$con->prepare("UPDATE `atletas` SET `id_enc_edu`=? WHERE `id_contribuinte`=?");
			$atletas->bind_param("ii",$id_contribuinte,$_POST['input_enc_edu_id']);
			$atletas->execute();
			$atletas->close();
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
		<!--Popup dos atletas-->
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
							<input name="tabela_atletas_procura" onkeyup="definir_procura(this.value);first_page();atualizar_tabela_popup(num_pagina,this.value);">
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
									<input class="input_atleta" name="mensalidade_valor_atleta" value="<?php 
										if (isset($_GET['id_contibuinte'])) {
											echo($linha['mensalidade_valor_atleta']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['mensalidade_valor_atleta']);
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
									<input id="insert_enc_edu" name="insert_enc_edu" type="checkbox" onchange="mostrar_inputs_enc_edu();">
							</div>
						</div>
					<!--Encarregado de educação-->
						<div id="container_enc_edu" style="display: none">
							<div>
								<label>Atleta deste encarregado de educação</label>
								<button type="button" onclick="mostrar_modal();atualizar_tabela_popup(1,'');">
									Selecionar
								</button>
								<button type="button" onclick="limpar_inputs_enc_edu();">limpar</button>
							</div>
							<input hidden id="input_enc_edu_id" name="input_enc_edu_id" class="readonly input_enc_edu" value="<?php 
										if (isset($_GET['id_contibuinte'])) {
											echo($linha['mensalidade_valor_atleta']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['mensalidade_valor_atleta']);
										} 
									?>">
							<div>
								<label>Nome do atleta selecionado:</label>
									<input required id="input_enc_edu_nome" name="input_enc_edu_nome" class="readonly input_enc_edu" value="<?php 
										if (isset($_GET['id_contibuinte'])) {
											echo($linha['mensalidade_valor_atleta']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['mensalidade_valor_atleta']);
										} 
									?>">
							</div>
							<div>
								<label>CC do atleta selecionado:</label>
									<input required id="input_enc_edu_cc" name="input_enc_edu_cc" class="readonly input_enc_edu" value="<?php 
										if (isset($_GET['id_contibuinte'])) {
											echo($linha['mensalidade_valor_atleta']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['mensalidade_valor_atleta']);
										} 
									?>">
							</div>
							<div>
								
							</div>
						</div>
					<!--Form principal-->
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
								<input required name="email" value="<?php 
										if (isset($_GET['id_contibuinte'])) {
											echo($linha['email']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['email']);
										} 
									?>"><br>
						</div>
						<div>
							<label>Telemovel:</label>
								<input required name="telefone" value="<?php 
										if (isset($_GET['id_contibuinte'])) {
											echo($linha['telemovel']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['telemovel']);
										} 
									?>"><br>
						</div>
						<div>
							<label>Telefone:</label>
								<input required name="telemovel" value="<?php 
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
					<!--Form secundario-->
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
											echo($linha['nome_enc']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['nome_enc']);
										} 
									?>"><br>			
						</div>
						<div>
							<label>CC:</label>
								<input class="input_enc" name="cc_enc" value="<?php 
										if (isset($_GET['id_contibuinte'])) {
											echo($linha['cc_enc']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['cc_enc']);
										} 
									?>"><br>
						</div>
						<div>
							<label>NIF:</label>
								<input class="input_enc" name="nif_enc" value="<?php 
										if (isset($_GET['id_contibuinte'])) {
											echo($linha['nif_enc']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['nif_enc']);
										} 
									?>"><br>
						</div>
						<div>
							<label>Morada:</label>
								<input class="input_enc" name="morada_enc" value="<?php 
										if (isset($_GET['id_contibuinte'])) {
											echo($linha['morada_enc']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['morada_enc']);
										} 
									?>"><br>
						</div>
						<div>
							<label>Localidade:</label>
								<input class="input_enc" name="localidade_enc" value="<?php 
										if (isset($_GET['id_contibuinte'])) {
											echo($linha['localidade_enc']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['localidade_enc']);
										} 
									?>"><br>
						</div>
						<div>
							<label>Freguesia:</label>
								<input class="input_enc" name="freguesia_enc" value="<?php 
										if (isset($_GET['id_contibuinte'])) {
											echo($linha['freguesia_enc']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['freguesia_enc']);
										} 
									?>"><br>
						</div>
						<div>
							<label>Concelho:</label>
								<input class="input_enc" name="concelho_enc" value="<?php 
										if (isset($_GET['id_contibuinte'])) {
											echo($linha['concelho_enc']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['concelho_enc']);
										} 
									?>"><br>
						</div>
						<div>
							<label>CP:</label>
								<input class="input_enc" name="cp_enc" value="<?php 
										if (isset($_GET['id_contibuinte'])) {
											echo($linha['cp_enc']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['cp_enc']);
										} 
									?>"><br>
						</div>
						<div>
							<label>Email:</label>
								<input class="input_enc" name="email_enc" value="<?php 
										if (isset($_GET['id_contibuinte'])) {
											echo($linha['email_enc']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['email_enc']);
										} 
									?>"><br>
						</div>
						<div>
							<label>Telemovel:</label>
								<input class="input_enc" name="telefone_enc" value="<?php 
										if (isset($_GET['id_contibuinte'])) {
											echo($linha['telemovel_enc']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['telemovel_enc']);
										} 
									?>"><br>
						</div>
						<div>
							<label>Telefone:</label>
								<input class="input_enc" name="telemovel_enc" value="<?php 
										if (isset($_GET['id_contibuinte'])) {
											echo($linha['telefone_enc']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['telefone_enc']);
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
											echo($linha['dt_nasc_enc']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['dt_nasc_enc']);
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
<script type="text/javascript">
	//controle popup
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
	//variaveis dos inputs
		var inputs_enc=document.getElementsByClassName("input_enc");
		var inputs_socio=document.getElementsByClassName("input_socio");
		var inputs_atleta=document.getElementsByClassName("input_atleta");
		var inputs_enc_edu=document.getElementsByClassName("readonly");
	//Readonly inputs com required
		$(".readonly").on('keydown paste', function(e){
			e.preventDefault();
		});

		function selecionar_atleta(id,nome,cc) {
			document.getElementById("input_enc_edu_id").value=id;
			document.getElementById("input_enc_edu_nome").value=nome;
			document.getElementById("input_enc_edu_cc").value=cc;
		}

		function limpar_inputs_enc_edu(){
			for (var i = inputs_enc_edu.length - 1; i >= 0; i--) {
				inputs_enc_edu[i].value="";
			}
		}

	function inputs(value,funcao){
		if (value=="enc_edu") {
			if (funcao=="1") {
				for (var i = inputs_enc_edu.length - 1; i >= 0; i--) {
					inputs_enc_edu[i].required=true;
					inputs_enc_edu[i].value="";
				}
			}else{
				for (var i = inputs_enc_edu.length - 1; i >= 0; i--) {
					inputs_enc_edu[i].required=false;
					inputs_enc_edu[i].value="";
				}
			}
		}
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
			if (funcao=="1") {
				for (var i = inputs_enc.length - 1; i >= 0; i--) {
					inputs_enc[i].required=true;
					inputs_enc[i].value="";
				}
			}else{
				for (var i = inputs_enc.length - 1; i >= 0; i--) {
					inputs_enc[i].required=false;
					inputs_enc[i].value="";
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
	//Mostra ou esconde o enc_edu
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
				if (document.getElementById('sexo_enc').value=="Masculino") {
					document.getElementById('foto_place_enc').src="fotos/Male_user.png"
				}else{
					document.getElementById('foto_place_enc').src="fotos/Female_user.png"
				}
			}
		}

	function mostrar_campos(tipo){
		if (tipo=="Sócio") {
			inputs("socio",1);
			inputs("enc",0);
			inputs("atleta",0);

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
	}elseif (isset($_POST['insert']) || isset($_POST['update'])){
		?>
			<script>
				
				if ("<?php echo $_POST['tipo_contribuinte'];?>"=="Sócio") {
					document.getElementById("tipo_contribuinte").selectedIndex=1;
					if ("<?php echo $_POST['metodo_pagamento'];?>"=="Domicilio") {
						document.getElementById("metodo_pagamento").selectedIndex=1;
					}else{
						document.getElementById("metodo_pagamento").selectedIndex=2;
					}
					document.getElementById("container_socio").style.display="block";
					document.getElementById("container_atleta").style.display="none";
					document.getElementById("container_enc_edu").style.display="none";
					document.getElementById("enc_edu_atleta").style.display="none";
				}
				if ("<?php echo $_POST['tipo_contribuinte'];?>"=="Atleta") {
					document.getElementById("tipo_contribuinte").selectedIndex=2;
					<?php if (isset($_POST['insert_enc_edu'])) { ?>
						document.getElementById("insert_enc_edu").checked = true;
						document.getElementById("enc_edu_atleta").style.display = "block";
					<?php }else{ ?>
						document.getElementById("insert_enc_edu").checked=false;
					<?php } ?>
					document.getElementById("container_socio").style.display="none";
					document.getElementById("container_atleta").style.display="block";
					document.getElementById("container_enc_edu").style.display="none";	
				}
				if ("<?php echo $_POST['tipo_contribuinte'];?>"=="Encarregado de educação") {
					document.getElementById("tipo_contribuinte").selectedIndex=3;
					document.getElementById("input_enc_edu_id").value="<?php echo($_POST['input_enc_edu_id']) ?>";
					document.getElementById("input_enc_edu_nome").value="<?php echo($_POST['input_enc_edu_nome']) ?>";
					document.getElementById("input_enc_edu_cc").value="<?php echo($_POST['input_enc_edu_cc']) ?>";
					document.getElementById("container_socio").style.display="none";
					document.getElementById("container_atleta").style.display="none";
					document.getElementById("container_enc_edu").style.display="block";				
				}
			</script>
		<?php
	}
?>