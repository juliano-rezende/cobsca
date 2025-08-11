<?php
$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../sessao.php";
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');


$FRM_action = isset( $_POST['action'])	? $_POST['action']: 	tool::msg_erros("O Campo Obrigatorio ação Faltando.");

/*#########################################################################################################################################################################################*/
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
	$FRM_med_procedimentos_id	=	isset( $_POST['proId'])			? $_POST['proId']: 	tool::msg_erros("O Campo Obrigatorio codigo de procedimento Faltando.");



/*valida o limite de uso do associado empresarial*/




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
			'tipo' 					=>0,
			'faturar' 				=>0,
			'status' 				=>0,
			'matricula' 			=>$FRM_matricula,
			'dependente' 			=>$FRM_dependente,
			'dependentes_id' 		=>$FRM_dependentes_id,
			'dt_inclusao' 			=>$FRM_dt_inclusao,
			'dt_realizacao' 		=>$FRM_dt_realizacao,
			'dt_realizacao' 		=>$FRM_dt_realizacao,
			'med_parceiros_id' 		=>$FRM_med_parceiros_id,
			'med_areas_id' 			=>1,
			'med_especialidades_id' =>$FRM_med_especialidades_id,
			'operador_id' 			=>$FRM_operador_id,
			'convenios_id' 			=>$QueryAssociados->convenios_id,
			'sub_convenios_id' 		=>$QueryAssociados->sub_convenios_id,
			'usuarios_id' 			=>$COB_Usuario_Id,
			'empresas_id' 			=>$COB_Empresa_Id
		));


/* verifica se ocorreu tudo bem na inserção da autorização */
/* quando ocorrer tudo bem passa para a proxima verificação*/
	if($QueryAutoricacao==true){


		$lastAut=med_autorizacoes::find("last");//recupera o ultimo id


		$QueryProcedimentos	= med_proc_autorizacoes::create(
			array(
				'med_autorizacoes_id' 	=> $lastAut->id,
				'med_procedimentos_id' 	=> $FRM_med_procedimentos_id
			));

/* verifica se ocorreu tudo bem na inserção do procedimento da autorização */
/* quando ocorrer tudo bem passa para a proxima verificação*/
		if($QueryProcedimentos==true){

			echo intval($lastAut->id);
		}else{

			$remover = med_proc_autorizacoes::find($lastAut->id);
			$remover->delete();
			echo tool::msg_erros("Erro ao solicitar autorização");
		}

	}else{
		echo tool::msg_erros("Erro ao solicitar autorização");
	}



