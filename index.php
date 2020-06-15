<?php
	require ('ligacao.php');

	if(isset($_POST['Login'])){		
		//prepara a query para o login do admin
		$querry = $con->prepare("SELECT * FROM admins WHERE username = ?");
		//define a procura
		$querry -> bind_param("s",$_POST['username']);
		//executa a query
		$querry->execute();
		//Busca o resultado do select
		$resultado=$querry->get_result();
		//Verifica se tem alguma linha com os valores
		if($resultado->num_rows === 0){
			//Não: Sai e dá erro
			?>
				<script>
					window.alert("Nenhum username encontrado!");
					//window.location.href = "index.php";
				</script>
			<?php 
		}else{
			//Sim: Confirma a password 
			//Busca o conteudo da linha e coloca na var global $_SESSION	
			$linha=$resultado->fetch_assoc();
			if (password_verify($_POST['password'],$linha['password'])) {
				$_SESSION['id']=$linha['id_admin'];
				$_SESSION['nome']=$linha['username'];
				$_SESSION['permissao']=1;
				//redireciona para a home
				header("Location: dashboard.php");
			}else{
				//Password errada.
			?>
				<script>
					window.alert("Password errada!");
					//window.location.href = "index.php";
				</script>
			<?php 
			}
			//fecha a query
			$querry->close();
		}	
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Clube estrela azul</title>
</head>
<body>
	<div>
		<h3>Login</h3>
		<form method="POST">
			<label>Username:</label>
				<input name="username"><br>
			<label>Password:</label>
				<input type="Password" name="password"><br>
			<input type="submit" name="Login">
		</form>
	</div>
</body>
</html>