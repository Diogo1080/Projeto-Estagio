<?php
	session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset="utf-8">
		<title>Contato</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<script type="text/javascript">
			function validar_form_contato(){
				var nome = formcontato.nome.value;
				var email = formcontato.email.value;
				var assunto = formcontato.assunto.value;
				var mensagem = formcontato.mensagem.value;
				
				if(nome == ""){
					alert("Campo nome é obrigatorio");
					formcontato.nome.focus();
					return false;
				}if(email == ""){
					alert("Campo email é obrigatorio");
					formcontato.email.focus();
					return false;
				}if(assunto == ""){
					alert("Campo assunto é obrigatorio");
					formcontato.assunto.focus();
					return false;
				}if(mensagem == ""){
					alert("Campo mensagem é obrigatorio");
					formcontato.mensagem.focus();
					return false;
				}
			}
		</script>
	<head>
	<body>
		<div class="container theme-showcase" role="main">
			<div class="page-header">
				<h1>Pagina de Contacto</h1>
			</div>
			<form class="form-horizontal" name="formcontato" method="POST" action="salva_mensagem.php">
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">
						Nome: 
					</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="nome" placeholder="Nome Completo" 
						<?php
							if(!empty($_SESSION['value_nome'])){
								echo "value='".$_SESSION['value_nome']."'";
								unset($_SESSION['value_nome']);
							}
						 ?>	>
						 <?php
							if(!empty($_SESSION['vazio_nome'])){
								echo "<p style='color: #f00; '>".$_SESSION['vazio_nome']."</p>";
								unset($_SESSION['vazio_nome']);
							}
						 ?>
					</div>
				</div>
				
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">
						Email: 
					</label>
					<div class="col-sm-10">
						 <input type="email" class="form-control" name="email" placeholder="Seu melho e-mail" 
						<?php
							if(!empty($_SESSION['value_email'])){
								echo "value='".$_SESSION['value_email']."'";
								unset($_SESSION['value_email']);
							}
						 ?>>
						 <?php
							if(!empty($_SESSION['vazio_email'])){
								echo "<p style='color: #f00; '>".$_SESSION['vazio_email']."</p>";
								unset($_SESSION['vazio_email']);
							}
						 ?>
					</div>
				</div>
				
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">
						Assunto: 
					</label>
					<div class="col-sm-10">
						 <input type="text" class="form-control" name="assunto" placeholder="Assunto do contato" 
						<?php
							if(!empty($_SESSION['value_assunto'])){
								echo "value='".$_SESSION['value_assunto']."'";
								unset($_SESSION['value_assunto']);
							}
						 ?>>
						 <?php
							if(!empty($_SESSION['vazio_assunto'])){
								echo "<p style='color: #f00; '>".$_SESSION['vazio_assunto']."</p>";
								unset($_SESSION['vazio_assunto']);
							}
						 ?>
					</div>
				</div>
				
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-2 control-label">
						Mensagem: 
					</label>
					<div class="col-sm-10">
						<?php
							if(!empty($_SESSION['value_mensagem'])){ ?>
								<textarea class="form-control" name="mensagem"><?php echo $_SESSION['value_mensagem']; ?></textarea>
								<?php
								unset($_SESSION['value_mensagem']);
							}else{ ?>
								<textarea class="form-control" name="mensagem"></textarea>
							<?php }
						?>
						 <?php
							if(!empty($_SESSION['vazio_mensagem'])){
								echo "<p style='color: #f00; '>".$_SESSION['vazio_mensagem']."</p>";
								unset($_SESSION['vazio_mensagem']);
							}
						 ?>
					</div>
				</div>
				
				<input class="btn btn-success" type="submit" value="Enviar" onclick="return validar_form_contato()">
			</form>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>