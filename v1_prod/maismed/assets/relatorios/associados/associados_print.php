<link href="../../../css/doc.uikit.css?<?php echo microtime(); ?>" rel="stylesheet" type="text/css" />
<?php

require_once("../../../sessao.php");

require_once("../../../conexao.php");
require_once("../../../config_ini.php");

$cfg->set_model_directory('../../../models/');



$FRM_order_by = isset($_GET['order']) ? $_GET['order']
    : tool::msg_erros("O Campo order é Obrigatorio.");


$FRM_convenio_id = isset($_GET['convenio_id']) ? $_GET['convenio_id']
    : tool::msg_erros("O Campo convenio_id é Obrigatorio.");

$FRM_status = isset($_GET['status']) ? $_GET['status']
    : tool::msg_erros("O Campo status é Obrigatorio.");

$FRM_dt_inicial = isset($_GET['dtini']) ? $_GET['dtini']
    : tool::msg_erros("O Campo dtini é Obrigatorio.");

$FRM_dt_final = isset($_GET['dtfim']) ? $_GET['dtfim']
    : tool::msg_erros("O Campo dtfim é Obrigatorio.");

$erro = "";

if ($FRM_dt_inicial == "" or $FRM_dt_final == "") {
    tool::msg_erros("Os campos Data inicial e data final são obrigatorios.");
}

/*
DEFINE O STATUS
*/
if ($FRM_status == "1") { /* Ativos */

    $status = "associados.status='1'";
    $dt = " AND associados.dt_cadastro <= '" . tool::InvertDateTime(tool::LimpaString($FRM_dt_final), 0) . "'";

} elseif ($FRM_status == "2") { /* CAncelados */

    $status = "associados.status='0'";
    $dt = "AND associados.dt_cancelamento BETWEEN '" . tool::InvertDateTime(tool::LimpaString($FRM_dt_inicial), 0) . "' AND '" . tool::InvertDateTime(tool::LimpaString($FRM_dt_final), 0) . "'";

} elseif ($FRM_status == "3") { /* Novos */

    $status = "associados.status='1'";
    $dt = "AND associados.dt_cadastro BETWEEN '" . tool::InvertDateTime(tool::LimpaString($FRM_dt_inicial), 0) . "' AND '" . tool::InvertDateTime(tool::LimpaString($FRM_dt_final), 0) . "'";

} elseif ($FRM_status == "4") { /* Spc */

    $status = "associados.status!='0' AND associados.spc='1'";
    $dt = "";

} else {
    tool::msg_erros("Status incorreto.");
}

/* define o convenio */
if ($FRM_convenio_id == "0") {
    $convenio_id = "";
} else {
    $convenio_id = "AND associados.convenios_id='" . $FRM_convenio_id . "'";
}

$Query_all = associados::find_by_sql("SELECT * FROM associados  WHERE " . $status . " " . $dt . " " . $convenio_id);

$items = count($Query_all);


$Query_associados = associados::find_by_sql("SELECT
                                           associados.nm_associado,
                                           associados.status,
                                           associados.dt_cadastro,
                                           associados.dt_cancelamento,
                                           associados.matricula,
                                           associados.fone_fixo,
                                           associados.fone_cel,
                                           convenios.nm_fantasia
                                           FROM
                                           associados
                                           LEFT JOIN convenios ON convenios.id = associados.convenios_id
                                          WHERE ".$status." ". $dt." ".$convenio_id." ORDER BY associados.".$FRM_order_by." ");

$List = new ArrayIterator($Query_associados);

?>
</div>

<div id="print_rel_associados" style="overflow:auto; width: 980px;max-width: 980px; margin: 0 auto">

    <link rel="stylesheet" href="../../../css/print.rel.css">

    <table class="uk-table-rel " style="font-weight:normal; border-bottom:2px double #ccc;">
        <tr>
            <th colspan="6" align="center" valign="middle" style="border:0;font-size:15px; text-align:center; background-color:transparent;">EXTRATO DE NOVOS CADASTROS</th>
        </tr>
    </table>

    <table class="uk-table-rel " style="border:0px solid #ccc; margin-top:3px;">

        <table class="uk-table-rel">
            <thead>
            <tr>
                <th class="uk-width uk-text-center"></th>
                <th class="uk-width uk-text-center">Matricula</th>
                <th class="uk-text-left">Nome</th>
                <th class="uk-width uk-text-center">Contatos</th>
                <th class="uk-width uk-text-center">Convênio</th>
                <th class="uk-width uk-text-center">Dt Cad | Dt Cancel</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $linha = 0;
            while ($List->valid()):

                $linha++;

                $dt_cad = new ActiveRecord\DateTime($List->current()->dt_cadastro);
                $dt_can = new ActiveRecord\DateTime($List->current()->dt_cancelamento);

                /* validação de nono digito*/
                if (strlen(tool::LimpaString($List->current()->fone_cel)) == "10") {
                    $fone_cel = tool::MascaraCampos("?? ?????-????", substr(tool::LimpaString($List->current()->fone_cel), 0, 2) . "0" . substr(tool::LimpaString($List->current()->fone_cel), 2, 8));
                } else {
                    $fone_cel = tool::MascaraCampos("?? ? ????-????", substr(tool::LimpaString($List->current()->fone_cel), 0, 2) . " " . substr(tool::LimpaString($List->current()->fone_cel), 3, 1) . " " . substr(tool::LimpaString($List->current()->fone_cel), 3, 8));
                }

                ?>
                <tr style="line-height:22px;">
                    <th class="uk-width uk-text-center"><?php echo $linha; ?></th>
                    <td class="uk-width uk-text-center"><?php echo $List->current()->matricula; ?></td>
                    <td class="uk-text-left" style="text-transform: uppercase;"><?php echo $List->current()->nm_associado; ?></td>
                    <td class="uk-width uk-text-center" style="width:180px;"><?php echo tool::MascaraCampos("??-????-????", $List->current()->fone_fixo) . " | " .
                            $fone_cel; ?></td>
                    <td class="uk-width uk-text-center" style="width:300px; text-transform: uppercase;"><?php echo $List->current()->nm_fantasia; ?></td>
                    <td class="uk-width uk-text-center" style="width:140px;">
                        <?php echo $dt_cad->format('d/m/Y') ?> |
                        <?php if ($List->current()->status == 1) {
                            echo "00/00/0000";
                        } else {
                            echo $dt_can->format('d/m/Y');
                        } ?>
                    </td>
                </tr>
                <?php
                $List->next();
            endwhile;
            ?>
            </tbody>
        </table>
</div>


<script language="JavaScript">

    window.print();
</script>