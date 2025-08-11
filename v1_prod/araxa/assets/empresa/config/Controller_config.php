<?php

$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../../sessao.php";
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');



// geral
$FRM_empresa_id			=	isset( $_POST['empresa_id'])		? $_POST['empresa_id']: 	tool::msg_erros("Falta o Campo Obrigatorio empresa_id.");
$FRM_juros				=	isset( $_POST['juros'])				? $_POST['juros']: 			tool::msg_erros("Falta o Campo Obrigatorio juros.");
$FRM_multa				=	isset( $_POST['multa'])				? $_POST['multa']: 			tool::msg_erros("Falta o Campo Obrigatorio multa.");
$FRM_plano_conta_id		=	isset( $_POST['plano_conta_id'])	? $_POST['plano_conta_id']: tool::msg_erros("Falta o Campo Obrigatorio plano_conta_id.");
$FRM_plano_conta_id_d	=	isset( $_POST['plano_conta_id_d'])	? $_POST['plano_conta_id_d']: tool::msg_erros("Falta o Campo Obrigatorio plano_conta_id.");
$FRM_centro_custo_id	=	isset( $_POST['centro_custo_id'])	? $_POST['centro_custo_id']:tool::msg_erros("Falta o Campo Obrigatorio centro_custo_id_d.");
$FRM_carencia			=	isset( $_POST['carencia'])			? $_POST['carencia']: 		tool::msg_erros("Falta o Campo Obrigatorio carencia.");
$FRM_desc_um			=	isset( $_POST['desc_um'])			? $_POST['desc_um']:		tool::msg_erros("Falta o Campo Obrigatorio desc_um.");
$FRM_desc_dois			=	isset( $_POST['desc_dois'])			? $_POST['desc_dois']: 		tool::msg_erros("Falta o Campo Obrigatorio desc_dois.");
$FRM_desc_tres			=	isset( $_POST['desc_tres'])			? $_POST['desc_tres']: 		tool::msg_erros("Falta o Campo Obrigatorio desc_tres.");
$FRM_desc_quatro		=	isset( $_POST['desc_quatro'])		? $_POST['desc_quatro']: 	tool::msg_erros("Falta o Campo Obrigatorio desc_quatro.");
$FRM_desc_cinco			=	isset( $_POST['desc_cinco'])		? $_POST['desc_cinco']: 	tool::msg_erros("Falta o Campo Obrigatorio desc_cinco.");
$FRM_desc_seis			=	isset( $_POST['desc_seis'])			? $_POST['desc_seis']: 		tool::msg_erros("Falta o Campo Obrigatorio desc_seis.");
$FRM_desc_sete			=	isset( $_POST['desc_sete'])			? $_POST['desc_sete']: 		tool::msg_erros("Falta o Campo Obrigatorio desc_sete.");
$FRM_desc_oito			=	isset( $_POST['desc_oito'])			? $_POST['desc_oito']: 		tool::msg_erros("Falta o Campo Obrigatorio desc_oito.");
$FRM_desc_nove			=	isset( $_POST['desc_nove'])			? $_POST['desc_nove']: 		tool::msg_erros("Falta o Campo Obrigatorio desc_nove.");




$FRM_nm_seguradora		=	isset( $_POST['nm_seguradora'])		? $_POST['nm_seguradora']: 	tool::msg_erros("Falta o Campo Obrigatorio nm_seguradora.");
$FRM_cnpj_seg			=	isset( $_POST['cnpj_seg'])			? $_POST['cnpj_seg']:		tool::msg_erros("Falta o Campo Obrigatorio cnpj_seg.");
$FRM_num_apolice		=	isset( $_POST['num_apolice'])		? $_POST['num_apolice']:tool::msg_erros("Falta o Campo Obrigatorio num_apolice.");
$FRM_vl_apol_seg		=	isset( $_POST['vlr_apol_seg'])		? tool::limpamoney($_POST['vlr_apol_seg']): 	tool::msg_erros("Falta o Campo Obrigatorio vl_apol_seg.");
$FRM_vl_aux_fun			=	isset( $_POST['vlr_aux_fun'])		? tool::limpamoney($_POST['vlr_aux_fun']):		tool::msg_erros("Falta o Campo Obrigatorio vl_aux_fun.");
$FRM_validade_apolice	=	isset( $_POST['validade_apolice'])	? $_POST['validade_apolice']:tool::msg_erros("Falta o Campo Obrigatorio validade_apolice.");


// EXECULTA A QUERY
$Query_update = configs::find($FRM_empresa_id);
$Query_update->update_attributes(array(
										'juros' 			=>$FRM_juros,
										'multa' 			=>$FRM_multa,
										'centros_custos_id' =>$FRM_centro_custo_id,
										'planos_contas_id' 	=>$FRM_plano_conta_id,
										'planos_contas_id_d'=>$FRM_plano_conta_id_d,
										'carencia' 			=>$FRM_carencia,
										'desc_um' 			=>$FRM_desc_um,
										'desc_dois' 		=>$FRM_desc_dois,
										'desc_tres' 		=>$FRM_desc_tres,
										'desc_quatro' 		=>$FRM_desc_quatro,
										'desc_cinco' 		=>$FRM_desc_cinco,
										'desc_seis' 		=>$FRM_desc_seis,
										'desc_sete' 		=>$FRM_desc_sete,
										'desc_oito' 		=>$FRM_desc_oito,
										'desc_nove' 		=>$FRM_desc_nove,
										'nm_seguradora'		=>$FRM_nm_seguradora,
										'cnpj_seg' 		=>$FRM_cnpj_seg,
										'num_apolice' 		=>$FRM_num_apolice,
										'vlr_apol_seg' 		=>$FRM_vl_apol_seg,
										'vlr_aux_fun' 		=>$FRM_vl_aux_fun,
										'validade_apolice' 	=>tool::LimpaString(tool::InvertDateTime($FRM_validade_apolice,0))
										));



if($Query_update==true ){
	echo "Configurações atualizadas com sucesso.";
	}else{
		echo tool::msg_erros("Erro ao atualizar dados da conta");
		}



?>