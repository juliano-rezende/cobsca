<?php
require_once("../../sessao.php");
?>
<div class="tabs-spacer" style="display:none;">

    <?php
    require_once("../../conexao.php");
    $cfg->set_model_directory('../../models/');

    $FRM_referencia = isset($_GET['ref']) ? $_GET['ref'] : tool::msg_erros("O Campo ref é Obrigatorio.");


    /* definimos o ano para o botão voltar*/
    $year = explode("-", $FRM_referencia);


    // retorna todo os registros para montar a paginação
    $Query_all = faturamentos::find_by_sql("SELECT titulos_bancarios.vlr_tarifa,associados.nm_associado,faturamentos.matricula,faturamentos.dt_vencimento,faturamentos.dt_pagamento,faturamentos.valor,faturamentos.obs,convenios.nm_fantasia,faturamentos.tipo_parcela
                                                FROM faturamentos
                                                LEFT JOIN associados ON faturamentos.matricula = associados.matricula
                                                LEFT JOIN titulos_bancarios ON titulos_bancarios.id=faturamentos.titulos_bancarios_id
                                                LEFT JOIN convenios ON associados.convenios_id = convenios.id
                                                WHERE faturamentos.dt_pagamento between '" . $FRM_referencia . "-01' AND '" . $FRM_referencia . "-31' AND faturamentos.status='1' AND faturamentos.empresas_id='" . $COB_Empresa_Id . "' ORDER BY faturamentos.matricula,faturamentos.tipo_parcela ASC");


    $List_detalhes = new ArrayIterator($Query_all);

    ?>
</div>
<div class="uk-modal-header">
    <a href="JavaScript:void(0);" id="Btn_Det_Rec_voltar" class="uk-button uk-button-success" uk-data-year="<?php echo $year[0]; ?>"><i class="uk-icon-angle-double-left"></i> Voltar</a>
</div>

<nav class="uk-navbar ">
    <table class="uk-table">
        <thead>
        <tr style="line-height:25px;">
            <th class="uk-width uk-text-center" style="width:30px;"></th>
            <th class="uk-width uk-text-center" style="width:100px;">Matricula</th>
            <th class="uk-width uk-text-left" style="width:220px;">Sacado</th>
            <th class="uk-width uk-text-center" style="width:100px;">Dt Venc</th>
            <th class="uk-width uk-text-center" style="width:100px;">Dt Pgto</th>
            <th class="uk-width uk-text-center" style="width:90px;">Valor</th>
            <th class="uk-width uk-text-center" style="width:90px;">Tarifa</th>
            <th class="uk-text-left">Convênio</th>
            <th class="uk-text-left">Tipo</th>
        </tr>
        </thead>
    </table>
</nav>

<div style="width: 100%; overflow-x: auto; height: 455px; padding-right: 10px;">

    <table class="uk-table uk-table-striped uk-table-hover">
        <tbody>
        <?php
        $linha = 0;
        $total = 0;
        $tarifa = 0;

        $a = 0;
        $m = 0;

        while ($List_detalhes->valid()):

            $linha++;
            $dt_venc = new ActiveRecord\DateTime($List_detalhes->current()->dt_vencimento);
            $dt_pgto = new ActiveRecord\DateTime($List_detalhes->current()->dt_pagamento);


            ?>

            <tr style="line-height:23px; <?php echo $color_error; ?>">
                <th class="uk-width uk-text-center" style="width:30px;"><?php echo $linha; ?></th>
                <td class="uk-width uk-text-center" style="width:100px;"><?php echo $List_detalhes->current()->matricula; ?></td>
                <td class="uk-width uk-text-left uk-text-overflow uk-text-uppercase" style="width:220px;max-width: 220px;"><?php echo $List_detalhes->current()->nm_associado; ?></td>
                <td class="uk-width uk-text-center" style="width:100px;"><?php echo $dt_venc->format('d/m/Y'); ?></td>
                <td class="uk-width uk-text-center" style="width:100px;"><?php echo $dt_pgto->format('d/m/Y'); ?></td>
                <td class="uk-width uk-text-center" style="width:90px;"><?php echo number_format($List_detalhes->current()->valor, 2, ',', '.'); ?></td>
                <td class="uk-width uk-text-center" style="width:90px;"><?php echo number_format($List_detalhes->current()->vlr_tarifa, 2, ',', '.'); ?></td>
                <td class="uk-text-left uk-text-uppercase"><?php echo $List_detalhes->current()->nm_fantasia; ?></td>
                <?php
                if($List_detalhes->current()->tipo_parcela == "M"):
                    $m++;
                ?>
                <td class="uk-text-left uk-text-uppercase uk-text-success">[<?php echo $List_detalhes->current()->tipo_parcela; ?>]</td>
                <?php
                else:
                    $a++;
                ?>
                <td class="uk-text-left uk-text-uppercase uk-text-warning">[<?php echo $List_detalhes->current()->tipo_parcela; ?>]</td>
                <?php
                endif;
                ?>
            </tr>
            <?php
            $total += $List_detalhes->current()->valor;
            $tarifa += $List_detalhes->current()->vlr_tarifa;
            $List_detalhes->next();
        endwhile;
        ?>
        </tbody>
        <tfoot>
        <tr style="line-height:25px;">
            <th class="uk-width uk-text-center" style="width:30px;"></th>
            <th class="uk-width uk-text-center" style="width:100px;"></th>
            <th class="uk-width uk-text-left" style="width:220px;">Total</th>
            <th class="uk-width uk-text-center" style="width:100px;"></th>
            <th class="uk-width uk-text-center" style="width:100px;"></th>
            <th class="uk-width uk-text-center" style="width:90px;"> <?php echo number_format($total, 2, ',', '.'); ?></th>
            <th class="uk-width uk-text-center" style="width:90px;"><?php echo number_format($tarifa, 2, ',', '.'); ?></th>
            <th class="uk-text-right"  colspan="2">Média tarifas <?php if ($tarifa != 0) {
                    echo number_format($tarifa / $linha, 2, ',', '.');
                } else {
                    echo "0,00";
                } ?></th>
        </tr>
        <tr style="line-height:25px;">
            <th class="uk-width uk-text-center" style="width:30px;"></th>
            <th class="uk-width uk-text-center" style="width:100px;"></th>
            <th class="uk-width uk-text-left" style="width:220px;"></th>
            <th class="uk-width uk-text-center" style="width:100px;"></th>
            <th class="uk-width uk-text-center" style="width:100px;"></th>
            <th class="uk-width uk-text-center" style="width:90px;"></th>
            <th class="uk-width uk-text-center" style="width:90px;"></th>
            <th class="uk-text-right uk-text-warning"  colspan="2">Adesões: <?php echo $a; ?></th>
        </tr>
        <tr style="line-height:25px;">
            <th class="uk-width uk-text-center" style="width:30px;"></th>
            <th class="uk-width uk-text-center" style="width:100px;"></th>
            <th class="uk-width uk-text-left" style="width:220px;"></th>
            <th class="uk-width uk-text-center" style="width:100px;"></th>
            <th class="uk-width uk-text-center" style="width:100px;"></th>
            <th class="uk-width uk-text-center" style="width:90px;"> </th>
            <th class="uk-width uk-text-center" style="width:90px;"></th>
            <th class="uk-text-right uk-text-success" colspan="2">Mensalidades: <?php echo $m; ?></th>
        </tr>
        </tfoot>
    </table>
</div>
<script type="text/javascript">

    /* botão retroceder year*/
    jQuery("#Btn_Det_Rec_voltar").click(function (event) {
        event.preventDefault();
        var year = jQuery(this).attr("uk-data-year");
        jQuery(".uk-modal-det").html('<i class="uk-icon-spinner uk-icon-spin"></i><span > Carregando </span>').load("assets/dashboard/Grid_recebimentos.php?year=" + year + "");
    });

</script>
