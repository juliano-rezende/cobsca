<?php
$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../sessao.php";
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');

//classe de validação de cnpj e cpf
require_once("../../classes/valid_cpf_cnpj.php");

// defini a ação a ser executada pelo controller
$FRM_action	=	isset( $_POST['action'])	? $_POST['action']:	tool::msg_erros("O Campo ação é Obrigatorio.");


// adciona nova forma de conbrança ao convênio
if($FRM_action == "addFcob"){

	$FRM_convenio_id	=	isset( $_POST['Conv_id'])			? $_POST['Conv_id']: 			tool::msg_erros("O Campo Obrigatorio convenio.");
	$FRM_descricao		=	isset( $_POST['desc'])				? $_POST['desc']:				tool::msg_erros("O Campo Obrigatorio descricao.");
	$FRM_f_cob_sys		=	isset( $_POST['f_cob_sys'])			? $_POST['f_cob_sys']: 			tool::msg_erros("O Campo Obrigatorio forma de cobrança.");
	$FRM_tp_cob			=	isset( $_POST['tp_cob'])			? $_POST['tp_cob']: 			tool::msg_erros("O Campo Obrigatorio tipo de cobrança.");


// EXECULTA A QUERY
	$Query_formasCob = formas_cobranca::create(
		array(
			'descricao' 			=> $FRM_descricao,
			'status' 				=> "1",
			'tipo' 					=> $FRM_tp_cob,
			'convenios_id' 			=> $FRM_convenio_id,
			'forma_cobranca_sys_id' => $FRM_f_cob_sys,
			'empresas_id' 			=> $COB_Empresa_Id
		));

	if($Query_formasCob==true){
		echo $FRM_convenio_id;
	}else{
		echo tool::msg_erros("Erro ao adcionar forma de cobrança ao convênio.");
	}
}

// adciona novo plano a forma de cobrança
if($FRM_action == "addFpl"){

	$FRM_descricao			=	isset( $_POST['mod_plano'])		? ($_POST['mod_plano']):		tool::msg_erros("O Campo Obrigatorio desc_plano.");
	$FRM_valor				=	isset( $_POST['vlr_plano'])		? $_POST['vlr_plano']: 			tool::msg_erros("O Campo Obrigatorio vlr_plano.");
	$FRM_obs_plano			=	isset( $_POST['desc_plano'])	? $_POST['desc_plano']: 		tool::msg_erros("O Campo Obrigatorio mod_plano.");
	$FRM_seguro				=	isset( $_POST['seguro'])		? $_POST['seguro']: 			tool::msg_erros("O Campo Obrigatorio seguro.");
	$FRM_forma_cobranca_id	=	isset( $_POST['f_cob_conv'])	? $_POST['f_cob_conv']: 		tool::msg_erros("O Campo Obrigatorio f_cob_conv.");


	// EXECULTA A QUERY
	$Query_planosCob = planos::create(
		array(
			'descricao' 		=> $FRM_descricao,
			'valor' 			=> tool::limpaMoney($FRM_valor),
			'obs_plano' 		=> $FRM_obs_plano,
			'seguro' 			=> $FRM_seguro,
			'data_cadastro'		=> date("Y-m-d"),
			'forma_cobranca_id' => $FRM_forma_cobranca_id
		));

	if($Query_planosCob==true){
		echo $FRM_forma_cobranca_id;
	}else{
		echo tool::msg_erros("Erro ao adcionar plano a forma de cobrança.");
	}
}


?>