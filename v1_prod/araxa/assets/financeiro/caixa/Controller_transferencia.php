<?php
require_once("../../../sessao.php");
include("../../../conexao.php");
$cfg->set_model_directory('../../../models/');



$contaorigem			=	isset( $_POST['contaorigem'])	? $_POST['contaorigem']: 									tool::msg_erros("O Campo Obrigatorio 1 Faltando.");
$contadestino			=	isset( $_POST['contadestino'])	? $_POST['contadestino']: 									tool::msg_erros("O Campo Obrigatorio 2 Faltando.");
$cdformaderecebimento	=	isset( $_POST['fpagamento'])	? $_POST['fpagamento']: 									tool::msg_erros("O Campo Obrigatorio 3 Faltando.");
$historico				=	isset( $_POST['historico'])		? $_POST['historico']: 										tool::msg_erros("O Campo Obrigatorio 4 Faltando.");
$data					=	isset( $_POST['data'])			? tool::InvertDateTime(tool::LimpaString($_POST['data']),0):tool::msg_erros("O Campo Obrigatorio 5 Faltando.");
$numdoc					=	isset( $_POST['numdoc'])		? $_POST['numdoc']: 										tool::msg_erros("O Campo Obrigatorio 6 Faltando.");
$valor					=	isset( $_POST['valor'])			? tool::limpaMoney($_POST['valor']):						tool::msg_erros("O Campo Obrigatorio 7 Faltando.");


// debitamos o valor da conta de origem
$create_debito= caixa::create(array(
								 'empresas_id'				=>$COB_Empresa_Id,
								 'usuarios_id'				=>$COB_Usuario_Id,
								 'contas_bancarias_id'		=>$contaorigem,
								 'clientes_fornecedores_id'	=>'3.0',
								 'centros_custos_id' 		=>'2',
								 'centros_custos_id'		=>'4',
								 'historico'				=>$historico,
								 'formas_pagamentos_id'		=>$cdformaderecebimento,
								 'data'						=>$data,
								 'numdoc'					=>$numdoc,
								 'valor'					=>$valor,
								 'tipo'						=>'d',
								 'tipolancamento'			=>'0'
								 ));

$create_credito= caixa::create(array(
								 'empresas_id'				=>$COB_Empresa_Id,
								 'usuarios_id'				=>$COB_Usuario_Id,
								 'contas_bancarias_id'		=>$contadestino,
								 'clientes_fornecedores_id'	=>'3.0',
								 'centros_custos_id' 		=>'2',
								 'centros_custos_id'		=>'4',
								 'historico'				=>$historico,
								 'formas_pagamentos_id'		=>$cdformaderecebimento,
								 'data'						=>$data,
								 'numdoc'					=>$numdoc,
								 'valor'					=>$valor,
								 'tipo'						=>'c',
								 'tipolancamento'			=>'0'
								 ));




if($create_credito==true && $create_debito==true){

$ultimolancamento=caixa::find("last");//recupera o ultimo id
echo $ultimolancamento->id;//msg de retorno


}else{
		tool::msg_erros("Erro ao completar transferencia.");
		 }


?>