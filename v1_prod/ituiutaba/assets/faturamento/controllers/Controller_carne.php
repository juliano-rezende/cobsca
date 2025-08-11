<?php
$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro


require_once"../../../sessao.php";
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

$FRM_matricula 			= isset( $_POST['mat']) 	? $_POST['mat']/* variavel com os ids das parcelas*/
											    	: tool::msg_erros("O Campo ids Obrigatorio faltando.");
$FRM_parcelas_id 		= isset( $_POST['ids']) 	? $_POST['ids']/* variavel com os ids das parcelas*/
											    	: tool::msg_erros("O Campo ids Obrigatorio faltando.");
$FRM_conta_bancaria_id 	= isset( $_POST['cb_id']) 	? $_POST['cb_id']/* variavel com o id da conta bancaria*/
											    	: tool::msg_erros("O Campo cb_id Obrigatorio faltando.");

// define a variavel de controle de erros
$query_errors 		="";


#################################################################################################################################################################
// DADOS SACADO
$sacado	= associados::find_by_sql("SELECT
									associados.nm_associado,
									associados.cpf,
									logradouros.descricao AS nm_logradouro,
									CASE
                                       WHEN associados.compl_end = '' THEN ' '
                                       ELSE associados.compl_end
                                    END AS compl_end ,
									associados.num,
									bairros.descricao AS nm_bairro,
									cidades.descricao AS nm_cidade,
									estados.sigla AS uf,
									logradouros.cep
									FROM
									  associados
									  LEFT JOIN logradouros ON logradouros.id = associados.logradouros_id
									  LEFT JOIN estados ON estados.id = logradouros.estados_id
									  LEFT JOIN cidades ON cidades.id = logradouros.cidades_id
									  LEFT JOIN bairros ON bairros.id = logradouros.bairros_id
									WHERE
									  associados.matricula = '".$FRM_matricula."'");

if($sacado[0]->nm_logradouro == ''){
	echo '":"","callback":"1","msg":"Associado com endereço iconrreto favor verificar.'.$sacado[0]->nm_logradouro.'","titulos_id":"","status":"danger';
	exit();
}

#################################################################################################################################################################
// DADOS DA CONTA COBRANÇA
$dadosconta=contas_bancarias::find_by_sql("SELECT
											contas_bancarias.cod_banco,
											contas_bancarias_cobs.cod_cedente,
											contas_bancarias_cobs.carteira_remessa,
											lower(contas_bancarias_cobs.desc_carteira_cob) as dir_bol
											FROM contas_bancarias
											LEFT JOIN contas_bancarias_cobs ON contas_bancarias_cobs.contas_bancarias_id = contas_bancarias.id
											WHERE contas_bancarias.id='".tool::CompletaZeros(3,$FRM_conta_bancaria_id)."' AND
		                                          contas_bancarias.tp_conta='2' AND
		                                          contas_bancarias.status='1'");


/* query nosso numero*/
$query_nsm=titulos::find_by_sql("SELECT
								 CASE WHEN  MAX(nosso_numero)  > 0 THEN  MAX((nosso_numero)+1)
								 ELSE (SELECT ult_nosso_numero FROM contas_bancarias_cobs WHERE contas_bancarias_id = '".$FRM_conta_bancaria_id."')
								 END AS nsm
								 FROM titulos_bancarios where contas_bancarias_id='".tool::CompletaZeros(3,$FRM_conta_bancaria_id)."'");

$dados_titulo['nosso_numero']=$query_nsm[0]->nsm;

$titulos_id = array();

#################################################################################################################################################################
// DADOS DAS PARCELAS SELECINADAS PARA GERAR O CARNE
$FRM_pa = explode(",",$FRM_parcelas_id);

// INICIA O LOOP DA PARCELAS
foreach ($FRM_pa as $value) {


// SELECIONA A PARCELA QUE SERÁ GERADA O TITULO
$Query_faturamento=faturamentos::find($value);



   if(strtotime($Query_faturamento->dt_vencimento) <= strtotime(date("Y-m-d"))){

      $query_errors.=("<i class='uk-icon-exclamation-triangle  uk-text-warning' ></i> Vencimento invalido para parcela nº ".$value.". o vencimento precisa ser maior que a data de emissão titulo não gerado..</br");

      continue;
   }



#################################################################################################################################################################
// VERIFICAMOS SE JÁ EXISTE NUMERO DE TITULO PARA PARCELA SE JÁ EXISTE NÃO PERMITE ALTERAR
if($Query_faturamento->titulos_bancarios_id == 0 or $Query_faturamento->titulos_bancarios_id == ""){

$ref = new ActiveRecord\DateTime($Query_faturamento->referencia);

$numero_doc = "0";/* 0 PF ou 1 PJ  */
$numero_doc .= tool::CompletaZeros(5,ltrim($FRM_matricula,"0"));/* MATRICULA OU CONVENIO 5 DIGITOS  */
$numero_doc .= $ref->format('m');/* MES DE REFERENCIA 2 DIGITOS  */
$numero_doc .= substr($ref->format('Y'),2,2);/* ANO DE REFERENCIA 2 DIGITOS  */

#################################################################################################################################################################
// CRIA O TITULO PARA PARCELA
$create_titulo = titulos::create(array(
										'empresas_id' 			=> $COB_Empresa_Id,
										'usuarios_id' 			=> $COB_Usuario_Id,
										'contas_bancarias_id'=> $FRM_conta_bancaria_id,
										'stflagimp'				=>1,
										'cod_mov_rem'	 		=> remessas::Cod_Tab_Remessa($dadosconta[0]->cod_banco,"MOV01"),
										'cod_ult_mov_rem'	 	=> remessas::Cod_Tab_Remessa($dadosconta[0]->cod_banco,"MOV01"),
										'cod_cedente' 			=> $dadosconta[0]->cod_cedente,
										'carteira_rem' 		=> $dadosconta[0]->carteira_remessa,
										'status' 				=> '0',
										'numero_doc' 			=> $numero_doc,
										'nosso_numero' 		=> $dados_titulo['nosso_numero'],
										'dt_emissao' 			=> date("Y-m-d"),
										'dt_vencimento'		=> $Query_faturamento->dt_vencimento,
										'vlr_nominal' 			=> $Query_faturamento->valor,
										'tp_sacado' 			=> '01',
										'cpfcnpjsacado'		=> $sacado[0]->cpf,
										'sacado' 				=> strtoupper($sacado[0]->nm_associado),
										'logradouro' 			=> strtoupper($sacado[0]->nm_logradouro." ".$sacado[0]->compl_end),
										'num' 					=> $sacado[0]->num,
										'bairro' 				=> strtoupper($sacado[0]->nm_bairro),
										'cep' 					=> tool::LimpaString($sacado[0]->cep),
										'cidade' 				=> strtoupper($sacado[0]->nm_cidade),
										'uf' 					   => strtoupper($sacado[0]->uf)
										));



// RECUPERAMOS O ID DO ULTIMO TITULO GERADO PARA ATUALIZAÇÕES POSTERIORES
$ult_id_titulos = titulos::last();


	// JOGAMOS O ID DO TITULO DENTRO DE UM JSON
	$titulos_id[]= $ult_id_titulos->id;

	if(!$create_titulo){
				$query_errors.=("<i class='uk-icon-exclamation-triangle  uk-text-warning' ></i> Erro ao cria titulo para parcela nº ".$value.".</br");
	}

	// ATUALIZAÇÃO DO ID DO TITULO GERADO NA TABELA FATURAMENTOS
	$Query_faturamento->update_attributes(array('titulos_bancarios_id'=>$ult_id_titulos->id,'contas_bancarias_id'=>$FRM_conta_bancaria_id,'flag_pago'=>'TITULO GERADO'));
	if(!$Query_faturamento){
			$query_errors.=("<i class='uk-icon-exclamation-triangle  uk-text-warning' ></i> Erro ao cria atualizar numero do titulo para parcela nº ".$value.".</br");
	}

	// ATUALIZAÇÃO DO ULTIMO NOSSO NUMERO NA TABELA CONtA BANCARIO COB

	// SOMA MAIS UM AO ULTIMO TITULO GERADO
	$dados_titulo['nosso_numero']++;

	$Up_date_nosso_numero=contas_bancarias_cob::find($FRM_conta_bancaria_id);
	$Up_date_nosso_numero->update_attributes(array('ult_nosso_numero'=>$dados_titulo['nosso_numero']));


	if(!$create_titulo){
			$query_errors.=("<i class='uk-icon-exclamation-triangle  uk-text-warning' ></i> Erro ao cria titulo para parcela nº ".$value.".</br");
	}

}else{

// JOGAMOS O ID DO TITULO DENTRO DE UM JSON
$titulos_id[]= $Query_faturamento->titulos_bancarios_id;
}

}/* FIM DO FOREACH */


// VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
if($query_errors !=""){
	echo '":"","callback":"1","msg":"'.$query_errors.'","dir_bol":"'.$dadosconta[0]->dir_bol.'","titulos_id":"'.json_encode($titulos_id).'","status":"warning';
}else{
	echo 'callback":"0","msg":"Titulo Gerados!","dir_bol":"'.$dadosconta[0]->dir_bol.'","titulos_id":"'.json_encode($titulos_id).'","status":"success';
}

?>