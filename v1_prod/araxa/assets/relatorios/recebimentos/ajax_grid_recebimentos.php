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

$FRM_dt_inicial     = isset( $_POST['dtini'])        ? $_POST['dtini']
                                                     : tool::msg_erros("O Campo dtini é Obrigatorio.");

$FRM_dt_final       = isset( $_POST['dtfim'])        ? $_POST['dtfim']
                                                     : tool::msg_erros("O Campo dtfim é Obrigatorio.");
$FRM_pesquisapor    = isset( $_POST['pesquisarpor']);

$erro="";




$status="faturamentos.status='1' AND";



/*
DEFINE OS DADOS DA PAGINAÇÃO
*/
if(isset($_POST['pagina'])){

  $pagina = $_POST['pagina'];

}else{

  $pagina = 1;

}

$limite =15; // Define o limite de registros a serem exibidos com o valor cinco

$inicio = ceil(($pagina * $limite) - $limite);


if($FRM_dt_inicial == "" or $FRM_dt_final == ""){tool::msg_erros("Os campos Data inicial e data final são obrigatorios.");}


/*
DEFINE  O PESQUISA POR
"0" Data da Vencimento
"1" Data da Pagamento
"2" Data Emissão
*/


if($FRM_pesquisapor == 0){

  $where ="".$status ."  faturamentos.dt_vencimento BETWEEN  '".tool::InvertDateTime(tool::LimpaString($FRM_dt_inicial),0)."' AND '".tool::InvertDateTime(tool::LimpaString($FRM_dt_final),0)."'";

}elseif($FRM_pesquisapor == 1){

  $where ="".$status." faturamentos.dt_pagamento BETWEEN '".tool::InvertDateTime(tool::LimpaString($FRM_dt_inicial),0)."' AND '".tool::InvertDateTime(tool::LimpaString($FRM_dt_final),0)."'";

}else{
    $erro="<div class='uk-notify-message uk-notify-message-danger uk-text-center' style='width:500px; margin:0 auto;'>
                  <i class='uk-icon-warning  uk-text-danger' ></i> Selecione o tipo de pesquisa.</br>
                </div>";
}



if($erro!= ""){echo $erro; exit();}

$arrayTipo= array("B","M");


