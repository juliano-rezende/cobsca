<div class="tabs-spacer" style="display:none;">
    <?php
    require_once "../../sessao.php";
    include("../../conexao.php");
    $cfg->set_model_directory('../../models/');

    $FRM_matricula = isset($_GET['matricula']) ? $_GET['matricula'] : tool::msg_erros("O Campo matricula é Obrigatorio.");

    $Query_convenio = associados::find_by_matricula($FRM_matricula);

    $Query_dadosCobranca = dados_cobranca::find_by_matricula($FRM_matricula);

    $FRM_convenio_id = $Query_convenio->convenios_id;

    ?>
</div>
<form method="post" id="FrmGeraParcelas" class="uk-form" style="margin: 0; padding: 0;">
    <?php  if ($Query_dadosCobranca->id > "0"): ?>
        <div class="uk-alert uk-alert-warning uk-text-center">
            Para Gerar Parcela com o vencimento escolhido pelo associado basta deixar o vencimento em branco.
        </div>
    <?php else: ?>
        <div class="uk-alert uk-alert-danger uk-text-center">
            Associado sem dados de cobrança.<br>Favor adcionar um dado de cobrança!
        </div>
    <?php endif; ?>

    <fieldset style="width:300px;">

        <label>
            <span>1º Vencimento</span>
            <input type="text" class="input_text w_120 uk-text-center" name="p_vencimento" id="p_vencimento" placeholder="00/00/0000" data-uk-datepicker="{format:'DD/MM/YYYY'}"/>
        </label>

        <label>
            <span>Qte Parcelas</span>
            <input type="number" id="nmparcelas" name="nmparcelas" min="0" max="12" value="1" class="uk-form-width uk-form-small w_50">
        </label>

    </fieldset>
</form>
<?php if ($Query_dadosCobranca->id > 0): ?>
    <a id="Btn_Gp_1" class="uk-button uk-button-primary" style="position:absolute;; right:15px; bottom:30px;line-height: 40px;" data-uk-tooltip="{pos:'left'}" title="Confirmar Parcelas" data-cached-title="Confirmar Parcelas"><i class="uk-icon-check"></i> Confimar</a>
<?php else: ?>
    <a id="Btn_fat_0" class="uk-button uk-button-success" style="position:absolute;; right:15px; bottom:30px;line-height: 40px;" data-uk-tooltip="{pos:'left'}" title="Adcionar dado de cobrança" data-cached-title="Adcionar dado de cobrança"><i class="uk-icon-plus"></i> Adcionar dados de cobrança</a>
<?php endif; ?>
<script type="text/javascript">

    // altera a cor de fundo do menu flutuante
    jQuery("#menu-float").css("background-color", "" + $("#" + $("#menu-float").closest('.Window').attr('id') + "").css('border-left-color') + "");

    jQuery(document).ready(function () {

        jQuery("#p_vencimento").mask("99/99/9999");

    });// fim document ready

    /*
        abre a janela de dados de cobrança
    */
    jQuery("#Btn_fat_0").click(function () {

        New_window('arquive', '500', '250', 'Dados de Cobrança', 'assets/faturamento/Frm_dados_cobranca.php?matricula=<?php echo $FRM_matricula; ?>&convenio_id=<?php echo $FRM_convenio_id; ?>', true, false, 'Carregando dados...');
    });

    // grava os dados no banco de dados

    jQuery(function () {

        jQuery("#Btn_Gp_1").click(function (event) {

            // mensagen de carregamento
            jQuery("#msg_loading").html("Gerando Parcelas ");

            //abre a tela de preload
            modal.show();

            //desabilita o envento padrao do formulario
            event.preventDefault();

            jQuery.ajax({
                async: true,
                url: "assets/faturamento/controllers/Controller_gera_parcelas.php",
                type: "post",
                data: 'matricula=<?php echo $FRM_matricula; ?>&' + jQuery("#FrmGeraParcelas").serialize(),
                success: function (resultado) {
                    //abre a tela de preload
                    UIkit.modal.alert("" + resultado + "");
                    /* id da janela dados de cobranca Frmdadoscobranca*/
                    jQuery("#" + jQuery("#FrmGeraParcelas").closest('.Window').attr('id') + "").fadeOut("slow").remove();
                    jQuery("#" + jQuery("#grid_faturamento").closest('.Window').attr('id') + "").fadeOut("slow").remove();
                    // recarrega a janela atualizando o valores
                    New_window('list', '950', '500', 'Faturamento', 'assets/faturamento/Frm_faturamento.php?matricula=<?php echo $FRM_matricula; ?>&convenio_id=<?php echo $Query_convenio->convenios_id; ?>', true, false, 'Carregando...');

                },
                error: function () {
                    UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
                    modal.hide();
                }
            });
        });
    });

</script>