<?php
require_once"../../../sessao.php";
require_once("../../../conexao.php");	
$cfg->set_model_directory('../../../models/');
require_once("../../../functions/funcoes.php");

$empresa=empresa::find_by_cdempresa($SCA_Id_empresa);
$seg_empresa=seg_emp::find_by_cdempresa($SCA_Id_empresa);

//recupera o endereço
$uf=estado::find_by_cdestado($empresa->cdestado);
$cidade=cidade::find_by_cdcidade($empresa->cdcidade);
$bairro=bairro::find_by_cdbairro($empresa->cdbairro);
$rua=logradouro::find_by_cdlogradouro($empresa->cdlogradouro);
 
// Inclui o arquivo class.phpmailer.php localizado na pasta phpmailer
require("../../../librarys/phpmailer/PHPMailerAutoload.php");

// Inicia a classe PHPMailer
$mail = new PHPMailer();

// Define os dados do servidor e tipo de conexão
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->IsSMTP(); // Define que a mensagem será SMTP
$mail->Host = "mail.unifamilia.com.br"; // Endereço do servidor SMTP
$mail->SMTPAuth = true; // Usar autenticação SMTP (obrigatório para smtp.seudomínio.com.br)
$mail->SMTP_PORT = 587;                     // Porta do servidor SMTP
$mail->Username = 'seguro@unifamilia.com.br'; // Usuário do servidor SMTP (endereço de email)
$mail->Password = 'jr120584'; // Senha do servidor SMTP (senha do email usado)
$mail->Priority = 1;

// Define o remetente
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->AddCustomHeader('Reply-to:seguro@unifamilia.com.br'); 
$mail->From = "seguro@unifamilia.com.br"; // Seu e-mail
$mail->FromName = utf8_decode("Sistema De Cobrança"); // Seu nome


// Plain text body (for mail clients that cannot read HTML)
$text_body  = "PLANILHA DE MOVIMENTAÇÃO SEGURO \n\n";
$text_body .= "E-MAIL GERADO ALTOMATICAMENTE FAVOR NÃO RESPONDER.\n\n";
$text_body .= "Obrigado, \n";
$text_body .= "";

// Define os destinatário(s)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->AddAddress($seg_empresa->emailseg);// email da seguradora
$mail->AddAddress($empresa->email);// copia para a empresa
$mail->AddCC('seguro@unifamilia.com.br', 'Sistema de Cobrança'); // Copia
//$mail->AddBCC('fulano@dominio.com.br', 'Fulano da Silva'); // Cópia Ocult

// Define os dados técnicos da Mensagem
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->IsHTML(true); // Define que o e-mail será enviado como HTML
//$mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)

// Define a mensagem (Texto)

$corpo='<p>OBS: E-mail gerado altomaticamente pelo sistema favor não responder</p>
<p>MOVIMENTAÇÃO DE SEGURO</p>

<p><img src="../../../imagens/logo_empresas/'.$empresa->logomarca.'"   alt=""/></p>

<p>Empresa: '.strtoupper($empresa->nomefantasia).'</p>

<p>Cnpj: '.mascara_campos("??.???.???/????-??",$empresa->cnpj).'</p>

<p>Endereço:'.strtoupper($rua->descricao.", ".$empresa->num." - ".$bairro->descricao." - ".$cidade->descricao." - ".$uf->sigla." - CEP: ".mascara_campos("??.???-???",$empresa->cep)).'</p>

<p>Responsável: '.$empresa->responsavel.'</p>

<p>Tel '.$empresa->telefonefixo.'  - Celular:  '.$empresa->telefonecel.'-  E-mail: '.$empresa->email.'</p>

<p><strong>Antes de imprimir, pense em sua responsabilidade e compromisso com o Meio Ambiente.</p>

<p></strong>A mensagem e seus anexos têm caráter confidencial. Qualquer uso integral ou parcial desta mensagem é proibido, sendo passível das ações judiciais cabíveis. </p>

<p>GRUPO Unifamilia informa que a responsabilidade pela mensagem acima é exclusivamente de seu autor e não se responsabiliza pelo seu conteúdo.</p>

<p>Segue em anexo a planilha de movimentação.</p>';

// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->Subject  = "PLANILHA DE MOVIMENTACAO SEGURO"; // Assunto da mensagem
$mail->msgHTML($corpo);
$mail->AltBody = $text_body;

// Define os anexos (opcional)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->AddAttachment("../Plan_enviadas/empresa_".$SCA_Id_empresa."/".$_POST['arquivo']."", "".$_POST['arquivo']."");  // Insere um anexo

// Envia o e-mail
$enviado = $mail->Send();

// Limpa os destinatários e os anexos
$mail->ClearAllRecipients();
$mail->ClearAttachments();


// altera a data de envio
$update=arq_seguradora::find_by_arquivo($_POST['arquivo']);
$update->update_attributes(
									array(
										'dataenvio'=>''.date("Y-m-d h:m:s").''
										));
// Exibe uma mensagem de resultado
if ($enviado) {
echo "E-MAIL ENVIADO COM SUCESSO !";
} else {
echo "Não foi possível enviar o e-mail.<br /><br />";
echo "<b>Informações do erro:</b> <br />" . $mail->ErrorInfo;
}
 
?>