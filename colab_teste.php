<!-- Ligação á base de Dados-->
<?php require('ligacao.php'); ?>

<html>

<!-- Ligação aos links e config da Head -->
<head>
  <?php include('head.php'); ?>
</head>
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
                <!-- Sexo -->
                <div class="form-group col-md-6">
                  <label for="inputPassword4">Sexo</label>
                  <select id="sexo" class="form-control" name="sexo" onchange="mudar_imagem()">
                    <option value="Masculino">Masculino</option>
                    <option value="Feminino">Feminino</option>
                  </select>
                </div>
                <!-- Data de Nascimento -->
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
              </div>
              <div class="form-row">
                <!-- Nif -->
                <div class="form-group col-md-6">
                    <label for="inputEmail4">NIF</label>
                    <input class="form-control" name="nif" maxlength="9" onkeypress="return sonumeros(event)" value="<?php 
                      if (isset($_GET['id_colaborador'])) {
                        echo($linha['nif']);
                      }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                        echo($_POST['nif']);
                      } 
                    ?>"><br>
                </div>

                <!-- Nif -->
                <div class="form-group col-md-6">
                    <label for="inputEmail4">Salário</label>
                    
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">€</div>
                      </div>
                      <input class="form-control" name="salario" onkeypress="return sonumeros(event)" value="<?php 
                        if (isset($_GET['id_colaborador'])) {
                          echo($linha['salario']);
                        }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                          echo($_POST['salario']);
                        } 
                      ?>">€<br>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>         
      </div>

      <div class="card" style=" margin-top:25px;">
        <h5 class="card-header">Cargos</h5>
        <div class="card-body">
          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="inputPassword4">Tipo de Utilizador</label><br> 
              <?php 
                //busca todos os cargos existentes na tabela cargos.
                $cargos = $con->prepare("SELECT * FROM cargos");
                $cargos->execute();
                $resultado=$cargos->get_result();
                if($resultado->num_rows === 0){ 
                  echo "Não existem cargos disponiveis.<br>";
                }else{
                  while ($linha_cargo=$resultado->fetch_assoc()) { 
                    if(!isset($_GET['id_colaborador'])){
                      ?>
                        <div class="form-check form-check-inline">
                          <label class="form-check-label" for="inlineCheckbox<?php echo($linha_cargo['id_cargo']); ?>">
                            <input class="form-check-input cargo <?php 
                              if ($linha_cargo['get_login']==1) {
                                echo"get_login ";
                              }
                              if ($linha_cargo['is_treinador']==1){
                                echo "is_treinador ";
                              }?>" onclick="<?php 
                              if ($linha_cargo['get_login']==1) {
                                echo"toogle_login_campos();";
                              }
                              if ($linha_cargo['is_treinador']==1){
                                echo "toogle_treinador_campos();";
                              }?>" value="<?php echo($linha_cargo['id_cargo']) ?>" type="checkbox" id="inlineCheckbox<?php echo($linha_cargo['id_cargo']); ?>" name="cargo[]">
                          <?php echo($linha_cargo['cargo']); ?>
                          </label>
                        </div>
                      <?php
                    }else{
                      $cargo_recurso=$con->prepare("SELECT * FROM cargos_recursos WHERE id_cargo=? AND id_recurso_humano=?");
                      $cargo_recurso->bind_param("ii",$linha_cargo['id_cargo'],$linha['id_recurso_humano']);
                      $cargo_recurso->execute();
                      $resultado_tabela=$cargo_recurso->get_result();
                      if($resultado_tabela->num_rows === 0){ 
                        ?>
                          <div class="form-check form-check-inline">
                            <label class="form-check-label" for="inlineCheckbox<?php echo($linha_cargo['id_cargo']); ?>">
                              <input class="form-check-input cargo <?php 
                                if ($linha_cargo['get_login']==1) {
                                  echo"get_login ";
                                }
                                if ($linha_cargo['is_treinador']==1){
                                  echo "is_treinador ";
                                }?>" onclick="<?php 
                                if ($linha_cargo['get_login']==1) {
                                  echo"toogle_login_campos();";
                                }
                                if ($linha_cargo['is_treinador']==1){
                                  echo "toogle_treinador_campos();";
                                }?>" value="<?php echo($linha_cargo['id_cargo']) ?>" type="checkbox" id="inlineCheckbox<?php echo($linha_cargo['id_cargo']); ?>" name="cargo[]">
                            <?php echo($linha_cargo['cargo']); ?></label>
                          </div>
                        <?php 
                      }else{
                        if ($linha_cargo['is_treinador']==1){
                          $is_treinador=1;
                        }
                        ?>
                          <div class="form-check form-check-inline">
                            <label class="form-check-label" for="inlineCheckbox<?php echo($linha_cargo['id_cargo']); ?>">
                              <input checked class="form-check-input cargo <?php 
                                if ($linha_cargo['get_login']==1) {
                                  echo"get_login ";
                                }
                                if ($linha_cargo['is_treinador']==1){
                                  echo "is_treinador ";
                                }?>" onclick="<?php 
                                if ($linha_cargo['get_login']==1) {
                                  echo"toogle_login_campos();";
                                }
                                if ($linha_cargo['is_treinador']==1){
                                  echo "toogle_treinador_campos();";
                                }?>" value="<?php echo($linha_cargo['id_cargo']) ?>" type="checkbox" id="inlineCheckbox<?php echo($linha_cargo['id_cargo']); ?>" name="cargo[]">
                              <?php echo($linha_cargo['cargo']); ?>
                            </label>
                          </div>
                        <?php 
                      }
                    }
                  }
                }
              ?>
            </div>
          </div>
        </div>
      </div>

      <div class="card" id="login_campos" style="margin-top:25px;display: none;">
        <h5 class="card-header">Campos de login</h5>
        <div class="card-body">
          <div>
            <?php if (isset($is_treinador)) {?>
              <label>Numero de treinador:</label>
              <input hidden name="num_recurso_humano[]" disabled class="input_login" value="T">
            <?php }else{ ?>
              <label>Numero de colaborador:</label>
              <input hidden name="num_recurso_humano[]" disabled class="input_login" value="C">
            <?php } ?>
              <input disabled value="T">
              
              <input onkeypress="return sonumeros(event)" value="<?php if(isset($is_treinador)){$num=explode("T",$linha['num_recurso_humano']);echo (end($num));} ?>" disabled class="input_login" name="num_recurso_humano[]">
          </div>
          <div>

          </div>
        </div>
      </div>

      <div class="card" id="treinador_campos" style="margin-top:25px;display: none;">
        <h5 class="card-header">Campos do treinador</h5>
        <div class="card-body">
          <div>
            <?php 
              if (isset($is_treinador)) {
                $treinador=$con->prepare("SELECT * FROM `treinadores` WHERE id_treinador=?");
                $treinador->bind_param("i",$linha['id_recurso_humano']);
                $treinador->execute();
                $resultado_treinador=$treinador->get_result();
                $linha_treinador=$resultado_treinador->fetch_assoc();

                $ficheiros=$con->prepare("SELECT * FROM `ficheiros` WHERE id_recurso_humano=$_GET[id_colaborador]");
                $ficheiros->execute();
                $resultado=$ficheiros->get_result();
                while ($linha_ficheiro=$resultado->fetch_assoc()){
                  if (strpos($linha_ficheiro['nome'],'Certificado_desportivo')!==false) {
                    ?>
                      <label>Atualizar certificado desportivo:</label>
                      <input type="file" name="Certificado_desportivo" accept=".pdf,.doc">
                      <a href="download_ficheiro.php?id_ficheiro=<?php echo($linha_ficheiro['id_ficheiro']); ?>"> 
                        <label>Download do registo criminal</label>
                      </a>
                    <?php 
                    $done=1;
                    break;
                  }
                }
                if (!isset($done)) {
                  ?>
                    <label>Certificado_desportivo:</label>
                    <input type="file" name="Certificado_desportivo" accept=".pdf,.doc">
                  <?php
                }else{
                  unset($done);
                }
              }else{
                ?>
                  <label>Certificado_desportivo:</label>
                  <input type="file" name="Certificado_desportivo" accept=".pdf,.doc">
                <?php
              } 
            ?>
          </div>
          <div>
            <label>Clubes anteriores:<textarea disabled class="input_treinador" name="clubes_anteriores"><?php if (isset($is_treinador)) {echo $linha_treinador['clubles_anteriores'];} ?></textarea></label>
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
            <!-- Concelho, Freguesia, Codigo Postal -->
            <div class="form-row">
              <!-- Concelho -->
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

            <!-- Email -->
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
                          
            <!-- Telemovel -->
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
                            
              <!-- Telefone -->
              <div class="form-group col-md-6">
                  <label for="inputPassword4">Telefone</label>
                    <input type="text" class="form-control" id="inputPassword4">
              </div>
          </div>        
        </div>
      </div>

      <div class="card" style=" margin-top:25px;">
        <h5 class="card-header">Ficheiros Relevantes</h5>
          <div class="card-body">
            <label for="exampleInputEmail1">Registo Criminal</label>
            <div class="input-group mb-3">
              <div class="custom-file">
                <?php 
                  if (isset($_GET['id_colaborador'])) {
                    $ficheiros=$con->prepare("SELECT * FROM `ficheiros` WHERE id_recurso_humano=$_GET[id_colaborador]");
                    $ficheiros->execute();
                    $resultado=$ficheiros->get_result();
                    while ($linha_ficheiro=$resultado->fetch_assoc()){
                      if (strpos($linha_ficheiro['nome'],'Registo_criminal')!==false) {
                        ?>
                          <label class="custom-file-label">Atualizar registo Criminal:</label>
                          <input class="custom-file-input" type="file" name="Registo_criminal" accept=".pdf,.doc">
                          </div>
                          <div class="input-group-append">
                            <span class="input-group-text" id="inputGroupFileAddon02">
                            <a href="download_ficheiro.php?id_ficheiro=<?php echo($linha_ficheiro['id_ficheiro']); ?>"> 
                                Download
                            </a>
                            </span>
                          </div>
                        <?php 
                        $done=1;
                        break;
                      }
                    }
                    if (!isset($done)) {
                      ?>
                        <label class="custom-file-label">Atualizar registo Criminal:</label>
                        <input class="custom-file-input" type="file" name="Registo_criminal" accept=".pdf,.doc">
                        </div>
                      <?php
                    }else{
                      unset($done);
                    }
                  }else{
                    ?>
                      <label class="custom-file-label">Atualizar registo Criminal:</label>
                      <input class="custom-file-input" type="file" name="Registo_criminal" accept=".pdf,.doc">
                      </div>
                    <?php
                  } 
                ?>
            </div>
            <hr>
            <label for="exampleInputEmail1">Certificado Académico</label>
            <div class="input-group mb-3">
              <div class="custom-file">
                <?php 
                  if (isset($_GET['id_colaborador'])) {
                    $ficheiros=$con->prepare("SELECT * FROM `ficheiros` WHERE id_recurso_humano=$_GET[id_colaborador]");
                    $ficheiros->execute();
                    $resultado=$ficheiros->get_result();
                    while ($linha_ficheiro=$resultado->fetch_assoc()){
                      if (strpos($linha_ficheiro['nome'],'Certificado_academico')!==false) {
                        ?>
                          <label class="custom-file-label">Atualizar certificado academico:</label>
                          <input class="custom-file-input" type="file" name="Certificado_academico" accept=".pdf,.doc">
                          </div>
                          <div class="input-group-append">
                            <span class="input-group-text" id="inputGroupFileAddon02">
                            <a href="download_ficheiro.php?id_ficheiro=<?php echo($linha_ficheiro['id_ficheiro']); ?>"> 
                                Download
                            </a>
                            </span>
                          </div>
                        <?php 
                        $done=1;
                        break;
                      }
                    }
                    if (!isset($done)) {
                      ?>
                         <label class="custom-file-label">Atualizar certificado academico:</label>
                        <input class="custom-file-input" type="file" name="Certificado_academico" accept=".pdf,.doc">
                        </div>
                      <?php
                    }else{
                      unset($done);
                    }
                  }else{
                    ?>
                       <label class="custom-file-label">Atualizar certificado academico:</label>
                      <input class="custom-file-input" type="file" name="Certificado_academico" accept=".pdf,.doc">
                      </div>
                    <?php
                  } 
                ?>
            </div>
            <label for="exampleInputEmail1">Certificado SBV DAE</label>
            <div class="input-group mb-3">
              <div class="custom-file">
                <?php 
                  if (isset($_GET['id_colaborador'])) {
                    $ficheiros=$con->prepare("SELECT * FROM `ficheiros` WHERE id_recurso_humano=$_GET[id_colaborador]");
                    $ficheiros->execute();
                    $resultado=$ficheiros->get_result();
                    while ($linha_ficheiro=$resultado->fetch_assoc()){
                      if (strpos($linha_ficheiro['nome'],'Certificado_sbv_dae')!==false) {
                        ?>
                          <label class="custom-file-label">Atualizar certificado sbv dae:</label>
                          <input class="custom-file-input" type="file" name="Certificado_sbv_dae" accept=".pdf,.doc">
                          </div>
                          <div class="input-group-append">
                            <span class="input-group-text" id="inputGroupFileAddon02">
                            <a href="download_ficheiro.php?id_ficheiro=<?php echo($linha_ficheiro['id_ficheiro']); ?>"> 
                                Download
                            </a>
                            </span>
                          </div>
                        <?php 
                        $done=1;
                        break;
                      }
                    }
                    if (!isset($done)) {
                      ?>
                        <label class="custom-file-label">Atualizar certificado sbv dae:</label>
                        <input class="custom-file-input" type="file" name="Certificado_sbv_dae" accept=".pdf,.doc">
                        </div>
                      <?php
                    }else{
                      unset($done);
                    }
                  }else{
                    ?>
                      <label class="custom-file-label">Atualizar certificado sbv dae:</label>
                      <input class="custom-file-input" type="file" name="Certificado_sbv_dae" accept=".pdf,.doc">
                      </div>
                    <?php
                  } 
                ?>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>    
