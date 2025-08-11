<?php

$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../../sessao.php";
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');


// geral
$FRM_conta_id		= isset( $_POST['contas_bancarias_id']) ? $_POST['contas_bancarias_id']: 		tool::msg_erros("Falta o Campo Obrigatorio contas_bancarias_id.");
$FRM_sd_abertura	= isset( $_POST['valor'])				  ? tool::limpaMoney($_POST['valor']): 	tool::msg_erros("Falta o Campo Obrigatorio valor.");


// atualizamo o saldo inicial da conta bancaria
$Query_cb=contas_bancarias::find($FRM_conta_id);
$Query_cb->update_attributes(array('sd_inicial'=>$FRM_sd_abertura));


// selecionamos o primeiro registro da conta no caixa considerando que o mesmo é o saldo inicial da conta
$Query_first=caixa::find_by_sql("SELECT id,valor FROM caixa WHERE contas_bancarias_id='".$FRM_conta_id."' ORDER BY id ASC LIMIT 1");

$Query_cx=caixa::find($Query_first[0]->id);
$Query_cx->update_attributes(array('valor'=>tool::limpaMoney($FRM_sd_abertura)));


if($Query_cb == true and $Query_cx == true){

		echo $FRM_conta_id;
}else{
		echo tool::msg_erros("Erro ao atualizar saldo");
}

?>