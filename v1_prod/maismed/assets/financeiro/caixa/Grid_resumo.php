<?php /* biblioteca*/require_once("../../../sessao.php");?>
<div class="tabs-spacer" style="display:none;">
    <?php
    require_once("../../../conexao.php");
    $cfg->set_model_directory('../../../models/');

/*
 * <option value="7">Hoje</option>
                      <option value="1">Mês atual</option>
                      <option value="2">Ultimos 7 dias</option>
                      <option value="3">Ultimos 15 dias</option>
                      <option value="4">Ultimos 30 dias</option>
                      <option value="5">Mes Anterior</option>
                      <option value="6">Personalizado</option>
 */
if (isset($_GET['periodo'])) {

    $intervalo = $_GET['periodo'];

    $ini = date('Y-m', strtotime('-1 months', strtotime(date('Y-m-d')))) . "-01";
    $fim = date("Y-m-t", strtotime("-1 Month", strtotime(date('Y/m/d H:i:s'))));

    if ($intervalo == 0) {
        $periodo = "caixa.data Between '" . date("Y-m") . "-01' And '" . date("Y-m") . "-31'";
        $saldoanterior = date("Y-m") . '-01';
        $periodoS = "Mês Atual";
    }// mes atual
    elseif ($intervalo == 1) {
        $periodo = "caixa.data Between '" . date("Y-m") . "-01' And '" . date("Y-m") . "-31'";
        $saldoanterior = date("Y-m") . '-01';
        $periodoS = "Mês Atual";
    }//mes atual
    elseif ($intervalo == 2) {
        $periodo = "caixa.data Between '" . tool::subDayIntoDate(date("Ymd"), 7) . "' And '" . date("Y-m") . "-31'";
        $saldoanterior = tool::subDayIntoDate(date("Ymd"), 7);
        $periodoS = "Ultimos 7 dias";
    }// 7 dias
    elseif ($intervalo == 3) {
        $periodo = "caixa.data Between '" . tool::subDayIntoDate(date("Ymd"), 15) . "' And '" . date("Y-m-d") . "'";
        $saldoanterior = tool::subDayIntoDate(date("Ymd"), 15);
        $periodoS = "Ultimos 15 dias";
    }// 15 dias
    elseif ($intervalo == 4) {
        $periodo = "caixa.data Between '" . tool::subDayIntoDate(date("Ymd"), 30) . "' And '" . date("Y-m-d") . "'";
        $saldoanterior = tool::subDayIntoDate(date("Ymd"), 30);
        $periodoS = "Ultimos 30 dias";
    }// 30 dias
    elseif ($intervalo == 5) {
        $periodo="AND caixa.data Between '".$ini."' And '".$fim."'";
        $saldoanterior=$ini;
        $periodoS = "Mês anterior";
    }// mes anterior
    elseif ($intervalo == 6) {
        $periodo = "caixa.data Between '" . tool::InvertDateTime(tool::LimpaString($_GET['inicio']), 0) . "' And '" . tool::InvertDateTime(tool::LimpaString($_GET['final']), 0) . "'";
        $saldoanterior = tool::InvertDateTime(tool::LimpaString($_GET['inicio']), 0);
        $periodoS = tool::InvertDateTime(tool::LimpaString($_GET['inicio']), 1)  . " à " . tool::InvertDateTime(tool::LimpaString($_GET['final']), 1);
    }// periodo especifico
    elseif ($intervalo == 7) {
        $periodo = "caixa.data Between '" . date("Y-m-d") . "' And '" . date("Y-m-d") . "'";
        $saldoanterior = date("Y-m-d");
        $periodoS = date("d/m/Y")  . " à " .  date("d/m/Y");
    }// periodo especifico
}

if (empty($_GET['operador'])) {
    $operador = "";
} else {
    $operador = $_GET['operador'];
}


    echo"<div class=\"tabs-spacer\" style=\"display:none;\">";
$query_c = users::find_by_sql("SELECT * FROM usuarios WHERE empresas_id='" . $COB_Empresa_Id . "' AND acessos_id < '4' AND status = '1' ORDER BY id ASC ");
    echo"</div>";
$usuarios = new ArrayIterator($query_c);

$op = 1;
?>
</div>
<div style="width: 900px; height: 550px; overflow-y: auto;">
    <h5 class="uk-comment-title uk-text-bold uk-margin uk-gradient-blue" style="padding-left: 10px; line-height: 40px;">Período: <?=$periodoS ;?></h5>
<?php
while ($usuarios->valid()) : ?>
<h1 class="uk-comment-title uk-text-bold uk-margin uk-gradient-cinza" style="padding-left: 10px; line-height: 30px; font-size: 14px;"><?= $op . "º - " . $usuarios->current()->nm_usuario; ?></h1>
    <?php
    echo"<div class=\"tabs-spacer\" style=\"display:none;\">";
    $query = caixa::find_by_sql("SELECT SQL_CACHE
                                        caixa.id,
                                        caixa.usuarios_id,
                                        caixa.formas_recebimentos_id,
                                        formas_recebimento_sys.descricao,
                                        formas_recebimentos.formas_recebimento_sys_id ,
                                        formas_recebimento_sys.descricao,
                                        sum(caixa.valor) as valor
                                    FROM
                                        caixa
                                        LEFT JOIN formas_recebimentos ON formas_recebimentos.id = caixa.formas_recebimentos_id
                                        LEFT JOIN formas_recebimento_sys ON formas_recebimento_sys.id = formas_recebimentos.formas_recebimento_sys_id
                                    WHERE
                                        " . $periodo . "  AND caixa.empresas_id='" . $COB_Empresa_Id . "' AND usuarios_id='" . $usuarios->current()->id . "' AND tipo='c' GROUP BY usuarios_id,formas_recebimentos_id");

    echo"</div>";
    $linhacaixa = new ArrayIterator($query);
    echo "<ul class=\"uk-list uk-list-line\">";
    while ($linhacaixa->valid()):
        echo "<li style='padding-left: 20px; font-size: 11px;'>".$linhacaixa->current()->descricao. "  <span class='uk-float-right uk-text-bold' style='padding-right: 5px;'>R$ ".number_format($linhacaixa->current()->valor,2,",",".")."</span></li>";
        $linhacaixa->next();
    endwhile;

    echo "</ul>";

    $op++;

    $usuarios->next();
    endwhile;
    ?>
</div>
