<?php

$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../sessao.php";
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');

//classe de validação de cnpj e cpf
require_once("../../classes/valid_cpf_cnpj.php");


// faz update do registro
if(!empty($_POST['emp_id'])){


$FRM_empresa_id		=	isset( $_POST['emp_id'])	? $_POST['emp_id']: 				 tool::msg_erros("O Campo Obrigatorio empresa_id.");
$FRM_razao_social	=	isset( $_POST['rzsc'])		? $_POST['rzsc']: 					 tool::msg_erros("O Campo Obrigatorio razao social.");
$FRM_nm_fantasia	=	isset( $_POST['nmfant'])	? $_POST['nmfant']: 				 tool::msg_erros("O Campo Obrigatorio nome fantasia.");
$FRM_cnpj			=	isset( $_POST['cnpj'])		? tool::LimpaString($_POST['cnpj']): tool::msg_erros("O Campo Obrigatorio cnpj.");
$FRM_im				=	isset( $_POST['im'])		? $_POST['im']: 					 tool::msg_erros("O Campo Obrigatorio isc municipal.");
$FRM_ie				=	isset( $_POST['ie'])		? $_POST['ie']: 					 tool::msg_erros("O Campo Obrigatorio isc estadual.");
$FRM_logradouro_id	=	isset( $_POST['logradouro'])? $_POST['logradouro']: 			 tool::msg_erros("O Campo Obrigatorio  lograduro_id.");
$FRM_compl			=	isset( $_POST['compl_end'])	? $_POST['compl_end']: 				 tool::msg_erros("O Campo Obrigatorio  compl_end.");
$FRM_num			=	isset( $_POST['num'])		? $_POST['num']: 					 tool::msg_erros("O Campo Obrigatorio  numero.");
$FRM_fone_fixo		=	isset( $_POST['fn_fx'])		? tool::LimpaString($_POST['fn_fx']):tool::msg_erros("O Campo Obrigatorio  fone fixo.");
$FRM_fone_cel		=	isset( $_POST['fn_cel'])	? tool::LimpaString($_POST['fn_cel']):	tool::msg_erros("O Campo Obrigatorio  fone cel.");
$FRM_email			=	isset( $_POST['email'])		? $_POST['email']:						tool::msg_erros("O Campo Obrigatorio  email.");
$FRM_website		=	isset( $_POST['wbst'])		? $_POST['wbst']: 						tool::msg_erros("O Campo Obrigatorio  web site.");
$FRM_contato		=	isset( $_POST['ct'])		? $_POST['ct']: 						tool::msg_erros("O Campo Obrigatorio  contato.");


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
$Query_update=empresas::find($FRM_empresa_id);
$Query_update->update_attributes(array(
										'razao_social' 	=>$FRM_razao_social,
										'nm_fantasia' 	=>$FRM_nm_fantasia,
										'cnpj' 			=>$FRM_cnpj,
										'im' 			=>$FRM_im,
										'ie' 			=>$FRM_ie,
										'fone_fixo' 	=>$FRM_fone_fixo,
										'fone_cel' 		=>$FRM_fone_cel,
										'email' 		=>$FRM_email,
										'website' 		=>$FRM_website,
										'contato' 		=>$FRM_contato,
										'logradouros_id'=>$FRM_logradouro_id,
										'compl_end'		=>$FRM_compl,
										'num' 			=>$FRM_num,
										'usuarios_id' 	=>$COB_Usuario_Id
										));

if($Query_update==true){
	echo $FRM_empresa_id;
	}else{
		echo tool::msg_erros("Erro ao atualizar dados da empresa");
		}





// cria um novo registro
}else{

$FRM_razao_social	=	isset( $_POST['rzsc'])		? $_POST['rzsc']: 					 tool::msg_erros("O Campo Obrigatorio razao social asdsda.");
$FRM_nm_fantasia	=	isset( $_POST['nmfant'])	? $_POST['nmfant']: 				 tool::msg_erros("O Campo Obrigatorio nome fantasia.");
$FRM_cnpj			=	isset( $_POST['cnpj'])		? tool::LimpaString($_POST['cnpj']): tool::msg_erros("O Campo Obrigatorio cnpj.");
$FRM_im				=	isset( $_POST['im'])		? $_POST['im']: 					 tool::msg_erros("O Campo Obrigatorio isc municipal.");
$FRM_ie				=	isset( $_POST['ie'])		? $_POST['ie']: 					 tool::msg_erros("O Campo Obrigatorio isc estadual.");
$FRM_logradouro_id	=	isset( $_POST['logradouro'])? $_POST['logradouro']: 			 tool::msg_erros("O Campo Obrigatorio  lograduro_id.");
$FRM_compl			=	isset( $_POST['compl_end'])	? $_POST['compl_end']: 				 tool::msg_erros("O Campo Obrigatorio  compl_end.");
$FRM_num			=	isset( $_POST['num'])		? $_POST['num']: 					 tool::msg_erros("O Campo Obrigatorio  numero.");
$FRM_fone_fixo		=	isset( $_POST['fn_fx'])		? tool::LimpaString($_POST['fn_fx']):tool::msg_erros("O Campo Obrigatorio  fone fixo.");
$FRM_fone_cel		=	isset( $_POST['fn_cel'])	? tool::LimpaString($_POST['fn_cel']):	tool::msg_erros("O Campo Obrigatorio  fone cel.");
$FRM_email			=	isset( $_POST['email'])		? $_POST['email']:						tool::msg_erros("O Campo Obrigatorio  email.");
$FRM_website		=	isset( $_POST['wbst'])		? $_POST['wbst']: 						tool::msg_erros("O Campo Obrigatorio  web site.");
$FRM_contato		=	isset( $_POST['ct'])		? $_POST['ct']: 						tool::msg_erros("O Campo Obrigatorio  contato.");


// VERIFICA DUPLICIDADE DE CNPJ
$query_duplicidade=empresas::find_by_cnpj($FRM_cnpj);
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

// cria a empresa
$Query_empresa 	= empresas::create(array( 'razao_social'=>$FRM_razao_social,
										  'nm_fantasia' =>$FRM_nm_fantasia,
										  'cnpj' 		=>$FRM_cnpj,
										  'im' 			=>$FRM_im,
										  'ie' 			=>$FRM_ie,
										  'fone_fixo' 	=>$FRM_fone_fixo,
										  'fone_cel' 	=>$FRM_fone_cel,
										  'email' 		=>$FRM_email,
										  'website' 	=>$FRM_website,
										  'contato' 	=>$FRM_contato,
										  'logradouros_id'=>$FRM_logradouro_id,
										  'compl_end'		=>$FRM_compl,
										  'num' 		=>$FRM_num,
										  'dt_cadastro'	=>date("Y-m-d"),
										  'usuarios_id' =>$COB_Usuario_Id
										));
$UltimaEmpresa=empresas::find("last");//recupera o ultimo id

// cria as configurações
$Query_config =configs::create(array('empresas_id'  	=>$UltimaEmpresa->id,
									'juros'		  		=>0,
									'multa' 	  		=>0,
									'desc_um'	  		=>0,
									'desc_dois'   		=>0,
									'desc_tres'   		=>0,
									'desc_quatro' 		=>0,
									'desc_cinco'  		=>0,
									'desc_seis'   		=>0,
									'desc_sete'   		=>0,
									'desc_oito'  		=>0,
									'desc_nove'   		=>0,
									'carencia'   		=>0,
									'vl_apol_seg' 		=>0,
									'vl_aux_fun'  		=>0,
									'validade_apolice'	=>'0000-00-00',
									'dt_limit_seg'		=>0,
									'centros_custos_id'	=>0,
									'planos_contas_id'	=>0
									));

// CRIA A CONTA BANCARIA cnpj_fav
$Query_contas 	= contas_bancarias::create(
										array(
											'nm_conta' 			=>"Caixa",
											'cod_banco' 		=>0,
											'agencia' 			=>0,
											'dv_agencia' 		=>0,
											'conta' 			=>0,
											'dv_conta' 			=>0,
											'status' 			=>1,
											'maq_cartao'		=>0,
											'debito_auto' 		=>0,
											'limite_credito' 	=>0,
											'pg_inicial' 		=>0,
											'prev_financeira' 	=>0,
											'sd_inicial' 		=>0,
											'tp_conta' 			=>0,
											'dt_criacao'		=>date("Y-m-d"),
											'empresas_id' 		=>$UltimaEmpresa->id,
											'usuarios_id' 		=>$COB_Usuario_Id
											));

if($Query_empresa==true){
		echo $UltimaEmpresa->id;
		}else{
			echo tool::msg_erros("Erro ao cadastrar Empresa");
			}

}
?>