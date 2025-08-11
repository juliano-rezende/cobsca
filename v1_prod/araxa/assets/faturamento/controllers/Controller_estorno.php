<?php
$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../../sessao.php";
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

// recupera as taxas configuradas na empresa
$dados_config=configs::find_by_empresas_id($COB_Empresa_Id);


// variavel que define a ação a ser executada
$FRM_matricula	 = isset( $_POST['mat']) 	 	?   $_POST['mat']
													:tool::msg_erros("O Campo mat Obrigatorio faltando.");
$FRM_parcelas_id = isset( $_POST['ids']) 	 	? 	$_POST['ids']
											    	:tool::msg_erros("O Campo ids Obrigatorio faltando.");
$FRM_vl_pago	 = isset( $_POST['vl_pgto']) 	?   tool::limpaMoney($_POST['vl_pgto'])
													:tool::msg_erros("O Campo vl_pago Obrigatorio faltando.");
$FRM_f_pgto_id	 = isset( $_POST['f_pgto_id']) ?	$_POST['f_pgto_id']
													:tool::msg_erros('<div class="uk-alert uk-alert-danger"> O campo f_pgto_id é obrigatorio! </div>');

$FRM_pa 		 = explode(",",$FRM_parcelas_id);
$query_errors	 ="";


###################################################################################################################################################################
// INICIA O LOOP DA PARCELAS
foreach ($FRM_pa as $value) {


// seleciona a parcela pelo id
$Query_parcela=faturamentos::find_by_sql("SELECT
											  faturamentos.*
											FROM
											  faturamentos
											  LEFT JOIN convenios ON faturamentos.convenios_id = convenios.id
											  LEFT JOIN dados_cobranca ON dados_cobranca.id =  faturamentos.dados_cobranca_id
  											  LEFT JOIN planos ON planos.id = dados_cobranca.planos_id
											WHERE
											  faturamentos.id= '".$value."'");

$referencia = $Query_parcela[0]->referencia;


	// aqui vamos tratar o boleto caso ele exista para a parcela
	if($Query_parcela[0]->titulos_bancarios_id > 0){


		$Query_update_titulo=titulos::find($Query_parcela[0]->titulos_bancarios_id);
		$Query_update_titulo->update_attributes(
													array(
															'status'			=>'0',
															'stflagrem'			=>'1', /* avisamos para o sistema que este registro deve ser enviado ao banco pois houve movimentação */
															'cod_mov_rem'		=>$Query_update_titulo->cod_ult_mov_rem,
															'dt_pagamento'		=>"0000-00-00",
															'dt_atualizacao' 	=>date("Y-m-d"),
															'vlr_pago'			=>'0',
															'cod_ult_mov_rem'	=>'',
															'mov_manual'		=>"N"
														));
		if(!$Query_update_titulo){
			$query_errors.=("<i class='uk-icon-exclamation-triangle  uk-text-warning' ></i> Erro ao atualizar titulo nº ".$Query_parcela[0]->titulos_bancarios_id.".</br");
		}
	}

	$Query_update_fat=faturamentos::find($value);
	$Query_update_fat->update_attributes(
			                           array(
												'status'				=>0,
												'tipo_baixa'			=>"",
												'negociada'				=>"",
												'dt_negociacao'			=>"0000-00-00",
												'dt_pagamento'			=>"0000-00-00",
												'valor_negociado'		=>0,
												'valor_pago'			=>0,
												'acrescimos'			=>0,
												'descontos'				=>0,
												'flag_pago'				=>"FATURADA",
												'contas_bancarias_id'	=>0,
												'usuarios_id'			=>$COB_Usuario_Id
												));
	if(!$Query_update_fat){
		$query_errors.=("<i class='uk-icon-exclamation-triangle  uk-text-warning' ></i> Erro ao estonar parcela nº ".$value.".</br");
	}
###################################################################################################################################################################
// SE POSSUI SEGURO FAZ A INSERÇÃO DO DADOS NA TABELA DE SEGURADOS

	// passa a matricula a referencia e o tipo de convenio pj ou pf
	$Query_assegurar = seguros::find_by_matricula_and_referencia_and_empresas_id($FRM_matricula,$referencia->format('Y-m-d'),$COB_Empresa_Id);

	// VERIFICA SE CORREU TUDO BEM NA INSERÇÃO DO DADOS NA TABELA SEGUROS
	if($Query_assegurar == true){

		$segurado = seguros::find($Query_assegurar->id);
		$segurado->delete();

		if(!$segurado){$query_errors.=("<i class='uk-icon-exclamation-triangle  uk-text-warning' ></i> Assegurado não removido </br");}

	}
}


###################################################################################################################################################################
// AQUI TRABALHAMOS A INSERÇÃO NO CAIXA

$doc_card=$FRM_matricula."-".$value;

// cria o lançamento no caiza
$create=caixa::create(array(
							'historico' 				=>"Estorno parcela ",
							'data' 						=>date("Y-m-d"),
							'valor' 					=>$FRM_vl_pago,
							'numdoc' 					=>$doc_card,
							'tipolancamento'			=>'1',
							'tipo'						=>"d",
							'formas_pagamentos_id' 		=>$FRM_f_pgto_id,
							'formas_recebimentos_id'	=>'0',
							'contas_bancarias_id' 		=>$Query_parcela[0]->contas_bancarias_id,
							'empresas_id' 				=>$COB_Empresa_Id,
							'usuarios_id' 				=>$COB_Usuario_Id,
							'clientes_fornecedores_id' 	=>"0.".$FRM_matricula, // SERÁ ADIONADO O ZERO ANTES DA MATRICULA INDICANDO QUE É UM ASSOCIADO E NÃO UM CLIENTE OU FORNECEDOR
							'planos_contas_id' 			=>$dados_config->planos_contas_id,
							'centros_custos_id' 		=>$dados_config->planos_contas_id,
							'detalhes' 					=>"Estorno parcela ".$value." matricula ".$FRM_matricula.""
						));

// valida o create caixa
	if($create == false){

			$query_errors.=("<i class='uk-icon-exclamation-triangle  uk-text-warning' ></i> Erro ao criar lançamento de estorno no caixa parcela nº ".$value.".</br");


	}

//dados do associado
$Query_associados=associados::find_by_matricula($FRM_matricula);



// VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
	if($query_errors !=""){
			echo '":"","callback":"1","msg":"'.$query_errors.'","status":"warning';
	}else{
				echo '":"","callback":"0","msg":"Estorno Concluido.","status":"success';
	}

?>