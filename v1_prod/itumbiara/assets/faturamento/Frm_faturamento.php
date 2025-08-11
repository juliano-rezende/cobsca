<div class="tabs-spacer" style="display:none;">
    <?php
    require_once "../../sessao.php";

    // blibliotecas
    require_once("../../conexao.php");
    $cfg->set_model_directory('../../models/');

    $FRM_matricula = isset($_GET['matricula']) ? $_GET['matricula'] : tool::msg_erros("O Campo matricula é Obrigatorio.");
    $FRM_convenio_id = isset($_GET['convenio_id']) ? $_GET['convenio_id'] : tool::msg_erros("O Campo convenio_id é Obrigatorio.");

    // recupera os dados do convenio
    $queryfatu = faturamentos::find_by_sql("SELECT SQL_CACHE
									  faturamentos.id,
                                      faturamentos.protestada,
									  faturamentos.titulos_bancarios_id,
									  faturamentos.referencia,
									  faturamentos.dt_vencimento,
									  faturamentos.dt_pagamento,
									  faturamentos.valor,
									  faturamentos.status,
									  faturamentos.valor_pago,
									  titulos_bancarios.stflagimp AS impresso,
									  titulos_bancarios.nosso_numero AS nosso_numero,
									  titulos_bancarios.dv_nosso_numero AS dv_nosso_numero,
									  CASE WHEN contas_bancarias.cod_banco != 0 THEN contas_bancarias.cod_banco  ELSE 0 END AS cod_banco,
									  contas_bancarias_cobs.desc_carteira_cob as carteira_bol,
                                      (SELECT count(id) FROM procedimentos WHERE faturamentos_id = faturamentos.id ) as total_pro,
									  (SELECT dados_cobranca.forma_cobranca_id FROM dados_cobranca WHERE dados_cobranca.id= faturamentos.dados_cobranca_id AND dados_cobranca.status = 1 ) as iddadocobranca
									 FROM
									  faturamentos
									  LEFT JOIN titulos_bancarios ON titulos_bancarios.id = faturamentos.titulos_bancarios_id
									  LEFT JOIN contas_bancarias ON contas_bancarias.id  = titulos_bancarios.contas_bancarias_id
									  LEFT JOIN contas_bancarias_cobs ON contas_bancarias_cobs.contas_bancarias_id  = contas_bancarias.id
									  LEFT JOIN dados_cobranca on dados_cobranca.id = faturamentos.dados_cobranca_id
									WHERE
									  faturamentos.matricula = '" . $FRM_matricula . "' AND  faturamentos.status != '3' AND dados_cobranca.status = 1
									ORDER BY
									  faturamentos.referencia");

    $listfat = new ArrayIterator($queryfatu);

    // recupera as taxas configuradas na empresa
    $tx_encontrada = configs::find_by_empresas_id($COB_Empresa_Id);

    // contas bancarias habilitadas a emitir boleto
    $Query_contas = contas_bancarias::find_by_sql("SELECT id,nm_conta,cod_banco FROM contas_bancarias WHERE empresas_id='" . $COB_Empresa_Id . "' AND tp_conta='2' AND status='1' ORDER BY id");
    $List_contas = new ArrayIterator($Query_contas);

    ?>
</div>

<style>
    #menu-float a {
        background-color: transparent;
    }

    .uk-text-warning {
        color: #F90 !important
    }

</style>

