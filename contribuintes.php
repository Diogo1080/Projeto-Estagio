<?php 
  //Prepara a ligação
    require ('ligacao.php');

    if ($_SESSION['permissao']==2) {
      if (!isset($_GET['id_contribuinte'])) {
       ?>
       <script type="text/javascript">
          window.location.href="listar_contribuintes.php"       
        </script>
       <?php
      }
    }
    unset($_SESSION['array_atletas']);
  //Se um contribuinte estiver selecionado prepara os dados do mesmo
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

  if (isset($_POST['insert'])) {
    print_r($_POST['tipo_contribuinte']);
    //prepara o insert do contribuinte
      $insert_contribuinte=$con->prepare("INSERT INTO `contribuintes`(`foto`, `num_socio`, `password`,`cc`, `nif`, `telemovel`, `telefone`, `cp`, `receber_email`, `tipo_contribuinte`, `morada`, `localidade`, `freguesia`, `concelho`, `nome`, `sexo`, `email`, `metodo_pagamento`, `dt_nasc`, `mensalidade_valor`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

    //inicia variaveis "dummy" para a inserção de ficheiros(foto)
      $null=NULL;
      $foto=NULL;

    //Busca variaveis
      if (isset($_POST['receber_email'])) {
        $receber_email=1;
      }else{
        $receber_email=0;
      }  
      $cp=explode('-',$_POST['cp']);
      $cp=$cp[0].$cp[1];
    
    //Se for socio
      if ($_POST['tipo_contribuinte']=="Sócio") {

        $hashed_password=NULL;
        $num_socio=NULL;

        if (isset($_POST['num_socio'][1])) {
          $num_socio=$_POST['num_socio'][0].$_POST['num_socio'][1];
        }

        if (isset($_POST['password'])) {
          if (!empty($_POST['password'])) {
            $hashed_password=password_hash($_POST['password'], PASSWORD_DEFAULT);
          }else{
            $hashed_password=password_hash('1234', PASSWORD_DEFAULT);
          }
        }

        //Prepara os dados para insert do Sócio.
          $insert_contribuinte->bind_param("bssiiiiiissssssssssd",$foto,$num_socio,$hashed_password,$_POST['cc'],$_POST['nif'],$_POST['telemovel'],$_POST['telefone'],$cp,$receber_email,$_POST['tipo_contribuinte'],$_POST['morada'],$_POST['localidade'],$_POST['freguesia'],$_POST['concelho'],$_POST['nome'],$_POST['sexo'],$_POST['email'],$_POST['metodo_pagamento'],$_POST['dt_nasc'],$_POST['mensalidade_valor']);
        //Verifica se tem foto se sim coloca a selecionada
        //Se nao coloca-a pelo sexo.
          if (is_uploaded_file($_FILES["foto"]["tmp_name"])){
            $insert_contribuinte->send_long_data(0,file_get_contents($_FILES["foto"]["tmp_name"]));
          }else{
            if ($_POST['sexo']=="Masculino") {
              $insert_contribuinte->send_long_data(0,file_get_contents("fotos/Male_user.png"));
            }else{
              $insert_contribuinte->send_long_data(0,file_get_contents("fotos/Female_user.png"));
            }
          }

        //Executa a query.
          $insert_contribuinte->execute();
        //Busca o id do sócio
          $id_contribuinte=$insert_contribuinte->insert_id;
    //Se for atleta
      }elseif($_POST['tipo_contribuinte']=="Atleta"){    
        //prepara as variaveis
          $metodo_pagamento="No clube";   
        //Prepara os dados para insert do Atleta.
          $insert_contribuinte->bind_param("bssiiiiiissssssssssd",$foto,$null,$null,$_POST['cc'],$_POST['nif'],$_POST['telemovel'],$_POST['telefone'],$cp,$receber_email,$_POST['tipo_contribuinte'],$_POST['morada'],$_POST['localidade'],$_POST['freguesia'],$_POST['concelho'],$_POST['nome'],$_POST['sexo'],$_POST['email'],$metodo_pagamento,$_POST['dt_nasc'],$_POST['mensalidade_valor_atleta']);
        //Verifica se tem foto se sim coloca a selecionada
        //Se nao coloca-a pelo sexo.
          if (is_uploaded_file($_FILES["foto"]["tmp_name"])){
            $insert_contribuinte->send_long_data(0,file_get_contents($_FILES["foto"]["tmp_name"]));
          }else{
            if ($_POST['sexo']=="Masculino") {
              $insert_contribuinte->send_long_data(0,file_get_contents("fotos/Male_user.png"));
            }else{
              $insert_contribuinte->send_long_data(0,file_get_contents("fotos/Female_user.png"));
            }
          }

        //Executa a query
          $insert_contribuinte->execute();

        //Busca o id do Atleta
          $id_contribuinte=$insert_contribuinte->insert_id;

        //Verifica se o enc_educação foi selecionado/inserido
          if (isset($_POST['insert_enc_edu'])) {
          //Se foi selecionado só busca o id do enc_edu
            if (!empty($_POST['id_enc'])) {
              $id_contribuinte_enc=$_POST['id_enc'];
          //Se foi inserido tem de inserir na tabela contribuintes o enc_educação 
            }else{
                $cp_enc=explode('-',$_POST['cp_enc']);
                $cp_enc=$cp_enc[0].$cp_enc[1];
              //Verifica se o enc_edu quer receber emails do clube
                if (isset($_POST['receber_email_enc'])) {
                  $receber_email_enc=1;
                }else{
                  $receber_email_enc=0;
                }

              //Prepara os dados para insert do enc_edu.
                $insert_contribuinte->bind_param("bssiiiiiissssssssssd",$foto,$null,$null,$_POST['cc_enc'],$_POST['nif_enc'],$_POST['telemovel_enc'],$_POST['telefone_enc'],$cp_enc,$receber_email_enc,$_POST['tipo_enc'],$_POST['morada_enc'],$_POST['localidade_enc'],$_POST['freguesia_enc'],$_POST['concelho_enc'],$_POST['nome_enc'],$_POST['sexo_enc'],$_POST['email_enc'],$null,$_POST['dt_nasc_enc'],$null,);
              //Verifica se tem foto se sim coloca a selecionada
              //Se nao coloca-a pelo sexo.
                if (is_uploaded_file($_FILES["foto_enc"]["tmp_name"])){
                  $insert_contribuinte->send_long_data(0,file_get_contents($_FILES["foto_enc"]["tmp_name"]));
                }else{
                  if ($_POST['sexo']=="Masculino") {
                    $insert_contribuinte->send_long_data(0,file_get_contents("fotos/Male_user.png"));
                  }else{
                    $insert_contribuinte->send_long_data(0,file_get_contents("fotos/Female_user.png"));
                  }
                }

              //Executa a query
                $insert_contribuinte->execute();

              //Busca o id do enc_edu
                $id_contribuinte_enc=$insert_contribuinte->insert_id;
            }
        //Se nao foi inserido o id do enc_edu, o mesmo é Null.
          }else{
            $id_contribuinte_enc=NULL;
          }

        //Verifica se a joia foi paga ou nao
          if (isset($_POST['joia'])) {
            $joia=1;
          }else{
            $joia=0;
          }

        //prepara o insert do atleta
          $atletas_insert=$con->prepare("INSERT INTO `atletas`(`id_contribuinte`, `id_enc_edu`, `valor_joia`, `joia`) VALUES (?,?,?,?)");

        //Prepara os dados para insert do Atleta.
          $atletas_insert->bind_param("iiii",$id_contribuinte,$id_contribuinte_enc,$_POST['valor_joia'],$joia);
        //Executa a query
          $atletas_insert->execute();
        //Fecha a query
          $atletas_insert->close();
    //Se for enc_edu
      }else{
        //Prepara os dados para insert do Enc_edu.
          $insert_contribuinte->bind_param("bssiiiiiissssssssssd",$foto,$null,$null,$cc,$_POST['nif'],$_POST['telemovel'],$_POST['telefone'],$_POST['cp'],$receber_email,$_POST['tipo_contribuinte'],$_POST['morada'],$_POST['localidade'],$_POST['freguesia'],$_POST['concelho'],$_POST['nome'],$_POST['sexo'],$_POST['email'],$null,$_POST['dt_nasc'],$null);
        //Verifica se tem foto se sim coloca a selecionada
        //Se nao coloca-a pelo sexo.
          if (is_uploaded_file($_FILES["foto"]["tmp_name"])){
            $insert_contribuinte->send_long_data(0,file_get_contents($_FILES["foto"]["tmp_name"]));
          }else{
            if ($_POST['sexo']=="Masculino") {
              $insert_contribuinte->send_long_data(0,file_get_contents("fotos/Male_user.png"));
            }else{
              $insert_contribuinte->send_long_data(0,file_get_contents("fotos/Female_user.png"));
            }
          }
        //Executa a query.
          $insert_contribuinte->execute();
        //Busca o id do enc_edu
          $id_contribuinte_enc=$insert_contribuinte->insert_id;
        //Prepara o update da tabela atletas
          $update_atletas=$con->prepare("UPDATE `atletas` SET `id_enc_edu`=? WHERE `id_contribuinte`=?");
        //Prepara os dados para insert do Enc_edu na tabela dos atletas.
          $update_atletas->bind_param("ii",$id_contribuinte,$_POST['required_edu_id']);
        //Executa a query
          $update_atletas->execute();
        //Fecha a query
          $update_atletas->close();
      }

    //Confirma se a query funcionou
      if ($insert_contribuinte->affected_rows<1) {
        $insert_contribuinte->close();
        ?>
          <script type="text/javascript">
            alert("Ocurreu algo não esperado.");
          </script>
        <?php
      }else{
        $insert_contribuinte->close();
        ?>
          <script type="text/javascript">
            alert("Dados inseridos com sucesso.");
            window.location.href="contribuintes.php?id_contribuinte=<?php echo $id_contribuinte ?>";
          </script>
        <?php
      }
  }

  if (isset($_POST['update'])) {
    //Busca o id do contribuinte
      $id_contribuinte=$_GET['id_contribuinte'];
    //prepara o update do contribuinte
      $update_contribuinte=$con->prepare("UPDATE `contribuintes` SET `foto`=?,`num_socio`=?,`password`=?,`cc`=?,`nif`=?,`telemovel`=?,`telefone`=?,`cp`=?,`receber_email`=?,`tipo_contribuinte`=?,`morada`=?,`localidade`=?,`freguesia`=?,`concelho`=?,`nome`=?,`sexo`=?,`email`=?,`metodo_pagamento`=?,`dt_nasc`=?,`mensalidade_valor`=? WHERE `id_contribuinte`=?");

    //inicia variaveis "dummy" para a inserção de ficheiros(foto)
      $null=NULL;
      $foto=NULL;

    //Busca variaveis
      if (isset($_POST['receber_email'])) {
        $receber_email=1;
      }else{
        $receber_email=0;
      }

      $cp=explode('-',$_POST['cp']);
      $cp=$cp[0].$cp[1];
    
    //Para cada tipo de contribuinte faz algo diferente:
      if ($_POST['tipo_contribuinte']=="Sócio") {
        
        $hashed_password=NULL;
        $num_socio=NULL;
        
        if (isset($_POST['num_socio'][1])) {
          $num_socio=$_POST['num_socio'][0].$_POST['num_socio'][1];
        }
        
        if (isset($_POST['password'])) {
          if (!empty($_POST['password'])) {
            $hashed_password=password_hash($_POST['password'], PASSWORD_DEFAULT);
            //Prepara os dados para update do Sócio.
              $update_contribuinte->bind_param("bssiiiiiissssssssssdi",$foto,$num_socio,$hashed_password,$_POST['cc'],$_POST['nif'],$_POST['telemovel'],$_POST['telefone'],$cp,$receber_email,$_POST['tipo_contribuinte'],$_POST['morada'],$_POST['localidade'],$_POST['freguesia'],$_POST['concelho'],$_POST['nome'],$_POST['sexo'],$_POST['email'],$_POST['metodo_pagamento'],$_POST['dt_nasc'],$_POST['mensalidade_valor'],$_POST['id_contribuinte']);
          }else{
            $update_contribuinte=$con->prepare("UPDATE `contribuintes` SET `foto`=? ,`num_socio`=?,`cc`=?,`nif`=?,`telemovel`=?,`telefone`=?,`cp`=?,`receber_email`=?,`tipo_contribuinte`=?,`morada`=?,`localidade`=?,`freguesia`=?,`concelho`=?,`nome`=?,`sexo`=?,`email`=?,`metodo_pagamento`=?,`dt_nasc`=?,`mensalidade_valor`=? WHERE `id_contribuinte`=?");
            //Prepara os dados para update do Sócio.
              $update_contribuinte->bind_param("bssiiiiiissssssssssdi",$foto,$num_socio,$_POST['cc'],$_POST['nif'],$_POST['telemovel'],$_POST['telefone'],$cp,$receber_email,$_POST['tipo_contribuinte'],$_POST['morada'],$_POST['localidade'],$_POST['freguesia'],$_POST['concelho'],$_POST['nome'],$_POST['sexo'],$_POST['email'],$_POST['metodo_pagamento'],$_POST['dt_nasc'],$_POST['mensalidade_valor'],$_POST['id_contribuinte']);
          }
        }

        //Verifica se tem foto se sim coloca-a se nao coloca-a pelo sexo.
          if (is_uploaded_file($_FILES["foto"]["tmp_name"])){
            $update_contribuinte->send_long_data(0,file_get_contents($_FILES["foto"]["tmp_name"]));
          }else{
            $update_contribuinte->send_long_data(0,$linha['foto']);
          }

          //Executa a query.
            $update_contribuinte->execute();
      }elseif($_POST['tipo_contribuinte']=="Atleta"){
        //prepara as variaveis
          $metodo_pagamento="No clube";   

        //Prepara os dados para update do Atleta.
          $update_contribuinte->bind_param("bssiiiiiissssssssssdi",$foto,$null,$null,$_POST['cc'],$_POST['nif'],$_POST['telemovel'],$_POST['telefone'],$cp,$receber_email,$linha['tipo_contribuinte'],$_POST['morada'],$_POST['localidade'],$_POST['freguesia'],$_POST['concelho'],$_POST['nome'],$_POST['sexo'],$_POST['email'],$metodo_pagamento,$_POST['dt_nasc'],$_POST['mensalidade_valor_atleta'],$_POST['id_contribuinte']);

        //Verifica se tem foto se sim coloca-a se nao coloca-a pelo sexo.
          if (is_uploaded_file($_FILES["foto"]["tmp_name"])){
            $update_contribuinte->send_long_data(0,file_get_contents($_FILES["foto"]["tmp_name"]));
          }else{
            $update_contribuinte->send_long_data(0,$linha['foto']);
          }

        //Executa a query
          $update_contribuinte->execute();

        //Verifica se o enc_educação foi selecionado/inserido
          if (isset($linha['id_enc_edu'])) {
          //Se foi selecionado só busca o id do enc_edu
            if (isset($_POST['id_enc'])) {
              $id_contribuinte_enc=$_POST['id_enc'];
          //Se foi inserido tem de inserir na tabela contribuintes o enc_educação 
            }else{
                $cp_enc=explode('-',$_POST['cp_enc']);
                $cp_enc=$cp_enc[0].$cp_enc[1];
              //Verifica se o enc_edu quer receber emails do clube
                if (isset($_POST['receber_email_enc'])) {
                  $receber_email_enc=1;
                }else{
                  $receber_email_enc=0;
                }
              //prepara o insert do enc_edu
                $insert_contribuinte=$con->prepare("INSERT INTO `contribuintes`(`foto`,`num_socio`, `cc`, `nif`, `telemovel`, `telefone`, `cp`, `receber_email`,`tipo_contribuinte`,`morada`, `localidade`, `freguesia`, `concelho`, `nome`, `sexo`, `email`, `metodo_pagamento`, `dt_nasc`, `mensalidade_valor`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
              //Prepara os dados para insert do enc_edu.
                $insert_contribuinte->bind_param("bssiiiiiissssssssssd",$foto,$null,$null,$_POST['cc_enc'],$_POST['nif_enc'],$_POST['telemovel_enc'],$_POST['telefone_enc'],$cp,$receber_email_enc,$_POST['tipo_enc'],$_POST['morada_enc'],$_POST['localidade_enc'],$_POST['freguesia_enc'],$_POST['concelho_enc'],$_POST['nome_enc'],$_POST['sexo_enc'],$_POST['email_enc'],$null,$_POST['dt_nasc_enc'],$null);

              //Verifica se tem foto se sim coloca-a se nao coloca-a pelo sexo.
                if (is_uploaded_file($_FILES["foto_enc"]["tmp_name"])){
                  $insert_contribuinte->send_long_data(0,file_get_contents($_FILES["foto_enc"]["tmp_name"]));
                }else{
                  $insert_contribuinte->send_long_data(0,$linha_enc['foto']);
                }

              //Executa a query
                $insert_contribuinte->execute();

              //Busca o id do enc_edu
                $id_contribuinte_enc=$insert_contribuinte->insert_id;
            }

        //Se nao foi inserido o id do enc_edu, o mesmo é Null.
          }else{
            $id_contribuinte_enc=NULL;
          }

        //Verifica se a joia foi paga ou nao
          if (isset($_POST['joia'])) {
            $joia=1;
          }else{
            $joia=0;
          }

        //Verifica se o enc de educação ja esta associado ao atleta
          $atleta_check=$con->prepare("SELECT count(id_atleta) as total FROM atletas WHERE id_enc_edu=? AND id_contribuinte=?");
          $atleta_check->bind_param("ii",$id_contribuinte_enc,$id_contribuinte);
          $atleta_check->execute();
          $atleta_check=$atleta_check->get_result();
          $atleta_check=$atleta_check->fetch_assoc();
          if ($atleta_check['total']<1) {
            //prepara o insert do atleta
              $atletas_insert=$con->prepare("INSERT INTO `atletas`(`id_contribuinte`, `id_enc_edu`, `valor_joia`, `joia`) VALUES (?,?,?,?)");

            //Prepara os dados para insert do Atleta.
              $atletas_insert->bind_param("iiii",$id_contribuinte,$id_contribuinte_enc,$_POST['valor_joia'],$joia);
            //Executa a query
              $atletas_insert->execute();
            //Fecha a query
              $atletas_insert->close();
          }else{
            //prepara o insert do atleta
              $atletas_insert=$con->prepare("UPDATE `atletas` SET `id_enc_edu`=?,`valor_joia`=?, `joia`=? WHERE `id_contribuinte`=?");
            //Prepara os dados para insert do Atleta.
              $atletas_insert->bind_param("iiii",$id_contribuinte_enc,$_POST['valor_joia'],$joia,$id_contribuinte);
            //Executa a query
              $atletas_insert->execute();
            //Fecha a query
              $atletas_insert->close();
          }
      }elseif($_POST['tipo_contribuinte']=="Encarregado de educação"){
        //Prepara os dados para insert do Enc_edu.
          $update_contribuinte->bind_param("bssiiiiiissssssssssdi",$foto,$null,$null,$_POST['cc'],$_POST['nif'],$_POST['telemovel'],$_POST['telefone'],$cp,$receber_email,$_POST['tipo_contribuinte'],$_POST['morada'],$_POST['localidade'],$_POST['freguesia'],$_POST['concelho'],$_POST['nome'],$_POST['sexo'],$_POST['email'],$null,$_POST['dt_nasc'],$null,$_POST['id_contribuinte']);

        //Verifica se tem foto se sim coloca-a se nao coloca-a pelo sexo.
          if (is_uploaded_file($_FILES["foto"]["tmp_name"])){
            $update_contribuinte->send_long_data(0,file_get_contents($_FILES["foto"]["tmp_name"]));
          }else{
            $update_contribuinte->send_long_data(0,$linha['foto']);
          }

        //Executa a query.
          $update_contribuinte->execute();

        //Limpa os valores do id do enc_edu da tabela atletas
          $query=mysqli_query($con,"UPDATE `atletas` SET `id_enc_edu`= NULL WHERE `id_enc_edu`='$id_contribuinte'");
          if (isset($_POST['required_edu_id'])){
            //Prepara o update da tabela atletas
              $update_atletas=$con->prepare("UPDATE `atletas` SET `id_enc_edu`=? WHERE `id_contribuinte`=?");
              for ($i=0; $i < sizeof($_POST['required_edu_id']); $i++) { 
                //Prepara os dados para insert do Enc_edu na tabela dos atletas.
                $update_atletas->bind_param("ii",$id_contribuinte,$_POST['required_edu_id'][$i]);
                //Executa a query
                $update_atletas->execute();
              }
            //Fecha a query
              $update_atletas->close();
          }
      }

      if ($update_contribuinte->affected_rows<0) {
        ?>
          <script type="text/javascript">
            alert("Ocurreu algo não esperado.");
          </script>
        <?php
      }else{
        ?>
          <script type="text/javascript">
            alert("Atualizado com sucesso.");
            window.location.href="Contribuintes.php?id_contribuinte=<?php echo $id_contribuinte ?>"
          </script>
        <?php 
      }
      $update_contribuinte->close();
  }
?>
<html>
<head>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <!-- Ligação aos links e config da Head -->
  <?php include('head.php'); ?>
</head>
<body>
  <!--Popup dos enc edu-->
  <div id="popup_tabela_enc_edu" class="w3-modal" style="padding:3%;display: none;">
    <div class="w3-modal-content" style="width:90%;">
      <header class="w3-container" style="background-color:#3dc4c4;"> 
        <span onclick="esconder_modal_enc_edu()" class="w3-button w3-display-topright">&times;</span>
        <h2 style="text-align:center;font-size: 30;color:white;font-weight:bold; font-family:'Arial';">
          Tabela dos Encarregados de educação
        </h2>
      </header>
      <div class="w3-container">
        <div style="position: relative;overflow: auto; height: 85%">
          <label>Procura: </label>
          <input name="tabela_enc_edu_procura" onkeyup="definir_procura_enc_edu(this.value);first_page();atualizar_tabela_popup_enc_edu(num_pagina,this.value);">
          <div id="tabela_enc_edu">
          </div>
        </div>  
      </div>
      <footer class="w3-container" style="padding:4px;background-color:#3dc4c4;">  
        <center>
          <button type="button" class="w3-btn page_btn" onclick="first_page();atualizar_tabela_popup_enc_edu(num_pagina,procura); ">
            <<
          </button>
          <button type="button" class="w3-btn page_btn" onclick="prev_page();atualizar_tabela_popup_enc_edu(num_pagina,procura);">
            <
          </button>
          <button type="button" class="w3-btn page_btn" onclick="next_page();atualizar_tabela_popup_enc_edu(num_pagina,procura);">
            >
          </button>
          <button type="button" class="w3-btn page_btn" onclick="last_page();atualizar_tabela_popup_enc_edu(num_pagina,procura);">
            >>
          </button> 
        </center>
      </footer>
    </div>
  </div>

  <!--Popup dos atletas-->
  <div id="popup_tabela_atletas" class="w3-modal" style="padding:3%;display: none;">
    <div class="w3-modal-content" style="width:90%;">
      <header class="w3-container" style="background-color:#3dc4c4;"> 
        <span onclick="esconder_modal_atletas()" class="w3-button w3-display-topright">&times;</span>
        <h2 style="text-align:center;font-size: 30;color:white;font-weight:bold; font-family:'Arial';">
          Tabela dos atletas
        </h2>
      </header>
      <div class="w3-container">
        <div style="position: relative;overflow: auto; height: 85%">
          <label>Procura: </label>
          <input name="tabela_atletas_procura" onkeyup="definir_procura_atletas(this.value);first_page();atualizar_tabela_popup_atletas(num_pagina,this.value);">
          <div id="tabela_atletas">
          </div>
        </div>  
      </div>
      <footer class="w3-container" style="padding:4px;background-color:#3dc4c4;">  
        <center>
          <button type="button" class="w3-btn page_btn" onclick="first_page();atualizar_tabela_popup_atletas(num_pagina,procura); ">
            <<
          </button>
          <button type="button" class="w3-btn page_btn" onclick="prev_page();atualizar_tabela_popup_atletas(num_pagina,procura);">
            <
          </button>
          <button type="button" class="w3-btn page_btn" onclick="next_page();atualizar_tabela_popup_atletas(num_pagina,procura);">
            >
          </button>
          <button type="button" class="w3-btn page_btn" onclick="last_page();atualizar_tabela_popup_atletas(num_pagina,procura);">
            >>
          </button> 
        </center>
      </footer>
    </div>
  </div>

  <!--Inicio das cenas do site-->
	<div class="container">
    <form method="POST" enctype="multipart/form-data">
    <!-- Conexão da navbar -->
    <?php include('navbar_dashboard.php'); ?>
    <center style=" margin-top:25px;"><h1>Inserir Contribuinte</h1></center>

    <!-- Infos Basicas do contribuinte -->
    <div class="card" style=" margin-top:25px;">
      <div class="card-header">
          <h3 class="panel-title">Informações Básicas</h3>
      </div>
      <?php if (isset($_GET['id_contribuinte'])) {  ?>
          <input name="id_contribuinte" hidden value="<?php echo $linha['id_contribuinte']; ?>">
      <?php } ?>
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
              <div class="form-group col-md-6">
                <label for="tipo_contribuinte">Tipo de Utilizador</label>
                <?php if (isset($_GET['id_contribuinte'])) { ?>
                  <input hidden name="tipo_contribuinte" value="<?php echo($linha['tipo_contribuinte']); ?>">
                <?php }?>
                <select class="form-control" id="tipo_contribuinte" name="tipo_contribuinte" required onchange="mostrar_campos(this.value);">
                  <option disabled selected value> -- Escolher uma opção -- </option>
                  <option>Sócio</option>
                  <option>Atleta</option>
                  <option>Encarregado de educação</option>
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
      </div>
    </div>  

    <!-- Infos de Contacto do contribuinte -->
    <div class="card" style=" margin-top:25px;">
      <div class="card-header">
        <h3 class="panel-title">Informações de Contacto</h3>
      </div>
      <!--Morada, Localidade, Concelho, Freguesia, Codigo postal, Email, Telemovel e telefone-->
      <div class="card-body">
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
      </div>
    </div>

    <!-- Sócio - info só do socio-->
    <div id="container_socio" class="card" style="margin-top:25px;display: none">
      <div class="card-header">
        <h3 class="panel-title">Informações relativas ao sócio</h3>
      </div>
      <div class="card-body">
        <!--Login do socio-->
        <div class="form-row">
          <div class="form-group col-md-6">
            <label>Numero de socio:</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text"> 
                  <input hidden class="form-control" name="num_socio[]" value="S">
                  S
                </div>
              </div>
              <input class="form-control  input_socio" name="num_socio[]" maxlength="10" onkeypress="return sonumeros(event)" value="<?php 
                if (isset($_GET['id_contribuinte'])) {
                  $num=explode("S",$linha['num_socio']);
                  echo (end($num));
                }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                  echo($_POST['num_socio']);
                } 
              ?>">
              </div>
          </div>
          <div class="form-group col-md-6">
            <?php if (isset($is_socio)) {?>
              <label>Definir nova palavra-passe:</label>
            <?php }else{ ?>
              <label>Palavra-passe:</label>
            <?php } ?>
              <input class="form-control input_socio" id="password" type="password" name="password">
          </div>
        </div>
        <!--Valor da quota mensal e metodo de pagamento-->
        <div class="form-row">
          <div class="form-group col-md-6">
            <label>Valor quota mensal:</label>
            <div class="input-group">
              <input class="form-control input_socio" name="mensalidade_valor" onkeypress="return sonumeros(event)" value="<?php 
                if (isset($_GET['id_contribuinte'])) {
                  echo($linha['mensalidade_valor']);
                }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                  echo($_POST['mensalidade_valor']);
                } 
              ?>">
              <div class="input-group-prepend">
                <div class="input-group-text">€</div>
              </div>
            </div>
          </div>
          <div class="form-group col-md-6">
            <label>Metodo de pagamento</label>
            <select class="form-control input_socio" id="metodo_pagamento" name="metodo_pagamento" class="">
              <option disabled selected value> -- Escolher uma opção -- </option>
              <option>Domicilio</option>
              <option>No clube</option>
            </select>
          </div>
        </div>
      </div>
    </div>     

    <!-- Enc de edu - info extra -->
    <div id="container_enc_edu" class="card" style="margin-top:25px;display: none">
      <div class="card-header">
        <h3 class="panel-title">Encarregado de educação - Informações EXTRA</h3>
      </div>
      <div class="card-body">

        <div>
          <label>Associar atleta a este encarregado de educação</label>
          <button type="button" class="btn btn-default" onclick="mostrar_modal_atletas();atualizar_tabela_popup_atletas(1,'');">
            Selecionar
          </button>
          <button type="button" class="btn btn-default" onclick="limpar_inputs_enc_edu();">limpar</button>
        </div>
        <div id="lista_atletas_enc">
          <div class="containers_dinamicos">  
            <label>Lista de atletas associados a este encarregado de educação</label> 
            <?php 
              //Querry para ir buscar os ids dos atletas do enc_edu
              if (isset($_GET['id_contribuinte'])) {
                $atletas=$con->prepare("SELECT contribuintes.nome,contribuintes.cc,atletas.id_contribuinte FROM contribuintes INNER JOIN atletas ON contribuintes.id_contribuinte=atletas.id_contribuinte WHERE atletas.id_enc_edu=?");
                $atletas->bind_param("i",$linha['id_contribuinte']);
                $atletas->execute();
                $resultado=$atletas->get_result();
                while ($linha_atleta=$resultado->fetch_assoc()) { ?>
                  <div class="row">
                    <input hidden id="required_edu_id" name="required_edu_id[]" class="input_enc required_edu" value="<?php 
                            if (isset($_GET['id_contribuinte'])) {
                              echo($linha_atleta['id_contribuinte']);
                            }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                              echo($_POST['mensalidade_valor_atleta']);
                            } 
                          ?>">
                    <div class="col-md-6">
                      <label>Nome do atleta:</label>
                        <input readonly required id="required_edu_nome" class="input_enc required_edu form-control" value="<?php 
                          if (isset($_GET['id_contribuinte'])) {
                            echo($linha_atleta['nome']);
                          }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                            echo($_POST['mensalidade_valor_atleta']);
                          } 
                        ?>">
                    </div>
                    <div class="col-md-6">
                      <label>CC do atleta:</label>
                        <input readonly required id="required_edu_cc" class=" input_enc required_edu form-control" value="<?php 
                          if (isset($_GET['id_contribuinte'])) {
                            echo($linha_atleta['cc']);
                          }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                            echo($_POST['mensalidade_valor_atleta']);
                          } 
                        ?>">
                    </div>
                  </div>
                  <br>
                <?php   
                }
              }
            ?>
          </div>          
        </div>
      </div>
    </div>
    
    <!-- Atleta - info extra--> 
    <div id="container_atleta" class="card" style="margin-top:25px;display: none">
      <div class="card-header">
        <h3 class="panel-title">Informações relativas ao atleta</h3>
      </div>
      <div class="card-body">
        <!--Valor da mensalidade e da joia-->
        <div class="row">
          <div class="form-group col-md-6">
            <label for="valor_mensalidade">Valor Mensalidade:</label>
            <input type="text" class="form-control" id="valor_mensalidade" maxlength="11" class="input_atleta" name="mensalidade_valor_atleta" onkeypress="return sonumeros(event)" value="<?php 
                if (isset($is_atleta)) {
                  echo($linha['mensalidade_valor']);
                }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                  echo($_POST['mensalidade_valor_atleta']);
                } 
              ?>">
          </div>
          <div class="form-group col-md-6">
            <label for="valor_joia">Valor Joia:</label>
            <input type="text" class="form-control" id="valor_joia" maxlength="11" class="input_atleta" name="valor_joia" onkeypress="return sonumeros(event)" value="<?php 
              if (isset($is_atleta)) {
                echo($linha['valor_joia']);
              }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                echo($_POST['valor_joia']);
              } 
            ?>">
          </div>
        </div>
        <!--Se tem enc_edu e se pagou a joia-->
        <div class="row">
          <div class="form-group col-md-6">
            <?php if (!isset($is_atleta)) {?>
              <label>Escolher/Inserir encarregado de educação do atleta</label>
            <?php }else{ ?>
              <label>Encarregado de educação:</label>
            <?php } ?>
              <input id="insert_enc_edu" name="insert_enc_edu" type="checkbox" onchange="mostrar_inputs_enc_edu();">
          </div>
          <div class="form-group col-md-6">
            <label>Pagou joia:</label>
              <input id="pagou_joia" name="joia" type="checkbox">
          </div>
        </div>
      </div>
    </div>

    <!-- Atleta - Info tecnica -->
    <div class="card" style=" margin-top:25px;display: none;">
      <div class="card-header">
        <h3 class="panel-title">Informações Técnicas do Atleta</h3>
      </div>
      <div class="card-body">
        <!--Passe, Receção e Finalização-->  
        <div class="row">
          <div class="col">
            <label>Passe</label>
            <input type="text" class="form-control" placeholder="">
          </div>
          <div class="col">
            <label>Receção</label>
            <input type="text" class="form-control" placeholder="">
          </div>
          <div class="col">
            <label>Finalização</label>
            <input type="text" class="form-control" placeholder="">
          </div>
        </div>
        <!--Jg. de cabeça, cruzamento, marcação-->
        <div class="row">
          <div class="col">
            <label>Jogo de Cabeça</label>
            <input type="text" class="form-control" placeholder="">
          </div>
          <div class="col">
            <label>Cruzamento</label>
            <input type="text" class="form-control" placeholder="">
          </div>
          <div class="col">
            <label>Marcação</label>
            <input type="text" class="form-control" placeholder="">
          </div>
        </div>
        <!--Posicionamento, vel. execução, tomada de decisão-->
        <div class="row">
          <div class="col">
            <label>Posicionamento</label>
            <input type="text" class="form-control" placeholder="">
          </div>
          <div class="col">
            <label>Velocidade de Execução</label>
            <input type="text" class="form-control" placeholder="">
          </div>
          <div class="col">
            <label>Tomada de Execução</label>
            <input type="text" class="form-control" placeholder="">
          </div>
        </div>
        <!--1x1 ofensivo e defensivo-->
        <div class="row">
          <div class="col">
            <label>1x1 Ofensivo</label>
            <input type="text" class="form-control" placeholder="">
          </div>
          <div class="col">
            <label>1x1 Defensivo</label>
            <input type="text" class="form-control" placeholder="">
          </div>
        </div>
        <!--Marcação de livres, Cap. de trabalho, agressividade-->
        <div class="row">
          <div class="col">
            <label>Marcação de livres</label>
            <input type="text" class="form-control" placeholder="">
          </div>
          <div class="col">
            <label>Capacidade de Trabalho</label>
            <input type="text" class="form-control" placeholder="">
          </div>
          <div class="col">
            <label>Agressividade</label>
            <input type="text" class="form-control" placeholder="">
          </div>
        </div>
        <!--Autoconfiança, autocontrolo, caracter/personalidade-->
        <div class="row">
          <div class="col">
            <label>Autoconfiança</label>
            <input type="text" class="form-control" placeholder="">
          </div>
          <div class="col">
            <label>Autocontrolo</label>
            <input type="text" class="form-control" placeholder="">
          </div>
          <div class="col">
            <label>Carácter / personalidade</label>
            <input type="text" class="form-control" placeholder="">
          </div>
        </div>
        <!--Int. em jogo, cond. fisica geral, resistencia as lesões-->
        <div class="row">
          <div class="col">
            <label>Inteligência em jogo</label>
            <input type="text" class="form-control" placeholder="">
          </div>
          <div class="col">
            <label>Condição fisica Geral</label>
            <input type="text" class="form-control" placeholder="">
          </div>
          <div class="col">
            <label>Resistencia ás lesões</label>
            <input type="text" class="form-control" placeholder="">
          </div>
        </div>
      </div>
    </div>

    <!-- Atleta - Info medica -->
    <div class="card" style=" margin-top:25px;display: none;">
      <div class="card-header">
          <h3 class="panel-title">ATLETA - Informações Médicas</h3>
      </div>
      <div class="card-body">
        <!-- Peso -->
        <h6>Peso <strong>(KG)</strong></h6>
        <label>Peso Inicial</label>
        <div class="row">
          <div class="col">
            <input type="text" class="form-control" placeholder="">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="">
          </div>
        </div>

        <label>Peso Intermédio</label>
        <div class="row">
          <div class="col">
            <input type="text" class="form-control" placeholder="">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="">
          </div>
        </div>

        <label>Peso Final</label>
        <div class="row">
          <div class="col">
            <input type="text" class="form-control" placeholder="">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="">
          </div>
        </div>

        <hr>

        <!-- Altura -->

        <h6>Altura <strong>(m)</strong></h6>

        <label>Altura Inicial</label>
        <div class="row">
          <div class="col">
            <input type="text" class="form-control" placeholder="">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="">
          </div>
        </div>

        <label>Altura Intermédia</label>
        <div class="row">
          <div class="col">
            <input type="text" class="form-control" placeholder="">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="">
          </div>
        </div>

        <label>Altura Final</label>
        <div class="row">
          <div class="col">
            <input type="text" class="form-control" placeholder="">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="">
          </div>
        </div>

        <hr>
        <!-- IMC -->
        <h6>IMC <strong>(%)</strong></h6>

        <label>Índice de Massa Corporal</label>
        <div class="row">
          <div class="col">
            <input type="text" class="form-control" placeholder="IMC Inicial">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="IMC Intermédio">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="IMC Final">
          </div>
        </div>

        <hr>

        <!-- Frequencia Cardiaca -->

        <h6>FCrepouso <strong>(bpm)</strong></h6>

        <label>Frequência Cardíaca em Repouso</label>
        <div class="row">
          <div class="col">
            <input type="text" class="form-control" placeholder="FCr Inicial">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="FCr Intermédio">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="FCr Final">
          </div>
        </div>
      </div>
    </div>

    <!-- Atleta - Info do enc_edu-->
    <div id="enc_edu_atleta" class="card" style=" margin-top:25px;display: none; ">
      <div class="card-header">
        <h3 class="panel-title">Encarregado de educação do atleta</h3>
      </div>
      <div class="card-body">
        <!--form de inserir o enc_educação-->
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <button class="form-control" onclick="mostrar_modal_enc_edu();atualizar_tabela_popup_enc_edu('1','');" type="button" style="margin-bottom: 20px;">
                Selecionar encarregado de educação
              </button>
            </div>
          </div>
          <div class="row">

            <input id="id_enc" name="id_enc" hidden value="<?php 
              if (isset($is_atleta)) { 
                echo $linha_enc['id_contribuinte'];
              } 
            ?>">

            <!--Imagem do enc_edu-->          
            <div class="col-md-4">
              <div class="form-group d-flex justify-content-center">
                <img id="foto_place_enc" src="<?php 
                    if (isset($is_atleta)){
                    echo 'data:image/jpeg;base64,'.base64_encode($linha_enc["foto"]);
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
                    <input type="file" class="custom-file-input input_enc" id="foto_enc" name="foto_enc" accept="image/png, image/jpeg">
                </div>
              </div>
            </div>
            <!--Nome, CC, Nif, dt de nascimento, sexo, tipo e receber email.-->
            <div class="col-md-8">
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="nome_enc">Nome</label>
                  <input type="text" maxlength="60" onkeypress="return soletras(event)" class="form-control input_enc required" id="nome_enc" name="nome_enc" value="<?php 
                    if (isset($is_atleta)) {
                      echo($linha_enc['nome']);
                    }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                      echo($_POST['nome_enc']);
                    } 
                  ?>">
                </div>
                <div class="form-group col-md-6">
                  <label for="cc_enc">CC</label>
                  <input type="text" required maxlength="8" minlength="8" onkeypress="return sonumeros(event)" class="input_enc required form-control" id="cc_enc" name="cc_enc" value="<?php 
                    if (isset($is_atleta)) {
                      echo($linha_enc['cc']);
                    }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                      echo($_POST['cc_enc']);
                    } 
                  ?>">
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="nif_enc">NIF</label>
                  <input type="text" required maxlength="9" minlength="9" onkeypress="return sonumeros(event)" class="input_enc required form-control" id="nif_enc" name="nif_enc" value="<?php 
                    if (isset($is_atleta)) {
                      echo($linha_enc['nif']);
                    }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                      echo($_POST['nif_enc']);
                    } 
                  ?>">
                </div>
                <div class="form-group col-md-6">
                  <label for="dt_nasc_enc">Data de Nascimento</label>
                  <input class="input_enc required form-control" type="date" required id="dt_nasc_enc" name="dt_nasc_enc" value="<?php 
                    if (isset($is_atleta)) {
                      echo($linha_enc['dt_nasc']);
                    }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                      echo($_POST['dt_nasc_enc']);
                    } 
                  ?>">
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="sexo_enc">Sexo</label>
                  <select id="sexo_enc" name="sexo_enc" class="input_enc form-control" onchange="mudar_imagem_enc()">
                    <option value="Masculino">Masculino</option>
                    <option value="Feminino">Feminino</option>
                  </select>
                </div>
                <div class="form-group col-md-6">
                  <input name="tipo_enc" hidden value="Encarregado de educação">
                </div>
              </div>

              <div class="form-group">
                <div class="form-check">
                  <input class="input_enc form-check-input" type="checkbox" id="receber_email_enc" name="receber_email_enc">
                  <label class="form-check-label" for="receber_email_enc">
                    Receber e-Mails sobre o Clube
                  </label>
                </div>
              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-md-12">
              <label>Morada:</label>
                <input id="morada_enc" maxlength="60" class="input_enc required form-control" name="morada_enc" onkeypress="return soletras(event)" value="<?php 
                    if (isset($is_atleta)) {
                      echo($linha_enc['morada']);
                    }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                      echo($_POST['morada_enc']);
                    } 
                  ?>"><br>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
                <label>Localidade:</label>
                  <input id="localidade_enc" maxlength="60" class="input_enc required form-control" name="localidade_enc" onkeypress="return soletras(event)" value="<?php 
                      if (isset($is_atleta)) {
                        echo($linha_enc['localidade']);
                      }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                        echo($_POST['localidade_enc']);
                      } 
                    ?>"><br>
              
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <label>Concelho:</label>
                <input id="concelho_enc" maxlength="60" class="input_enc required form-control" name="concelho_enc" onkeypress="return soletras(event)" value="<?php 
                    if (isset($is_atleta)) {
                      echo($linha_enc['concelho']);
                    }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                      echo($_POST['concelho_enc']);
                    } 
                  ?>"><br>
            </div>
            <div class="col-md-4">
              <label>Freguesia:</label>
                <input id="freguesia_enc" maxlength="60" class="input_enc required form-control" name="freguesia_enc" onkeypress="return soletras(event)" value="<?php 
                    if (isset($is_atleta)) {
                      echo($linha_enc['freguesia']);
                    }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                      echo($_POST['freguesia_enc']);
                    } 
                  ?>"><br>
            </div>
            <div class="col-md-4">
              <label>CP:</label>
                <input id="cp_enc" class="input_enc required form-control"  name="cp_enc" minlength="8" maxlength="8" onkeypress="return sonumeros(event)" value="<?php 
                    if (isset($is_atleta)) {
                      echo($linha_enc['cp']);
                    }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                      echo($_POST['cp_enc']);
                    } 
                  ?>"><br>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <label>Email:</label>
                <input id="email_enc" maxlength="60" class="input_enc required form-control" minlength="3" maxlength="60" name="email_enc" type="email" value="<?php 
                  if (isset($is_atleta)) {
                    echo($linha_enc['email']);
                  }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                    echo($_POST['email_enc']);
                  } 
                ?>"><br>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label>Telemovel:</label>
                <input id="telemovel_enc" class="input_enc required form-control" name="telemovel_enc" minlength="9" maxlength="9" onkeypress="return sonumeros(event)" value="<?php 
                    if (isset($is_atleta)) {
                      echo($linha_enc['telemovel']);
                    }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                      echo($_POST['telemovel_enc']);
                    } 
                  ?>"><br>
            </div>
            <div class="col-md-6">
              <label>Telefone:</label>
                <input id="telefone_enc" class="input_enc required form-control" minlength="9" maxlength="9" name="telefone_enc" onkeypress="return sonumeros(event)" value="<?php 
                    if (isset($is_atleta)) {
                      echo($linha_enc['telefone']);
                    }elseif (isset($_POST['insert']) || isset($_POST['update'])){
                      echo($_POST['telefone_enc']);
                    } 
                  ?>"><br>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!--Butões-->
    <div class="d-flex justify-content-center" style=" margin-top:25px;margin-bottom:25px; ">
      <div class="alert alert-primary">
        <div>
          <?php if (isset($_GET['id_contribuinte'])) {?>
            <input class="btn btn-default" type="submit" id="btn_atualizar" name="update" value="Atualizar">
          <?php }else{?>
            <input class="btn btn-default" type="submit" id="btn_inserir" name="insert" value="Inserir">
          <?php }if ($_SESSION['permissao']==1){ ?>
            <button class="btn btn-default" type="button" onclick="window.location.href ='contribuintes.php'">Limpar</button>
          <?php } ?>
        </div>
      </div>
    </div>

  </form>
  </div> 
