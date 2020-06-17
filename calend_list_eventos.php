<?php
    include 'ligacao.php';

    $resultado_events = $con->prepare("SELECT * FROM treinos");
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