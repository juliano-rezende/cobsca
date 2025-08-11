
<div class="tabs-spacer" style="display:none;">
<?php

$conta=contas_bancarias::all(array('conditions'=>array('status= ? AND empresas_id= ?','1', $COB_Empresa_Id))); 

?>
</div>
<div class="uk-modal-dialog" style="width:500px;">
		<!--<button type="button" class="uk-modal-close uk-close"></button> -->
		<div class="uk-modal-header ">
		<h2><i class="uk-icon-filter uk-icon-small" ></i> Filtro</h2>
		</div>
                <form name="fr_filtro_cpagar" method="post" id="fr_filtro_cpagar" class="uk-form">
                <fieldset style=" width:450px;border:0; padding:0; padding-top:0px;">
                <label>
                <span>Conta</span>
                <select name="contas_bancarias_id_fl" class="select" id="contas_bancarias_id_fl" >
                <option value="" selected="selected">Todas</option>
                <?php
                    $desconta= new ArrayIterator($conta);
                    while($desconta->valid()):
                    echo'<option value="'.$desconta->current()->id.'" >'.utf8_encode($desconta->current()->nm_conta).'</option>';
                    $desconta->next();
                    endwhile;
                ?>
                </select>
                </label>
                <label>
                <span>Tipo</span>
                <select name="status_fl" class="select " id="status_fl">
                    <option value="" selected="selected">Todas</option>
                    <option value="1">Pagas</option>
                    <option value="0">Abertas</option>
                </select>
                </label>
                <label> 
                <span>Periodo</span>
                    <select name="periodo_fl" id="periodo_fl" class="select ">
                      <option value="" selected="selected">Todas</option>
                      <option value="1">Mês atual</option>
                      <option value="2">Ultimos 7 dias</option>
                      <option value="3">Ultimos 15 dias</option>
                      <option value="4">Ultimos 30 dias</option>
                      <option value="5">Mes Anterior</option>
                      <option value="6">Personalizado</option>
                    </select>
                </label>
                <label>
                <span>Data Inicial</span>
                <input name="periodoinicio_fl"  type="text" class="w_100 uk-text-center" id="periodoinicio_fl"     />
                </label>
                <label>
                <span>Data Final</span>
                <input name="periodofim_fl"  type="text" class="w_100 uk-text-center" id="periodofim_fl"    />
                </label>
                </fieldset>
                </form>
        <div class="uk-modal-footer uk-text-right">
            <a href="JavaScript:void(0);" id="Btn_filtro_cpagar_00" class="uk-button uk-button-small uk-button-primary" ><i class="uk-icon-search" ></i> Pesquisar</a>
            <a href="JavaScript:void(0);" id="Btn_filtro_cpagar_01" class="uk-button uk-button-danger uk-button-small uk-modal-close" ><i class="uk-icon-remove" ></i> Cancelar</a>
		</div>
</div>

<script type="text/javascript">

jQuery("#periodoinicio_fl").mask("99/99/9999").attr("disabled", true);
jQuery("#periodofim_fl").mask("99/99/9999").attr("disabled", true);


/********************************************************************/
jQuery("#periodo_fl").change(function(){

var periodo = jQuery("#periodo_fl").val();
// se a pesquisa for por periodo
if(periodo == 6){
jQuery("#periodoinicio_fl").attr("disabled", false).focus();
jQuery("#periodofim_fl").attr("disabled", false);

}else{
jQuery("#periodoinicio_fl").attr("disabled", true);
jQuery("#periodofim_fl").attr("disabled", true);
}
});
/********************************************************************/
jQuery("#Btn_filtro_cpagar_00").click(function (){

var conta  = jQuery("#contas_bancarias_id_fl").val();// id da conta que sera pesquisado
var status = jQuery("#status_fl").val();// tipo se e entrada ou saida
var periodo= jQuery("#periodo_fl").val();// periodo da pesquisa
var inicio = jQuery("#periodoinicio_fl").val(); //data inicial
var fim    = jQuery("#periodofim_fl").val();// data final

LoadContent('assets/financeiro/cpagar/Grid_c_pagar.php?action=search&contas_bancarias_id='+conta+'&status='+status+'&intervalo='+periodo+'&p_inicio='+inicio+'&p_fim='+fim+'','GridContasPagar');


});

</script>