</body>
</html>
<script type="text/javascript">
  var x=0;
  //controle popup_atletas
    var num_pagina=1;

    function atualizar_tabela_popup_atletas(num_pagina,procura){
      tabela_popup_atletas(num_pagina,procura);
    }

    function tabela_popup_atletas(num_pagina,procura){
      $.post(
        'tabela_popup_atletas.php', 
        {
          'num_pagina': num_pagina,
          'procura':procura
        }, 
        function(response) {
          var resposta=response.split("«");
          total_num_paginas=resposta[0];
          $('#tabela_atletas').html(resposta[1]);
        }
      )
    }
    function definir_procura_atletas(value){
      procura=value;
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

    function mostrar_modal_atletas(){
      document.getElementById('popup_tabela_atletas').style.display='block';
    }

    function esconder_modal_atletas(){
      document.getElementById('popup_tabela_atletas').style.display='none';
    }

  //controle popup_enc_edu
    function atualizar_tabela_popup_enc_edu(num_pagina,procura){
      tabela_popup_enc_edu(num_pagina,procura);
    }

    function tabela_popup_enc_edu(num_pagina,procura){
      $.post(
        'tabela_popup_enc_edu.php', 
        {
          'num_pagina': num_pagina,
          'procura':procura
        }, 
        function(response) {
          var resposta=response.split("«");
          total_num_paginas=resposta[0];
          $('#tabela_enc_edu').html(resposta[1]);
        }
      )
    }
    function definir_procura_enc_edu(value){
      procura=value;
    }

    function mostrar_modal_enc_edu(){
      document.getElementById('popup_tabela_enc_edu').style.display='block';
    }

    function esconder_modal_enc_edu(){
      document.getElementById('popup_tabela_enc_edu').style.display='none';
    }

  //variaveis dos inputs
    var inputs_enc=document.getElementsByClassName("input_enc");
    var inputs_socio=document.getElementsByClassName("input_socio");
    var inputs_atleta=document.getElementsByClassName("input_atleta");

  //Selecionar o enc_edu
    function selecionar_enc_edu(id_contribuinte,nome,cc,nif,morada,localidade,freguesia,concelho,cp,email,telemovel,telefone,sexo,dt_nasc,receber_email){
      for (var i = inputs_enc.length - 1; i >= 0; i--) {
        inputs_enc[i].disabled=false;
      }
      $.post(
        'buscar_img_contribuinte.php', 
        {
          'id_contribuinte':id_contribuinte
        }, 
        function(response) {
          document.getElementById('foto_place_enc').src='data:image/jpeg;base64,'+response;
        }
      )
      document.getElementById("id_enc").value=id_contribuinte;
      document.getElementById("nome_enc").value=nome;
      document.getElementById("cc_enc").value=cc;
      document.getElementById("nif_enc").value=nif;
      document.getElementById("morada_enc").value=morada;
      document.getElementById("localidade_enc").value=localidade;
      document.getElementById("freguesia_enc").value=freguesia;
      document.getElementById("concelho_enc").value=concelho;
      document.getElementById("cp_enc").value=cp;
      document.getElementById("email_enc").value=email;
      document.getElementById("telemovel_enc").value=telemovel;
      document.getElementById("telefone_enc").value=telefone;
      document.getElementById("dt_nasc_enc").value=dt_nasc;
      if (sexo=="Masculino") {
        document.getElementById("sexo_enc").options.selectedIndex="0";
      }else{
        document.getElementById("sexo_enc").options.selectedIndex="1"; 
      }
      if (receber_email=="1") {
        document.getElementById("receber_emails_enc").checked=true;
      }
      for (var i = inputs_enc.length - 1; i >= 0; i--) {
        inputs_enc[i].disabled=true;  
      }
    }

    function selecionar_atleta(acao,id,nome,cc,num_pagina,procura) {
      $.post(
        'selecionar_atleta.php', 
        {
          'acao': acao,
          'id':id
        }, 
        function() {
          atualizar_tabela_popup_atletas(num_pagina,procura);
          if (acao=="0") {
            document.getElementById("container"+id+"").remove();
          }else{
            x++;
            $('#container_enc_edu').append('<div id="container'+id+'" class="containers_dinamicos"><div><input hidden id="required_edu_id'+x+'" name="required_edu_id[]" class="input_enc required_edu" value="'+id+'"></div><div><label>Nome do atleta:</label><input required id="input_enc required_edu_nome'+x+'" readonly class="input_enc required_edu" value="'+nome+'"></div><div><label>CC do atleta:</label><input required id="input_enc required_edu_cc'+x+'" readonly class="input_enc required_edu" value="'+cc+'"><button type="button" name="remove" id="'+x+'" onclick="selecionar_atleta(0,'+id+','+nome+','+cc+','+num_pagina+','+procura+');document.getElementById(\'container'+id+'\').remove();" class="btn btn_remove">Remover</button></div><br></div>');
          } 
        }
      )
    }

    function limpar_inputs_enc_edu(){
      var campos=document.getElementsByClassName("containers_dinamicos");
      for (var i = campos.length - 1; i >= 0; i--) {
        campos[i].remove();
      }
      $.post(
        'selecionar_atleta.php', 
        {
          'acao': 10,
          'id':0
        }, 
        function() {}
      )
    }

  //função de desativar/ativar inputs
    function inputs(value,funcao){
      if (value=="socio") {
        if (funcao=="1") {
          for (var i = inputs_socio.length - 1; i >= 0; i--) {
            inputs_socio[i].required=true;
            inputs_socio[i].value="";
          }
        }else{
          for (var i = inputs_socio.length - 1; i >= 0; i--) {
            inputs_socio[i].required=false;
            inputs_socio[i].value="";
          }
        }
      }
      if (value=="enc") {
        required=document.getElementsByClassName("required");
        if (funcao=="1") {
          for (var i = required.length - 1; i >= 0; i--) {
            required[i].disabled=false;
            required[i].required=true;
            required[i].value="";
          }
        }else{
          for (var i = required.length - 1; i >= 0; i--) {
            required[i].required=false;
            required[i].value="";
          } 
        }

      }
      if (value=="atleta") {
        if (funcao=="1") {
          for (var i = inputs_atleta.length - 1; i >= 0; i--) {
            inputs_atleta[i].required=true;
            inputs_atleta[i].value="";
          }
        }else{
          for (var i = inputs_atleta.length - 1; i >= 0; i--) {
            inputs_atleta[i].required=false;
            inputs_atleta[i].value="";
          }
        }
      }
    }

  //Mostra ou esconde o enc_edu limpando os inputs
    function mostrar_inputs_enc_edu(){
      if (document.getElementById("enc_edu_atleta").style.display=="none") {
        document.getElementById("enc_edu_atleta").style.display="block";
        inputs("enc",1);
      }else{
        document.getElementById("enc_edu_atleta").style.display="none";
        inputs("enc",0);
      }
    }

  //Faz upload da foto para mostrar no site temporariamente
    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
          $('#foto_place').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
      }
    }
    $("#foto").change(function() {
      readURL(this);
    });

  //Faz upload da foto do encarregado de educação para mostrar no site temporariamente
    function readURL_enc(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
          $('#foto_place_enc').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
      }
    }

    $("#foto_enc").change(function() {
      readURL_enc(this);
    });


  //Função de escolher a imagem do encarregado de educação consuante o sexo
    function mudar_imagem_enc(){
      if ((document.getElementById("foto_enc").value=='')) {
        if (document.getElementById('sexo_contribuinte_enc').value=="Masculino") {
          document.getElementById('foto_place_enc').src="fotos/Male_user.png"
        }else{
          document.getElementById('foto_place_enc').src="fotos/Female_user.png"
        }
      }
    }

  //função de chamar a função de desativar/ativar inputs
    function mostrar_campos(tipo){
      if (tipo=="Sócio") {
        inputs("socio",1);
        inputs("enc",0);
        inputs("atleta",0);
        limpar_inputs_enc_edu()
        document.getElementById("container_socio").style.display="block";
        document.getElementById("container_atleta").style.display="none";
        document.getElementById("container_enc_edu").style.display="none";
        document.getElementById("enc_edu_atleta").style.display="none";
      }
      //Mostra os inputs do atleta
      if (tipo=="Atleta") {
        inputs("socio",0);
        inputs("enc",0);
        inputs("atleta",1);
        limpar_inputs_enc_edu()

        document.getElementById("container_socio").style.display="none";
        document.getElementById("container_atleta").style.display="block";
        document.getElementById("container_enc_edu").style.display="none";  
        document.getElementById("insert_enc_edu").checked="";
      }
      if (tipo=="Encarregado de educação") {
        inputs("socio",0);
        inputs("enc",0);
        document.getElementById("container_socio").style.display="none";
        document.getElementById("container_atleta").style.display="none";
        document.getElementById("container_enc_edu").style.display="block";
        document.getElementById("enc_edu_atleta").style.display="none";
      }
    }

  //Confirmações e mascara em jquery
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

    function letras_numeros(evt){
      evt = (evt) ? evt : window.event;
      var charCode=(evt.which) ? evt.which : evt.keyCode;
      if ((charCode==32) || (charCode==186) || (charCode>=65 && charCode<=90) || (charCode>=97 && charCode<=122) || (charCode>=192 && charCode<=255) || (charCode >= 48 && charCode <= 57)) {
        return true;
      }
        return false;
    }

    function moradacheck(evt) {
      let confirmar = letras_numeros(evt)
      
      if (!confirmar) {
        return false
      } else {
        return true
      }
    }

    $(document).ready(() => {
      $("#cp").mask('0000-000', {reverse: true})
    })
    $(document).ready(() => {
      $("#cp_enc").mask('0000-000', {reverse: true})
    })

