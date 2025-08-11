<?php
require_once "../../../sessao.php";
include("../../../conexao.php");
$cfg->set_model_directory('../../../models/');
?>
<div class="tabs-spacer" style="display:none;">
    <?php
    //formas de recebimento do sistema
    $Query_frec = formas_recebimentos::find_by_sql("SELECT
                                              formas_recebimentos.id AS f_receb_emp_id,
                                              formas_recebimento_sys.descricao,
                                              formas_recebimentos.acrescimos,
                                              formas_recebimento_sys.id as f_recebe_sys_id
                                            FROM
                                              formas_recebimentos
                                              INNER JOIN formas_recebimento_sys ON formas_recebimento_sys.id =
                                            formas_recebimentos.formas_recebimento_sys_id
                                            WHERE
                                              formas_recebimentos.status = 1;");
    $formas = new ArrayIterator($Query_frec);
    ?>
</div>
<div id="DvFormConfirm" class="uk-modal">
    <div class="uk-modal-dialog" style="width:500px;">
        <!--<button type="button" class="uk-modal-close uk-close"></button> -->
        <div class="uk-modal-header ">
            <h2><i class="uk-icon-money uk-icon-small"></i> Recebimento</h2>
        </div>
        <form name="FrmBarcode" method="post" id="FrmBarcode" class="uk-form">
            <fieldset style=" width:450px;border:0; padding:0; padding-top:0px;">
                <label>
                    <span>Receber em?</span>
                    <select name="f_receb" id="f_receb" class="select w_250">
                        <option value="0" selected>Selecionar</option>
                        <?php
                        while ($formas->valid()) :
                            echo '<option value="' . $formas->current()->f_receb_emp_id.'" >' . utf8_encode(strtoupper($formas->current()->descricao)) . '</option>';
                            $formas->next();
                        endwhile;
                        ?>
                    </select>
                </label>
                <label>
                    <span>Juros e Multa?</span>
                    <select name="juros_multa" id="juros_multa" class="select w_250">
                        <option value="N" selected>NÂO</option>
                        <option value="S">SIM</option>
                    </select>
                </label>
            </fieldset>

        </form>
        <div class="uk-modal-footer uk-text-right">
            <a href="JavaScript:void(0);" id="BtnRec" class="uk-button uk-button-small uk-button-primary"><i class="uk-icon-barcode"></i> Receber</a>
            <a href="JavaScript:void(0);" id="Btn_tt_02" class="uk-button uk-button-danger uk-button-small uk-modal-close"><i class="uk-icon-remove"></i> Cancelar</a>
        </div>
    </div>
</div>
<nav class="uk-navbar uk-gradient-cinza">
    <form id="FrmBaixaTitulo">
        <div class="uk-navbar-content uk-hidden-small uk-form">
            <input type="text" id="search" name="search" autocomplete="off" class="uk-width uk-text-center" style="width:460px;" maxlength="60" placeholder="Digite somente números">
        </div>
        <div class="uk-navbar-content type_persona uk-text-warning">
            <input type="radio" name="tp" value="1" class="tp_search"> Associado PF
            <input type="radio" name="tp" value="2" class="tp_search"> Associado PJ

            <button type="submit" class="uk-button uk-button-primary" style="margin-left: 10px;">Pesquisar</button>
        </div>
    </form>
</nav>
<div id="Grid_titulos" style="height:415px; overflow-y:auto; overflow-x: hidden">
<span style="width:100%; display:block; text-align:center;">
Aguardando ...
</span>
</div>
<nav class="uk-navbar" style="padding:10px;">
    <button type="button" class="uk-button uk-button-primary uk-float-right" data-uk-modal="{target:'#DvFormConfirm'}" style="margin-top: -5px;">Baixar boleto</button>
</nav>
<script type="text/javascript">
    $(".type_persona").hide();
    jQuery("#search").attr("maxlength", "35").focus();

    jQuery("#search").click(function () {

         //2112.03.0254.1221.003200
        jQuery("#search").unmask("9999.99.9999.9999.99999");
        jQuery("#search").unmask("9999.99.9999.9999.999999");
        jQuery("#search").unmask("9999.99.99999999999.9999.99999999");
        jQuery("#search").unmask("9.9999.99.99999999999.9999.99999999");
        jQuery("#search").val($(this).val().replace(/\D/g, ''));
    });

    $("#FrmBaixaTitulo").submit(function (e) {
        // 21120300000001198122100002600
        e.preventDefault();
        var form = $(this);
        var vl = jQuery("#search").val();
        var data = form.serialize();
        var length = vl.length;
        checado = $(".tp_search").is(":checked");
        if (length < 19) {
            UIkit.modal.alert("Linha digitável invalida! Pagamento não permitido.");/*erro de caminho invalido do arquivo*/
            jQuery("#search").focus()
            return false;
        }
        if (length > 35) {
            UIkit.modal.alert("Linha digitável invalida! Pagamento não permitido.");/*erro de caminho invalido do arquivo*/
            jQuery("#search").focus()
            return false;
        }
        if (length == 19 && !checado) {
            jQuery("#search").mask("9999.99.9999.9999.99999");
            $(".type_persona").show();
            UIkit.modal.alert('<div class="uk-alert uk-alert-danger">Opss, para que possamos realizar a busca por este titulo por favor nos informe o tipo de associado</div>');/*erro de caminho invalido do arquivo*/
            return false;
        }
        if (length == 20 && !checado) {
            jQuery("#search").mask("9999.99.9999.9999.999999");
            $(".type_persona").show();
            UIkit.modal.alert('<div class="uk-alert uk-alert-danger">Opss, para que possamos realizar a busca por este titulo por favor nos informe o tipo de associado</div>');/*erro de caminho invalido do arquivo*/
            return false;
        }
        if (length == 29 && !checado) {
            jQuery("#search").mask("9999.99.99999999999.9999.99999999").focus();
            $(".type_persona").show();
            UIkit.modal.alert('<div class="uk-alert uk-alert-danger">Opss, para que possamos realizar a busca por este titulo por favor nos informe o tipo de associado</div>');/*erro de caminho invalido do arquivo*/
            return false;
        }
        if (length == 30) {
            jQuery("#search").mask("9.9999.99.99999999999.9999.99999999").focus();
        }
        $.ajax({
            url: "assets/cobranca/baixa_titulo/Veiw_search_titulos_barcode.php",
            type: "POST",
            data: data,
            async: true,
            beforeSend: function () {
                modal.show();
            }, success: function (callback) {
                $("#Grid_titulos").html(callback);
                modal.hide();
            }
        });
    });


    /*
       pega todas as parcelas selecionadas para recebimento
   */
    jQuery("#BtnRec").click(function () {

        var ids = [];// array com os valores dos checks

        jQuery("#Grid_titulos input[type=checkbox]").each(function () {// verifica qual está marcado dentro da grid faturamento apenas

            if (this.checked) {
                ids.push(jQuery(this).val());// retorna o valor do campo marcado
            }
        });

        var databarcode  = $("#FrmBarcode").serialize();

        $.ajax({
            type: "POST",
            url: "assets/cobranca/baixa_titulo/baixa_titulo_barcode.php",
            data: "&ids=" + ids+"&"+databarcode,
            success: function (callback) {

                var text = '{"' + callback + '"}';
                var obj = JSON.parse(text);

                // se o callback for 0 indica que não houve erro ai mostramos o resultado da execução das querys
                if (obj.callback == 0) {

                    $(".uk-modal-close").trigger("click");
                    // New_window('exclamation-triangle', '500', '250', 'Atenção', '<div style="padding: 5px; width:490px; height:240px; overflow-x:auto;">' + obj.msg + '</div>', true, true, 'Aguarde...');
                    UIkit.notify('' + obj.msg + '', {
                        timeout: 2000,
                        status: '' + obj.status + ''
                    });

                    jQuery("#search").unmask("9999.99.99999999999.9999.99999999");
                    jQuery("#search").unmask("9.9999.99.99999999999.9999.99999999");
                    jQuery("#search").val("").focus();

                    jQuery("#Grid_titulos").html('<span style="width:100%; display:block; text-align:center; padding-top: 20px;">Aguardando nova pesquisa.</span>');

                    console.log("Ok")
                    // se for = 1 indica que houve erro ai retornamo o erro na tela do usuario
                } else {

                    UIkit.notify('' + obj.msg + '', {
                        timeout: 2000,
                        status: '' + obj.status + ''
                    });
                    return false;
                }

            }
        });


    });
</script>