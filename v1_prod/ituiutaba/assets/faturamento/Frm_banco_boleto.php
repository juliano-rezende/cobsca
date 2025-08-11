<?php
require_once"../../sessao.php";
include("../../conexao.php");
$cfg->set_model_directory('../../models/');

$FRM_valor			=	isset( $_GET['v']) 		? $_GET['v']		: tool::msg_erros("O Campo Obrigatorio invalido.");
$FRM_parcelas		=	isset( $_GET['p']) 		? $_GET['p']		: tool::msg_erros("O Campo Obrigatorio invalido.");

?>
<div class="tabs-spacer" style="display:none;">

<?php
// recupera todas as parcelas em aberto
$query=contas_bancarias::find_by_sql("SELECT * FROM contas_bancarias WHERE empresa_id='".$COB_Empresa_Id."' AND tipo_conta='1' AND status='1' ORDER BY sequencia");
$list= new ArrayIterator($query);

?>
</div>

<form method="post" id="FrmBancoBolNeg" class="uk-form" style="height:70%;"> 
<fieldset class="uk-text-center" style="width:275px;">

<label> 
<select name="banco_emissor" id="banco_emissor" class="select" style=" width:100%; height:35px;">

    <option value="0" selected="selected">Selecionar banco</option>

<?php
while($list->valid()):
?>
    <option value="<?php echo $list->current()->cod_banco; ?>"><?php echo $list->current()->nm_banco; ?></option>

<?php
$list->next();
endwhile; 
?>
 </select>

</label>
</fieldset>

</form>

    <a  id="Btn_banco_1" class="uk-button " style=" float:right; margin-right:5px;" data-uk-tooltip="{pos:'left'}" title="Proceguir" data-cached-title="Proceguir" >Prosseguir <i class="uk-icon-angle-double-right"></i></a>


<script>
jQuery("#menu-float").css("background-color",""+$("#"+$("#menu-float").closest('.Window').attr('id')+"").css('border-left-color')+"");


jQuery(function() {

 jQuery("#Btn_banco_1").click(function(event) {

	//desabilita o envento padrao do formulario
	 event.preventDefault();

	//matricula
	var matricula=$("#matricula").val();
	//items do boleto
	var itemsboleto = "<?php echo $FRM_parcelas; ?>";
	// codigo do banco para gerar o boleto
	var cdb=$("#banco_emissor").val();
	// valor total das parcelas
	var vtotal="<?php echo $FRM_valor; ?>";

	// nova janela com boleto para impressão
	New_window('list-ol','800','520','Parcelamento','assets/faturamento/Frm_parcelamento.php?cdb='+cdb+'&m='+matricula+'&p='+itemsboleto+'&v='+vtotal+'',true);


 });

});

</script>