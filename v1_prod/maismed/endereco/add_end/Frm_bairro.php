<div class="uk-modal-dialog" style="width:500px;">
        <!--<button type="button" class="uk-modal-close uk-close"></button> -->
        <div class="uk-modal-header" >
            <h2><i class="uk-icon-filter uk-icon-street-view" ></i> Adcionar Bairro</h2>
        </div>
         <form method="post" id="FrmAddBairro" class="uk-form" style="padding: 5px 0; margin-top: 0; padding-top: 10px;">
            <label>
                <span >Estado</span>
                <select class="select w_250" name="nm_uf_bai" id="nm_uf_bai" >
                    <?php
                        echo'<option value="" >Selecionar Estado</option>';
                        $query_estados=estados::find('all');
                        $desc_estados= new ArrayIterator($query_estados);
                        while($desc_estados->valid()):
                            echo'<option value="'.$desc_estados->current()->id.'" >'.strtoupper(utf8_encode($desc_estados->current()->descricao)).'</option>';
                            $desc_estados->next();
                        endwhile;
                    ?>
                    </select>
            </label>
            <label>
                <span >Cidades</span>
                <select class="select w_250" name="nm_cidade_bai" id="nm_cidade_bai" >
                    <?php
                        echo'<option value="" >Aguardando...</option>';
                    ?>
                    </select>
            </label>
            <label>
                <span>Bairro</span>
                <input  type="text" class="w_250" name="nm_bairro" id="nm_bairro"  />
            </label>

        </form>

        <div class="uk-modal-footer uk-text-right">
            <a href="JavaScript:void(0);" id="Btn_add_bairro" class="uk-button  uk-button-small uk-button-primary" ><i class="uk-icon-plus" ></i> Confirmar</a>
            <a href="JavaScript:void(0);"  class="uk-button uk-button-danger  uk-button-small uk-modal-close" ><i class="uk-icon-remove" ></i> Cancelar</a>
        </div>
</div>
</div>




<script type="text/javascript">

// envia os dados para gerar os boleto ou receber
jQuery(function(){

    jQuery("#Btn_add_bairro").click(function(event) {

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
                    data:"acao=bairro&"+jQuery("#FrmAddBairro").serialize(),
                    success: function(resultado) {

                      // Exibimos no campo marca antes de concluirmos
                    jQuery("select[name=bairro]").html(resultado);
                    jQuery("#"+jQuery("#FrmAddBairro").closest('.Window').attr('id')+"").remove();
                    modal.hide();

                    },
                    error:function (){
                        UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
                        modal.hide();
                    }

        });
    });

});

    // popula as cidades
    jQuery("select[name=nm_uf_bai]").change(function(){

        if(jQuery(this).val() == ""){return false;}// retorna falso se for vazio

        // Exibimos no campo marca antes de concluirmos
        jQuery("select[name=nm_cidade_bai]").html('<option value="">Carregando...</option>');

        // Passando tipo por parametro para a pagina ajax-marca.php
        jQuery.post("endereco/ajax_cidades.php",
        {cdestado:jQuery(this).val()},
        // Carregamos o resultado acima para o campo marca
        function(valor){
            jQuery("select[name=nm_cidade_bai]").html(valor).focus().addClass( "uk-text-warning" );
        });
    });
</script>