</body>
</html>


<!-- Scripts e verificações / Diogo -->

<!--Faz upload da foto para mostrar no site temporariamente-->
<script type="text/javascript">
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader()

			reader.onload = (e) => {
				$('#foto_place').attr('src', e.target.result)
			}
			reader.readAsDataURL(input.files[0])
		}
	}

	$("#foto").change(() => {
		readURL(this)
	})

	let checkbox_get_login=document.getElementsByClassName("get_login")
  let inputs_get_login=document.getElementsByClassName("input_login")
  let is_get_login=0

	function toogle_login_campos() {
    for (var i = checkbox_get_login.length - 1; i >= 0; i--) {
      is_get_login=0
      if (checkbox_get_login[i].checked==true){
        is_get_login=1
        break
      }
    }

		if (is_get_login==1) {
			document.getElementById("login_campos").style.display="block"
			for (i = inputs_get_login.length - 1; i >= 0; i--) {
				inputs_get_login[i].disabled = false
			}
			for (i = inputs_get_login.length - 1; i >= 0; i--) {
				inputs_get_login[i].required = true
			}
		}else{
			document.getElementById("login_campos").style.display="none";	
			for (i = inputs_get_login.length - 1; i >= 0; i--) {
				inputs_get_login[i].disabled = true
			}
			for (var i = inputs_get_login.length - 1; i >= 0; i--) {
				inputs_get_login[i].required = false
			}		
		}
	}

  let checkbox_is_treinador=document.getElementsByClassName("is_treinador")
  let inputs_is_treinador=document.getElementsByClassName("input_treinador")
  let is_treinador=0

  function toogle_treinador_campos() {
    for (var i = checkbox_is_treinador.length - 1; i >= 0; i--) {
      is_treinador=0
      if (checkbox_is_treinador[i].checked==true){
        is_treinador=1
        break
      }
    }

    if (is_treinador==1) {
      document.getElementById("treinador_campos").style.display="block"
      for (i = inputs_is_treinador.length - 1; i >= 0; i--) {
        inputs_is_treinador[i].disabled = false
      }
      for (i = inputs_is_treinador.length - 1; i >= 0; i--) {
        inputs_is_treinador[i].required = true
      }
    }else{
      document.getElementById("treinador_campos").style.display="none"; 
      for (i = inputs_is_treinador.length - 1; i >= 0; i--) {
        inputs_is_treinador[i].disabled = true
      }
      for (var i = inputs_is_treinador.length - 1; i >= 0; i--) {
        inputs_is_treinador[i].required = false
      }   
    }
  }

  toogle_treinador_campos()
  toogle_login_campos()

	function sonumeros(e) {
        let charCode = e.charCode ? e.charCode : e.keyCode
        // charCode 8 = backspace   
        // charCode 9 = tab
        if (charCode !== 8 && charCode !== 9) {
            // charCode 48 equivale a 0   
            // charCode 57 equivale a 9
            if (charCode < 48 || charCode > 57) {
                return false
            }
        }
    }

	function soletras(evt){
		evt = (evt) ? evt : window.event
		var charCode = (evt.wich) ? evt.which: evt.keyCode
		return (charCode === 32) || (charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) || (charCode >= 192 && charCode <= 255)
	}

	function nomecheck(evt){
		//verifica se tem 9 digitos
		if (document.getElementById("nome").value.length === 40) {
			toastr.error('O nome só pode ter 40 caracteres')
			return false
		}
		
		let confirmar = soletras(evt)
		
		if (!confirmar) {
			toastr.error('O nome só pode conter letras')
			return false
		}
			return true
	};

	let isactive = false
	function emailcheck() {

		if (!(/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/.test(form_atleta.email.value))) {
			if (isactive === true) {
				toastr.clear()
				isactive = false
			}
			toastr.error('Endereço de email invalido')

		} else {

			if (!isactive) {
				toastr.clear()
				isactive = true
			}
			toastr.success('Endereço de email valido')

		}
	}

	function moradacheck(evt) {
		//verifica se tem 9 digitos
		if (document.getElementById("morada").value.length === 60) {
			toastr.error('A morada só pode ter 60 caracteres')
			return false
		}

		let confirmar = letras_numeros(evt)
		
		if (!confirmar) {
			toastr.error('A morada só pode conter letras numeros e caracteres como º')
			return false
		} else {
			return true
		}
	}

	function codigo_postalcheck(evt) {
		//verifica se tem 9 digitos
		if (document.getElementById("codigo_postal").value.length === 7) {
			toastr.error('O codigo postal só pode ter 7 caracteres')
			return false
		}

		let confirmar = sonumeros(evt)
		if (!confirmar) {
			toastr.error('O código postal só pode conter numeros')
			return false
		} else {
			return true
		}
	}

	function telemovelcheck(evt){
		//verifica se tem 9 digitos
		if (document.getElementById("telemovel").value.length === 9) {
			toastr.error('O número de telemóvel só pode ter 9 caracteres')
			return false
		}
		//verifica se é numero ou não
		let confirmar = sonumeros(evt)
		if (!confirmar) {
			toastr.error('O número de telemóvel só pode conter numeros')
			return false
		} else {
			return true
		}
	}

	$(document).ready(() => {
        let $campo = $("#cp")
        $campo.mask('0000-000', {reverse: true})
    })
</script>
<?php
	if (!isset($_GET['id_colaborador'])) {
		?>
		<script>
			//Função de escolher a imagem consuante o sexo
			function mudar_imagem(){
				if ((document.getElementById("foto").value === '')) {
					if (document.getElementById('sexo').value === "Masculino") {
						document.getElementById('foto_place').src="fotos/Male_user.png"
					} else {
						document.getElementById('foto_place').src="fotos/Female_user.png"
					}
				}
			}
		</script>
		<?php
	} else {
		if (isset($is_treinador)) {
			?><script type="text/javascript">toogle_treinador_campos()</script><?php
		}
		?>
			<script>
				//Escolher o sexo 
					if ("<?php echo ($linha['sexo']); ?>" === "Masculino") {
						document.getElementById("sexo").options.selectedIndex = 0
					}
					if ("<?php echo ($linha['sexo']); ?>" === "Feminino") {
						document.getElementById("sexo").options.selectedIndex = 1
					}
			</script>
		<?php 
	}
?>