/* recebe a ação para remover a autorização*/
}elseif($FRM_action == 1){


	$FRM_med_autorizacoes_id	=	isset( $_POST['autId'])	? $_POST['autId']: 	tool::msg_erros("O Campo Obrigatorio codigo de autorização Faltando.");


	$List_proc = med_proc_autorizacoes::find_by_sql("SELECT id FROM med_proc_autorizacoes WHERE med_autorizacoes_id='".$FRM_med_autorizacoes_id."'");


// verifica se existe procedimentos para a guia
	if($List_proc){


		$list= new ArrayIterator($List_proc);
		while($list->valid()):


			$removerpro = med_proc_autorizacoes::find($list->current()->id);
			$removerpro->delete();

		$list->next();
		endwhile;

		$remover = med_autorizacoes::find($FRM_med_autorizacoes_id);
		$remover->delete();
		echo $FRM_med_autorizacoes_id;

	}else{

		$remover = med_autorizacoes::find($FRM_med_autorizacoes_id);
		$remover->delete();
		echo $FRM_med_autorizacoes_id;
	}



/*#########################################################################################################################################################################################*/
}elseif($FRM_action == 2){/* inicio a solicitação da autorização*/


	$FRM_aut_id      = isset( $_POST['autIdPgto'])    ? $_POST['autIdPgto']   : tool::msg_erros("O Campo autIdPgto é Obrigatorio.");
	$FRM_vlr_pro     = isset( $_POST['vlr_pro'])      ? tool::limpamoney($_POST['vlr_pro'])   : tool::msg_erros("O Campo vlr_pro é Obrigatorio.");
	$FRM_faturar     = isset( $_POST['faturar'])      ? $_POST['faturar']     : tool::msg_erros("O Campo faturar é Obrigatorio.");
	$FRM_parcelar    = isset( $_POST['parcelar'])     ? $_POST['parcelar']    : tool::msg_erros("O Campo parcelar é Obrigatorio.");
	$FRM_parcelas    = isset( $_POST['parcelas'])     ? $_POST['parcelas']    : tool::msg_erros("O Campo parcelas é Obrigatorio.");
	$FRM_dt_venc     = isset( $_POST['dt_venc'])      ? tool::InvertDateTime(tool::LimpaString($_POST['dt_venc']),"-")  : tool::msg_erros("O Campo dt_venc é Obrigatorio.");
	$FRM_operador_id = isset( $_POST['operador_id'])  ? $_POST['operador_id'] : tool::msg_erros("O Campo operador_id é Obrigatorio.");
	$proc_aut        = "";

	//echo tool::msg_erros($FRM_dt_venc);

	// valor total a pagar com parcelamento ou não
	$FRM_vlr_total = ($FRM_vlr_pro * $FRM_parcelas);


	$dadosautorizacao = med_autorizacoes::find_by_sql("SELECT med_autorizacoes.*,
		CASE WHEN med_autorizacoes.dependente = '1' THEN (SELECT dependentes.nome FROM dependentes  WHERE id = med_autorizacoes.dependentes_id)
		ELSE (SELECT associados.nm_associado FROM associados  WHERE matricula = med_autorizacoes.matricula) END AS  solicitante,
		(SUM(med_procedimentos.vlr_custo) + SUM(med_procedimentos.tx_adm)) AS vlr_total,
		convenios.razao_social AS nm_convenio,
		CASE WHEN med_parceiros.tp_parceiro = 'F' THEN med_parceiros.nm_parceiro ELSE med_parceiros.razao_social END AS  nm_parceiro,
		med_parceiros.local_pgto AS local_pgto,
		sub_convenios.razao_social AS nm_subconvenio,
		med_procedimentos.descricao AS desc_pro,
		med_especialidades.descricao AS desc_esp
		FROM
		med_autorizacoes
		LEFT JOIN med_proc_autorizacoes ON med_proc_autorizacoes.med_autorizacoes_id = med_autorizacoes.id
		LEFT JOIN med_procedimentos ON med_procedimentos.id = med_proc_autorizacoes.med_procedimentos_id
		LEFT JOIN convenios ON med_autorizacoes.convenios_id = convenios.id
		LEFT JOIN sub_convenios ON med_autorizacoes.sub_convenios_id = sub_convenios.id
		LEFT JOIN empresas ON med_autorizacoes.empresas_id = empresas.id
		LEFT JOIN associados ON associados.matricula = med_autorizacoes.matricula
		LEFT JOIN med_parceiros ON med_parceiros.id = med_autorizacoes.med_parceiros_id
		LEFT JOIN med_especialidades ON med_especialidades.id = med_autorizacoes.med_especialidades_id
		WHERE
		med_autorizacoes.id='".$FRM_aut_id."'");



	// verificamos se a autorização enviada existe no banco
	if(!$dadosautorizacao){echo tool::msg_erros("Erro linha 149");}


	/*prepara a tabela para update*/
	$update_autorizacao=med_autorizacoes::find($FRM_aut_id);


	// verificamos se a autorização enviada existe no banco
	if(!$update_autorizacao){echo tool::msg_erros("Erro linha 176");}


/* preparamos as descrições dos procedimentos inclusos na autorização*/
	if($update_autorizacao->tipo == 0){

		$proc_aut.=$dadosautorizacao[0]->desc_pro;

	}else{


		/*prepara a tabela para update*/
		$queryProAut=med_proc_autorizacoes::find_by_sql("SELECT
			med_procedimentos.descricao
			FROM
			med_proc_autorizacoes INNER JOIN
			med_procedimentos ON med_procedimentos.id = med_proc_autorizacoes.med_procedimentos_id
			WHERE
			med_proc_autorizacoes.med_autorizacoes_id = '".$FRM_aut_id."'");

		$list_pro= new ArrayIterator($queryProAut);

		while($list_pro->valid()):

		    $proc_aut.="(".$list_pro->current()->descricao.") -";

		$list_pro->next();
		endwhile;
	}


	/* primeira coisa que precisamos saber é para faturar sim ou não*/
	/* inicio if faturar*/
	if($FRM_faturar == 0){

		/*update autorizações com dados do pagamento*/
		$update_autorizacao->update_attributes(array(
			'status'		=> 5,
			'dt_vencimento'	=> $FRM_dt_venc,
			'vlr_total'		=> $FRM_vlr_total,
			'faturar'		=> $FRM_faturar,
			'parcelar'		=> $FRM_parcelar
		));


		$obs="";// variavel vazia que sofrerá update apos inserção
		/* query Up001*/
		$create_notificacao=notificacoes::create(array(
			'usuarios_id'	=> $COB_Usuario_Id,
			'operador_id'	=> $FRM_operador_id,
			'msg'			=> ('Pedido de autorização!'),
			'obs'			=> $obs,
			'indice'		=> 3,//importancia da msg 0 pouco importante, 1 normal, 2 importante e 3 urgente
			'data_hora'		=> date("Y-m-d h:m:s"),
			'empresas_id'   => $COB_Empresa_Id
		));


		$last=notificacoes::find("last");//recupera o ultimo id

		if($dadosautorizacao[0]->local_pgto == 0 ){ $msg_pgto = "Autorização paga para o profissional."; }else{ $msg_pgto = "Autorização paga no emissor."; }

		/* adcionamos uma notificação avisando que o retorno possui erros */
		$obs = '<table class="uk-table uk-table-striped uk-table-hover">';
		$obs .='<tbody>';
		$obs .='<tr style="line-height: 30px"><th>Convênio</th><td>'.$dadosautorizacao[0]->nm_convenio.'</td></tr>';
		$obs .='<tr style="line-height: 30px"><th>Sub-Convênio</th><td>'.$dadosautorizacao[0]->nm_subconvenio.'</td></tr>';
		$obs .='<tr style="line-height: 30px"><th>Solicitante</th><td>( Mat:'.tool::CompletaZeros(11,$dadosautorizacao[0]->matricula).') - Nome:'.$dadosautorizacao[0]->solicitante.'</td></tr>';
		$obs .='<tr style="line-height: 30px"><th>parceiro</th><td>'.$dadosautorizacao[0]->nm_parceiro.'</td></tr>';
		$obs .='<tr style="line-height: 30px"><th>Especialidade</th><td>'.$dadosautorizacao[0]->desc_esp.'</td></tr>';
		$obs .='<tr style="line-height: 30px"><th>Profissional/procedimento</th><td>'.$proc_aut.'</td></tr>';
		$obs .='<tr style="line-height: 30px"><th>Valor total</th><td>'.number_format($dadosautorizacao[0]->vlr_total,2,",",".").'</td></tr>';
		$obs .='<tr style="line-height: 30px"><th>Local de pagamento</th><td>'.$msg_pgto.'</td></tr>';
		$obs .='<tr style="line-height: 30px"><th>Observações</th><td>Autorização sem solicitação de faturamento</td></tr>';
		$obs .='</tbody></table>';
		$obs .='<button class="uk-button uk-button-primary uk-margin uk-btn-notify" type="button" uk-data-id="'.$FRM_aut_id.'" uk-data-not-id="'.$last->id.'"  style="float: right;">Confirmar</button>';



			if($update_autorizacao){

				/* query Up006*/
				$update_not=notificacoes::find($last->id); $update_not->update_attributes(array( 'obs' => $obs));

				if($update_not){

						echo $FRM_aut_id;
				}else{
					echo tool::msg_erros("Erro linha 237");
				}
			}else{
				echo tool::msg_erros("Erro linha 191");
			}

	/* fim if faturar NÃO*/
	/* inicia o procedimento para faturamento e parcelamento*/
	}else{


		for($i = 1;$i <= $FRM_parcelas; $i++){


			$dtv = explode("-",$FRM_dt_venc);
			$ano=$dtv[0];
			$mes=$dtv[1];
			$dia=$dtv[2];

			if($i >=1){$add=$i;}else{$add="";}

			$datavenc = mktime(0, 0, 0, $mes+$add, $dia, $ano);
			$vencimento= date('Y-m-d',$datavenc);
			$referencia_parcela=date('Y-m',$datavenc).'-01';



			/* recupera o id da parcela com vencimento escolhido para lançar o faturamento*/
			$Query_confirm_ref=faturamentos::find_by_sql("SELECT id FROM faturamentos WHERE referencia='".$referencia_parcela."' AND matricula='".$dadosautorizacao[0]->matricula."' AND status='0' AND titulos_bancarios_id='0' ");

			if(!$Query_confirm_ref){


			/* recupera o id da parcela com vencimento escolhido para lançar o faturamento*/
			$Query_confirm_ref2=faturamentos::find_by_sql("SELECT id FROM faturamentos WHERE referencia='".$referencia_parcela."' AND matricula='".$dadosautorizacao[0]->matricula."' AND (status!='0' OR titulos_bancarios_id > '0')");

			if($Query_confirm_ref2){echo tool::msg_erros("Associado com faturamento fechado. Verifique o faturamento antes de faturar este procedimento.");}


				// validamos a referencia escolhida para o desconto
				$dados_cobranca=dados_cobranca::find_by_matricula($dadosautorizacao[0]->matricula);

				$create_parcelas = faturamentos::create(
					array('matricula' 			=> $dadosautorizacao[0]->matricula,
						'dt_vencimento' 		=> $vencimento,
						'referencia' 			=> $referencia_parcela,
						'valor' 				=> $dados_cobranca->valor,
						'dados_cobranca_id' 	=> $dados_cobranca->id,
						'tipo_parcela' 			=> 'M',
						'usuarios_id' 			=> $COB_Usuario_Id,
						'empresas_id'			=> $COB_Empresa_Id,
						'flag_pago'				=> 'FATURADO',
						'convenios_id'			=> $dadosautorizacao[0]->convenios_id,
						'status'				=> '0'
					));

				$create_parcelas=faturamentos::find("last");

			$faturamento_id =  $create_parcelas->id;

			}else{
				$faturamento_id = $Query_confirm_ref[0]->id;

			}

			// inserimos a autorização na parcela do assegurado
			$create_pro = procedimentos::create(array(
				'matricula' 			=> $dadosautorizacao[0]->matricula,
				'dt_lancamento'	 		=> $dadosautorizacao[0]->dt_inclusao,
				'dt_vencimento' 		=> $vencimento, /*1º vencimento*/
				'med_autorizacoes_id'	=> $FRM_aut_id,
				'faturamentos_id' 		=> $faturamento_id,
				'valor'					=> $FRM_vlr_pro,
				'historico' 			=> ("Autorizacao numero  ".$FRM_aut_id.""),
				'detalhes'				=> ('Registro automatico para desconto em folha'),
				'nm_paciente'			=> $dadosautorizacao[0]->solicitante,
				'convenios_id' 			=> $COB_Usuario_Id,
				'empresas_id'			=> $COB_Empresa_Id
			));

		}/* FIM DO LAÇO FOR*/


		/*update autorizações com dados do pagamento*/
		$update_autorizacao->update_attributes(array(
			'status'		=> 5,
			'dt_vencimento'	=> $vencimento,
			'vlr_total'		=> $FRM_vlr_total,
			'faturar'		=> $FRM_faturar,
			'parcelar'		=> $FRM_parcelar
		));


		// variavel vazia que sofrerá update apos inserção
		$obs="";


		/* CRIAMOS A NOTIFICAÇÃO NA TABELA*/
		$create_notificacao=notificacoes::create(array(
			'usuarios_id'	=> $COB_Usuario_Id,
			'operador_id'	=> $FRM_operador_id,
			'msg'			=> ('Pedido de autorização!'),
			'obs'			=> $obs,
			'indice'		=> 3, //importancia da msg 0 pouco importante, 1 normal, 2 importante e 3 urgente
			'data_hora'		=> date("Y-m-d h:m:s"),
			'empresas_id'   => $COB_Empresa_Id
		));

		// verificamos se a autorização enviada existe no banco
		if(!$create_notificacao){echo tool::msg_erros("Erro linha 342");}

		// recuperamos o id da ultima notificação inserida no banco
		$last=notificacoes::find("last");

		$msg_pgto = "Autorização pare debito em folha de pagamento."; 

		/* criamos toda msg da notificação */
		$obs = '<table class="uk-table uk-table-striped uk-table-hover">';
		$obs .='<tbody>';
		$obs .='<tr style="line-height: 30px"><th>Convênio</th><td>'.$dadosautorizacao[0]->nm_convenio.'</td></tr>';
		$obs .='<tr style="line-height: 30px"><th>Sub-Convênio</th><td>'.$dadosautorizacao[0]->nm_subconvenio.'</td></tr>';
		$obs .='<tr style="line-height: 30px"><th>Solicitante</th><td>( Mat:'.tool::CompletaZeros(11,$dadosautorizacao[0]->matricula).') - Nome:'.$dadosautorizacao[0]->solicitante.'</td></tr>';
		$obs .='<tr style="line-height: 30px"><th>parceiro</th><td>'.$dadosautorizacao[0]->nm_parceiro.'</td></tr>';
		$obs .='<tr style="line-height: 30px"><th>Especialidade</th><td>'.$dadosautorizacao[0]->desc_esp.'</td></tr>';
		$obs .='<tr style="line-height: 30px"><th>Profissional/procedimento</th><td>'.$dadosautorizacao[0]->desc_pro.'</td></tr>';
		$obs .='<tr style="line-height: 30px"><th>Valor total</th><td>'.number_format($dadosautorizacao[0]->vlr_total,2,",",".").'</td></tr>';
		$obs .='<tr style="line-height: 30px"><th>Local de pagamento</th><td>'.$msg_pgto.'</td></tr>';
		$obs .='<tr style="line-height: 30px"><th>Observações</th><td>Autorização sem solicitação de faturamento</td></tr>';
		$obs .='</tbody></table>';
		$obs .='<button class="uk-button uk-button-primary uk-margin uk-btn-notify" type="button" uk-data-id="'.$FRM_aut_id.'" uk-data-not-id="'.$last->id.'"  style="float: right;">Confirmar</button>';

		// atualizamos o campo obs da ultima notificação inserida
		$update_not=notificacoes::find($last->id); $update_not->update_attributes(array( 'obs' => $obs));

		// verificamos se a atualização do campo obs foi alterado com sucesso
		if(!$update_not){echo tool::msg_erros("Erro linha 374");}

		echo $FRM_aut_id;



	}/*fim do else faturado com ou sem parcelamento*/


/*#########################################################################################################################################################################################*/
}elseif($FRM_action == 3){ /* inicio confirmação da solicitação da autorização*/

	$FRM_aut_id      = isset( $_POST['autId'])    ? $_POST['autId']   : tool::msg_erros("O Campo autId é Obrigatorio.");
	$FRM_not_id      = isset( $_POST['notId'])    ? $_POST['notId']   : tool::msg_erros("O Campo autId é Obrigatorio.");


	/* query de atualização dos campos status e visuliado pelo operador*/
	$update_aut=med_autorizacoes::find($FRM_aut_id); $update_aut->update_attributes(array('status'=> 1, 'veiw_operador'	=> 1 ));
	/* query de atualização do campo status */
	$update_not=notificacoes::find($FRM_not_id); $update_not->update_attributes(array( 'status' => 1 ));



	if($update_aut){
		if($update_not){echo $FRM_aut_id;}else{echo tool::msg_erros("Erro $update_not!");}
	}else{
		echo tool::msg_erros("Erro ao update_aut!");
	}

/*#########################################################################################################################################################################################*/
}elseif($FRM_action == 4){

	$FRM_aut_id      = isset( $_POST['autId'])    ? $_POST['autId']   : tool::msg_erros("O Campo autId é Obrigatorio.");
	$FRM_not_id      = isset( $_POST['notId'])    ? $_POST['notId']   : tool::msg_erros("O Campo autId é Obrigatorio.");


	/* query de atualização dos campos status e visuliado pelo operador*/
	$update_aut=med_autorizacoes::find($FRM_aut_id); $update_aut->update_attributes(array('status'=> 4, 'veiw_operador'	=> 1 ));
	/* query de atualização do campo status */
	$update_not=notificacoes::find($FRM_not_id); $update_not->update_attributes(array( 'status' => 1 ));


	if($update_aut){
		if($update_not){echo $FRM_aut_id;}else{echo tool::msg_erros("Erro $update_not!");}
	}else{
		echo tool::msg_erros("Erro ao update_aut!");
	}




/* CONTROLHER DAS AUTORIZAÇÕES DE EXAMES*/
/* cria um anova autorização de exame*/
}elseif($FRM_action == 5){

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
}
?>