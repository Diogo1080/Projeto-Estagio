<?php

include("class.phpmailer.php");

function email($para_email, $para_nome, $assunto, $html) {

  $mail2 = new PHPMailer;
  $mail2->IsSMTP();

  $mail2->From = 'EstrelaAzul@email.com';
  $mail2->FromName = 'Clube Estrela Azul';
  $mail2->Host       = 'nome-do-server';
  $mail2->Port       = 'Porta de saida (ex: 465)';
  $mail2->SMTPAuth   = true;
  $mail2->SMTPAuth   = 'ssl';
  $mail2->Username =   'EstrelaAzul@email.com';
  $mail2->Password =   'senha';
    
  $mail2->AddAddress($para_email, $para_nome);
  $mail2->Subject = $assunto;
    
  $mail2->AltBody = 'Para ver esta mensagem, use um programa compatível com HTML!';
    
  $mail2->MsgHTML($html);

  if ($mail2->Send()) {
    return "1";
  } else {
      return $mail2->ErrorInfo;
    }
  }

  $corpo_email = '<html><body><p>Feliz Aniversário ($nome do socio)! O Clube Estrela Azul deseja-lhe um feliz aniversário. Obrigado por fazer parte da familia CEA!</body></html>';

  $controle =  email('socio@email.com', '$nome do socio', 'Feliz Aniversário!', $corpo_email);
  if ($controle == "1") {
      echo "Email enviado com sucesso";
  } else {
      echo "Erro no envio do email: " . $controle->ErrorInfo;
  }

  $hoje = date("m-d");
  $sql = "SELECT nome, email FROM contribuintes WHERE date_format(dt_nasc, '%m-%d') = '$hoje'";
?>