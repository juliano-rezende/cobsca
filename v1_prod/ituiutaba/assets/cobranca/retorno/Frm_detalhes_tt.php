<?php
require_once"../../../sessao.php";
?>
<div class="tabs-spacer" style="display:none;">
<?php
// blibliotecas
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

$FRM_tt_id	= isset( $_GET['t_id']) 	? $_GET['t_id']	: tool::msg_erros("O Campo id do titulo é Obrigatorio.");

// recupera os dados do convenio
$Query_fat=faturamentos::find_by_sql("SELECT * FROM titulos_bancarios WHERE	id = '".$FRM_tt_id."'");



$dtvenc    = new ActiveRecord\DateTime($Query_fat[0]->dt_vencimento);
$dtemissao = new ActiveRecord\DateTime($Query_fat[0]->dt_emissao);

?>
</div>


<form id="FrmDetalhesTitulo"  class="uk-form">

<div style="overflow-x:hidden; overflow-y: scroll; height: 90%;">

<label>
    <span>Sacado</span>
    <input type="text" class="uk-text-left  w_350"   value="<?php echo $Query_fat[0]->sacado; ?>" />
</label>

<label>
    <span>Rua/Av</span>
    <input type="text" class="uk-text-left  w_350"   value="<?php echo $Query_fat[0]->logradouro; ?>" />
</label>

<label>
    <span class="uk-text-right">Numero</span>
    <input type="text" class="uk-text-left  w_50"   value="<?php echo $Query_fat[0]->num; ?>" />
</label>

<label>
    <span>Bairro</span>
    <input type="text" class="uk-text-left  w_350"   value="<?php echo $Query_fat[0]->bairro; ?>" />
</label>

<label>
    <span>Cidade</span>
    <input type="text" class="uk-text-left  w_350"   value="<?php echo $Query_fat[0]->cidade; ?>" />
</label>

<label>
    <span>UF</span>
    <input type="text" class="uk-text-left  w_50"   value="<?php echo $Query_fat[0]->uf; ?>" />
</label>

<label>
    <span>Cep</span>
    <input type="text" class="uk-text-left w_100 cep"   value="<?php echo $Query_fat[0]->cep; ?>" />
</label>

<label>
   <span>Nº Documento</span>
   <input type="text" class="uk-text-right  w_120" disabled="disabled"   value="<?php echo $Query_fat[0]->numero_doc;?>" />
</label>

<label>
    <span>Nosso_numero</span>
    <input type="text" class="uk-text-right w_120" disabled="disabled" value="<?php echo $Query_fat[0]->nosso_numero;?>" />-
    <input type="text" class="uk-text-center w_30" disabled="disabled" value="<?php echo $Query_fat[0]->dv_nosso_numero;?>" />
</label>

<label>
    <span>DT Emissão</span>
    <input type="text" class="uk-text-right w_120" disabled="disabled"   value="<?php echo $dtemissao->format('d/m/Y'); ?>" id="dtv" data-uk-datepicker="{format:'DD/MM/YYYY'}" />
</label>

<label>
	<span>DT Vencimento</span>
	<input type="text" class="uk-text-right w_120"  value="<?php echo $dtvenc->format('d/m/Y'); ?>" id="dte" data-uk-datepicker="{format:'DD/MM/YYYY'}" />
</label>

<label>
<span>Valor</span>
    <input type="text"  class="uk-text-right w_120 uk-badge-primary" value="<?php echo number_format($Query_fat[0]->vlr_nominal,2,",","."); ?>" id="vlr_nominal" />
</label>

<label>
    <span>Cod retorno</span>
    <input type="text" class="uk-text-right  w_120" disabled="disabled"  value="<?php echo tool::CompletaZeros(12,$Query_fat[0]->cod_retorno);?>" />
</label>

<label>
	<span>Cod remessa</span>
	<input type="text" class="uk-text-right  w_120" disabled="disabled" value="<?php echo tool::CompletaZeros(12,$Query_fat[0]->cod_remessa);?>" />
</label>

<label>
	<span>Linha digitavel</span>
	<input type="text" id="line_dig" class="uk-text-left w_350" disabled="disabled" value="<?php  echo $Query_fat[0]->linha_digitavel;?>" />
</label>

<label>
    <span>Linha remessa</span>
    <input type="text" id="line_rem" class="uk-text-left w_400" disabled="disabled" value="<?php  echo $Query_fat[0]->linha_remessa;?>" />
</label>

<label>
    <span>Observações</span>
    <?php echo  str_replace("<br>", "\n", $Query_fat[0]->obs);?>
</label>


</form>
</div>

<a  id="Btn_det_tt" class="uk-button uk-button-primary" style=" bottom:30px; position:absolute;  right:15px;" data-uk-tooltip="{pos:'left'}" title="Atualizar" data-cached-title="Atualizar" ><i class="uk-icon-check"></i> Atualizar </a>


<script type="text/javascript">

jQuery(".cep").mask("99.999-999");
jQuery("#line_dig").mask("99999.99999.99999.999999 99999.999999 9 99999999999999");
jQuery("#vlr_nominal").maskMoney({showSymbol:true, symbol:"", decimal:",", thousands:"."});

</script>