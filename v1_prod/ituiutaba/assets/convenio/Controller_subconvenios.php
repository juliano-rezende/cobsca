<?php
$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../sessao.php";
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');

//classe de validação de cnpj e cpf
require_once("../../classes/valid_cpf_cnpj.php");


$FRM_action	=	isset( $_POST['action'])		? $_POST['action']: 					 	tool::msg_erros("O Campo ação é Obrigatorio.");


if($FRM_action == "new"){

$FRM_razao_social	=	isset( $_POST['subRzsc'])		? $_POST['subRzsc']: 					 	tool::msg_erros("O Campo Obrigatorio razao social.");
$FRM_nm_fantasia	=	isset( $_POST['subNmfant'])		? $_POST['subNmfant']: 				 		tool::msg_erros("O Campo Obrigatorio nome fantasia.");
$FRM_cnpj			=	isset( $_POST['subCnpj'])		? tool::LimpaString($_POST['subCnpj']): 	tool::msg_erros("O Campo Obrigatorio cnpj.");
$FRM_fone_fixo		=	isset( $_POST['subFonefx'])		? tool::LimpaString($_POST['subFonefx']):	tool::msg_erros("O Campo Obrigatorio fone fixo.");
$FRM_contato		=	isset( $_POST['subContact'])	? $_POST['subContact']: 					tool::msg_erros("O Campo Obrigatorio contato.");
$FRM_convenios_id	=	isset( $_POST['Conv_id'])		? $_POST['Conv_id']: 						tool::msg_erros("O Campo Obrigatorio convenios_id."); 


// VERIFICA DUPLICIDADE DE CNPJ
$query_duplicidade=sub_convenios::find_by_cnpj($FRM_cnpj);
if($query_duplicidade){echo tool::msg_erros("CNPJ cadastrado em nossa base de dados.");}

// VALIDA OS TELEFONES
if(empty($FRM_fone_fixo)){
			tool::msg_erros("É obrigatório pelo o numero de telefone.");
}

// VALIDAÇÃO DO CPF
$cpf_cnpj = new ValidaCPFCNPJ($FRM_cnpj);
if ( !$cpf_cnpj->valida() ) {
	sleep(1);
	echo tool::msg_erros("O CNPJ invalido.");
	return false;
}

// EXECULTA A QUERY
$Query_convenios = sub_convenios::create(
array(
	'razao_social' 	=> $FRM_razao_social,
	'nm_fantasia' 	=> $FRM_nm_fantasia,
	'cnpj' 			=> $FRM_cnpj,
	'fone_fixo' 	=> $FRM_fone_fixo,
	'contato' 		=> $FRM_contato,
	'dt_cadastro'	=> date("Y-m-d"),
	'usuarios_id' 	=> $COB_Usuario_Id,
	'convenios_id' 	=> $FRM_convenios_id,
	'empresas_id' 	=> $COB_Empresa_Id
));

if($Query_convenios==true){
		$Ultimoconvenio=convenios::find("last");//recupera o ultimo id
		echo $Ultimoconvenio->id;
		}else{
			echo tool::msg_erros("Erro ao cadastrar Sub-Convenio");
			}
}if($FRM_action == "disabled"){

	
$FRM_SubConvId	=	isset( $_POST['SubConvId'])		? $_POST['SubConvId']:	 	tool::msg_erros("O Campo Sub-convênio é obrigatorio.");


$Query_update_SUB=sub_convenios::find($FRM_SubConvId);
$Query_update_SUB->update_attributes(array('status'=>0));

if($Query_update_SUB == true){
		echo "1";
		}else{
			echo tool::msg_erros("Erro ao desativar o Sub-Convenio");
			}


}if($FRM_action == "enabled"){

	
$FRM_SubConvId	=	isset( $_POST['SubConvId'])		? $_POST['SubConvId']:	 	tool::msg_erros("O Campo Sub-convênio é obrigatorio.");


$Query_update_SUB=sub_convenios::find($FRM_SubConvId);
$Query_update_SUB->update_attributes(array('status'=>1));

if($Query_update_SUB == true){
		echo "1";
		}else{
			echo tool::msg_erros("Erro ao reativar o Sub-Convenio");
			}


}if($FRM_action == "ajaxSub"){

$FRM_convenio_id    = isset( $_POST['Conv_id'])    ? $_POST['Conv_id']            : tool::msg_erros("O Campo convenio é Obrigatorio.");

$Query_subconvenios =sub_convenios::find_by_sql("SELECT * FROM sub_convenios WHERE empresas_id='".$COB_Empresa_Id."' AND convenios_id='".$FRM_convenio_id."' ORDER BY id");
$List_subcovenios   = new ArrayIterator($Query_subconvenios);


	if($Query_subconvenios == true ){

		echo'<option value="">Selecione uma opção</option>';
		
		while($List_subcovenios->valid()):
	
			echo '<option value="'.$List_subcovenios->current()->id.'">'.($List_subcovenios->current()->nm_fantasia).'</option>';

		$List_subcovenios->next();

		endwhile;

	}else{echo 1;}
}

?>