<?php 
	//Prepara a ligação
		require ('ligacao.php');
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
			<h1>Tabela</h1>
			<div>
				Procura: <input onkeyup="definir_procura(this.value);tabela_contribuintes(num_pagina,procura,tipo);">
				Tipo: 
				<select onchange="definir_tipo(this.value);tabela_contribuintes(num_pagina,procura,tipo);">
					<option></option>
					<option>Sócio</option>
					<option>Atleta</option>
					<option>Encarregado de educação</option>
				</select>
			</div>
			<div id="tabela_contribuintes"></div>
			<div>
				<button type="button" class="w3-btn page_btn" onclick="first_page();tabela_contribuintes(num_pagina,procura,tipo); ">
					<<
				</button>
				<button type="button" class="w3-btn page_btn" onclick="prev_page();tabela_contribuintes(num_pagina,procura,tipo);">
					<
				</button>
				<button type="button" class="w3-btn page_btn" onclick="next_page();tabela_contribuintes(num_pagina,procura,tipo);">
					>
				</button>
				<button type="button" class="w3-btn page_btn" onclick="last_page();tabela_contribuintes(num_pagina,procura,tipo);">
					>>
				</button>	
			</div>
		</div>
	</body>
</html>
<script type="text/javascript">
	var procura='';
	var num_pagina=1;
	var tipo='';

	function tabela_contribuintes(num_pagina,procura,tipo){
		$.post(
			'tabela_contribuintes.php', 
			{
				'num_pagina': num_pagina,
				'procura':procura,
				'tipo':tipo
			}, 
			function(response) {
				var resposta=response.split("«");
				total_num_paginas=resposta[0];
				$('#tabela_contribuintes').html(resposta[1]);
			}
		)
	}
	function definir_procura(value){
		procura=value;
	}
	function definir_tipo(value){
		tipo=value;
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

	tabela_contribuintes(num_pagina,procura,tipo);
</script>
