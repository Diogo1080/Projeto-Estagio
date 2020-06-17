<?php
    include 'ligacao.php';

    $resultado_events = $con->prepare("SELECT DISTINCT treinos.*,equipas.cor FROM treinos INNER JOIN equipa_treinos ON treinos.id_treino=equipa_treinos.id_treino INNER JOIN equipas ON equipa_treinos.id_equipa=equipas.id_equipa");
    $resultado_events->execute();
    $resultado_events=$resultado_events->get_result();
    $eventos = [];

    while($row_events = $resultado_events->fetch_assoc()){
        $id = $row_events['id_treino'];
        $title = $row_events['titulo'];
        $color = $row_events['cor'];
        $start = $row_events['dt_inicio'];
        $end = $row_events['dt_fim'];
        
        $eventos[] = [
            'id' => $id, 
            'title' => $title, 
            'color' => $color, 
            'start' => $start, 
            'end' => $end, 
        ];
    }

    echo json_encode($eventos);
?>