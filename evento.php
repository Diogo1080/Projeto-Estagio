<?php
	require 'ligacao.php';
	if (isset($_GET['id'])) {
		$evento=$con->prepare("SELECT DISTINCT jogos.*,equipas.cor,equipas.id_equipa FROM jogos INNER JOIN equipa_convocados ON jogos.id_jogo=equipa_convocados.id_jogo INNER JOIN equipas ON equipa_convocados.id_equipa=equipas.id_equipa WHERE jogos.id_jogo=?");
		if (
			$evento->bind_param("i",$_GET['id'])&&
			$evento->execute()
		){
			$evento=$evento->get_result();
			$linha=$evento->fetch_assoc();
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<link href="https://unpkg.com/tabulator-tables@4.6.3/dist/css/tabulator.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/tabulator/4.6.3/css/bootstrap/tabulator_bootstrap4.min.css" rel="stylesheet">
	<script type="text/javascript" src="https://unpkg.com/tabulator-tables@4.6.3/dist/js/tabulator.min.js"></script>
</head>
<body>
	<div>
		<?php require 'nav.php'; ?>
	</div>
	<div>
		<div id="example-table"></div>
<script type="text/javascript">
	var tabledata = [
		<?php 
			//Busca o tempo do jogo
			$jogo=$con->prepare("SELECT escaloes.* FROM equipas INNER JOIN escaloes ON equipas.id_escalao=escaloes.id_escalao WHERE equipas.id_equipa=$linha[id_equipa]");
			$jogo->execute();
			$jogo=$jogo->get_result();
			$linha_jogo=$jogo->fetch_assoc();

			//Busca os atletas do jogo
			$atletas=$con->prepare("SELECT * FROM equipa_convocados WHERE id_jogo=$linha[id_jogo] AND id_equipa=$linha[id_equipa]");
			$atletas->execute();
			$atletas=$atletas->get_result();
			
			$linha_atletas=$atletas->fetch_assoc();
			echo '{id:'.$linha_atletas['id_atleta'].', name:"'.$linha_atletas['id_atleta'].'"},';
		?>
	];
	var table = new Tabulator("#example-table", {
	 	height:600,
	 	layout:"fitColumns",
	 	data:tabledata, //assign data to table
		keybindings:{
			"navLeft" : "shift + 37", 
			"navUp":"shift +38",
			"navRight":"shift + 39",
			"navDown":"shift + 40"
		},
	 	columns:[ //Define Table Columns
		 	{title:"Jogadores", field:"name", width:120 ,frozen:true},
		 	<?php 
				$minutos_passados=0;
				$num_intervalos=0;
				for ($i=1; $i <= $linha_jogo['duracao_jogos']; $i++) {
				$minutos_passados+=1;
				echo '{title:"'.$i.'", field:"minuto['.$i.']",width:10,headerSort:false,editor:"input"},';
				if ($minutos_passados==$linha_jogo['intercalacao']) {
					if ($num_intervalos<$linha_jogo['num_intervalos']) {
						$duracao_intervalo=explode("-", $linha_jogo['duracao_intervalo']);
						$duracao_intervalo=$duracao_intervalo[$num_intervalos];
						$num_intervalos+=1;
						echo '{title:"Intervalo ('.$duracao_intervalo.' min)", field:"Intervalo['.$num_intervalos.']",width:10,headerSort:false,headerVertical:true,editor:"input",},';
						$minutos_passados=0;
					}
				}
			}
			?>
	 	],
	 	cellEdited:function(e, row){
        	alert("Row " + row.getIndex() + " Clicked!!!!")
    	},
	});
</script>
</body>
</html>