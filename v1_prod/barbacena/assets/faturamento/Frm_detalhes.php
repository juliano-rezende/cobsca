<?php
require_once "../../sessao.php";
?>
<div class="tabs-spacer" style="display:none;">
    <?php
    // blibliotecas
    require_once("../../conexao.php");
    $cfg->set_model_directory('../../models/');

    $FRM_parcela_id = isset($_GET['paid']) ? $_GET['paid'] : tool::msg_erros("O Campo codigo da parcela é Obrigatorio.");

    // recupera os dados do convenio
    $Query_fat = faturamentos::find_by_sql("SELECT
	faturamentos.*,
	convenios.nm_fantasia,
    usuarios.nm_usuario,
	formas_cobranca_sys.descricao,
    titulos_bancarios.carteira_rem,
    titulos_bancarios.obs as obs_titulo,
    contas_bancarias.nm_conta,
    case when  titulos_bancarios.nosso_numero is null then 0 else CONCAT( titulos_bancarios.nosso_numero,'-',titulos_bancarios.dv_nosso_numero )  end as nosso_numero,
    case when  titulos_bancarios.cod_retorno is null then 0 else titulos_bancarios.cod_retorno  end as cod_retorno,
    case when  titulos_bancarios.cod_remessa is null then 0 else titulos_bancarios.cod_remessa  end as cod_remessa,
    case when  titulos_bancarios.numero_doc is null then '0' else titulos_bancarios.numero_doc  end as numero_doc,
    case when  titulos_bancarios.linha_digitavel is null then '0' else titulos_bancarios.linha_digitavel  end as linha_digitavel,
    seguros.status as status,
    seguros.obs as obs_seg
	FROM
	faturamentos
	LEFT JOIN convenios ON convenios.id = faturamentos.convenios_id
    LEFT JOIN usuarios ON usuarios.id = faturamentos.usuarios_id
	LEFT JOIN dados_cobranca ON dados_cobranca.id =	faturamentos.dados_cobranca_id
	LEFT JOIN formas_cobranca_sys ON formas_cobranca_sys.id =	dados_cobranca.formascobranca_sys_id
	LEFT JOIN titulos_bancarios ON faturamentos.titulos_bancarios_id =	titulos_bancarios.id
	LEFT JOIN seguros ON faturamentos.referencia =	seguros.referencia AND faturamentos.matricula = seguros.matricula
    LEFT JOIN contas_bancarias ON contas_bancarias.id = titulos_bancarios.contas_bancarias_id
	WHERE
	faturamentos.id = '" . $FRM_parcela_id . "'");

    $Query_pro = procedimentos::find_by_sql("SELECT * FROM procedimentos WHERE  faturamentos_id = '" . $FRM_parcela_id . "'");


    $ref = new ActiveRecord\DateTime($Query_fat[0]->referencia);
    $dtultimaalteracao = new ActiveRecord\DateTime($Query_fat[0]->ultima_alteracao);
    $dtvenc = new ActiveRecord\DateTime($Query_fat[0]->dt_vencimento);
    $dtpgto = new ActiveRecord\DateTime($Query_fat[0]->dt_pagamento);
    ?>
</div>

<ul class="uk-tab uk-gradient-cinza" data-uk-tab>
    <li class="uk-active"><a uk-data-tab="#Geral">Geral</a></li>
    <li><a uk-data-tab="#Procedimentos">Procedimentos</a></li>
    <li><a uk-data-tab="#Detalhes">Informações Adicionais</a></li>
    <li><a uk-data-tab="#Historico">Histórico</a></li>
</ul>

<form id="FrmDetalhesParcela" class="uk-form">

    <div id="Geral" class="tab-content">
        <label>
            <span>Id Parcela</span>
            <input type="text" class="uk-text-right  w_120" readonly="readonly" id="id_p" value="<?php echo tool::CompletaZeros(10, $Query_fat[0]->id); ?>"/>
        </label>

        <div style="height:30px; width:350px; position:absolute; top:78px; right:20px; text-align:center;">
            <label>
                <span>Referencia</span>
                <input type="text" class="uk-text-right  w_120" readonly="readonly" value="<?php echo tool::Referencia($ref->format('Ymd'), "/"); ?>"/>
            </label>
        </div>

        <label>
            <span>Nº Documento</span>
            <input type="text" class="uk-text-right  w_120" readonly="readonly" value="<?php echo $Query_fat[0]->numero_doc; ?>"/>
        </label>

        <div style="height:30px; width:350px; position:absolute; top:114px; right:20px; text-align:center;">
            <label>
                <span>Nº Boleto</span>
                <input type="text" class="uk-text-right  w_120" readonly="readonly" value="<?php echo $Query_fat[0]->nosso_numero; ?>"/>
            </label>

        </div>

        <label>
            <span>DT Vencimento</span>
            <input type="text" class="uk-text-right w_120" style="background-color: #fff; color: #000;"
                   value="<?php echo $dtvenc->format('d/m/Y'); ?>"
                   id="dtv"
                <?php if ($Query_fat[0]->titulos_bancarios_id > 0) { ?> disabled="disabled" <?php } ?>
                <?php if ($Query_fat[0]->titulos_bancarios_id == 0) { ?> d data-uk-datepicker="{format:'DD/MM/YYYY'}" <?php } ?> />
        </label>

        <div style="height:30px; width:350px; position:absolute; top:151px; right:20px; text-align:center;">
            <label>
                <span>DT Pagamento</span>
                <input type="text" class="uk-text-right w_120" readonly="readonly" value="<?php if ($Query_fat[0]->dt_pagamento == "") {
                    echo "00/00/0000";
                } else {
                    echo $dtpgto->format('d/m/Y');
                } ?>"/>
            </label>
        </div>


        <label>
            <span>Valor</span>
            <input type="text" style=" background-color: #fff; color: #000;" <?php if ($Query_fat[0]->titulos_bancarios_id > 0) {
                echo 'disabled="disabled"';
            } ?>" class="uk-text-right w_120 uk-badge-primary" value="<?php echo number_format($Query_fat[0]->valor, 2, ",", "."); ?>" id="vlr" />
        </label>

        <label>
            <span>Valor pago</span>
            <input type="text" class="uk-text-right  w_120 uk-text-success" readonly="readonly" value="<?php echo number_format($Query_fat[0]->valor_pago, 2, ",", "."); ?>"/>
        </label>

        <div style="height:30px; width:350px; position:absolute; top:187px; right:20px; text-align:center;">
            <label>
                <span>Descontos</span>
                <input type="text" class="uk-text-right  w_120 uk-text-warning" readonly="readonly" value="<?php echo number_format($Query_fat[0]->descontos, 2, ",", "."); ?>"/>
            </label>
        </div>

        <div style="height:30px; width:350px; position:absolute; top:223px; right:20px; text-align:center;">
            <label>
                <span>Acrescimos</span>
                <input type="text" class="uk-text-right  w_120 uk-text-primary" readonly="readonly" value="<?php echo number_format($Query_fat[0]->acrescimos, 2, ",", "."); ?>"/>
            </label>
        </div>

        <label>
            <span>Cod retorno</span>
            <input type="text" class="uk-text-right  w_120" readonly="readonly" value="<?php echo tool::CompletaZeros(12, $Query_fat[0]->cod_retorno); ?>"/>
        </label>

        <div style="height:30px; width:350px; position:absolute; top:258px; right:20px; text-align:center;">
            <label>
                <span>Cod remessa</span>
                <input type="text" class="uk-text-right  w_120" readonly="readonly" value="<?php echo tool::CompletaZeros(12, $Query_fat[0]->cod_remessa); ?>"/>
            </label>
        </div>
        <label>
            <span>Obs retorno</span>
            <div class="uk-badge uk-badge-warning uk-badge-notification uk-text-small" style="margin: 5px 0;">
                <?php if ($Query_fat[0]->cod_retorno == 0 && $Query_fat[0]->nosso_numero == 0) {

                    echo "Parcela Ainda não Possui Boleto Bancario!";

                }
                if ($Query_fat[0]->cod_remessa > 0 && $Query_fat[0]->nosso_numero > 0) {

                    echo "Parcela trasmitida para o banco!";

                }
                if ($Query_fat[0]->cod_retorno > 0 && $Query_fat[0]->nosso_numero > 0) {
                    echo $Query_fat[0]->obs_titulo;
                } ?>

            </div>
        </label>
        <label>
            <span>Observações</span>
            <textarea  <?php if ($Query_fat[0]->titulos_bancarios_id > 0) {
                echo 'disabled="disabled"';
            } ?> id="dth" style="height: 120px; width: 380px; background-color: #fff; color: #000;"><?php echo str_replace("<br>", "\n", $Query_fat[0]->obs); ?></textarea>
        </label>


        <?php
        if ($Query_fat[0]->cod_remessa <= "0") {
            ?>
            <a id="Btn_det_0" class="uk-button uk-button-primary" style=" bottom:30px; position:absolute;  right:15px;" data-uk-tooltip="{pos:'left'}" title="Atualizar"
               data-cached-title="Atualizar"><i class="uk-icon-check"></i> Atualizar </a>
            <?php
        }
        ?>

    </div>
    <div id="Procedimentos" class="tab-content" style="display:none; overflow-y: scroll; height:405px;">


        <?php

        // laço que loopa os lançamentos dos convenios  agrupando por data
        $listpro = new ArrayIterator($Query_pro);
        $lf = 1; // linha de titulos

        while ($listpro->valid()):

            $dt_lancamento = new ActiveRecord\DateTime($listpro->current()->dt_vencimento);

            if ($listpro->current()->status == 0) {
                $st = "A faturar";
                $class = "uk-badge-warning";
            }
            if ($listpro->current()->status == 1) {
                $st = "Faturado";
                $class = "uk-badge";
            }
            if ($listpro->current()->status == 2) {
                $st = "Cancelado";
                $class = "uk-badge-danger";
            }
            if ($listpro->current()->status == 3) {
                $st = "Pago";
                $class = "uk-badge-success";
            }

            ?>

            <article class="uk-comment" id="line_proc_<?php echo $listpro->current()->id; ?>">
                <header class="uk-comment-header">
                    <h4 class="uk-comment-title uk-text-bold" style="padding-bottom: 5px;">
                        <?php echo $lf . "- " . $listpro->current()->historico; ?>
                    </h4>
                    <div class="uk-comment-meta">
                        status:
                        <div class="uk-badge <?php echo $class; ?>"><?php echo $st; ?></div>
                        Valor:
                        <div class="uk-badge uk-badge-success"><?php echo number_format($listpro->current()->valor, 2, ',', '.'); ?></div>
                        <button class="uk-button uk-button-small uk-icon-remove uk-remove-proc uk-button-danger" type="button" data-uk-tooltip title="Remover"
                                data-uk-proc="<?php echo $listpro->current()->id; ?>" style="float: right;"> Remover
                        </button>
                </header>
                <div class="uk-comment-body" style="text-transform: uppercase; font-size: 11px;"><?php echo nl2br($listpro->current()->detalhes); ?></div>
            </article>

            <?php

            $lf++;
            $listpro->next();
        endwhile;
        ?>
    </div>
    <div id="Detalhes" class="tab-content" style="display:none; overflow-y: hidden; height: 390px;">

        <label>
            <span>Forma de Cob</span>
            <input type="text" class="uk-text-left  w_350" readonly="readonly" value="<?php echo $Query_fat[0]->descricao; ?>"/>
        </label>

        <label>
            <span>Conta Bancaria</span>
            <input type="text" class="uk-text-left  w_350" readonly="readonly" value="<?php echo $Query_fat[0]->nm_conta; ?>"/>
        </label>

        <label>
            <span>Convênio</span>
            <input type="text" class="uk-text-left  w_350" readonly="readonly" value="<?php echo $Query_fat[0]->nm_fantasia; ?>"/>
        </label>

        <label>
            <span>Linha digitavel</span>
            <input type="text" id="line_dig" class="uk-text-left  w_350" readonly="readonly" value="<?php echo $Query_fat[0]->linha_digitavel; ?>"/>
        </label>

        <label>
            <span>Tipo de Baixa</span>
            <input type="text" class="uk-text-center  w_120" readonly="readonly" value="<?php if ($Query_fat[0]->tipo_baixa == "B") {
                echo "Bancaria";
            }
            if ($Query_fat[0]->tipo_baixa == "M") {
                echo "Manual";
            } else {
                echo "-";
            } ?>"/>
        </label>
        <div style="height:30px; width:260px; position:absolute; top:223px; right:20px; text-align:center;">
            <label>
                <span>Negociada ?</span>
                <input type="text" class="uk-text-center  w_120" readonly="readonly" value="<?php if ($Query_fat[0]->negociada == "S") {
                    echo "Sim";
                } else {
                    echo "Não";
                } ?>"/>
            </label>
        </div>

        <label>
            <span>VL Negociação</span>
            <input type="text" class="uk-text-center  w_120 uk-badge-primary" readonly="readonly" value="<?php echo number_format($Query_fat[0]->valor_negociado, 2, ",", "."); ?>"/>
        </label>

        <div style="height:30px; width:260px; position:absolute; top:258px; right:20px; text-align:center;">

            <label>
                <span>Tipo da Parcela</span>
                <input type="text" class="uk-text-center  w_120" readonly="readonly" value="<?php if ($Query_fat[0]->tipo_parcela == "M") {
                    echo "Mensalidade";
                } else {
                    echo "Adesão";
                } ?>"/>
            </label>
        </div>
        <label>
            <span>Assegurada ?</span>
            <input type="text" class="uk-text-left  w_350 <?php if ($Query_fat[0]->status == "0") {
                echo "uk-text-danger uk-text-bold";
            } elseif ($Query_fat[0]->status == "1") {
                echo "uk-text-primary uk-text-bold";
            } elseif ($Query_fat[0]->status == "2") {
                echo "uk-text-sucsess uk-text-bold";
            } elseif ($Query_fat[0]->status == "3") {
                echo "uk-text-danger uk-text-bold";
            }
            ?>" readonly="readonly" value="<?php if ($Query_fat[0]->status == "0") {
                echo "( NÃO ) ";
            } elseif ($Query_fat[0]->status == "1") {
                echo "( SIM ) ";
            } elseif ($Query_fat[0]->status == "2") {
                echo "( SIM ) ";
            } elseif ($Query_fat[0]->status == "3") {
                echo "( NÃO ) ";
            }
            echo $Query_fat[0]->obs_seg; ?>"/>
        </label>


        <label>
            <span>Ult. Alteração</span>
            <input type="text" class="uk-text-left  w_120" readonly="readonly" value="<?php echo $dtultimaalteracao->format('d/m/Y H:i:s'); ?>"/>
        </label>
        <label>
            <span>Alterado Por</span>
            <input type="text" class="uk-text-left  w_350" readonly="readonly" value="<?php echo $Query_fat[0]->nm_usuario; ?>"/>
        </label>

    </div>
    <div id="Historico" class="tab-content" style="display:none; overflow-y: scroll; height: 408px; padding-left: 10px;">
        <?php
        $Query_hist = transacoes_cards::find_by_sql("SELECT * FROM transacoes_cards WHERE  matricula = '" . $Query_fat[0]->matricula . "'");
        $List= new ArrayIterator($Query_hist);
        while ($List->valid()):
            $data = new ActiveRecord\DateTime($List->current()->created_at);
            ?>
            <article class="uk-article">
                <p class="uk-article-meta"><?= $data->format('d/m/Y'); ?></p>
                <p class="uk-article-lead">Envio de transação</p>
                <?= $List->current()->obs ?>
                <hr class="uk-article-divider">
            </article>
            <?php
            $List->next();
        endwhile;
        ?>
    </div>

