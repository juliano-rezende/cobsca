<?php require_once("../../../sessao.php"); ?>

<div class="tabs-spacer" style="display:none;">
<?php
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');


 $FRM_order_by       = isset( $_GET['order'])        ? $_GET['order']
                                                     : tool::msg_erros("O Campo order é Obrigatorio.");

$FRM_convenio_id    = isset( $_GET['convenio_id'])  ? $_GET['convenio_id']
                                                     : tool::msg_erros("O Campo convenio_id é Obrigatorio.");

$FRM_status         = isset( $_GET['status'])       ? $_GET['status']
                                                     : tool::msg_erros("O Campo status é Obrigatorio.");

$FRM_dt_inicial     = isset( $_GET['dtini'])        ? $_GET['dtini']
                                                     : tool::msg_erros("O Campo dtini é Obrigatorio.");

$FRM_dt_final       = isset( $_GET['dtfim'])        ? $_GET['dtfim']
                                                     : tool::msg_erros("O Campo dtfim é Obrigatorio.");


if($FRM_dt_inicial == "" or $FRM_dt_final == ""){tool::msg_erros("Os campos Data inicial e data final são obrigatorios.");}


/*
DEFINE O STATUS
*/
if($FRM_status == "1"){ /* Ativos */

  $status="associados.status='1'";
  $dt=" AND associados.dt_cadastro <= '".tool::InvertDateTime(tool::LimpaString($FRM_dt_final),0)."'";

}elseif($FRM_status == "2"){ /* CAncelados */

  $status="associados.status='0'";
  $dt= "AND associados.dt_cancelamento BETWEEN '".tool::InvertDateTime(tool::LimpaString($FRM_dt_inicial),0)."' AND '".tool::InvertDateTime(tool::LimpaString($FRM_dt_final),0)."'";

}elseif($FRM_status == "3"){ /* Novos */

  $status="associados.status='1'";
  $dt= "AND associados.dt_cadastro BETWEEN '".tool::InvertDateTime(tool::LimpaString($FRM_dt_inicial),0)."' AND '".tool::InvertDateTime(tool::LimpaString($FRM_dt_final),0)."'";

}else{
  tool::msg_erros("Status incorreto.");
}

/* define o convenio */
if($FRM_convenio_id =="0"){ $convenio_id=""; }else{$convenio_id="AND associados.convenios_id='".$FRM_convenio_id."'";}





$Query_associados=associados::find_by_sql("SELECT
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
                                           WHERE ".$status." ". $dt." ".$convenio_id." ORDER BY associados.".$FRM_order_by."");

$List= new ArrayIterator($Query_associados);

?>
</div>

<link rel="stylesheet" type="text/css"  media="print"  href="http://itumbiara.unifamilia.com.br/unicob/framework/uikit-2.24.0/css/uikit.css?<?php echo microtime(); ?>">
<link rel="stylesheet" type="text/css"  href="http://itumbiara.unifamilia.com.br/unicob/framework/uikit-2.24.0/css/uikit.css?<?php echo microtime(); ?>">

<table  class="uk-table" >
  <thead >
    <tr style="line-height:25px;">
      <th class="uk-width uk-text-center" style="width:20px"></th>
      <th class="uk-width uk-text-center" style="width:90px;" >Matricula</th>
        <th class="uk-text-left" >Nome</th>
        <th class="uk-width uk-text-center" style="width:180px;" >Contatos</th>
        <th class="uk-width uk-text-center" style="width:310px;" >Convênio</th>
        <th class="uk-width uk-text-center" style="width:150px;" >Dt Cad |  Dt Cancel</th>
        <th class="uk-width uk-text-center" style="width:120px;" >
        </th>
    </tr>
    </thead>
  <tbody>
  <?php
  $linha=0;
  while($List->valid()):

  $linha++;

  $dt_cad   = new ActiveRecord\DateTime($List->current()->dt_cadastro);
  $dt_can   = new ActiveRecord\DateTime($List->current()->dt_cancelamento);

/* validação de nono digito*/
if(strlen(tool::LimpaString($List->current()->fone_cel)) == "10"){

$fone_cel= tool::MascaraCampos("??-?????-????",substr(tool::LimpaString($List->current()->fone_cel),0,2)."0".substr(tool::LimpaString($List->current()->fone_cel),2,8));

}else{

$fone_cel= tool::MascaraCampos("??-?????-????",substr(tool::LimpaString($List->current()->fone_cel),0,2)." ".substr(tool::LimpaString($List->current()->fone_cel),2,8));
}

?>
      <tr style="line-height:22px;" >
        <th class="uk-text-center"><?php echo $linha; ?></th>
        <td class="uk-text-center" ><?php echo $List->current()->matricula; ?> </th>
        <td class="uk-text-left" style="text-transform: uppercase;"><?php echo $List->current()->nm_associado; ?></td>
        <td class="uk-text-center"  ><?php echo tool::MascaraCampos("??-????-????",$List->current()->fone_fixo)." | ".
                                                                             $fone_cel;?></td>
        <td class="uk-text-center" style=" text-transform: uppercase;"><?php echo $List->current()->nm_fantasia; ?></td>
        <td class=" uk-text-center">
        <?php echo $dt_cad->format('d/m/Y') ?> | 
        <?php if($List->current()->status == 1){echo "00/00/0000";}else{echo $dt_can->format('d/m/Y');} ?>
        </td>
        <td class="uk-text-center"></td>

      </tr>
  <?php
  $List->next();
  endwhile;
  ?>
  </tbody>
</table>
<script type="text/javascript">
  
  window.print();
</script>