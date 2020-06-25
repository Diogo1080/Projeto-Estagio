<!-- Ligação á base de Dados -->
<?php require('ligacao.php'); ?>

<html>

<!-- Ligação aos links e config da Head -->
<?php include('head.php'); ?>
<body>

	<div class="container">

        <!-- Conexão da navbar -->
	      <?php include('navbar_dashboard.php'); ?>

        <center style=" margin-top:25px;"><h1>Inserir Contribuinte</h1></center>

        <!-- Infos Basicas -->
        <div class="card" style=" margin-top:25px;">


            <div class="card-header">
                <h3 class="panel-title">Informações Básicas</h3>
            </div>


            <div class="card-body">

            <div class="row">

<div class="col-md-4">
        <div class="form-group">
          <label for="exampleFormControlFile1">Inserir Fotografia</label>
          <input type="file" class="form-control-file" id="exampleFormControlFile1">
        </div>
</div>

<div class="col-md-8">
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="inputEmail4">Nome</label>
            <input type="email" class="form-control" id="inputEmail4">
          </div>
          <div class="form-group col-md-6">
            <label for="inputPassword4">CC</label>
            <input type="text" class="form-control" id="inputPassword4">
          </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
              <label for="inputEmail4">NIF</label>
              <input type="email" class="form-control" id="inputEmail4">
            </div>
            <div class="form-group col-md-6">
              <label for="inputPassword4">Data de Nascimento</label>
              <input class="form-control" type="date">
            </div>
          </div>

        <div class="form-row">
            <div class="form-group col-md-6">
              <label for="inputEmail4">Sexo</label>
              <select class="form-control">
                <option>Default select</option>
              </select>
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

        
        <!-- Infos de Contacto -->
        <div class="card" style=" margin-top:25px;">


            <div class="card-header">
                <h3 class="panel-title">Informações de Contacto</h3>
            </div>


            <div class="card-body">
            
              <div class="form-group">
                <label for="inputAddress">Morada</label>
                <input type="text" class="form-control" id="inputAddress" placeholder="Insira a sua Morada">
              </div>
              <div class="form-group">
                <label for="inputAddress2">Localidade</label>
                <input type="text" class="form-control" id="inputAddress2" placeholder="Insira a sua Localidade">
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="inputCity">Concelho</label>
                  <input type="text" class="form-control" id="inputCity">
                </div>
                <div class="form-group col-md-4">
                  <label for="inputState">Freguesia</label>
                  <input type="text" class="form-control" id="inputState">
                </div>
                <div class="form-group col-md-2">
                  <label for="inputZip">Código-Postal</label>
                  <input type="text" class="form-control" id="inputZip">
                </div>
              </div>
              <div class="form-group">
                <label for="inputAddress2">E-Mail</label>
                <input type="text" class="form-control" id="inputAddress2" placeholder="Insira o seu E-Mail">
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="inputEmail4">Telemóvel</label>
                  <input type="email" class="form-control" id="inputEmail4">
                </div>
                <div class="form-group col-md-6">
                  <label for="inputPassword4">Telefone</label>
                  <input type="text" class="form-control" id="inputPassword4">
                </div>
              </div>

            </div>
        </div>


        <!-- ATLETA - INFO BASICA -->
        <div class="card" style=" margin-top:25px;">


            <div class="card-header">
                <h3 class="panel-title">ATLETA - Informações EXTRA</h3>
            </div>


            <div class="card-body">

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="inputEmail4">Valor Mensalidade</label>
                  <input type="email" class="form-control" id="inputEmail4">
                </div>
                <div class="form-group col-md-6">
                  <label for="inputPassword4">Jóia</label>
                  <input type="password" class="form-control" id="inputPassword4">
                </div>
              </div>

              <div class="form-group">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="gridCheck">
                  <label class="form-check-label" for="gridCheck">
                    Pagou Jóia
                  </label>
                </div>
              </div>

            </div>
        </div>        
        
        
        <!-- ATLETA - INFO TECNICA -->
        <div class="card" style=" margin-top:25px;">


            <div class="card-header">
                <h3 class="panel-title">ATLETA - Informações Técnicas</h3>
            </div>


            <div class="card-body">

            
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

              <div class="row">
                <div class="col">
                  <label>Capacidade de Trabalho</label>
                  <input type="text" class="form-control" placeholder="">
                </div>
                <div class="col">
                  <label>Agressividade</label>
                  <input type="text" class="form-control" placeholder="">
                </div>
                <div class="col">
                  <label>Autoconfiança</label>
                  <input type="text" class="form-control" placeholder="">
                </div>
              </div>

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

              <div class="row">
                <div class="col">
                  <label>Caráter / Personalidade</label>
                  <input type="text" class="form-control" placeholder="">
                </div>
                <div class="col">
                  <label>Game Sense</label>
                  <input type="text" class="form-control" placeholder="">
                </div>
                <div class="col">
                  <label>Auto Controlo</label>
                  <input type="text" class="form-control" placeholder="">
                </div>
              </div>

              <div class="row">
                <div class="col">
                  <label>Condição Física Geral</label>
                  <input type="text" class="form-control" placeholder="">
                </div>
                <div class="col">
                  <label>Resistência (Lesões)</label>
                  <input type="text" class="form-control" placeholder="">
                </div>
              </div>

            </div>
        </div>

        <!-- ATLETA - INFO MEDICA -->
        <div class="card" style=" margin-top:25px;">


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

        <div class="card" style=" margin-top:25px;">

              <div class="card-header">
              <h3 class="panel-title">Sócios - Informações EXTRA</h3>
              </div>

              <div class="card-body">

              <div class="row">
                <div class="col">
                <label>Nº de Sócio</label>
                  <input type="text" class="form-control">
                </div>
              </div>
                
              <div class="row">

                  <div class="col">
                  <label>Valor Quota</label>
                    <input type="text" class="form-control">
                  </div>

                  <div class="col">
                    <label for="inputState">Opção de pagamento</label>
                    <select id="inputState" class="form-control">
                      <option selected>Choose...</option>
                      <option>...</option>
                    </select>
                  </div>

              </div>
        </div>
      </div>

        <div class="card" style=" margin-top:25px;">
              <div class="card-header">
              <h3 class="panel-title">Encarregados de Euducação- Informações EXTRA</h3>
              </div>
              <div class="card-body">

              <h3 class="panel-title">Aqui =? ligação ao Pop Up ???</h3>

              </div>
          </div>

	      
</body>
</html>