</script>
<!--Confirmações finais sobre mostar informação certa-->
  <?php
    if (!isset($_GET['id_contribuinte'])) {?>
      <script type="text/javascript">
        //Função de escolher a imagem consuante o sexo
          function mudar_imagem(){
            if ((document.getElementById("foto").value=='')) {
              if (document.getElementById('sexo').value=="Masculino") {
                document.getElementById('foto_place').src="fotos/Male_user.png"
              }else{
                document.getElementById('foto_place').src="fotos/Female_user.png"
              }
            }
          }
      </script>
    <?php }
    if (isset($_GET['id_contribuinte'])) {
      //Busca o sexo do contribuinte
        if ($linha['sexo']=="Masculino") { 
          ?><script type="text/javascript">document.getElementById("sexo").options.selectedIndex=0;</script><?php
        }else{
          ?><script type="text/javascript">document.getElementById("sexo").options.selectedIndex=1;</script><?php
        }
      //Se o contribuinte for socio
        if ($linha['tipo_contribuinte']=="Sócio") {
        //Seleciona o metodo de pagamento que o Sócio selecionou
        if ($linha['metodo_pagamento']=="Domicilio") {
          ?><script type="text/javascript">document.getElementById("metodo_pagamento").options.selectedIndex=1;</script><?php
        }else{
          ?><script type="text/javascript">document.getElementById("metodo_pagamento").options.selectedIndex=2;</script><?php
        }
        ?>
          <script type="text/javascript">
            //Escolhe o tipo no dropbox input e desativa-o
            document.getElementById("tipo_contribuinte").options.selectedIndex=1;
            document.getElementById("tipo_contribuinte").disabled=true;
            //mostra os campos do socio
            document.getElementById("container_socio").style.display="block";
            document.getElementById("container_atleta").style.display="none";
            document.getElementById("container_enc_edu").style.display="none";
            document.getElementById("enc_edu_atleta").style.display="none";
          </script>
        <?php

      //Se o contribuinte for atleta
        }elseif ($linha['tipo_contribuinte']=="Atleta") {
        //Seleciona se pagou a joia 
          if ($linha['joia']==1) {
            ?><script type="text/javascript">document.getElementById("pagou_joia").checked=true;</script><?php
          }
        //Se o atleta tiver um enc_edu mostra os inputs do enc_edu desativados e seleciona a checkbox
          if ($linha['id_enc_edu']) {
            ?>
              <script type="text/javascript">
                document.getElementById("insert_enc_edu").checked=true;
                document.getElementById("insert_enc_edu").disabled=true;
                document.getElementById("enc_edu_atleta").style.display="block";
              </script>
            <?php
            if ($linha_enc['sexo']=="Masculino") { 
              ?><script type="text/javascript">document.getElementById("sexo_enc").options.selectedIndex="0";</script><?php
            }else{
              ?><script type="text/javascript">document.getElementById("sexo_enc").options.selectedIndex="1";</script><?php
            }
            ?>
              <script type="text/javascript">
                for (var i = inputs_enc.length - 1; i >= 0; i--) {
                  inputs_enc[i].disabled=true;  
                }
              </script>
            <?php
          } ?>
            <script type="text/javascript">
              document.getElementById("tipo_contribuinte").options.selectedIndex=2;
              document.getElementById("tipo_contribuinte").disabled=true;
          
              document.getElementById("container_socio").style.display="none";
              document.getElementById("container_atleta").style.display="block";
              document.getElementById("container_enc_edu").style.display="none";
            </script>
          <?php
      
      //Se o contribuinte for enc_edu
        }else{
          ?>
            <script type="text/javascript">
              document.getElementById("tipo_contribuinte").options.selectedIndex=3;
              document.getElementById("tipo_contribuinte").disabled=true;
              document.getElementById("container_socio").style.display="none";
              document.getElementById("container_atleta").style.display="none";
              document.getElementById("container_enc_edu").style.display="block";
            </script>
          <?php
        }
    
    }elseif (isset($_POST['insert']) || isset($_POST['update'])){
      //Busca o sexo do contribuinte
        if ($_POST['sexo']=="Masculino") { 
          ?><script type="text/javascript">document.getElementById("sexo").options.selectedIndex=0;</script><?php
        }else{
          ?><script type="text/javascript">document.getElementById("sexo").options.selectedIndex=1;</script><?php
        }
      //Se o contribuinte for socio
        if ($_POST['tipo_contribuinte']=="Sócio") {
          if ($_POST['metodo_pagamento']=="Domicilio") {
            ?><script type="text/javascript">document.getElementById("metodo_pagamento").options.selectedIndex=1;</script><?php
          }else{
            ?><script type="text/javascript">document.getElementById("metodo_pagamento").options.selectedIndex=2;</script><?php
          }
          ?>
            <script type="text/javascript">
              document.getElementById("tipo_contribuinte").options.selectedIndex=1;
              document.getElementById("container_socio").style.display="block";
              document.getElementById("container_atleta").style.display="none";
              document.getElementById("container_enc_edu").style.display="none";
              document.getElementById("enc_edu_atleta").style.display="none";
            </script>
          <?php
      //Se o contribuinte for atleta
        }elseif ($_POST['tipo_contribuinte']=="Atleta") {
          if (isset($_POST['insert_enc_edu'])) {
            ?><script type="text/javascript">
              document.getElementById("insert_enc_edu").checked = true;
              document.getElementById("enc_edu_atleta").style.display = "block";
            </script><?php
          }
          if (isset($_POST['joia'])) {
            ?><script type="text/javascript">document.getElementById("pagou_joia").checked = true</script><?php
          }
          ?>
            <script type="text/javascript">
              document.getElementById("tipo_contribuinte").options.selectedIndex=2;
              document.getElementById("container_socio").style.display="none";
              document.getElementById("container_atleta").style.display="block";
              document.getElementById("container_enc_edu").style.display="none";
            </script>
          <?php
      //Se o contribuinte for enc_edu
        }else{
          ?>
            <script type="text/javascript">
              document.getElementById("tipo_contribuinte").options.selectedIndex=3;
              document.getElementById("input_enc required_edu_id").value="<?php echo($_POST['input_enc required_edu_id']) ?>";
              document.getElementById("input_enc required_edu_nome").value="<?php echo($_POST['input_enc required_edu_nome']) ?>";
              document.getElementById("input_enc required_edu_cc").value="<?php echo($_POST['input_enc required_edu_cc']) ?>";
              document.getElementById("container_socio").style.display="none";
              document.getElementById("container_atleta").style.display="none";
              document.getElementById("container_enc_edu").style.display="block";
            </script>
          <?php
        }
    }
  
if ($_SESSION['permissao']==2) {
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
        document.getElementById("btn_atualizar").disabled=true;

      </script>
    <?php
  }
  ?>
