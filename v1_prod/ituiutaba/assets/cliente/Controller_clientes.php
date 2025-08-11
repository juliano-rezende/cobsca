<?php 
$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../sessao.php";
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');

//classe de validação de cnpj e cpf
require_once("../../classes/valid_cpf_cnpj.php");

// faz update do registro
if(!empty($_POST['cliente_id'])){


$FRM_cliente_id		=	isset( $_POST['cliente_id'])? $_POST['cliente_id']: 					tool::msg_erros("O Campo Obrigatorio 1 Faltando.");
$FRM_tp_pessoa		=	isset( $_POST['tp_pessoa']) ? $_POST['tp_pessoa']: 						tool::msg_erros("O Campo Obrigatorio 2 Faltando.");
$FRM_nm_cliente		=	isset( $_POST['nm_cliente'])? $_POST['nm_cliente']: 					tool::msg_erros("O Campo Obrigatorio 3 Faltando.");
$FRM_razao_social	=	isset( $_POST['rzsc'])		? $_POST['rzsc']: 							tool::msg_erros("O Campo Obrigatorio 4 Faltando.");
$FRM_nm_fantasia	=	isset( $_POST['nmfant'])	? $_POST['nmfant']: 						tool::msg_erros("O Campo Obrigatorio 5 Faltando.");
$FRM_cpf			=	isset( $_POST['cpf'])		? tool::LimpaString($_POST['cpf']): 		tool::msg_erros("O Campo Obrigatorio 6 Faltando.");
$FRM_cnpj			=	isset( $_POST['cnpj'])		? tool::LimpaString($_POST['cnpj']): 		tool::msg_erros("O Campo Obrigatorio 7 Faltando.");
$FRM_rg				=	isset( $_POST['rg'])		? $_POST['rg']: 							tool::msg_erros("O Campo Obrigatorio 8 Faltando.");
$FRM_ie				=	isset( $_POST['ie'])		? $_POST['ie']: 							tool::msg_erros("O Campo Obrigatorio 9 Faltando.");
$FRM_im				=	isset( $_POST['im'])		? $_POST['im']: 							tool::msg_erros("O Campo Obrigatorio 10 Faltando.");
$FRM_logradouro_id	=	isset( $_POST['logradouro'])? $_POST['logradouro']: 					tool::msg_erros("O Campo Obrigatorio 11 Faltando.");
$FRM_compl			=	isset( $_POST['compl_end']) ? $_POST['compl_end']:						tool::msg_erros("O Campo Obrigatorio 12 Faltando.");
$FRM_num			=	isset( $_POST['num'])		? $_POST['num']: 							tool::msg_erros("O Campo Obrigatorio 13 Faltando.");
$FRM_fone_fixo		=	isset( $_POST['fone_fixo'])	? tool::LimpaString($_POST['fone_fixo']):	tool::msg_erros("O Campo Obrigatorio 14 Faltando.");
$FRM_fone_cel		=	isset( $_POST['fone_cel'])	? tool::LimpaString($_POST['fone_cel']):	tool::msg_erros("O Campo Obrigatorio 15 Faltando.");
$FRM_email			=	isset( $_POST['email'])		? $_POST['email']: 							tool::msg_erros("O Campo Obrigatorio 16 Faltando.");



// VALIDA OS TELEFONES
if(empty($FRM_fone_fixo)){
		if(empty($FRM_fone_cel)){
			tool::msg_erros("É obrigatório pelo menos um numero de telefone.");
	}
}

// VALIDAÇÃO DO CPF e CNPJ
if($FRM_cpf != ""){

	$cpf_cnpj = new ValidaCPFCNPJ($FRM_cpf);
	if ( !$cpf_cnpj->valida() ) {
		sleep(1);
		echo tool::msg_erros("O CPF invalido.");
		return false;
	}

}else{

	$cpf_cnpj = new ValidaCPFCNPJ($FRM_cnpj);
	if( !$cpf_cnpj->valida() ) {
		sleep(1);
		echo tool::msg_erros("O CPF invalido.");
		return false;
	}

}

// EXECULTA A QUERY
$Query_update=clientes_fornecedores::find($FRM_cliente_id);
$Query_update->update_attributes(array(
										'tp_pessoa' 	=>$FRM_tp_pessoa,
										'nm_cliente' 	=>strtolower($FRM_nm_cliente),
										'razao_social' 	=>strtolower($FRM_razao_social),
										'nm_fantasia' 	=>strtolower($FRM_nm_fantasia),
										'cpf' 			=>$FRM_cpf,
										'cnpj' 			=>$FRM_cnpj,
										'rg' 			=>$FRM_rg,
										'ie' 			=>$FRM_ie,
										'im' 			=>$FRM_im,
										'fone_fixo' 	=>$FRM_fone_fixo,
										'fone_cel' 		=>$FRM_fone_cel,
										'email' 		=>$FRM_email,
										'logradouros_id'=>$FRM_logradouro_id,
										'compl_end'		=>$FRM_compl,
										'num' 			=>$FRM_num,
										'usuarios_id' 	=>$COB_Usuario_Id
										));

if($Query_update==true){
	echo $FRM_cliente_id;
	}else{
		echo tool::msg_erros("Erro ao Atualizar Dados do Cliente");
		}





// cria um novo registro
}else{

$FRM_tp_pessoa		=	isset( $_POST['tp_pessoa'])? $_POST['tp_pessoa']: 						tool::msg_erros("O Campo Obrigatorio 17 Faltando.");
$FRM_nm_cliente		=	isset( $_POST['nm_cliente'])? $_POST['nm_cliente']: 					tool::msg_erros("O Campo Obrigatorio 18 Faltando.");
$FRM_razao_social	=	isset( $_POST['rzsc'])		? $_POST['rzsc']: 							tool::msg_erros("O Campo Obrigatorio 19 Faltando.");
$FRM_nm_fantasia	=	isset( $_POST['nmfant'])	? $_POST['nmfant']: 						tool::msg_erros("O Campo Obrigatorio 20 Faltando.");
$FRM_cpf			=	isset( $_POST['cpf'])		? tool::LimpaString($_POST['cpf']): 		tool::msg_erros("O Campo Obrigatorio 21 Faltando.");
$FRM_cnpj			=	isset( $_POST['cnpj'])		? tool::LimpaString($_POST['cnpj']): 		tool::msg_erros("O Campo Obrigatorio 22 Faltando.");
$FRM_rg				=	isset( $_POST['rg'])		? $_POST['rg']: 							tool::msg_erros("O Campo Obrigatorio 23 Faltando.");
$FRM_ie				=	isset( $_POST['ie'])		? $_POST['ie']: 							tool::msg_erros("O Campo Obrigatorio 23 Faltando.");
$FRM_im				=	isset( $_POST['im'])		? $_POST['im']: 							tool::msg_erros("O Campo Obrigatorio 25 Faltando.");
$FRM_logradouro_id	=	isset( $_POST['logradouro'])? $_POST['logradouro']: 					tool::msg_erros("O Campo Obrigatorio 26 Faltando.");
$FRM_compl			=	isset( $_POST['compl_end']) ? $_POST['compl_end']:						tool::msg_erros("O Campo Obrigatorio 27 Faltando.");
$FRM_num			=	isset( $_POST['num'])		? $_POST['num']: 							tool::msg_erros("O Campo Obrigatorio 28 Faltando.");
$FRM_fone_fixo		=	isset( $_POST['fone_fixo'])	? tool::LimpaString($_POST['fone_fixo']):	tool::msg_erros("O Campo Obrigatorio 29 Faltando.");
$FRM_fone_cel		=	isset( $_POST['fone_cel'])	? tool::LimpaString($_POST['fone_cel']):	tool::msg_erros("O Campo Obrigatorio 30 Faltando.");
$FRM_email			=	isset( $_POST['email'])		? $_POST['email']: 							tool::msg_erros("O Campo Obrigatorio 31 Faltando.");


// VERIFICA DUPLICIDADE DE CPF
$query_duplicidade=clientes_fornecedores::find_by_cpf($FRM_cpf);
if($query_duplicidade){echo tool::msg_erros("CPF cadastrado em nossa base de dados.");}

// VALIDA OS TELEFONES
if(empty($FRM_fone_fixo)){
		if(empty($FRM_fone_cel)){
			tool::msg_erros("É obrigatório pelo menos um numero de telefone.");
	}
}

// VALIDAÇÃO DO CPF E CNPJ
if($FRM_cpf != ""){

	$cpf_cnpj = new ValidaCPFCNPJ($FRM_cpf);
	if ( !$cpf_cnpj->valida() ) {
		sleep(1);
		echo tool::msg_erros("O CPF invalido.");
		return false;
	}

}else{

	$cpf_cnpj = new ValidaCPFCNPJ($FRM_cnpj);
	if( !$cpf_cnpj->valida() ) {
		sleep(1);
		echo tool::msg_erros("O CPF invalido.");
		return false;
	}

}

// EXECULTA A QUERY
$Query_cliente 		= clientes_fornecedores::create(
													array(
														'tipo'			=>'1',
														'tp_pessoa' 	=>$FRM_tp_pessoa,
														'nm_cliente' 	=>strtolower($FRM_nm_cliente),
														'razao_social' 	=>strtolower($FRM_razao_social),
														'nm_fantasia' 	=>strtolower($FRM_nm_fantasia),
														'cpf' 			=>$FRM_cpf,
														'cnpj' 			=>$FRM_cnpj,
														'rg' 			=>$FRM_rg,
														'ie' 			=>$FRM_ie,
														'im' 			=>$FRM_im,
														'fone_fixo' 	=>$FRM_fone_fixo,
														'fone_cel' 		=>$FRM_fone_cel,
														'email' 		=>$FRM_email,
														'logradouros_id'=>$FRM_logradouro_id,
														'compl_end'		=>$FRM_compl,
														'dt_cadastro'	=>date("Y-m-d"),
														'num' 			=>$FRM_num,
														'usuarios_id' 	=>$COB_Usuario_Id,
														'empresas_id' 	=>$COB_Empresa_Id
														));

if($Query_cliente==true){
		$Ultimocliente=clientes_fornecedores::find("last");//recupera o ultimo id
		echo $Ultimocliente->id;
		}else{
			echo tool::msg_erros("Erro ao Cadastrar Cliente");
			}
}
?>