<?php
	require 'ligacao.php';
	if (isset($_GET['id'])&&isset($_GET['tipo'])) {
		if ($_GET['tipo']=="Treino") {
			$evento=$con->prepare("SELECT DISTINCT treinos.*,equipas.cor,equipas.id_equipa FROM treinos INNER JOIN equipa_treinos ON treinos.id_treino=equipa_treinos.id_treino INNER JOIN equipas ON equipa_treinos.id_equipa=equipas.id_equipa WHERE treinos.id_treino=?");
			if (
				$evento->bind_param("i",$_GET['id'])&&
				$evento->execute()
			){
				$evento=$evento->get_result();
				$linha=$evento->fetch_assoc();
			}
		}else{
			$evento=$con->prepare("SELECT DISTINCT jogos.*,equipas.cor,equipas.id_equipa FROM jogos INNER JOIN equipa_convocados ON jogos.id_jogo=equipa_convocados.id_jogo INNER JOIN equipas ON equipa_convocados.id_equipa=equipas.id_equipa WHERE jogos.id_jogo=?");
			if (
				$evento->bind_param("i",$_GET['id'])&&
				$evento->execute()
			){
				$evento=$evento->get_result();
				$linha=$evento->fetch_assoc();
			}
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<div>
		<?php require 'nav.php'; ?>
	</div>
	<div>
		<form method="POST" >
			<div>
				<h3>
					<?php 
						if (isset($_GET['id'])&&isset($_GET['tipo'])) {
							if ($_GET['tipo']=="Treino") {
								echo "Treino: ";
							}else{
								echo "Jogo: ";
							}
						}
					?>
					<input hidden id="id" name="id" value="<?php 
						if (isset($_GET['id'])&&isset($_GET['tipo'])) {
								echo($_GET['id']);
						}
					?>">
					<input hidden id="tipo" name="tipo" value="<?php 
						if (isset($_GET['id'])&&isset($_GET['tipo'])) {
							if ($_GET['tipo']=="Treino") {
								echo "Treino";
							}else{
								echo "Jogo";
							}
						}
					?>">
					<input id="titulo" name="titulo" value="<?php 
						if (isset($_GET['id'])&&isset($_GET['tipo'])) {
							echo($linha['titulo']);
						}
					?>">
				</h3>
			</div>
			<div>
				
			</div>
		</form>
	</div>
</body>
</html>