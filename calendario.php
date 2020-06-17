<?php
    require ('ligacao.php');
?>
<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset='utf-8' />
        <link href='css/core/main.min.css' rel='stylesheet' />
        <link href='css/daygrid/main.min.css' rel='stylesheet' />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.6/css/all.css">
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
    </head>
    <body>
        <?php
            require 'nav.php';  
        ?>
        <div id="warning" onchange="setTimeout(() => {this.style.display='none';}, 1);" onclick="this.style.display='none'"></div>

        <div id='calendar'></div>

        <div class="modal fade" id="visualizar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detalhes do Treino</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="visevent">
                            <dl class="row">
                                <dt class="col-sm-3">ID do Treino</dt>
                                <dd class="col-sm-9" id="id"></dd>

                                <dt class="col-sm-3">Título do Treino</dt>
                                <dd class="col-sm-9" id="title"></dd>

                                <dt class="col-sm-3">Início do Treino</dt>
                                <dd class="col-sm-9" id="start"></dd>

                                <dt class="col-sm-3">Fim do Treino</dt>
                                <dd class="col-sm-9" id="end"></dd>
                            </dl>
                            <button class="btn btn-warning btn-canc-vis">Editar</button>
                            <a href="" id="apagar_evento" class="btn btn-danger">Apagar</a>
                        </div>
                        <div class="formedit">
                            <span id="msg-edit"></span>
                            <form id="editevent" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id" id="id" >
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="title">Título</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="title" class="form-control" id="title" placeholder="Título do Treino">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="color">Color</label>
                                    <div class="col-sm-10">
                                        <input hidden name="color"><i class="fas fa-circle"></i>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Início do Treino</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="start" class="form-control" id="start" onkeypress="DataHora(event, this)">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Final do Treino</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="end" class="form-control" id="end"  onkeypress="DataHora(event, this)">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-10">
                                        <button type="button" class="btn btn-primary btn-canc-edit">Cancelar</button>
                                        <button type="submit" name="CadEvent" id="CadEvent" value="CadEvent" class="btn btn-warning">Adicionar</button>
                                    </div>
                                </div>
                            </form>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="cadastrar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Adicionar Evento</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul class="nav nav-tabs">
                            <li class="nav-item" ><a class="nav-link active" data-toggle="tab" href="#Treinos">Treinos</a></li>
                            <li class="nav-item" ><a class="nav-link" data-toggle="tab" href="#Jogos">Jogos</a></li>
                        </ul>
              
                        <div class="tab-content">
                            <div id="Treinos" class="tab-pane fade show active">
                                <h3>Treinos</h3>
                                <form id="addevent" method="POST" enctype="multipart/form-data">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Título</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="title" class="form-control" id="title" placeholder="Título do Treino">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Color</label>
                                        <div class="col-sm-10">
                                            <input id="input_color" hidden name="color">
                                            <i id="color" class="fas fa-square"></i>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label" for="start">Início do Treino</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="start" class="form-control" id="start" onkeypress="DataHora(event, this)">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label" for="end">Final do Treino</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="end" class="form-control" id="end" onkeypress="DataHora(event, this)">
                                        </div>
                                    </div>
                                    <div>
                                        <label for="select_equipa">Equipa</label>
                                        <select id="select_equipa" name="equipa" onchange="buscar_atletas(this.value,data_final)" required>
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
                                    <div id="mostrar_atletas">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-10">
                                            <button type="submit" name="CadEvent" id="CadEvent" value="CadEvent" class="btn btn-success">Adicionar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div id="Jogos" class="tab-pane fade">
                                <h3>Jogos</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
<script type="text/javascript">
    function buscar_atletas(id_equipa,data_final){
        $.post(
            'calend_buscar_atletas.php', 
            {
                'id_equipa': id_equipa,
                'data_final': data_final
            }, 
            function(response) {
                var resposta=response.split("«");
                document.getElementById('color').style.color=resposta[1];
                document.getElementById('input_color').value=resposta[1];
                $('#mostrar_atletas').html(resposta[0]);
            }
        )
    }
</script>

