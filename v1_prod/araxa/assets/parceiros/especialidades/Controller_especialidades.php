<?php
$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../../sessao.php";
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');


if(empty($_POST['espId'])){

	$FRM_status			=	isset( $_POST['st'])		? $_POST['st']: 	tool::msg_erros("O Campo Obrigatorio status Faltando.");
	$FRM_descricao		=	isset( $_POST['dsc'])		? $_POST['dsc']: 	tool::msg_erros("O Campo Obrigatorio descrição Faltando.");
	$FRM_areas_id		=	isset( $_POST['area'])		? $_POST['area']: 	tool::msg_erros("O Campo Obrigatorio area Faltando.");
	$FRM_parceiros_id	=	isset( $_POST['par_id'])	? $_POST['par_id']: tool::msg_erros("O Campo Obrigatorio codigo parceiro Faltando.");




	// EXECULTA A QUERY
	$Query_especialidade 	= med_especialidades::create(
		array(
			'status' 			=>$FRM_status,
			'descricao' 		=>strtolower($FRM_descricao),
			'med_areas_id' 		=>$FRM_areas_id,
			'med_parceiros_id' 	=>$FRM_parceiros_id,
			'empresas_id' 		=>$COB_Empresa_Id,
			'usuarios_id' 		=>$COB_Usuario_Id
		));

	if($Query_especialidade==true){
			$Ultimoespecialidade=med_especialidades::find("last");//recupera o ultimo id
			echo $Ultimoespecialidade->id;
		}else{
			echo tool::msg_erros("Erro ao cadastrar especialidade");
		}


	}else{

		$FRM_esp_id			=	isset( $_POST['espId'])		? $_POST['espId']: tool::msg_erros("O Campo Obrigatorio id Faltando.");
		$FRM_status			=	isset( $_POST['st'])		? $_POST['st']: 	tool::msg_erros("O Campo Obrigatorio status Faltando.");
		$FRM_descricao		=	isset( $_POST['dsc'])		? $_POST['dsc']: 	tool::msg_erros("O Campo Obrigatorio descrição Faltando.");
		$FRM_areas_id		=	isset( $_POST['area'])		? $_POST['area']: 	tool::msg_erros("O Campo Obrigatorio area Faltando.");

		$query_edit=med_especialidades::find($FRM_esp_id);
		$query_edit->update_attributes(
			array(
				'status' 			=>$FRM_status,
				'descricao' 		=>strtolower($FRM_descricao),
				'med_areas_id' 		=>$FRM_areas_id,
				'usuarios_id' 		=>$COB_Usuario_Id
			));



		if($query_edit==true){
			echo $FRM_esp_id;
		}else{
			echo tool::msg_erros("Erro ao editar especialidade");
		}
	}
?>