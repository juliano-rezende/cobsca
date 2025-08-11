<?php
$Frm_cad 	= true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../../sessao.php";
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');





// faz update do registro
if(!empty($_POST['conta_id'])){


// geral
$FRM_erro               	= 0;
$FRM_conta_id				= isset( $_POST['conta_id'])		? $_POST['conta_id']: 			tool::msg_erros("Falta o Campo Obrigatorio conta_id.");
$FRM_nm_conta				= isset( $_POST['nm_conta'])		? $_POST['nm_conta']: 			tool::msg_erros("Falta o Campo Obrigatorio nm_conta.");
$FRM_tp_conta				= isset( $_POST['tp_conta'])		? $_POST['tp_conta']: 			tool::msg_erros("Falta o Campo Obrigatorio tp_conta.");
$FRM_cod_banco				= isset( $_POST['cod_banco'])		? $_POST['cod_banco']: 			tool::msg_erros("Falta o Campo Obrigatorio cod_banco.");
$FRM_agencia				= isset( $_POST['agencia'])			? $_POST['agencia']:			tool::msg_erros("Falta o Campo Obrigatorio agencia.");
$FRM_dv_agencia				= isset( $_POST['dv_agencia'])		? $_POST['dv_agencia']: 		tool::msg_erros("Falta o Campo Obrigatorio dv_agencia.");
$FRM_conta					= isset( $_POST['conta'])			? $_POST['conta']: 				tool::msg_erros("Falta o Campo Obrigatorio conta.");
$FRM_dv_conta				= isset( $_POST['dv_conta'])		? $_POST['dv_conta']: 			tool::msg_erros("Falta o Campo Obrigatorio dv_conta.");
$FRM_moeda					= isset( $_POST['moeda'])			? $_POST['moeda']: 				tool::msg_erros("Falta o Campo Obrigatorio moeda.");
$FRM_limite_credito			= isset( $_POST['lt_credito'])		? $_POST['lt_credito']: 		tool::msg_erros("Falta o Campo Obrigatorio lt_credito.");
$FRM_dt_venc_limite			= isset( $_POST['dt_v_limite'])		? $_POST['dt_v_limite']: 		tool::msg_erros("Falta o Campo Obrigatorio dt_v_limite.");
$FRM_pg_inicial				= isset( $_POST['pg_inicial'])		? $_POST['pg_inicial']: 		"0";
$FRM_status					= isset( $_POST['status'])			? $_POST['status']: 			"0";
$FRM_prev_financeira		= isset( $_POST['prev_financeira'])	? $_POST['prev_financeira']: 	"0";
$FRM_maq_cartao				= isset( $_POST['maq_cartao'])		? $_POST['maq_cartao']: 		"0";
$FRM_debito_auto			= isset( $_POST['deb_aut'])			? $_POST['deb_aut']: 			"0";


/* definimos a forma de recebimento pelo tipo da conta*/
if($FRM_tp_conta == 2){
	$FRM_formas_recebimentos_id = isset( $_POST['formas_recebimentos_id'])? $_POST['formas_recebimentos_id']: tool::msg_erros("Falta o Campo Obrigatorio formas_recebimentos_id.");
}else{

	$FRM_formas_recebimentos_id =0;
}


// VALIDA OS CAMPOS OBRIGATORIOS
if($FRM_tp_conta == 1){

	if(empty($FRM_cod_banco)){
		tool::msg_erros("Codigo do banco é obrigario é obrigatorio.");
	}if(empty($FRM_nm_conta)){
				tool::msg_erros("Nome da conta é obrigatorio.");
	}if(empty($FRM_agencia)){
				tool::msg_erros("Numero da agencia é obrigatorio.");
	}if(empty($FRM_conta)){
				tool::msg_erros("Numero da conta é obrigatorio.");
	}
}else{

	if(empty($FRM_nm_conta)){
		tool::msg_erros("Nome da conta é obrigario é obrigatorio.");
	}
}

/*
SE FOR PARA COLOCAR A CONTA PARA RECEBIMENTO DE CARTÃO NÃO DEVE EXISTIR OUTRA NO MESMO PARAMETRO CASO HAJA DEVERA SER ALTERADO ANTES DE ADCIONAR UMA NOVA OU ALTERAR UMA EXISTENTE
*/
if($FRM_maq_cartao == 1){

$Query_validate=contas_bancarias::find_by_id_and_maq_cartao("SELECT id FROM contas_bancarias WHERE id!='".$FRM_conta_id."' and maq_cartao='1'");

if($Query_validate){
		tool::msg_erros("Já existe uma conta para recebimento de cartão.");
	}

}

// EXECULTA A QUERY COD DE ERRO 001
$Query_update_cc=contas_bancarias::find($FRM_conta_id);
$Query_update_cc->update_attributes(array(
										'nm_conta' 				=> $FRM_nm_conta,
										'cod_banco' 			=> $FRM_cod_banco,
										'formas_recebimentos_id'=> $FRM_formas_recebimentos_id,
										'agencia' 				=> $FRM_agencia,
										'dv_agencia' 			=> $FRM_dv_agencia,
										'conta' 				=> $FRM_conta,
										'dv_conta' 				=> $FRM_dv_conta,
										'status' 				=> $FRM_status,
										'debito_auto' 			=> $FRM_debito_auto,
										'maq_cartao' 			=> $FRM_maq_cartao,
										'limite_credito' 		=> tool::limpaMoney($FRM_limite_credito),
										'dt_venc_limite' 		=> tool::InvertDateTime(tool::LimpaString($FRM_dt_venc_limite),0),
										'pg_inicial' 			=> $FRM_pg_inicial,
										'prev_financeira' 		=> $FRM_prev_financeira,
										'tp_conta' 				=> $FRM_tp_conta,
										));
/*
VALIDAÇÃO DE QUERY
*/
if($Query_update_cc != true){$FRM_erro=001;}

// SE A CONTA FOR COBRANÇA CRIA OU EDITA OS DADOS DA CONTA COBRANÇA
if($FRM_tp_conta == 2){


// cobrança
$FRM_favorecido			= isset( $_POST['favorecido'])			? $_POST['favorecido']			: tool::msg_erros("Falta o Campo Obrigatorio favor.");
$FRM_cnpj_fav			= isset( $_POST['cnpj_fav'])			? $_POST['cnpj_fav']			: tool::msg_erros("Falta o Campo Obrigatorio cnpj_fav.");
$FRM_cod_cedente		= isset( $_POST['cod_cedente'])			? $_POST['cod_cedente']			: tool::msg_erros("Falta o Campo Obrigatorio cod_cedente.");
$FRM_dv_cod_cedente		= isset( $_POST['dv_cod_ced'])			? $_POST['dv_cod_ced']			: tool::msg_erros("Falta o Campo Obrigatorio dv_cod_ced.");
$FRM_cod_transmissão	= isset( $_POST['cod_transmissao'])		? $_POST['cod_transmissao']		: tool::msg_erros("Falta o Campo Obrigatorio cod_transmissao.");
$FRM_carteira 			= isset( $_POST['carteira_cobranca'])	? $_POST['carteira_cobranca']	: tool::msg_erros("Falta o Campo Obrigatorio carteira_cobranca.");
$FRM_carteira 			= 	explode("_",$FRM_carteira);

$FRM_carteira_cob		= $FRM_carteira[0];
$FRM_desc_carteira_cob	= $FRM_carteira[1];

$FRM_variacao_carteira	= isset( $_POST['variacao_carteira'])	? $_POST['variacao_carteira']	: tool::msg_erros("Falta o Campo Obrigatorio variacao_carteira.");
$FRM_carteira_remessa	= isset( $_POST['carteira_remessa'])	? $_POST['carteira_remessa']	: tool::msg_erros("Falta o Campo Obrigatorio carteira_remessa.");
$FRM_modalidade	= isset( $_POST['modalidade'])	? $_POST['modalidade']	: tool::msg_erros("Falta o Campo Obrigatorio modalidade.");
$FRM_especie			= isset( $_POST['especie'])				? $_POST['especie']				: tool::msg_erros("Falta o Campo Obrigatorio especie.");
$FRM_especie_doc		= isset( $_POST['especie_doc'])			? $_POST['especie_doc']			: tool::msg_erros("Falta o Campo Obrigatorio especie_doc.");
$FRM_aceite				= isset( $_POST['aceite'])				? $_POST['aceite']				: tool::msg_erros("Falta o Campo Obrigatorio aceite.");
$FRM_ult_nosso_numero	= isset( $_POST['ult_nosso_numero'])	? $_POST['ult_nosso_numero']	: tool::msg_erros("Falta o Campo Obrigatorio ult_nosso_numero.");
$FRM_tipo_arquivo		= isset( $_POST['tp_arquivo'])			? $_POST['tp_arquivo']			: tool::msg_erros("Falta o Campo Obrigatorio tp_arquivo no form.");

// detalhes do boleto
$FRM_local_pgto			= isset( $_POST['local_pgto'])			? $_POST['local_pgto']			: tool::msg_erros("Falta o Campo Obrigatorio local_pgto.");
$FRM_inst1				= isset( $_POST['inst1'])				? $_POST['inst1']				: tool::msg_erros("Falta o Campo Obrigatorio inst1.");
$FRM_inst2				= isset( $_POST['inst2'])				? $_POST['inst2']				: tool::msg_erros("Falta o Campo Obrigatorio inst2.");
$FRM_inst_adcional		= isset( $_POST['inst_adcional'])		? $_POST['inst_adcional']		: tool::msg_erros("Falta o Campo Obrigatorio inst_adcional.");

/*
VALIDAÇÕES
*/
//if(empty($FRM_favorecido)){
//				tool::msg_erros("Nome do favorecido é obrigatorio.");
//	}if(empty($FRM_cod_cedente)){
//				tool::msg_erros("Codigo do cedente ou codigo cliente é obrigatorio.");
//	}if(empty($FRM_carteira_cob)){
//				tool::msg_erros("Codigo da carteira cobrança é obrigatorio.");
//	}if(empty($FRM_especie)){
//				tool::msg_erros("Especie é obrigatorio.");
//	}if(empty($FRM_especie_doc)){
//				tool::msg_erros("Especie do documento é obrigatorio.");
//	}if(empty($FRM_aceite)){
//				tool::msg_erros("Campo aceite é obrigatorio.");
//	}




// VERIFICAMOS SE JÁ EXISTE OS DADOS
$Query_cob=contas_bancarias_cob::find_by_contas_bancarias_id($FRM_conta_id);


if($Query_cob == true){

/*
COD DE ERRO 002
*/
$Query_cob->update_attributes(array(
										'cod_cedente' 		=> $FRM_cod_cedente,
										'dv_cod_cedente' 	=> $FRM_dv_cod_cedente,
										'cod_transmissao' 	=> $FRM_cod_transmissão,
										'carteira_cobranca'	=> $FRM_carteira_cob,
										'variacao_carteira' => $FRM_variacao_carteira,
										'desc_carteira_cob' => $FRM_desc_carteira_cob,
										'desc_carteira_cob'	=> $FRM_desc_carteira_cob,
										'carteira_remessa'	=> $FRM_carteira_remessa,
										'local_pgto' 		=> $FRM_local_pgto,
										'favorecido' 		=> $FRM_favorecido,
										'cnpj' 				=> tool::LimpaString($FRM_cnpj_fav),
										'especie' 			=> $FRM_especie,
										'especie_doc' 		=> $FRM_especie_doc,
										'aceite' 			=> $FRM_aceite,
										'tipo_arquivo' 		=> $FRM_tipo_arquivo,
										'moeda' 			=> $FRM_moeda,
										'ult_nosso_numero' 	=> $FRM_ult_nosso_numero,
										'inst1' 			=> $FRM_inst1,
										'inst2' 			=> $FRM_inst2,
										'inst_adcional' 	=> $FRM_inst_adcional,
										'usuarios_id' 		=> $COB_Usuario_Id
										));

}else{
/*
COD DE ERRO 003
*/
$Query_cob 	= contas_bancarias_cob::create(
										array(
											'cod_cedente' 		 => $FRM_cod_cedente,
											'dv_cod_cedente' 	 => $FRM_dv_cod_cedente,
											'cod_transmissao' 	 => $FRM_cod_transmissão,
											'carteira_cobranca'	 => $FRM_carteira_cob,
											'variacao_carteira'  => $FRM_variacao_carteira,
											'desc_carteira_cob'  => $FRM_desc_carteira_cob,
											'carteira_remessa'	 => $FRM_carteira_remessa,
                                 'modalidade'	 => $FRM_modalidade,
											'local_pgto' 		 => $FRM_local_pgto,
											'favorecido' 		 => $FRM_favorecido,
											'cnpj' 				 => tool::LimpaString($FRM_cnpj_fav),
											'especie' 			 => $FRM_especie,
											'especie_doc' 		 => $FRM_especie_doc,
											'aceite' 			 => $FRM_aceite,
											'ult_nosso_numero' 	 => $FRM_ult_nosso_numero,
											'tipo_arquivo' 		 => $FRM_tipo_arquivo,
											'moeda' 			 => $FRM_moeda,
											'inst1' 			 => $FRM_inst1,
											'inst2' 			 => $FRM_inst2,
											'inst_adcional' 	 => $FRM_inst_adcional,
											'contas_bancarias_id'=> $FRM_conta_id,
											'usuarios_id' 		 => $COB_Usuario_Id
											));
}

/*
VALIDAÇÃO DE QUERY
*/
if($Query_cob != true){$FRM_erro="002/003";}
}


if($FRM_erro ==""){
	echo $FRM_conta_id;
	}else{
		echo tool::msg_erros("Erro ao atualizar dados da conta COD ".$FRM_erro."");
		}



// cria um novo registro
}else{

// geral
$FRM_nm_conta			= isset( $_POST['nm_conta'])		? $_POST['nm_conta']						: tool::msg_erros("Falta o Campo Obrigatorio nm_conta.");
$FRM_tp_conta			= isset( $_POST['tp_conta'])		? $_POST['tp_conta']						: tool::msg_erros("Falta o Campo Obrigatorio tp_conta.");
$FRM_cod_banco			= isset( $_POST['cod_banco'])		? $_POST['cod_banco']						: tool::msg_erros("Falta o Campo Obrigatorio cod_banco.");
$FRM_agencia			= isset( $_POST['agencia'])			? $_POST['agencia']							: tool::msg_erros("Falta o Campo Obrigatorio agencia.");
$FRM_dv_agencia			= isset( $_POST['dv_agencia'])		? $_POST['dv_agencia']						: tool::msg_erros("Falta o Campo Obrigatorio dv_agencia.");
$FRM_conta				= isset( $_POST['conta'])			? $_POST['conta']							: tool::msg_erros("Falta o Campo Obrigatorio conta.");
$FRM_dv_conta			= isset( $_POST['dv_conta'])		? $_POST['dv_conta']						: tool::msg_erros("Falta o Campo Obrigatorio dv_conta.");
$FRM_moeda				= isset( $_POST['moeda'])			? $_POST['moeda']							: tool::msg_erros("Falta o Campo Obrigatorio moeda.");
$FRM_limite_credito		= isset( $_POST['lt_credito'])		? $_POST['lt_credito']						: tool::msg_erros("Falta o Campo Obrigatorio lt_credito.");
$FRM_dt_venc_limite		= isset( $_POST['dt_v_limite'])		? $_POST['dt_v_limite']						: tool::msg_erros("Falta o Campo Obrigatorio dt_v_limite.");
$FRM_pg_inicial			= isset( $_POST['pg_inicial'])		? $_POST['pg_inicial']						: "0";
$FRM_status				= isset( $_POST['status'])			? $_POST['status']							: "0";
$FRM_prev_financeira	= isset( $_POST['prev_financeira'])	? $_POST['prev_financeira']					: "0";
$FRM_dt_abertura		= isset( $_POST['dt_abertura'])		? $_POST['dt_abertura']						: tool::msg_erros("Falta o Campo Obrigatorio dt_abertura.");
$FRM_sd_abertura		= isset( $_POST['sd_abertura'])		? tool::limpaMoney($_POST['sd_abertura'])	: tool::msg_erros("Falta o Campo Obrigatorio sd_abertura.");
$FRM_maq_cartao			= isset( $_POST['maq_cartao'])		? $_POST['maq_cartao']						: "0";
$FRM_debito_auto		= isset( $_POST['deb_aut'])			? $_POST['deb_aut']							: "0";

/* definimos a forma de recebimento pelo tipo da conta*/
if($FRM_tp_conta == 2){
	$FRM_formas_recebimentos_id = isset( $_POST['formas_recebimentos_id'])? $_POST['formas_recebimentos_id']: tool::msg_erros("Falta o Campo Obrigatorio formas_recebimentos_id.");
}else{

	$FRM_formas_recebimentos_id =0;
}




// VALIDA OS CAMPOS OBRIGATORIOS
//if($FRM_tp_conta == 1){
//
//	if(empty($FRM_cod_banco)){
//		tool::msg_erros("Codigo do banco é obrigario é obrigatorio.");
//	}if(empty($FRM_nm_conta)){
//				tool::msg_erros("Nome da conta é obrigatorio.");
//	}if(empty($FRM_agencia)){
//				tool::msg_erros("Numero da agencia é obrigatorio.");
//	}if(empty($FRM_conta)){
//				tool::msg_erros("Numero da conta é obrigatorio.");
//	}
//}else{
//
//	if(empty($FRM_nm_conta)){
//		tool::msg_erros("Nome da conta é obrigario é obrigatorio.");
//	}
//}

/*
SE FOR PARA COLOCAR A CONTA PARA RECEBIMENTO DE CARTÃO NÃO DEVE EXISTIR OUTRA NO MESMO PARAMETRO CASO HAJA DEVERA SER ALTERADO ANTES DE ADCIONAR UMA NOVA OU ALTERAR UMA EXISTENTE
*/
if($FRM_maq_cartao == 1){

$Query_validate=contas_bancarias::find_by_maq_cartao(1);

if($Query_validate == true){
		tool::msg_erros("Já existe uma conta para recebimento de cartão.");
	}

}


// CRIA A CONTA BANCARIA cnpj_fav
$Query_contas 	= contas_bancarias::create(
										array(
											'nm_conta' 				=> $FRM_nm_conta,
											'cod_banco' 			=> $FRM_cod_banco,
											'formas_recebimentos_id'=> $FRM_formas_recebimentos_id,
											'agencia' 				=> $FRM_agencia,
											'dv_agencia' 			=> $FRM_dv_agencia,
											'conta' 				   => $FRM_conta,
											'dv_conta' 				=> $FRM_dv_conta,
											'status' 				=> $FRM_status,
											'debito_auto' 			=> $FRM_debito_auto,
											'maq_cartao' 			=> $FRM_maq_cartao,
											'limite_credito' 		=> tool::limpaMoney($FRM_limite_credito),
											'dt_venc_limite' 		=> tool::InvertDateTime(tool::LimpaString($FRM_dt_venc_limite),0),
											'pg_inicial' 			=> $FRM_pg_inicial,
											'prev_financeira' 		=> $FRM_prev_financeira,
											'dt_abertura' 			=> tool::InvertDateTime(tool::LimpaString($FRM_dt_abertura),0),
											'sd_inicial' 			=> tool::limpaMoney($FRM_sd_abertura),
											'tp_conta' 				=> $FRM_tp_conta,
											'dt_criacao'			=> date("Y-m-d"),
											'empresas_id' 			=> $COB_Empresa_Id,
											'usuarios_id' 			=> $COB_Usuario_Id
											));
// RECUPERA A ULTIMA CONTA CRIADA
$Ultimaconta=contas_bancarias::find("last");//recupera o ultimo id


// SE A CONTA FOR COBRANÇA CRIA OS DADOS DA CONTA COBRANÇA
if($FRM_tp_conta == 2){


// cobrança
$FRM_favorecido			= isset( $_POST['favorecido'])			? $_POST['favorecido']			: tool::msg_erros("Falta o Campo Obrigatorio favor.");
$FRM_cnpj_fav			= isset( $_POST['cnpj_fav'])			? $_POST['cnpj_fav']			: tool::msg_erros("Falta o Campo Obrigatorio cnpj_fav.");
$FRM_cod_cedente		= isset( $_POST['cod_cedente'])			? $_POST['cod_cedente']			: tool::msg_erros("Falta o Campo Obrigatorio cod_cedente.");
$FRM_dv_cod_cedente		= isset( $_POST['dv_cod_ced'])			? $_POST['dv_cod_ced']			: tool::msg_erros("Falta o Campo Obrigatorio dv_cod_ced.");
$FRM_cod_transmissão	= isset( $_POST['cod_transmissao'])		? $_POST['cod_transmissao']		: tool::msg_erros("Falta o Campo Obrigatorio cod_transmissao.");
$FRM_carteira 			= isset( $_POST['carteira_cobranca'])	? $_POST['carteira_cobranca']	: tool::msg_erros("Falta o Campo Obrigatorio carteira_cobranca.");
$FRM_carteira 			= 	explode("_",$FRM_carteira);

$FRM_carteira_cob		= $FRM_carteira[0];
$FRM_desc_carteira_cob	= $FRM_carteira[1];


$FRM_variacao_carteira	= isset( $_POST['variacao_carteira'])	? $_POST['variacao_carteira']	: tool::msg_erros("Falta o Campo Obrigatorio variacao_carteira.");
$FRM_carteira_remessa	= isset( $_POST['carteira_remessa'])	? $_POST['carteira_remessa']	: tool::msg_erros("Falta o Campo Obrigatorio carteira_remessa.");
$FRM_modalidade	= isset( $_POST['modalidade'])	? $_POST['modalidade']	: tool::msg_erros("Falta o Campo Obrigatorio modalidade.");
$FRM_especie			= isset( $_POST['especie'])				? $_POST['especie']				: tool::msg_erros("Falta o Campo Obrigatorio especie.");
$FRM_especie_doc		= isset( $_POST['especie_doc'])			? $_POST['especie_doc']			: tool::msg_erros("Falta o Campo Obrigatorio especie_doc.");
$FRM_aceite				= isset( $_POST['aceite'])				? $_POST['aceite']				: tool::msg_erros("Falta o Campo Obrigatorio aceite.");
$FRM_ult_nosso_numero	= isset( $_POST['ult_nosso_numero'])	? $_POST['ult_nosso_numero']	: tool::msg_erros("Falta o Campo Obrigatorio ult_nosso_numero.");
$FRM_tipo_arquivo		= isset( $_POST['tp_arquivo'])			? $_POST['tp_arquivo']			: tool::msg_erros("Falta o Campo Obrigatorio tp_arquivo.");

// detalhes do boleto
$FRM_local_pgto			= isset( $_POST['local_pgto'])			? $_POST['local_pgto']			: tool::msg_erros("Falta o Campo Obrigatorio local_pgto.");
$FRM_inst1				= isset( $_POST['inst1'])				? $_POST['inst1']				: tool::msg_erros("Falta o Campo Obrigatorio inst1.");
$FRM_inst2				= isset( $_POST['inst2'])				? $_POST['inst2']				: tool::msg_erros("Falta o Campo Obrigatorio inst2.");
$FRM_inst_adcional		= isset( $_POST['inst_adcional'])		? $_POST['inst_adcional']		: tool::msg_erros("Falta o Campo Obrigatorio inst_adcional.");

/*
VALIDAÇÕES
*/
//if(empty($FRM_favorecido)){
//				tool::msg_erros("Nome do favorecido é obrigatorio.");
//	}if(empty($FRM_cod_cedente)){
//				tool::msg_erros("Codigo do cedente ou codigo cliente é obrigatorio.");
//	}if(empty($FRM_carteira_cob)){
//				tool::msg_erros("Codigo da carteira cobrança é obrigatorio.");
//	}if(empty($FRM_especie)){
//				tool::msg_erros("Especie é obrigatorio.");
//	}if(empty($FRM_especie_doc)){
//				tool::msg_erros("Especie do documento é obrigatorio.");
//	}if(empty($FRM_aceite)){
//				tool::msg_erros("Campo aceite é obrigatorio.");
//	}


$Query_contas 	= contas_bancarias_cob::create(
										array(
											'cod_cedente' 		 => $FRM_cod_cedente,
											'dv_cod_cedente' 	 => $FRM_dv_cod_cedente,
											'cod_transmissao' 	 => $FRM_cod_transmissão,
											'carteira_cobranca'	 => $FRM_carteira_cob,
											'desc_carteira_cob'  => $FRM_desc_carteira_cob,
											'variacao_carteira'  => $FRM_variacao_carteira,
											'carteira_remessa'	 => $FRM_carteira_remessa,
                                 'modalidade'	 => $FRM_modalidade,
											'local_pgto' 		 => $FRM_local_pgto,
											'favorecido' 		 => $FRM_favorecido,
											'cnpj' 				 => tool::LimpaString($FRM_cnpj_fav),
											'especie' 			 => $FRM_especie,
											'especie_doc' 		 => $FRM_especie_doc,
											'aceite' 			 => $FRM_aceite,
											'ult_nosso_numero' 	 => $FRM_ult_nosso_numero,
											'tipo_arquivo' 		 => $FRM_tipo_arquivo,
											'moeda' 			 => $FRM_moeda,
											'inst1' 			 => $FRM_inst1,
											'inst2' 			 => $FRM_inst2,
											'inst_adcional' 	 => $FRM_inst_adcional,
											'contas_bancarias_id' => $Ultimaconta->id,
											'usuarios_id' 		  => $COB_Usuario_Id
											));
}



// VERIFICA SE O SALDO É MAIOR QUE ZERO
if($FRM_sd_abertura > 0){

	// CRIA O SALDO INICIAL DA CONTA NO CAIXA
	$Query_caixa 	= caixa::create(
									array(
										'historico' 			=> "Abertura de conta",
										'data' 					=> date("Y-m-d"),
										'valor' 				=> $FRM_sd_abertura,
										'numdoc' 				=> "1",
										'tipolancamento' 		=> "1",
										'tipo' 					=> "c",
										'contas_bancarias_id' 	=> $Ultimaconta->id,
										'empresas_id' 			=> $COB_Empresa_Id,
										'usuarios_id' 			=> $COB_Usuario_Id
									));

}

if($Query_contas==true ){
		echo $Ultimaconta->id;
		}else{
			echo tool::msg_erros("Erro ao cadastrar Conta");
			}
}
?>