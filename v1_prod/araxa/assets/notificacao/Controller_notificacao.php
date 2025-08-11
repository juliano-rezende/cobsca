<?php
$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../sessao.php";
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');


$FRM_notificacao_id	=	isset( $_POST['notif_id'])	? $_POST['notif_id']: tool::msg_erros("O Campo Obrigatorio notif_id.");


// EXECULTA A QUERY
$Query_update=notificacoes::find_by_id($FRM_notificacao_id);


// VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG

require("../../library/PHPMailer/PHPMailerAutoload.php");

if(!$Query_update){
	echo suporte::Email_Suporte("Log de erros","Ocorreu um erro ao realizar update no arquivo Controller_notificacao.php linha 15",$COB_Empresa_Id,"");
}else{
	$Query_update->update_attributes(array('status'=>1));
	echo "0";
}

?>