<div id="menu-float" style="text-align:center;margin:0 955px;top:42px;border:0;background-color:#546e7a;">

    <a id="Btn_fat_0" class="uk-icon-button uk-icon-archive" style="margin-top:2px;text-align:center;" data-uk-tooltip="{pos:'left'}" title="Dados de Cobrança"
       data-cached-title="Dados de Cobrança"></a>
    <a id="Btn_fat_1" class="uk-icon-button uk-icon-plus" style="margin-top:2px;text-align:center;" data-uk-tooltip="{pos:'left'}" title="Criar Parcelas" data-cached-title="Criar Parcelas"></a>

    <span id="opt_abertas">
	
    <a id="Btn_fat_2" class="uk-icon-button uk-icon-money" style="margin-top:2px;text-align:center;" data-uk-tooltip="{pos:'left'}" title="Receber" data-cached-title="Receber"></a>

	<?php if (intval($listfat[0]->iddadocobranca) == 1) { ?>

        <a id="Btn_fat_12" class="uk-icon-button uk-icon-file-text-o" style="margin-top:2px;text-align:center;" data-uk-tooltip="{pos:'left'}" title="Imprimir Carnê"
           data-cached-title="Imprimir Carnê"></a>

    <?php } elseif (intval($listfat[0]->iddadocobranca) == 2) { /*forma de cobrança cartão de credito*/ ?>

    <?php } elseif (intval($listfat[0]->iddadocobranca) == 3) { ?>

        <a id="Btn_fat_4" class="uk-icon-button uk-icon-copy" style="margin-top:2px;text-align:center;" data-uk-modal="{target:'#DadosGeraTitulo'}" data-uk-tooltip="{pos:'left'}" title="Gerar Titulos"
           data-cached-title="Gerar Titulos"></a>
        <a id="Btn_fat_10" class="uk-icon-button uk-icon-barcode" style="margin-top:2px;text-align:center;" data-uk-tooltip="{pos:'left'}" title="Imprimir Titulos"
           data-cached-title="Imprimir Titulos"></a>

    <?php } ?>
    <a id="Btn_fat_11" class="uk-icon-button uk-icon-clipboard" style="margin-top:2px;text-align:center;" data-uk-tooltip="{pos:'left'}" title="Adcionar Procedimentos"
       data-cached-title="Adcionar Procedimentos"></a>

    <a id="Btn_fat_5" class="uk-icon-button uk-icon-remove" style="margin-top:2px; text-align:center;" data-uk-tooltip="{pos:'left'}" title="Cancelar Titulo" data-cached-title="Cancelar titulo"></a>
    <a id="Btn_fat_6" class="uk-icon-button uk-icon-trash-o" style="margin-top:2px; text-align:center;" data-uk-tooltip="{pos:'left'}" title="Remover Parcela" data-cached-title="Remover Parcela"></a>
	</span>
    <span id="opt_pgto">
    <a id="Btn_fat_3" class="uk-icon-button uk-icon-file-text-o" style="margin-top:2px; text-align:center;" data-uk-tooltip="{pos:'left'}" title="Recibo" data-cached-title="Recibo"></a>
    <a id="Btn_fat_7" class="uk-icon-button uk-icon-reply" style="margin-top:2px;text-align:center;" data-uk-tooltip="{pos:'left'}" title="Estonar" data-cached-title="Estornar"></a>
	</span>
    <span id="opt_cancel">
    <a id="Btn_fat_8" class="uk-icon-button uk-icon-reply" style="margin-top:2px;text-align:center;" data-uk-tooltip="{pos:'left'}" title="Reabrir" data-cached-title="Reabrir"></a>
	</span>

</div>

