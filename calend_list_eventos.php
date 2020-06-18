<?php
    include 'ligacao.php';

    $treinos = $con->prepare("SELECT DISTINCT treinos.*,equipas.cor,equipas.id_equipa FROM treinos INNER JOIN equipa_treinos ON treinos.id_treino=equipa_treinos.id_treino INNER JOIN equipas ON equipa_treinos.id_equipa=equipas.id_equipa");
    $treinos->execute();
    $treinos=$treinos->get_result();
    $eventos = [];

    while($row_events = $treinos->fetch_assoc()){
        $eventos[] = [
            'id' => $row_events['id_treino'], 
            'title' => $row_events['titulo'], 
            'color' => $row_events['cor'], 
            'start' => $row_events['dt_inicio'], 
            'end' => $row_events['dt_fim'], 
            'extendedProps'=>[
                'id_equipa'=>$row_events['id_equipa'],
                'tipo'=>'Treino'
            ]
        ];
    }
    $jogos = $con->prepare("SELECT DISTINCT jogos.*,equipas.cor,equipas.id_equipa FROM jogos INNER JOIN equipa_convocados ON jogos.id_jogo=equipa_convocados.id_jogo INNER JOIN equipas ON equipa_convocados.id_equipa=equipas.id_equipa ");
    $jogos->execute();
    $jogos=$jogos->get_result();

    while($row_events = $jogos->fetch_assoc()){
        array_push($eventos,[
            'id' => $row_events['id_jogo'], 
            'title' => $row_events['titulo'], 
            'color' => $row_events['cor'], 
            'start' => $row_events['dt_inicio'], 
            'end' => $row_events['dt_fim'], 
            'extendedProps'=>[
                'id_equipa'=>$row_events['id_equipa'],
                'tipo'=>'Jogo'
            ]
        ]);
    }

    echo json_encode($eventos);
?>