</form>

<script src="framework/uikit-2.24.0/js/core/tab.min.js"></script>
<script type="text/javascript">

    jQuery("#line_dig").mask("99999.99999.99999.999999 99999.999999 9 99999999999999");
    jQuery("#vlr").maskMoney({showSymbol: true, symbol: "", decimal: ",", thousands: "."});

    //FUNÇÃO TABS
    $(".uk-tab a").click(function (event) {
        event.preventDefault();
        var tab = $(this).attr("uk-data-tab");
        $(".tab-content").not(tab).css("display", "none");
        $(tab).fadeIn();
    });

    // envia os dados para gerar os boleto ou receber
    jQuery(function () {

        jQuery("#Btn_det_0").click(function (event) {

            // mensagen de carregamento
            jQuery("#msg_loading").html(" Aguarde...");

            //abre a tela de preload
            modal.show();

            //desabilita o envento padrao do formulario
            event.preventDefault();

            var data = "action=update&id=" + jQuery("#id_p").val() + "&vlr=" + jQuery("#vlr").val() + "&dtv=" + jQuery("#dtv").val() + "&dth=" + jQuery("#dth").val();

            jQuery.ajax({
                async: true,
                url: "assets/faturamento/controllers/Controller_recebimento.php",
                type: "post",
                data: data,
                success: function (resultado) {


                    var text = '{"' + resultado + '"}';
                    var obj = JSON.parse(text);

                    // se o callback for 0 indica que não houve erro ai mostramos o resultado da execução das querys
                    if (obj.callback == 1) {

                        New_window('exclamation-triangle', '500', '250', 'Atenção', '<div style="padding: 5px; width:490px; height:240px; overflow-x:auto;">' + obj.msg + '</div>', true, true, 'Aguarde...');
                        modal.hide();

                        // se for = 1 indica que houve erro ai retornamo o erro na tela do usuario
                    } else {

                        UIkit.notify('' + obj.msg + '', {timeout: 2000, status: '' + obj.status + ''});
                        // /* id da janela dados de cobranca Frmdadoscobranca*/
                        jQuery("#" + jQuery("#FrmDetalhesParcela").closest('.Window').attr('id') + "").remove();
                        jQuery("#" + jQuery("#FrmGridFaturamentos").closest('.Window').attr('id') + "").remove();
                        New_window('list', '950', '500', 'Faturamento', 'assets/faturamento/Frm_faturamento.php?matricula=<?php echo $Query_fat[0]->matricula; ?>&convenio_id=<?php echo $Query_fat[0]->convenios_id;?>', true, false, 'Carregando...');
                    }
                },
                error: function () {
                    UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
                    modal.hide();
                }

            });
        });

    });

    /* remove o procedimento*/
    // envia os dados para gerar os boleto ou receber
    jQuery(function () {

        jQuery(".uk-remove-proc").click(function (event) {


            // mensagen de carregamento
            jQuery("#msg_loading").html(" Aguarde...");

            //abre a tela de preload
            modal.show();

            //desabilita o envento padrao do formulario
            event.preventDefault();

            var data = "action=removeproc&id=" + jQuery(this).attr("data-uk-proc");
            var lineremove = "line_proc_" + jQuery(this).attr("data-uk-proc");

            jQuery.ajax({
                async: true,
                url: "assets/faturamento/controllers/Controller_procedimento.php",
                type: "post",
                data: data,
                success: function (resultado) {

                    alert(resultado);


                    var text = '{"' + resultado + '"}';
                    var obj = JSON.parse(text);

                    // se o callback for 0 indica que não houve erro ai mostramos o resultado da execução das querys
                    if (obj.callback == 1) {

                        UIkit.notify('' + obj.msg + '', {timeout: 2000, status: '' + obj.status + ''});
                        jQuery("#" + lineremove + "").remove();
                        modal.hide();

                        // se for = 1 indica que houve erro ai retornamo o erro na tela do usuario
                    } else {

                        UIkit.notify('' + obj.msg + '', {timeout: 2000, status: '' + obj.status + ''});

                    }
                },
                error: function () {
                    UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
                    modal.hide();
                }

            });
        });

    });


</script>