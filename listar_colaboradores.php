<?php require('ligacao.php'); ?>
<html lang="en">
  <?php include('head.php'); ?>
  <body>
    <div class="container">
      <?php include('navbar_dashboard.php'); ?>

      <!-- Tables dos colaboradores -->
      <div class="col-sm-12">
        <div class="card"style="margin-top: 30px">
          <div class="card-header"> 
            <div class="row">
              <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">
                  <h3 class="panel-title">Lista de Colaboradores</h3>
              </div>

               <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6" align="right">
                  <a href="colaboradores.php" name="add" id="add_button" class="btn btn-default btn-xs" >Novo Colaborador</a>      
              </div>
            </div>
          </div>
          <div class="row card-header">
            <div class="col-lg-6 col-md-8 col-sm-12 col-xs-6 " align="right">
              <input class="form-control mr-sm-2" type="search" placeholder="Pesquisa" aria-label="Search" onkeyup="definir_procura(this.value);tabela_colaboradores(num_pagina,procura);">
            </div>
          </div>

          <div class="card-body" id="tabela_colaboradores"></div>

          <div class="row card-header">
            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
              <button type="button" class="btn btn-default" onclick="first_page();tabela_colaboradores(num_pagina,procura); ">
              <<
              </button>
              <button type="button" class="btn btn-default" onclick="prev_page();tabela_colaboradores(num_pagina,procura);">
              <
              </button>
              <button type="button" class="btn btn-default" onclick="next_page();tabela_colaboradores(num_pagina,procura);">
              >
              </button>
              <button type="button" class="btn btn-default" onclick="last_page();tabela_colaboradores(num_pagina,procura);">
              >>
              </button> 
            </div>
         </div>
      </div>
    </div>
  </body>
</html>
    <script src="//code.jquery.com/jquery.min.js"></script>
<script type="text/javascript">
  var procura='';
  var num_pagina=1;
  var total_num_paginas=0;

  function tabela_colaboradores(num_pagina,procura){
    $.post(
      'tabela_colaboradores.php', 
      {
        'num_pagina': num_pagina,
        'procura':procura
      }, 
      function(response) {
        var resposta=response.split("«");
        total_num_paginas=resposta[0];
        $('#tabela_colaboradores').html(resposta[1]);
      }
    )
  }
  function definir_procura(value){
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

  tabela_colaboradores(num_pagina,procura);
</script>