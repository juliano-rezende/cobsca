<?php
$FRM_matricula = isset($_GET['mat']) ? $_GET['mat'] : tool::msg_erros("O Campo matricula é Obrigatorio.");
$FRM_dados_cobranca = isset($_GET['dc']) ? $_GET['dc'] : tool::msg_erros("O Campo matricula é Obrigatorio.");
$FRM_api_cob_cliente_id = $_GET['a_c_id'];
?>
<style>
    #FrmAddCard label span{width: 150px;}
</style>
<form method="post" id="FrmAddCard" class="uk-form" style="margin: 0; padding: 0;" >
    <fieldset style="width:480px; margin: 0 0; padding-top:30px;">
        <label>
            <span>Nome impresso no Cartão</span>
            <input type="text" class="uk-text-center input_text w_250"  name="nm_cli" id="nm_cli"/>
        </label>
        <label>
            <span>Nº impresso no Cartão</span>
            <input type="text" class="uk-text-center input_text w_180"  name="n_cc" id="n_cc" placeholder="0000-0000-0000-0000"/>
        </label>
        <label>
            <span>Validade mês/ano</span>
            <input type="text" class="uk-text-center input_text w_50" name="vm" id="vm" placeholder="00"/> <input type="text" class="uk-text-center input_text w_80" name="vy" id="vy" placeholder="0000"/>
        </label>
        <label>
            <span>Cod Segurança</span>
            <input type="text" class="uk-text-center input_text w_80" name="c_s_cc" id="c_s_cc"/>
            <input type="hidden" name="dc" id="dc" value="<?=$FRM_dados_cobranca;?>"/>
            <input type="hidden" name="a_c_id" id="a_c_id" value="<?=$FRM_api_cob_cliente_id;?>"/>
        </label>
    </fieldset>
</form>

<a id="Btn_card" class="uk-button" style="position:absolute;; right:15px; bottom:30px; z-index:2;" data-uk-tooltip="{pos:'left'}" title="Confirmar dados" data-cached-title="Confirmar dados"><i class="uk-icon-check"></i> Cadastrar Cartão</a>
<script type="text/javascript">
    jQuery("#n_cc").mask("9999-9999-9999-9999");
    jQuery("#vm").mask("99");
    jQuery("#vy").mask("9999");

    /*envia o formulario para o controller*/
    jQuery(function () {

        jQuery("#Btn_card").click(function (event) {

            /*mensagen de carregamento*/
            jQuery("#msg_loading").html(" Aguarde... ");

            /*abre a tela de preload*/
            modal.show();

            //desabilita o envento padrao do formulario
            event.preventDefault();


            jQuery.ajax({
                type: "POST",
                async: true,
                url: "assets/faturamento/controllers/Controller_card.php",
                data: jQuery("#FrmAddCard").serialize(),
            }).done(function (resultado) {

                var text = '{"' + resultado + '"}';
                var obj = JSON.parse(text);

                if(obj.failCad == "true"){

                    var str = obj.message;
                    var res = str.replace("[br]", "<br>");

                    UIkit.modal.alert(""+res+"");
                    modal.hide();

                }else{

                    jQuery("#associados_card_id").html("<option>Aguarde...</option>");

                    jQuery.post( "assets/faturamento/controllers/Ajax_cards_associado.php?mat=<?=$FRM_matricula;?>", function( data ) {

                        jQuery("#associados_card_id").html(""+data+"");

                        UIkit.modal.alert("Cartão adcionado com sucesso. Para finalizar clique no botão Confirmar Dados.");
                        jQuery("#" + jQuery("#FrmAddCard").closest('.Window').attr('id') + "").fadeOut(500).remove();
                        modal.hide();



                    });
                }
            });
        });
    });
</script>