<?php
require_once("../../../sessao.php");
?>
<div class="tabs-spacer" style="display:none;">
  <?php
  require_once("../../../conexao.php");
  $cfg->set_model_directory('../../../models/');
  ?>
</div>
<?php


$FRM_dt_inicial     = isset($_POST['dtini']) ? $_POST['dtini']  : tool::msg_erros("O Campo dtini é Obrigatorio.");
$FRM_dt_final       = isset($_POST['dtfim'])  ? $_POST['dtfim']  : tool::msg_erros("O Campo dtfim é Obrigatorio.");
$FRM_pesquisapor    = isset($_POST['pesquisarpor']) ? $_POST['pesquisarpor']  : tool::msg_erros("O Campo pesquisarpor é Obrigatorio.");

if($FRM_pesquisapor != 0 && $FRM_pesquisapor != 1 && $FRM_pesquisapor != 3){
    $FRM_operador    = $_POST['operador'];
    $FRM_conta       =  $_POST['cdcontabanco'];
}else{
    $FRM_operador    = $COB_Usuario_Id;
    $FRM_conta       =  $_POST['cdcontabanco'];
}

if ($FRM_dt_inicial == "" or $FRM_dt_final == "") {
    tool::msg_erros("Os campos Data inicial e data final são obrigatorios.");
}


$erro = "";



/*
DEFINE  O PESQUISA POR
"0" Data da Vencimento
"1" Data da Pagamento
"2" Data Emissão
*/

$status = "faturamentos.status='1' AND";

if ($FRM_pesquisapor == 0) {

  $where = "" . $status . "  faturamentos.dt_vencimento BETWEEN  '" . tool::InvertDateTime(tool::LimpaString($FRM_dt_inicial), 0) . "' AND '" . tool::InvertDateTime(tool::LimpaString($FRM_dt_final), 0) . "'";
} elseif ($FRM_pesquisapor == 1) {

  $where = "" . $status . " faturamentos.dt_pagamento BETWEEN '" . tool::InvertDateTime(tool::LimpaString($FRM_dt_inicial), 0) . "' AND '" . tool::InvertDateTime(tool::LimpaString($FRM_dt_final), 0) . "' AND faturamentos.status='1'";
} elseif ($FRM_pesquisapor == 2) {

  $where = "" . $status . " faturamentos.usuarios_id='" . $FRM_operador . "' AND faturamentos.dt_pagamento BETWEEN  '" . tool::InvertDateTime(tool::LimpaString($FRM_dt_inicial), 0) . "' AND '" . tool::InvertDateTime(tool::LimpaString($FRM_dt_final), 0) . "' AND faturamentos.status='1'";
} elseif ($FRM_pesquisapor == 3) {
  $where = "" . $status . " faturamentos. contas_bancarias_id='" . $FRM_conta . "' AND faturamentos.dt_pagamento BETWEEN  '" . tool::InvertDateTime(tool::LimpaString($FRM_dt_inicial), 0) . "' AND '" . tool::InvertDateTime(tool::LimpaString($FRM_dt_final), 0) . "'";
} elseif ($FRM_pesquisapor == 4) {

  $where = "" . $status . " faturamentos.usuarios_id > '0'  AND faturamentos.dt_pagamento BETWEEN  '" . tool::InvertDateTime(tool::LimpaString($FRM_dt_inicial), 0) . "' AND '" . tool::InvertDateTime(tool::LimpaString($FRM_dt_final), 0) . "' AND faturamentos.status='1'";
} else {
  $erro = "<div class='uk-notify-message uk-notify-message-danger uk-text-center' style='width:500px; margin:0 auto;'>
                  <i class='uk-icon-warning  uk-text-danger' ></i> Selecione o tipo de pesquisa.</br>
                </div>";
}

if ($erro != "") {
  echo $erro;
  exit();
}

?>
<div class="tabs-spacer" style="display:none;">
  <?php


  $query = "SELECT
          faturamentos.id,
          faturamentos.matricula,
          faturamentos.status,
          faturamentos.titulos_bancarios_id,
          associados.nm_associado,
          faturamentos.dt_vencimento,
          faturamentos.dt_pagamento,
          faturamentos.valor,
          faturamentos.valor_pago,
          faturamentos.tipo_baixa,
          associados.vendedores_id,
          associados.fone_cel,
          associados.fone_fixo,
          vendedores.nm_vendedor,
          usuarios.nm_usuario,
          contas_bancarias.nm_conta
          FROM
          faturamentos
          LEFT JOIN associados ON faturamentos.matricula = associados.matricula
          LEFT JOIN vendedores ON associados.vendedores_id = vendedores.id
          LEFT JOIN usuarios ON faturamentos.usuarios_id = usuarios.id
          LEFT JOIN contas_bancarias ON faturamentos.contas_bancarias_id = contas_bancarias.id
          WHERE
          " . $where . "  ORDER BY faturamentos.matricula ASC";

  $Query_recebimentos = faturamentos::find_by_sql($query);
  $items = count($Query_recebimentos);
  $Listitles = new ArrayIterator($Query_recebimentos);

  $vlr_total = 0;
  ?>
</div>

