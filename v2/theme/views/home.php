<?php $v->layout("_theme"); ?>

<?php $v->start("css"); ?>

<!-- Data table css -->
<link href="<?= url("theme/admin"); ?>/plugins/datatable/dataTables.bootstrap4.min.css" rel="stylesheet"/>
<link href="<?= url("theme/admin"); ?>/plugins/datatable/responsivebootstrap4.min.css" rel="stylesheet"/>
<style>
    .item .tag {
        border-radius: 0;
        margin-left: 6px;
    }
</style>
<?php $v->end(); ?>

<!-- Grid Modal -->
<div class="modal fade" id="detailsTransation" tabindex="-1" role="dialog" aria-labelledby="detailsTransation" aria-hidden="true">

</div>

<!-- page-header -->
<div class="page-header">
    <ol class="breadcrumb breadcrumb-arrow mt-3">
        <li class="active"><span>Home</span></li>
    </ol>
    <!-- End breadcrumb -->
</div><!-- End page-header -->

<!--Row-->
<div class="row">
    <div class="col-md-12">
        <h2>Últimos pedidos</h2>
    </div>
</div><!-- row end -->
<!-- row -->
<div class="row">
    <div class="col-md-12">
        <div class="owl-carousel owl-carousel2 owl-theme mb-5">
            <?php

            use Source\App\FuturaApi\ControllerTransactionsFutura;

            $today = date('Y-m-d');
            $today = implode("/", array_reverse(explode("-", $today)));

            $bgInit = 0;
            $textInit = 0;
            $seqInit = 0;
            $repeat = 0;
            $seqOrder = 1;
            foreach ($last10Transations as $transation):

                $bgs = array("failed" => "warning-transparent", "succeeded" => "success-transparent", "success" => "success-transparent", "canceled" => "danger-transparent");
                $txt = array("failed" => "warning", "succeeded" => "success", "success" => "success", "canceled" => "danger");
                $icon = array("failed" => "close", "succeeded" => "check", "success" => "check", "canceled" => "close");
                $txttag = array("failed" => "Falhada", "succeeded" => "Aprovada", "success" => "Aprovada", "canceled" => "Cancelada");

                $orderId = preg_replace("/[^0-9]/", "", $transation->description);

                if ($orderId == $repeat) {
                    $seqOrder++;
                } else {
                    $seqOrder = 1;
                }
                ?>
                <div class="item">
                    <span class="tag tag-<?= $txt["{$transation->status}"]; ?>"><?= $seqOrder . "º Tentativa"; ?></span>
                    <div class="card mb-0">
                        <div class="row">
                            <div class="col-4">
                                <div class="feature">
                                    <div class="fa-stack fa-lg fa-2x icon bg-<?= $bgs["{$transation->status}"]; ?>">
                                        <i class="ti ti-<?= $icon["{$transation->status}"]; ?>  fa-stack-1x text-<?= $txt["{$transation->status}"]; ?>"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="card-body p-3  d-flex">
                                    <div>
                                        <!-- R$ --><?php //$transation->amount;
                                        ?>
                                        <p class="text-muted mb-1">Pedido nº</p>
                                        <h2 class="mb-0 text-dark"><?= $orderId; ?></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- col end -->
                <?php
                $repeat = $orderId;
                $seqInit++;
            endforeach;
            ?>
        </div>
    </div>
</div><!-- row end -->

<!--Row-->
<div class="row">
    <div class="col-md-12">
        <h2>Transações</h2>
    </div>
