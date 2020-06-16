<?php 
	function is_admin(){
		if ($_SESSION['permissao']<>1) {
			header("Location: home.php");
		}
	}
?>