<!-- Ligação á base de Dados -->
<?php 
  require('ligacao.php'); 
  unset($_SESSION['array_atletas']);
  unset($_SESSION['array_treinador']);
  if (isset($_POST['insert'])) {
    if (isset($_POST['treinador']) && isset($_POST['id_atletas'])) {
      echo "yes";
    }else{
      ?>
        <script type="text/javascript">
          alert("No minimo de 1 treinador e 1 atleta têm de estar selecionados para criar esta equipa.");
        </script>
      <?php
    }
    print_r($_POST);
  }
  if (isset($_POST['update'])) {
    # code...
  }
?>

<html>

<!-- Ligação aos links e config da Head -->
<?php include('head.php'); ?>
<head>

<!-- CSS Dependencies para o color picker -->

<link rel="stylesheet" href="color-picker/build/1.2.3/css/pick-a-color-1.2.3.min.css">	  


<!-- JS Dependencies -->
<script src="color-picker/build/dependencies/tinycolor-0.9.15.min.js"></script>
<script src="color-picker/build/1.2.3/js/pick-a-color-1.2.3.min.js"></script>

</head>
<script type="text/javascript">
    var x=1; 

    var total_num_paginas_treinador=1
    var total_num_paginas_atletas=1

    var procura_treinador=''
    var procura_atletas=''

    var num_pagina_treinador=1
    var num_pagina_atletas=1

    var equipa_atletas='T';

    $(document).ready(function () {

        $(".pick-a-color").pickAColor({
            showSpectrum            : true,
            showSavedColors         : true,
            saveColorsPerElement    : true,
            fadeMenuToggle          : true,
            showAdvanced            : true,
            showBasicColors         : true,
            showHexInput            : false,
            allowBlank              : true,
            inlineDropdown          : true
        });
        
    });

    //Tabela dos treinadores
        function tabela_treinadores(num_pagina,procura){
          $.post(
            'tabela_treinadores_equipas.php', 
            {
              'num_pagina': num_pagina,
              'procura':procura
            }, 
            function(response) {
              var resposta=response.split("«");
              total_num_paginas_treinador=resposta[0];
              $('#tabela_treinadores').html(resposta[1]);
            }
          )
        }

        function definir_procura_treinador(value){
          procura_treinador=value;
        }

        function first_page_treinador(){
          num_pagina_treinador=1;
        }

        function prev_page_treinador(){
          if (num_pagina_treinador>1) {
            num_pagina_treinador--;
          }
        }

        function next_page_treinador(){
          if (num_pagina_treinador<total_num_paginas_treinador) {
            num_pagina_treinador++;
          }
        }

        function last_page_treinador(){
          num_pagina_treinador=total_num_paginas_treinador;
        }

    //Tabela atletas

        function tabela_atletas(num_pagina,procura,equipa){
          $.post(
            'tabela_atletas_equipas.php', 
            {
              'num_pagina': num_pagina,
              'procura':procura,
              'equipa':equipa
            }, 
            function(response) {
              var resposta=response.split("«");
              total_num_paginas_atletas=resposta[0];
              $('#tabela_atletas').html(resposta[1]);
            }
          )
        }

        function definir_procura_atletas(value){
          procura_atletas=value;
        }

        function definir_equipa_atletas(value){
          equipa_atletas=value;
        }

        function first_page_atletas(){
          num_pagina_atletas=1;
        }

        function prev_page_atletas(){
          if (num_pagina_atletas>1) {
            num_pagina_atletas--;
          }
        }

        function next_page_atletas(){
          if (num_pagina_atletas<total_num_paginas_atletas) {
            num_pagina_atletas++;
          }
        }

        function last_page_atletas(){
          num_pagina_atletas=total_num_paginas_atletas;
        }

        tabela_atletas(num_pagina_atletas,procura_atletas,equipa_atletas)
        tabela_treinadores(num_pagina_treinador,procura_treinador)

    function selecionar_atleta(acao,id,nome) {
      $.post(
        'selecionar_atleta.php', 
        {
          'acao': acao,
          'id':id
        }, 
        function() {
          tabela_atletas(num_pagina_atletas,procura_atletas,equipa_atletas);
          if (acao=="0") {
            document.getElementById("container"+id+"").remove();
          }else{
            x++;
            $('#container_atletas').append('<div id="container'+id+'" class="containers_dinamicos"><input hidden id="id_atletas'+x+'" name="id_atletas[]" class="" value="'+id+'"><div><input class="form-control" disabled value="'+nome+'"></div></div>');
          } 
        }
      )
    }

    function toogle_mostrar_atletas(){
      if (document.getElementById("container_atletas").style.display=="none") {
        document.getElementById("container_atletas").style.display="block"
      }else{
        document.getElementById("container_atletas").style.display="none"
      }
    }
