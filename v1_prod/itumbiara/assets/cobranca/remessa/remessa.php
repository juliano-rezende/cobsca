<div class="tabs-spacer" style="display:none;">
   <?php
   require_once("../../../sessao.php");
   require_once("../../../conexao.php");
   $cfg->set_model_directory('../../../models/');
   ?>
</div>
<style>
    .group_button a {
        border-radius: 0;
        border: 0;
    }
</style>
<div id="Form_0" class="uk-modal">
    <div class="uk-modal-dialog" style="width:500px;">
        <!--<button type="button" class="uk-modal-close uk-close"></button> -->
        <div class="uk-modal-header ">
            <h2><i class="uk-icon-bank  uk-icon-small"></i> Filtrar Banco</h2>
        </div>
        <form name="FrmFiltroRemessa" method="post" id="FrmFiltroRemessa" class="uk-form">
            <fieldset style=" width:450px;border:0; padding:0; padding-top:0px;">
                <label id="lab00">  <!--pesquisar por conta bancaria -->
                    <span>Banco</span> <select name="cod_banco_grid" class="select w_300" id="cod_banco_grid">
                        <option value="" selected></option>
                      <?php
                      $contas = contas_bancarias::find_by_sql("SELECT contas_bancarias.cod_banco,contas_bancarias.nm_conta,contas_bancarias.id
                                                     FROM contas_bancarias
                                                     LEFT JOIN contas_bancarias_cobs ON contas_bancarias_cobs.contas_bancarias_id = contas_bancarias.id
                                                     WHERE contas_bancarias_cobs.carteira_remessa !='0'");
                      $listcontas = new ArrayIterator($contas);
                      while ($listcontas->valid()):
                         echo '<option value="' . $listcontas->current()->cod_banco . '" >' . $listcontas->current()->id . " " . $listcontas->current()->cod_banco . " " . utf8_encode($listcontas->current()->nm_conta) . '</option>';
                         $listcontas->next();
                      endwhile;
                      ?>
                    </select> </label>
            </fieldset>
        </form>
        <div class="uk-modal-footer uk-text-right">
            <a href="JavaScript:void(0);" id="BtnRem00" class="uk-button uk-button-small"><i class="uk-icon-angle-double-right"></i> Prossequir</a>
        </div>
    </div>
</div>
<div id="Form_1" class="uk-modal">
    <div class="uk-modal-dialog" style="width:500px;">
        <!--<button type="button" class="uk-modal-close uk-close"></button> -->
        <div class="uk-modal-header ">
            <h2><i class="uk-icon-file-text-o  uk-icon-small"></i> Gerar Remessa</h2>
        </div>
        <form name="FrmGeraRemessa" method="post" id="FrmGeraRemessa" class="uk-form">
            <fieldset style=" width:450px;border:0; padding:0; padding-top:0px;">
                <label id="lab00">  <!--pesquisar por conta bancaria -->
                    <span>Banco</span> <select name="cod_banco_rem" class="select w_300" id="cod_banco_rem">
                        <option value="" selected></option>
                      <?php
                      $contas = contas_bancarias::find_by_sql("SELECT contas_bancarias.id,contas_bancarias.nm_conta,contas_bancarias.cod_banco, contas_bancarias_cobs.tipo_arquivo
                                                     FROM contas_bancarias
                                                     LEFT JOIN contas_bancarias_cobs
                                                     ON contas_bancarias_cobs.contas_bancarias_id = contas_bancarias.id
                                                     WHERE  contas_bancarias.status = '1' AND  contas_bancarias.tp_conta != '0' AND contas_bancarias_cobs.carteira_remessa !='0'");
                      $listcontas = new ArrayIterator($contas);
                      while ($listcontas->valid()):
                         echo '<option value="' . $listcontas->current()->cod_banco . '_' . $listcontas->current()->tipo_arquivo . '" uk-data-conta_id="' . tool::Completazeros(3, $listcontas->current()->id) . '" >' . $listcontas->current()->cod_banco . " " . utf8_encode($listcontas->current()->nm_conta) . '</option>';
                         $listcontas->next();
                      endwhile;
                      ?>
                    </select> </label> <label> <span>Dta Inicial</span> <input type="text" class="input_text w_120 uk-text-center" name="dtinirem" id="dtinirem" data-uk-datepicker="{format:'DD/MM/YYYY'}" placeholder="00/00/0000"/> </label> <label>
                    <span>Dta Final</span> <input type="text" class="input_text w_120 uk-text-center" name="dtinifim" id="dtinifim" data-uk-datepicker="{format:'DD/MM/YYYY'}" placeholder="00/00/0000"/> </label>
            </fieldset>
        </form>
        <div class="uk-modal-footer uk-text-right">
            <a href="JavaScript:void(0);" id="BtnRem01" class="uk-button uk-button-small"><i class="uk-icon-angle-double-right"></i> Prossequir</a>
        </div>
    </div>
</div>
<nav class="uk-navbar ">
    <div class="uk-button-group group_button" style="border:0px solid #ccc;float:right; padding: 5px; ">
        <a href="JavaScript:void(0);" class="uk-button uk-button-small uk-button-success" data-uk-modal="{target:'#Form_0'}" style="border-left:1px solid #ccc;padding-top:2px; line-height: 30px;"><i class="uk-icon-filter "></i> Pesquisar Banco</a>
        <a href="JavaScript:void(0);" class="uk-button uk-button-small uk-button-primary" data-uk-modal="{target:'#Form_1'}" style="border-left:1px solid #ccc;padding-top:2px; line-height: 30px;"><i class="uk-icon-file-text-o"></i> Gerar Remessa</a>
    </div>
</nav>
<nav class="uk-navbar ">
    <table class="uk-table">
        <thead>
        <tr style="line-height:25px;">
            <th class="uk-width uk-text-center" style="width:90px;">Codigo</th>
            <th class="uk-width uk-text-center" style="width:100px;">Status</th>
            <th class="uk-width uk-text-center" style="width:100px;">Data Criação</th>
            <th class="uk-width uk-text-center" style="width:150px;">Data Download</th>
            <th class="uuk-text-left">Arquivo</th>
            <th class="uk-width uk-text-center" style="width:120px;">Registros</th>
            <th class="uk-width uk-text-center" style="width:120px;">Lote Remessa</th>
            <th class="uk-width uk-text-center" style="width:120px;"></th>
        </tr>
        </thead>
    </table>
</nav>
<div id="Grid_remessa" style="background-color: #fff; height:<?php echo tool::HeightContent($COB_Heigth, $COB_Browser) - 80; ?>px;"></div>

<script type="text/javascript" charset="utf-8" async defer>

    jQuery (function () {

        jQuery ("#BtnRem00").click (function (event) {

            // mensagen de carregamento
            jQuery ("#msg_loading").html ("Aguarde ");

            //abre a tela de preload
            modal.show ();

            //desabilita o envento padrao do formulario
            event.preventDefault ();

            jQuery.ajax ({
                async: true, url: "assets/cobranca/remessa/ajax_grid_remessa.php", type: "post", data: jQuery ("#FrmFiltroRemessa").serialize (), success: function (resultado) {

                    jQuery ("#Grid_remessa").html (resultado);
                    modal.hide ();
                }, error: function () {
                    UIkit.modal.alert ("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
                    modal.hide ();
                }
            });
        });
    });


    /* gera o arquivo */
    jQuery ("#BtnRem01").click (function () {

        // mensagen de carregamento
        jQuery ("#msg_loading").html ("Escrevendo arquivo... ");

        //abre a tela de preload
        modal.show ();

        /* variaveis */
        var contas_bancarias_id = jQuery ('#cod_banco_rem option:selected').attr ("uk-data-conta_id");/* id da conta bancaria */
        var dir_rem = jQuery ('#cod_banco_rem option:selected').val (); /* caminho para o arquivo que gera a remessa*/


        jQuery.ajax ({
            async: true,
            url: "assets/cobranca/remessa/controllers/Controller_gera_remessa_" + dir_rem + ".php",
            type: "post",
            data: 'contas_bancarias_id=' + contas_bancarias_id + '&' + jQuery ("#FrmGeraRemessa").serialize (),
            success: function (resultado) {

                console.log(resultado);

                var text = '{"' + resultado + '"}';

                var obj = JSON.parse (text);

                /* se o callback for 1 indica que  houve erro ai mostramos o resultado */
                if (obj.callback == 1) {

                    UIkit.notify ('' + obj.msg + '', {timeout: 2000, status: '' + obj.status + ''});
                    modal.hide ();

                } else {

                    UIkit.notify ('' + obj.msg + '', {timeout: 2000, status: '' + obj.status + ''});

                    $.post ("assets/cobranca/remessa/ajax_grid_remessa.php", {cod_banco_grid: obj.cod_banco, pagina: 1}, function (data) {

                        jQuery ("#Grid_remessa").html (data);
                        modal.hide ();
                    });
                }
            },
            error: function () {
                UIkit.modal.alert ("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
                modal.hide ();
            }
        });
    });


</script>