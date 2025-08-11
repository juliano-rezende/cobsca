<?php
require_once("../../../sessao.php");
include("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

//define se é aletaração o inclusão
$action		=	isset( $_POST['action'])	? $_POST['action']: tool::msg_erros("O Campo action Obrigatorio Faltando.");
$erro=""; /* definimos a variavel erro como vazia*/


/* inseri uma nova conta na base de dados*/
if($action == "new"){

$clientes_fornecedores_id 	= isset( $_POST['clientes_fornecedores_id'])? $_POST['clientes_fornecedores_id']: 	$erro="O Campo Obrigatorio 0-1 Faltando.";
$planos_contas_id			= isset( $_POST['planos_conta_id'])			? $_POST['planos_conta_id']: 			$erro="O Campo Obrigatorio 0-2 Faltando.";
$centros_custos_id			= isset( $_POST['centros_custo_id'])		? $_POST['centros_custo_id']: 			$erro="O Campo Obrigatorio 0-3 Faltando.";
$contas_bancarias_id	 	= isset( $_POST['contas_bancarias_id'])		? $_POST['contas_bancarias_id']:  		$erro="O Campo Obrigatorio 0-4 Faltando.";
$formas_recebimentos_id 	= isset( $_POST['formas_recebimentos_id'])	? $_POST['formas_recebimentos_id']:  		$erro="O Campo Obrigatorio 0-5 Faltando.";
$historico			 		= isset( $_POST['historico'])				? $_POST['historico']:  				$erro="O Campo Obrigatorio 0-6 Faltando.";
$dt_emissao_doc				= isset( $_POST['dt_emissao_doc'])	? tool::InvertDateTime(tool::LimpaString($_POST['dt_emissao_doc']),"0"):    $erro="O Campo Obrigatorio 0-7 Faltando.";
$dt_vencimento				= isset( $_POST['dt_vencimento'])	? tool::InvertDateTime(tool::LimpaString($_POST['dt_vencimento']),"0"):     $erro="O Campo Obrigatorio 0-8 Faltando.";
$linha_dig					= isset( $_POST['linha_dig'])		? tool::LimpaString($_POST['linha_dig']):	  							  	$erro="O Campo Obrigatorio 0-09 Faltando.";
$obs						= isset( $_POST['obs'])				? $_POST['obs']:	  							  							$erro="O Campo Obrigatorio 0-10 Faltando.";
$vlr_nominal				= isset( $_POST['vlr_nominal'])		? tool::limpamoney($_POST['vlr_nominal']):	  								$erro="O Campo Obrigatorio 0-11 Faltando.";
$qte_p						= isset( $_POST['qte_parcelas'])	? $_POST['qte_parcelas']:	  							  					$erro="O Campo Obrigatorio 0-12 Faltando.";
$num_doc					= isset( $_POST['num_doc'])			? $_POST['num_doc']:	  							  						$erro="O Campo Obrigatorio 0-13 Faltando.";


/* validamos as data de emissao do documento e vencimento*/
if(tool::LimpaString($dt_emissao_doc) > date("Ymd")){$erro ="Data de emissão superior a data atual!";}

/* verificamos se houve algum erro*/
if($erro!=""){echo '":"","callback":"1","msg":"'.$erro.'","status":"warning';return false;}

/* criamos a parcela */
if($qte_p > 1){


//define os valores da parcela
$vlr_nominal=$vlr_nominal/$qte_p;	//valor da parcela


	for($parcela=1; $parcela<=$qte_p; $parcela++){

		/* define a data de vencimentos da parcela */
		if($parcela == 1){
			$dt_vencimento;
		}else{

			$dt_vencimento = date('Y-m-d', strtotime("+1 month", strtotime($dt_vencimento)));
		}

		/* definimos o numero da parcela*/
		$n_parcela = $num_doc."-".$parcela."/".$qte_p;


		$create= contas_receber::create(array(
								'clientes_fornecedores_id'	=> $clientes_fornecedores_id,
								'planos_contas_id'			=> $planos_contas_id,
								'centros_custos_id'			=> $centros_custos_id,
								'contas_bancarias_id'		=> $contas_bancarias_id,
								'formas_recebimentos_id'	=> $formas_recebimentos_id,
								'historico'					=> $historico,
								'dt_emissao_doc'			=> $dt_emissao_doc,
								'dt_vencimento'				=> $dt_vencimento,
								'dt_ult_alt'				=> date("Y-m-d h:i:s"),
								'n_parcela'					=> $n_parcela,
								'num_doc'					=> $num_doc,
								'vlr_nominal'				=> $vlr_nominal,
								'linha_dig'					=> $linha_dig,
								'obs'						=> $obs,
								'usuarios_id'				=> $COB_Usuario_Id,
								'empresas_id'				=> $COB_Empresa_Id
								));

	}

}else{

	$create= contas_receber::create(array(
							'clientes_fornecedores_id'	=> $clientes_fornecedores_id,
							'planos_contas_id'			=> $planos_contas_id,
							'centros_custos_id'			=> $centros_custos_id,
							'contas_bancarias_id'		=> $contas_bancarias_id,
							'formas_recebimentos_id'	=> $formas_recebimentos_id,
							'historico'					=> $historico,
							'dt_emissao_doc'			=> $dt_emissao_doc,
							'dt_vencimento'				=> $dt_vencimento,
							'dt_ult_alt'				=> date("Y-m-d h:i:s"),
							'n_parcela'					=> ($num_doc."_".'1/1'),
							'num_doc'					=> $num_doc,
							'vlr_nominal'				=> $vlr_nominal,
							'linha_dig'					=> $linha_dig,
							'obs'						=> $obs,
							'usuarios_id'				=> $COB_Usuario_Id,
							'empresas_id'				=> $COB_Empresa_Id
							));

}
// VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
	if(!$create){
		echo '":"","callback":"1","msg":"erro ao realizar lançamento!","status":"warning';
	}else{
		echo '":"","callback":"0","msg":"Lançamento realizado com sucesso!","status":"success';
	}
}
/* edita uma conta existente na base de dados */
elseif($action == "edit"){

$creceber_id 				= isset( $_POST['creceber_id'])				? $_POST['creceber_id']: 					$erro="O Campo Obrigatorio 1-0 Faltando.";
$clientes_fornecedores_id 	= isset( $_POST['clientes_fornecedores_id'])? $_POST['clientes_fornecedores_id']: 	$erro="O Campo Obrigatorio 1-1 Faltando.";
$planos_contas_id			= isset( $_POST['planos_conta_id'])			? $_POST['planos_conta_id']: 			$erro="O Campo Obrigatorio 1-2 Faltando.";
$centros_custos_id			= isset( $_POST['centros_custo_id'])		? $_POST['centros_custo_id']: 			$erro="O Campo Obrigatorio 1-3 Faltando.";
$contas_bancarias_id	 	= isset( $_POST['contas_bancarias_id'])		? $_POST['contas_bancarias_id']:  		$erro="O Campo Obrigatorio 1-4 Faltando.";
$formas_recebimentos_id 	= isset( $_POST['formas_recebimentos_id'])	? $_POST['formas_recebimentos_id']:  		$erro="O Campo Obrigatorio 0-5 Faltando.";
$historico			 		= isset( $_POST['historico'])			? $_POST['historico']:  				$erro="O Campo Obrigatorio 1-6 Faltando.";
$dt_emissao_doc				= isset( $_POST['dt_emissao_doc'])	? tool::InvertDateTime(tool::LimpaString($_POST['dt_emissao_doc']),"0"):    $erro="O Campo Obrigatorio 1-7 Faltando.";
$dt_vencimento				= isset( $_POST['dt_vencimento'])	? tool::InvertDateTime(tool::LimpaString($_POST['dt_vencimento']),"0"):     $erro="O Campo Obrigatorio 1-8 Faltando.";
$linha_dig					= isset( $_POST['linha_dig'])		? tool::LimpaString($_POST['linha_dig']):	  							  	$erro="O Campo Obrigatorio 1-09 Faltando.";
$obs						= isset( $_POST['obs'])				? $_POST['obs']:	  							  							$erro="O Campo Obrigatorio 1-10 Faltando.";
$vlr_nominal				= isset( $_POST['vlr_nominal'])		? tool::limpamoney($_POST['vlr_nominal']):	  								$erro="O Campo Obrigatorio 1-11 Faltando.";
$parcela					= isset( $_POST['parcela'])			? $_POST['parcela']:	  							  						$erro="O Campo Obrigatorio 1-12 Faltando.";
$num_doc					= isset( $_POST['num_doc'])			? $_POST['num_doc']:	  							  						$erro="O Campo Obrigatorio 1-13 Faltando.";

/* validamos as data de emissao do documento e vencimento*/
if(tool::LimpaString($dt_emissao_doc) > date("Ymd")){$erro ="Data de emissão superior a data atual!";}

/* verificamos se houve algum erro*/
if($erro!=""){echo '":"","callback":"1","msg":"'.$erro.'","status":"warning';return false;}

$update=contas_receber::find($creceber_id);
$update->update_attributes(array(
							'clientes_fornecedores_id'	=> $clientes_fornecedores_id,
							'planos_contas_id'			=> $planos_contas_id,
							'centros_custos_id'			=> $centros_custos_id,
							'contas_bancarias_id'		=> $contas_bancarias_id,
							'formas_recebimentos_id'	=> $formas_recebimentos_id,
							'historico'					=> $historico,
							'dt_emissao_doc'			=> $dt_emissao_doc,
							'dt_vencimento'				=> $dt_vencimento,
							'dt_ult_alt'				=> date("Y-m-d h:i:s"),
							'n_parcela'					=> $parcela,
							'num_doc'					=> $num_doc,
							'vlr_nominal'				=> $vlr_nominal,
							'linha_dig'					=> $linha_dig,
							'obs'						=> $obs,
							'usuarios_id'				=> $COB_Usuario_Id,
							'empresas_id'				=> $COB_Empresa_Id
							));

// VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
	if(!$update){
		echo '":"","callback":"1","msg":"Erro na atualização!","status":"warning';
	}else{
		echo '":"","callback":"0","msg":"Atualização realizada!","status":"success';
	}


}
/* remove um conta já existente na base de dados */
elseif($action == "remove"){

$creceber_id 	= isset( $_POST['creceber_id'])				? $_POST['creceber_id']: 					$erro="O Campo Obrigatorio 2-0 Faltando.";

$remover = contas_receber::find($creceber_id);
$remover->delete();

	// VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
	if(!$remover){
			echo '":"","callback":"1","msg":"Erro ao remover lançamento!","status":"warning';
	}else{
			echo '":"","callback":"0","msg":"Lançamento removido!","status":"success';
	}

}
/* reliaza o pagamento de uma conta existente na base de dados */
elseif($action == "pay"){


$creceber_id 	= isset( $_POST['creceber_id_cx'])		? $_POST['creceber_id_cx']: 					$erro="O Campo Obrigatorio 0-0 Faltando.";
$historico		= isset( $_POST['historico_cx'])		? $_POST['historico_cx']:  						$erro="O Campo Obrigatorio 0-1 Faltando.";
$vlr_nominal	= isset( $_POST['vlr_total_cx'])		? tool::limpamoney($_POST['vlr_total_cx']):	  	$erro="O Campo Obrigatorio 0-2 Faltando.";
$num_doc		= isset( $_POST['num_doc_cx'])			? $creceber_id."-".$_POST['num_doc_cx']:	  	$erro="O Campo Obrigatorio 0-3 Faltando.";

$tp_lc			="1";
$tp 			="c";
$data 			=date("Y-m-d");

$formas_recebimentos_id 	= isset( $_POST['formas_recebimentos_id_cx'])	? $_POST['formas_recebimentos_id_cx']:  	$erro="O Campo Obrigatorio 0-4 Faltando.";
$contas_bancarias_id		= isset( $_POST['contas_bancarias_id_cx'])		? $_POST['contas_bancarias_id_cx']:  		$erro="O Campo Obrigatorio 0-5 Faltando.";
$clientes_fornecedores_id 	= isset( $_POST['clientes_fornecedores_id_cx'])	? "1.".$_POST['clientes_fornecedores_id_cx']:$erro="O Campo Obrigatorio 0-6 Faltando.";
$centros_custos_id			= isset( $_POST['centros_custo_id_cx'])			? $_POST['centros_custo_id_cx']: 			$erro="O Campo Obrigatorio 0-7 Faltando.";
$planos_contas_id			= isset( $_POST['planos_conta_id_cx'])			? $_POST['planos_conta_id_cx']: 			$erro="O Campo Obrigatorio 0-8 Faltando.";
$detalhes					= isset( $_POST['obs_cx'])						? $_POST['obs_cx']:	  						$erro="O Campo Obrigatorio 0-9 Faltando.";


$dt_recebimento				= isset( $_POST['dt_recebimento_cx'])	? tool::InvertDateTime(tool::LimpaString($_POST['dt_recebimento_cx']),"0"):    $erro="O Campo Obrigatorio 0-10 Faltando.";
$dt_vencimento				= isset( $_POST['dt_vencimento_cx'])? tool::InvertDateTime(tool::LimpaString($_POST['dt_vencimento_cx']),"0"):   $erro="O Campo Obrigatorio 0-11 Faltando.";


/* validamos as data de vencimento do documento e pagamento*/
if(tool::LimpaString($dt_recebimento) <= '000000'){$erro ="Data de pagamento superior a data atual!";}

/* verificamos se houve algum erro*/
if($erro!=""){echo '":"","callback":"1","msg":"'.$erro.'","status":"warning';return false;}

$create= caixa::create(array(
						'historico'					=> $historico,
						'data'						=> $dt_recebimento,
						'valor' 					=> $vlr_nominal,
						'numdoc'					=> $num_doc,
						'tipolancamento'			=> $tp_lc,
						'tipo'						=> $tp,
						'formas_recebimentos_id'	=> $formas_recebimentos_id,
						'contas_bancarias_id'		=> $contas_bancarias_id,
						'clientes_fornecedores_id'	=> $clientes_fornecedores_id,
						'centros_custos_id'			=> $centros_custos_id,
						'planos_contas_id'			=> $planos_contas_id,
						'detalhes'					=> $detalhes,
						'empresas_id'				=> $COB_Empresa_Id,
						'usuarios_id'				=> $COB_Usuario_Id
						));

/*realizamos o update da onta na base de dados*/
$update=contas_receber::find($creceber_id);
$update->update_attributes(array(
							'dt_recebimento'			=> $dt_recebimento,
							'vlr_pago'					=> $vlr_nominal,
							'status' 					=>1,
							'usuarios_id'				=> $COB_Usuario_Id,
							'dt_ult_alt'				=> date("Y-m-d h:i:s")
							));


// VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
	if(!$create or !$update){
		echo '":"","callback":"1","msg":"Houve um erro ao realizar o recebimento!","status":"warning';
	}else{
		echo '":"","callback":"0","msg":"Recebimento realizado com sucesso!","status":"success';
	}

}

?>