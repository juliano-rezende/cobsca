<div class="uk-modal-dialog" style="width:500px;">
		<!--<button type="button" class="uk-modal-close uk-close"></button> -->
		<div class="uk-modal-header ">
		<h2><i class="uk-icon-filter uk-icon-small" ></i> Filtro</h2>
		</div>
                <form name="FrmFiltroConvenios" method="post" id="FrmFiltroConvenios" class="uk-form">
                <fieldset style=" width:450px;border:0; padding:0; padding-top:0px;">

                <label>
                <span>Convênio</span>
                <select name="conv_id" class="select" id="conv_id" >
                <?php
                    $convenio=convenios::find('all',array('conditions'=>array('status= ? AND empresas_id= ?','1', $COB_Empresa_Id)));
                    $listaconvenios= new ArrayIterator($convenio);
                    while($listaconvenios->valid()):
                    echo'<option value="'.$listaconvenios->current()->id.'" >'.utf8_encode($listaconvenios->current()->nm_fantasia).'</option>';
                    $listaconvenios->next();
                    endwhile;
                ?>
                </select>
                </label>

                <label>
                <span>Referência</span>
                    <input  type="text" class="w_100 uk-text-center" name="ref" id="ref" placeholder="MES/ANO"/>
                </label>
                </fieldset>

                </form>
        <div class="uk-modal-footer uk-text-right">
            <a href="JavaScript:void(0);" id="Btn_tt_00" class="uk-button uk-button-small" ><i class="uk-icon-search" ></i> Visualizar</a>
            <a href="JavaScript:void(0);" id="Btn_tt_01" class="uk-button uk-button-small" ><i class="uk-icon-print" ></i> Imprimir</a>
            <a href="JavaScript:void(0);" id="Btn_tt_02" class="uk-button uk-button-danger uk-button-small uk-modal-close" ><i class="uk-icon-remove" ></i> Cancelar</a>
		</div>
</div>

<script type="text/javascript">


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
				url: "assets/relatorios/fatconvenios/ajax_grid_convenios.php",
				type: "post",
				data:jQuery("#FrmFiltroConvenios").serialize(),
				success: function(resultado) {
														//abre a tela de preload
                           jQuery("#Grid_convenios").html(resultado);
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

 var LeftPosition = (screen.width) ? (screen.width-980)/2 : 0;

 var convenios_id = jQuery("#conv_id").val();
 var referencia   = jQuery("#ref").val();

 window.open("<?php echo dirname($_SERVER['PHP_SELF']); ?>/print_pdf.php?action=&conv_id="+convenios_id+"&ref="+referencia+"","Extrato de convênios");


});


});


</script>