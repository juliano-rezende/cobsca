<div class="tabs-spacer" style="display:none;">
<?php
require_once"../../sessao.php";
include("../../conexao.php");
$cfg->set_model_directory('../../models/');

?>
</div>

<form method="post" id="FrmAddlogradouro" class="uk-form" style="padding: 10px 0; margin-top: 0; padding-top: 20px;">
    <label>
        <span >Estado</span>
        <select class="select w_250" name="nm_uf" id="nm_uf" >
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
        <select class="select w_250" name="nm_cidade" id="nm_cidade" >
            <?php
                echo'<option value="" >Selecionar Cidade</option>';
                $query_cidades=cidades::find('all');
                $desc_cidades= new ArrayIterator($query_cidades);
                while($desc_cidades->valid()):
                    echo'<option value="'.$desc_cidades->current()->id.'" >'.strtoupper(utf8_encode($desc_cidades->current()->descricao)).'</option>';
                    $desc_cidades->next();
                endwhile;
            ?>
            </select>
    </label>
    <label>
        <span >Bairro</span>
        <select class="select w_250" name="nm_bairro" id="nm_bairro" >
            <option value="" >Selecionar Bairro</option>
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
<button class="uk-button uk-button-primary uk-button-small" type="button" id="Btn_add_logradouro" style="right:10px;margin-right:5px; position:absolute;top:270px; "><i class="uk-icon-save"></i> Gravar Novo</button>
</form>


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
    jQuery("select[name=nm_uf]").change(function(){

        if(jQuery(this).val() == ""){return false;}// retorna falso se for vazio

        // Exibimos no campo marca antes de concluirmos
        jQuery("select[name=nm_cidade]").html('<option value="">Carregando...</option>');

        // Passando tipo por parametro para a pagina ajax-marca.php
        jQuery.post("endereco/ajax_cidades.php",
        {cdestado:jQuery(this).val()},
        // Carregamos o resultado acima para o campo marca
        function(valor){
            jQuery("select[name=nm_cidade]").html(valor).focus().addClass( "uk-text-warning" );
        });
    });

    // popula os bairros
    jQuery("select[name=nm_cidade]").change(function(){

        if(jQuery(this).val() == ""){return false;}// retorna falso se for vazio

        // Exibimos no campo marca antes de concluirmos
        jQuery("select[name=nm_bairro]").html('<option value="">Carregando...</option>');

        // Passando tipo por parametro para a pagina ajax-marca.php
        jQuery.post("endereco/ajax_bairros.php",
        {cdcidade:jQuery(this).val()},
        // Carregamos o resultado acima para o campo marca
        function(valor){
            jQuery("select[name=nm_cidade]").removeClass( "uk-text-warning" );
            jQuery("select[name=nm_bairro]").html(valor).css("color","#0277bd").addClass( "uk-text-warning" );
        });
    });

</script>