<form class="uk-form uk-form-tab" id="FrmGridFaturamentos" style="padding: 0; margin: 0;">
    <nav class="uk-navbar">
        <table class="uk-table">
            <thead>
            <tr style="line-height: 30px;">
                <th class="uk-width uk-text-center" style="width:30px;"></th>
                <th class="uk-width uk-text-center" style="width:100px;" data-uk-tooltip="" title="Codigo da parcela" data-cached-title="Codigo da parcela">Id parcela</th>
                <!--<th class="uk-width uk-text-center" style="width:100px;" data-uk-tooltip="" title="Id do titulo" data-cached-title="Id do titulo" >Titulo Nº</th>-->
                <th class="uk-width uk-text-center" style="width:80px;" data-uk-tooltip="" title="Mês de referencia" data-cached-title="Mês de referencia">Referência</th>
                <th class="uk-width uk-text-center" style="width:90px;" data-uk-tooltip="" title="Data de vencimento" data-cached-title="Data de vencimento">Vencimento</th>
                <th class="uk-width uk-text-center" style="width:90px;" data-uk-tooltip="" title="Data de pagamento" data-cached-title="Data de pagamento">pago em</th>
                <th class="uk-width uk-text-center" style="width:90px;" data-uk-tooltip="" title="Valor da parcela" data-cached-title="Valor da parcela">Valor</th>
                <th class="uk-width uk-text-center" style="width:90px;" data-uk-tooltip="" title="Valor da Atual" data-cached-title="Valor da parcela">Valor Atual</th>
                <th class="uk-width uk-text-center" style="width:90px;" data-uk-tooltip="" title="Valor pago" data-cached-title="Valor pago">valor pg</th>
                <th class="uk-text-center"> Status</th>
                <th class="uk-width uk-text-center" style="width:100px;" data-uk-tooltip="" title="Banco Boleto" data-cached-title="Banco Boleto">Detalhes</th>
                <th class="uk-text-center" style="width:32px;">

                    <div class="uk-button-group" style=" position: absolute;right:10px;top:42px;">
                        <div data-uk-dropdown="{pos:'left-top',delay:'2000',mode:'click'}">
                            <a class="uk-button" style="border:0;"><i class="uk-icon-filter uk-icon-small" style="margin:3px 0;"></i></a>
                            <div class="uk-dropdown uk-dropdown-small uk-text-left uk-filtro-fat">
                                <ul class="uk-nav uk-nav-dropdown" style="font-size:11px;">
                                    <li>
                                        <a class="check-status" data-veiw="home"><i class="uk-icon-home "></i> Inicio</a>
                                        <a class="check-status" data-veiw="all"><i class="uk-icon-list"></i> Todas</a>
                                        <a class="check-status" data-veiw="pg"><i class="uk-icon-check-square"></i> Pagas</a>
                                        <a class="check-status" data-veiw="abv"><i class="uk-icon-check-square-o"></i> Vencidas</a>
                                        <a class="check-status" data-veiw="ab"><i class="uk-icon-square-o"></i> A vencer</a>
                                        <a class="check-status" data-veiw="cld"><i class="uk-icon-close "></i> Canceladas</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </th>
            </thead>
        </table>
    </nav>

    <div id="grid_faturamento" style="height:458px; overflow-y:scroll;">

        <table class="uk-table uk-table-striped uk-table-hover">
            <tbody>
            <?php
            // laço que loopa os lançamentos dos convenios  agrupando por data
            $lf = 1; // linha de titulos

            while ($listfat->valid()):

                $ref = new ActiveRecord\DateTime($listfat->current()->referencia);
                $dtvenc = new ActiveRecord\DateTime($listfat->current()->dt_vencimento);
                $dtpgto = new ActiveRecord\DateTime($listfat->current()->dt_pagamento);


                if ($listfat->current()->status == 0) {

                    if ($dtvenc->format('Ymd') < date("Ymd")) {
                        $msg = '<div class="uk-badge uk-badge-warning uk-link">Em aberto <i class="uk-icon-search" style="margin:3px 0;" ></i></div>';
                        $tag = 'abv'; // tag para controle de exibição
                    } else {
                        $msg = '<div class="uk-badge uk-badge-primary uk-link">A Vencer <i class="uk-icon-search" style="margin:3px 0;" ></i></div>';
                        $tag = 'ab'; // tag para controle de exibição
                    }
                }
                if ($listfat->current()->status == 1) {
                    $msg = '<div class="uk-badge uk-badge-success uk-link">Paga <i class="uk-icon-search" style="margin:3px 0;" ></i></div>';
                    $tag = 'pg'; // tag para controle de exibição
                }
                if ($listfat->current()->status == 2) {
                    $msg = '<div class="uk-badge uk-badge-danger uk-link">Cancelada <i class="uk-icon-search" style="margin:3px 0;" ></i></div>';
                    $tag = 'cld'; // tag para controle de exibição
                }
                ?>
                <tr class="all <?php echo $tag; ?>" data-id="<?php echo $listfat->current()->id; ?>" style="line-height: 30px">
                    <th class="uk-width uk-text-center " style="width:30px;">
                        <?php echo $lf; ?>
                    </th>
                    <td class="uk-width uk-text-center" style="width:100px;">
                        <?php
                        echo tool::CompletaZeros(10, $listfat->current()->id);
                        ?>
                    </td>
                    <!--
        <td class="uk-width uk-text-center" style="width:100px;">
        <?php
                    /* echo tool::CompletaZeros(10,$listfat->current()->nosso_numero)."-".$listfat->current()->dv_nosso_numero;*/
                    ?>
       	</td>
       	-->
                    <td class="uk-width uk-text-center uk-text-bold" style="width:80px;"><?php echo tool::Referencia($ref->format('Ymd'), "/"); ?></td>
                    <td class="uk-width uk-text-center" style="width:90px;"><?php echo $dtvenc->format('d/m/Y'); ?></td>
                    <td class="uk-width uk-text-center" style="width:90px;"><?php if ($listfat->current()->dt_pagamento == "" || $dtpgto->format('d/m/Y') == "01/01/0001") {
                            echo "00/00/0000";
                        } else {
                            echo $dtpgto->format('d/m/Y');
                        } ?></td>
                    <td class="uk-width uk-text-center uk-text-primary uk-text-bold" style="width:90px;">
                        <input type="text" autocomplete="off" style="width: 100%; height: 100%; border:0;  margin: 0; padding:0; background-color: transparent;" class="uk-text-center uk-vep" id="vep"
                               value="<?php echo number_format($listfat->current()->valor, 2, ",", "."); ?>" data-uk-valp="<?php echo number_format($listfat->current()->valor, 2, ",", "."); ?>"
                               readonly="readonly"/>
                    </td>
                    <td class="uk-width uk-text-center uk-text-warning uk-text-bold" style="width:90px;">
                        <?php

                        if ($listfat->current()->status == 0) {

                            $vl_atual = faturamentos::Calcula_Juros($listfat->current()->valor, $dtvenc->format('Y-m-d'), $tx_encontrada->juros, $tx_encontrada->multa);
                            echo number_format($vl_atual, 2, ",", ".");
                        } else {
                            echo "-";
                        }
                        ?>
                    </td>
                    <td class="uk-width uk-text-center uk-text-success uk-text-bold" style="width:90px;"><?php echo number_format($listfat->current()->valor_pago, 2, ",", "."); ?></td>
                    <td class="uk-text-center ">
                        <?php
                        echo $msg;
                        ?>
                    </td>
                    <td lass="uk-width uk-text-center" style="width:93px;">
                        <?php
                        if ($listfat->current()->impresso != 0) {
                            echo ' <img src="imagens/icon_bancos/' . $listfat->current()->cod_banco . '.png" alt="" width="15" height="15"  data-uk-tooltip="" title="" data-cached-title="Banco de origem do titulo" onclick="DetalhesTitulo(' . $listfat->current()->titulos_bancarios_id . ');">';
                        }
                        /* se a boleto for registrado exibimos uma informação infirmando ao usuario */
                        if ($listfat->current()->carteira_bol == "CR") {
                            echo ' <div  class="uk-badge uk-badge-notification uk-text-small" data-uk-tooltip="" title="" data-cached-title="Titulo Registrado">Reg</div>';
                        }
                        /* se a boleto for registrado exibimos uma informação infirmando ao usuario */
                        if ($listfat->current()->total_pro > 0) {
                            echo ' <div  class="uk-badge uk-badge-warning uk-badge-notification uk-text-small" data-uk-tooltip="" title="" data-cached-title="Parcela prossui procedimentos médicos">Proc</div>';
                        }if ($listfat->current()->protestada == 'S') {
                            $prt = '<div class="uk-badge uk-badge-warning" data-uk-tooltip="" title="" data-cached-title="Parcela enviada para protesto">>Prot</div>';
                        }

                        ?>
                    </td>
                    <th class="uk-width uk-text-center" style="width:20px;">
                        <?php if ($listfat->current()->status == 0) { ?>
                            <input type="checkbox" style="margin:3px;" class="check_ab" name="check0[]" value="<?php echo $listfat->current()->id; ?>"
                                   data-uk-codbank="<?php echo $listfat->current()->cod_banco; ?>" data-uk-id-titulo="<?php echo $listfat->current()->titulos_bancarios_id; ?>"/>
                        <?php }
                        if ($listfat->current()->status == 1) { ?>
                            <input type="checkbox" style="margin:3px;" class="check_pgto" name="check0[]" value="<?php echo $listfat->current()->id; ?>"
                                   data-uk-codbank="<?php echo $listfat->current()->cod_banco; ?>" data-uk-id-titulo="<?php echo $listfat->current()->titulos_bancarios_id; ?>"/>
                        <?php }
                        if ($listfat->current()->status == 2) { ?>
                            <input type="checkbox" style="margin:3px;" class="check_cancel" name="check0[]" value="<?php echo $listfat->current()->id; ?>"
                                   data-uk-codbank="<?php echo $listfat->current()->cod_banco; ?>" data-uk-id-titulo="<?php echo $listfat->current()->titulos_bancarios_id; ?>"/>
                        <?php } ?>
                    </th>
                </tr>
                <?php
                $lf++;
                $listfat->next();
            endwhile;
            ?>
            </tbody>
            <tfoot>
        </table>
    </div>
    <div id="DadosGeraTitulo" class="uk-modal">
        <div class="uk-modal-dialog" style="width:300px;">
            <!--<button type="button" class="uk-modal-close uk-close"></button> -->
            <div class="uk-modal-header ">
                <h2><i class="uk-icon-bank"></i> Banco Emissor </h2>
            </div>
            <?php
            while ($List_contas->valid()):

                echo '<label class="uk-text-uppercase">
		                <input type="radio"  name="banco_emissor" id="banco_emissor" value="' . $List_contas->current()->id . '""> ' . $List_contas->current()->nm_conta . '
		                </label><br />';

                $List_contas->next();

            endwhile;
            ?>

            <div class="uk-modal-footer uk-text-right">
                <a id="Btn_fat_9" class="uk-button uk-button-primary uk-button-small"><i class="uk-icon-check"></i> Confirmar</a>
                <a id="BtnCancelarFiltro" class="uk-button uk-button-danger uk-button-small uk-modal-close"><i class="uk-icon-remove"></i> Cancelar</a>
            </div>
        </div>
    </div>

