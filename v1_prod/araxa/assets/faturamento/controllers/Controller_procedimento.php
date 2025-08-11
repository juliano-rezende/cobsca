<?php
$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../../sessao.php";
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');



// variavel que define a ação a ser executada
$FRM_acao			= isset( $_POST['action']) 		? $_POST['action']		: tool::msg_erros("O Campo act Obrigatorio faltando.");


###################################################################################################################################################################
// INICIA A AÇÃO INSERT
if($FRM_acao == "insert"){

	$FRM_matricula		=	isset( $_POST['mat']) 			? $_POST['mat']								: tool::msg_erros("O Campo matricula é Obrigatorio.");
	$FRM_id_fat			=	isset( $_POST['id_fat'])		? $_POST['id_fat']							: tool::msg_erros("O Campo cod faturamento é Obrigatorio.");
	$FRM_dt_lanc		=	isset( $_POST['dt_lanc'])		? tool::LimpaString($_POST['dt_lanc'])		: tool::msg_erros("O Campo data de lançamento é Obrigatorio.");
	$FRM_dt_venc		=	isset( $_POST['dt_venc'])		? $_POST['dt_venc']		: tool::msg_erros("O Campo data de vencimento é Obrigatorio.");
	$FRM_vlr_pro		=	isset( $_POST['vlr_proc'])		? tool::limpaMoney($_POST['vlr_proc'])		: tool::msg_erros("O Campo valor do procedimento é Obrigatorio.");
	$FRM_qte_parcelas	=	isset( $_POST['qte_parcelas'])	? $_POST['qte_parcelas']					: tool::msg_erros("O Campo quantidade de parcelas é Obrigatorio.");
	$FRM_nmpaciente 	=	isset( $_POST['nm_paciente'])	? $_POST['nm_paciente']	: tool::msg_erros("O Campo nome do paciente é Obrigatorio.");
	$FRM_historico		=	isset( $_POST['historico'])		? $_POST['historico']	: tool::msg_erros("O Campo historico é Obrigatorio.");
	$FRM_obs			=	isset( $_POST['obs'])			? $_POST['obs']			: tool::msg_erros("O Campo observações é Obrigatorio.");

	$query_errors=0;


	/* recupera os dados da matricula*/
	$dadosassociado= associados::find_by_sql("SELECT convenios_id FROM associados d WHERE matricula = ".$FRM_matricula."");



	/* definimos o valor da parcela */
	$vlr_parcela = ($FRM_vlr_pro / $FRM_qte_parcelas);

	$data = explode('/',$FRM_dt_venc);
	$mes  = $data[1];	//mes
	$dia  = $data[0];	//dia
	$ano  = $data[2];	//ano



	for($i = 0;$i < $FRM_qte_parcelas;$i++){

		$datavenc 	= mktime(0, 0, 0, $mes+$i, $dia, $ano);
		$vencimento	= date('Y-m-d',$datavenc);


		if($i == 0){$historico=$FRM_historico."parcela 1 X ".$FRM_qte_parcelas;}else{$historico=$FRM_historico."parcela ".($i+1)." X ".$FRM_qte_parcelas;}

		$create_pro = procedimentos::create(array(
												'matricula' 		=> $FRM_matricula,
												'dt_lancamento'	 	=> tool::InvertDateTime($FRM_dt_lanc,0),
												'dt_vencimento' 	=> $vencimento,
												'faturamentos_id' 	=> $FRM_id_fat,
												'valor'				=> $vlr_parcela,
												'historico' 		=> $historico,
												'detalhes'			=> $FRM_obs,
												'nm_paciente'		=> $FRM_nmpaciente,
												'convenios_id' 		=> $COB_Usuario_Id,
												'empresas_id'		=> $COB_Empresa_Id
												));

		if(!$create_pro){
			$query_errors.=" Erro ao inserir novo procedimento";
		}
	}


		// VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
		if($query_errors !=""){
			echo '":"","callback":"1","msg":"'.$query_errors.'","status":"warning';
		}else{
			echo '":"","callback":"0","msg":"Procedimento adcionado.","status":"success';
		}

}
###################################################################################################################################################################
// INICIA A AÇÃO REMOVER
if($FRM_acao == "removeproc"){

$FRM_id			= isset( $_POST['id']) 		? $_POST['id']		: tool::msg_erros("O Campo id Obrigatorio faltando.");

/* seleciona a parcela no faturamento*/
$Query=procedimentos::find($FRM_id);

$remove=$Query->delete();


// VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
		if($remove !=""){
			echo '":"","callback":"1","msg":"Procedimento removido","status":"warning';
		}else{
			echo '":"","callback":"0","msg":"Erro ao remover procedimento.","status":"success';
		}

}



?>