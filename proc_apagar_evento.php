<?php

session_start();

include_once './ligacao.php';

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!empty($id)) {
    $query_event = "DELETE FROM treinos WHERE id=:id";
    $delete_event = $conn->prepare($query_event);
    
    $delete_event->bindParam("id", $id);
    
    if($delete_event->execute()){
        $_SESSION['msg'] = '<div class="alert alert-success" role="alert">Treino apagado com sucesso!</div>';
        header("Location: calendario.php");
    }else{
        $_SESSION['msg'] = '<div class="alert alert-danger" role="alert">Erro: Treino não foi apagado com sucesso!</div>';
        header("Location: calendario.php");
    }
} else {
    $_SESSION['msg'] = '<div class="alert alert-danger" role="alert">Erro: Treino não foi apagado com sucesso!</div>';
    header("Location: calendario.php");
}
