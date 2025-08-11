<?php
require_once"../../sessao.php";
?>
<div class="tabs-spacer" style="display:none;">
<?php
// blibliotecas
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');

$FRM_titulo_id	= isset( $_GET['titid']) 	? $_GET['titid']	: tool::msg_erros("O Campo codigo da parcela é Obrigatorio.");

// recupera os dados do titulo
$Query_titulo=titulos::find_by_sql("SELECT
									SQL_CACHE
									titulos_bancarios.*,
									contas_bancarias.nm_conta,
									usuarios.nm_usuario
									FROM titulos_bancarios
									LEFT JOIN contas_bancarias ON contas_bancarias.id = titulos_bancarios.contas_bancarias_id
									LEFT JOIN usuarios ON usuarios.id = titulos_bancarios.usuarios_id
									WHERE
									titulos_bancarios.id = '".$FRM_titulo_id."'");


$dtemissao = new ActiveRecord\DateTime($Query_titulo[0]->dt_emissao);
$dtvenc  = new ActiveRecord\DateTime($Query_titulo[0]->dt_vencimento);
$dtpgto  = new ActiveRecord\DateTime($Query_titulo[0]->dt_pagamento);



/* detalhamos a composição do numero do documento */
$num_doc=" ".$Query_titulo[0]->numero_doc;
$numero_doc = substr($num_doc, 1,1)."."; /* pj or pf*/
$numero_doc .= substr($num_doc, 2,5).".";/* MATRICULA OU CONVENIO 5 DIGITOS  */
$numero_doc .= substr($num_doc, 7,2).".";/* MES DE REFERENCIA 2 DIGITOS  */
$numero_doc .= substr($num_doc, 9,2);/* ANO DE REFERENCIA 2 DIGITOS  */
?>
</div>
<form method="post" id="FrmDettit" class="uk-form" style="padding: 0px 0; margin-top: 0;padding-top: 0px; ">

<table class="uk-table uk-table-striped uk-table-hover" style="text-transform: capitalize;">
	<tbody>
		<tr>
			<th class="uk-text-center">Id Boleto</th>
			<th class="uk-text-center">Nº Documento</th>
			<th class="uk-text-center">Data Emissão</th>
			<th class="uk-text-center">Data Vencimento</th>
			<th class="uk-text-center" colspan="2">Data pagamento</th>
		</tr>
		<tr>
			<td class="uk-text-center"><input readonly="readonly" type="text" class="uk-text-center  w_120" name="nsm" id="nsm"  value="<?php  echo $Query_titulo[0]->id;?>" /></td>
			<td class="uk-text-center"><input readonly="readonly" type="text" class="uk-text-center  w_120" name="ndoc" id="ndoc"  value="<?php  echo $numero_doc;?>" data-uk-tooltip="" title="Composição do numero (Tipo do titulo 0-PF 1-PJ).(Matricula 5 digitos ).(Mes).(Ano) de referencia da parcela" data-cached-title="Composição do numero (Tipo do titulo 0-PF 1-PJ).(Matricula 5 digitos ).(Mes).(Ano) de referencia da parcela"/></td>
			<td class="uk-text-center"><input readonly="readonly" type="text" class="uk-text-center  w_120" name="dt_emissao" id="dt_emissao"  value="<?php echo $dtemissao->format('d/m/Y'); ?>" /> </td>
			<td class="uk-text-center"><input type="text" class="uk-text-center  w_120" name="dt_venc" id="dt_venc"   value="<?php echo $dtvenc->format('d/m/Y'); ?>" data-uk-datepicker="{format:'DD/MM/YYYY'}"/></td>
			<td class="uk-text-center" colspan="2"><input readonly="readonly" type="text" class="uk-text-center  w_120" name="dt_pgto" id="dt_pgto"  value="<?php if($Query_titulo[0]->dt_pagamento==""){echo"00/00/0000";}else{echo $dtpgto->format('d/m/Y');} ?>" /></td>
		</tr>
		<tr>
			<th class="uk-text-center">Valor nominal</th>
			<th class="uk-text-center">Valor pago</th>
			<th class="uk-text-center">Acrescimos</th>
			<th class="uk-text-center">Descontos</th>
			<th class="uk-text-center">Juros</th>
			<th class="uk-text-center">Multa</th>
		</tr>
		<tr>
			<td class="uk-text-center"><input readonly="readonly" type="text" class="uk-text-center  w_120" name="vlr_nom" id="vlr_nom"  value="<?php  echo number_format($Query_titulo[0]->vlr_nominal,2,',','.');?>" /></td>
			<td class="uk-text-center"><input readonly="readonly" type="text" class="uk-text-center  w_120" name="vlr_pago" id="vlr_pago"  value="<?php  echo number_format($Query_titulo[0]->vlr_pago,2,',','.');?>" /></td>
			<td class="uk-text-center"><input readonly="readonly" type="text" class="uk-text-center  w_120" name="vlr_acr" id="vlr_acr"  value="<?php  echo number_format($Query_titulo[0]->vlr_acrescimos,2,',','.');?>" /></td>
			<td class="uk-text-center"><input readonly="readonly" type="text" class="uk-text-center  w_120" name="vlr_desc" id="vlr_desc"  value="<?php  echo number_format($Query_titulo[0]->vlr_descontos,2,',','.');?>" /></td>
			<td class="uk-text-center"><input readonly="readonly" type="text" class="uk-text-center  w_120" name="vlr_jur" id="vlr_jur"  value="<?php  echo number_format($Query_titulo[0]->vlr_juros,2,',','.');?>" /></td>
			<td class="uk-text-center"><input readonly="readonly" type="text" class="uk-text-center  w_120" name="vlr_mul" id="vlr_mul"  value="<?php  echo number_format($Query_titulo[0]->vlr_multa,2,',','.');?>" /></td>
		</tr>
		<tr>
			<th class="uk-text-center">Nosso numero</th>
			<th class="uk-text-center">Cod retorno</th>
			<th class="uk-text-center">Cod Remessa</th>
			<th class="uk-text-center" colspan="4">Linha Digitavel</th>
		</tr>
		<tr>
			<td class="uk-text-center"><?php  echo $Query_titulo[0]->nosso_numero."-".$Query_titulo[0]->dv_nosso_numero;?></td>
			<td class="uk-text-center"><?php  echo $Query_titulo[0]->cod_retorno;?></td>
			<td class="uk-text-center"><?php  echo $Query_titulo[0]->cod_remessa;?></td>
			<td class="uk-text-center" colspan="4"><?php  echo $Query_titulo[0]->linha_digitavel; ?></td>
		</tr>
		<tr>
			<th class="uk-text-center" colspan="2">Conta Bancaria</th>
			<th class="uk-text-center" colspan="2">Usuario</th>
			<th class="uk-text-center" colspan="2">Via Impressa</th>
		</tr>
		<tr>
			<td class="uk-text-center" colspan="2"><?php  echo $Query_titulo[0]->nm_conta; ?></td>
			<td class="uk-text-center" colspan="2"><?php  echo $Query_titulo[0]->nm_usuario; ?></td>
			<td class="uk-text-center" colspan="2">
			<?php
			echo $Query_titulo[0]->stflagimp.'º  via';
			?>
			</td>
		</tr>
		<tr>
			<th class="uk-text-center" colspan="6">Detalhes</th>
		</tr>
		<tr>
			<td class="uk-text-center" colspan="6"><?php  echo $Query_titulo[0]->obs; ?></td>
		</tr>
	</tbody>
</table>
</form>

<script type="text/javascript">

//jQuery("#line_dig").mask("99999.99999.99999.999999 99.99.999999 9 9999999999");



</script>

