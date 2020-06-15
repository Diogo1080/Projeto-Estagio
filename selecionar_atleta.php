<?php 
	require 'ligacao.php';
	if (!isset($_SESSION['array_atletas'])) {
		$_SESSION['array_atletas']=array();
	}
	if ($_POST['acao']==0) {
		$_SESSION['array_atletas'] = \array_diff($_SESSION['array_atletas'], ["$_POST[id]"]);
	}elseif($_POST['acao']==1){
		if (!in_array($_POST['id'],$_SESSION['array_atletas'])) {
			Array_push($_SESSION['array_atletas'],$_POST['id']);
		}
	}else{
		unset($_SESSION['array_atletas']);
	}
?>