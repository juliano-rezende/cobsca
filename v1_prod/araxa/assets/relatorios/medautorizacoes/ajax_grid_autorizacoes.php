<link rel="stylesheet" type="text/css"  media="print"  href="framework/uikit-2.24.0/css/uikit.css?<?php echo microtime(); ?>">
<div class="tabs-spacer" style="display:none;">

<?php
require_once("../../../sessao.php");
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');




if(isset($_POST['dtini'])){

$FRM_dt_inicial     = isset( $_POST['dtini'])        ? $_POST['dtini']
                                                     : tool::msg_erros("O Campo dtini é Obrigatorio.");

$FRM_dt_final       = isset( $_POST['dtfim'])        ? $_POST['dtfim']
                                                     : tool::msg_erros("O Campo dtfim é Obrigatorio.");

if($FRM_dt_inicial == "" or $FRM_dt_final == ""){tool::msg_erros("Os campos Data inicial e data final são obrigatorios.");}



}else{

  $FRM_dt_inicial = date("d/m/Y");

  $FRM_dt_final   = date("d/m/Y");
}


$Query_recebimentos=med_autorizacoes::find_by_sql("
 SELECT med_autorizacoes.*, 
CASE WHEN med_autorizacoes.dependente = '1' THEN (SELECT dependentes.nome FROM dependentes WHERE id = med_autorizacoes.dependentes_id) ELSE (SELECT associados.nm_associado FROM associados WHERE matricula = med_autorizacoes.matricula) END AS solicitante 
FROM med_autorizacoes WHERE med_autorizacoes.dt_inclusao BETWEEN '".tool::InvertDateTime(tool::LimpaString($FRM_dt_inicial),0)."' AND '".tool::InvertDateTime(tool::LimpaString($FRM_dt_final),0)."' AND usuarios_id='".$COB_Usuario_Id."'");

$Listitles= new ArrayIterator($Query_recebimentos);


?>
</div>


<table  class="uk-table uk-table-striped uk-table-hover" style="font-size: 10px;" >
  <thead class="uk-hidden">
    <tr style="line-height:25px;">
      <th class="uk-text-center uk-hidden" style=" background-color: #f5f5f5; border: 1px solid #666;" colspan="9">RELATORIO DE AUTORIZAÇÕES</th>
    </tr>
  <tbody>
  <?php
  $linha=0;

  while($Listitles->valid()):

  $linha++;
  $dt_v = new ActiveRecord\DateTime($Listitles->current()->dt_vencimento);
  $dt_i = new ActiveRecord\DateTime($Listitles->current()->dt_inclusao);


  ?>
      <tr style="line-height:22px;" class="<?php echo $class; ?>">
        <th class="uk-width uk-text-center" style="width:20px; overflow: hidden;"><?php echo $linha; ?></th>
        <td class="uk-width uk-text-center" style="width:90px;"><?php echo $Listitles->current()->id; ?> </th>
        <td class="uk-width uk-text-center" style="width:90px;"><?php echo $Listitles->current()->status; ?></td>
        <td class="uk-text-left" style="text-transform: uppercase;"><?php echo utf8_encode($Listitles->current()->solicitante); ?></td>
        <td class="uk-width uk-text-center " style="width:120px;" ><?php echo $dt_v->format('d/m/Y'); ?></td>
        <td class="uk-width uk-text-center" style="width:120px;" ><?php if($Listitles->current()->dt_vencimento == ""){echo "00/00/0000";}else{echo $dt_i->format('d/m/Y');} ?></td>
        <td class="uk-width uk-text-center" style="width:120px;" ><?php echo number_format($Listitles->current()->vlr_total,2,",","."); ?></td>
        <td class="uk-width uk-text-center" style="width:120px;" ></td>
      </tr>
<?php
  $Listitles->next();
  endwhile;
?>
  </tbody>
</table>


