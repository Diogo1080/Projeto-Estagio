<?php
	require 'PHPMailer/PHPMailerAutoload.php';
	
	$Mailer = new PHPMailer();
	
	//Uso de SMTP
	$Mailer->IsSMTP();
	
	//Enviar email em HTML
	$Mailer->isHTML(true);
	
	//Aceitar caracteres especiais
	$Mailer->Charset = 'UTF-8';
	
	//Configurações
	$Mailer->SMTPAuth = true;
	$Mailer->SMTPSecure = 'ssl';
	
	//nome do servidor
	$Mailer->Host = 'nome-do-server';
	//Porta de saida de email 
	$Mailer->Port = 'Porta de saida (ex: 465)';
	
	//Dados do e-mail de saida - autenticação
	$Mailer->Username = 'EstrelaAzul@dominio.com';
	$Mailer->Password = 'senha';
	
	//Email remetente (deve ser o mesmo de quem fez a autenticação)
	$Mailer->From = 'EstrelaAzul@dominio.com';
	
	//Nome do Remetente
	$Mailer->FromName = 'Estrela Azul';
	
	//Assunto da mensagem
	$Mailer->Subject = 'Titulo - Feliz Aniversário';
	
	//Corpo da Mensagem
	$Mailer->Body = 'Feliz Aniversário (nome do socio)!';
	
	//Corpo da mensagem em texto
	$Mailer->AltBody = 'Feliz Aniversário (nome do socio)! O Clube Estrela Azul deseja-lhe um feliz aniversário. Obrigado por fazer parte da familia CEA!';
	
	//Destinatario 
	$Mailer->AddAddress('utilizador@dominio.com');
	
	if($Mailer->Send()){
		echo "Email enviado com sucesso";
	}else{
		echo "Erro no envio do email: " . $Mailer->ErrorInfo;
	}
	
?>



