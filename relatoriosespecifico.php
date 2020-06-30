<?php
	session_start();
	include_once('ligacao.php');
?>
<!DOCTYPE html>
<html lang="pt">
	<head>
		<meta charset="utf-8">
		<title>Relatório Especifico</title>
	<head>
	<body>
		<?php
		if(isset($_POST['msg_contato'])){
			// Definimos o nome do arquivo que será exportado
			$arquivo = 'Relatorio.xls';
			
			// Criamos uma tabela HTML com o formato da planilha
			$html = '';
			$html .= '<table border="1">';
			$html .= '<tr>';
			$html .= '<td colspan="4">Relatório Especifico</tr>';
			$html .= '</tr>';
			
			
			$html .= '<tr>';
			$html .= '<td><b>ID</b></td>';
			$html .= '<td><b>Nome</b></td>';
			$html .= '<td><b>Sexo</b></td>';
			$html .= '<td><b>Data de Nascimento</b></td>';
			$html .= '</tr>';
			
			foreach($_POST['msg_contato'] as $id => $msg_contato){
				//echo "ID do item: $id <br>";
				//Selecionar todos os itens da tabela 
				$result_msg_contatos = "SELECT contribuintes.id_contribuinte, contribuintes.nome, contribuintes.sexo, contribuintes.dt_nasc, contribuintes.tipo_contribuinte FROM contribuintes INNER JOIN atletas ON contribuintes.id_contribuinte = atletas.id_contribuinte WHERE contribuintes.id_contribuinte = $id LIMIT 1";
				$resultado_msg_contatos = mysqli_query($con , $result_msg_contatos);
				
				while($row_msg_contatos = mysqli_fetch_assoc($resultado_msg_contatos)){
					$html .= '<tr>';
					$html .= '<td>'.$row_msg_contatos["id_contribuinte"].'</td>';
					$html .= '<td>'.$row_msg_contatos["nome"].'</td>';
					$html .= '<td>'.$row_msg_contatos["sexo"].'</td>';
					$html .= '<td>'.$row_msg_contatos["dt_nasc"].'</td>';
					$html .= '</tr>';
					;
				}
			}
			// Configurações header para forçar o download
			header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
			header ("Cache-Control: no-cache, must-revalidate");
			header ("Pragma: no-cache");
			header ("Content-type: application/x-msexcel");
			header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );
			header ("Content-Description: PHP Generated Data" );
			// Envia o conteúdo do arquivo
			echo $html;
			exit;
		}else{
			echo "Nenhum item selecionado";
		}
		
		?>
	</body>
</html>