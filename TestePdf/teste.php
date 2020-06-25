<?php	

	//referenciar o DomPDF com namespace
	use Dompdf\Dompdf;

	// include autoloader
	require_once("dompdf/autoload.inc.php");

	//Criando a Instancia
	$dompdf = new DOMPDF();

	// Carrega seu HTML
	$dompdf->load_html('
			<h1 style="text-align: center;">Teste - Gerar PDF</h1>
			<p>Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.</p>

			<p>Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.Teste relatorio.</p>
		');

	//Renderizar o html
	$dompdf->render();

	//Exibibir a página
	$dompdf->stream(
		"relatorio.pdf", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
?>