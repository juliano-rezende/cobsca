<?php
$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../sessao.php";
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');


// variavel com o valor da acao que é para ser execultada
$plano_id	= isset( $_POST['plano_id']) 	 ? $_POST['plano_id'] : tool::msg_erros("O Campo plano_id Obrigatorio faltando.");



// data inicial
$ref_ini	= isset( $_POST['ref_ini']) 	 ? $_POST['ref_ini'] : tool::msg_erros("O Campo ref_ini Obrigatorio faltando.");
$ref_i=explode("/",$ref_ini);
$ref_p_ini=$ref_i[1]."-".$ref_i[0]."-01";

// data final
$ref_fim	= isset( $_POST['ref_fim']) 	 ? $_POST['ref_fim'] : tool::msg_erros("O Campo ref_fim Obrigatorio faltando.");
$ref_f=explode("/",$ref_fim);
$ref_p_fim= $ref_f[1]."-".$ref_f[0]."-01";



// define a variavel de controle de erros
$query_errors 	="";


$faturamento=faturamentos::find_by_sql("SELECT
											faturamentos.id AS fat_id_parcela,
											dados_cobranca.planos_id,
											faturamentos.referencia,
  											faturamentos.titulos_bancarios_id as titulo,
  											planos.valor AS planos_valor
										FROM
										dados_cobranca
										INNER JOIN faturamentos ON faturamentos.dados_cobranca_id = dados_cobranca.id
										INNER JOIN planos ON planos.id = dados_cobranca.planos_id
										WHERE
											dados_cobranca.planos_id = '".$plano_id."' AND  faturamentos.referencia BETWEEN '".$ref_p_ini."' and '".$ref_p_fim."'");


$fat= new ArrayIterator($faturamento);// joga o resultado em um array
while($fat->valid()):// faz um loop dos dados


if($fat->current()->titulo > 0){

	$query_errors.="<div class='uk-alert uk-alert-warning'>	<i class='uk-icon-warning uk-text-warning' ></i> Erro ao reajustar parcela  nº ".$fat->current()->fat_id_parcela." já possui um titulo bancario.</div>";


}else{

	// faz a atualização
	$update = faturamentos::find($fat->current()->fat_id_parcela);
	$update->valor = ''.$fat->current()->planos_valor.'';
	$update->save();

	// verificamos se ocorreu tudo bem
	if(!$update){
		$query_errors.="<div class='uk-alert uk-alert-warning'>
			<i class='uk-icon-warning uk-text-warning' ></i> Erro ao reajustar parcela  nº ".$fat->current()->fat_id_parcela.".</div>";
	}

}

$fat->next();
endwhile;

// VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
if($query_errors !=""){
	echo '":"","callback":"1","msg":"'.$query_errors.'","status":"warning';
}else{
	echo '":"","callback":"0","msg":"Total de Parcelas atualizadas ('.count($faturamento).')","status":"success';
}
?>