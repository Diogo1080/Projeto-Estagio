<?php
    require ('ligacao.php');
?>
<!DOCTYPE html>
<html lang="pt">

    <!-- Ligação aos links e config da Head -->
    <?php include('head.php'); ?>

    <!-- Head custom do calendário -->
    <head>
        <meta charset='utf-8' />
        <link href='css/core/main.min.css' rel='stylesheet' />
        <link href='css/daygrid/main.min.css' rel='stylesheet' />
        <link rel="stylesheet" href="css/personalizado.css">

        <script src='js/core/main.min.js'></script>
        <script src='js/interaction/main.min.js'></script>
        <script src='js/daygrid/main.min.js'></script>
        <script src='js/core/locales/pt-br.js'></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        

        <link href="https://unpkg.com/@fullcalendar/core@4.4.0/main.min.css" rel="stylesheet">
        <link href="https://unpkg.com/@fullcalendar/daygrid@4.4.0/main.min.css" rel="stylesheet">
        <link href="https://unpkg.com/@fullcalendar/timegrid@4.4.0/main.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
        <script src="https://unpkg.com/@fullcalendar/core@4.4.0/main.min.js"></script>
        <script src="https://unpkg.com/@fullcalendar/core@4.4.0/locales-all.min.js"></script>
        <script src="https://unpkg.com/@fullcalendar/interaction@4.4.0/main.min.js"></script>
        <script src="https://unpkg.com/@fullcalendar/daygrid@4.4.0/main.min.js"></script>
        <script src="https://unpkg.com/@fullcalendar/timegrid@4.4.0/main.min.js"></script>
        <script src="https://unpkg.com/@fullcalendar/interaction/main.min.js"></script>    
        <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>

        <script src="js/personalizado.js"></script>
        <script src="js/bootstrap.main.min.js"></script>
        <title>Calendario</title>

        <!-- So para mudar o titulo -->
        <style>
            #calendar h2{
            color:black;
            }
        </style>
    </head>
    <body>

    <div class="container"> 
        <!-- Navbar -->
        <?php include('navbar_dashboard.php'); ?>

        <!-- Conteúdo da página -->
        <div class="card" style=" margin-top:25px;">

            <!-- Titulo + Botões  -->
            <div class="card-header">
                <h3 class="panel-title">Calendário do Clube</h3>
            </div>

            <!-- Tabelas / Forms / TUDO -->
            <div class="card-body" style="padding: 25px;">

            <div id="warning" onclick="this.style.display='none'"></div>

            <div id='calendar'></div>

            <!-- Div a dizer alguma info -->
            <div class="d-flex justify-content-center">

                <div class="alert alert-info text-center" role="alert" style="width:50%; margin-top:25px;">
                Alerta: sei lá: rádio Botaréu xD 69
                </div>

            </div>
    

            <div class="modal fade" id="modal_calendario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Evento</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <ul class="nav nav-tabs">
                                <li class="nav-item" ><a id="nav_treinos" class="nav-link active" data-toggle="tab" href="#Treinos">Treinos</a></li>
                                <li class="nav-item" ><a id="nav_jogos" class="nav-link disabled" data-toggle="tab" href="#Jogos">Jogos</a></li>
                            </ul>
                
                            <div class="tab-content">
                                <div id="Treinos" class="tab-pane fade show active">
                                    <h3>Treinos</h3>
                                    <form id="treino" method="POST" enctype="multipart/form-data">
                                        <input  class="input_calendario" name="treino_id" id="treino_id" value="" hidden>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Título</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="title" class="input_calendario form-control" id="treino_titulo" placeholder="Título do Treino">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Color</label>
                                            <div class="col-sm-10">
                                                <input class="input_calendario" id="treino_input_color" hidden name="color">
                                                <i id="treino_color" class="fas fa-square"></i>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label" for="treino_dt_inicio">Início do Treino</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="start" class="input_calendario form-control" id="treino_dt_inicio" onkeypress="DataHora(event, this)">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label" for="treino_dt_fim">Final do Treino</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="end" class="input_calendario form-control" id="treino_dt_fim" onkeypress="DataHora(event, this)">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label" for="select_equipa">Equipa</label>
                                            <div class="col-sm-10">
                                            <select id="treino_select_equipa" name="equipa" onchange="buscar_atletas_treino(this.value,data_final,'')" class="input_calendario form-control" required>
                                                    <option  disabled selected>--Selecione uma equipa--</option>
                                                    <?php 
                                                        $equipas=$con->prepare("SELECT * FROM equipas");
                                                        $equipas->execute();
                                                        $equipas=$equipas->get_result();
                                                        while ($linha=$equipas->fetch_assoc()) {
                                                            ?>
                                                                <option value="<?php echo $linha['id_equipa']; ?>"><?php echo $linha['nome']; ?></option>
                                                            <?php
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div>
                                            
                                        </div>
                                        <div id="treino_mostrar_atletas">
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-10">
                                                <button type="submit" name="treino_submit" id="treino_insert" value="" class="btn btn-success">   
                                                    Adicionar
                                                </button>
                                                <button type="submit" name="treino_submit" id="treino_update" value="" class="btn btn-success">   
                                                    Atualizar
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div id="Jogos" class="tab-pane fade">
                                    <h3>Jogos</h3>
                                    <form id="jogo" method="POST" enctype="multipart/form-data">
                                        <input class="input_calendario" name="jogo_id" id="jogo_id" value="" hidden>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Título</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="title" class="input_calendario form-control" id="jogo_titulo" placeholder="Título do jogo">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Color</label>
                                            <div class="col-sm-10">
                                                <input class="input_calendario" id="jogo_input_color" hidden name="color">
                                                <i id="jogo_color" class="fas fa-square"></i>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label" for="jogo_dt_inicio">Início do Jogo</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="start" class="input_calendario form-control" id="jogo_dt_inicio" onkeypress="DataHora(event, this)">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label" for="jogo_dt_fim">Final do Jogo</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="end" class="input_calendario form-control" id="jogo_dt_fim" onkeypress="DataHora(event, this)">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label" for="select_equipa">Equipa</label>
                                            <div class="col-sm-10">
                                            <select id="jogo_select_equipa" name="equipa" class="input_calendario form-control" onchange="buscar_atletas_jogos(this.value,data_final,'')" required>
                                                    <option  disabled selected>--Selecione uma equipa--</option>
                                                    <?php 
                                                        $equipas=$con->prepare("SELECT * FROM equipas");
                                                        $equipas->execute();
                                                        $equipas=$equipas->get_result();
                                                        while ($linha=$equipas->fetch_assoc()) {
                                                            ?>
                                                                <option value="<?php echo $linha['id_equipa']; ?>"><?php echo $linha['nome']; ?></option>
                                                            <?php
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="jogos_mostrar_atletas">
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-10">
                                                <button type="submit" name="jogo_submit" id="jogo_insert" value="" class="btn btn-success">   
                                                    Adicionar
                                                </button>
                                                <button type="submit" name="jogo_submit" id="jogo_update" value="" class="btn btn-success">   
                                                    Atualizar
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
               
            </div>
        </div>
    
    </div>
    </body>
</html>
<script type="text/javascript">
    function buscar_atletas_treino(id_equipa,data_final,id_treino){
        $.post(
            'calend_buscar_atletas_treino.php', 
            {
                'id_equipa': id_equipa,
                'data_final': data_final,
                'id_treino': id_treino
            }, 
            function(response) {
                var resposta=response.split("«");
                document.getElementById('treino_color').style.color=resposta[1];
                document.getElementById('treino_input_color').value=resposta[1];
                $('#treino_mostrar_atletas').html(resposta[0]);
            }
        )
    }
    function buscar_atletas_jogos(id_equipa,data_final,id_jogo){
        $.post(
            'calend_buscar_atletas_jogo.php', 
            {
                'id_equipa': id_equipa,
                'data_final': data_final,
                'id_jogo': id_jogo
            }, 
            function(response) {
                var resposta=response.split("«");
                document.getElementById('jogo_color').style.color=resposta[1];
                document.getElementById('jogo_input_color').value=resposta[1];
                $('#jogos_mostrar_atletas').html(resposta[0]);
            }
        )
    }
</script>

