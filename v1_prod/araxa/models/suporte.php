<?php

class suporte extends ActiveRecord\Model{



	static function Email_Suporte($assunto,$msg,$empresas_id,$arquivo,$msg_ret=false){ // assunto do email, msg do email, id da empresa, caminho do arquivo que ira em anexo


	// recupera os dados da empresa
	$query_empresa=empresas::find($empresas_id);

	// Inicia a classe PHPMailer
	$mail = new PHPMailer(true);

	// Define os dados do servidor e tipo de conexão
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail->IsSMTP(); // Define que a mensagem será SMTP

	try {

	     $mail->Host       = 'smtp.unifamilia.com.br'; // Endereço do servidor SMTP (Autenticação, utilize o host smtp.seudomínio.com.br)
	     $mail->SMTPAuth   = true;  // Usar autenticação SMTP (obrigatório para smtp.seudomínio.com.br)
	     $mail->Port       = 587; //  Usar 587 porta SMTP
	     $mail->Username   = 'suporte@unifamilia.com.br'; // Usuário do servidor SMTP (endereço de email)
	     $mail->Password   = 'jr120584'; // Senha do servidor SMTP (senha do email usado)

	     //Define o remetente
	     // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	     $mail->SetFrom('suporte@unifamilia.com.br', 'Unicob - sistema de controle de associados'); //Seu e-mail
	     $mail->AddReplyTo('suporte@unifamilia.com.br', 'Unicob - sistema de controle de associados'); //Seu e-mail
	     $mail->Subject = ''.$assunto.'';//Assunto do e-mail


	     //Define os destinatário(s)
	     //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	     $mail->AddAddress('julianoreze@gmail.com', ''.$assunto.'');

	     //Campos abaixo são opcionais
	     //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	     $mail->AddCC('suporte@unifamilia.com.br', 'Unicob - sistema de controle de associados'); // Copia
	     //$mail->AddBCC('destinatario_oculto@dominio.com.br', 'Destinatario2`'); // Cópia Oculta
	     //$mail->AddAttachment('images/phpmailer.gif');      // Adicionar um anexo

	     //Define o corpo do email
	     $mail->MsgHTML($msg.' - empresa:'.$query_empresa->razao_social.' ');

	     ////Caso queira colocar o conteudo de um arquivo utilize o método abaixo ao invés da mensagem no corpo do e-mail.
	     //$mail->MsgHTML(file_get_contents('arquivo.html'));

	    if($arquivo !=""){
	     $mail->AddAttachment($arquivo);  // Insere um anexo
	 	}

	     $mail->Send();

		if($msg_ret == true){
	    /* mensagem de tela */
	     $return= '<div class="uk-alert uk-alert-warning" style="margin:0;"> <i class="uk-icon-send " ></i>
	      			Ops! Ocorreu um erro inesperado. Não se preocupe já informamos o suporte por e-mail.</div>';
		}else{$return="";}
	    //caso apresente algum erro é apresentado abaixo com essa exceção.
	    }catch (phpmailerException $e) {
	      $return = "<div class='uk-alert uk-alert-warning'> Não foi possivel enviar e-mail.</div></br>";
	      $return.= "<div class='uk-alert uk-alert-warning'> Erro ".$e->errorMessage()."</div></br>";
	    }


	return $return;

	}


static function Email_Seguradora($assunto,$msg,$empresas_id,$arquivo){ // assunto do email, msg do email, id da empresa, caminho do arquivo que ira em anexo


	// recupera os dados da empresa
	$query_empresa=empresas::find($empresas_id);

	// Inicia a classe PHPMailer
	$mail = new PHPMailer(true);

	// Define os dados do servidor e tipo de conexão
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail->IsSMTP(); // Define que a mensagem será SMTP

	try {

	     $mail->Host       = 'smtp.unifamilia.com.br'; // Endereço do servidor SMTP (Autenticação, utilize o host smtp.seudomínio.com.br)
	     $mail->SMTPAuth   = true;  // Usar autenticação SMTP (obrigatório para smtp.seudomínio.com.br)
	     $mail->Port       = 587; //  Usar 587 porta SMTP
	     $mail->Username   = 'seguros@unifamilia.com.br'; // Usuário do servidor SMTP (endereço de email)
	     $mail->Password   = 'jr120584'; // Senha do servidor SMTP (senha do email usado)

	     //Define o remetente
	     // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	     $mail->SetFrom('seguros@unifamilia.com.br', 'Unicob - sistema de controle de associados'); //Seu e-mail
	     $mail->AddReplyTo('seguros@unifamilia.com.br', 'Unicob - sistema de controle de associados'); //Seu e-mail
	     $mail->Subject = ''.$assunto.'';//Assunto do e-mail


	     //Define os destinatário(s)
	     //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	     $mail->AddAddress('julianoreze@gmail.com', ''.$assunto.'');

	     //Campos abaixo são opcionais
	     //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	     $mail->AddCC('seguros@unifamilia.com.br', 'Unicob - sistema de controle de associados'); // Copia
	     //$mail->AddBCC('destinatario_oculto@dominio.com.br', 'Destinatario2`'); // Cópia Oculta
	     //$mail->AddAttachment('images/phpmailer.gif');      // Adicionar um anexo

	     //Define o corpo do email
	     $mail->MsgHTML($msg.' - empresa:'.$query_empresa->razao_social.' ');

	     ////Caso queira colocar o conteudo de um arquivo utilize o método abaixo ao invés da mensagem no corpo do e-mail.
	     //$mail->MsgHTML(file_get_contents('arquivo.html'));

	    if($arquivo !=""){
	     $mail->AddAttachment($arquivo);  // Insere um anexo
	 	}

	     $mail->Send();

	    /* mensagem de tela */
	      echo '<div class="uk-alert uk-alert-warning" style="margin:0;"> <i class="uk-icon-send " ></i>
	      			Ops! Ocorreu um erro inesperado. Não se preocupe já informamos o suporte por e-mail.</div>';

	    //caso apresente algum erro é apresentado abaixo com essa exceção.
	    }catch (phpmailerException $e) {
	      echo "<div class='uk-alert uk-alert-warning'> Não foi possivel enviar e-mail.</div></br>";
	      echo "<div class='uk-alert uk-alert-warning'> Erro ".$e->errorMessage()."</div></br>";

	    }


	return $return;

	}


}
?>