<table class="uk-table uk-table-striped uk-table-hover" style="font-size: 10px;">
  <thead>
    <tr style="line-height:25px;">
      <th class="uk-width uk-text-center" style="width:20px">Seq</th>
      <th class="uk-width uk-text-center" style="width:90px;">Matricula</th>
      <th class="uk-width uk-text-center" style="width:90px;">Parcela</th>
      <th class="uk-width uk-text-left" style="width:280px; min-width:280px;">Associado</th>
      <th class="uk-width uk-text-center" style="width:120px;">Operador</th>
      <th class="uk-width uk-text-center" style="width:120px;">Dt Vencimento</th>
      <th class="uk-width uk-text-center" style="width:120px;">Dt Pagamento</th>
      <th class="uk-width uk-text-center" style="width:120px;">Valor</th>
      <th class="uk-width uk-text-center" style="width:120px;">Valor pago</th>
      <th class="uk-text-center">Local Pgto</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $linha = 0;
    while ($Listitles->valid()) :

      $linha++;
      $dt_v = new ActiveRecord\DateTime($Listitles->current()->dt_vencimento);
      $dt_p = new ActiveRecord\DateTime($Listitles->current()->dt_pagamento);

      $vlr_total += $Listitles->current()->valor_pago;

      if ($Listitles->current()->status == 0) {
        if (strtotime('' . $dt_v->format('d-m-Y') . '') < strtotime(date('d-m-Y'))) {
          $class = "uk-text-warning uk-text-bold";
        } else {
          $class = "uk-text-bold uk-text-primary";
        }
      } elseif ($Listitles->current()->status == 2) {
        $class = "uk-text-danger uk-text-bold uk-text-line-through";
      } else {
        $class = "uk-text-muted ";
      }
    ?>

      <tr style="line-height:22px;" class="<?php echo $class; ?>">
        <th class="uk-width uk-text-center" style="width:20px; overflow: hidden;"><?php echo $linha; ?></th>
        <td class="uk-width uk-text-center" style="width:70px;"><?php echo $Listitles->current()->matricula; ?></td>
        <td class="uk-width uk-text-center" style="width:80px;"><?php echo $Listitles->current()->id; ?></td>
        <td class="uk-width uk-text-left" style="width:250px; min-width:250px;"><?php echo utf8_encode($Listitles->current()->nm_associado); ?></td>
        <td class="uk-width uk-text-center" style="width:120px;"><?php echo strtoupper(utf8_encode($Listitles->current()->nm_usuario)); ?></td>
        <td class="uk-width uk-text-center" style="width:120px;"><?php echo $dt_v->format('d/m/Y'); ?></td>
        <td class="uk-width uk-text-center" style="width:120px;"><?php if ($Listitles->current()->dt_pagamento == "") {
                                                                    echo "00/00/0000";
                                                                  } else {
                                                                    echo $dt_p->format('d/m/Y');
                                                                  } ?></td>
        <td class="uk-width uk-text-center" style="width:120px;"><?php echo number_format($Listitles->current()->valor, 2, ",", "."); ?></td>
        <td class="uk-width uk-text-center" style="width:120px;"><?php echo number_format($Listitles->current()->valor_pago, 2, ",", "."); ?></td>
        <td class="uk-text-center">
            <?php echo strtoupper(utf8_encode($Listitles->current()->nm_conta)); ?>
        </td>
      </tr>
    <?php
      $Listitles->next();
    endwhile;
    ?>
  </tbody>
  <tfoot>
    <tr style="line-height:30px;">
      <td class="uk-text-center" colspan="10"></td>
    </tr>
    <?php
    if ($FRM_pesquisapor != 3):
      echo '<div class="tabs-spacer" style="display:none;">';
      $Query_contas = contas_bancarias::all();
      echo '</div>';
      $conta = new ArrayIterator($Query_contas);
      while ($conta->valid()) :
        $id = $conta->current()->id;
        $query = faturamentos::find_by_sql("SELECT SUM(faturamentos.valor_pago) as total FROM faturamentos WHERE " . $where . " AND faturamentos.contas_bancarias_id ='" . $id . "'");

        if($conta->current()->tp_conta == 2){
            $info = " - [Cartão Debito/Credito  e Boleto]";
        }else{
            $info="";
        }
    ?>
        <tr style="line-height:22px;" class="<?php echo $class; ?>">
          <td class="uk-text-right" colspan="9"><strong>Conta <?= $conta->current()->nm_conta; ?> <?= $info; ?> </strong></td>
          <td class="uk-width uk-text-right" style="width:120px;">R$ <?php echo number_format($query[0]->total, 2, ',', ' '); ?></td>
        </tr>
    <?php
        $conta->next();
      endwhile;

    endif;
    ?>
    <tr style="line-height:22px;" class="<?php echo $class; ?>">
      <td class="uk-text-right" colspan="9"><strong>Total de recebimentos</strong></td>
      <td class="uk-width uk-text-right" style="width:120px;">R$ <?php echo number_format($vlr_total, 2, ',', ' '); ?></td>
    </tr>
  </tfoot>
</table>
<div style="page-break-after: always;"></div>


<script src="framework/uikit-2.24.0/js/components/pagination.min.js"></script>
<script type="text/javascript">
  jQuery("#print_ok").val("1");

  jQuery('[data-uk-pagination]').on('select.uk.pagination', function(e, pageIndex) {

    // mensagen de carregamento
    jQuery("#msg_loading").html("Pesquisando ");

    //abre a tela de preload
    modal.show();

    jQuery.ajax({
      async: true,
      url: "assets/relatorios/titulos/ajax_grid_titulos.php",
      type: "post",
      data: 'pagina=' + (pageIndex + 1) + '&' + jQuery("#FrmFiltroTitulos").serialize(),
      success: function(resultado) {
        //abre a tela de preload
        jQuery("#Grid_titulos").html(resultado);
        //abre a tela de preload
        modal.hide();
      },
      error: function() {
        UIkit.modal.alert("Erro ao enviar dados! Erro 404"); /*erro de caminho invalido do arquivo*/
        modal.hide();
      }

    });
  });
</script>