</script>
<?php 
  if (isset($_GET['id_equipa'])) {
    $equipa=$con->prepare("SELECT * FROM `equipas` WHERE id_equipa=?");
    $equipa->bind_param("i",$_GET['id_equipa']);
    $equipa->execute();
    $equipa=$equipa->get_result();
    $linha=$equipa->fetch_assoc();

    $atletas=$con->prepare("SELECT atletas.id_atleta,contribuintes.nome FROM `atletas_equipas` INNER JOIN atletas ON atletas_equipas.id_atleta=atletas.id_atleta INNER JOIN contribuintes ON atletas.id_contribuinte=contribuintes.id_contribuinte WHERE id_equipa=? AND atual=1");
    $atletas->bind_param("i",$_GET['id_equipa']);
    $atletas->execute();
    $atletas=$atletas->get_result();
    while($linha_atletas=$atletas->fetch_assoc()){
        ?>
        <script type="text/javascript">
          selecionar_atleta(1,<?php echo $linha_atletas['id_atleta']; ?>,"<?php echo $linha_atletas['nome']; ?>")
        </script>
        <?php
    } 
  }elseif(isset($_POST['insert']) || isset($_POST['update'])){
    $atletas=$con->prepare("SELECT nome FROM contribuintes INNER JOIN atletas ON contribuintes.id_contribuinte=atletas.id_contribuinte WHERE id_atleta=?");
    for ($i=0; $i < count($_POST['id_atletas']); $i++) { 
      $atletas->bind_param("i",$_POST['id_atletas'][$i]);
      $atletas->execute();
      $atletas=$atletas->get_result();
      $linha_atletas=$atletas->fetch_assoc();
      ?>
        <script type="text/javascript">
          selecionar_atleta(1,<?php echo $_POST['id_atletas'][$i]; ?>,"<?php echo $linha_atletas['nome']; ?>")
        </script>
      <?php
    }
  }
