<?php
$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../../sessao.php";
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');


if(empty($_POST['procId'])){

	$FRM_status				=	isset( $_POST['st'])			? $_POST['st']: 						tool::msg_erros("O Campo Obrigatorio status Faltando.");
	$FRM_descricao			=	isset( $_POST['dsc'])			? $_POST['dsc']: 						tool::msg_erros("O Campo Obrigatorio descrição Faltando.");
	$FRM_vlr_custo			= 	isset( $_POST['vlr_custo'])		? tool::limpamoney($_POST['vlr_custo']):tool::msg_erros("O Campo Obrigatorio valor de custo Faltando.");
	$FRM_tx_adm				= 	isset( $_POST['tx_adm'])		? tool::limpamoney($_POST['tx_adm'])	:tool::msg_erros("O Campo Obrigatorio valor de venda Faltando 10.");
	$FRM_parceiros_id		=	isset( $_POST['par_id'])		? $_POST['par_id']: 					tool::msg_erros("O Campo Obrigatorio parceiro id Faltando.");
	$FRM_especialidades_id	=	isset( $_POST['esp_id'])		? $_POST['esp_id']: 					tool::msg_erros("O Campo Obrigatorio especialidade id Faltando.");




	// EXECULTA A QUERY
	$Query_procedimento = med_procedimentos::create(
		array(
			'status' 				=>$FRM_status,
			'descricao' 			=>strtolower($FRM_descricao),
			'vlr_custo' 			=>$FRM_vlr_custo,
			'tx_adm' 				=>$FRM_tx_adm,
			'dt_alteracao' 			=>date("Y-m-d"),
			'med_parceiros_id' 		=>$FRM_parceiros_id,
			'med_especialidades_id' =>$FRM_especialidades_id,
			'empresas_id' 			=>$COB_Empresa_Id,
			'usuarios_id' 			=>$COB_Usuario_Id
		));

	if($Query_procedimento==true){
			$Ultimoprocedimento=med_procedimentos::find("last");//recupera o ultimo id
			echo $Ultimoprocedimento->id;
		}else{
			echo tool::msg_erros("Erro ao cadastrar procedimento");
		}


	}else{

		$FRM_pro_id				=	isset( $_POST['procId'])	? $_POST['procId']: 					tool::msg_erros("O Campo Obrigatorio id Faltando.");
		$FRM_status				=	isset( $_POST['st'])		? $_POST['st']: 						tool::msg_erros("O Campo Obrigatorio status Faltando.");
		$FRM_descricao			=	isset( $_POST['dsc'])		? $_POST['dsc']: 						tool::msg_erros("O Campo Obrigatorio descrição Faltando.");
		$FRM_vlr_custo			= 	isset( $_POST['vlr_custo'])		? tool::limpamoney($_POST['vlr_custo']):tool::msg_erros("O Campo Obrigatorio valor de custo Faltando.");
		$FRM_tx_adm				= 	isset( $_POST['tx_adm'])		? tool::limpamoney($_POST['tx_adm'])	:tool::msg_erros("O Campo Obrigatorio valor de venda Faltando.");



		$query_edit=med_procedimentos::find($FRM_pro_id);
		$query_edit->update_attributes(
			array(
				'status' 		=>$FRM_status,
				'descricao' 	=>strtolower($FRM_descricao),
				'vlr_custo' 	=>$FRM_vlr_custo,
				'tx_adm' 		=>$FRM_tx_adm,
				'dt_alteracao' 	=>date("Y-m-d"),
				'usuarios_id' 	=>$COB_Usuario_Id
			));


		if($query_edit==true){
			echo $FRM_pro_id;
		}else{
			echo tool::msg_erros("Erro ao editar procedimento");
		}
	}

	?>