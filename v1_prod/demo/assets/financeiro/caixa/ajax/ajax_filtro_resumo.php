<div class="uk-modal-dialog" style="width:500px;">
		<!--<button type="button" class="uk-modal-close uk-close"></button> -->
		<div class="uk-modal-header ">
		<h2><i class="uk-icon-filter uk-icon-small" ></i> Filtro</h2>
		</div>
                <form name="fr_filtro_recebimentos" method="post" id="fr_filtro_resumo" class="uk-form">
                <fieldset style=" width:450px;border:0; padding:0; padding-top:0px;">
                    <label style="display: none;">
                        <span>Operador</span>
                        <select name="operador" class="select" id="operador">
                            <option value="" selected="selected">Todos</option>
                            <?php
                            $query_c = users::find_by_sql("SELECT * FROM usuarios WHERE empresas_id='" . $COB_Empresa_Id . "' AND acessos_id < '4' AND status = '1' ORDER BY id ASC ");
                            $usuarios = new ArrayIterator($query_c);
                            while ($usuarios->valid()) :
                                echo '<option value="' . $usuarios->current()->id . '" >' . utf8_encode($usuarios->current()->nm_usuario) . '</option>';
                                $usuarios->next();
                            endwhile;
                            ?>
                        </select>
                    </label>
                <label>
                <span>Periodo</span>
                    <select name="periodo" id="periodo" class="camposdigitaveis border_radios3 select ">
                      <option value="0" selected="selected">Selecionar</option>
                      <option value="7">Hoje</option>
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
            <a href="JavaScript:void(0);" id="Btn_filtro_res_00" class="uk-button uk-button-small uk-button-primary" ><i class="uk-icon-search" ></i> Pesquisar</a>
            <a href="JavaScript:void(0);" id="Btn_filtro_res_02" class="uk-button uk-button-danger uk-button-small uk-modal-close" ><i class="uk-icon-remove" ></i> Cancelar</a>
		</div>
</div>
<script type="text/javascript" src="js/jquery/plugins/jquery_print/jquery.printElement.min.js"></script>
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
jQuery("#Btn_filtro_res_00").click(function (){

var periodo= jQuery("#periodo").val();// periodo da pesquisa
var inicio = jQuery("#periodoinicio").val(); //data inicial
var fim    = jQuery("#periodofim").val();// data final
var operador = "";

// LoadContent('assets/financeiro/caixa/Grid_resumo.php?operador='+operador+'&periodo='+periodo+'&inicio='+inicio+'&final='+fim+'','gridlancamentos');

    jQuery(".Window").remove(); //  fecha demais janelas abertas para prevenir falhas

    New_window('list','900','550','Resumos de Recebimentos','assets/financeiro/caixa/Grid_resumo.php?operador='+operador+'&periodo='+periodo+'&inicio='+inicio+'&final='+fim+'',true,false,'Aguarde ...');// abre a janela atualizada

});
/********************************************************************/

jQuery("#Btn_filtro_res_01").click(function (){

var periodo= jQuery("#periodo").val();// periodo da pesquisa
var inicio = jQuery("#periodoinicio").val(); //data inicial
var fim    = jQuery("#periodofim").val();// data final
var operador = "";
jQuery( "#gridlancamentos" ).load( 'assets/financeiro/caixa/Grid_resumo.php?operador='+operador+'&periodo='+periodo+'&inicio='+inicio+'&final='+fim+'', function() {
  jQuery("#gridlancamentos").print();
});




});
/********************************************************************/
</script>