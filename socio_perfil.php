<!doctype html>
<?php
  require ('ligacao.php');

  if (isset($_GET['id_contribuinte'])) {
    //prepara o select do contribuinte, faz bind dos parametros, executa, busca os valores e coloca-os num array associativo
      $contibuintes_select=$con->prepare("SELECT * FROM contribuintes WHERE id_contribuinte=?");
      $contibuintes_select->bind_param("i",$_GET['id_contribuinte']);
      $contibuintes_select->execute();
      $resultado=$contibuintes_select->get_result();
      $linha=$resultado->fetch_assoc();

    //Check se é socio, atleta ou enc_edu
      if ($linha['tipo_contribuinte']=="Sócio") {
        $is_socio=1;
      }elseif ($linha['tipo_contribuinte']=="Atleta") {
        $is_atleta=1;
        //prepara o select do atleta, faz bind dos parametros, executa, busca os valores e coloca-os num array
          $contibuintes_select=$con->prepare("SELECT * FROM contribuintes INNER JOIN atletas ON contribuintes.id_contribuinte=atletas.id_contribuinte WHERE contribuintes.id_contribuinte=?");
          $contibuintes_select->bind_param("i",$_GET['id_contribuinte']);
          $contibuintes_select->execute();
          $resultado=$contibuintes_select->get_result();
          $linha=$resultado->fetch_assoc();
        //prepara o select do enc_edu do atleta, faz bind dos parametros, executa, busca os valores e coloca-os num array
          $enc_edu_atleta=$con->prepare("SELECT * FROM contribuintes WHERE id_contribuinte=?");
          $enc_edu_atleta->bind_param("i",$linha['id_enc_edu']);
          $enc_edu_atleta->execute();
          $resultado_atleta=$enc_edu_atleta->get_result();
          $linha_enc=$resultado_atleta->fetch_assoc();

      }elseif($linha['tipo_contribuinte']=="Encarregado de educação"){
        $is_enc_edu=1;
      }
  }
