<?php
$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../sessao.php";
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');


// valida  o usuario para evitar fraudes
if($COB_Acesso_Id < 3){tool::msg_erros("Alterações de Usuarios só são Permitidas por Adminstradores.");}

//variaveir vindas do formulario
$FRM_usuario_id			= isset( $_POST['usuario_id']) 			? $_POST['usuario_id']		 : tool::msg_erros("Erro campo usuario_id é Obrigatorio.");
$FRM_empresa_id			= isset( $_POST['empresa_id']) 			? $_POST['empresa_id'] 		 : tool::msg_erros("Erro campo empresa_id é Obrigatorio.");
$FRM_nmcompleto			= isset( $_POST['nmcompleto'])			? $_POST['nmcompleto']  	 : tool::msg_erros("Erro campo nmcompleto é Obrigatorio.");
$FRM_username			= isset( $_POST['username'])			? $_POST['username']  		 : tool::msg_erros("Erro campo username é Obrigatorio.");
$FRM_password			= isset( $_POST['pwd'])					? $_POST['pwd']  			 : tool::msg_erros("Erro campo pwd é Obrigatorio.");
$FRM_recovery_password	= isset( $_POST['r_pwd'])				? $_POST['r_pwd']  			 : tool::msg_erros("Erro campo r_pwd é Obrigatorio.");

$FRM_interval_am		= isset( $_POST['interval_am'])			? $_POST['interval_am']  	 : tool::msg_erros("Erro campo interval_am é Obrigatorio.");
$vowels_am				= array(" ",":");
$FRM_interval_am		= str_replace($vowels_am, "", $FRM_interval_am);


$FRM_interval_pm		= isset( $_POST['interval_pm'])			? $_POST['interval_pm']  	 : tool::msg_erros("Erro campo interval_pm é Obrigatorio.");
$vowels_pm				= array(" ",":");
$FRM_interval_pm		= str_replace($vowels_pm, "", $FRM_interval_pm);

$FRM_day_acs_user		=	isset( $_POST['day_acs_user'])			? $_POST['day_acs_user']  	 : tool::msg_erros("Erro campo day_acs_user é Obrigatorio.");
$FRM_salt				=	isset( $_POST['st'])					? $_POST['st']  			 : tool::msg_erros("Erro campo st é Obrigatorio.");
$FRM_access				=	isset( $_POST['access'])				? $_POST['access']  		 : tool::msg_erros("Erro campo access é Obrigatorio.");
$FRM_password_expires	=	isset( $_POST['password_expires'])		? $_POST['password_expires'] : tool::msg_erros("Erro campo password_expires é Obrigatorio.");
$FRM_date_expires_pass	=	isset( $_POST['date_expires_pass'])		? $_POST['date_expires_pass']: tool::msg_erros("Erro campo date_expires_pass é Obrigatorio.");
$FRM_status				=	isset( $_POST['status'])				? $_POST['status']  		 : tool::msg_erros("Erro campo status é Obrigatorio.");
$FRM_notificar 			=	isset( $_POST['notificar'])				? $_POST['notificar']  		 : tool::msg_erros("Erro campo notificar é Obrigatorio.");

// trata o campo data
$FRM_date_expires_pass	=	tool::InvertDateTime(tool::LimpaString($FRM_date_expires_pass),0);



// Validados o codigo do usuario para saber se é uma nova inclusão ou edição de usuario
// validamos para tratar a senha do usuario
if(empty($FRM_usuario_id )){

$pwd		=	tool::hash_user($FRM_password);	// gera um senha e um salt
$Password	=	$pwd['password'];				// senha gerada ja com encode e salt
$Salt		=	$pwd['salt'];					// salt gerado para o usuario

// caso seja edição de usuario verificamos se ouve mudança na senha
}else{

	if($FRM_password != $FRM_recovery_password){

		$pwd		=	tool::hash_user($FRM_password,$FRM_salt);	// gera um senha e um salt
		$Password	=	$pwd['password'];					// senha gerada ja com encode e salt
		$Salt		=	$pwd['salt'];						// salt gerado para o usuario

	}else{
		$Password	=	$FRM_password;			// senha que está gravado no banco
		$Salt		=	$FRM_salt;				// salt que está gravado no banco

	}

}

// atualiza o valor das variaveis vindas do formulario com dados validados
$FRM_password			=	$Password;
$FRM_salt				=	$Salt;


// verifica se é um novo usuario ou edição
if(empty($FRM_usuario_id )){

// cria um novo usuario
$Query_user = users::create(
							array(
								'login'					=>	$FRM_username,
								'senha'					=>	$FRM_password,
								'salt' 					=>	$FRM_salt,
								'nm_usuario' 			=>	$FRM_nmcompleto,
								'email' 				=>	"",
								'digital' 				=>	"",
								'status' 				=>	$FRM_status,
								'notificar' 			=>	$FRM_notificar,
								'day_access_user'		=>	$FRM_day_acs_user,
								'interval_am'			=>	$FRM_interval_am,
								'interval_pm'			=>	$FRM_interval_pm,
								'foto' 					=>	"",
								'senha_expira' 			=>	$FRM_password_expires,
								'data_senha_expira'		=>	$FRM_date_expires_pass,
								'data_cadastro'			=>	date("Y-m-d"),
								'acessos_id'			=>	$FRM_access,
								'usuarios_id'			=>  $COB_Usuario_Id,
								'empresas_id'			=>	$FRM_empresa_id
							));


if($Query_user==true){
		$Query_user=users::find("last");//recupera o ultimo id
		echo $Query_user->id;
		}else{
			echo tool::msg_erros("Erro ao Cadastrar usuario");
			}


}else{

// atualiza os dados do usuario
$update=users::find($FRM_usuario_id);
$update->update_attributes(array(
								'login'					=>	$FRM_username,
								'senha'					=>	$FRM_password,
								'salt' 					=>	$FRM_salt,
								'nm_usuario' 			=>	$FRM_nmcompleto,
								'email' 				=>	"",
								'digital' 				=>	"",
								'status' 				=>	$FRM_status,
								'notificar' 			=>	$FRM_notificar,
								'day_access_user'		=>	$FRM_day_acs_user,
								'interval_am'			=>	$FRM_interval_am,
								'interval_pm'			=>	$FRM_interval_pm,
								'foto' 					=>	"",
								'senha_expira' 			=>	$FRM_password_expires,
								'data_senha_expira'		=>	$FRM_date_expires_pass,
								'data_cadastro'			=>	date("Y-m-d"),
								'acessos_id'			=>	$FRM_access,
								'usuarios_id'			=>	$COB_Usuario_Id,
								'empresas_id'			=>	$FRM_empresa_id
								));

if($update==true){
		echo $FRM_usuario_id;
		}else{
			echo tool::msg_erros("Erro ao Editar usuario");
			}

}


?>