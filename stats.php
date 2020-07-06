<!-- Ligação á base de Dados -->
<?php require('ligacao.php'); ?>

<html>

<!-- Ligação aos links e config da Head -->
<?php include('head.php'); ?>
<body>

	<div class="container">

        <!-- Conexão da navbar -->
	      <?php include('navbar_dashboard.php'); ?>

		  <div class="col-md-6"> </div>


		<div class="card" style=" margin-top:25px;">
			<!-- Card - Socios  -->
			<div class="card-header">
				<a href="stats_socios.php"><h3 class="panel-title">Sócios</h3></a>
			</div>

			<!-- PEsquisa + Link geral -->
			<div class="card-body">



			</div>
		</div>


		<div class="card" style=" margin-top:25px;">
			<!-- Card - Socios  -->
			<div class="card-header">
				<a href="stats_socios.php"><h3 class="panel-title">Atletas</h3></a>
			</div>

			<!-- PEsquisa + Link geral -->
			<div class="card-body">


			</div>
		</div>

		<div class="card" style=" margin-top:25px;">
			<!-- Card - Socios  -->
			<div class="card-header">
				<h3 class="panel-title">Treinadores</h3>
			</div>

			<!-- PEsquisa + Link geral -->
			<div class="card-body">


			</div>
		</div>
	</div>       
</body>
</html>