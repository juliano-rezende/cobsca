<div class="uk-modal-dialog" style="width:500px;">
		<!--<button type="button" class="uk-modal-close uk-close"></button> -->
		<div class="uk-modal-header ">
		<h2><i class="uk-icon-filter uk-icon-small" ></i> Filtro</h2>
		</div>
                <form name="FrmFiltroRecebimentos" method="post" id="FrmFiltroRecebimentos" class="uk-form">
                <fieldset style=" width:450px;border:0; padding:0; padding-top:0px;">
                    <input name="print_ok" value="0"  type="hidden" id="print_ok"/>

                <label>
                <span>Pesquisar por</span>
                <select name="pesquisarpor" class="select " id="pesquisarpor">
                    <option value="" selected="selected"></option>
                    <option value="0">Data da Vencimento</option>
                    <option value="1">Data da Pagamento</option>
                </select>
                </label>
                <label>
                <span>Data Inicial</span>
                <div class="uk-form-icon">
                <i class="uk-icon-calendar"></i>
                <input name="dtini" value="<?php echo date("d/m/Y"); ?>"  type="text" class="input_text w_100 center periodo" id="dtini" data-uk-datepicker="{format:'DD/MM/YYYY'}"    />
                </div>
                </label>
                <label>
                <span>Data Final</span>
                <div class="uk-form-icon">
                <i class="uk-icon-calendar"></i>
                <input name="dtfim" value="<?php echo date("d/m/Y"); ?>"  type="text" class="input_text w_100 center periodo" id="dtfim" data-uk-datepicker="{format:'DD/MM/YYYY'}"   />
                </div>
                </label>

                </fieldset>

                </form>
        <div class="uk-modal-footer uk-text-right">
            <a href="JavaScript:void(0);" id="Btn_tt_00" class="uk-button uk-button-small"><i class="uk-icon-search" ></i> Visualizar</a>
            <a href="JavaScript:void(0);" id="Btn_tt_01" class="uk-button uk-button-small"><i class="uk-icon-print" ></i> Imprimir</a>
            <a href="JavaScript:void(0);" id="Btn_tt_02" class="uk-button uk-button-danger uk-button-small uk-modal-close" ><i class="uk-icon-remove" ></i> Cancelar</a>
		</div>
</div>

<script type="text/javascript">

jQuery("#dtini,#dtfim").mask("99/99/9999");
jQuery("#lab00").hide();

jQuery(function() {

 jQuery("#Btn_tt_00").click(function(event) {

 // mensagen de carregamento
 jQuery("#msg_loading").html("Pesquisando ");

 //abre a tela de preload
 modal.show();

 //desabilita o envento padrao do formulario
 event.preventDefault();

 jQuery.ajax({
				async: true,
				url: "assets/relatorios/recebimentos/ajax_grid_recebimentos.php",
				type: "post",
				data:jQuery("#FrmFiltroRecebimentos").serialize(),
				success: function(resultado) {
														//abre a tela de preload
                           jQuery("#Grid_recebimentos").html(resultado);
                            //abre a tela de preload
							modal.hide();
				},
				error:function (){
					UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
					modal.hide();
					}

			});

		});

});

jQuery(function() {

 jQuery("#Btn_tt_01").click(function(event) {

    var print_ok = jQuery("#print_ok").val();

if(print_ok == 0){UIkit.modal.alert("Só é possivel imprimir apos visualização em tela.");/*erro de caminho invalido do arquivo*/
}else{jQuery( "#Grid_recebimentos" ).print();}


});
});


</script>