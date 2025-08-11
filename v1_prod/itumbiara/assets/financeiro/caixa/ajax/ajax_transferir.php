	<div class="uk-modal-dialog" style="width:650px;">
		<!--<button type="button" class="uk-modal-close uk-close"></button> -->
		<div class="uk-modal-header" >
		<h2><i class="uk-icon-exchange uk-icon-small" ></i> Transferir</h2>
		</div>
            <form name="FrmTransferencia" method="post" id="FrmTransferencia" class="uk-form">
            <label>
            <span style="width: 150px;">Conta de Origem</span>
                <select name="contaorigem" class="select" id="contaorigem">
                    <?php
                        $query_conta=contas_bancarias::all(array('conditions'=>array('status= ? AND empresas_id= ?','1', $COB_Empresa_Id)));
                        $conta= new ArrayIterator($query_conta);
                        while($conta->valid()):
                        echo'<option value="'.$conta->current()->id.'" >'.utf8_encode($conta->current()->nm_conta).'</option>';
                        $conta->next();
                        endwhile;
                      ?>
                </select>
            </label>
            <label>
            <span style="width: 150px;">Conta de Destino</span>
                <select name="contadestino" class="select" id="contadestino">
                    <?php
                        $query_conta=contas_bancarias::all(array('conditions'=>array('status= ? AND empresas_id= ?','1', $COB_Empresa_Id)));
                        $conta= new ArrayIterator($query_conta);
                        while($conta->valid()):
                        echo'<option value="'.$conta->current()->id.'" >'.utf8_encode($conta->current()->nm_conta).'</option>';
                        $conta->next();
                        endwhile;
                      ?>
                </select>
            </label>
            <label>
            <span style="width: 150px;">Espêcie doc</span>
            <select name="fpagamento" class="select " id="fpagamento">
            <?php
            $formas=formas_recebimentos::find_by_sql("SELECT
                                                              formas_recebimento_sys.descricao,
                                                              formas_recebimentos.id
                                                            FROM
                                                              formas_recebimentos
                                                              INNER JOIN formas_recebimento_sys ON formas_recebimento_sys.id =
                                                            formas_recebimentos.formas_recebimento_sys_id
                                                            WHERE
                                                              formas_recebimentos.status = '1' AND   formas_recebimentos.empresas_id = '".$COB_Empresa_Id."'");

            $formas_list= new ArrayIterator($formas);
            while($formas_list->valid()):
            echo'<option value="'.$formas_list->current()->id.'" >'.utf8_encode($formas_list->current()->descricao).'</option>';
            $formas_list->next();
            endwhile;
             ?>
            </select>
            </label>
            <label>
            <span style="width: 150px;">Data</span>
            <input name="data"  type="text" class="input_text w_100 center " id="data" data-uk-datepicker="{format:'DD/MM/YYYY'}" />
            </label>
            <label>
            <span style="width: 150px;">Numero doc</span>
            <input name="numdoc"  type="text" class="input_text w_100 left " id="numdoc" />
            </label>
            <label>
            <span style="width: 150px;">Valor</span>
            <input name="valor"  type="text" class="input_text w_100 center " id="valor" />
            </label>
            <label>
            <span style="width: 150px;">Historico</span>
            <input name="historico"  type="text" class="input_text w_400 left " id="historico" />
            </label>

            </form>
        <div class="uk-modal-footer uk-text-right">
        <a href="JavaScript:void(0);" id="Btn_transferir_00" class="uk-button  uk-button-small uk-button-primary"  ><i class="uk-icon-exchange" ></i> Confirmar</a>
		<a href="JavaScript:void(0);" id="Btn_transferir_01" class="uk-button uk-button-danger  uk-button-small uk-modal-close" ><i class="uk-icon-remove" ></i> Cancelar</a>

		</div>
</div>
<script type="text/javascript">


jQuery("#data").mask("99/99/9999");
jQuery('#valor').maskMoney({prefix:'R$ ', thousands:'.', decimal:',', affixesStay: true});


jQuery(function() {

	jQuery("#Btn_transferir_00").click(function(event) {
		// mensagen de carregamento
		jQuery("#msg_loading").html(" Transferindo ");
		//abre a tela de preload
		modal.show();
		//desabilita o envento padrao do formulario
		event.preventDefault();

		jQuery.ajax({
				url: "assets/financeiro/caixa/Controller_transferencia.php",
				type: "post",
				data:$("#FrmTransferencia").serialize(),
				success: function(resultado) {
												if(jQuery.isNumeric(resultado)){

                                                        UIkit.notify('Transferencia realizada', {timeout: 2000,status:'success'});

														jQuery("#msg_loading").html(" Aguarde ...");

														LoadContent('assets/financeiro/caixa/Grid_lancamentos.php?conta_id='+jQuery("#contadestino").val()+'&periodo=0','gridlancamentos');
												}else{

														modal.hide();
														UIkit.modal.alert(""+resultado+"");
													}
				},
				error:function (){
					UIkit.modal.alert("Caminho Invalido!");
					}
			});
		});
});

</script>