</div><!-- row end -->
<!--Row-->
<div class="row">
    <div class="col-xl-4 p-0 pr-lg-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Vendas aprovadas no dia</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col d-flex">
                        <h3 class="mt-3 ml-3 mb-0">Balanço</span></h3>
                    </div>
                    <div class="col col-auto">
                        <h3 class="font-weight-semibold mb-0 mt-2 text-success">R$ <?= number_format(($statesSales->total_day)->amount, 2, ",", "."); ?></h3>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <span class="fs-14">Pedidos</span>
                        <h2 class="mt-1 mb-0"><?= ($statesSales->total_day)->qte; ?> <span class="fs-14"> | <?php echo ($statesSales->total_day)->percent_day; ?> %</span></h2>
                    </div>
                    <div class="col col-auto week-sales">
                        <div class="d-flex"></div>
                        <p class="mb-0 mt-2"><span class="fs-14 font-weight-semibold"><?= date('d/m', strtotime('-3 hour', strtotime(date("d-m-Y")))); ?></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 p-0 pl-lg-2 pr-lg-2">
        <div class="card ">
            <div class="card-header">
                <h3 class="card-title">Vendas aprovadas no mês</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col d-flex">
                        <h3 class="mt-3 ml-3 mb-0">Balanço</h3>
                    </div>
                    <div class="col col-auto">
                        <h3 class="font-weight-semibold mb-0 mt-2 text-cyan">R$ <?= number_format(($statesSales->total_month)->amount, 2, ",", "."); ?></h3>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <span class="fs-14">Pedidos</span>
                        <h2 class="mt-1 mb-0"><?= ($statesSales->total_month)->qte; ?> <span class="fs-14"> | <?php echo ($statesSales->total_month)->percent_month; ?> %</span></h2>
                    </div>
                    <div class="col col-auto week-sales">
                        <div class="d-flex"></div>
                        <p class="mb-0 mt-2"><span class="fs-14 font-weight-semibold"><?= date("m/Y"); ?></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 p-0 pl-lg-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Vendas aprovadas acumuladas</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col d-flex">
                        <h3 class="mt-3 ml-3 mb-0">Balanço</h3>
                    </div>
                    <div class="col col-auto">
                        <h3 class="font-weight-semibold mb-0 mt-2 text-azure">R$ <?= number_format(($statesSales->total_all)->amount, 2, ",", "."); ?></h3>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <span class="fs-14">Pedidos</span>
                        <h2 class="mt-1 mb-0"><?= ($statesSales->total_all)->qte;?></h2>
                    </div>
                    <div class="col col-auto week-sales">
                        <div class="d-flex"></div>
                        <p class="mb-0 mt-2"><span class="fs-14 font-weight-semibold"><?= date("Y"); ?></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- row end -->

<!-- row -->
<div class="row">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Movimentação em: <?= $today; ?></h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <?php
                    $amountTodaySuccess = 0;
                    $amountTodayFail = 0;
                    $amountTodayCancel = 0;
                    $amountTodaySuccessQte = 0;
                    $amountTodayFailQte = 0;
                    $amountTodayCancelQte = 0;
                    $setOrder0 = 0;
                    $arrSuccess = array();

                    foreach ($todayTransations as $todaytransation):

                        $metadata = $todaytransation->metadata;

                        $orderPay = preg_replace("/[^0-9]/", "", $todaytransation->description);

                        if ($todaytransation->status == "succeeded" or $todaytransation->status == "success"):

                            $amountTodaySuccess += $todaytransation->amount;
                            $amountTodaySuccessQte++;

                        elseif ($todaytransation->status == "failed"):

                            if ($setOrder0 != $orderPay) :

                                $amountTodayFail += $todaytransation->amount;
                                $amountTodayFailQte++;
                                $setOrder0 = $orderPay;

                            endif;

                        elseif ($todaytransation->status == "canceled"):

                            if ($setOrder0 != $orderPay) :

                                $amountTodayCancel += $todaytransation->amount;
                                $amountTodayCancelQte++;
                                $setOrder0 = $orderPay;

                            endif;
                        else:
                            $setOrder0 = 0;
                        endif;
                    endforeach;
                    ?>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex clearfix">
                                                <div class="text-left mt-3">
                                                    <p class="card-text text-muted mb-1">Vendas aprovadas</p>
                                                    <h2 class="mb-0 text-dark mainvalue"><?= $amountTodaySuccessQte; ?></h2>
                                                </div>
                                                <div class="ml-auto">
                                                <span class="bg-success-transparent icon-service text-success">
                                                    <i class="fa fa-shopping-cart fs-2"></i>
                                                </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex clearfix">
                                                <div class="text-left mt-3">
                                                    <p class="card-text text-muted mb-1">Vendas falhadas</p>
                                                    <h2 class="mb-0 text-dark mainvalue"><?= $amountTodayFailQte; ?></h2>
                                                </div>
                                                <div class="ml-auto">
                                            <span class="bg-warning-transparent icon-service text-warning">
                                                <i class="fa fa-shopping-cart fs-2"></i>
                                            </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex clearfix">
                                                <div class="text-left mt-3">
                                                    <p class="card-text text-muted mb-1">Vendas canceladas</p>
                                                    <h2 class="mb-0 text-dark mainvalue"><?= $amountTodayCancelQte; ?></h2>
                                                </div>
                                                <div class="ml-auto">
                                                <span class="bg-danger-transparent icon-service text-danger">
                                                    <i class="fa fa-shopping-cart fs-2"></i>
                                                </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-8 col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div id="salesComparison"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- row end -->

