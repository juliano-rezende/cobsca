<div class="uk-modal-dialog" style="width:500px;">
        <!--<button type="button" class="uk-modal-close uk-close"></button> -->
        <div class="uk-modal-header" >
            <h2><i class="uk-icon-filter uk-icon-street-view" ></i> Adcionar Logradouro</h2>
        </div>
          <form method="post" id="FrmAddlogradouro" class="uk-form" style="padding: 5px 0; margin-top: 0; padding-top: 10px;">
            <label>
                <span >Estado</span>
                <select class="select w_250" name="nm_uf_log" id="nm_uf_log" >
                    <?php
                        echo'<option value="" >Selecionar Estado</option>';
                        $query_estados=estados::find_by_sql("SELECT * FROM estados GROUP BY descricao");
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
                <select class="select w_250" name="nm_cidade_log" id="nm_cidade_log" >
                    <?php
                        echo'<option value="" >Aguardando ...</option>';
                    ?>
                    </select>
            </label>
            <label>
                <span >Bairro</span>
                <select class="select w_250" name="nm_bairro_log" id="nm_bairro_log" >
                    <option value="" >Aguardando ...</option>
                </select>
            </label>
            <label>
                <span>Complemento</span>
                    <select class="select w_250" name="nm_compl" id="nm_compl" >
                        <option value="RUA" >Rua</option><option value="AV" >Av</option><option value="PC" >Praça</option><option value="TRAV" >Travessa</option>
                    </select>
            </label>
             <label>
                <span>Logradouro</span>
                <input  type="text" class="w_250" name="nm_logradouro" id="nm_logradouro"  />
            </label>
             <label>
                <span>Cep</span>
                <div class="uk-form-icon">
                        <i class="uk-icon-street-view"></i>
                <input name="cep_new_log" class="w_100 uk-text-center" id="cep_new_log" maxlength="9"  >
                </div>
            </label>
        </form>

        <div class="uk-modal-footer uk-text-right">
            <a href="JavaScript:void(0);" id="Btn_add_logradouro" class="uk-button  uk-button-small uk-button-primary" ><i class="uk-icon-plus" ></i> Confirmar</a>
            <a href="JavaScript:void(0);"  class="uk-button uk-button-danger  uk-button-small uk-modal-close" ><i class="uk-icon-remove" ></i> Cancelar</a>
        </div>
</div>
</div>

<script type="text/javascript">

/* mascara para o cep*/
jQuery("#cep_new_log").mask("99.999-999");

// envia os dados para gerar os boleto ou receber
jQuery(function(){

    jQuery("#Btn_add_logradouro").click(function(event) {

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
                    data:"acao=logradouro&"+jQuery("#FrmAddlogradouro").serialize(),
                    success: function(resultado) {

                      // Exibimos no campo marca antes de concluirmos
                    jQuery("select[name=logradouro]").html(resultado);
                    jQuery("#"+jQuery("#FrmAddlogradouro").closest('.Window').attr('id')+"").remove();
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
    jQuery("select[name=nm_uf_log]").change(function(){

        if(jQuery(this).val() == ""){return false;}// retorna falso se for vazio

        // Exibimos no campo marca antes de concluirmos
        jQuery("select[name=nm_cidade_log]").html('<option value="">Carregando...</option>');

        // Passando tipo por parametro para a pagina ajax-marca.php
        jQuery.post("endereco/ajax_cidades.php",
        {cdestado:jQuery(this).val()},
        // Carregamos o resultado acima para o campo marca
        function(valor){
            jQuery("select[name=nm_cidade_log]").html(valor).focus().addClass( "uk-text-warning" );
        });
    });

    // popula os bairros
    jQuery("select[name=nm_cidade_log]").change(function(){

        if(jQuery(this).val() == ""){return false;}// retorna falso se for vazio

        // Exibimos no campo marca antes de concluirmos
        jQuery("select[name=nm_bairro_log]").html('<option value="">Carregando...</option>');

        // Passando tipo por parametro para a pagina ajax-marca.php
        jQuery.post("endereco/ajax_bairros.php",
        {cdcidade:jQuery(this).val()},
        // Carregamos o resultado acima para o campo marca
        function(valor){
            jQuery("select[name=nm_cidade_log]").removeClass( "uk-text-warning" );
            jQuery("select[name=nm_bairro_log]").html(valor).css("color","#0277bd").addClass( "uk-text-warning" );
        });
    });

</script>