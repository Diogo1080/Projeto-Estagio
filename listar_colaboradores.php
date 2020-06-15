<?php require('ligacao.php'); ?>
<html lang="en">
  <?php include('head.php'); ?>
  <body>
    <div class="container">
      <?php include('navbar_dashboard.php'); ?>

        <!-- Tables Of Products -->
        <div class="col-sm-12">
          <div class="card"style="margin-top: 30px">
            <div class="card-header"> 
                <div class="row">
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
                          <h3 class="panel-title">Lista de Colaboradores</h3>
                      </div>
                      <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6" align="right">
                          <a href="colaboradores.php" name="add" id="add_button" class="btn btn-default btn-xs" >Novo Colaborador</a>      
                      </div>
                  </div>
            </div>
            <div class="card-body">
              <table class="table table-hover table-bordered">
                <thead>
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Dados</th>
                    <th scope="col">Dados</th>
                    <th scope="col">Operações</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                    <td>@mdo</td>
                  </tr>
                  <tr>
                    <th scope="row">2</th>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                    <td>@mdo</td>
                  </tr>
                  <tr>
                    <th scope="row">3</th>
                    <td>Larry the Bird</td>
                    <td>@twitter</td>
                    <td>@mdo</td>
                    <td>@mdo</td>
                  </tr>
                </tbody>
              </table>
            </div>
        </div>
      </div>
    </div>
   

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  </body>
</html>