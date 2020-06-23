<!-- Ligação á base de Dados
<? php require('ligacao.php'); ?> -->

<html>

<!-- Ligação aos links e config da Head -->
<?php include('head.php'); ?>
<body>

<div class="container">
<?php include('navbar_dashboard.php'); ?>
<center style=" margin-top:25px;"><h1>Inserir Colaborador</h1></center> 

    <!-- Fetches -->
    <?php 
				if (isset($_GET['id_colaborador'])) {
					$recursos_humanos=$con->prepare("SELECT * FROM recursos_humanos WHERE id_recurso_humano=?");
					$recursos_humanos->bind_param("i",$_GET['id_colaborador']);
					$recursos_humanos->execute();
					$resultado=$recursos_humanos->get_result();
					$linha=$resultado->fetch_assoc();
				}
		?>
    <!-- Inicio do Form -->
    <form method="POST" enctype="multipart/form-data">
        <div class="card" style=" margin-top:25px;">
            <h5 class="card-header">Informações Básicas</h5>
            <div class="card-body">

                <div class="row">



                    <!-- Inserir Foto -->
                    <div class="col-md-4">
                    
                    <img id="foto_place" src="
                    <?php 
                      if (isset($_GET['id_colaborador'])){
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
                    ?>" alt="Foto do colaborador" height="200" width="200"><br>

                            <div class="form-group">
                              <label for="exampleFormControlFile1">Inserir Fotografia</label>
                              <input type="file" class="form-control-file" id="exampleFormControlFile1">
                            </div>
                            
                    </div>
                


                    <div class="col-md-8">
                            <div class="form-row">
                            <!-- Nome -->
                              <div class="form-group col-md-6">
                                <label for="inputEmail4">Nome</label>
                                <input type="email" class="form-control"name="nome" onkeypress="return soletras(event)" value="<?php 
                                  if (isset($_GET['id_colaborador'])) {
                                    echo($linha['nome']);
                                  }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                                    echo($_POST['nome']);
                                  } 
                                ?>">
                              </div>
                            <!-- Cartão de Cidadão -->
                              <div class="form-group col-md-6">
                                <label for="inputPassword4">CC</label>
                                <input type="text" class="form-control" name="cc" maxlength="9" onkeypress="return sonumeros(event)" value="<?php 
                                  if (isset($_GET['id_colaborador'])) {
                                    echo($linha['cc']);
                                  }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                                    echo($_POST['cc']);
                                  } 
                                ?>">
                              </div>
                            </div>
 
                            <div class="form-row">

                              <!-- Nif -->
                                <div class="form-group col-md-6">
                                  <label for="inputEmail4">NIF</label>
                                  <input type="text" class="form-control" name="nif" maxlength="9" onkeypress="return sonumeros(event)" value="<?php 
                                    if (isset($_GET['id_colaborador'])) {
                                      echo($linha['nif']);
                                    }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                                      echo($_POST['nif']);
                                    } 
                                  ?>">
                                </div>
                              <!-- Sexo -->
                                <div class="form-group col-md-6">
                                  <label for="inputPassword4">Sexo</label>
                                  <select id="sexo" class="form-control" name="sexo" onchange="mudar_imagem()">
                                    <option value="Masculino">Masculino</option>
                                    <option value="Feminino">Feminino</option>
                                  </select>
                                </div>
                              </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                  <label for="inputEmail4">Data de Nascimento</label>
                                  <input class="form-control" type="date" name="dt_nasc" max="<?php echo date('Y-m-d',mktime(00,00,00, 12, 31,$thisyear)); ?>" value="<?php
                                      if (isset($_GET['id_colaborador'])) {
                                          echo($linha['dt_nasc']);
                                      }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                                          echo($_POST['dt_nasc']);
                                      }else{
                                          echo date('Y-m-d',mktime(0,0,0, 12, 31,$thisyear));
                                      }
                                  ?>">
                                </div>
                                <div class="form-group col-md-6">
                                  <label for="inputPassword4">Tipo de Utilizador</label>
                                  <select class="form-control">
                                    <option>Default select</option>
                                  </select>
                                </div>
                              </div>

                            <div class="form-group">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="gridCheck">
                                <label class="form-check-label" for="gridCheck">
                                  Receber e-Mails sobre o Clube
                                </label>
                              </div>
                            </div>
                    </div>
                  </div>
            </div>         
        </div>

        <div class="card" style=" margin-top:25px;">
            <h5 class="card-header">Informações de Contacto</h5>
            <div class="card-body">
              <!-- Morada -->
                <div class="form-group">
                    <label for="inputAddress">Morada</label>
                    <input type="text" class="form-control" placeholder="Insira a sua Morada"name="morada" onkeypress="return moradacheck(event)"  value="<?php 
                      if (isset($_GET['id_colaborador'])) {
                        echo($linha['morada']);
                      }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                        echo($_POST['morada']);
                      } 
                    ?>">
                  </div>
              <!-- Localidade -->
                  <div class="form-group">
                    <label for="inputAddress2">Localidade</label>
                    <input type="text" class="form-control" placeholder="Insira a sua Localidade" name="localidade" onkeypress="return soletras(event)" value="<?php 
                      if (isset($_GET['id_colaborador'])) {
                        echo($linha['localidade']);
                      }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                        echo($_POST['localidade']);
                      } 
                    ?>">
                  </div>
              <!-- Concelho -->
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="inputCity">Concelho</label>
                      <input type="text" class="form-control" name="concelho" onkeypress="return soletras(event)" value="<?php 
                        if (isset($_GET['id_colaborador'])) {
                          echo($linha['concelho']);
                        }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                          echo($_POST['concelho']);
                        } 
                      ?>">
                    </div>
              <!-- Freguesia -->
                    <div class="form-group col-md-4">
                      <label for="inputState">Freguesia</label>
                      <input type="text" class="form-control" name="freguesia" onkeypress="return soletras(event)" value="<?php 
                        if (isset($_GET['id_colaborador'])) {
                          echo($linha['freguesia']);
                        }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                          echo($_POST['freguesia']);
                        } 
                      ?>">
                    </div>
              <!-- Codigo Postal -->
                    <div class="form-group col-md-2">
                      <label for="inputZip">Código-Postal</label>
                      <input type="text" class="form-control" id="cp" name="cp" maxlength="8" onkeypress="return codigo_postalcheck(event)" value="<?php 
                        if (isset($_GET['id_colaborador'])) {
                          echo($linha['cp']);
                        }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                          echo($_POST['cp']);
                        } 
                      ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputAddress2">E-Mail</label>
                    <input type="text" class="form-control" placeholder="Insira o seu E-Mail"name="email" onkeypress="return emailcheck(event)" value="<?php 
                      if (isset($_GET['id_colaborador'])) {
                        echo($linha['email']);
                      }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                        echo($_POST['email']);
                      } 
                    ?>">
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="inputEmail4">Telemóvel</label>
                      <input type="email" class="form-control" name="telemovel" maxlength="9" onkeypress="return sonumeros(event)" value="<?php 
                        if (isset($_GET['id_colaborador'])) {
                          echo($linha['telemovel']);
                        }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                          echo($_POST['telemovel']);
                        } 
                      ?>">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="inputPassword4">Telefone</label>
                      <input type="text" class="form-control" id="inputPassword4">
                    </div>
                  </div>
                </div>
            </div>

            <div class="card" style=" margin-top:25px;">
                <div class="card-header">
                  Featured
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <label for="exampleFormControlFile1">Ficheiro 1</label>
                        <input type="file" class="form-control-file" id="exampleFormControlFile1">
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlFile1">Ficheiro 2</label>
                        <input type="file" class="form-control-file" id="exampleFormControlFile1">
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlFile1">Ficheiro 3</label>
                        <input type="file" class="form-control-file" id="exampleFormControlFile1">
                    </div>

                </div>
              </div>

        </div>
    </form>
    </div>
	      
</body>
</html>