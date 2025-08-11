<?php
$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../sessao.php";
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');


//valida user para evitar injeções sql
$usuario_id			=	isset( $_POST['user']) ?
						$_POST['user']  :
						tool::msg_erros("Usuario Invalido.");


//valida os acessos para evitar injeções sql
$ids				=	isset( $_POST['acessos']) ?
						$_POST['acessos']  :
						tool::msg_erros("Não hé items de acesso a liberar.");
$ids_acesso			=	explode(',' , $ids);		// explode a variavei para separar os valores

// limpamos todas as permissões do usuario para liberarmos novamente

$errors				=	"";

// verifica se existe alguma acesso
$permissaomenu=permissaomenu::find_by_usuario_id($usuario_id);

if($permissaomenu){
	// limpa todos os grupos
	$acessos_grupo_user = permissaomenu::delete_all(array('conditions' => array('usuario_id' => $usuario_id)));
	$permissaosubmenu=permissaosubmenu::find_by_usuario_id($usuario_id);

	if($permissaosubmenu){
		// limpa todos os acessos
		$acessos_user = permissaosubmenu::delete_all(array('conditions' => array('usuario_id' => $usuario_id)));

		if(!$acessos_user){
			tool::msg_erros("Erro ao limpar  acesso do grupo do usúario.");
			}
	}

	if(!$acessos_grupo_user ){
	tool::msg_erros("Erro ao limpar grupo de acesso do usuario.");
	}
}

// faz um loop para pegar o valor de cada item
foreach( $ids_acesso as $id ){


$acessos			=	explode(';' , $id);		// explode a variavei para separar os valores
$menu_id			= $acessos[0]; // id do menu
$sub_menu_id		= $acessos[1]; // id do sub menu

$valida_menu = permissaomenu::find_by_menu_id_and_usuario_id($menu_id,$usuario_id);

if($valida_menu == false){

$create_menu = permissaomenu::create(
										array(
										'menu_id' 	=>	$menu_id,
										'usuario_id' =>	$usuario_id,
										'status'	=>	'1'
										));
}

if(!$create_menu){
	$errors.="Erro ao criar item de acesso para o grupo  ".$menu_id." | ";
}else{

$create_sub_menu = permissaosubmenu::create(
								array(
								'menu_id' =>$menu_id,
								'submenu_id' =>$sub_menu_id,
								'usuario_id' =>$usuario_id,
								'status'=>'1'
								));

if(!$create_sub_menu){$errors.="Erro ao criar permissao de grupo para o acesso ".$sub_menu_id." | ";}


	}


}// fim do foreach

if($errors != ""){echo $errors;}else{echo $usuario_id;}


?>