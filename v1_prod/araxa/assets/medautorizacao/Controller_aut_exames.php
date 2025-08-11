<?php
$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../sessao.php";
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');


$FRM_action = isset( $_POST['action'])	? $_POST['action']: 	tool::msg_erros("O Campo Obrigatorio ação Faltando.");


/* recebe a ação de inserir nova autorização*/
if($FRM_action == 0){

	$FRM_matricula				=	isset( $_POST['mat'])		? $_POST['mat']: 	tool::msg_erros("O Campo Obrigatorio matricula Faltando.");
	$FRM_slt					=	isset( $_POST['slt'])		? $_POST['slt']: 	tool::msg_erros("O Campo Obrigatorio solicitante Faltando.");
	$FRM_med_parceiros_id		=	isset( $_POST['parId'])		? $_POST['parId']: 	tool::msg_erros("O Campo Obrigatorio codigo parceiro Faltando.");
	$FRM_med_especialidades_id	=	isset( $_POST['espId'])		? $_POST['espId']: 	tool::msg_erros("O Campo Obrigatorio codigo especialidade Faltando.");
	$FRM_operador_id			=	isset( $_POST['opeId'])		? $_POST['opeId']: tool::msg_erros("O Campo Obrigatorio codigo operador Faltando.");
	$FRM_dt_inclusao			=	date("Y-m-d");
	$FRM_dt_realizacao			=	isset( $_POST['dt_realizacao']) ?	tool::InvertDateTime(tool::LimpaString($_POST['dt_realizacao']),0):  tool::msg_erros('Campo dt_realizacao está faltando');
	$FRM_hr_realizacao			=	isset( $_POST['hr_realizacao']) ?	$_POST['hr_realizacao']:  tool::msg_erros('Campo dt_realizacao está faltando');	



/*valida a data de faturamento*/
$dadosparceiro=med_parceiros::find($FRM_med_parceiros_id);

$dt1 =tool::GeraTimeStamp(str_replace("/", "-", $_POST['dt_realizacao']));
$dt2 =tool::GeraTimeStamp(date("d-m-Y"));

if($dadosparceiro->local_pgto == 0){

	if($dt1 <= $dt2){echo tool::msg_erros("Atenção não é permitido liberar autorização para este profissional no mesmo dia da execução!");}
}

	if($FRM_slt == 0){

		$FRM_dependente = "0";
		$FRM_dependentes_id	="0";

	}else{

		$FRM_dependente = "1";
		$FRM_dependentes_id	=	isset( $_POST['assoc'])			? $_POST['assoc']: tool::msg_erros("O Campo Obrigatorio codigo associado Faltando.");

	}


	/*recupera o convenio e o subconvenio da matricula*/

	$QueryAssociados=associados::find($FRM_matricula);

	// EXECULTA A QUERY
	$QueryAutoricacao	= med_autorizacoes::create(
		array(
			'tipo' 					=>1,
			'faturar' 				=>0,
			'status' 				=>0,
			'matricula' 			=>$FRM_matricula,
			'dependente' 			=>$FRM_dependente,
			'dependentes_id' 		=>$FRM_dependentes_id,
			'dt_inclusao' 			=>$FRM_dt_inclusao,
			'dt_realizacao' 		=>$FRM_dt_realizacao,
			'dt_realizacao' 		=>$FRM_dt_realizacao,
			'med_parceiros_id' 		=>$FRM_med_parceiros_id,
			'med_areas_id' 			=>2,
			'med_especialidades_id' =>$FRM_med_especialidades_id,
			'operador_id' 			=>$FRM_operador_id,
			'convenios_id' 			=>$QueryAssociados->convenios_id,
			'sub_convenios_id' 		=>$QueryAssociados->sub_convenios_id,
			'usuarios_id' 			=>$COB_Usuario_Id,
			'empresas_id' 			=>$COB_Empresa_Id
		));

	$lastAut=med_autorizacoes::find("last");//recupera o ultimo id



/* verifica se ocorreu tudo bem na inserção da autorização */
/* quando ocorrer tudo bem passa para a proxima verificação*/
	if($QueryAutoricacao==true){
		
		echo intval($lastAut->id);


	}else{
		echo tool::msg_erros("Erro ao solicitar autorização");
	}



/* recebe a ação pra remover a autorização*/
}elseif($FRM_action == 1){


	$FRM_med_autorizacoes_id	=	isset( $_POST['autId'])	? $_POST['autId']: 	tool::msg_erros("O Campo Obrigatorio codigo de autorização Faltando.");

	$ProcAutorizacoes = med_proc_autorizacoes::find_by_med_autorizacoes_id($FRM_med_autorizacoes_id);


	if($ProcAutorizacoes){
		echo tool::msg_erros("Para remover a autorização você precisa remover primeiro os procedimentos");
	}else{

		$remover = med_autorizacoes::find($FRM_med_autorizacoes_id);
		$remover->delete();

		if($remover==true){
			echo $FRM_med_autorizacoes_id;
		}else{
			echo tool::msg_erros("Autorização removida com sucesso!");
		}
	}


}

?>