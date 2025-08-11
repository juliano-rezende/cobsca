<?php
$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../sessao.php";
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');

//classe de validação de cnpj e cpf
require_once("../../classes/valid_cpf_cnpj.php");

// faz update do registro
if(!empty($_POST['par_id'])){


$FRM_parceiro_id	=	isset( $_POST['par_id'])		? $_POST['par_id']: 						tool::msg_erros("O Campo Obrigatorio id Faltando.");
$FRM_status			=	isset( $_POST['st'])			? $_POST['st']: 							tool::msg_erros("O Campo Obrigatorio status Faltando.");
$FRM_tp_parceiro	=	isset( $_POST['tp_parceiro'])	? $_POST['tp_parceiro']: 					tool::msg_erros("O Campo Obrigatorio tipo de parceiro Faltando.");

/* se o parceiro for pessoa fisica*/
$FRM_nm_parceiro	=	isset( $_POST['nm_parceiro'])	? $_POST['nm_parceiro']: 					tool::msg_erros("O Campo Obrigatorio nome do parceiro Faltando.");
$FRM_cpf			=	isset( $_POST['cpf']) 			? tool::LimpaString($_POST['cpf']): 		tool::msg_erros("O Campo CPF é Obrigatorio.");
$FRM_rg				=	isset( $_POST['rg']) 			? $_POST['rg']: 							tool::msg_erros("O Campo RG é Obrigatorio.");
$FRM_data_nasc		=	isset( $_POST['data_nasc']) 	? tool::LimpaString($_POST['data_nasc']): 	tool::msg_erros("O Campo data de nascimento é Obrigatorio.");
$FRM_classe			=	isset( $_POST['classe']) 		? $_POST['classe']: 						tool::msg_erros("O Campo data de classe medica é Obrigatorio.");
$FRM_numclasse		=	isset( $_POST['numclasse']) 	? $_POST['numclasse']: 						tool::msg_erros("O Campo data de número da classe medica é Obrigatorio.");

/* se o parceiro for pessoa juridica*/
$FRM_razao_social	=	isset( $_POST['rzsc'])		? $_POST['rzsc']: 						tool::msg_erros("O Campo Obrigatorio razão social Faltando.");
$FRM_nm_fantasia	=	isset( $_POST['nmfant'])	? $_POST['nmfant']: 					tool::msg_erros("O Campo Obrigatorio nome fantasia Faltando.");
$FRM_cnpj			=	isset( $_POST['cnpj'])		? tool::LimpaString($_POST['cnpj']):	tool::msg_erros("O Campo Obrigatorio CNPJ Faltando.");
$FRM_im				=	isset( $_POST['im'])		? $_POST['im']: 						tool::msg_erros("O Campo Obrigatorio inscrição municipal Faltando.");
$FRM_ie				=	isset( $_POST['ie'])		? $_POST['ie']: 						tool::msg_erros("O Campo Obrigatorio inscrição estadual Faltando.");

/* dados comuns */
$FRM_logradouro_id	=	isset( $_POST['logradouro'])? $_POST['logradouro']: 				tool::msg_erros("O Campo Obrigatorio logradouro Faltando.");
$FRM_compl			=	isset( $_POST['compl_end']) ? $_POST['compl_end']: 					tool::msg_erros("O Campo Obrigatorio complemento do logradouro Faltando.");
$FRM_num			=	isset( $_POST['num'])		? $_POST['num']: 						tool::msg_erros("O Campo Obrigatorio numero do estabelescimento Faltando.");
$FRM_fone_fixo		=	isset( $_POST['fn_fx'])		? tool::LimpaString($_POST['fn_fx']):	tool::msg_erros("O Campo Obrigatorio telefone fixo Faltando.");
$FRM_fone_cel		=	isset( $_POST['fn_cel'])	? tool::LimpaString($_POST['fn_cel']): 	tool::msg_erros("O Campo Obrigatorio telefone celular Faltando.");
$FRM_email			=	isset( $_POST['email'])		? $_POST['email']: 						tool::msg_erros("O Campo Obrigatorio e-mail Faltando.");
$FRM_website		=	isset( $_POST['wbst'])		? $_POST['wbst']: 						tool::msg_erros("O Campo Obrigatorio web site Faltando.");
$FRM_contato		=	isset( $_POST['ct'])		? $_POST['ct']: 						tool::msg_erros("O Campo Obrigatorio contato Faltando.");
$FRM_prazo_pgto		=	isset( $_POST['prz_pgto'])	? $_POST['prz_pgto']: 					tool::msg_erros("O Campo Obrigatorio prazo de pagamento Faltando.");
$FRM_local_pgto		=	isset( $_POST['local_pgto'])? $_POST['local_pgto']: 				tool::msg_erros("O Campo Obrigatorio local de pagamento Faltando.");
$FRM_dia_venc		=	isset( $_POST['dia_venc'])	? $_POST['dia_venc']: 					tool::msg_erros("O Campo Obrigatorio dia_venc Faltando.");




if($FRM_tp_parceiro == "F"){

	$query_duplicidade=med_parceiros::find_by_cpf($FRM_cpf);

	if($query_duplicidade->id != $FRM_parceiro_id){echo tool::msg_erros("CNPJ atribuido a outro parceiro favor verificar os dados digitados.");}
	
	// VALIDAÇÃO DO CPF E CNPJ
	$cpf_cnpj = new ValidaCPFCNPJ($FRM_cpf);
	if ( !$cpf_cnpj->valida() ) {
		sleep(1);
		echo tool::msg_erros("O CPF/CNPJ invalido.");
		return false;
	}

}else{

	$query_duplicidade=med_parceiros::find_by_cnpj($FRM_cnpj);

	if($query_duplicidade->id != $FRM_parceiro_id){echo tool::msg_erros("CNPJ atribuido a outro parceiro favor verificar os dados digitados.");}
	
	// VALIDAÇÃO DO CPF E CNPJ
	$cpf_cnpj = new ValidaCPFCNPJ($FRM_cnpj);
	if ( !$cpf_cnpj->valida() ) {
		sleep(1);
		echo tool::msg_erros("O CPF/CNPJ invalido.");
		return false;
	}

}







// VALIDA OS TELEFONES
if(empty($FRM_fone_fixo)){
		if(empty($FRM_fone_cel)){
			tool::msg_erros("É obrigatório pelo menos um numero de telefone.");
	}
}

// EXECULTA A QUERY
$Query_update=med_parceiros::find($FRM_parceiro_id);
$Query_update->update_attributes(array(
										'status' 		=>$FRM_status,
										'tp_parceiro' 	=>$FRM_tp_parceiro,
										'nm_parceiro' 	=>strtolower($FRM_nm_parceiro),
										'dt_nascimento'	=> tool::InvertDateTime($FRM_data_nasc,0),
										'classe' 		=>$FRM_classe,
										'numclasse' 	=>$FRM_numclasse,
										'cpf' 			=>$FRM_cpf,
										'rg' 			=>$FRM_rg,
										'nm_fantasia' 	=>strtolower($FRM_nm_fantasia),
										'razao_social' 	=>strtolower($FRM_razao_social),								
										'cnpj' 			=>$FRM_cnpj,
										'im' 			=>$FRM_im,
										'ie' 			=>$FRM_ie,
										'login' 		=>"",
										'senha' 		=>"",
										'obs' 			=>"",
										'contato' 		=>$FRM_contato,
										'fone1' 		=>$FRM_fone_fixo,
										'fone2' 		=>$FRM_fone_cel,
										'email' 		=>$FRM_email,
										'prz_pgto'		=>$FRM_prazo_pgto,
										'local_pgto'	=>$FRM_local_pgto,
										'dia_venc'		=>$FRM_dia_venc,
										'dt_cadastro'	=>date("Y-m-d"),
										'logradouros_id'=>$FRM_logradouro_id,
										'compl_end'		=>$FRM_compl,
										'num' 			=>$FRM_num,
										'empresas_id' 	=>$COB_Empresa_Id,
										'usuarios_id' 	=>$COB_Usuario_Id
										));

if($Query_update==true){
	echo $FRM_parceiro_id;
	}else{
		echo tool::msg_erros("Erro ao atualizar dados do fornecedor");
		}


// cria um novo registro
}else{

$FRM_status			=	isset( $_POST['st'])			? $_POST['st']: 							tool::msg_erros("O Campo Obrigatorio status Faltando.");
$FRM_tp_parceiro	=	isset( $_POST['tp_parceiro'])	? $_POST['tp_parceiro']: 					tool::msg_erros("O Campo Obrigatorio tipo de parceiro Faltando.");

/* se o parceiro for pessoa fisica*/
$FRM_nm_parceiro	=	isset( $_POST['nm_parceiro'])	? $_POST['nm_parceiro']: 					tool::msg_erros("O Campo Obrigatorio nome do parceiro Faltando.");
$FRM_cpf			=	isset( $_POST['cpf']) 			? tool::LimpaString($_POST['cpf']): 		tool::msg_erros("O Campo CPF é Obrigatorio.");
$FRM_rg				=	isset( $_POST['rg']) 			? $_POST['rg']: 							tool::msg_erros("O Campo RG é Obrigatorio.");
$FRM_data_nasc		=	isset( $_POST['data_nasc']) 	? tool::LimpaString($_POST['data_nasc']): 	tool::msg_erros("O Campo data de nascimento é Obrigatorio.");
$FRM_classe			=	isset( $_POST['classe']) 		? $_POST['classe']: 						tool::msg_erros("O Campo data de classe medica é Obrigatorio.");
$FRM_numclasse		=	isset( $_POST['numclasse']) 	? $_POST['numclasse']: 						tool::msg_erros("O Campo data de número da classe medica é Obrigatorio.");

/* se o parceiro for pessoa juridica*/
$FRM_razao_social	=	isset( $_POST['rzsc'])		? $_POST['rzsc']: 						tool::msg_erros("O Campo Obrigatorio razão social Faltando.");
$FRM_nm_fantasia	=	isset( $_POST['nmfant'])	? $_POST['nmfant']: 					tool::msg_erros("O Campo Obrigatorio nome fantasia Faltando.");
$FRM_cnpj			=	isset( $_POST['cnpj'])		? tool::LimpaString($_POST['cnpj']):	tool::msg_erros("O Campo Obrigatorio CNPJ Faltando.");
$FRM_im				=	isset( $_POST['im'])		? $_POST['im']: 						tool::msg_erros("O Campo Obrigatorio inscrição municipal Faltando.");
$FRM_ie				=	isset( $_POST['ie'])		? $_POST['ie']: 						tool::msg_erros("O Campo Obrigatorio inscrição estadual Faltando.");

/* dados comuns */
$FRM_logradouro_id	=	isset( $_POST['logradouro'])? $_POST['logradouro']: 				tool::msg_erros("O Campo Obrigatorio logradouro Faltando.");
$FRM_compl			=	isset( $_POST['compl_end']) ? $_POST['compl_end']: 					tool::msg_erros("O Campo Obrigatorio complemento do logradouro Faltando.");
$FRM_num			=	isset( $_POST['num'])		? $_POST['num']: 						tool::msg_erros("O Campo Obrigatorio numero do estabelescimento Faltando.");
$FRM_fone_fixo		=	isset( $_POST['fn_fx'])		? tool::LimpaString($_POST['fn_fx']):	tool::msg_erros("O Campo Obrigatorio telefone fixo Faltando.");
$FRM_fone_cel		=	isset( $_POST['fn_cel'])	? tool::LimpaString($_POST['fn_cel']): 	tool::msg_erros("O Campo Obrigatorio telefone celular Faltando.");
$FRM_email			=	isset( $_POST['email'])		? $_POST['email']: 						tool::msg_erros("O Campo Obrigatorio e-mail Faltando.");
$FRM_website		=	isset( $_POST['wbst'])		? $_POST['wbst']: 						tool::msg_erros("O Campo Obrigatorio web site Faltando.");
$FRM_contato		=	isset( $_POST['ct'])		? $_POST['ct']: 						tool::msg_erros("O Campo Obrigatorio contato Faltando.");
$FRM_prazo_pgto		=	isset( $_POST['prz_pgto'])	? $_POST['prz_pgto']: 					tool::msg_erros("O Campo Obrigatorio prazo de pagamento Faltando.");
$FRM_local_pgto		=	isset( $_POST['local_pgto'])? $_POST['local_pgto']: 				tool::msg_erros("O Campo Obrigatorio local de pagamento Faltando.");
$FRM_dia_venc		=	isset( $_POST['dia_venc'])	? $_POST['dia_venc']: 					tool::msg_erros("O Campo Obrigatorio dia_venc Faltando.");





// VERIFICA DUPLICIDADE DECPF E CNPJ
if($FRM_tp_parceiro == "F"){

	$query_duplicidade=med_parceiros::find_by_cpf($FRM_cpf);

	if($query_duplicidade){echo tool::msg_erros("CPF atribuido a outro parceiro favor verificar os dados digitados.");}
	$cpf_cnpj = new ValidaCPFCNPJ($FRM_cpf);
	if ( !$cpf_cnpj->valida() ) {
		sleep(1);
		echo tool::msg_erros("O CPF invalido.");
		return false;
	}


}else{

	$query_duplicidade=med_parceiros::find_by_cnpj($FRM_cnpj);

	if($query_duplicidade){echo tool::msg_erros("CNPJ atribuido a outro parceiro favor verificar os dados digitados.");}
	$cpf_cnpj = new ValidaCPFCNPJ($FRM_cnpj);
	if( !$cpf_cnpj->valida() ) {
		sleep(1);
		echo tool::msg_erros("O CPF invalido.");
		return false;
	}

}


// VALIDA OS TELEFONES
if(empty($FRM_fone_fixo)){
		if(empty($FRM_fone_cel)){
			tool::msg_erros("É obrigatório pelo menos um numero de telefone.");
	}
}



// EXECULTA A QUERY
$Query_parceiro 	= med_parceiros::create(
												array(
													'status' 		=>$FRM_status,
													'tp_parceiro' 	=>$FRM_tp_parceiro,
													'nm_parceiro' 	=>strtolower($FRM_nm_parceiro),
													'dt_nascimento'	=> tool::InvertDateTime($FRM_data_nasc,0),
													'classe' 		=>$FRM_classe,
													'numclasse' 	=>$FRM_numclasse,
													'cpf' 			=>$FRM_cpf,
													'rg' 			=>$FRM_rg,
													'nm_fantasia' 	=>strtolower($FRM_nm_fantasia),
													'razao_social' 	=>strtolower($FRM_razao_social),								
													'cnpj' 			=>$FRM_cnpj,
													'im' 			=>$FRM_im,
													'ie' 			=>$FRM_ie,
													'login' 		=>"",
													'senha' 		=>"",
													'obs' 			=>"",
													'contato' 		=>$FRM_contato,
													'fone1' 		=>$FRM_fone_fixo,
													'fone2' 		=>$FRM_fone_cel,
													'email' 		=>$FRM_email,
													'prz_pgto'		=>$FRM_prazo_pgto,
													'local_pgto'	=>$FRM_local_pgto,
													'dia_venc'		=>$FRM_dia_venc,
													'dt_cadastro'	=>date("Y-m-d"),
													'logradouros_id'=>$FRM_logradouro_id,
													'compl_end'		=>$FRM_compl,
													'num' 			=>$FRM_num,
													'empresas_id' 	=>$COB_Empresa_Id,
													'usuarios_id' 	=>$COB_Usuario_Id
												));

if($Query_parceiro==true){
		$Ultimoparceiro=med_parceiros::find("last");//recupera o ultimo id
		echo $Ultimoparceiro->id;
		}else{
			echo tool::msg_erros("Erro ao cadastrar parceiro");
			}
}
?>