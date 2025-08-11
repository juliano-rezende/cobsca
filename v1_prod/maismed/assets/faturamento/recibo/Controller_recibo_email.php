<?php
// blibliotecas
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

$FRM_email    = isset( $_POST['email']) ? strtolower($_POST['email']) : tool::msg_erros("Campo email invalido.");
$FRM_arquivo  = isset( $_POST['arq'])   ? $_POST['arq']   : tool::msg_erros("Campo arq faltando.");
$FRM_ids      = isset( $_POST['ids']) ? str_replace(",",";",$_POST['ids']) : tool::msg_erros("Campo ids invalido.");

//$FRM_email    = "julianoreze@gmail.com";
//$FRM_arquivo  = 'marcia dos reis souza .pdf';

// Inclui o arquivo class.phpmailer.php localizado na pasta class
require("../../../library/PHPMailer/PHPMailerAutoload.php");



// Inicia a classe PHPMailer
$mail = new PHPMailer(true);

// Define os dados do servidor e tipo de conexão
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->IsSMTP(); // Define que a mensagem será SMTP

try {

    $mail->Host = 'smtp.unifamilia.com.br'; // Endereço do servidor SMTP (Autenticação, utilize o host smtp.seudomínio.com.br)
    $mail->SMTPAuth   = true;  // Usar autenticação SMTP (obrigatório para smtp.seudomínio.com.br)
    $mail->Port       = 587; //  Usar 587 porta SMTP
    $mail->Username = 'info@unifamilia.com.br'; // Usuário do servidor SMTP (endereço de email)
    $mail->Password = 'uni1205*-25'; // Senha do servidor SMTP (senha do email usado)
    $mail->Priority = 1;

     //Define o remetente
     // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    $mail->SetFrom('info@unifamilia.com.br', 'Cartao unifamilia'); //Seu e-mail
    $mail->AddReplyTo('info@unifamilia.com.br', 'Cartao unifamilia'); //Seu e-mail
    $mail->Subject = 'Recibo de pagamento';//Assunto do e-mail

     //Define os destinatário(s)
     //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    $mail->AddAddress(''.$FRM_email.'', 'Recibo de pagamento');
    $mail->AddCC('info@unifamilia.com.b', 'Cópia');

     //Campos abaixo são opcionais
     //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
     //$mail->AddCC('destinarario@dominio.com.br', 'Destinatario'); // Copia
     //$mail->AddBCC('destinatario_oculto@dominio.com.br', 'Destinatario2`'); // Cópia Oculta
     $mail->AddAttachment('arquivos/'.$FRM_arquivo.'');      // Adicionar um anexo


     //Define o corpo do email
     $mail->MsgHTML('Recibo de pagamento referente as parcelas : '.$FRM_ids.'');

     ////Caso queira colocar o conteudo de um arquivo utilize o método abaixo ao invés da mensagem no corpo do e-mail.
     //$mail->MsgHTML(file_get_contents('arquivo.html'));


    $mail->Send();// Limpa os destinatários e os anexos
    $mail->ClearAllRecipients();
    $mail->ClearAttachments();

     echo "Mensagem enviada com sucesso";

    //caso apresente algum erro é apresentado abaixo com essa exceção.
    }catch (phpmailerException $e) {
      echo $e->errorMessage(); //Mensagem de erro costumizada do PHPMailer
}
?>