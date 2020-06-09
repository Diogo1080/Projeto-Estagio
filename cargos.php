<?php 
	//Prepara a ligação
		require ('ligacao.php');
	//Se um contribuinte estiver selecionado prepara os dados do mesmo
		if (isset($_GET['id_contribuinte'])) {
			//prepara o select do contribuinte
				$contibuintes_select=$con->prepare("SELECT contribuintes.* FROM contribuintes WHERE id_contribuinte=?");
			//Prepara os dados para o select
				$contibuintes_select->bind_param("i",$_GET['id_contribuinte']);
			//Executa a query
				$contibuintes_select->execute();
			//Busca os resultados
				$resultado=$contibuintes_select->get_result();
			//Coloca na variavel linha um array com os valores
				$linha=$resultado->fetch_assoc();
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
					if (isset($_POST['id_enc_edu'])) {
						$id_contribuinte_enc=$_POST['id_enc_edu'];
				//Se foi inserido tem de inserir na tabela contribuintes o enc_educação 
					}else{
						//Verifica se o enc_edu quer receber emails do clube
							if (isset($_POST['receber_email_enc'])) {
								$receber_email_enc=1;
							}else{
								$receber_email_enc=0;
							}

						//Prepara os dados para insert do enc_edu.
							$insert_contribuinte->bind_param("biiiiiiissssssssssd",$foto,$null,$_POST['cc_enc'],$_POST['nif_enc'],$_POST['telemovel_enc'],$_POST['telefone_enc'],$_POST['cp_enc'],$receber_email_enc,$_POST['tipo_contribuinte_enc'],$_POST['morada_enc'],$_POST['localidade_enc'],$_POST['freguesia_enc'],$_POST['concelho_enc'],$_POST['nome_enc'],$_POST['sexo_enc'],$_POST['email_enc'],$null,$_POST['dt_nasc_enc'],$null,);

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
				$update_atletas->bind_param("ii",$id_contribuinte,$_POST['input_enc_edu_id']);
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
					window.location.href="contribuintes.php?id_contribuinte=<?php echo $id_contribuinte ?>";
				</script>
			<?php
		}
	}

	if (isset($_POST['update'])) {
		//prepara o update do contribuinte
			$update_contribuintes=$con->prepare("UPDATE `contribuintes` SET `foto`=? `num_socio`=?,`cc`=?,`nif`=?,`telemovel`=?,`telefone`=?,`cp`=?,`receber_email`=?,`tipo_contribuinte`=?,`morada`=?,`localidade`=?,`freguesia`=?,`concelho`=?,`nome`=?,`sexo`=?,`email`=?,`metodo_pagamento`=?,`dt_nasc`=?,`mensalidade_valor`=? WHERE `id_contribuinte`=?");

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
				$update_contribuinte->bind_param("biiiiiiissssssssssd",$foto,$_POST['num_socio'],$_POST['cc'],$_POST['nif'],$_POST['telemovel'],$_POST['telefone'],$_POST['cp'],$receber_email,$_POST['tipo_contribuinte'],$_POST['morada'],$_POST['localidade'],$_POST['freguesia'],$_POST['concelho'],$_POST['nome'],$_POST['sexo'],$_POST['email'],$_POST['metodo_pagamento'],$_POST['dt_nasc'],$_POST['mensalidade_valor'],$_POST['id_contribuinte']);
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
			//Busca o id do sócio
				$id_contribuinte=$update_contribuinte->insert_id;
		}elseif($_POST['tipo_contribuinte']=="Atleta"){
			//prepara as variaveis
				$metodo_pagamento="No clube";		

			//Prepara os dados para update do Atleta.
				$update_contribuinte->bind_param("biiiiiiissssssssssd",$foto,$null,$_POST['cc'],$_POST['nif'],$_POST['telemovel'],$_POST['telefone'],$_POST['cp'],$receber_email,$_POST['tipo_contribuinte'],$_POST['morada'],$_POST['localidade'],$_POST['freguesia'],$_POST['concelho'],$_POST['nome'],$_POST['sexo'],$_POST['email'],$metodo_pagamento,$_POST['dt_nasc'],$_POST['mensalidade_valor_atleta']);

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

			//Busca o id do Atleta
				$id_contribuinte=$update_contribuinte->insert_id;

			//Verifica se o enc_educação foi selecionado/inserido
				if (isset($_POST['insert_enc_edu'])) {
				//Se foi selecionado só busca o id do enc_edu
					if (isset($_POST['id_enc_edu'])) {
						$id_contribuinte_enc=$_POST['id_enc_edu'];
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
							$insert_contribuinte->bind_param("biiiiiiissssssssssd",$foto,$null,$_POST['cc_enc'],$_POST['nif_enc'],$_POST['telemovel_enc'],$_POST['telefone_enc'],$_POST['cp_enc'],$receber_email_enc,$_POST['tipo_contribuinte_enc'],$_POST['morada_enc'],$_POST['localidade_enc'],$_POST['freguesia_enc'],$_POST['concelho_enc'],$_POST['nome_enc'],$_POST['sexo_enc'],$_POST['email_enc'],$null,$_POST['dt_nasc_enc'],$null,);

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
				$update_contribuinte->bind_param("biiiiiiissssssssssd",$foto,$null,$_POST['cc'],$_POST['nif'],$_POST['telemovel'],$_POST['telefone'],$_POST['cp'],$receber_email,$_POST['tipo_contribuinte'],$_POST['morada'],$_POST['localidade'],$_POST['freguesia'],$_POST['concelho'],$_POST['nome'],$_POST['sexo'],$_POST['email'],$null,$_POST['dt_nasc'],$null);
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
			//Prepara o update da tabela atletas
				$update_atletas=$con->prepare("UPDATE `atletas` SET `id_enc_edu`=? WHERE `id_contribuinte`=?");
			//Prepara os dados para insert do Enc_edu na tabela dos atletas.
				$update_atletas->bind_param("ii",$id_contribuinte,$_POST['input_enc_edu_id']);
			//Executa a query
				$update_atletas->execute();
			//Fecha a query
				$update_atletas->close();
		}

		if ($contribuintes_update->affected_rows<0) {
			?>
				<script type="text/javascript">
					alert("Ocurreu algo não esperado.");
				</script>
			<?php
		}elseif($contribuintes_update->affected_rows==0){
			?>
				<script type="text/javascript">
					alert("Não existe alteração do registo.");
					window.location.href="Contribuintes.php?id_contribuinte=<?php echo $id_contribuinte ?>"
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
		$contribuintes_update->close();
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
		<title>Cargos</title>
	</head>
	<body>
		<?php require ('nav.php'); ?>
		<div>
			<form method="POST" enctype="multipart/form-data">
				<!--form de inserir o enc_educação-->
				<div>
					<!--Form secundario-->
						<h1>Cargos</h1>
						<div>
							<label>Nome do cargo:</label>
								<input class="input_enc" name="nome_enc" value="<?php 
										if (isset($_GET['id_contribuinte'])) {
											echo($linha['nome_enc']);
										}elseif (isset($_POST['insert']) || isset($_POST['update'])){
											echo($_POST['nome_enc']);
										} 
									?>"><br>			
						</div>
				</div>
				<div>
					<?php if (isset($_GET['id_contribuinte'])) {?>
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
			inputs("enc_edu",0);
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
			inputs("enc_edu",0);

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
		echo  ($linha['tipo_contribuinte']);
		?>
		<script>
				if ("<?php echo ($linha['tipo_contribuinte']); ?>"=="Sócio"){
					document.getElementById("tipo_contribuinte").selectedIndex=1;
					if ("<?php echo $linha['metodo_pagamento'];?>"=="Domicilio") {
						document.getElementById("metodo_pagamento").selectedIndex=1;
					}else{
						document.getElementById("metodo_pagamento").selectedIndex=2;
					}
					document.getElementById("container_socio").style.display="block";
					document.getElementById("container_atleta").style.display="none";
					document.getElementById("container_enc_edu").style.display="none";
					document.getElementById("enc_edu_atleta").style.display="none";
				}
				if ("<?php echo ($linha['tipo_contribuinte']); ?>"=="Atleta") {
					document.getElementById("tipo_contribuinte").selectedIndex=2;
					document.getElementById("container_socio").style.display="none";
					document.getElementById("container_atleta").style.display="block";
					document.getElementById("container_enc_edu").style.display="none";
				}
				if ("<?php echo ($linha['tipo_contribuinte']); ?>"=="Encarregado de educação") {
					document.getElementById("tipo_contribuinte").selectedIndex=3;
					document.getElementById("container_socio").style.display="none";
					document.getElementById("container_atleta").style.display="none";
					document.getElementById("container_enc_edu").style.display="block";	
				}
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