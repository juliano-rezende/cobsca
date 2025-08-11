<?php
$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../../sessao.php";
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

$FRM_convenio_id 		= isset( $_POST['convenio_id']) 		? $_POST['convenio_id']												/* variavel com os convenio das parcelas*/
											    				: tool::msg_erros("O Campo convenio_id Obrigatorio faltando.");
$FRM_parcelas_id 		= isset( $_POST['ids']) 				? $_POST['ids']														/* variavel com os ids das parcelas*/
											    				: tool::msg_erros("O Campo ids Obrigatorio faltando.");

$FRM_conta_bancaria_id 	= isset( $_POST['conta_bancaria_id']) 	? $_POST['conta_bancaria_id']										/* variavel com o conta_bancaria_id da conta bancaria*/
											    				: tool::msg_erros("O Campo conta_bancaria_id Obrigatorio faltando.");
$FRM_vencimento		 	= isset( $_POST['vencimento']) 			? tool::InvertDateTime(tool::LimpaString($_POST['vencimento']),0) 	/* variavel com o vencimento da conta bancaria*/
											    				: tool::msg_erros("O Campo 	vencimento Obrigatorio faltando.");
$FRM_valor				= isset( $_POST['valor'])				? $_POST['valor']:													/* variavel com o valor da conta bancaria*/
																  tool::msg_erros("O Campo valor Obrigatorio  Faltando.");

// SEPARA AS PARCELAS DO ENVIADAS
$FRM_pa = explode(",",$FRM_parcelas_id);


// define a variavel de controle de erros
$query_errors 		="";

