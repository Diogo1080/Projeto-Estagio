<?php include("ligacao.php"); ?>
<html lang="en">
      <?php include('head.php'); ?>
  <body>
    <div class="container">
      <?php include('navbar_dashboard.php'); ?>
      <!-- Row 1 -->
      <div class="row" style="margin-top: 30px">
        <div class="col-sm-3">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title" style="text-align: center;">Atletas</h5>
            </div>
            <div class="card-body">
              <?php 
                $atletas=$con->prepare("SELECT COUNT(id_contribuinte) as total_atletas FROM contribuintes WHERE tipo_contribuinte='Atleta'");
                $atletas->execute();
                $resultado_atletas=$atletas->get_result();
                $linha_atletas=$resultado_atletas->fetch_assoc();
                $atletas->close();
              ?>
              <h1 class="card-text" style="text-align: center;color: #007EA7; "><?php echo $linha_atletas['total_atletas'];?></h1>
            </div>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title" style="text-align: center;">Colaboradores</h5>
            </div>
            <div class="card-body">
              <?php 
                $colaboradores=$con->prepare("SELECT COUNT(id_recurso_humano) as total_colaboradores FROM recursos_humanos");
                $colaboradores->execute();
                $resultado_colaboradores=$colaboradores->get_result();
                $linha_colaboradores=$resultado_colaboradores->fetch_assoc();
                $colaboradores->close();
              ?>
              <h1 class="card-text" style="text-align: center;color: #007EA7; "><?php echo $linha_colaboradores['total_colaboradores'];?></h1>
            </div>
          </div>
        </div>
         <div class="col-sm-3">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title" style="text-align: center;">Treinadores</h5>
            </div>
            <div class="card-body">
              <?php 
                $treinadores=$con->prepare("SELECT COUNT(recursos_humanos.id_recurso_humano) as total_treinadores FROM recursos_humanos INNER JOIN cargos_recursos ON recursos_humanos.id_recurso_humano=cargos_recursos.id_recurso_humano INNER JOIN cargos ON cargos_recursos.id_cargo=cargos.id_cargo WHERE is_treinador=1 ");
                $treinadores->execute();
                $resultado_treinadores=$treinadores->get_result();
                $linha_treinadores=$resultado_treinadores->fetch_assoc();
                $treinadores->close();
              ?>
              <h1 class="card-text" style="text-align: center;color: #007EA7; "><?php echo $linha_treinadores['total_treinadores']; ?></h1>
            </div>
          </div>
        </div>
         <div class="col-sm-3">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title" style="text-align: center;">Treinos Agendados</h5>
            </div>
            <div class="card-body">
              <?php 
                $treinos=$con->prepare("SELECT COUNT(id_treino) as total_treinos FROM treinos");
                $treinos->execute();
                $resultado_treinos=$treinos->get_result();
                $linha_treinos=$resultado_treinos->fetch_assoc();
                $treinos->close();
              ?>
              <h1 class="card-text" style="text-align: center;color: #007EA7; "><?php echo $linha_treinos['total_treinos']; ?></h1>
            </div>
          </div>
        </div>
      </div>
      <!-- Row 2 -->
      <div class="row" style="margin-top: 30px">
        <div class="col-sm-4">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title" style="text-align: center;">Quotas Servidas</h5>
            </div>
            <div class="card-body">
              <h1 class="card-text" style="text-align: center;color: #007EA7; ">1431</h1>
            </div>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title" style="text-align: center;"> Total Receitas Geradas</h5>
            </div>
            <div class="card-body">
              <h1 class="card-text" style="text-align: center;color: #007EA7; ">1431</h1>
            </div>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title" style="text-align: center;">Sócio mais Antigo</h5>
            </div>
            <div class="card-body">
              <h1 class="card-text" style="text-align: center;color: #007EA7; ">Zé Nárcio</h1>
            </div>
          </div>
        </div>

        <!-- Tables -->
        <div class="col-sm-12">
          <div class="card"style="margin-top: 30px">
            <div class="card-header">
              <h3 class="panel-title">Últimos 4 Atletas Inseridos</h3>
            </div>
            <div class="card-body">
              <table class="table table-hover table-bordered">
                <thead>
                  <tr>
                    <th scope="col">Nome</th>
                    <th scope="col">Sexo</th>
                    <th scope="col">E-mail</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $atletas=$con->prepare("SELECT * FROM contribuintes WHERE tipo_contribuinte='Atleta' ORDER BY id_contribuinte LIMIT 4");
                    $atletas->execute();
                    $resultado_atletas=$atletas->get_result();
                    if ($resultado_atletas->num_rows==0) {
                      ?>
                        <tr>
                          <td colspan="100%">Nenhum atleta inserido</td>
                        </tr>
                      <?php
                    }else{
                      while ($linha_atletas=$resultado_atletas->fetch_assoc()) {
                        ?>
                        <tr>
                          <td><?php echo $linha_atletas['nome']; ?></td>
                          <td><?php echo $linha_atletas['sexo']; ?></td>
                          <td><?php echo $linha_atletas['email']; ?></td>
                        </tr>
                        <?php
                      }
                    }
                    $atletas->close();
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
