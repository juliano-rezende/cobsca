<?php
$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../../../ssessao.php";
require_once(".../../../../sonexao.php");
$cfg->set_model_directory('../../../../models/');

// recupera as taxas configuradas na empresa
$dados_config=configs::find_by_empresas_id($COB_Empresa_Id);




################################################################################################################################################################################
// UPDATE DE VALOR,DATA DE VECIMENTO E DETALHES



$FRM_id				= isset( $_POST['id_t'])?
					  $_POST['id']:
					  tool::msg_erros('Campo id está faltando');	// id da parcela
$FRM_valor			= isset( $_POST['vlr'])?
					  tool::limpaMoney($_POST['vlr']):
					  tool::msg_erros('Campo valor está faltando');	// valor da parcela
$FRM_dtvencimento	= isset( $_POST['dtv'])?
					  tool::InvertDateTime(tool::LimpaString($_POST['dtv']),0):
					  tool::msg_erros('Campo dtv está faltando');	// data vencimento da parcela
$FRM_detalhes		= isset( $_POST['dth'])?
					  $_POST['dth']:
					  tool::msg_erros('Campo dth está faltando');	// detalhes da parcela


$Query_update=faturamentos::find($FRM_id);
$Query_update->update_attributes(array(
										'dt_vencimento'	=>$FRM_dtvencimento,
										'valor'			=>$FRM_valor,
										'obs'			=>$FRM_detalhes,
										'usuarios_id'	=>$COB_Usuario_Id
										));

if($Query_update->titulos_bancarios_id > 0 ){


$Query_update_titulo=titulos::find($Query_update->titulos_bancarios_id);
$Query_update_titulo->update_attributes(
										array('dt_vencimento'	=>$FRM_dtvencimento,
											  'dt_atualizacao' 	=>date("Y-m-d"),
											  'stflagrem'		=>'1',
											  'cod_mov_rem'	 	=>remessas::Cod_Tab_Remessa($Query_titulo[0]->cod_banco,"MOV05")
										));

}

// VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
	if(!$Query_update){
			echo '":"","callback":"1","msg":"Erro ao atualizar dados!","status":"warning';
	}else{
				echo '":"","callback":"0","msg":"Dados atualizados !.","status":"success';
	}



?>