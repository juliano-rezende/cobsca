<?php
$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../../sessao.php";
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

// recupera as taxas configuradas na empresa
$dados_config=configs::find_by_empresas_id($COB_Empresa_Id);


// variavel que define a ação a ser executada
$FRM_acao			= isset( $_POST['action']) 		? $_POST['action']		: tool::msg_erros("O Campo act Obrigatorio faltando.");


// verifica se ela é apenas letras
if(!ctype_alpha($FRM_acao)){
	tool::msg_erros('<div class="uk-alert uk-alert-danger"> O campo ação deve ser alpha numerico </div>');
}

##################################################################################################################################################################################
// ACAO PARA OPERAÇÕES MATEMATICAS DAS PARCELAS

if($FRM_acao == "op"){

	/* sub/ add/ mul/ div */
	$FRM_tp			= isset( $_POST['tp'])?
					  $_POST['tp']:
					  tool::msg_erros('Campo tp está faltando');	// id da forma de recebimento

	/* SOBTRAÇÃO DE VALORES */
	if($FRM_tp == "sub"){

		// valor nominmal da divida
		$FRM_valor_total	= isset( $_POST['vl_n']) ?
							  tool::limpaMoney($_POST['vl_n']):
							  tool::msg_erros('Campo vl_n está faltando');
		// valor do desconto
		$FRM_valor_desconto	= isset( $_POST['desconto'])?
							  tool::limpaMoney($_POST['desconto']):
							  tool::msg_erros('Campo desconto está faltando');

		if(!is_numeric($FRM_valor_total)){
							  tool::msg_erros('O valor total deve ser em valor monetario');
		}
		if(!is_numeric($FRM_valor_desconto)){
							  tool::msg_erros('O valor do desconto deve ser em valor monetario!');
		}

		echo number_format(($FRM_valor_total-$FRM_valor_desconto),2,",",".");
	}
##################################################################################################################################################################################
// ADIÇÃO DE VALORES POR PERCENTUAL

	if($FRM_tp == "perc"){

		// valor nominmal da divida
		$FRM_valor_subtotal = isset( $_POST['vl_n']) 			?	tool::limpaMoney($_POST['vl_n'])	:
																	tool::msg_erros('<div class="uk-alert uk-alert-danger"> O campo vl_n é obrigatorio! </div>');
		// percentual de desconto caso seja negociação
		$FRM_desc_conced 	= isset( $_POST['desc_conced']) 	?	$_POST['desc_conced']:
																	tool::msg_erros('<div class="uk-alert uk-alert-danger"> O campo desc_conced é obrigatorio! </div>');
		// id da forma de pagamento
		$FRM_f_receb_id	 	= isset( $_POST['f_receb_id']) 		?	$_POST['f_receb_id']:
																	tool::msg_erros('<div class="uk-alert uk-alert-danger"> O campo f_receb_id é obrigatorio! </div>');
		// percentual de acrescimo pela forma de pagamento
		$FRM_f_receb_acrescimos = isset( $_POST['acrescimos'])	? 	tool::limpaMoney($_POST['acrescimos']):
																  	tool::msg_erros('<div class="uk-alert uk-alert-danger"> O campo acrescimos é obrigatorio! </div>');
		// se vai negociar ou nao
		$FRM_negociacao		= isset( $_POST['neg'])				?	tool::limpaMoney($_POST['neg']):
																	tool::msg_erros('<div class="uk-alert uk-alert-danger"> O campo neg é obrigatorio! </div>');

		// declaramos a variavel desconto fazia
		$desconto	= "";

		// define o valor do desconto
		if($FRM_negociacao == 1){
			$desconto = ($FRM_desc_conced * $FRM_valor_subtotal )  / 100;
		}else{
			$desconto = 0;
		}

		$saldo = $FRM_valor_subtotal - $desconto;

		// define o valor do acrescimo pela forma de recebimento
		$acrescimos =($FRM_f_receb_acrescimos * $saldo )  / 100;

		$total_g  = $FRM_valor_subtotal - $desconto + $acrescimos;

		$str='":"","descontos":"'.number_format($desconto,2,",",".").'","total":"'.number_format($total_g,2,",",".").'';
		echo $str;

	}
################################################################################################################################################################################
// UPDATE DE VALOR,DATA DE VECIMENTO E DETALHES

}if($FRM_acao == "update"){


$FRM_id				= isset( $_POST['id'])?
					  $_POST['id']:
					  tool::msg_erros('Campo id está faltando');	// id da parcela
$FRM_valor			= isset( $_POST['vlr'])?
					  tool::limpaMoney($_POST['vlr']):
					  tool::msg_erros('Campo valor está faltando');	// valor da parcela
$FRM_dtvencimento	= isset( $_POST['dtv'])?
					  tool::InvertDateTime(tool::LimpaString($_POST['dtv']),0):
					  tool::msg_erros('Campo dtv está faltando');	// data vencimento da parcela
$FRM_detalhes		= isset( $_POST['dth'])?
					  $_POST['dth']:
					  tool::msg_erros('Campo dth está faltando');	// detalhes da parcela


$Query_update=faturamentos::find($FRM_id);
$Query_update->update_attributes(array(
										'dt_vencimento'	=>$FRM_dtvencimento,
										'valor'			=>$FRM_valor,
										'obs'			=>$FRM_detalhes,
										'usuarios_id'	=>$COB_Usuario_Id
										));

if($Query_update->titulos_bancarios_id > 0 ){


$Query_update_titulo=titulos::find($Query_update->titulos_bancarios_id);
$Query_update_titulo->update_attributes(
										array('dt_vencimento'	=>$FRM_dtvencimento,
											  'dt_atualizacao' 	=>date("Y-m-d"),
											  'stflagrem'		=>'1',
											  'cod_mov_rem'	 	=>remessas::Cod_Tab_Remessa($Query_titulo[0]->cod_banco,"MOV05")
										));

}

// VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
	if(!$Query_update){
			echo '":"","callback":"1","msg":"Erro ao atualizar dados!","status":"warning';
	}else{
				echo '":"","callback":"0","msg":"Dados atualizados !.","status":"success';
	}

################################################################################################################################################################################
// RECEBIMENTO DAS PARCELAS
}if($FRM_acao == "rec"){


	// define o valor da variavel erro
	$erro="";

	// matricula associado
	$FRM_matricula  	= isset( $_POST['mat'])						?	$_POST['mat']:
																	$erro=('Campo mat está faltando ');
	// ids das parcelas selecionadas
	$FRM_parcelas_id	= isset( $_POST['pa_id'])					?	$_POST['pa_id']:
																	$erro=('Campo pa_id está faltando ');
	// total a ser pago
	$FRM_vl_ttpg		= isset( $_POST['total'])					?	tool::limpaMoney($_POST['total']):
																	$erro=('Campo total está faltando');
	// total do valor de desconto concedido
	$FRM_vl_desconto 	= isset( $_POST['desc'])					?	tool::limpaMoney($_POST['desc']):
																	$erro=('Campo desc está faltando');
	// id da forma de recebimento
	$FRM_f_receb_id		= isset( $_POST['f_receb_id'])				?	$_POST['f_receb_id']:
																	$erro=('Campo f_receb_id está faltando');
	// id da forma de recebimento do sistema
	$FRM_f_receb_sys_id	= isset( $_POST['f_receb_sys_id'])			?	$_POST['f_receb_sys_id']:
																	$erro=('Campo f_receb_sys_id está faltando');
	// numero de documento se for cartao
	$FRM_num_doc_c		= isset( $_POST['num_doc_c'])			?	$_POST['num_doc_c']:
																	$erro=('Campo num_doc_c está faltando');

################################################################################################################################################################################
// VALIDANDO A FORMA DE PAGAMENTO PARA NÃO VIR NULA

	if($FRM_f_receb_id == 0){
		$erro=('É necessario selecionar uma forma da pagamento');
	}

	// verificamos se exixti algum erro nas variaveis anterir a este if
	if($erro!=""){
		// retorna um jason con os dados
		echo '":"","callback":"1","msg":"'.$erro.'","status":"danger';
		// para a exucução
		exit();
	}

	//	separa as parcelas
	$FRM_pa 			= explode(",",$FRM_parcelas_id);
	$FRM_t_p			= count($FRM_pa);				// total de parcelas

	// define o valor pago das parcelas levamos em consideração o total pago dividido pelo total de parcelas
	$FRM_vl_pago		= (($FRM_vl_ttpg-$FRM_vl_desconto)/$FRM_t_p);		// valor pago por parcela
	$FRM_vl_desc		= ($FRM_vl_desconto/$FRM_t_p);	// valor do desconto por parcela

	$query_errors = ""; // se houve erros em alguna query guarda s resultado

################################################################################################################################################################################
/* validamos a forma de cobrança para definir qual conta será lançado o valor */

	/* pagamento em especie direto no beneficiario */
	if($FRM_f_receb_sys_id == '001' or
	   $FRM_f_receb_sys_id == '005' or
	   $FRM_f_receb_sys_id == '006' or
	   $FRM_f_receb_sys_id == '008' or
	   $FRM_f_receb_sys_id == '009' or
	   $FRM_f_receb_sys_id == '010' ){

	   $Select_conta = "SELECT id FROM contas_bancarias WHERE empresas_id='".$COB_Empresa_Id."' AND tp_conta='0' AND status='1'";

	/* pagamento em cartoes com credito direto na conta do beneficiario */
	}elseif($FRM_f_receb_sys_id == 002 or $FRM_f_receb_sys_id == 003 ){

	   $Select_conta = "SELECT id FROM contas_bancarias WHERE empresas_id='".$COB_Empresa_Id."' AND tp_conta='2' AND status='1' AND maq_cartao='1'";

	   if(!$Select_conta){echo '":"","callback":"1","msg":"Empresa não possui conta configurada para recebimento de cartão.","status":"warning'; exit();}

	/* pagamento em debito automatico direto na conta do associado */
	}elseif($FRM_f_receb_sys_id == 007){

	   $Select_conta = "SELECT id FROM contas_bancarias WHERE empresas_id='".$COB_Empresa_Id."' AND tp_conta='2' AND status='1' AND debito_auto='1'";

	   	  if(!$Select_conta){echo '":"","callback":"1","msg":"Empresa não possui conta configurada para recebimento de debito em conta.","status":"warning'; exit();}

	}else{

		$Select_conta = "SELECT id FROM contas_bancarias WHERE empresas_id='".$COB_Empresa_Id."' AND tp_conta='0' AND status='1'";
	}

	// conta onde vai ser lançado o valor do recebimento
	$Query_conta_deposito=contas_bancarias::find_by_sql($Select_conta);

################################################################################################################################################################################

	//dados do associado
	$Query_associados=associados::find_by_matricula($FRM_matricula);


	// define se é negociação ou não com base se ouve desconto
	if($FRM_vl_desconto>"0"){
		$FRM_dt_neg=date("Y-m-d");/* data da negociação */
		$FRM_neg="S";/*negociada*/
	}else{
		$FRM_dt_neg="0000-00-00";
		$FRM_neg="N";
	}

###################################################################################################################################################################
// INICIA O LOOP DA PARCELAS

// definimos a variavel valor que será lançacado no caixa como zero antes do loop
$valor_caixa=0;

foreach ($FRM_pa as $value) {

	// seleciona a parcela pelo id
	$Query_parcela=faturamentos::find_by_sql("SELECT
											  faturamentos.*,
											  convenios.tipo_convenio,planos.seguro
											FROM
											  faturamentos
											  INNER JOIN convenios ON faturamentos.convenios_id = convenios.id
											  INNER JOIN dados_cobranca ON dados_cobranca.id =  faturamentos.dados_cobranca_id
  											  INNER JOIN planos ON planos.id = dados_cobranca.planos_id
											WHERE
											  faturamentos.id= '".$value."'");

	// recupera o vencimento e a referencia da parcela
	$dt_venc 	= new ActiveRecord\DateTime($Query_parcela[0]->dt_vencimento);
	$referencia = $Query_parcela[0]->referencia;


	// verificamos se a parcela já não foi baixada
	if($Query_parcela[0]->status == 1){

				$query_errors.=("<i class='uk-icon-exclamation-triangle  uk-text-warning' ></i> Parcela ".$value." já se encontra quitada ! </br");
				continue;
	}
	// calcula o valor negociado da parcelas com juros
	$FRM_vl_neg = faturamentos::Calcula_Juros($Query_parcela[0]->valor,$dt_venc->format('Y-m-d'),$dados_config->juros,$dados_config->multa);


	// definimos os acrescimos ou descontos
	if($FRM_vl_pago > $Query_parcela[0]->valor){
												$FRM_acrescimos=($FRM_vl_pago - $Query_parcela[0]->valor);
												$FRM_descontos="";
											}elseif($FRM_vl_pago < $Query_parcela[0]->valor){
												$FRM_acrescimos="";
												$FRM_descontos=($Query_parcela[0]->valor - $FRM_vl_pago) ;
											}else{
												$FRM_acrescimos="";
												$FRM_descontos=	"";
											}

 //altera os dados da parcela na tabela faturamento
	$Query_update=faturamentos::find($value);
	$Query_update->update_attributes(
		                           array(
											'status'				=>'1',
											'tipo_baixa'			=>"M",
											'negociada'				=>$FRM_neg,
											'dt_negociacao'			=>$FRM_dt_neg,
											'dt_pagamento'			=>date("Y-m-d"),
											'valor_negociado'		=>$FRM_vl_neg,
											'valor_pago'			=>$FRM_vl_pago,
											'acrescimos'			=>$FRM_acrescimos,
											'descontos'				=>$FRM_descontos,
											'flag_pago'				=>"PAGA",
											'contas_bancarias_id'	=>$Query_conta_deposito[0]->id,
											'usuarios_id'			=>$COB_Usuario_Id
											));


		// houve erros ao baixar a parcela
		if(!$Query_update){

			$query_errors.=("<i class='uk-icon-exclamation-triangle  uk-text-warning' ></i> Não foi possivel atualizar a parcela ".$value." houve algum erro </br");

		}else{

			// aqui vamos tratar o boleto caso ele exista para a parcela
			if($Query_parcela[0]->titulos_bancarios_id > 0 ){

				$Query_titulo=titulos::find_by_sql("SELECT titulos_bancarios.*,
													  contas_bancarias.cod_banco
													FROM
													  titulos_bancarios
													  INNER JOIN contas_bancarias ON contas_bancarias.id = titulos_bancarios.contas_bancarias_id
													WHERE
													  titulos_bancarios.id = ".$Query_parcela[0]->titulos_bancarios_id."");

				if($Query_titulo){

					if($Query_titulo[0]->cod_remessa > 0){

						if($Query_titulo[0]->status == 0){$stflagrem=1;}else{$stflagrem=0;}

					}else{$stflagrem=0;}

					$Query_update_titulo=titulos::find($Query_parcela[0]->titulos_bancarios_id);
					$Query_update_titulo->update_attributes(
													array(
															'status'		 =>1,
															'cod_ult_mov_rem'=>$Query_update_titulo->cod_mov_rem,
															'stflagrem'		 =>$stflagrem, /* avisamos para o sistema que este registro deve ser enviado ao banco pois houve movimentação */
															'dt_pagamento'	 =>date("Y-m-d"),
															'dt_atualizacao' =>date("Y-m-d"),
															'vlr_pago'		 =>$FRM_vl_pago,
															'cod_mov_rem'	 =>remessas::Cod_Tab_Remessa($Query_titulo[0]->cod_banco,"MOV12"),
															'mov_manual'	 =>"S",
                                            				'local_pagamento'=>'PGTO NO CEDENTE'
														));
				}else{

					$query_errors.=("<i class='uk-icon-exclamation-triangle  uk-text-warning' ></i> Titulo nº ".$Query_parcela[0]->titulos_bancarios_id." não encontrado!</br");

				}

			}
##################################################################################################################################################################################
// SE POSSUI SEGURO FAZ A INSERÇÃO DO DADOS NA TABELA DE SEGURADOS

		if($Query_parcela[0]->seguro == 1){

		// passa a matricula a referencia e o tipo de convenio pj ou pf
		$Query_assegurar = seguros::segurar($FRM_matricula,$referencia->format('Y-m-d'),$Query_parcela[0]->tipo_convenio,$COB_Empresa_Id);

			// VERIFICA SE CORREU TUDO BEM NA INSERÇÃO DO DADOS NA TABELA SEGUROS
			if($Query_assegurar == false){

			$query_errors.=("<i class='uk-icon-exclamation-triangle  uk-text-warning' ></i> Associado não assegurado na referencia ".$referencia->format('m-Y').".</br");


			}
		}
	}
##################################################################################################################################################################################
//  AQUI TRABALHAMOS A INSERÇÃO NO CAIXA

// definição do numero do documento
if($FRM_num_doc_c !=""){$doc_card =$FRM_num_doc_c;}else{$doc_card=$FRM_matricula."-".$value;}


// cria o lançamento no caiza
$create=caixa::create(array(
							'historico' 				=>"Recebimento parcela ".$value."",
							'data' 						=>date("Y-m-d"),
							'valor' 					=>$FRM_vl_pago,
							'numdoc' 					=>$doc_card,
							'tipolancamento'			=>1,
							'tipo'						=>"c",
							'formas_pagamentos_id' 		=>0,
							'formas_recebimentos_id'	=>$FRM_f_receb_id,
							'contas_bancarias_id' 		=>$Query_conta_deposito[0]->id,
							'empresas_id' 				=>$COB_Empresa_Id,
							'usuarios_id' 				=>$COB_Usuario_Id,
							'clientes_fornecedores_id' 	=>"0.".$FRM_matricula, // SERÁ ADIONADO O ZERO ANTES DA MATRICULA INDICANDO QUE É UM ASSOCIADO E NÃO UM CLIENTE OU FORNECEDOR
							'planos_contas_id' 			=>$dados_config->planos_contas_id,
							'centros_custos_id' 		=>$dados_config->planos_contas_id,
							'detalhes' 					=>"matricula ".$FRM_matricula.""
						));

// valida o create caixa
	if($create == false){

			$query_errors.=("<i class='uk-icon-exclamation-triangle  uk-text-warning' ></i> Erro ao criar lançamento no caixa parcela nº ".$value.".</br");
	}

}//final foreach
##################################################################################################################################################################################

	// VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
	if($query_errors !=""){
			echo '":"","callback":"1","msg":"'.$query_errors.'","status":"warning';
	}else{
		echo '":"","callback":"0","msg":"Recebimento Concluido.","status":"success';
	}

}
?>