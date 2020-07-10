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
                <h3 class="panel-title">Lista de Contribuintes</h3>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6" align="right">
                <a href="contribuintes.php" name="add" id="add_button" class="btn btn-default btn-xs" >Novo Contribuinte</a>      
              </div>
            </div>
          </div>
          <div class="row card-header">
            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-6 ">
                <input class="form-control mr-sm-2" type="search" placeholder="Pesquisa" aria-label="Search" onkeyup="definir_procura(this.value);tabela_contribuintes(num_pagina,procura,tipo);">
            </div> 
            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-6 ">
                <select class="form-control" onchange="definir_tipo(this.value);tabela_contribuintes(num_pagina,procura,tipo);">
                  <?php if ($_SESSION ['permissao']==2) { ?>
                     <option>Atleta</option>
                  <?php }else{ ?>
                    <option value="">Todos os contribuintes</option>
                    <option>Sócio</option>
                    <option>Atleta</option>
                    <option>Encarregado de educação</option>
                  <?php } ?>
                </select>
            </div>
          </div>
          <div class="card-body " id="tabela_contribuintes"></div>

          <div class="row card-header">
            <div class="col-lg-6 col-md-4 col-sm-6 col-xs-6">
              <button type="button" class="btn btn-default" onclick="first_page();tabela_contribuintes(num_pagina,procura,tipo); ">
                <<
              </button>
              <button type="button" class="btn btn-default" onclick="prev_page();tabela_contribuintes(num_pagina,procura,tipo);">
                <
              </button>
              <button type="button" class="btn btn-default" onclick="next_page();tabela_contribuintes(num_pagina,procura,tipo);">
                >
              </button>
              <button type="button" class="btn btn-default" onclick="last_page();tabela_contribuintes(num_pagina,procura,tipo);">
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
  var tipo='';
</script>
<?php if ($_SESSION['permissao']==2) {?><script type="text/javascript"> tipo="Atleta"</script><?php } ?>
<script type="text/javascript">
  function tabela_contribuintes(num_pagina,procura,tipo){
    $.post(
      'tabela_contribuintes.php', 
      {
        'num_pagina': num_pagina,
        'procura':procura,
        'tipo':tipo
      }, 
      function(response) {
        var resposta=response.split("«");
        total_num_paginas=resposta[0];
        $('#tabela_contribuintes').html(resposta[1]);
      }
    )
  }
  function definir_procura(value){
    procura=value;
  }
  function definir_tipo(value){
    tipo=value;
    alert(tipo);
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



  tabela_contribuintes(num_pagina,procura,tipo);
</script>