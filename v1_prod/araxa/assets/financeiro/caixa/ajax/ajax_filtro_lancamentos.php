<div class="uk-modal-dialog" style="width:500px;">
		<!--<button type="button" class="uk-modal-close uk-close"></button> -->
		<div class="uk-modal-header ">
		<h2><i class="uk-icon-filter uk-icon-small" ></i> Filtro</h2>
		</div>
                <form name="fr_filtro_recebimentos" method="post" id="fr_filtro_recebimentos" class="uk-form">
                <fieldset style=" width:450px;border:0; padding:0; padding-top:0px;">
                <label>
                <span>Conta</span>
                <select name="cdcontabanco" class="select" id="cdcontabanco" >
                <?php
                    $conta=contas_bancarias::all(array('conditions'=>array('status= ? AND empresas_id= ?','1', $COB_Empresa_Id)));
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
                <select name="tipo" class="select " id="tipo">
                    <option value="0" selected="selected">Todos</option>
                    <option value="c">Entradas</option>
                    <option value="d">Saidas</option>
                    <option value="1">Transferencias</option>
                </select>
                </label>
                <label> 
                <span>Periodo</span>
                    <select name="periodo" id="periodo" class="camposdigitaveis border_radios3 select ">
                      <option value="0" selected="selected">Selecionar</option>
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
                <input name="periodoinicio"  type="text" class="input_text w_100 center periodo" id="periodoinicio"     />
                </label>
                <label>
                <span>Data Final</span>
                <input name="periodofim"  type="text" class="input_text w_100 center periodo" id="periodofim"    />
                </label>
                </fieldset>
                </form>
        <div class="uk-modal-footer uk-text-right">
            <a href="JavaScript:void(0);" id="Btn_filtro_lancamento_00" class="uk-button uk-button-small uk-button-primary" ><i class="uk-icon-search" ></i> Pesquisar</a>
            <a href="JavaScript:void(0);" id="Btn_filtro_lancamento_01" class="uk-button uk-button-small uk-button-success" ><i class="uk-icon-print"></i> Imprimir</a>
            <a href="JavaScript:void(0);" id="Btn_filtro_lancamento_02" class="uk-button uk-button-danger uk-button-small uk-modal-close" ><i class="uk-icon-remove" ></i> Cancelar</a>
		</div>
</div>

<script type="text/javascript">

jQuery("#periodoinicio").mask("99/99/9999").attr("disabled", true);
jQuery("#periodofim").mask("99/99/9999").attr("disabled", true);


/********************************************************************/
jQuery("#periodo").change(function(){

var periodo = jQuery("#periodo").val();
// se a pesquisa for por periodo
if(periodo == 6){
jQuery("#periodoinicio").attr("disabled", false).focus();
jQuery("#periodofim").attr("disabled", false);

}else{
jQuery("#periodoinicio").attr("disabled", true);
jQuery("#periodofim").attr("disabled", true);
}
});
/********************************************************************/
jQuery("#Btn_filtro_lancamento_00").click(function (){

var conta  = jQuery("#cdcontabanco").val();// id da conta que sera pesquisado
var tipo   = jQuery("#tipo").val();// tipo se e entrada ou saida
var periodo= jQuery("#periodo").val();// periodo da pesquisa
var inicio = jQuery("#periodoinicio").val(); //data inicial
var fim    = jQuery("#periodofim").val();// data final

LoadContent('assets/financeiro/caixa/Grid_lancamentos.php?conta_id='+conta+'&tipo='+tipo+'&periodo='+periodo+'&inicio='+inicio+'&final='+fim+'','gridlancamentos');

});
/********************************************************************/

jQuery("#Btn_filtro_lancamento_01").click(function (){

jQuery("#Filtro_Lancamentos").slideToggle(200);

var conta=jQuery("#cdcontabanco").val();// id da conta que sera pesquisado
var tipo=jQuery("#tipo").val();// tipo se e entrada ou saida
var periodo=jQuery("#periodo").val();// periodo da pesquisa
var inicio=jQuery("#periodoinicio").val(); //data inicial
var fim=jQuery("#periodofim").val();// data final

//SE TUDO OCOOREU BEM ACIMA FAZ A FILTRAGEM

//New_window('print','960','550','Impressão de Relatorio','relatorios/financeiro/caixa/lancamentos.php?conta_id='+conta+'&tipo='+tipo+'&periodo='+periodo+'&inicio='+inicio+'&final='+fim+'',true,false.'Aguarde ...');

});
/********************************************************************/
</script>