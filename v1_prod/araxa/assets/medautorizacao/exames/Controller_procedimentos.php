<?php
$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../../sessao.php";
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');



$FRM_action 				= isset( $_POST['action'])	? $_POST['action']: 	tool::msg_erros("O Campo Obrigatorio ação Faltando.");
$FRM_med_autorizacoes_id	=	isset( $_POST['autId'])	? $_POST['autId']: 	tool::msg_erros("O Campo Obrigatorio codigo de autorização Faltando.");
$FRM_med_procedimentos_id	=	isset( $_POST['proId'])	? $_POST['proId']: 	tool::msg_erros("O Campo Obrigatorio codigo de procedimento Faltando.");


/* recebe a ação de inserir novo procedimento a autorização*/
if($FRM_action == 0){


// EXECULTA A QUERY
$QueryProcedimentos	= med_proc_autorizacoes::create(
	array(
		'med_autorizacoes_id' 	=>$FRM_med_autorizacoes_id,
		'med_procedimentos_id' 	=>$FRM_med_procedimentos_id
	));

if($QueryProcedimentos==true){
			echo $FRM_med_autorizacoes_id;
		}else{
			echo tool::msg_erros("Erro ao inserir procedimento");
		}

/* remove o procedimento da autorização*/
}elseif($FRM_action == 1){


$FRM_med_procedimentos_id	=	isset( $_POST['proId'])	? $_POST['proId']: 	tool::msg_erros("O Campo Obrigatorio codigo de procedimento Faltando.");
$remover = med_proc_autorizacoes::find($FRM_med_procedimentos_id);
$remover->delete();

	if($remover==true){
			echo $FRM_med_autorizacoes_id;
		}else{
			echo tool::msg_erros("Erro ao inserir procedimento");
		}
}

?>