<?php
$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../sessao.php";
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');


// valida  o usuario para evitar fraudes
//if($COB_Acesso_Id < 3){tool::msg_erros("Alterações de Usuarios so são Permitidas por Adminstradores.");}

//variaveir vindas do formulario
$FRM_nmcompleto			=	isset( $_POST['nmcompleto'])	? $_POST['nmcompleto']  				: tool::msg_erros("O Campo Nome Completo é Obrigatorio.");
$FRM_username			=	isset( $_POST['username'])		? $_POST['username']  					: tool::msg_erros("O Campo Usuario é Obrigatorio.");
$FRM_password			=	isset( $_POST['pwd'])			? users::valid_password($_POST['pwd'])	: tool::msg_erros("O Campo Senha Antiga é Obrigatorio.");
$FRM_recovery_password	=	isset( $_POST['r_pwd'])			? $_POST['r_pwd']  						: tool::msg_erros("001 Erro Falta Campo Obrigatorio.");
$FRM_new_pwd			=	isset( $_POST['new_pwd'])		? $_POST['new_pwd'] 					: tool::msg_erros("O campo Nova Senha é Obrigatorio.");
$FRM_new_pwd			=	!empty( $_POST['new_pwd'])		? $_POST['new_pwd'] 					: tool::msg_erros("O campo Nova Senha não deve fica em branco.");
$FRM_conf_new_pwd		=	isset( $_POST['conf_new_pwd'])	? $_POST['conf_new_pwd']  				: tool::msg_erros("O campo Confirmar Senha é Obrigatorio.");

// verifica quantidade de caracteres da senha
if(strlen($FRM_new_pwd) < 6  or strlen($FRM_new_pwd) > 8){
	tool::msg_erros("Sua Nova senha deve ter no mimino 6 e no maximo 8 digitos.");
}
// compara a nova senha com a confirmação para valida se ta igual
if($FRM_new_pwd != $FRM_conf_new_pwd ){
	tool::msg_erros("Nova senha diferente de confirmação de senha.");
}


// recupera os dados salvos
$dadosusuario	=	users::find($COB_Usuario_Id);
// senha de acesso salva no banco
$PWD_bd			=	$dadosusuario->senha;
// senha digitada em hash
$pwd_old		=	tool::hash_user($FRM_password,$dadosusuario->salt);	// gera a senha em hash

if($PWD_bd	!= $pwd_old['password']){tool::msg_erros("Senha atual Invalida.");}




// senha digitada em hash
$pwd_new_bd		=	tool::hash_user('250183',$dadosusuario->salt);	// gera a senha em hash


// atualiza os dados do usuario
$update=users::find($COB_Usuario_Id);
$update->update_attributes(array(
								'login'					=>	$FRM_username,
								'senha'					=>	$pwd_new_bd['password'],
								'nm_usuario' 			=>	$FRM_nmcompleto,
								));

if(!$update){tool::msg_erros("Houver um ao Processar Solicitação.");}else{echo $COB_Usuario_Id;}




?>