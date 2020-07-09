<?php require('ligacao.php'); ?>
<html lang="en">
  <?php include('head.php'); ?>
  <body>
    <div class="container">
      <?php include('navbar_dashboard.php'); ?>

      <!-- Tables dos colaboradores -->
  
        <div class="card"style="margin-top: 30px">
          <div class="card-header"> 
            <div class="row">
              <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">
                  <h3 class="panel-title">Lista de Equipas</h3>
              </div>

               <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6" align="right">
                  <a href="equipa.php" name="add" id="add_button" class="btn btn-default btn-xs" >Nova Equipa</a>      
              </div>
            </div>
          </div>
          <div class="row card-header">
            <div class="col-lg-6 col-md-8 col-sm-12 col-xs-6 " align="right">
              <input class="form-control mr-sm-2" type="search" placeholder="Pesquisa" aria-label="Search" onkeyup="definir_procura(this.value);tabela_equipas(num_pagina,procura,escalao);">
            </div>
            <div class="col-lg-6 col-md-8 col-sm-12 col-xs-6 " align="right">
              <select onchange="definir_escalao(this.value);tabela_equipas(num_pagina,procura,escalao);" class="form-control">
                <option value="T">Todos os escalões</option>
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
            </div>
          </div>

          <div class="card-body" id="tabela_equipas"></div>

          <div class="row card-header">
            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
              <button type="button" class="btn btn-default" onclick="first_page();tabela_equipas(num_pagina,procura,escalao); ">
              <<
              </button>
              <button type="button" class="btn btn-default" onclick="prev_page();tabela_equipas(num_pagina,procura,escalao);">
              <
              </button>
              <button type="button" class="btn btn-default" onclick="next_page();tabela_equipas(num_pagina,procura,escalao);">
              >
              </button>
              <button type="button" class="btn btn-default" onclick="last_page();tabela_equipas(num_pagina,procura,escalao);">
              >>
              </button> 
            </div>
         </div>
      </div>
   
  </body>
</html>
    <script src="//code.jquery.com/jquery.min.js"></script>
<script type="text/javascript">
  var procura='';
  var escalao='T';
  var num_pagina=1;
  var total_num_paginas=0;

  function tabela_equipas(num_pagina,procura,escalao){
    $.post(
      'tabela_equipas.php', 
      {
        'num_pagina': num_pagina,
        'procura':procura,
        'escalao': escalao
      }, 
      function(response) {
        var resposta=response.split("«");
        total_num_paginas=resposta[0];
        $('#tabela_equipas').html(resposta[1]);
      }
    )
  }
  function definir_procura(value){
    procura=value;
  }
  function definir_escalao(value){
    escalao=value;
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

  tabela_equipas(num_pagina,procura,escalao);
</script>