<?php
$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../../sessao.php";
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

// variavel que define a ação a ser executada
$FRM_acao			= isset( $_POST['action']) 		? $_POST['action']		: tool::msg_erros("O Campo act Obrigatorio faltando.");
// variavel com os ids das parcelas
$FRM_parcelas_id 	= isset( $_POST['ids']) 	 	? 	$_POST['ids']
											    	:tool::msg_erros("O Campo ids Obrigatorio faltando.");
// define a variavel de controle de erros
$query_errors 		="";

// verifica se ela é apenas letras
if(!ctype_alpha($FRM_acao)){
	tool::msg_erros('<div class="uk-alert uk-alert-danger"> O campo ação deve ser alpha numerico </div>');
}

###################################################################################################################################################################
// INICIA A AÇÃO REABRIR
if($FRM_acao == "reabrir"){

$FRM_pa 			= explode(",",$FRM_parcelas_id);

// INICIA O LOOP DA PARCELAS
foreach ($FRM_pa as $value) {

	$Query_update_fat=faturamentos::find($value);
	$Query_update_fat->update_attributes(array('status'=>0));

	if(!$Query_update_fat){
		$query_errors.=("<i class='uk-icon-exclamation-triangle  uk-text-warning' ></i> Erro ao reabrir parcela nº ".$value.".</br");
	}
	// VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
	if($query_errors !=""){
		echo '":"","callback":"1","msg":"'.$query_errors.'","status":"warning';
	}else{
		echo '":"","callback":"0","msg":"Parcelas Reabertas.","status":"success';
	}

}
###################################################################################################################################################################
// INICIA A AÇÃO CANCELAR
}if($FRM_acao == "cancel"){

$FRM_pa 			= explode(",",$FRM_parcelas_id);

// INICIA O LOOP DA PARCELAS
foreach ($FRM_pa as $value) {

	/* seleciona a parcela no faturamento*/
	$Query_update_fat=faturamentos::find($value);

	/* quardamos o valor do titulos_bancarios_id na tabela faturamentos*/
	$titulo_id = $Query_update_fat->titulos_bancarios_id;

	/* verificamos quantas parcelas está atrelada ao titulo */
	$valida_titulo = titulos::find($titulo_id);

	/* se o titulo for um titulo empresarial avisamos para o usuario que deve ser feito o cancelamento do titulo no link cancelamento de titulos */
	if($valida_titulo->tp_sacado == 2){echo '":"","callback":"1","icon":"exclamation","msg":"Titulos empresariais devem ser cancelados no menu faturamentos opção cancelamento de titulos.","status":"danger'; exit();}


	/* realiza as atualizações na tabela faturamentos mudamos o status para cancelado e o id do titulo 0 */
	/* observações da parcela*/
	$obs_parcela = $Query_update_fat->obs.'Titulo '.$Query_update_fat->titulos_bancarios_id.' cancelado em: '.date("d/m/Y").'<br>';

	$Query_update_fat->update_attributes(array('titulos_bancarios_id'=>'0','obs'=>$obs_parcela));


	/* verificamos se o update da parcela no faturamento foi ok*/
	if(!$Query_update_fat){

		$query_errors.=("<div class='uk-alert uk-alert-danger'><i class='uk-icon-warning  uk-text-danger' ></i> Erro ao cancelar Titulo da parcela nº ".$value.".</br></div>");

	/* se ocorreu tudo bem proceguimos com o cancelamento do titulo bancario caso ele exista*/
	}else{


		/* selecionamos o banco para trabalhar os codigos de remessa */
		$Query_update_titulo=titulos::find_by_sql("SELECT
												  contas_bancarias.cod_banco,
												  titulos_bancarios.*
												FROM
												  titulos_bancarios
												  INNER JOIN contas_bancarias ON contas_bancarias.id = titulos_bancarios.contas_bancarias_id
												WHERE
												  titulos_bancarios.id = ".$titulo_id."");


		/* verificamos se a parcela possui titulo bancario atrelado a ela se sim cancela o mesmo*/
		if(count($Query_update_titulo) > 0 ){



			if($Query_update_titulo[0]->cod_remessa > 0){


				/* realiza as atualizações na tabela titulos $Query_update_titulo->dt_transmissao !=""*/
				$update_titulo=titulos::find($titulo_id);
				$update_titulo->update_attributes(
														array(
															'status'		=>2,
															'dt_atualizacao'=>date("Y-m-d"),
															'stflagrem'		=>1, /* avisamos para o sistema que este registro deve ser enviado ao banco pois*/
															'cod_mov_rem'	=>remessas::Cod_Tab_Remessa($Query_update_titulo[0]->cod_banco,"MOV02"),
															'mov_manual'	=>"S",
															'obs'			=>'Parcela cancelada no faturamento apos o envio ao banco.'
														));

				if(!$update_titulo ){
					$query_errors.=("<div class='uk-alert uk-alert-danger'>
									 <i class='uk-icon-warning  uk-text-danger' ></i> Erro ao cancelar titulo nº ".$titulo_id." apos o envio ao banco.</br></div>");
				}

			}else{

				/* realiza as atualizações na tabela titulos $Query_update_titulo->dt_transmissao !=""*/
				$update_titulo=titulos::find($titulo_id);
				$update_titulo->update_attributes(
														array(
															'status'		=>2,
															'dt_atualizacao'=>date("Y-m-d"),
															'stflagrem'		=>0, /* avisamos para o sistema que este registro não deve ser enviado ao banco */
															'cod_mov_rem'	=>remessas::Cod_Tab_Remessa($Query_update_titulo[0]->cod_banco,"MOV13"),
															'mov_manual'	=>"S",
															'obs'			=>'Parcela cancelada no faturamento antes do envio ao banco.'
														));

				if(!$update_titulo ){
					$query_errors.=("<div class='uk-alert uk-alert-danger'>
									 <i class='uk-icon-warning  uk-text-danger' ></i> Erro ao cancelar titulo nº ".$titulo_id." antes do envio ao banco.</br></div>");
				}
			}

		}else{$query_errors.=("<i class='uk-icon-exclamation-triangle  uk-text-warning' ></i> Titulo nº ".$Query_parcela[0]->titulos_bancarios_id." não encontrado!</br");}
	}


}
// VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
	if($query_errors !=""){
		echo '":"","callback":"1","icon":"exclamation","msg":"'.$query_errors.'","status":"warning';
	}else{
		echo '":"","callback":"0","icon":"check","msg":"Parcelas Canceladas.","status":"success';
	}
###################################################################################################################################################################
// FINALIZA A AÇÃO CANCELAR E INICIA A AÇÃO REMOVER
}if($FRM_acao == "remove"){

$FRM_pa 			= explode(",",$FRM_parcelas_id);

// INICIA O LOOP DA PARCELAS
foreach ($FRM_pa as $value) {


	/* seleciona a parcela no faturamento*/
	$Query_update_fat=faturamentos::find($value);

	/* quardamos o valor do titulos_bancarios_id na tabela faturamentos*/
	$titulo_id = $Query_update_fat->titulos_bancarios_id;

	if($titulo_id > 0){

	/* verificamos quantas parcelas está atrelada ao titulo */
	$valida_titulo = titulos::find($titulo_id);

	/* se o titulo for um titulo empresarial avisamos para o usuario que deve ser feito o cancelamento do titulo no link cancelamento de titulos */
	if($valida_titulo->tp_sacado == 2){

		echo '":"","callback":"1","msg":"Está parcela possui vinculo a um titulo empresarial, favor efetuar o cancelamento  no menu faturamentos opção cancelamento de titulos antes da exclusão desta parcela.","status":"warning';
		exit();
	}}

	/* realiza as atualizações na tabela faturamentos mudamos o status para cancelado e o id do titulo 0 */
	$Query_update_fat->update_attributes(array('status'=>3));


	/* verificamos se o update da parcela no faturamento foi ok*/
	if(!$Query_update_fat){

		$query_errors.=("<div class='uk-alert uk-alert-danger'>
							<i class='uk-icon-warning  uk-text-danger' ></i> Erro ao remover parcela nº ".$value.".</br>
						</div>");

	/* se ocorreu tudo bem proceguimos com o cancelamento do titulo bancario caso ele exista*/
	}else{


		/* selecionamos o banco para trabalhar os codigos de remessa */
		$Query_update_titulo=titulos::find_by_sql("SELECT
													  contas_bancarias.cod_banco,
													  titulos_bancarios.*
													FROM
													  titulos_bancarios
													  INNER JOIN contas_bancarias ON contas_bancarias.id = titulos_bancarios.contas_bancarias_id
													WHERE
													  titulos_bancarios.id = ".$titulo_id."");


		/* verificamos se a parcela possui titulo bancario atrelado a ela se sim cancela o mesmo*/
		if(count($Query_update_titulo) > 0 ){


			if($Query_update_titulo[0]->cod_remessa > 0){


				/* realiza as atualizações na tabela titulos $Query_update_titulo->dt_transmissao !=""*/
				$update_titulo=titulos::find($titulo_id);
				$update_titulo->update_attributes(
														array(
															'status'		=>2,
															'dt_atualizacao'=>date("Y-m-d"),
															'stflagrem'		=>1, /* avisamos para o sistema que este registro deve ser enviado ao banco pois*/
															'cod_mov_rem'	=>remessas::Cod_Tab_Remessa($Query_update_titulo[0]->cod_banco,"MOV02"),
															'mov_manual'	=>"S",
															'obs'			=>'Parcela cancelada no faturamento apos o envio ao banco.'
														));

				if(!$update_titulo ){
					$query_errors.=("<div class='uk-alert uk-alert-danger'>
									 <i class='uk-icon-warning  uk-text-danger' ></i> Erro ao cancelar titulo nº ".$titulo_id." apos o envio ao banco.</br></div>");
				}

			}else{

				/* realiza as atualizações na tabela titulos $Query_update_titulo->dt_transmissao !=""*/
				$update_titulo=titulos::find($titulo_id);
				$update_titulo->update_attributes(
														array(
															'status'		=>3,
															'dt_atualizacao'=>date("Y-m-d"),
															'stflagrem'		=>0, /* avisamos para o sistema que este registro não deve ser enviado ao banco */
															'cod_mov_rem'	=>remessas::Cod_Tab_Remessa($Query_update_titulo[0]->cod_banco,"MOV13"),
															'mov_manual'	=>"S",
															'obs'			=>'Parcela cancelada no faturamento antes do envio ao banco.'
														));

				if(!$update_titulo ){
					$query_errors.=("<div class='uk-alert uk-alert-danger'>
									 <i class='uk-icon-warning  uk-text-danger' ></i> Erro ao cancelar titulo nº ".$titulo_id." antes do envio ao banco.</br></div>");
				}
			}

		}else{/*$query_errors.=("<i class='uk-icon-exclamation-triangle  uk-text-warning' ></i> Parcela removida !</br");*/}





	}


}// FINALIZA O FOREACH REMOVER

// VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
	if($query_errors !=""){
		echo '":"","callback":"1","msg":"'.$query_errors.'","status":"warning';
	}else{
		echo '":"","callback":"0","msg":"Parcelas Removidas.","status":"success';
	}

}
?>