?>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Local CSS -->
    <link rel="stylesheet" href="socio.css">

    <title>Perfil</title>
  </head>
  <body>
    

    <!-- Container Geral -->

    <div class="container">

        <!-- Card -->
        <div class="card">

            <!-- Título do Card -->
            <div class="card-header text-center">
              <h4>Bem-Vindo <?php echo $_SESSION['nome']; ?></h4>
            </div>

            <!-- Corpo do Card -->
            <div class="card-body">
              <div class="row">
                <!--Imagem-->
                <div class="col-md-4">
                  <div class="form-group d-flex justify-content-center">
                    <img id="foto_place" src="<?php 
                        if (isset($_GET['id_contribuinte'])){
                          echo 'data:image/jpeg;base64,'.base64_encode($linha["foto"]);
                        }elseif (isset($_POST['insert']) or isset($_POST['update'])){
                          if($_POST['sexo']=='Masculino'){
                            echo("fotos/Male_user.png");
                          }else{
                            echo("fotos/Female_user.png");
                          }
                        }else{
                          echo"fotos/Male_user.png";
                        } 
                      ?>" alt="Foto do contribuinte" height="220" width="210"><br>
                  </div>
                  <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                      <label class="custom-file-label">Escolher a foto</label>
                        <input class="custom-file-input" type="file" id="foto" name="foto" accept="image/png, image/jpeg">
                    </div>
                  </div>
                </div>
                <!--Nome, CC, Nif, dt de nascimento, sexo, tipo e receber email.-->
                <div class="col-md-8">
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="nome">Nome</label>
                      <input type="text" required maxlength="60" onkeypress="return soletras(event)" class="form-control" id="nome" name="nome" value="<?php 
                        if (isset($_GET['id_contribuinte'])) {
                          echo($linha['nome']);
                        }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                          echo($_POST['nome']);
                        } 
                      ?>">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="cc">CC</label>
                      <input type="text" required maxlength="8" minlength="8" onkeypress="return sonumeros(event)" class="form-control" id="cc" name="cc" value="<?php 
                        if (isset($_GET['id_contribuinte'])) {
                          echo($linha['cc']);
                        }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                          echo($_POST['cc']);
                        } 
                      ?>">
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="nif">NIF</label>
                      <input type="text" required maxlength="9" minlength="9" onkeypress="return sonumeros(event)" class="form-control" id="nif" name="nif" value="<?php 
                        if (isset($_GET['id_contribuinte'])) {
                          echo($linha['nif']);
                        }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                          echo($_POST['nif']);
                        } 
                      ?>">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="dt_nasc">Data de Nascimento</label>
                      <input class="form-control" type="date" required id="dt_nasc" name="dt_nasc" value="<?php 
                        if (isset($_GET['id_contribuinte'])) {
                          echo($linha['dt_nasc']);
                        }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                          echo($_POST['dt_nasc']);
                        } 
                      ?>">
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="sexo">Sexo</label>
                      <select id="sexo" name="sexo" class="form-control" onchange="mudar_imagem()">
                        <option value="Masculino">Masculino</option>
                        <option value="Feminino">Feminino</option>
                      </select>
                    </div>
                  </div>
                    <div class="form-group">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="receber_email">
                        <label class="form-check-label" for="receber_email">
                          Receber e-Mails sobre o Clube
                        </label>
                      </div>
                    </div>


                </div>
              </div>

                  <!-- Divisão dos Cards -->
                  <hr>

        <div class="form-group">
          <label for="morada">Morada</label>
          <input type="text" class="form-control" placeholder="Insira a sua Morada" required name="morada" maxlength="60" onkeypress="return moradacheck(event)" value="<?php 
            if (isset($_GET['id_contribuinte'])) {
              echo($linha['morada']);
            }elseif (isset($_POST['insert']) || isset($_POST['update'])){
              echo($_POST['morada']);
            } 
          ?>">
        </div>
        <div class="form-group">
          <label for="localidade">Localidade</label>
          <input type="text" class="form-control" placeholder="Insira a sua Localidade" required name="localidade" maxlength="60" onkeypress="return soletras(event)" value="<?php 
            if (isset($_GET['id_contribuinte'])) {
              echo($linha['localidade']);
            }elseif (isset($_POST['insert']) || isset($_POST['update'])){
              echo($_POST['localidade']);
            } 
          ?>">
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="concelho">Concelho</label>
            <input type="text" class="form-control" placeholder="Insira o seu Concelho" required name="concelho" maxlength="60" onkeypress="return soletras(event)" value="<?php 
              if (isset($_GET['id_contribuinte'])) {
                echo($linha['concelho']);
              }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                echo($_POST['concelho']);
              } 
            ?>">
          </div>
          <div class="form-group col-md-4">
            <label for="freguesia">Freguesia</label>
            <input type="text" class="form-control" placeholder="Insira a sua Freguesia" required name="freguesia" maxlength="60" onkeypress="return soletras(event)" value="<?php 
              if (isset($_GET['id_contribuinte'])) {
                echo($linha['freguesia']);
              }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                echo($_POST['freguesia']);
              } 
            ?>">
          </div>
          <div class="form-group col-md-2">
            <label for="cp">Código-Postal</label>
            <input type="text" class="form-control" placeholder="0000-000" required id="cp" name="cp" minlength="8" maxlength="8" onkeypress="return sonumeros(event)" value="<?php 
              if (isset($_GET['id_contribuinte'])) {
                echo($linha['cp']);
              }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                echo($_POST['cp']);
              } 
            ?>">
          </div>
        </div>
        <hr>
        <div class="form-group">
          <label for="email">E-Mail</label>
          <input type="email" class="form-control" placeholder="Insira o seu E-Mail" required name="email" minlength="3" maxlength="60" type="email" value="<?php 
            if (isset($_GET['id_contribuinte'])) {
              echo($linha['email']);
            }elseif (isset($_POST['insert']) || isset($_POST['update'])){
              echo($_POST['email']);
            } 
          ?>">
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="telemovel">Telemóvel</label>
            <input class="form-control" required name="telefone" minlength="9" maxlength="9" onkeypress="return sonumeros(event)" value="<?php 
              if (isset($_GET['id_contribuinte'])) {
                echo($linha['telemovel']);
              }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                echo($_POST['telemovel']);
              } 
            ?>">
          </div>
          <div class="form-group col-md-6">
            <label for="telefone">Telefone</label>
            <input class="form-control" required name="telemovel" minlength="9" maxlength="9" onkeypress="return sonumeros(event)" value="<?php 
              if (isset($_GET['id_contribuinte'])) {
                echo($linha['telefone']);
              }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                echo($_POST['telefone']);
              } 
            ?>">
          </div>
        </div>
        <hr>
        <div class="d-flex justify-content-center" style="margin-bottom:25px; ">
                <button class="btn btn-default" type="button" onclick="window.location.href ='logout.php'">Log out</button>
        </div>

        <!-- Footer do Card -->
        <div class="card-footer text-center">
            © Estrela Azul FC - 2020
        </div>

        </div>

    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>
<?php 
  if ($linha['sexo']=="Masculino") { 
    ?><script type="text/javascript">document.getElementById("sexo").options.selectedIndex=0;</script><?php
  }else{
    ?><script type="text/javascript">document.getElementById("sexo").options.selectedIndex=1;</script><?php
  }
?>
      <script type="text/javascript">
        let todos_inputs=document.getElementsByTagName("input");
        for (var i = 0; i < todos_inputs.length; i++) {
          todos_inputs[i].disabled=true
        }
        todos_inputs=document.getElementsByTagName("select");
        for (var i = 0; i < todos_inputs.length; i++) {
          todos_inputs[i].disabled=true
        }
        todos_inputs=document.getElementsByTagName("textarea");
        for (var i = 0; i < todos_inputs.length; i++) {
          todos_inputs[i].disabled=true
        }

      </script>
