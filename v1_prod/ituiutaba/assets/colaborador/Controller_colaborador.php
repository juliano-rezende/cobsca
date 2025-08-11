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
$FRM_nm_cliente		=	isset( $_POST['nm_cliente'])? $_POST['nm_cliente']: 					tool::msg_erros("O Campo Obrigatorio 2 Faltando.");
$FRM_cpf			=	isset( $_POST['cpf'])		? tool::LimpaString($_POST['cpf']): 		tool::msg_erros("O Campo Obrigatorio 3 Faltando.");
$FRM_rg				=	isset( $_POST['rg'])		? $_POST['rg']: 							tool::msg_erros("O Campo Obrigatorio 4 Faltando.");
$FRM_logradouro_id	=	isset( $_POST['logradouro'])? $_POST['logradouro']: 					tool::msg_erros("O Campo Obrigatorio 5 Faltando.");
$FRM_compl			=	isset( $_POST['compl_end']) ? $_POST['compl_end']:						tool::msg_erros("O Campo Obrigatorio 6 Faltando.");
$FRM_num			=	isset( $_POST['num'])		? $_POST['num']: 							tool::msg_erros("O Campo Obrigatorio 7 Faltando.");
$FRM_fone_fixo		=	isset( $_POST['fone_fixo'])	? tool::LimpaString($_POST['fone_fixo']):	tool::msg_erros("O Campo Obrigatorio 8 Faltando.");
$FRM_fone_cel		=	isset( $_POST['fone_cel'])	? tool::LimpaString($_POST['fone_cel']):	tool::msg_erros("O Campo Obrigatorio 9 Faltando.");
$FRM_email			=	isset( $_POST['email'])		? $_POST['email']: 							tool::msg_erros("O Campo Obrigatorio 10 Faltando.");



// VALIDA OS TELEFONES
if(empty($FRM_fone_fixo)){
		if(empty($FRM_fone_cel)){
			tool::msg_erros("É obrigatório pelo menos um numero de telefone.");
	}
}

// VALIDAÇÃO DO CPF
$cpf_cnpj = new ValidaCPFCNPJ($FRM_cpf);
if ( !$cpf_cnpj->valida() ) {
	sleep(1);
	echo tool::msg_erros("O CPF invalido.");
	return false;
}

// EXECULTA A QUERY
$Query_update=clientes_fornecedores::find($FRM_cliente_id);
$Query_update->update_attributes(array(
										'nm_cliente' 	=>$FRM_nm_cliente,
										'cpf' 			=>$FRM_cpf,
										'rg' 			=>$FRM_rg,
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

$FRM_nm_cliente		=	isset( $_POST['nm_cliente1'])? $_POST['nm_cliente1']: 					tool::msg_erros("O Campo Obrigatorio 11 Faltando.");
$FRM_cpf			=	isset( $_POST['cpf'])		? tool::LimpaString($_POST['cpf']): 		tool::msg_erros("O Campo Obrigatorio 12 Faltando.");
$FRM_rg				=	isset( $_POST['rg'])		? $_POST['rg']: 							tool::msg_erros("O Campo Obrigatorio 13 Faltando.");
$FRM_logradouro_id	=	isset( $_POST['logradouro'])? $_POST['logradouro']: 					tool::msg_erros("O Campo Obrigatorio 14 Faltando.");
$FRM_compl			=	isset( $_POST['compl_end']) ? $_POST['compl_end']:						tool::msg_erros("O Campo Obrigatorio 15 Faltando.");
$FRM_num			=	isset( $_POST['num'])		? $_POST['num']: 							tool::msg_erros("O Campo Obrigatorio 16 Faltando.");
$FRM_fone_fixo		=	isset( $_POST['fone_fixo'])	? tool::LimpaString($_POST['fone_fixo']):	tool::msg_erros("O Campo Obrigatorio 17 Faltando.");
$FRM_fone_cel		=	isset( $_POST['fone_cel'])	? tool::LimpaString($_POST['fone_cel']):	tool::msg_erros("O Campo Obrigatorio 18 Faltando.");
$FRM_email			=	isset( $_POST['email'])		? $_POST['email']: 							tool::msg_erros("O Campo Obrigatorio 19 Faltando.");


// VERIFICA DUPLICIDADE DE CPF
$query_duplicidade=clientes_fornecedores::find_by_cpf($FRM_cpf);
if($query_duplicidade){echo tool::msg_erros("CPF cadastrado em nossa base de dados.");}

// VALIDA OS TELEFONES
if(empty($FRM_fone_fixo)){
		if(empty($FRM_fone_cel)){
			tool::msg_erros("É obrigatório pelo menos um numero de telefone.");
	}
}

// VALIDAÇÃO DO CPF
$cpf_cnpj = new ValidaCPFCNPJ($FRM_cpf);
if ( !$cpf_cnpj->valida() ) {
	sleep(1);
	echo tool::msg_erros("O CPF invalido.");
	return false;
}

// EXECULTA A QUERY
$Query_cliente 		= clientes_fornecedores::create(
													array(
														'tipo'			=>'1',
														'nm_cliente' 	=>$FRM_nm_cliente,
														'cpf' 			=>$FRM_cpf,
														'rg' 			=>$FRM_rg,
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