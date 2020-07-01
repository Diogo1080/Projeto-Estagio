<!-- Ligação á base de Dados -->
<?php require('ligacao.php'); ?>

<html>

<!-- Ligação aos links e config da Head -->
<?php include('head.php'); ?>
<head>

<!-- CSS Dependencies para o color picker -->
<link rel="stylesheet" href="color-picker/build/1.2.3/css/pick-a-color-1.2.3.min.css">	  

<style type="text/css">		
        .pick-a-color-markup {
            margin:20px 0px;
        }
</style> 

<!-- JS Dependencies -->
<script src="color-picker/build/dependencies/jquery-1.9.1.min.js"></script>
<script src="color-picker/build/dependencies/tinycolor-0.9.15.min.js"></script>
<script src="color-picker/build/1.2.3/js/pick-a-color-1.2.3.min.js"></script>

</head>
<body>

	<div class="container">

        <!-- Conexão da navbar -->
	      <?php include('navbar_dashboard.php'); ?>

        <center style=" margin-top:25px;"><h1>Inserir Equipa</h1></center>

        <!-- Conteúdo da página -->
        <div class="card" style=" margin-top:25px;">

            <!-- Titulo + Botões  -->
            <div class="card-header">
                <h3 class="panel-title">Informações da Equipa</h3>
            </div>

            <!-- ID e Nome da Equipa -->
            <div class="card-body">

                <div class="form-row">
                    <div class="col-md-4">
                        <label>ID da Equipa</label>
                        <input type="text" class="form-control" placeholder="First name">
                    </div>
                    <div class="col-md-8">
                        <label>Nome da Equipa</label>
                        <input type="text" class="form-control" placeholder="Last name">
                    </div>
                </div>        


            <!-- Color Picker --> <hr>
            
            <input type="text" value="222" name="border-color" class="pick-a-color form-control">

            <!-- ID Escalão e Treinador --> <hr>
            <div class="form-row">
                    <div class="col-md-4">
                        <label>ID da Equipa</label>
                        <select class="custom-select custom-select-lg mb-3">
                            <option selected>Open this select menu</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
             
                    </div>
                    <div class="col-md-8">
                        <label>Associar Treinador</label>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">ID</th>
                                    <th scope="col">Nome</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">1</th>
                                    <td>Mark</td>
                                    <td>Otto</td>
                                </tr>
                                <tr>
                                    <th scope="row">2</th>
                                    <td>Jacob</td>
                                    <td>Thornton</td>
                                </tr>
                                <tr>
                                    <th scope="row">3</th>
                                    <td>@twitter</td>
                                    <td>@twitter</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
            </div> 

            </div>
        </div>

     
<!-- Script / Não sei o que faz, rezar que dê -->
<script type="text/javascript">
	
		$(document).ready(function () {

			$(".pick-a-color").pickAColor({
			  showSpectrum            : true,
				showSavedColors         : true,
				saveColorsPerElement    : true,
				fadeMenuToggle          : true,
				showAdvanced						: true,
				showBasicColors         : true,
				showHexInput            : true,
				allowBlank							: true,
				inlineDropdown					: true
			});
			
		});
	
</script>

</body>
</html>