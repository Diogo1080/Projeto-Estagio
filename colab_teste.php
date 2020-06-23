<!-- Ligação á base de Dados -->
<?php require('ligacao.php'); ?> 

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
                                    <input type="text" class="form-control" name="nif">
                                </div>

                                <!-- Nif -->
                                <div class="form-group col-md-6">
                                    <label for="inputEmail4">Salário</label>
                                    
                                    <div class="input-group">
                                      <div class="input-group-prepend">
                                        <div class="input-group-text">€</div>
                                      </div>
                                      <input type="text" class="form-control">
                                    </div>

                                </div>

                              </div>

                            


                            <!-- Verifica se recebe email -->
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
            <h5 class="card-header">Cargos</h5>
            <div class="card-body">

              <div class="form-row">
                    <div class="form-group col-md-12">
                      <label for="inputPassword4">Tipo de Utilizador</label><br> 
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                        <label class="form-check-label" for="inlineCheckbox1">Médico</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2">
                        <label class="form-check-label" for="inlineCheckbox2">Treinador</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option3" disabled>
                        <label class="form-check-label" for="inlineCheckbox3">Secretaria</label>
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

                  <hr>

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
                    <input type="file" class="custom-file-input" id="inputGroupFile02">
                    <label class="custom-file-label" for="inputGroupFile02" aria-describedby="inputGroupFileAddon02">Choose file</label>
                  </div>
                </div><hr>
                <label for="exampleInputEmail1">Certificado Académico</label>
                <div class="input-group mb-3">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="inputGroupFile02">
                    <label class="custom-file-label" for="inputGroupFile02" aria-describedby="inputGroupFileAddon02">Choose file</label>
                  </div>
                </div><hr>
                <label for="exampleInputEmail1">Certificado SBE [?]</label>
                <div class="input-group mb-3">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="inputGroupFile02">
                    <label class="custom-file-label" for="inputGroupFile02" aria-describedby="inputGroupFileAddon02">Choose file</label>
                  </div>
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
</script>
<script type="text/javascript">
	function toogle_treinador_campos() {
		let inputs_required=document.getElementsByClassName("required")
		let  inputs_disable=document.getElementsByClassName("disable")
		if (document.getElementById("treinador_campos").style.display === "none") {
			document.getElementById("treinador_campos").style.display="block"
			for (i = inputs_disable.length - 1; i >= 0; i--) {
				inputs_disable[i].disabled = false
			}
			for (i = inputs_required.length - 1; i >= 0; i--) {
				inputs_required[i].required = true
			}
		}else{
			document.getElementById("treinador_campos").style.display="none";	
			for (i = inputs_disable.length - 1; i >= 0; i--) {
				inputs_disable[i].disabled = true
			}
			for (var i = inputs_required.length - 1; i >= 0; i--) {
				inputs_required[i].required = false
			}		
		}
	}

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
