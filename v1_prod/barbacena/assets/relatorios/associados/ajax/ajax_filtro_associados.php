<div class="uk-modal-dialog" style="width:500px;">
		<!--<button type="button" class="uk-modal-close uk-close"></button> -->
		<div class="uk-modal-header ">
		<h2><i class="uk-icon-filter uk-icon-small" ></i> Filtro</h2>
		</div>
                <form name="FrmFiltroAssociados" method="post" id="FrmFiltroAssociados" class="uk-form">
                <fieldset style=" width:450px;border:0; padding:0; padding-top:0px;">

                <label >  <!--pesquisar por status -->
                <span>Situação</span>
                <select name="status" class="select " id="status">
                    <option value="" selected ></option>
                    <option value="1" >Ativos</option>
                    <option value="2" >Cancelados</option>
                    <option value="3" >Novos</option>
                    <option value="4" >Informados ao SPC</option>
                </select>
                </label>
                <label id="lab02"><!--pesquisar por convenio -->
                <span>Convênio</span>
                <select name="convenio_id" class="select" id="convenio_id" >
                <option value="0" selected>Todos</option>
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
                <span>Ordenar por?</span>
                <select name="order" id="order" class="select ">
                <option value="matricula" selected="selected">Matricula</option>
                <option value="nm_associado" >Nome</option>
                <option value="dt_cadastro" >Data de Cadastro</option>
                <option value="convenios_id" >Convênio</option>
                </select>
                </label>

                <label>
                <span>Data Inicial</span>
                <div class="uk-form-icon">
                <i class="uk-icon-calendar"></i>
                <input name="dtini" value="<?php echo date("d/m/Y"); ?>" type="text" class="input_text w_100 center periodo" id="dtini" data-uk-datepicker="{format:'DD/MM/YYYY'}"    />
                </div>
                </label>

                <label>
                <span>Data Final</span>
                <div class="uk-form-icon">
                <i class="uk-icon-calendar"></i>
                <input name="dtfim" value="<?php echo date("d/m/Y"); ?>" type="text" class="input_text w_100 center periodo" id="dtfim" data-uk-datepicker="{format:'DD/MM/YYYY'}"   />
                </div>
                </label>

                </fieldset>

                </form>
        <div class="uk-modal-footer uk-text-right">
            <a href="JavaScript:void(0);" id="Btn_tt_00" class="uk-button uk-button-small" ><i class="uk-icon-search" ></i> Visualizar</a>
            <a href="JavaScript:void(0);" id="Btn_tt_02" class="uk-button uk-button-danger uk-button-small uk-modal-close" ><i class="uk-icon-remove" ></i> Cancelar</a>
		</div>
</div>

<script type="text/javascript">

jQuery("#dtini,#dtfim").mask("99/99/9999");

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
				url: "assets/relatorios/associados/ajax_grid_associados.php",
				type: "post",
				data:jQuery("#FrmFiltroAssociados").serialize(),
				success: function(resultado) {
														//abre a tela de preload
                           jQuery("#Grid_associados").html(resultado);
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

jQuery("#status").change(function(){

if(jQuery().val() == 4){
jQuery("#dtini, #dtfim").attr('readonly="readonly"');

}else{jQuery("#dtini, #dtfim").removeAttr('readonly="readonly"');}
});
</script>