<!-- row --><!--<div class="row">--><!--    <div class="card">--><!--        <div class="card-header">--><!--            <h3 class="card-title">Créditos programados</h3>--><!--        </div>-->
<!--        <div class="card-body">--><!--            <div class="row">--><!--                <div class="table-responsive">-->
<!--                    <table class="table table-bordered align-items-center">--><!--                        <tbody>--><!--                        --><?php
//                        $lines = 0;
//                        $limit = "";
//                        $total_amount = 0;
//                        $total_fees = 0;
//                        $total_net = 0;
//                        $releasesItem = 0;
//                        foreach ($creditFutures->items as $key => $creditfuture):
//
//                            $date = $release_dt = date("d/m/Y", strtotime($key));
//
//                            foreach ($creditfuture->items as $item):
//                                if($item->status === "succeeded"){
//                                    $limit++;
//                                    $releasesItem++;
//                                    $total_amount+= number_format((intval($item->amount)/100), 2, ".", "");
//                                    $total_fees+= number_format((intval($item->fees)/100), 2, ".", "");
//                                    $total_net+= number_format((intval($item->net)/100), 2, ".", "");
//                                }
//                            endforeach;
//                            ?>
<!--                            <tr>--><!--                                <td>--><? //= $release_dt ?><!--</td>-->
<!--                                <td class="text-right font-weight-bold">R$ --><? //= number_format($total_net, 2, ",", "."); ?><!--</td>--><!--                            </tr>-->
<!--                            --><?php
//                            $lines++;
//                            $total_amount = 0;
//                            $total_fees = 0;
//                            $total_net = 0;
//
//                            if($lines == 10){
//                                break;}
//                        endforeach;
//                        ?>
<!--                        </tbody>--><!--                    </table>--><!--                </div>--><!--            </div>--><!--        </div>--><!--    </div>--><!--</div>--><!--End Row-->

<?php
$lines = 0;
$limit = "";
$total_net = 0;
$release_dt = "";

foreach ($creditFutures->items as $key => $creditfuture):
    $release_dt = date("d/m/Y", strtotime($key));
    if (strtotime(date("Y-m-d", strtotime($key))) < strtotime(date("Y-m-d"))) {
        continue;
    }
    $total_net = $creditfuture->total_amount;
    $lines++;
    if ($lines == 1) {
        break;
    }
endforeach;
?>

<?php

if($disputa == true){
    $v->start("notification_ball");

    ?>
    <a class="nav-link icon" data-toggle="dropdown" aria-expanded="false">
        <i class="fe fe-bell "></i>
        <span class="pulse bg-danger"></span>
    </a>
    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow ">
        <a href="#" class="dropdown-item text-center">Notificações</a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item d-flex pb-3">
            <div class="notifyimg bg-orange">
                <i class="fa fa-exclamation"></i>
            </div>
            <div>
                <strong>Disputas</strong>
                <div class="small text-muted">Você possui disputa em andamento.</div>
            </div>
        </a>
    </div>
    <?php
    $v->end();
}
?>

