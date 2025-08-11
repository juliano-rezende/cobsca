<?php
require_once("../../../sessao.php");
include("../../../conexao.php");
$cfg->set_model_directory('../../../models/');


//define se é aletaração o inclusão
$caixa_id 	=	isset( $_POST['id'])	? $_POST['id']: tool::msg_erros("O Campo  id Obrigatorio Faltando.");
$action		=	isset( $_POST['action'])	? $_POST['action']: tool::msg_erros("O Campo action Obrigatorio Faltando.");


if($action == "new_up"){


//se for alertação
if(empty($caixa_id)){

	$historico				 	= isset( $_POST['historico'])				? $_POST['historico']:
							  	  tool::msg_erros("O Campo Obrigatorio 1 Faltando.");
	$data						= isset( $_POST['data'])					? tool::InvertDateTime(tool::LimpaString($_POST['data']),"-"):
							      tool::msg_erros("O Campo Obrigatorio 2 Faltando.");
	$valor						= isset( $_POST['valor'])					? tool::limpamoney($_POST['valor']):
							  	  tool::msg_erros("O Campo Obrigatorio 3 Faltando.");
	$num_doc				 	= isset( $_POST['numdoc'])					? $_POST['numdoc']:
							  	  tool::msg_erros("O Campo Obrigatorio 4 Faltando.");
	$tipolancamento			 	= "1";
	$tipo					 	= "d";
	$formas_pagamentos_id 		= isset( $_POST['formas_pagamentos_id'])	? $_POST['formas_pagamentos_id']:
							  	  tool::msg_erros("O Campo Obrigatorio 7 Faltando.");
	$contas_bancarias_id	 	= isset( $_POST['contas_bancarias_id'])		? $_POST['contas_bancarias_id']:
							  	  tool::msg_erros("O Campo Obrigatorio 8 Faltando.");
	$clientes_fornecedores_id 	= "0".isset( $_POST['clientes_fornecedores_id'])? $_POST['clientes_fornecedores_id']:
							   	  tool::msg_erros("O Campo Obrigatorio 9 Faltando.");
	$centros_custos_id			= isset( $_POST['centros_custos_id'])		? $_POST['centros_custos_id']:
							  	  tool::msg_erros("O Campo Obrigatorio 10 Faltando.");
	$planos_contas_id			= isset( $_POST['planos_contas_id'])		? $_POST['planos_contas_id']:
							  	  tool::msg_erros("O Campo Obrigatorio 11 Faltando.");
	$detalhes					= isset( $_POST['detalhes'])				? $_POST['detalhes']:
							  	  tool::msg_erros("O Campo Obrigatorio 12 Faltando.");

$create= caixa::create(array(
						'historico'					=> $historico,
						'data'						=> $data,
						'valor' 					=> $valor,
						'numdoc'					=> $num_doc,
						'tipolancamento'			=> $tipolancamento,
						'tipo'						=> $tipo,
						'formas_pagamentos_id'		=> $formas_pagamentos_id,
						'contas_bancarias_id'		=> $contas_bancarias_id,
						'clientes_fornecedores_id'	=> $clientes_fornecedores_id,
						'centros_custos_id'			=> $centros_custos_id,
						'planos_contas_id'			=> $planos_contas_id,
						'detalhes'					=> $detalhes,
						'empresas_id'				=> $COB_Empresa_Id,
						'usuarios_id'				=> $COB_Usuario_Id
						));

// VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
	if(!$create){
		echo '":"","callback":"1","msg":"Lançamento não realizado!","status":"warning';
	}else{
		echo '":"","callback":"0","msg":"","status":"success';
	}



}else{

	$id_caixa					= $caixa_id;
	$historico				 	= isset( $_POST['historico'])				? $_POST['historico']:
							  	  tool::msg_erros("O Campo Obrigatorio 1 Faltando.");
	$data						= isset( $_POST['data'])					? tool::InvertDateTime(tool::LimpaString($_POST['data']),"-"):
							      tool::msg_erros("O Campo Obrigatorio 2 Faltando.");
	$valor						= isset( $_POST['valor'])					? tool::limpamoney($_POST['valor']):
							  	  tool::msg_erros("O Campo Obrigatorio 3 Faltando.");
	$num_doc				 	= isset( $_POST['numdoc'])					? $_POST['numdoc']:
							  	  tool::msg_erros("O Campo Obrigatorio 4 Faltando.");
	$tipolancamento			 	= "1";
	$tipo					 	= "d";
	$formas_pagamentos_id 		= isset( $_POST['formas_pagamentos_id'])	? $_POST['formas_pagamentos_id']:
							  	  tool::msg_erros("O Campo Obrigatorio 7 Faltando.");
	$contas_bancarias_id	 	= isset( $_POST['contas_bancarias_id'])		? $_POST['contas_bancarias_id']:
							  	  tool::msg_erros("O Campo Obrigatorio 8 Faltando.");
	$clientes_fornecedores_id 	= "0".isset( $_POST['clientes_fornecedores_id'])? $_POST['clientes_fornecedores_id']:
							   	  tool::msg_erros("O Campo Obrigatorio 9 Faltando.");
	$centros_custos_id			= isset( $_POST['centros_custos_id'])		? $_POST['centros_custos_id']:
							  	  tool::msg_erros("O Campo Obrigatorio 10 Faltando.");
	$planos_contas_id			= isset( $_POST['planos_contas_id'])		? $_POST['planos_contas_id']:
							  	  tool::msg_erros("O Campo Obrigatorio 11 Faltando.");
	$detalhes					= isset( $_POST['detalhes'])				? $_POST['detalhes']:
							  	  tool::msg_erros("O Campo Obrigatorio 12 Faltando.");



$update=caixa::find($id_caixa);
$update->update_attributes(array(
						'historico'					=> $historico,
						'data'						=> $data,
						'valor' 					=> $valor,
						'numdoc'					=> $num_doc,
						'formas_pagamentos_id'		=> $formas_pagamentos_id,
						'contas_bancarias_id'		=> $contas_bancarias_id,
						'clientes_fornecedores_id'	=> $clientes_fornecedores_id,
						'centros_custos_id'			=> $centros_custos_id,
						'planos_contas_id'			=> $planos_contas_id,
						'detalhes'					=> $detalhes,
						'empresas_id'				=> $COB_Empresa_Id,
						'usuarios_id'				=> $COB_Usuario_Id
						));

// VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
	if(!$update){
			echo '":"","callback":"1","msg":"Erro ao atualizar lançamento!","status":"warning';
	}else{
			echo '":"","callback":"0","msg":"","status":"success';
	}

}


}if($action == 'remove'){

$remover = caixa::find($caixa_id);
$remover->delete();

	// VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
	if(!$remover){
			echo '":"","callback":"1","msg":"Erro ao remover lançamento!","status":"warning';
	}else{
			echo '":"","callback":"0","msg":"","status":"success';
	}





}

?>