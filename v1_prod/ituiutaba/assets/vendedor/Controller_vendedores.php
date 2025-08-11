<?php
$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../sessao.php";
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');

//classe de validação de cnpj e cpf
require_once("../../classes/valid_cpf_cnpj.php");

if(empty($_POST['vendor_id'])){

	$FRM_nm_vendedor		=	isset( $_POST['nome']) 					? $_POST['nome']								: tool::msg_erros("O Campo nome é Obrigatorio.");
	$FRM_fone_fixo			=	isset( $_POST['fone_fixo']) 			? tool::LimpaString($_POST['fone_fixo'])		: tool::msg_erros("O Campo telefone residencia é Obrigatorio.");
	$FRM_fone_cel			=	isset( $_POST['fone_cel']) 				? tool::LimpaString($_POST['fone_cel'])			: tool::msg_erros("O Campo telefone celular é Obrigatorio.");
	$FRM_cpf				=	isset( $_POST['cpf']) 					? tool::LimpaString($_POST['cpf'])				: tool::msg_erros("O Campo CPF é Obrigatorio.");
	$FRM_rg					=	isset( $_POST['rg']) 					? $_POST['rg']									: tool::msg_erros("O Campo RG é Obrigatorio.");
	$FRM_orgao_emissor_rg	=	isset( $_POST['orgao_emissor_rg']) 		? $_POST['orgao_emissor_rg']					: tool::msg_erros("O Campo orgão emissão RG é Obrigatorio.");
	$FRM_sexo				=	isset( $_POST['sexo']) 					? $_POST['sexo']								: tool::msg_erros("O Campo sexo é Obrigatorio.");
	$FRM_estado_civil		=	isset( $_POST['estado_civil']) 			? $_POST['estado_civil']						: tool::msg_erros("O Campo estado civil é Obrigatorio.");
	$FRM_email				=	isset( $_POST['email']) 				? $_POST['email']								: tool::msg_erros("O Campo email é Obrigatorio.");
	$FRM_logradouro			=	isset( $_POST['logradouro']) 			? $_POST['logradouro']							: tool::msg_erros("O Campo logradouro é Obrigatorio.");
	$FRM_compl				=	isset( $_POST['compl_end']) 			? $_POST['compl_end']							: tool::msg_erros("O Campo complemento é Obrigatorio.");
	$FRM_num				=	isset( $_POST['num']) 					? $_POST['num']									: tool::msg_erros("O Campo numero é Obrigatorio.");
	$FRM_data_cadastro		=	date("Y-m-d");


	// VERIFICA DUPLICIDADE DE CPF
	$query_duplicidade=vendedores::find_by_cpf($FRM_cpf);
	if($query_duplicidade){echo tool::msg_erros("CPF cadastrado em nossa base de dados.");}

	// VALIDA OS TELEFONES
	if(empty($FRM_fone_fixo)){
		if(empty($FRM_fone_trab)){
			if(empty($FRM_fone_cel)){
				tool::msg_erros("É obrigatório pelo menos um numero de telefone.");
			}
		}
	}

	// VALIDAÇÃO DO CPF
	$cpf_cnpj = new ValidaCPFCNPJ($FRM_cpf);
	if ( !$cpf_cnpj->valida() ) {
		sleep(1);
		echo tool::msg_erros("O CPF invalido.");
		return false;
	}

	//VALIDA O ENDEREÇO
	if($FRM_logradouro==""  or $FRM_logradouro=="0" ){echo tool::msg_erros("è necessario selecionar um logradouro.");}

	$Query_create= vendedores::create(
								array(
										'nm_vendedor'		=> $FRM_nm_vendedor,
										'fone_fixo'			=> $FRM_fone_fixo,
										'fone_cel'			=> $FRM_fone_cel,
										'cpf'				=> $FRM_cpf,
										'rg'				=> $FRM_rg,
										'orgao_emissor_rg'	=> $FRM_orgao_emissor_rg,
										'sexo'				=> $FRM_sexo,
										'estado_civil'		=> $FRM_estado_civil,
										'email'				=> $FRM_email,
										'num'				=> $FRM_num,
										'dt_cadastro'		=> $FRM_data_cadastro,
										'usuarios_id'		=> $COB_Usuario_Id,
										'empresas_id'		=> $COB_Empresa_Id,
										'logradouros_id'	=> $FRM_logradouro,
										'compl_end'			=>$FRM_compl
										));

	if($Query_create==true){
		$last=vendedores::find("last");//recupera o ultimo id
		echo $last->id;
		}else{
			echo tool::msg_erros("Erro ao gravar novo associado");
			}


// editar o vendedor
}else{

	$FRM_vendedor_id		=	isset( $_POST['vendor_id']) 			? $_POST['vendor_id']							: tool::msg_erros("O Campo vendor_id é Obrigatorio.");
	$FRM_nm_vendedor		=	isset( $_POST['nome']) 					? $_POST['nome']								: tool::msg_erros("O Campo nome é Obrigatorio.");
	$FRM_fone_fixo			=	isset( $_POST['fone_fixo']) 			? tool::LimpaString($_POST['fone_fixo'])		: tool::msg_erros("O Campo telefone residencia é Obrigatorio.");
	$FRM_fone_cel			=	isset( $_POST['fone_cel']) 				? tool::LimpaString($_POST['fone_cel'])			: tool::msg_erros("O Campo telefone celular é Obrigatorio.");
	$FRM_cpf				=	isset( $_POST['cpf']) 					? tool::LimpaString($_POST['cpf'])				: tool::msg_erros("O Campo CPF é Obrigatorio.");
	$FRM_rg					=	isset( $_POST['rg']) 					? $_POST['rg']									: tool::msg_erros("O Campo RG é Obrigatorio.");
	$FRM_orgao_emissor_rg	=	isset( $_POST['orgao_emissor_rg']) 		? $_POST['orgao_emissor_rg']					: tool::msg_erros("O Campo orgão emissão RG é Obrigatorio.");
	$FRM_sexo				=	isset( $_POST['sexo']) 					? $_POST['sexo']								: tool::msg_erros("O Campo sexo é Obrigatorio.");
	$FRM_estado_civil		=	isset( $_POST['estado_civil']) 			? $_POST['estado_civil']						: tool::msg_erros("O Campo estado civil é Obrigatorio.");
	$FRM_email				=	isset( $_POST['email']) 				? $_POST['email']								: tool::msg_erros("O Campo email é Obrigatorio.");
	$FRM_logradouro			=	isset( $_POST['logradouro']) 			? $_POST['logradouro']							: tool::msg_erros("O Campo logradouro é Obrigatorio.");
	$FRM_compl				=	isset( $_POST['compl_end']) 			? $_POST['compl_end']							: tool::msg_erros("O Campo complemento é Obrigatorio.");
	$FRM_num				=	isset( $_POST['num']) 					? $_POST['num']									: tool::msg_erros("O Campo numero é Obrigatorio.");
	$FRM_data_cadastro		=	date("Y-m-d");


	// VALIDA OS TELEFONES
	if(empty($FRM_fone_fixo)){
		if(empty($FRM_fone_trab)){
			if(empty($FRM_fone_cel)){
				tool::msg_erros("É obrigatório pelo menos um numero de telefone.");
			}
		}
	}

	// VALIDAÇÃO DO CPF
	$cpf_cnpj = new ValidaCPFCNPJ($FRM_cpf);
	if ( !$cpf_cnpj->valida() ) {
		sleep(1);
		echo tool::msg_erros("O CPF invalido.");
		return false;
	}

	//VALIDA O ENDEREÇO
	if($FRM_logradouro=="" or $FRM_logradouro=="0" ){echo tool::msg_erros("è necessario selecionar um logradouro.");}

	$query=vendedores::find($FRM_vendedor_id);
	$query->update_attributes(
	                           array(
										'nm_vendedor'		=>$FRM_nm_vendedor,
										'fone_fixo'			=>$FRM_fone_fixo,
										'fone_cel'			=>$FRM_fone_cel,
										'cpf'				=>$FRM_cpf,
										'rg'				=>$FRM_rg,
										'orgao_emissor_rg'	=>$FRM_orgao_emissor_rg,
										'sexo'				=>$FRM_sexo,
										'estado_civil'		=>$FRM_estado_civil,
										'email'				=>$FRM_email,
										'logradouros_id'	=>$FRM_logradouro,
										'compl_end'			=>$FRM_compl,
										'num'				=>$FRM_num,
										'dt_cadastro'		=>$FRM_data_cadastro,
										'usuarios_id'		=>$COB_Usuario_Id,
										'empresas_id'		=>$COB_Empresa_Id
							   ));

	if($query){
				echo $query->matricula;
			}else{
				echo tool::msg_erros("Erro ao atualizar cadastro.");
			}
}
?>