<?php $v->start("notification_bar"); ?>

<div class="row">
    <div class="col d-flex">
        <h5 class="mt-3 ml-3 mb-0">Próximo crédito<span class="fs-14"> <?= $release_dt ?></span></h5>
    </div>
    <div class="col col-auto">
        <h3 class="font-weight-semibold mb-0 mt-2">R$ <?= number_format($total_net, 2, ",", "."); ?></h3>
    </div>
</div>
<?php $v->end(); ?>
<!-- row end -->


<div class="row">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Histórico de Transações em <?= $today; ?></h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="table-responsive">
                    <table id="transationGrid" class="table table-striped table-bordered text-nowrap w-100">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th class="text-center">Ações</th>
                            <th class="text-center">Pedido ID</th>
                            <th class="text-center">Bandeira</th>
                            <th class="text-center" style="display: none">Valor transação</th>
                            <th class="text-center" style="display: none">Taxas</th>
                            <th class="text-center" style="display: none">Valor Liquido</th>
                            <th class="text-center ">Parcelas</th>
                            <th class="text-center">Data Crédito</th>
                            <th class="text-center">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $seqInit = 1;
                        $setOrder = 0;

                        foreach ($todayTransations as $todaytransation):

                            $orderPay = preg_replace("/[^0-9]/", "", $todaytransation->description);
                            if ($todaytransation->status == "failed"):
                                if ($setOrder != $orderPay) :
                                    $setOrder = $orderPay;
                                else:
                                    continue;
                                endif;
                            endif;
                            $expectedOn = "-";

                            /**
                             * calcula as taxas
                             */
                            $fees = $todaytransation->fees;
                            $splits = 0;

                            foreach ($todaytransation->split_rules as $split):
                                $splits += $split->receivable_amount;
                            endforeach;

                            $txSplit = $splits + $fees;

                            if ($todaytransation->status == "success" or $todaytransation->status == "succeeded") {

                                $vlrT = $todaytransation->amount;
                                $taxas = $txSplit;
                                $vlrLq = ($todaytransation->amount - $txSplit);
                                $parc = ($todaytransation->installment_plan)->number_installments;

                                $expectedOn = explode("T", $todaytransation->expected_on);
                                $dtCred = implode("/", array_reverse(explode("-", $expectedOn[0])));

                            } else {
                                $vlrT = 0;
                                $taxas = 0;
                                $vlrLq = 0;
                                $parc = "-";
                                $dtCred = "-";
                            }

                            ?>
                            <tr>
                                <td class="text-center"><?= $seqInit; ?></td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-info" href="#details" data-action="<?= $router->route("transaction.details"); ?>" data-transation="<?= $todaytransation->id; ?>"
                                       data-status="<?= $todaytransation->status; ?>" data-btn=true>
                                        <i class="fa fa-info-circle"></i> Detalhes</a>
                                </td>
                                <td class="text-center"><?= preg_replace("/[^0-9]/", "", $todaytransation->description); ?></td>
                                <td class="text-center"><?= ($todaytransation->payment_method)->card_brand; ?></td>
                                <td class="text-right" style="display: none"><?= number_format($vlrT, 2, ",", "."); ?></td>
                                <td class="text-center" style="display: none"><?= number_format($taxas, 2, ",", "."); ?></td>
                                <td class="text-center" style="display: none"><?= number_format($vlrLq, 2, ",", "."); ?></td>
                                <td class="text-center"><?= $parc; ?></td>
                                <td class="text-center"><?= $dtCred; ?></td>
                                <td class="text-center">
                                    <span class="tag tag-<?= (new ControllerTransactionsFutura())->colorTextStatus($todaytransation->status); ?>"><?= (new ControllerTransactionsFutura())->textStatus($todaytransation->status); ?></span>
                                </td>
                            </tr>
                            <?php
                            $seqInit++;
                        endforeach;
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div><!--End Row-->

