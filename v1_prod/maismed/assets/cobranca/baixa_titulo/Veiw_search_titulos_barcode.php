<?php
require_once("../../../sessao.php");
?>
<div class="tabs-spacer" style="display:none;">
    <?php
    //classe de validação de cnpj e cpf
    require_once("../../../classes/valid_cpf_cnpj.php");
    require_once("../../../conexao.php");
    $cfg->set_model_directory('../../../models/');
    ?>
</div>
<?php

// recupera as taxas configuradas na empresa
$dados_config = configs::find_by_empresas_id($COB_Empresa_Id);

$FRM_vl = isset($_POST['search']) ? preg_replace("/[^0-9]/", "", $_POST['search']) : "";

// vericamos se não está vazio antes de qualquer coisa
if ($FRM_vl == "") {
    echo '<span style="width:80%; display:block; text-align:center; margin: 10px auto;" class=" uk-alert uk-alert-danger">Não é possivel realizar sua pesquisa dados insuficientes.</span>';
    exit();
}
if (!is_numeric($FRM_vl)) {
    echo '<span style="width:80%; display:block; text-align:center; margin: 10px auto;" class=" uk-alert uk-alert-danger">Codigo de barras invalido digitos.</span>';
    exit();
}

//boleto antigo
if (isset($_POST['tp'])) {

    $tipo_pessoa = $_POST['tp'];

    if ($tipo_pessoa == 1) {

        $dt_cadastro = substr($FRM_vl, 0, 4);
        $id_empresa = substr($FRM_vl, 4, 2);
        $matricula = substr($FRM_vl, 6, 11);
        $dt_referencia = "20" . substr($FRM_vl, 19, 2) . substr($FRM_vl, 17, 2) . "01";

        if(strlen($FRM_vl) == 19){

            $valor = substr($FRM_vl, 20, 7);
            echo '<span style="width:80%; display:block; text-align:center; margin: 10px auto;" class=" uk-alert uk-alert-danger">'.$valor.'.</span>';
            exit();

        }else {

            $valor = substr($FRM_vl, 21, 8);
            echo '<span style="width:80%; display:block; text-align:center; margin: 10px auto;" class=" uk-alert uk-alert-danger">'.$valor.'.</span>';
            exit();
        }


        $query = "faturamentos.matricula = '" . $matricula . "' AND faturamentos.referencia = '" . $dt_referencia . "' OR (YEAR(faturamentos.referencia) = ".substr($FRM_vl, 19, 2)." AND MONTH(faturamentos.referencia) = ".substr($FRM_vl, 17, 2).") AND  faturamentos.status  = '0' AND dados_cobranca.status = 1 ORDER BY faturamentos.referencia";

    }
    if ($tipo_pessoa == 2) {

        $dt_cadastro = substr($FRM_vl, 0, 4);
        $id_empresa = substr($FRM_vl, 4, 2);
        $id_convenio= substr($FRM_vl, 6, 11);
        $dt_referencia = "20" . substr($FRM_vl, 19, 2) .substr($FRM_vl, 17, 2) . "01";
        if(strlen($FRM_vl) == 19){
            $valor = substr($FRM_vl, 20, 7);
        }else {
            $valor = substr($FRM_vl, 21, 8);
        }

        $query = "faturamentos.convenios_id = '" . $id_convenio . "' AND faturamentos.referencia = '" . $dt_referencia . "' OR (YEAR(faturamentos.referencia) = ".substr($FRM_vl, 19, 2)." AND MONTH(faturamentos.referencia) = ".substr($FRM_vl, 17, 2).")  AND  faturamentos.status = '0' AND dados_cobranca.status = 1 ORDER BY faturamentos.referencia";

    }


//boleto novo
} else {

    $tipo_pessoa = substr($FRM_vl, 0, 1);

    if ($tipo_pessoa == 1) {

        $dt_cadastro = substr($FRM_vl, 1, 4);
        $id_empresa = substr($FRM_vl, 5, 2);
        $matricula = substr($FRM_vl, 7, 11);
        $dt_referencia = "20" . substr($FRM_vl, 20, 2) . substr($FRM_vl, 18, 2) . "01";
        $valor = substr($FRM_vl, 22, 8);

        $query = "faturamentos.matricula = '" . $matricula . "' AND faturamentos.referencia = '" . $dt_referencia . "' OR (YEAR(faturamentos.referencia) = ".substr($FRM_vl, 20, 2)." AND MONTH(faturamentos.referencia) = ".substr($FRM_vl, 18, 2).")  AND  faturamentos.status = '0' AND dados_cobranca.status = 1 ORDER BY faturamentos.referencia";
    }
    if ($tipo_pessoa == 2) {

        $dt_cadastro = substr($FRM_vl, 1, 4);
        $id_empresa = substr($FRM_vl, 5, 2);
        $id_convenio= substr($FRM_vl, 7, 11);
        $dt_referencia = "20" . substr($FRM_vl, 20, 2) .substr($FRM_vl, 18, 2) . "01";
        $valor = substr($FRM_vl, 22, 8);

        $query = "faturamentos.convenios_id = '" . $id_convenio . "' AND (faturamentos.referencia = '" . $dt_referencia . "' OR (YEAR(faturamentos.referencia) = ".substr($FRM_vl, 20, 2)." AND MONTH(faturamentos.referencia) = ".substr($FRM_vl, 18, 2).") AND  faturamentos.status = '0' AND dados_cobranca.status = 1 ORDER BY faturamentos.referencia";

    }
}

