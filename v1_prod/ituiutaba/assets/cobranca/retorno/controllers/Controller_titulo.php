<?php
$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../../../sessao.php";
require_once("../../../../conexao.php");
$cfg->set_model_directory('../../../../models/');

$action  = isset( $_POST['action'])   ? $_POST['action'] : tool::msg_erros("O Campo action Obrigatorio.");



if($action == "reenviar"){
################################################################################################################################################################################
// LIBERA O TITULO PARA REENVIAR

$id      	= isset( $_POST['id'])     	 ? $_POST['id'] : tool::msg_erros("O Campo id Obrigatorio.");
$cod_banco  = isset( $_POST['cod_banco'])   ? $_POST['cod_banco'] : tool::msg_erros("O Campo cod_banco Obrigatorio.");

$Query_update_titulo=titulos::find($id);
$Query_retorno=retornos::find_by_lote_retorno($Query_update_titulo->cod_retorno);




$t_erros = ($Query_retorno->t_erros-1);
$Query_update_retorno=retornos::find($Query_retorno->id);
$Query_update_retorno->update_attributes(array('t_erros' =>$t_erros));



$Query_update_titulo->update_attributes(
										array(
											  'dt_atualizacao' 	=>date("Y-m-d"),
											  'stflagrem'		=>'1',
											  'cod_mov_rem'	 	=>remessas::Cod_Tab_Remessa($cod_banco,"MOV01"),
											  'cod_retorno'		=>"",
											  'cod_remessa'		=>"",
											  'dt_remessa'		=>'0000-00-00'
										));


// VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
	if(!$Query_update_titulo || !$Query_update_retorno){
			echo '":"","callback":"1","msg":"Erro ao atualizar Titulo!","status":"warning';
	}else{
				echo '":"","callback":"0","msg":"Titulo atualizado !.","status":"success';
	}


}if($action == "ignorar"){
################################################################################################################################################################################
// LIBERA O TITULO PARA REENVIAR

$id      	= isset( $_POST['id'])     	 ? $_POST['id'] : tool::msg_erros("O Campo id Obrigatorio.");


$Query_update_titulo=titulos::find($id);

$Query_retorno=retornos::find_by_lote_retorno($Query_update_titulo->cod_retorno);



$t_erros = ($Query_retorno->t_erros-1);

$Query_update_retorno=retornos::find($Query_retorno->id);
$Query_update_retorno->update_attributes(array('t_erros' =>$t_erros));



$Query_update_titulo->update_attributes(
										array(
											  'dt_atualizacao' 	=>date("Y-m-d"),
											  'cod_rej2'		=>'',
											  'cod_rej2'	 	=>'',
											  'cod_rej2'		=>""
										));


// VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
	if(!$Query_update_titulo || !$Query_update_retorno){
			echo '":"","callback":"1","msg":"Erro ao atualizar Titulo!","status":"warning';
	}else{
				echo '":"","callback":"0","msg":"Titulo atualizado !.","status":"success';
	}


}
?>