<!-- row -->
<div class="row">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Vendas por estado</h3>
        </div>
        <div class="card-body">
            <div id="stateSales"></div>
        </div>
    </div>
</div><!-- row end -->

<?php $v->start("scripts"); ?>

<!-- Data tables js-->
<script src="<?= url("theme/admin"); ?>/plugins/datatable/jquery.dataTables.min.js"></script>
<script src="<?= url("theme/admin"); ?>/plugins/datatable/dataTables.bootstrap4.min.js"></script>
<script src="<?= url("theme/admin"); ?>/plugins/datatable/dataTables.responsive.min.js"></script>

<script type="application/javascript">

    $(function (e) {
        $('#transationGrid').DataTable({
            'paging': true, 'lengthChange': true, 'searching': true, 'ordering': true, 'info': true, 'autoWidth': true, "language": {
                "search": "Nº do pedido: _INPUT_",
                "lengthMenu": "",
                "zeroRecords": "Nada encontrado - desculpe",
                "info": "Páginas _PAGE_ de _PAGES_",
                "infoEmpty": "Nenhum registro disponível",
                "infoFiltered": "(filtrado do total de _MAX_ registros)",
                "paginate": {
                    "previous": "Anterior", "next": "Proximo"
                }
            }
        });
    });

    $(function (e) {
        'use strict';
        /* ---hightchart7----*/

        var chart = Highcharts.chart('salesComparison', {
            chart: {
                height: 455
            }, title: {
                text: 'Comparativo'
            }, subtitle: {
                text: ''
            }, exporting: {enabled: false}, yAxis: [{
                title: {
                    text: 'Quantidade'
                }
            }], credits: {
                enabled: false
            }, xAxis: {
                categories: ['Aprovadas', 'Falhadas', 'Canceladas']
            }, colors: ['#64E572', '#f1c40f', '#ff5c75'], series: [{
                type: 'column', colorByPoint: true, data: [<?=$amountTodaySuccessQte;?>, <?=$amountTodayFailQte;?>, <?=$amountTodayCancelQte;?>], showInLegend: false
            }]
        });
    });
    $(function (e) {
        'use strict';
        /* ---hightchart7----*/

        var chart = Highcharts.chart('stateSales', {
            chart: {
                height: 455
            }, title: {
                text: 'Vendas total <?=$statesSales->total;?>'
            }, subtitle: {
                text: 'Acumulado no mês'
            }, exporting: {enabled: false}, yAxis: [{
                title: {
                    text: 'Quantidade'
                }
            }], credits: {
                enabled: false
            }, xAxis: {
                categories: [
                    <?php
                    foreach ($statesSales->states as $state) {
                        echo "'" . $state . "',";
                    }
                    ?>
                ]
            }, series: [{
                type: 'column', colorByPoint: true, data: [
                    <?php
                    foreach ($statesSales->values as $value) {
                        echo "" . $value . ",";
                    }
                    ?>
                ], showInLegend: false
            }]
        });
    });

    $("body").on("click", "[data-action]", function (e) {

        e.preventDefault();

        var $this = $(this);
        var data = $this.data();

        var modal = "detailsTransation";
        var action = $this.attr("data-action");

        $.ajax({
            url: action, type: "POST", data: data, dataType: "JSON", beforeSend: function () {
                $("#global-loader").fadeIn();
            }, success: function (callback) {
                /*INJECT FRAGMENT */
                $("#" + modal + "").html(callback.details);
            }, fail: function () {
                swal({
                    title: "Opsssss", text: "Ocorreu um erro ao processar informação. Entre em contato com suporte.", type: "error"
                });
            }, complete: function () {
                $("#" + modal + "").modal();
                $("#global-loader").fadeOut();
            }
        });
    });

</script>
<?php $v->end(); ?>
