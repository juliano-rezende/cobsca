<div class="tabs-spacer" style="display:none;">
    <?php
    require_once "../../sessao.php";
    include("../../conexao.php");
    $cfg->set_model_directory('../../models/');

    $FRM_matricula = isset($_GET['mat']) ? $_GET['mat'] : tool::msg_erros("O Campo matricula é Obrigatorio.");
    $FRM_vlrcob = isset($_GET['vlrcob']) ? $_GET['vlrcob'] : tool::msg_erros("O Campo vlrcob é Obrigatorio.");

    $assoc = associados::find($FRM_matricula);
    $identificador = $assoc->empresas_id.".".$assoc->convenios_id.".".$assoc->matricula;

    ?>
</div>

<style>
    #FrmAddCardCob label span{width: 150px;}
</style>
<form method="post" id="FrmAddCardCob" class="uk-form" style="margin: 0; padding: 0;" >
    <fieldset style="width:480px; margin: 0 0; padding-top:20px;">
        <label>
            <span>Identificador</span>
            <input type="text" class="uk-text-center input_text w_150 uk-" readonly  name="nm_cli" id="nm_cli" value="<?=$identificador;?>"/>
        </label>
        <label>
            <span>Valor a Cobrar</span>
            <input type="text" class="uk-text-center input_text w_150" readonly  name="vlr" id="vlr" value="<?=$FRM_vlrcob;?>"/>
        </label>
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
            <input type="hidden" name="mat" id="mat" value="<?=$FRM_matricula;?>"/>
            <input type="hidden" name="vlcob" id="vlcob" value="<?=$FRM_vlrcob;?>"/>
        </label>
    </fieldset>
</form>

<a id="Btn_card" class="uk-button uk-button-success" style="position:absolute;; right:15px; bottom:30px; z-index:2;" data-uk-tooltip="{pos:'left'}" title="Confirmar Recebimento" data-cached-title="Confirmar Recebimento"><i class="uk-icon-check"></i> Confirmar Recebimento</a>
<script type="text/javascript">

    jQuery("#n_cc").mask("9999-9999-9999-9999");
    jQuery("#vm").mask("99");
    jQuery("#vy").mask("9999");

    /*envia o formulario para o controller*/
    jQuery(function () {

        jQuery("#Btn_card").click(function (event) {

            alert("Aguarde ainda em implementação."); return false;

            /*mensagen de carregamento*/
            jQuery("#msg_loading").html(" Aguarde... ");

            /*abre a tela de preload*/
            modal.show();

            //desabilita o envento padrao do formulario
            event.preventDefault();

            jQuery.ajax({
                type: "POST",
                async: true,
                url: "assets/faturamento/controllers/Controller_transacao_card.php",
                data: jQuery("#FrmAddCardCob").serialize(),
            }).done(function (resultado) {

                var text = '{"' + resultado + '"}';
                var obj = JSON.parse(text);

                if(obj.failCad == "true"){

                    UIkit.modal.alert(""+obj.message+"");
                    modal.hide();

                }else{

                    jQuery("#associados_card_id").html("<option>Aguarde...</option>");

                    jQuery.post( "assets/faturamento/controllers/Ajax_cards_associado.php?mat=<?=$FRM_matricula;?>", function( data ) {

                        jQuery("#associados_card_id").html(""+data+"");

                        UIkit.modal.alert("Pagamento Realizado Com Sucesso.");
                        jQuery("#" + jQuery("#FrmAddCardCob").closest('.Window').attr('id') + "").fadeOut(500).remove();
                        modal.hide();



                    });
                }
            });
        });
    });
</script>