?>
<body>
  <form method="POST">
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

              
              <div class="col-md-6">
                <label>Nome da equipa</label>
                <input required id="nome" name="nome" type="text" class="form-control" placeholder="Nome da equipa" value="<?php 
                    if (isset($_GET['id_equipa'])) {
                        echo $linha['nome'];
                    }elseif(isset($_POST['insert']) || isset($_POST['update'])){
                      echo $_POST['nome'];
                    }
                ?>">
              </div>
              <div class="col-md-6">
                <label>Escalão</label>
              <select required id="escalao" name="escalao" class="form-control" required>
                  <option selected disabled>--Escolher um escalão--</option>
                  <?php 
                      $escalao=$con->prepare("SELECT * FROM escaloes");
                      $escalao->execute();
                      $escalao=$escalao->get_result();
                      while ($linha_escalao=$escalao->fetch_assoc()) {
                        if (isset($_GET['id_equipa'])) {
                          if ($linha_escalao['id_escalao']==$linha['id_escalao']){
                            ?>
                              <option value="<?php echo $linha_escalao['id_escalao']; ?>" selected><?php echo $linha_escalao['escalao']; ?></option>
                            <?php }else{ ?>
                              <option value="<?php echo $linha_escalao['id_escalao']; ?>"><?php echo $linha_escalao['escalao']; ?></option>
                            <?php 
                          }
                        }elseif(isset($_POST['insert']) || isset($_POST['update'])){
                          if ($linha_escalao['id_escalao']==$_POST['escalao']){
                            ?>
                              <option value="<?php echo $linha_escalao['id_escalao']; ?>" selected><?php echo $linha_escalao['escalao']; ?></option>
                            <?php }else{ ?>
                              <option value="<?php echo $linha_escalao['id_escalao']; ?>"><?php echo $linha_escalao['escalao']; ?></option>
                            <?php 
                          }
                        }else{
                          ?>
                            <option value="<?php echo $linha_escalao['id_escalao']; ?>"><?php echo $linha_escalao['escalao']; ?></option>
                          <?php
                        }
                      }
                  ?>
                </select>
              </div>
              <div class="col-lg-12">
                <label>Cor da Equipa</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                      <div class="input-group-text">#</div>
                  </div>
                  <input id="color" class="input-group-text" disabled>
                  <div class="input-group-append">
                      <input required onchange="document.getElementById('color').value=this.value" type="text" required id="choose_color" name="cor" class="pick-a-color form-control" value="<?php 
                          if (isset($_GET['id_equipa'])) {
                            echo $linha['cor'];
                          }elseif(isset($_POST['insert']) || isset($_POST['update'])){
                            echo $_POST['cor'];
                          }
                          ?>"  >
                  </div>
                </div>
              </div>
          </div>

          <hr>

          <!-- Treinador --> 
          <div class="form-row">
            <div class="col-md-12">
                <label>Associar Treinador</label>
            </div>
            <div class="col-md-12">
                <input type="text" class="form-control" placeholder="Procurar treinador" onkeyup="definir_procura_treinador(this.value);tabela_treinadores(num_pagina_treinador,procura_treinador);">
                <br>
            </div>
            <div class="col-md-12" id="tabela_treinadores">

            </div>
          </div> 
          <div class="form-row">
            <div class="col-12">
              <button type="button" class="btn btn-default" onclick="first_page_treinador();tabela_treinadores(num_pagina_treinador,procura_treinador); ">
              <<
              </button>
              <button type="button" class="btn btn-default" onclick="prev_page_treinador();tabela_treinadores(num_pagina_treinador,procura_treinador);">
              <
              </button>
              <button type="button" class="btn btn-default" onclick="next_page_treinador();tabela_treinadores(num_pagina_treinador,procura_treinador);">
              >
              </button>
              <button type="button" class="btn btn-default" onclick="last_page_treinador();tabela_treinadores(num_pagina_treinador,procura_treinador);">
              >>
              </button> 
            </div>
          </div>

          <hr>
          
          <!-- Atletas --> 
          <div class="form-row">
            <div class="col-md-12">
                <label>Associar Atletas</label>
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Procurar atletas" onkeyup="definir_procura_atletas(this.value);tabela_atletas(num_pagina_atletas,procura_atletas,equipa_atletas);">
                <br>
            </div>
            <div class="col-md-6">
                <select type="text" class="form-control" onchange="definir_equipa_atletas(this.value);tabela_atletas(num_pagina_atletas,procura_atletas,equipa_atletas);">
                  <option selected value="T"> Todos os atletas</option>
                  <option value="S"> Sem equipa</option>
                  <option value="C"> Com equipas</option>
                  <option disabled="">----------</option>
                  <?php 
                    $equipas=$con->prepare("SELECT * FROM escaloes");
                    $equipas->execute();
                    $equipas=$equipas->get_result();
                    while ($linha_equipa=$equipas->fetch_assoc()) {
                      ?>
                        <option value="<?php echo $linha_equipa['id_escalao']; ?>"><?php echo $linha_equipa['escalao']; ?></option>
                      <?php
                    }
                  ?>
                </select>
                <br>
            </div>
            <div class="col-md-12" id="tabela_atletas">
            </div>
          </div>
          <div class="form-row">
              <div class="col-md-8 col-sm-8">
                <button type="button" class="btn btn-default" onclick="first_page_atletas();tabela_atletas(num_pagina_atletas,procura_atletas,equipa_atletas); ">
                <<
                </button>
                <button type="button" class="btn btn-default" onclick="prev_page_atletas();tabela_atletas(num_pagina_atletas,procura_atletas,equipa_atletas);">
                <
                </button>
                <button type="button" class="btn btn-default" onclick="next_page_atletas();tabela_atletas(num_pagina_atletas,procura_atletas,equipa_atletas);">
                >
                </button>
                <button type="button" class="btn btn-default" onclick="last_page_atletas();tabela_atletas(num_pagina_atletas,procura_atletas,equipa_atletas);">
                >>
                </button> 
              </div>
              <div class="col-md-4 col-sm-4" align="right">
                <button type="button" onclick="toogle_mostrar_atletas()" class="btn btn-default">Mostrar/Esconder Atletas selecionados</button>
              </div>
          </div>
          <div id="container_atletas">
            <hr>
            <div>
              <label>Atletas selecionados</label>
            </div>
          </div>
        </div>
      </div>
      <div class="d-flex justify-content-center" style=" margin-top:25px;">
        <div class="alert alert-primary">
          <?php if (!isset($_GET['id_colaborador'])) { ?>
            <input type="submit" class="btn btn-default" name="insert" value="Inserir dados">
          <?php }else{ ?>
            <input type="submit" class="btn btn-default" name="update" value="Atualizar dados">
          <?php } ?>
          <button type="button" class="btn btn-default" onclick="window.location.href='equipa.php'">Limpar</button>
        </div>
      </div>

    </div>
  </form>
</body>
</html> 
<?php 
  if (isset($_GET['id_equipa'])) {
  ?>
    <script type="text/javascript">
      var cor=document.getElementById('choose_color').value
      cor=cor.split("#")
      cor=cor[1].toLowerCase()
      document.getElementById('color').value=cor
    </script>
  <?php
  }elseif(isset($_POST['insert']) || isset($_POST['update'])){
  ?>
    <script type="text/javascript">
       document.getElementById('color').value=document.getElementById('choose_color').value
    </script>
  <?php
  }
?>