##########################################################################################################################################################
// DADOS SACADO
$sacado	= convenios::find_by_sql("SELECT
									convenios.razao_social,
									convenios.cnpj,
									logradouros.descricao AS nm_logradouro,
									CASE
                                       WHEN convenios.compl_end = '' THEN ' '
                                       ELSE convenios.compl_end
                                    END AS compl_end ,
									convenios.num,
									bairros.descricao AS nm_bairro,
									cidades.descricao AS nm_cidade,
									estados.sigla AS uf,
									logradouros.cep
									FROM
									  convenios
									  LEFT JOIN logradouros ON logradouros.id = convenios.logradouros_id
									  LEFT JOIN estados ON estados.id = logradouros.estados_id
									  LEFT JOIN cidades ON cidades.id = logradouros.cidades_id
									  LEFT JOIN bairros ON bairros.id = logradouros.bairros_id
									WHERE
									  convenios.id = '".$FRM_convenio_id."'");


if(!$sacado){
	echo '":"","callback":"1","msg":"Erro de Cadastro Favor verificar cadastro do convênio!","titulos_id":"'.json_decode($FRM_parcelas_id).'","status":"danger';
	exit();
}

#################################################################################################################################################################
// DADOS DA CONTA COBRANÇA
$dadosconta=contas_bancarias::find_by_sql("SELECT
											  contas_bancarias.cod_banco,
											  contas_bancarias_cobs.cod_cedente,
											  contas_bancarias_cobs.carteira_remessa
											FROM
											  contas_bancarias
											  LEFT JOIN contas_bancarias_cobs
											ON contas_bancarias_cobs.contas_bancarias_id = contas_bancarias.id
											WHERE contas_bancarias.id='".$FRM_conta_bancaria_id."' AND
		                                          contas_bancarias.tp_conta='2' AND
		                                          contas_bancarias.status='1'");

/* query nosso numero*/
$query_nsm=titulos::find_by_sql("SELECT MAX((nosso_numero)+1) as nsm FROM titulos_bancarios where contas_bancarias_id='".$FRM_conta_bancaria_id."'");

$dados_titulo['nosso_numero']=$query_nsm[0]->nsm;


$titulos_id = array();

/*
SELECIONA UMA PARCELA PARA RECUPERAR A REFERENCIA
*/
$Query_faturamento=faturamentos::find($FRM_pa[0]);
$ref = new ActiveRecord\DateTime($Query_faturamento->referencia);

/*
CRIA O NUMERO DO DOCUMENTO
*/
$numero_doc = "1";/* 0 PF ou 1 PJ  */
$numero_doc .= tool::CompletaZeros(5,ltrim($FRM_convenio_id,"0"));/* MATRICULA OU CONVENIO 5 DIGITOS  */
$numero_doc .= $ref->format('m');/* MES DE REFERENCIA 2 DIGITOS  */
$numero_doc .= substr($ref->format('Y'),2,2);/* ANO DE REFERENCIA 2 DIGITOS  */


#################################################################################################################################################################
// CRIA O TITULO PARA O CONVÊNIO
$create_titulo = titulos::create(array(
										'empresas_id' 			=> $COB_Empresa_Id,
										'usuarios_id' 			=> $COB_Usuario_Id,
										'contas_bancarias_id'	=> $FRM_conta_bancaria_id,
										'stflagimp'				=>1,
										'cod_mov_rem'	 		=>remessas::Cod_Tab_Remessa($dadosconta[0]->cod_banco,"MOV01"),
										'cod_cedente' 			=> $dadosconta[0]->cod_cedente,
										'carteira_rem' 			=> $dadosconta[0]->carteira_remessa,
										'status' 				=> '0',
										'numero_doc' 			=> $numero_doc,
										'nosso_numero' 			=> $dados_titulo['nosso_numero'],
										'dt_emissao' 			=> date("Y-m-d"),
										'dt_vencimento'			=> $FRM_vencimento,
										'vlr_nominal' 			=> $FRM_valor,
										'tp_sacado' 			=> '02',
										'cpfcnpjsacado'			=> $sacado[0]->cnpj,
										'sacado' 				=> strtoupper($sacado[0]->razao_social),
										'logradouro' 			=> strtoupper($sacado[0]->nm_logradouro." ".$sacado[0]->compl_end),
										'num' 					=> $sacado[0]->num,
										'bairro' 				=> strtoupper($sacado[0]->nm_bairro),
										'cep' 					=> tool::LimpaString($sacado[0]->cep),
										'cidade' 				=> strtoupper($sacado[0]->nm_cidade),
										'uf' 					=> strtoupper($sacado[0]->uf)
										));
if(!$create_titulo){
		$query_errors.=("<i class='uk-icon-exclamation-triangle  uk-text-warning' ></i> Erro ao cria titulo para o convênio nº ".$FRM_convenio_id.".</br");
}else{
	/* ultimo id da adcionado */
	$ult_id_titulos=titulos::last();

// SOMA MAIS UM AO ULTIMO TITULO GERADO
$dados_titulo['nosso_numero']++;

/* atualiza o nosso numero na tabela */
$update_nosso_numero=contas_bancarias_cob::find($FRM_conta_bancaria_id);
$update_nosso_numero->update_attributes(array('ult_nosso_numero'=>$dados_titulo['nosso_numero']));

}


#################################################################################################################################################################
// INICIA O LOOP DA PARCELAS
foreach ($FRM_pa as $value) {

// SELECIONA A PARCELA QUE SERÁ GERADA O TITULO
$Query_faturamento=faturamentos::find($value);

#################################################################################################################################################################
// ATUALIZAÇÃO DO ID DO TITULO GERADO NA TABELA FATURAMENTOS
	$Query_faturamento->update_attributes(array('titulos_bancarios_id'=>$ult_id_titulos->id,'contas_bancarias_id'=>$FRM_conta_bancaria_id,'dt_vencimento'=>$FRM_vencimento,'flag_pago'=>'TITULO GERADO'));

	if(!$Query_faturamento){
			$query_errors.=("<i class='uk-icon-exclamation-triangle  uk-text-warning' ></i> Erro ao cria atualizar numero do titulo para parcela nº ".$value.".</br");
	}

/* verifica se existe procedimento atrelhado a este titulo consultas ou exames */
$Query_pro = procedimentos::find_by_sql("SELECT * FROM procedimentos WHERE faturamentos_id='".$value."'");

if($Query_pro){

$dt_faturamento = date("d/m/Y h:m:s");

$listfat= new ArrayIterator($Query_pro);
while($listfat->valid()):

  $up_procedimentos = procedimentos::find($listfat->current()->id);

  $up_procedimentos->update_attributes(array('status'=> 1,'obs'=>'Procedimento faturado em '.$dt_faturamento.''));

$listfat->next();
endwhile;

}


}/* FIM DO FOREACH */

// VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
if($query_errors !=""){
	echo '":"","callback":"1","msg":"'.$query_errors.'","banco_emissor":"'.$dadosconta[0]->cod_banco.'","titulos_id":"'.$ult_id_titulos->id.'","status":"warning';
}else{
	echo 'callback":"0","msg":"Titulo Gerados!","banco_emissor":"'.$dadosconta[0]->cod_banco.'","titulos_id":"'.$ult_id_titulos->id.'","status":"success';
}


?>