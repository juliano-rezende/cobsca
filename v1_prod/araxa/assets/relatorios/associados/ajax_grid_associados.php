<?php
require_once("../../../sessao.php");

$proto = strtolower(preg_replace('/[^a-zA-Z]/','',$_SERVER['SERVER_PROTOCOL'])); //pegando só o que for letra
$location = $proto.'://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];

$caminho=explode("assets",$location);
?>
<div class="tabs-spacer" style="display:none;">
<?php
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');


$FRM_order_by       = isset( $_POST['order'])        ? $_POST['order']
                                                     : tool::msg_erros("O Campo order é Obrigatorio.");

$FRM_convenio_id    = isset( $_POST['convenios_id'])  ? $_POST['convenios_id']
                                                     : tool::msg_erros("O Campo convenio_id é Obrigatorio.");

$FRM_status         = isset( $_POST['status'])       ? $_POST['status']
                                                     : tool::msg_erros("O Campo status é Obrigatorio.");

$FRM_dt_inicial     = isset( $_POST['dtini'])        ? $_POST['dtini']
                                                     : tool::msg_erros("O Campo dtini é Obrigatorio.");

$FRM_dt_final       = isset( $_POST['dtfim'])        ? $_POST['dtfim']
                                                     : tool::msg_erros("O Campo dtfim é Obrigatorio.");

$FRM_subconvenio    = isset( $_POST['sub_conv'])   ? $_POST['sub_conv']            
                                                      : tool::msg_erros("O Campo subconvenio é Obrigatorio linha 30.");


if($FRM_subconvenio > 0){$subconvenio="AND associados.sub_convenios_id='".$FRM_subconvenio."'";}else{$subconvenio="";}


$erro="";
/*
DEFINE OS DADOS DA PAGINAÇÃO
*/
if(isset($_POST['pagina'])){

  $pagina = $_POST['pagina'];

}else{

  $pagina = 1;

}

$limite =17; // Define o limite de registros a serem exibidos com o valor cinco

$inicio = ceil(($pagina * $limite) - $limite);


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


// retorna todo os registros para montar a paginação
$Query_all=associados::find_by_sql("SELECT * FROM associados  WHERE ".$status." ". $dt." ".$convenio_id." ".$subconvenio );

$items=count($Query_all);


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
                                           WHERE ".$status." ". $dt." ".$convenio_id." ".$subconvenio." ORDER BY associados.".$FRM_order_by." LIMIT ".$inicio.",".$limite."");

$List= new ArrayIterator($Query_associados);

?>
</div>
<div style="height:<?php echo tool::HeightContent($COB_Heigth)-75;?>px;">

<table  class="uk-table uk-table-striped uk-table-hover" >
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
        <th class="uk-width uk-text-center" style="width:20px;"><?php echo $linha; ?></th>
        <td class="uk-width uk-text-center" style="width:90px;"><?php echo $List->current()->matricula; ?> </th>
        <td class="uk-text-left" style="text-transform: uppercase;"><?php echo $List->current()->nm_associado; ?></td>
        <td class="uk-width uk-text-center" style="width:180px;" ><?php echo tool::MascaraCampos("??-????-????",$List->current()->fone_fixo)." | ".
                                                                             $fone_cel;?></td>
        <td class="uk-width uk-text-center" style="width:300px; text-transform: uppercase;"><?php echo $List->current()->nm_fantasia; ?></td>
        <td class="uk-width uk-text-center" style="width:140px;" >
        <?php echo $dt_cad->format('d/m/Y') ?> | 
        <?php if($List->current()->status == 1){echo "00/00/0000";}else{echo $dt_can->format('d/m/Y');} ?>
        </td>
        <td class="uk-width uk-text-center" style="width:130px;" ></td>

      </tr>
  <?php
  $List->next();
  endwhile;
  ?>
  </tbody>
</table>
</div>

<nav class="uk-navbar " style="bottom: 0; position: absolute; width: 100%">
<span style="float: left; margin: 7px;" class="uk-text-small"><?php echo "Total de Registros ".$items.""; ?></span>
 <ul class="uk-pagination" style="position:relative; margin:3px 0;" data-uk-pagination="{edges:4,items:<?php echo $items; ?>, itemsOnPage:17, currentPage:<?php echo $pagina;?>}"></ul>

</nav>

<script src="framework/uikit-2.24.0/js/components/pagination.min.js"></script>
<script type="text/javascript">


jQuery('[data-uk-pagination]').on('select.uk.pagination', function(e, pageIndex){


    // mensagen de carregamento
 jQuery("#msg_loading").html("Pesquisando ");

 //abre a tela de preload
 modal.show();

   jQuery.ajax({
        async: true,
        url: "assets/relatorios/associados/ajax_grid_associados.php",
        type: "post",
        data:'pagina='+(pageIndex+1)+'&'+jQuery("#FrmFiltroAssociados").serialize(),
        success: function(resultado) {
                            //abre a tela de preload
                           jQuery("#Grid_associados").html(resultado);
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