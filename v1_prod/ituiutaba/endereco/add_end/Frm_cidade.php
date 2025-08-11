<div class="uk-modal-dialog" style="width:500px;">
        <!--<button type="button" class="uk-modal-close uk-close"></button> -->
        <div class="uk-modal-header" >
            <h2><i class="uk-icon-filter uk-icon-street-view" ></i> Adcionar Cidade</h2>
        </div>
           <form method="post" id="FrmAddCidade" class="uk-form" style="padding: 5px 0; margin-top: 0; padding-top: 10px;">
            <label>
                <span >Estado</span>
                <select class="select w_250" name="nm_uf_cid" id="nm_uf_cid" >
                    <?php
                        echo'<option value="" >Selecionar Estado</option>';
                        $query_estados=estados::find_by_sql("SELECT * FROM estados GROUP BY descricao");
                        $descricao= new ArrayIterator($query_estados);
                        while($descricao->valid()):
                            echo'<option value="'.$descricao->current()->id.'" >'.strtoupper(utf8_encode($descricao->current()->descricao)).'</option>';
                            $descricao->next();
                        endwhile;
                    ?>
                    </select>
            <label>
                <span>Cidade</span>
                <input  type="text" class="w_250" name="nm_cidade" id="nm_cidade"  />
            </label>

        </form>

        <div class="uk-modal-footer uk-text-right">
            <a href="JavaScript:void(0);" id="Btn_add_cidade" class="uk-button  uk-button-small uk-button-primary" ><i class="uk-icon-plus" ></i> Confirmar</a>
            <a href="JavaScript:void(0);"  class="uk-button uk-button-danger  uk-button-small uk-modal-close" ><i class="uk-icon-remove" ></i> Cancelar</a>
        </div>
</div>
</div>



<script type="text/javascript">

// envia os dados para gerar os boleto ou receber
jQuery(function(){

    jQuery("#Btn_add_cidade").click(function(event) {

        // mensagen de carregamento
        jQuery("#msg_loading").html("Aguarde...");

        //abre a tela de preload
        modal.show();

        //desabilita o envento padrao do formulario
        event.preventDefault();

        jQuery.ajax({
                   async: true,
                    url: "endereco/add_end/Controller_add_end.php",
                    type: "post",
                    data:"acao=cidade&"+jQuery("#FrmAddCidade").serialize(),
                    success: function(resultado) {

                      // Exibimos no campo marca antes de concluirmos
                    jQuery("select[name=cidade]").html(resultado);
                    jQuery("#"+jQuery("#FrmAddCidade").closest('.Window').attr('id')+"").remove();
                    modal.hide();

                    },
                    error:function (error){
                        
                        UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
                        modal.hide();
                    }

        });
    });

});

</script>