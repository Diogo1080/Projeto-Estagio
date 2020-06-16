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
                  <div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
                        <h3 class="panel-title">Lista de Colaboradores</h3>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6" align="right">
                        <a href="contribuintes.php" name="add" id="add_button" class="btn btn-default btn-xs" >Novo Contribuinte</a>      
                    </div>
                </div>
          </div>
        <div>
          Procura: <input onkeyup="definir_procura(this.value);tabela_contribuintes(num_pagina,procura,tipo);">
          Tipo: 
          <select onchange="definir_tipo(this.value);tabela_contribuintes(num_pagina,procura,tipo);">
            <option></option>
            <option>Sócio</option>
            <option>Atleta</option>
            <option>Encarregado de educação</option>
          </select>
        </div>
        <div class="card-body" id="tabela_contribuintes"></div>
        <div>
        <button type="button" class="w3-btn page_btn" onclick="first_page();tabela_contribuintes(num_pagina,procura,tipo); ">
          <<
        </button>
        <button type="button" class="w3-btn page_btn" onclick="prev_page();tabela_contribuintes(num_pagina,procura,tipo);">
          <
        </button>
        <button type="button" class="w3-btn page_btn" onclick="next_page();tabela_contribuintes(num_pagina,procura,tipo);">
          >
        </button>
        <button type="button" class="w3-btn page_btn" onclick="last_page();tabela_contribuintes(num_pagina,procura,tipo);">
          >>
        </button> 
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
    <script src="//code.jquery.com/jquery.min.js"></script>
<script type="text/javascript">
  var procura='';
  var num_pagina=1;
  var tipo='';

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