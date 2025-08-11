<?php
$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro


//
require_once"../../sessao.php";
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');


//classe de validação de cnpj e cpf
require_once("../../classes/valid_cpf_cnpj.php");





//// faz update do registro
if(!empty($_POST['conv_id'])){


$FRM_convenio_id	=	isset( $_POST['conv_id'])	? $_POST['conv_id']: 				 tool::msg_erros("O Campo Obrigatorio conv_id.");
$FRM_convenio_id_maismed	=	isset( $_POST['convenio_id_maismed'])	? $_POST['convenio_id_maismed']: 				 tool::msg_erros("O Campo Obrigatorio convenio_id_maismed.");
$FRM_razao_social	=	isset( $_POST['rzsc'])		? $_POST['rzsc']: 					 tool::msg_erros("O Campo Obrigatorio razao social.");
$FRM_nm_fantasia	=	isset( $_POST['nmfant'])	? $_POST['nmfant']: 				 tool::msg_erros("O Campo Obrigatorio nome fantasia.");
$FRM_cnpj			=	isset( $_POST['cnpj'])		? tool::LimpaString($_POST['cnpj']): tool::msg_erros("O Campo Obrigatorio cnpj.");
$FRM_im				=	isset( $_POST['im'])		? $_POST['im']: 					 tool::msg_erros("O Campo Obrigatorio isc municipal.");
$FRM_ie				=	isset( $_POST['ie'])		? $_POST['ie']: 					 tool::msg_erros("O Campo Obrigatorio isc estadual.");
$FRM_logradouro_id	=	isset( $_POST['logradouro'])? $_POST['logradouro']: 			 tool::msg_erros("O Campo Obrigatorio lograduro_id.");
$FRM_compl			=	isset( $_POST['compl_end']) ? $_POST['compl_end']:				 tool::msg_erros("O Campo Obrigatorio complemento.");
$FRM_num			=	isset( $_POST['num'])		? $_POST['num']: 					 tool::msg_erros("O Campo Obrigatorio  numero.");
$FRM_fone_fixo		=	isset( $_POST['fn_fx'])		? tool::LimpaString($_POST['fn_fx']):tool::msg_erros("O Campo Obrigatorio  fone fixo.");
$FRM_fone_cel		=	isset( $_POST['fn_cel'])	? tool::LimpaString($_POST['fn_cel']):	tool::msg_erros("O Campo Obrigatorio  fone cel.");
$FRM_email			=	isset( $_POST['email'])		? $_POST['email']:						tool::msg_erros("O Campo Obrigatorio  email.");
$FRM_contato		=	isset( $_POST['ct'])		? $_POST['ct']: 						tool::msg_erros("O Campo Obrigatorio  contato.");

// DETALHES DO CONVENIO
$FRM_tp_convenio	=	isset( $_POST['tp_convenio'])	? $_POST['tp_convenio']: 		tool::msg_erros("O Campo Obrigatorio  tp_convenio.");
$FRM_tx_adesao		=	isset( $_POST['tx_adesao'])		? $_POST['tx_adesao']: 			tool::msg_erros("O Campo Obrigatorio  tx_adesao.");

// VALIDA OS TELEFONES
if(empty($FRM_fone_fixo)){
		if(empty($FRM_fone_cel)){
			tool::msg_erros("É obrigatório pelo menos um numero de telefone.");
	}
}

// VALIDAÇÃO DO CPF
$cpf_cnpj = new ValidaCPFCNPJ($FRM_cnpj);
if ( !$cpf_cnpj->valida() ) {
	sleep(1);
	echo tool::msg_erros("O cnpj invalido.");
	return false;
}

// EXECULTA A QUERY
$Query_update=convenios::find($FRM_convenio_id);
$Query_update->update_attributes(array(
										'razao_social' 	=>$FRM_razao_social,
										'convenio_id_maismed' 	=>$FRM_convenio_id_maismed,
										'nm_fantasia' 	=>$FRM_nm_fantasia,
										'cnpj' 			=>$FRM_cnpj,
										'im' 			=>$FRM_im,
										'ie' 			=>$FRM_ie,
										'fone_fixo' 	=>$FRM_fone_fixo,
										'fone_cel' 		=>$FRM_fone_cel,
										'email' 		=>$FRM_email,
										'contato' 		=>$FRM_contato,
										'logradouros_id'=>$FRM_logradouro_id,
										'compl_end'		=>$FRM_compl,
										'num' 			=>$FRM_num,
										'tipo_convenio' =>$FRM_tp_convenio,
										'tx_adesao' 	=>tool::limpaMoney($FRM_tx_adesao),
										'usuarios_id' 	=>$COB_Usuario_Id
										));

if($Query_update==true){
	echo $FRM_convenio_id;
	}else{
		echo tool::msg_erros("Erro ao atualizar dados do convênio.");
		}



// edita um novo registro
}else{


$FRM_convenio_id_maismed	=	isset( $_POST['convenio_id_maismed'])	? $_POST['convenio_id_maismed']: 				 tool::msg_erros("O Campo Obrigatorio convenio_id_maismed.");
$FRM_razao_social	=	isset( $_POST['rzsc'])		? $_POST['rzsc']: 					 tool::msg_erros("O Campo Obrigatorio razao social.");
$FRM_nm_fantasia	=	isset( $_POST['nmfant'])	? $_POST['nmfant']: 				 tool::msg_erros("O Campo Obrigatorio nome fantasia.");
$FRM_cnpj			=	isset( $_POST['cnpj'])		? tool::LimpaString($_POST['cnpj']): tool::msg_erros("O Campo Obrigatorio cnpj.");
$FRM_im				=	isset( $_POST['im'])		? $_POST['im']: 					 tool::msg_erros("O Campo Obrigatorio isc municipal.");
$FRM_ie				=	isset( $_POST['ie'])		? $_POST['ie']: 					 tool::msg_erros("O Campo Obrigatorio isc estadual.");
$FRM_logradouro_id	=	isset( $_POST['logradouro'])? $_POST['logradouro']: 			 tool::msg_erros("O Campo Obrigatorio  lograduro_id.");
$FRM_compl			=	isset( $_POST['compl_end']) ? $_POST['compl_end']:				 tool::msg_erros("O Campo Obrigatorio complemento.");
$FRM_num			=	isset( $_POST['num'])		? $_POST['num']: 					 tool::msg_erros("O Campo Obrigatorio  numero.");
$FRM_fone_fixo		=	isset( $_POST['fn_fx'])		? tool::LimpaString($_POST['fn_fx']):tool::msg_erros("O Campo Obrigatorio  fone fixo.");
$FRM_fone_cel		=	isset( $_POST['fn_cel'])	? tool::LimpaString($_POST['fn_cel']):	tool::msg_erros("O Campo Obrigatorio  fone cel.");
$FRM_email			=	isset( $_POST['email'])		? $_POST['email']:						tool::msg_erros("O Campo Obrigatorio  email.");
$FRM_contato		=	isset( $_POST['ct'])		? $_POST['ct']: 						tool::msg_erros("O Campo Obrigatorio  contato.");


// DETALHES DO CONVENIO
$FRM_tp_convenio	=	isset( $_POST['tp_convenio'])	? $_POST['tp_convenio']: 		tool::msg_erros("O Campo Obrigatorio  tp_convenio.");
$FRM_tx_adesao		=	isset( $_POST['tx_adesao'])		? $_POST['tx_adesao']: 			tool::msg_erros("O Campo Obrigatorio  tx_adesao.");




// VERIFICA DUPLICIDADE DE CNPJ
$query_duplicidade=convenios::find_by_cnpj($FRM_cnpj);
if($query_duplicidade){echo tool::msg_erros("CNPJ cadastrado em nossa base de dados.");}

// VALIDA OS TELEFONES
if(empty($FRM_fone_fixo)){
		if(empty($FRM_fone_cel)){
			tool::msg_erros("É obrigatório pelo menos um numero de telefone.");
	}
}

// VALIDAÇÃO DO CPF
$cpf_cnpj = new ValidaCPFCNPJ($FRM_cnpj);
if ( !$cpf_cnpj->valida() ) {
	sleep(1);
	echo tool::msg_erros("O CNPJ invalido.");
	return false;
}

// EXECULTA A QUERY
$Query_convenios	= convenios::create(
												array(
												    'convenio_id_maismed' 	=>$FRM_convenio_id_maismed,
													'razao_social' 	=>$FRM_razao_social,
													'nm_fantasia' 	=>$FRM_nm_fantasia,
													'cnpj' 			=>$FRM_cnpj,
													'im' 			=>$FRM_im,
													'ie' 			=>$FRM_ie,
													'fone_fixo' 	=>$FRM_fone_fixo,
													'fone_cel' 		=>$FRM_fone_cel,
													'email' 		=>$FRM_email,
													'contato' 		=>$FRM_contato,
													'logradouros_id'=>$FRM_logradouro_id,
													'compl_end'		=>$FRM_compl,
													'num' 			=>$FRM_num,
													'tipo_convenio' =>$FRM_tp_convenio,
													'tx_adesao' 	=>tool::limpaMoney($FRM_tx_adesao),
													'dt_cadastro'	=>date("Y-m-d"),
													'usuarios_id' 	=>$COB_Usuario_Id,
													'empresas_id' 	=>$COB_Empresa_Id
												));

if($Query_convenios==true){
		$Ultimoconvenio=convenios::find("last");//recupera o ultimo id
		echo $Ultimoconvenio->id;
		}else{
			echo tool::msg_erros("Erro ao cadastrar Convenio");
			}

}
?>