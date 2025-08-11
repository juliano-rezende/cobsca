<?php
$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../sessao.php";
require_once("../../conexao.php");
require_once("../../config_ini.php");
$cfg->set_model_directory('../../models/');

//classe de validação de cnpj e cpf
require_once("../../classes/valid_cpf_cnpj.php");

if(empty($_POST['matricula'])){

$FRM_nm_associado		=	isset( $_POST['nome']) 					? strtoupper($_POST['nome'])					: tool::msg_erros("O Campo nome é Obrigatorio.");
$FRM_limite				= 	isset( $_POST['limite'])				? tool::limpamoney($_POST['limite'])			: tool::msg_erros("O Campo limite financeiro é Obrigatorio.");
$FRM_agregado			=	isset( $_POST['agregado']) 				? strtoupper($_POST['agregado'])				: tool::msg_erros("O Campo tipo de associado é Obrigatorio.");
$FRM_fone_fixo			=	isset( $_POST['fone_fixo']) 			? tool::LimpaString($_POST['fone_fixo'])		: tool::msg_erros("O Campo telefone residencia é Obrigatorio.");
$FRM_fone_trab			=	isset( $_POST['fone_trab']) 			? tool::LimpaString($_POST['fone_trab']	)		: tool::msg_erros("O Campo telefone de trabalho é Obrigatorio.");
$FRM_fone_cel			=	isset( $_POST['fone_cel']) 				? tool::LimpaString($_POST['fone_cel'])			: tool::msg_erros("O Campo telefone celular é Obrigatorio.");
$FRM_data_nasc			=	isset( $_POST['data_nasc']) 			? tool::LimpaString($_POST['data_nasc'])		: tool::msg_erros("O Campo data de nascimento é Obrigatorio.");
$FRM_cpf				=	isset( $_POST['cpf']) 					? tool::LimpaString($_POST['cpf'])				: tool::msg_erros("O Campo CPF é Obrigatorio.");
$FRM_rg					=	isset( $_POST['rg']) 					? $_POST['rg']									: tool::msg_erros("O Campo RG é Obrigatorio.");
$FRM_orgao_emissor_rg	=	isset( $_POST['orgao_emissor_rg']) 		? $_POST['orgao_emissor_rg']					: tool::msg_erros("O Campo orgão emissão RG é Obrigatorio.");
$FRM_data_emissao_rg	=	isset( $_POST['data_emissao_rg']) 		? tool::LimpaString($_POST['data_emissao_rg'])	: tool::msg_erros("O Campo data emissão RG é Obrigatorio.");
$FRM_sexo				=	isset( $_POST['sexo']) 					? $_POST['sexo']								: tool::msg_erros("O Campo sexo é Obrigatorio.");
$FRM_casa_propria		=	isset( $_POST['casa_propria']) 			? $_POST['casa_propria']						: tool::msg_erros("O Campo casa propria é Obrigatorio.");
$FRM_estado_civil		=	isset( $_POST['estado_civil']) 			? $_POST['estado_civil']						: tool::msg_erros("O Campo estado civil é Obrigatorio.");
$FRM_vendedor			=	isset( $_POST['vendedor']) 				? $_POST['vendedor']							: tool::msg_erros("O Campo vendedor é Obrigatorio.");
$FRM_convenio			=	isset( $_POST['convenio']) 				? $_POST['convenio']							: $COB_Convenio_Id;
$FRM_subconvenio		=	isset( $_POST['subconvenio']) 			? $_POST['subconvenio']							: tool::msg_erros("O Campo subconvenio é Obrigatorio.");
$FRM_email				=	isset( $_POST['email']) 				? $_POST['email']								: tool::msg_erros("O Campo email é Obrigatorio.");
$FRM_logradouro			=	isset( $_POST['logradouro']) 			? $_POST['logradouro']							: tool::msg_erros("O Campo logradouro é Obrigatorio.");
$FRM_compl				=	isset( $_POST['compl_end']) 			? $_POST['compl_end']							: tool::msg_erros("O Campo complemento é Obrigatorio.");
$FRM_num				=	isset( $_POST['num']) 					? $_POST['num']									: tool::msg_erros("O Campo numero é Obrigatorio.");
$FRM_obs				=	isset( $_POST['obs']) 					? $_POST['obs']									: tool::msg_erros("O Campo observação é Obrigatorio.");
$FRM_data_cadastro		=	date("Y-m-d");

/* VERIFICA DUPLICIDADE DE CPF
if($DuplicidadeReg == false){

	$query_duplicidade=associados::find_by_cpf_and_status($FRM_cpf,1);
	if($query_duplicidade){echo tool::msg_erros("CPF cadastrado em nossa base de dados.");}
}*/


// VALIDA OS TELEFONES
if(empty($FRM_fone_fixo)){
	if(empty($FRM_fone_trab)){
		if(empty($FRM_fone_cel)){
			tool::msg_erros("É obrigatório pelo menos um numero de telefone.");
		}
	}
}

//VALIDA A DATA DE NASC
$datanasc=tool::InvertDateTime($FRM_data_nasc,0);

if ( !checkdate( substr($FRM_data_nasc,2,2) , substr($FRM_data_nasc,0,2) , substr($FRM_data_nasc,4,4) ) 				// se a data for inválida
     || substr($FRM_data_nasc,4,4) < 1900                                    			   								// ou o ano menor que 1900
     || mktime( 0, 0, 0, substr($FRM_data_nasc,2,2), substr($FRM_data_nasc,0,2), substr($FRM_data_nasc,4,4)) > time() )	// ou a data passar de hoje
{
	echo tool::msg_erros("Data de nascimento invalida.");

}

// VALIDAÇÃO DO CPF
$cpf_cnpj = new ValidaCPFCNPJ($FRM_cpf);
if ( !$cpf_cnpj->valida() ) {
	sleep(1);
	echo tool::msg_erros("O CPF invalido.");
	return false;
}

//VALIDA O VENDEDOR
if($FRM_vendedor=="" or $FRM_vendedor=="0"){echo tool::msg_erros("è necessario selecionar um vendedor.");}
//VALIDA O CONVENIO
if($FRM_convenio==""  or $FRM_convenio=="0"){echo tool::msg_erros("è necessario selecionar um convênio.");}
//VALIDA O ENDEREÇO
if($FRM_logradouro==""  or $FRM_logradouro=="0" ){echo tool::msg_erros("è necessario selecionar um logradouro.");}

$Query_create= associados::create(
							array(
									'nm_associado'		=> $FRM_nm_associado,
									'limite'			=> $FRM_limite,
									'agregado'			=> $FRM_agregado,
									'fone_fixo'			=> $FRM_fone_fixo,
									'fone_trabalho'		=> $FRM_fone_trab,
									'fone_cel'			=> $FRM_fone_cel,
									'cpf'				=> $FRM_cpf,
									'rg'				=> $FRM_rg,
									'dt_nascimento'		=> tool::InvertDateTime($FRM_data_nasc,0),
									'orgao_emissor_rg'	=> $FRM_orgao_emissor_rg,
									'data_emissao_rg'	=> tool::InvertDateTime($FRM_data_emissao_rg,0),
									'sexo'				=> $FRM_sexo,
									'casa_propria'		=> $FRM_casa_propria,
									'estado_civil'		=> $FRM_estado_civil,
									'email'				=> $FRM_email,
									'num'				=> $FRM_num,
									'obs'				=>  $FRM_obs,
									'dt_cadastro'		=> $FRM_data_cadastro,
									'usuarios_id'		=> $COB_Usuario_Id,
									'vendedores_id'		=> $FRM_vendedor,
									'empresas_id'		=> $COB_Empresa_Id,
									'convenios_id'		=> $FRM_convenio,
									'sub_convenios_id'	=> $FRM_subconvenio,
									'logradouros_id'	=> $FRM_logradouro,
									'compl_end'			=> $FRM_compl
									));

if($Query_create==true){
	$last=associados::find("last");//recupera o ultimo id
	echo $last->id;
	}else{
		echo tool::msg_erros("Erro ao gravar novo associado");
		}

// editar O ASSOCIADO
}else{

$FRM_matricula			=	isset( $_POST['matricula']) 		? $_POST['matricula']							: tool::msg_erros("O Campo matricula é Obrigatorio.");
$FRM_limite				= 	isset( $_POST['limite'])			? tool::limpamoney($_POST['limite'])			: tool::msg_erros("O Campo limite financeiro é Obrigatorio.");
$FRM_agregado			=	isset( $_POST['agregado']) 			? strtoupper($_POST['agregado'])				: tool::msg_erros("O Campo tipo de associado é Obrigatorio.");
$FRM_nm_associado		=	isset( $_POST['nome']) 				? strtoupper($_POST['nome'])					: tool::msg_erros("O Campo matricula é Obrigatorio.");
$FRM_fone_fixo			=	isset( $_POST['fone_fixo']) 		? tool::LimpaString($_POST['fone_fixo'])		: tool::msg_erros("O Campo telefone residencia é Obrigatorio.");
$FRM_fone_trab			=	isset( $_POST['fone_trab']) 		? tool::LimpaString($_POST['fone_trab']	)		: tool::msg_erros("O Campo telefone de trabalho é Obrigatorio.");
$FRM_fone_cel			=	isset( $_POST['fone_cel']) 			? tool::LimpaString($_POST['fone_cel'])			: tool::msg_erros("O Campo telefone celular é Obrigatorio.");
$FRM_data_nasc			=	isset( $_POST['data_nasc']) 		? tool::LimpaString($_POST['data_nasc'])		: tool::msg_erros("O Campo data de nascimento é Obrigatorio.");
$FRM_cpf				=	isset( $_POST['cpf']) 				? tool::LimpaString($_POST['cpf'])				: tool::msg_erros("O Campo CPF é Obrigatorio.");
$FRM_rg					=	isset( $_POST['rg']) 				? $_POST['rg']									: tool::msg_erros("O Campo RG é Obrigatorio.");
$FRM_orgao_emissor_rg	=	isset( $_POST['orgao_emissor_rg']) 	? $_POST['orgao_emissor_rg']					: tool::msg_erros("O Campo orgão emissão RG é Obrigatorio.");
$FRM_data_emissao_rg	=	isset( $_POST['data_emissao_rg']) 	? tool::LimpaString($_POST['data_emissao_rg'])	: tool::msg_erros("O Campo data emissão RG é Obrigatorio.");
$FRM_sexo				=	isset( $_POST['sexo']) 				? $_POST['sexo']								: tool::msg_erros("O Campo sexo é Obrigatorio.");
$FRM_casa_propria		=	isset( $_POST['casa_propria']) 		? $_POST['casa_propria']						: tool::msg_erros("O Campo casa propria é Obrigatorio.");
$FRM_estado_civil		=	isset( $_POST['estado_civil']) 		? $_POST['estado_civil']						: tool::msg_erros("O Campo estado civil é Obrigatorio.");
$FRM_vendedor			=	isset( $_POST['vendedor']) 			? $_POST['vendedor']							: tool::msg_erros("O Campo vendedor é Obrigatorio.");
$FRM_convenio			=	isset( $_POST['convenio']) 			? $_POST['convenio']							: tool::msg_erros("O Campo convenio é Obrigatorio.");
$FRM_subconvenio		=	isset( $_POST['subconvenio']) 		? $_POST['subconvenio']							: tool::msg_erros("O Campo subconvenio é Obrigatorio.");
$FRM_cv_confirm			=	isset( $_POST['cv_confirm']) 		? $_POST['cv_confirm']							: tool::msg_erros("O Campo cv_confirm é Obrigatorio.");
$FRM_email				=	isset( $_POST['email']) 			? $_POST['email']								: tool::msg_erros("O Campo email é Obrigatorio.");
$FRM_logradouro			=	isset( $_POST['logradouro']) 		? $_POST['logradouro']							: tool::msg_erros("O Campo logradouro é Obrigatorio.");
$FRM_compl				=	isset( $_POST['compl_end']) 		? $_POST['compl_end']							: tool::msg_erros("O Campo complemento é Obrigatorio.");
$FRM_num				=	isset( $_POST['num']) 				? $_POST['num']									: tool::msg_erros("O Campo numero é Obrigatorio.");
$FRM_obs				=	isset( $_POST['obs']) 				? $_POST['obs']									: tool::msg_erros("O Campo observação é Obrigatorio.");
$FRM_data_cadastro		=	date("Y-m-d");


// VALIDA OS TELEFONES
if(empty($FRM_fone_fixo)){
	if(empty($FRM_fone_trab)){
		if(empty($FRM_fone_cel)){
			tool::msg_erros("É obrigatório pelo menos um numero de telefone.");
		}
	}
}

//VALIDA A DATA DE NASC
$datanasc=tool::InvertDateTime($FRM_data_nasc,0);

if ( !checkdate( substr($FRM_data_nasc,2,2) , substr($FRM_data_nasc,0,2) , substr($FRM_data_nasc,4,4) ) 				// se a data for inválida
     || substr($FRM_data_nasc,4,4) < 1900                                    			   								// ou o ano menor que 1900
     || mktime( 0, 0, 0, substr($FRM_data_nasc,2,2), substr($FRM_data_nasc,0,2), substr($FRM_data_nasc,4,4)) > time() )	// ou a data passar de hoje
{
	echo tool::msg_erros("Data de nascimento invalida.");

}

/*VALIDAÇÃO DO CPF*/
$cpf_cnpj = new ValidaCPFCNPJ($FRM_cpf);
if ( !$cpf_cnpj->valida() ) {
	sleep(1);
	echo tool::msg_erros("O CPF invalido.");
	return false;
}

//VALIDA O VENDEDOR
if($FRM_vendedor==""  or $FRM_vendedor=="0"){echo tool::msg_erros("è necessario selecionar um vendedor.");}
//VALIDA O CONVENIO
if($FRM_convenio==""  or $FRM_convenio=="0"){echo tool::msg_erros("è necessario selecionar um convênio.");}
//VALIDA O ENDEREÇO
if($FRM_logradouro=="" or $FRM_logradouro=="0" ){echo tool::msg_erros("è necessario selecionar um logradouro.");}


$query_assoc=associados::find($FRM_matricula);
$query_assoc->update_attributes(
                           array(
									'nm_associado'		=> $FRM_nm_associado,
									'limite'			=> $FRM_limite,
									'agregado'			=> $FRM_agregado,
									'fone_fixo'			=> $FRM_fone_fixo,
									'fone_trabalho'		=> $FRM_fone_trab,
									'fone_cel'			=> $FRM_fone_cel,
									'cpf'				=> $FRM_cpf,
									'rg'				=> $FRM_rg,
									'dt_nascimento'		=> tool::InvertDateTime($FRM_data_nasc,0),
									'orgao_emissor_rg'	=> $FRM_orgao_emissor_rg,
									'data_emissao_rg'	=> tool::InvertDateTime($FRM_data_emissao_rg,0),
									'sexo'				=> $FRM_sexo,
									'casa_propria'		=> $FRM_casa_propria,
									'estado_civil'		=> $FRM_estado_civil,
									'email'				=> $FRM_email,
									'num'				=> $FRM_num,
									'obs'				=> $FRM_obs,
									'usuarios_id'		=> $COB_Usuario_Id,
									'vendedores_id'		=> $FRM_vendedor,
									'empresas_id'		=> $COB_Empresa_Id,
									'convenios_id'		=> $FRM_convenio,
									'sub_convenios_id'	=> $FRM_subconvenio,
									'logradouros_id'	=> $FRM_logradouro,
									'compl_end'			=> $FRM_compl
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