$Query_titulos = faturamentos::find_by_sql("SELECT SQL_CACHE
                    associados.nm_associado as sacado,
                    faturamentos.tipo_parcela,
                    faturamentos.id,
                    faturamentos.referencia,
                    faturamentos.dt_vencimento,
                    faturamentos.valor
                    FROM
                    faturamentos
                    LEFT JOIN dados_cobranca on dados_cobranca.id = faturamentos.dados_cobranca_id
                    LEFT JOIN planos on planos.id = dados_cobranca.planos_id
                    LEFT JOIN associados on associados.matricula = faturamentos.matricula
                    WHERE {$query}");

$Listitles = new ArrayIterator($Query_titulos);

// vericamos se não está vazio antes de qualquer coisa
if (count($Query_titulos) == 0) {
    echo '<span style="width:80%; display:block; text-align:center; margin: 10px auto;" class=" uk-alert uk-alert-danger">Registro não localizado.</span>';
    exit();
}
?>

<table class="uk-table" style="margin-top: -21px">
    <thead>
    <tr style="line-height:25px;">
        <th class="uk-width uk-text-center" style="width:29px;">#</th>
        <th class="uk-width uk-text-center" style="width:100px; ">Id</th>
        <th class="uk-width uk-text-left" style="width:400px;">Sacado</th>
        <th class="uk-width uk-text-center" style="width:130px;">Referencia</th>
        <th class="uk-width uk-text-center" style="width:130px;">Vencimento</th>
        <th class="uk-width uk-text-center" style="width:130px;">Valor</th>
        <th class="uk-text-center">Juros/Multa</th>
    </tr>
    </thead>
    <tbody>

    <?php

    $linha = 0;
    $total = 0;
    $jurosMulta = 0;
    $totalJurosMulta = 0;

    while ($Listitles->valid()):
        $linha++;
        $dt_v = new ActiveRecord\DateTime($Listitles->current()->dt_vencimento);
        $dt_r = new ActiveRecord\DateTime($Listitles->current()->referencia);

        $FRM_vl = faturamentos::Calcula_Juros($Listitles->current()->valor, $dt_v->format('Y-m-d'), $dados_config->juros, $dados_config->multa);

        if($FRM_vl > $Listitles->current()->valor){
           $jurosMulta =  $FRM_vl - $Listitles->current()->valor;
        }

        ?>
        <tr>
            <td class="uk-text-c" >
                <input type="checkbox" checked="checked" disabled="disabled" class="check_ab" name="check0[]" value="<?php echo $Listitles->current()->id; ?>"/>
            </td>
            <td class="uk-text-center" ><?php echo tool::CompletaZeros("7", $Listitles->current()->id); ?></td>
            <td class="uk-text-left"><?php echo utf8_encode($Listitles->current()->sacado); ?></td>
            <td class="uk-text-center"><?php echo $dt_r->format('d/m/Y'); ?></td>
            <td class="uk-text-center"><?php echo $dt_v->format('d/m/Y'); ?></td>
            <td class="uk-text-center uk-text-success"><?php echo number_format($Listitles->current()->valor, 2, ",", "."); ?></td>
            <td class="uk-text-center uk-text-danger"><?php echo number_format($jurosMulta, 2, ",", "."); ?></td>
        </tr>

        <?php
        $total += $Listitles->current()->valor;
        $totalJurosMulta += $jurosMulta;
        $Listitles->next();
    endwhile;
    ?>
    </tbody>
    <tfoot class="uk-navbar">
    <tr>
        <th class="uk-text-left" colspan="5">
            Total
        </th>
        <td class="uk-text-center"><strong><?php echo number_format($total, 2, ",", "."); ?></strong></td>
        <td class="uk-text-center"><strong><?php echo number_format($totalJurosMulta, 2, ",", "."); ?></strong></td>
    </tr>
    </tfoot>