<?php
$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro
require_once"../../sessao.php";
echo'<div class="tabs-spacer" style="display:none;">';
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');
echo'</div>';


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

// DEFINIR A VARIAVEL LINHA COMO VAZIA ANTES DE PREECHER COM O RESULTADO ABAIXO //
$linha 	=	"";
echo'<div class="tabs-spacer" style="display:none;">';
$faturamento=faturamentos::find_by_sql("SELECT
											faturamentos.id AS fat_id_parcela,
											faturamentos.matricula AS matricula,
											dados_cobranca.planos_id,
											associados.nm_associado,
											faturamentos.referencia,
  											faturamentos.titulos_bancarios_id as titulo,
  											planos.valor AS planos_valor
										FROM
										dados_cobranca
										INNER JOIN faturamentos ON faturamentos.dados_cobranca_id = dados_cobranca.id
										INNER JOIN planos ON planos.id = dados_cobranca.planos_id
										INNER JOIN associados ON associados.matricula = faturamentos.matricula
										WHERE
											dados_cobranca.planos_id = '".$plano_id."' AND  faturamentos.referencia BETWEEN '".$ref_p_ini."' and '".$ref_p_fim."'");
echo'</div>';

$fat= new ArrayIterator($faturamento);// joga o resultado em um array
while($fat->valid()):// faz um loop dos dados

$ref = new ActiveRecord\DateTime($fat->current()->referencia);

if($fat->current()->titulo > 0){


	$linha.='<tr>';
	$linha.='<th class="uk-width uk-text-center" style="width:100px;" >'.$fat->current()->matricula.'</th>';
	$linha.='<td class=" uk-text-left" style="text-transform:uppercase; " >'.$fat->current()->nm_associado.'</td>';
	$linha.='<td class="uk-width uk-text-center" style="width:90px;" >'.tool::Referencia($ref->format('Ymd'),"/").'</td>';
	$linha.='<td class="uk-width uk-text-center" style="width:90px;" >'.$FRM_p_vencimento.'</td>';
	$linha.='<td class="uk-width uk-text-center" style="width:135px;" ><i class="uk-icon-remove uk-text-danger"></i> Erro já possui titulo bancario.</td>';
	$linha.= '</tr>';


}else{

	// faz a atualização
	$update = faturamentos::find($fat->current()->fat_id_parcela);
	$update->valor = ''.$fat->current()->planos_valor.'';
	$update->save();

	// verificamos se ocorreu tudo bem
	if(!$update){

			$linha.='<tr>';
			$linha.='<th class="uk-width uk-text-center" style="width:100px;" >'.$fat->current()->matricula.'</th>';
			$linha.='<td class=" uk-text-left" style="text-transform:uppercase; " >'.$fat->current()->nm_associado.'</td>';
			$linha.='<td class="uk-width uk-text-center" style="width:90px;" >'.tool::Referencia($ref->format('Ymd'),"/").'</td>';
			$linha.='<td class="uk-width uk-text-center" style="width:90px;" >'.$FRM_p_vencimento.'</td>';
			$linha.='<td class="uk-width uk-text-center" style="width:135px;" ><i class="uk-icon-remove uk-text-danger"></i> Erro ao reajustar parcela.</td>';
			$linha.= '</tr>';
	}else{

			$linha.='<tr>';
			$linha.='<th class="uk-width uk-text-center" style="width:100px;" >'.$fat->current()->matricula.'</th>';
			$linha.='<td class=" uk-text-left" style="text-transform:uppercase; " >'.$fat->current()->nm_associado.'</td>';
			$linha.='<td class="uk-width uk-text-center" style="width:90px;" >'.tool::Referencia($ref->format('Ymd'),"/").'</td>';
			$linha.='<td class="uk-width uk-text-center" style="width:90px;" >'.$FRM_p_vencimento.'</td>';
			$linha.='<td class="uk-width uk-text-center" style="width:135px;" ><i class="uk-icon-remove uk-text-danger"></i> Parcela reajustada com sucesso.</td>';
			$linha.= '</tr>';
	}

}

$fat->next();
endwhile;


echo'<table  class="uk-table uk-table-header">';
echo'<thead class="uk-gradient-cinza">';
echo'<tr>';
	echo'<th class="uk-width uk-text-center" style="width:100px;" >Matricula</th>';
	echo'<th class=" uk-text-left"  >Conveniado</th>';
	echo'<th class="uk-width uk-text-center"  style="width:90px;" >Referencia</th>';
	echo'<th class="uk-width uk-text-center" style="width:90px;" >Vencimento</th>';
	echo'<th class="uk-width uk-text-center" style="width:135px;" >Status</th>';
	echo'<th class="uk-width uk-text-center" style="width:15px;" ></th>';
echo'</tr>';
echo'</thead>';
echo'</table>';
echo'<div id="result" style="overflow-y:scroll; height: 470px;">';
	echo'<table class="uk-table uk-table-striped uk-table-hover">';
	echo'<tbody>';
	echo $linha;
	echo'</tbody>';
	echo'</table>';
echo'</div>';
?>
<script type="text/javascript">
 modal.hide();
</script>