foreach($arrayTipo as $tipo) {

?>
<div class="tabs-spacer" style="display:none;">
<?php


$query="SELECT
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
          vendedores.nm_vendedor
          FROM
          faturamentos
          LEFT JOIN associados ON faturamentos.matricula = associados.matricula
          LEFT JOIN vendedores ON associados.vendedores_id = vendedores.id
          WHERE
          ".$where." AND faturamentos.tipo_baixa='".$tipo."' ORDER BY faturamentos.matricula ASC";


// retorna todo os registros para montar a paginação
$Query_all=faturamentos::find_by_sql($query);
$items=count($Query_all);

// monta a query especifica
//$query_p=$query." LIMIT ".$inicio.",".$limite."";
$query_p=$query;

$Query_recebimentos=faturamentos::find_by_sql($query_p);
$Listitles= new ArrayIterator($Query_recebimentos);

$vlr_total="";

?>
</div>

<table  class="uk-table uk-table-striped uk-table-hover" style="font-size: 10px;" >
  <thead class="uk-hidden">
    <tr style="line-height:25px;">
      <th class="uk-text-center uk-hidden" style=" background-color: #f5f5f5; border: 1px solid #666;" colspan="9">RELATORIO DE RECEBIMENTOS</th>
    </tr>
    <tr style="line-height:25px;">
      <th class="uk-width uk-text-center" style="width:20px">Seq</th>
      <th class="uk-width uk-text-center" style="width:90px; text-align: left;" >Matricula</th>
      <th class="uk-width uk-text-center" style="width:90px;text-align: left;" >Parcela</th>
      <th class="uuk-text-left" style="text-align: left;">Associado</th>
      <th class="uk-width uk-text-center" style="width:120px;text-align: left;" >Dt Vencimento</th>
      <th class="uk-width uk-text-center" style="width:120px;text-align: left;" >Dt Pagamento</th>
      <th class="uk-width uk-text-center" style="width:120px;text-align: left;" >Valor</th>
      <th class="uk-width uk-text-center" style="width:120px;text-align: left;" >Valor pago</th>
      <th class="uk-width uk-text-center" style="width:120px;text-align: left;"> Tipo Receb</th>
    </tr>
  </thead>
  <tbody>
  <?php
  $linha=0;
  while($Listitles->valid()):

  $linha++;
  $dt_v = new ActiveRecord\DateTime($Listitles->current()->dt_vencimento);
  $dt_p = new ActiveRecord\DateTime($Listitles->current()->dt_pagamento);

  $vlr_total += $Listitles->current()->valor;


  if($Listitles->current()->status == 0){

    if(strtotime(''.$dt_v->format('d-m-Y').'') < strtotime( date( 'd-m-Y' ) ) ){

      $class="uk-text-warning uk-text-bold";

    }else{

      $class="uk-text-bold uk-text-primary";

    }

  }elseif($Listitles->current()->status == 2){

  $class="uk-text-danger uk-text-bold uk-text-line-through";

  }else{

  $class="uk-text-muted ";

  }
  ?>
      <tr style="line-height:22px;" class="<?php echo $class; ?>">
        <th class="uk-width uk-text-center" style="width:20px; overflow: hidden;"><?php echo $linha; ?></th>
        <td class="uk-width uk-text-center" style="width:90px;"><?php echo $Listitles->current()->matricula; ?> </th>
        <td class="uk-width uk-text-center" style="width:90px;"><?php echo $Listitles->current()->id; ?></td>
        <td class="uk-text-left" style="text-transform: uppercase;"><?php echo utf8_encode($Listitles->current()->nm_associado); ?></td>
        <td class="uk-width uk-text-center " style="width:120px;" ><?php echo $dt_v->format('d/m/Y'); ?></td>
        <td class="uk-width uk-text-center" style="width:120px;" ><?php if($Listitles->current()->dt_pagamento == ""){echo "00/00/0000";}else{echo $dt_p->format('d/m/Y');} ?></td>
        <td class="uk-width uk-text-center" style="width:120px;" ><?php echo number_format($Listitles->current()->valor,2,",","."); ?></td>
        <td class="uk-width uk-text-center" style="width:120px;" ><?php echo number_format($Listitles->current()->valor_pago,2,",","."); ?></td>
        <td class="uk-width uk-text-center" style="width:120px;" ><?php if($Listitles->current()->tipo_baixa =="M"){echo "Receb Local";}else{echo "Receb Bancario";} ?></td>
      </tr>
<?php
  $Listitles->next();
  endwhile;
?>
  </tbody>
</table>
<table  class="uk-table uk-table-striped uk-table-hover" style="font-size: 10px;" >
  <tfoot class="uk-hidden">
    <tr style="line-height:25px;">
      <th class="uk-text-left " colspan="7" style="text-align: left;">Total de recebimentos</th>
      <th class="uk-width uk-text-center" style="width:120px;text-align: left;"><?php echo number_format($vlr_total, 2, ',', ' ');?></th>
    </tr>
  </tfoot>
</table>
<div style="page-break-after: always;"></div>

<?php
}
?>

<script src="framework/uikit-2.24.0/js/components/pagination.min.js"></script>
<script type="text/javascript">

jQuery("#print_ok").val("1");

jQuery('[data-uk-pagination]').on('select.uk.pagination', function(e, pageIndex){

    // mensagen de carregamento
 jQuery("#msg_loading").html("Pesquisando ");

 //abre a tela de preload
 modal.show();

   jQuery.ajax({
        async: true,
        url: "assets/relatorios/titulos/ajax_grid_titulos.php",
        type: "post",
        data:'pagina='+(pageIndex+1)+'&'+jQuery("#FrmFiltroTitulos").serialize(),
        success: function(resultado) {
                            //abre a tela de preload
                           jQuery("#Grid_titulos").html(resultado);
                            //abre a tela de preload
              modal.hide();
        },
        error:function (){
          UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
          modal.hide();
          }

      });
});
</script>