<?php require_once"../../sessao.php"; ?>

<form method="post" id="FrmAddUf" class="uk-form" style="padding: 10px 0; margin-top: 0; padding-top: 20px;">
    <label>
        <span >Estado</span>
        <select class="select w_200" name="nm_uf" id="nm_uf" >
            <option value="0" selected></option>
            <option value="AC">Acre</option>
            <option value="AL">Alagoas</option>
            <option value="AP">Amapá</option>
            <option value="AM">Amazonas</option>
            <option value="BA">Bahia</option>
            <option value="CE">Ceará</option>
            <option value="DF">Distrito Federal</option>
            <option value="ES">Espírito Santo</option>
            <option value="GO">Goiás</option>
            <option value="MA">Maranhão</option>
            <option value="MT">Mato Grosso</option>
            <option value="MS">Mato Grosso do Sul</option>
            <option value="MG">Minas Gerais</option>
            <option value="PA">Pará</option>
            <option value="PB">Paraíba</option>
            <option value="PR">Paraná</option>
            <option value="PE">Pernambuco</option>
            <option value="PI">Piauí</option>
            <option value="RJ">Rio de Janeiro</option>
            <option value="RN">Rio Grande do Norte</option>
            <option value="RS">Rio Grande do Sul</option>
            <option value="RO">Rondônia</option>
            <option value="RR">Roraima</option>
            <option value="SC">Santa Catarina</option>
            <option value="SP">São Paulo</option>
            <option value="SE">Sergipe</option>
            <option value="TO">Tocantins</option>
            </select>
    </label>


<button class="uk-button uk-button-primary uk-button-small" type="button" id="Btn_add_uf" style="right:10px;margin-right:5px; position:absolute;top:150px; "><i class="uk-icon-save"></i> Gravar Novo</button>

</form>


<script type="text/javascript">

// envia os dados para gerar os boleto ou receber
jQuery(function(){

    jQuery("#Btn_add_uf").click(function(event) {

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
                    data:"acao=uf&"+jQuery("#FrmAddUf").serialize(),
                    success: function(resultado) {

                      // Exibimos no campo marca antes de concluirmos
                    jQuery("select[name=uf]").html(resultado);
                    jQuery("#"+jQuery("#FrmAddUf").closest('.Window').attr('id')+"").remove();
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