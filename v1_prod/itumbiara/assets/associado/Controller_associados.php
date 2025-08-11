<?php
$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../sessao.php";
require_once("../../conexao.php");
require_once("../../config_ini.php");
$cfg->set_model_directory('../../models/');

//classe de validação de cnpj e cpf
require_once("../../classes/valid_cpf_cnpj.php");

if(empty($_POST['matricula'])){

$FRM_nm_captacao		=	isset( $_POST['lead']) 	? $_POST['lead'] : null;
$FRM_data_captacao	=	isset( $_POST['data_cap']) 	? $_POST['data_cap']		: null;

$FRM_nm_associado		=	isset( $_POST['nome']) 					? strtoupper($_POST['nome'])					: tool::msg_erros("O Campo nome é Obrigatorio.");
$FRM_fone_fixo			=	isset( $_POST['fone_fixo']) 			? tool::LimpaString($_POST['fone_fixo'])		: null;
$FRM_fone_trab			=	isset( $_POST['fone_trab']) 			? tool::LimpaString($_POST['fone_trab']	)		: null;
$FRM_fone_cel			=	isset( $_POST['fone_cel']) 				? tool::LimpaString($_POST['fone_cel'])			: null;
$FRM_data_nasc			=	isset( $_POST['data_nasc']) 			? tool::LimpaString($_POST['data_nasc'])		: null;
$FRM_cpf				=	isset( $_POST['cpf']) 					? tool::LimpaString($_POST['cpf'])				: null;
$FRM_rg					=	isset( $_POST['rg']) 					? $_POST['rg']									: null;
$FRM_orgao_emissor_rg	=	isset( $_POST['orgao_emissor_rg']) 		? $_POST['orgao_emissor_rg']					: null;
$FRM_data_emissao_rg	=	isset( $_POST['data_emissao_rg']) 		? tool::LimpaString($_POST['data_emissao_rg'])	: null;
$FRM_sexo				=	isset( $_POST['sexo']) 					? $_POST['sexo']								: null;
$FRM_casa_propria		=	isset( $_POST['casa_propria']) 			? $_POST['casa_propria']						: null;
$FRM_estado_civil		=	isset( $_POST['estado_civil']) 			? $_POST['estado_civil']						: null;
$FRM_vendedor			=	isset( $_POST['vendedor']) 				? $_POST['vendedor']							: tool::msg_erros("O Campo vendedor é Obrigatorio.");
$FRM_convenio			=	isset( $_POST['convenio']) 				? $_POST['convenio']							: tool::msg_erros("O Campo convenio é Obrigatorio.");
$FRM_email				=	isset( $_POST['email']) 				? $_POST['email']								: tool::msg_erros("O Campo email é Obrigatorio.");
$FRM_logradouro			=	isset( $_POST['logradouro']) 			? $_POST['logradouro']							: null;
$FRM_compl				=	isset( $_POST['compl_end']) 			? $_POST['compl_end']							: null;
$FRM_num				=	isset( $_POST['num']) 					? $_POST['num']									: null;
//$FRM_obs				=	isset( $_POST['obs']) 					? $_POST['obs']									: tool::msg_erros("O Campo observação é Obrigatorio.");
$FRM_data_cadastro		=	date("Y-m-d");

/* VERIFICA DUPLICIDADE DE CPF */
$query_duplicidade_assoc=associados::find_by_sql("SELECT matricula FROM associados WHERE cpf='".$FRM_cpf."' AND nm_associado='".$FRM_nm_associado."'");

if(count($query_duplicidade_assoc) > 0){
    
    echo tool::msg_erros("Este CPF ja existe em nossa base de associados registrado na matricula <b>".$query_duplicidade_assoc[0]->matricula." </b>");
    return false;
    
}
/*else {
    
    $query_duplicidade_dep = dependentes ::find_by_sql("SELECT id FROM dependentes WHERE (cpf='" . $FRM_cpf . "' OR nome='" . $FRM_nm_associado . "')");
    
    if (count($query_duplicidade_dep) > 0) {
        echo tool ::msg_erros("Este CPF ja existe em nossa base de dependentes registrado na matricula <b>".$query_duplicidade_assoc[0]->matricula." </b>");
        return false;
    }
    
}*/

// VALIDA OS TELEFONES
if(empty($FRM_fone_fixo)){
    
	if(empty($FRM_fone_trab)){
	    
		if(empty($FRM_fone_cel)){
			tool::msg_erros("É obrigatório pelo menos um numero de telefone.");
		}
	}
}



// //VALIDA A DATA DE NASC
// $datanasc=tool::InvertDateTime($FRM_data_nasc,0);

// if ($FRM_data_nasc != null && !checkdate( substr($FRM_data_nasc,2,2) , substr($FRM_data_nasc,0,2) , substr($FRM_data_nasc,4,4) ) 				// se a data for inválida
//      || substr($FRM_data_nasc,4,4) < 1900                                    			   								// ou o ano menor que 1900
//      || mktime( 0, 0, 0, substr($FRM_data_nasc,2,2), substr($FRM_data_nasc,0,2), substr($FRM_data_nasc,4,4)) > time() )	// ou a data passar de hoje
// {
// 	echo tool::msg_erros("Data de nascimento invalida.{}");

// }


// // VALIDAÇÃO DO CPF
// $cpf_cnpj = new ValidaCPFCNPJ($FRM_cpf);
// if ($FRM_cpf != null & !$cpf_cnpj->valida() ) {
// 	sleep(1);
// 	echo tool::msg_erros("O CPF invalido.");
// 	return false;
// }

// //VALIDA O VENDEDOR
// if($FRM_vendedor=="" or $FRM_vendedor=="0"){echo tool::msg_erros("é necessario selecionar um vendedor.");}
// //VALIDA O CONVENIO
// if($FRM_convenio==""  or $FRM_convenio=="0"){echo tool::msg_erros("é necessario selecionar um convênio.");}

    
// //VALIDA O ENDEREÇO
// if($FRM_logradouro != null && $FRM_logradouro=="" or $FRM_logradouro=="0" ){echo tool::msg_erros("é necessario selecionar um logradouro.");}




$Query_create= associados::create(
							array(
									'lead'		        =>$FRM_nm_captacao,
									'data_contato_lead'	=>$FRM_data_captacao,
									'nm_associado'		=>$FRM_nm_associado,
									'fone_fixo'			=>$FRM_fone_fixo,
									'fone_trabalho'		=>$FRM_fone_trab,
									'fone_cel'			=>$FRM_fone_cel,
									'cpf'				=>$FRM_cpf,
									'rg'				=>$FRM_rg,
									'dt_nascimento'		=>tool::InvertDateTime($FRM_data_nasc,0),
									'orgao_emissor_rg'	=>$FRM_orgao_emissor_rg,
									'data_emissao_rg'	=>tool::InvertDateTime($FRM_data_emissao_rg,0),
									'sexo'				=>$FRM_sexo,
									'casa_propria'		=>$FRM_casa_propria,
									'estado_civil'		=>$FRM_estado_civil,
									'email'				=>$FRM_email,
									'num'				=>$FRM_num,
									//'obs'				=> $FRM_obs,
									'dt_cadastro'		=>$FRM_data_cadastro,
									'usuarios_id'		=>$COB_Usuario_Id,
									'vendedores_id'		=>$FRM_vendedor,
									'empresas_id'		=>$COB_Empresa_Id,
									'convenios_id'		=>$FRM_convenio,
									'logradouros_id'	=>$FRM_logradouro,
									'compl_end'			=>$FRM_compl
									));


if($Query_create==true){
	$last=associados::find("last");//recupera o ultimo id
	echo $last->id;
	}else{
		echo tool::msg_erros("Erro ao gravar novo associado");
		}


// editar O ASSOCIADO
}else{

$FRM_nm_captacao		=	isset( $_POST['lead']) 	? $_POST['lead'] : null;
$FRM_data_captacao	=	isset( $_POST['data_cap']) 	? $_POST['data_cap']: null;

$FRM_matricula			=	isset( $_POST['matricula']) 		? $_POST['matricula']							: tool::msg_erros("O Campo matricula é Obrigatorio.");
$FRM_nm_associado		=	isset( $_POST['nome']) 				? strtoupper($_POST['nome'])					: tool::msg_erros("O Campo matricula é Obrigatorio.");

$FRM_fone_fixo			=	isset( $_POST['fone_fixo']) 			? tool::LimpaString($_POST['fone_fixo'])		: null;
$FRM_fone_trab			=	isset( $_POST['fone_trab']) 			? tool::LimpaString($_POST['fone_trab']	)		: null;
$FRM_fone_cel			=	isset( $_POST['fone_cel']) 				? tool::LimpaString($_POST['fone_cel'])			: null;
$FRM_data_nasc			=	isset( $_POST['data_nasc']) 			? tool::LimpaString($_POST['data_nasc'])		: null;
$FRM_cpf				=	isset( $_POST['cpf']) 					? tool::LimpaString($_POST['cpf'])				: null;
$FRM_rg					=	isset( $_POST['rg']) 					? $_POST['rg']									: null;
$FRM_orgao_emissor_rg	=	isset( $_POST['orgao_emissor_rg']) 		? $_POST['orgao_emissor_rg']					: null;
$FRM_data_emissao_rg	=	isset( $_POST['data_emissao_rg']) 		? tool::LimpaString($_POST['data_emissao_rg'])	: null;
$FRM_sexo				=	isset( $_POST['sexo']) 					? $_POST['sexo']								: null;
$FRM_casa_propria		=	isset( $_POST['casa_propria']) 			? $_POST['casa_propria']						: null;
$FRM_estado_civil		=	isset( $_POST['estado_civil']) 			? $_POST['estado_civil']						: null;
$FRM_vendedor			=	isset( $_POST['vendedor']) 				? $_POST['vendedor']							: tool::msg_erros("O Campo vendedor é Obrigatorio.");
$FRM_convenio			=	isset( $_POST['convenio']) 				? $_POST['convenio']							: tool::msg_erros("O Campo convenio é Obrigatorio.");
$FRM_email				=	isset( $_POST['email']) 				? $_POST['email']								: tool::msg_erros("O Campo email é Obrigatorio.");
$FRM_logradouro			=	isset( $_POST['logradouro']) 			? $_POST['logradouro']							: null;
$FRM_compl				=	isset( $_POST['compl_end']) 			? $_POST['compl_end']							: null;
$FRM_num				=	isset( $_POST['num']) 					? $_POST['num']									: null;
//$FRM_obs				=	isset( $_POST['obs']) 					? $_POST['obs']									: tool::msg_erros("O Campo observação é Obrigatorio.");


// VALIDA OS TELEFONES
if(empty($FRM_fone_fixo)){
	if(empty($FRM_fone_trab)){
		if(empty($FRM_fone_cel)){
			tool::msg_erros("É obrigatório pelo menos um numero de telefone.");
		}
	}
}


// if($FRM_data_nasc != null){
// //VALIDA A DATA DE NASC
// $datanasc=tool::InvertDateTime($FRM_data_nasc,0);

// if ($FRM_data_nasc != null && !checkdate( substr($FRM_data_nasc,2,2) , substr($FRM_data_nasc,0,2) , substr($FRM_data_nasc,4,4) ) 				// se a data for inválida
//      || substr($FRM_data_nasc,4,4) < 1900                                    			   								// ou o ano menor que 1900
//      || mktime( 0, 0, 0, substr($FRM_data_nasc,2,2), substr($FRM_data_nasc,0,2), substr($FRM_data_nasc,4,4)) > time() )	// ou a data passar de hoje
// {
// 	echo tool::msg_erros("Data de nascimento invalida.");

// }

// }

// if($FRM_cpf != null){
// // VALIDAÇÃO DO CPF
// $cpf_cnpj = new ValidaCPFCNPJ($FRM_cpf);
// if ($FRM_cpf != null & !$cpf_cnpj->valida() ) {
// 	sleep(1);
// 	echo tool::msg_erros("O CPF invalido.");
// 	return false;
// }
// }


// //VALIDA O VENDEDOR
// if($FRM_vendedor==""  or $FRM_vendedor=="0"){echo tool::msg_erros("é necessario selecionar um vendedor.");}
// //VALIDA O CONVENIO
// if($FRM_convenio==""  or $FRM_convenio=="0"){echo tool::msg_erros("é necessario selecionar um convênio.");}

    
// //VALIDA O ENDEREÇO
// if($FRM_logradouro != null && $FRM_logradouro=="" or $FRM_logradouro=="0" ){echo tool::msg_erros("é necessario selecionar um logradouro.");}




$query_assoc=associados::find($FRM_matricula);
$query_assoc->update_attributes(
                           array(
									'lead'		        =>$FRM_nm_captacao,
									'data_contato_lead'	=>$FRM_data_captacao,
									'nm_associado'		=>$FRM_nm_associado,
									'fone_fixo'			=>$FRM_fone_fixo,
									'fone_trabalho'		=>$FRM_fone_trab,
									'fone_cel'			=>$FRM_fone_cel,
									'cpf'				=>$FRM_cpf,
									'rg'				=>$FRM_rg,
									'dt_nascimento'		=>tool::InvertDateTime($FRM_data_nasc,0),
									'orgao_emissor_rg'	=>$FRM_orgao_emissor_rg,
									'data_emissao_rg'	=>tool::InvertDateTime($FRM_data_emissao_rg,0),
									'sexo'				=>$FRM_sexo,
									'casa_propria'		=>$FRM_casa_propria,
									'estado_civil'		=>$FRM_estado_civil,
									'email'				=>$FRM_email,
									'num'				=>$FRM_num,
									//'obs'				=> $FRM_obs,
									'usuarios_id'		=>$COB_Usuario_Id,
									'vendedores_id'		=>$FRM_vendedor,
									'empresas_id'		=>$COB_Empresa_Id,
									'convenios_id'		=>$FRM_convenio,
									'logradouros_id'	=>$FRM_logradouro,
									'compl_end'			=>$FRM_compl
						   			));

// VERIFICAMOS SE HOUVE MUDANÇA DE CONVENIO SE SIM ATUALIZAMOS O CONVENIO NO FATURAMENTO
if($FRM_convenio != $FRM_cv_confirm){
	$queryfatu=faturamentos::find_by_sql("SELECT * FROM faturamentos WHERE matricula='".$FRM_matricula."' and status='0' and convenios_id='".$FRM_cv_confirm."'");
	$listfat= new ArrayIterator($queryfatu);
	while($listfat->valid()):
		$update_convenio=faturamentos::find($listfat->current()->id);
		$update_convenio->convenios_id =$FRM_convenio;
		$update_convenio->save();
	$listfat->next();
	endwhile;
}


if($query_assoc==true ){echo $query_assoc->matricula;}else{echo tool::msg_erros("Erro ao atualizar cadastro.");}
}
?>