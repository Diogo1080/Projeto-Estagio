<!-- Ligação á base de Dados -->
<?php require('ligacao.php'); ?>

<html>

<!-- Ligação aos links e config da Head -->
<?php include('head.php'); ?>
<body>

	<div class="container">

        <!-- Conexão da navbar -->
	      <?php include('navbar_dashboard.php'); ?>

        <!-- <center style=" margin-top:25px;"><h1>Título da página</h1></center>   // Título Extra-->

        <!-- Conteúdo da página -->
        <div class="card" style=" margin-top:25px;">

            <!-- Titulo + Botões  -->
            <div class="card-header">
                <h3 class="panel-title">Qualquer Coisa</h3>
            </div>

            <!-- Tabelas / Forms / TUDO -->
            <div class="card-body">

                <h5 class="card-title">Conteudo aqui</h5>
                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                <a href="#" class="btn btn-default">Clicar wtf</a>


                <h5 class="card-title">Exemplo tabela</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">First</th>
                        <th scope="col">Last</th>
                        <th scope="col">Handle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <th scope="row">1</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                        </tr>
                        <tr>
                        <th scope="row">2</th>
                        <td>Jacob</td>
                        <td>Thornton</td>
                        <td>@fat</td>
                        </tr>
                        <tr>
                        <th scope="row">3</th>
                        <td colspan="2">Larry the Bird</td>
                        <td>@twitter</td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>

	      
</body>
</html>