<div class="tabs-spacer" style="display:none;">
<?php
require_once"../../sessao.php";
include("../../conexao.php");
$cfg->set_model_directory('../../models/');

?>
</div>

<form method="post" id="FrmAddCidade" class="uk-form" style="padding: 10px 0; margin-top: 0; padding-top: 20px;">
    <label>
        <span >Estado</span>
        <select class="select w_250" name="nm_uf" id="nm_uf" >
            <?php
                echo'<option value="" >Selecionar Estado</option>';
                $descricaoestados=estados::find('all');
                $descricao= new ArrayIterator($descricaoestados);
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
<button class="uk-button uk-button-primary uk-button-small" type="button" id="Btn_add_cidade" style="right:10px;margin-right:5px; position:absolute;top:160px; "><i class="uk-icon-save"></i> Gravar Novo</button>

</form>


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
                    error:function (){
                        UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
                        modal.hide();
                    }

        });
    });

});

</script>