</form>
<script type="text/javascript">

    jQuery(document).ready(function () {
        jQuery(".cld").hide(); // abre a window com as parcelas canceladas ocultas
        jQuery("#menu-float").css("background-color", "" + jQuery("#" + jQuery("#menu-float").closest('.Window').attr('id') + "").css('border-left-color') + "");// define a cor do menu lateral
        jQuery("#opt_abertas,#opt_pgto,#opt_cancel").hide();// oculta as opções avançadas
    });
    /*
        exibe as opções em parcelas

        uk-icon-remove uk-icon-trash-o
    */
    jQuery("#grid_faturamento input[type=checkbox]").click(function (evento) {

        var checked = jQuery(this).attr("class");

        if (checked == "check_ab") {

            if (jQuery(".check_ab").is(":checked")) {

                jQuery("#opt_abertas").css("display", "block");	// exibe o grupo de opções gerais
                jQuery("#opt_pgto").css("display", "none");		// oculta o grupo de opções de estorno
                jQuery("#opt_cancel").css("display", "none");	// oculta o grupo de opções de cancelamento


                jQuery('.check_pgto,.check_cancel').each(function () {
                    jQuery(this).attr("checked", false);
                });
            } else {
                jQuery("#opt_abertas").css("display", "none");	// exibe o grupo de opções gerais

            }
        }
        if (checked == "check_pgto") {

            if (jQuery(".check_pgto").is(":checked")) {

                jQuery("#opt_abertas").css("display", "none");	// exibe o grupo de opções gerais
                jQuery("#opt_pgto").css("display", "block");	// oculta o grupo de opções de estorno
                jQuery("#opt_cancel").css("display", "none");	// oculta o grupo de opções de cancelamento

                jQuery('.check_ab,.check_cancel').each(function () {
                    jQuery(this).attr("checked", false);
                });
            } else {
                jQuery("#opt_pgto").css("display", "none");	// oculta o grupo de opções de estorno

            }
        }
        if (checked == "check_cancel") {

            if (jQuery(".check_cancel").is(":checked")) {

                jQuery("#opt_abertas").css("display", "none");	// exibe o grupo de opções gerais
                jQuery("#opt_pgto").css("display", "none");	// oculta o grupo de opções de estorno
                jQuery("#opt_cancel").css("display", "block");	// oculta o grupo de opções de cancelamento

                jQuery('.check_pgto,.check_ab').each(function () {
                    jQuery(this).attr("checked", false);
                });

            } else {
                jQuery("#opt_cancel").css("display", "none");	// oculta o grupo de opções de cancelamento


            }
        }

    });

    /*
    function() {}unção para exibição de linhas do filtro
    */
    jQuery(".check-status").click(function () {

        var tp = jQuery(this).attr('data-veiw');//pega a linha de exibição
        jQuery(".uk-filtro-fat").hide();

        if (tp == "home") {
            jQuery(".cld").hide();
            jQuery(".abv,.ab,.pg").show();
            return false;

        }
        if (tp == "all") {
            jQuery(".all").show();
            return false;
        }
        if (tp == "abv") {
            jQuery(".all").hide();
            jQuery("." + tp + "").show();
            return false;
        } else {

            jQuery(".all").hide();
            jQuery("." + tp + "").show();

            return false;
        }
    });

    /*
        função para selecionar todos os checks
    */
    function marcardesmarcar(id) {

        jQuery('.check').each(function () {
            if (jQuery(this).prop("checked")) {
                jQuery(this).prop("checked", false);
                jQuery("#plus_opt").css("display", "none");
            } else {
                jQuery(this).prop("checked", true);
                jQuery("#plus_opt").css("display", "block");
            }
        });
    }

    /*
         função para visualizar detalhes da parcela
     */
    jQuery("#grid_faturamento table tr td .uk-link").click(function () {

        var id_parcela = jQuery(this).closest('tr').attr('data-id');//pega a linha de exibição

        New_window('file-text-o', '800', '450', 'Detalhes da parcela', 'assets/faturamento/Frm_detalhes.php?paid=' + id_parcela + '', true, false, 'Carregando parcela...');
    });


    /*
        abre a janela de dados de cobrança
    */
    jQuery("#Btn_fat_0").click(function () {

        New_window('arquive', '500', '250', 'Dados de Cobrança', 'assets/faturamento/Frm_dados_cobranca.php?matricula=<?php echo $FRM_matricula; ?>&convenio_id=<?php echo $FRM_convenio_id; ?>', true, false, 'Carregando dados...');
    });


    /*
        abre a janela para criar novas parcelas
    */
    jQuery("#Btn_fat_1").click(function () {
        New_window('copy', '330', '250', 'Gerar Parcelas', 'assets/faturamento/Frm_gera_parcelas.php?matricula=<?php echo $FRM_matricula; ?>', true, false, 'Aguarde...');
    });


    /*
        pega todas as parcelas selecionadas para recebimento
    */
    jQuery("#Btn_fat_2").click(function () {

        var ids = [];// array com os valores dos checks

        jQuery("#grid_faturamento input[type=checkbox]").each(function () {// verifica qual está marcado dentro da grid faturamento apenas

            if (this.checked) {
                ids.push(jQuery(this).val());// retorna o valor do campo marcado
            }
        });


        New_window('download', '400', '420', 'Recebimento', 'assets/faturamento/Frm_recebimento.php?mat=<?php echo $FRM_matricula; ?>&convenio_id=<?php echo $FRM_convenio_id; ?>&ids=' + ids + '', true, false, 'Carregando...');
    });


    /*
        recibo
    */
    jQuery("#Btn_fat_3").click(function () {

        var ids = [];// array com os valores dos checks

        jQuery("#grid_faturamento input[type=checkbox]").each(function () {// verifica qual está marcado dentro da grid faturamento apenas

            if (this.checked) {
                ids.push(jQuery(this).val());// retorna o valor do campo marcado
            }
        });

        New_window('file-text-o', '620', '410', 'Recibo', 'assets/faturamento/Frm_comp_pgto.php?ids=' + ids + '&mat=<?php echo $FRM_matricula; ?>', true, false, 'Carregando recibo...');
    });


    /*
        pega todas as parcelas selecionadas para cancelamento do titulo
    */
    jQuery(function () {

        jQuery("#Btn_fat_5").click(function (event) {


            UIkit.modal.confirm('Atenção esta ação é irreversível, deseja prosseguir ? .', function () {

                /* mensagen de carregamento*/
                jQuery("#msg_loading").html(" Cancelando...");

                //abre a tela de preload*/
                modal.show();

                /*desabilita o envento padrao do formulario*/
                event.preventDefault();
                /* array com os valores dos checks*/
                var ids = [];
                /* verifica qual está marcado dentro da grid faturamento apenas*/
                jQuery("#grid_faturamento input[type=checkbox]").each(function () {

                    id_titulo = jQuery(this).attr("data-uk-id-titulo");

                    if (this.checked) {

                        if (id_titulo > 0) {

                            ids.push(jQuery(this).val());/* retorna o valor do campo marcado*/
                        } else {

                            UIkit.notify('Parcela ' + jQuery(this).val() + ' Não possui titulo favor utilizar a ação remover parcela.', {timeout: 3000, status: 'danger'});
                        }
                    }
                });

                jQuery.ajax({
                    async: true,
                    url: "assets/faturamento/controllers/Controller_faturamento.php",
                    type: "POST",
                    data: "action=cancel&ids=" + ids + "",
                    success: function (resultado) {

                        var text = '{"' + resultado + '"}';

                        var obj = JSON.parse(text);


                        /* se o callback for 0 indica que não houve erro ai mostramos o resultado da execução das querys*/
                        if (obj.callback == 1) {

                            UIkit.notify('<i class="uk-icon-' + obj.icon + '"></i> ' + obj.msg + '', {timeout: 3000, status: '' + obj.status + ''});
                            modal.hide();

                            /* se for = 1 indica que houve erro ai retornamo o erro na tela do usuario*/
                        } else {

                            UIkit.notify('' + obj.msg + '', {timeout: 2000, status: '' + obj.status + ''});

                            jQuery("#" + jQuery("#FrmGridFaturamentos").closest('.Window').attr('id') + "").remove();

                            New_window('list', '950', '500', 'Faturamento', 'assets/faturamento/Frm_faturamento.php?matricula=<?php echo $FRM_matricula; ?>&convenio_id=<?php echo $FRM_convenio_id; ?>', true, false, 'Carregando...');

                        }
                    },
                    error: function () {
                        UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
                        modal.hide();
                    }
                });
            });
        });
    });


    /*
        pega todas as parcelas selecionadas para remover
    */
    jQuery(function () {

        jQuery("#Btn_fat_6").click(function (event) {

            UIkit.modal.confirm('Atenção esta ação é irreversível, deseja prosseguir ? .', function () {

                /* mensagen de carregamento*/
                jQuery("#msg_loading").html(" Removendo...");

                //abre a tela de preload*/
                modal.show();

                /*desabilita o envento padrao do formulario*/
                event.preventDefault();
                /* array com os valores dos checks*/
                var ids = [];
                /* verifica qual está marcado dentro da grid faturamento apenas*/
                jQuery("#grid_faturamento input[type=checkbox]").each(function () {

                    if (this.checked) {
                        ids.push(jQuery(this).val());/* retorna o valor do campo marcado*/
                    }
                });

                jQuery.ajax({
                    async: true,
                    url: "assets/faturamento/controllers/Controller_faturamento.php",
                    type: "POST",
                    data: "action=remove&ids=" + ids + "",
                    success: function (resultado) {

                        var text = '{"' + resultado + '"}';
                        var obj = JSON.parse(text);


                        /* se o callback for 0 indica que não houve erro ai mostramos o resultado da execução das querys*/
                        if (obj.callback == 1) {

                            UIkit.modal.alert("" + obj.msg + "");
                            modal.hide();


                            /* se for = 1 indica que houve erro ai retornamo o erro na tela do usuario*/
                        } else {

                            UIkit.notify('' + obj.msg + '', {timeout: 2000, status: '' + obj.status + ''});
                            jQuery("#" + jQuery("#FrmGridFaturamentos").closest('.Window').attr('id') + "").remove();
                            New_window('list', '950', '500', 'Faturamento', 'assets/faturamento/Frm_faturamento.php?matricula=<?php echo $FRM_matricula; ?>&convenio_id=<?php echo $FRM_convenio_id; ?>', true, false, 'Carregando...');
                        }
                    },
                    error: function () {
                        UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
                        modal.hide();
                    }
                });
            });
        });

    });

    /*
        pega todas as parcelas selecionadas para estorno
    */
    jQuery("#Btn_fat_7").click(function () {

        var ids = [];// array com os valores dos checks

        jQuery("#grid_faturamento input[type=checkbox]").each(function () {// verifica qual está marcado dentro da grid faturamento apenas

            if (this.checked) {
                ids.push(jQuery(this).val());// retorna o valor do campo marcado
            }
        });

        New_window('download', '300', '320', 'Estorno', 'assets/faturamento/Frm_estorno.php?mat=<?php echo $FRM_matricula; ?>&convenio_id=<?php echo $FRM_convenio_id; ?>&ids=' + ids + '', true, false, 'Carregando...');
    });

    /*
        pega todas as parcelas selecionadas para reabrir
    */
    jQuery(function () {

        jQuery("#Btn_fat_8").click(function (event) {


            /* mensagen de carregamento*/
            jQuery("#msg_loading").html(" Aguarde...");

            //abre a tela de preload*/
            modal.show();

            /*desabilita o envento padrao do formulario*/
            event.preventDefault();
            /* array com os valores dos checks*/
            var ids = [];
            /* verifica qual está marcado dentro da grid faturamento apenas*/
            jQuery("#grid_faturamento input[type=checkbox]").each(function () {

                if (this.checked) {
                    ids.push(jQuery(this).val());/* retorna o valor do campo marcado*/
                }
            });

            jQuery.ajax({
                async: true,
                url: "assets/faturamento/controllers/Controller_faturamento.php",
                type: "POST",
                data: "action=reabrir&ids=" + ids + "",
                success: function (resultado) {

                    var text = '{"' + resultado + '"}';
                    var obj = JSON.parse(text);

                    /* se o callback for 1 indica que não houve erro ai mostramos o resultado da execução das querys*/
                    if (obj.callback == 1) {

                        UIkit.modal.alert("" + obj.msg + "");

                        /* se for = 0 indica que houve erro ai retornamo o erro na tela do usuario*/
                    } else {

                        UIkit.notify('' + obj.msg + '', {timeout: 2000, status: '' + obj.status + ''});
                        jQuery("#" + jQuery("#FrmGridFaturamentos").closest('.Window').attr('id') + "").remove();
                        New_window('list', '950', '500', 'Faturamento', 'assets/faturamento/Frm_faturamento.php?matricula=<?php echo $FRM_matricula; ?>&convenio_id=<?php echo $FRM_convenio_id; ?>', true, false, 'Carregando...');

                    }
                },
                error: function () {
                    UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
                    modal.hide();
                }
            });
        });

    });
    /*
        pega todas as parcelas selecionadas para geraçao do carne
    */
    jQuery(function () {

        jQuery("#Btn_fat_9").click(function (event) {

            /* mensagen de carregamento*/
            jQuery("#msg_loading").html(" Aguarde...");

            //abre a tela de preload*/
            modal.show();

            /*desabilita o envento padrao do formulario*/
            event.preventDefault();
            /* array com os valores dos checks*/
            var ids = [];
            /* verifica qual está marcado dentro da grid faturamento apenas*/
            jQuery("#grid_faturamento input[type=checkbox]").each(function () {

                if (this.checked) {
                    ids.push(jQuery(this).val());/* retorna o valor do campo marcado*/
                }
            });


            /* variavel com o id da conta bancaria*/
            var banco_emissor = jQuery("input[name='banco_emissor']:checked").val();

            jQuery.ajax({
                async: true,
                url: "assets/faturamento/controllers/Controller_carne.php",
                type: "POST",
                data: "mat=<?php echo $FRM_matricula; ?>&ids=" + ids + "&cb_id=" + banco_emissor + "",
                success: function (resultado) {

                    var text = '{"' + resultado + '"}';
                    var obj = JSON.parse(text);

                    /* se o callback for 1 indica que não houve erro ai mostramos o resultado da execução das querys*/
                    if (obj.callback == 1) {

                        UIkit.modal.alert("" + obj.msg + "");
                        modal.hide();

                        /* se for = 0 indica que houve erro ai retornamo o erro na tela do usuario*/
                    } else {

                        UIkit.notify('' + obj.msg + '', {timeout: 2000, status: '' + obj.status + ''});

                        jQuery("#" + jQuery("#FrmGridFaturamentos").closest('.Window').attr('id') + "").remove();

                        New_window('list', '950', '500', 'Faturamento', 'assets/faturamento/Frm_faturamento.php?matricula=<?php echo $FRM_matricula; ?>&convenio_id=<?php echo $FRM_convenio_id; ?>', true, false, 'Carregando...');

                        /*UIkit.modal.confirm('Deseja Imprimir O titulo?', function(){

                          New_window('list','980','550','Titulos','assets/cobranca/boleto/carne/'+jQuery("#banco_emissor").val()+'/veiw_bol_'+obj.dir_bol+'.php?ids='+obj.titulos_id+'',true,false,'Carregando...');

                        });*/


                    }
                },
                error: function () {

                    UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
                    modal.hide();

                }
            });
        });
    });

    /*
        pega todas as parcelas selecionadas para impressão do carne
    */
    jQuery(function () {

        jQuery("#Btn_fat_10").click(function (event) {

            /* mensagen de carregamento*/
            jQuery("#msg_loading").html(" Preparando...");

            //abre a tela de preload*/
            modal.show();

            /*desabilita o envento padrao do formulario*/
            event.preventDefault();
            /* array com os valores dos checks*/
            var ids = [];
            var emissor_titulo = [];
            var dir_bol = [];

            /* verifica qual está marcado dentro da grid faturamento apenas*/
            jQuery("#grid_faturamento input[type=checkbox]").each(function () {

                if (this.checked) {

                    ids.push(jQuery(this).val());/* retorna o valor do campo marcado*/


                    if (jQuery.isEmptyObject(emissor_titulo)) {

                        if (jQuery(this).attr("data-uk-codbank") == 0) {
                            UIkit.modal.alert("Parcela não possui titulo bancario!");
                            modal.hide();
                            exit();
                        }


                        emissor_titulo.push(jQuery(this).attr("data-uk-codbank"));

                        dir_bol.push(jQuery(this).attr("data-uk-dir_bol"));

                    } else {

                        if (jQuery.inArray(jQuery(this).attr("data-uk-codbank"), emissor_titulo) !== -1) {

                        } else {

                            modal.hide();
                            UIkit.modal.alert("Selecione somente parcelas para o mesmo banco!");/*erro de caminho invalido do arquivo*/
                            exit();
                        }
                    }
                }
            });

            New_window('list', '980', '550', 'Titulos', 'assets/cobranca/boleto/carne/' + emissor_titulo + '/veiw_bol.php?ids=' + ids + '', true, false, 'Carregando...');

        });
    });

    // botão para adcionar procedimento a parcela
    jQuery("#Btn_fat_11").click(function () {

        var ids = [];// array com os valores dos checks

        jQuery("#grid_faturamento input[type=checkbox]").each(function () {// verifica qual está marcado dentro da grid faturamento apenas

            if (this.checked) {
                ids.push(jQuery(this).val());// retorna o valor do campo marcado
            }
        });

        New_window('file-text-o', '620', '410', 'Procedimentos', 'assets/faturamento/Frm_procedimentos.php?ids=' + ids + '&mat=<?php echo $FRM_matricula; ?>', true, false, 'Carregando...');
    });


    /*
        pega todas as parcelas selecionadas para impressão do carne
    */
    jQuery(function () {

        jQuery("#Btn_fat_12").click(function (event) {

            /* mensagen de carregamento*/
            jQuery("#msg_loading").html(" Preparando...");

            //abre a tela de preload*/
            modal.show();

            /*desabilita o envento padrao do formulario*/
            event.preventDefault();
            /* array com os valores dos checks*/
            var ids = [];

            /* verifica qual está marcado dentro da grid faturamento apenas*/
            jQuery("#grid_faturamento input[type=checkbox]").each(function () {

                if (this.checked) {

                    ids.push(jQuery(this).val());/* retorna o valor do campo marcado*/

                }
            });

            if (ids.length === 0) {

                UIkit.modal.alert("Não existem parcelas selecionadas!");
                modal.hide();
                exit();
            }

            New_window('list', '980', '550', 'CarnÊ', 'assets/cobranca/carne/veiw_bol.php?ids=' + ids + '', true, false, 'Carregando' + '...');

        });
    });


    /*
     Função de exibição de detalhes do titulo

    function DetalhesTitulo(id){

    New_window('file-text-o','800','500','Detalhes do Titulo','assets/faturamento/Frm_detalhes_titulo.php?titid='+id+'',true,false,'Carregando...');

    }
    */
    /*
    Função para edição de valores da parcela

    jQuery(".uk-vep").dblclick(function(){

    $(this).prop( "readonly", false ).css("background-color","#43A047").css("color","#FFFFFF").val("").maskMoney({showSymbol:true, symbol:"", decimal:",", thousands:"."});

    });

    /*
    Função para edição de valores da parcela

    jQuery(".uk-vep").blur(function(){

    //<input type="text" style="width: 100%; border:0; line-height: 100%; margin: 0; padding:0;" class="uk-text-center" id="vl_nominal" value="" readonly="readonly"  data-editval-id="" />

    $(this).prop( "readonly", true ).css("background-color","transparent").css("color","#666");

    var newval=parseInt(jQuery(this).val());

    if(newval > 0){


    var attval=jQuery(this).val();


    /* mensagen de carregamento
    jQuery("#msg_loading").html(" Aguarde...");

    //abre a tela de preload
    modal.show();


    }else{

        jQuery(this).val(jQuery(this).attr("data-uk-valp"));

    }

    });
    */
</script>