<?php
include_once './ligacao.php';

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//Converter a data e hora para o formato da Base de Dados
$data_start = str_replace('/', '-', $dados['start']);
$data_start_conv = date("Y-m-d H:i:s", strtotime($data_start));

$data_end = str_replace('/', '-', $dados['end']);
$data_end_conv = date("Y-m-d H:i:s", strtotime($data_end));

$insert_event = $con->prepare("INSERT INTO treinos (titulo, cor, data_inicio, data_fim) VALUES (?,?,?,?)");
$insert_event->bind_param('ssss', $dados['title'],$dados['color'],$data_start_conv,$data_end_conv);

if ($insert_event->execute()) {
    $retorna = ['sit' => true, 'msg' => '<div class="alert alert-success" role="alert">Treino adicionado com sucesso!</div>'];
    $_SESSION['msg'] = '<div class="alert alert-success" role="alert">Treino adicionado com sucesso!</div>';
} else {
    $retorna = ['sit' => false, 'msg' => '<div class="alert alert-danger" role="alert">Erro: Treino não foi adicionado com sucesso!</div>'];
}

//header('Content-Type: application/json');
echo json_encode($retorna);