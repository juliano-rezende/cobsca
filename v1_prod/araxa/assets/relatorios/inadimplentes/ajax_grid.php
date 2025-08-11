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
  ?>
</div>
<?php

$FRM_convenio       = isset( $_POST['convenio_id'])  ? intval($_POST['convenio_id']): tool::msg_erros("O Campo convenio_id é Obrigatorio.");

$FRM_dt_inicial     = isset( $_POST['dtini'])        ? $_POST['dtini']: tool::msg_erros("O Campo dtini é Obrigatorio.");

$FRM_dt_final       = isset( $_POST['dtfim'])        ? $_POST['dtfim']: tool::msg_erros("O Campo dtfim é Obrigatorio.");

$erro="";
/*
DEFINE OS DADOS DA PAGINAÇÃO
*/
if(isset($_POST['pagina'])){

  $pagina = $_POST['pagina'];

}else{

  $pagina = 1;

}

$limite =16; // Define o limite de registros a serem exibidos com o valor cinco

$inicio = ceil(($pagina * $limite) - $limite);


if($FRM_dt_inicial == "" or $FRM_dt_final == ""){tool::msg_erros("Os campos Data inicial e data final são obrigatorios.");}

/*
DEFINE O convenios
*/
if($FRM_convenio != ""){$convenio=" AND faturamentos.convenios_id='".$FRM_convenio."'";}else{$convenio="";}


$where =" faturamentos.status='0' AND faturamentos.titulos_bancarios_id > '0' AND faturamentos.dt_vencimento BETWEEN  '".tool::InvertDateTime(tool::LimpaString($FRM_dt_inicial),0)."' AND '".tool::InvertDateTime(tool::LimpaString($FRM_dt_final),0)."' ".$convenio."";


?>
<div class="tabs-spacer" style="display:none;">

  <?php


  $query="SELECT faturamentos.id,faturamentos.matricula,faturamentos.referencia,faturamentos.dt_vencimento,faturamentos.titulos_bancarios_id,faturamentos.valor, associados.nm_associado FROM faturamentos LEFT JOIN associados ON faturamentos.matricula= associados.matricula  WHERE ".$where."";


// retorna todo os registros para montar a paginação
  $Query_all=titulos::find_by_sql($query);
  $items=count($Query_all);

// monta a query especificaD
  $query_p=$query." ORDER BY matricula ASC LIMIT ".$inicio.",".$limite."";

  $Query_titulos=titulos::find_by_sql($query_p);

  $Listitles= new ArrayIterator($Query_titulos);

  ?>
</div>
<div style="height:<?php echo tool::HeightContent($COB_Heigth,$COB_Browser)-75;?>px; overflow:hidden; ">

  <table  class="uk-table uk-table-striped uk-table-hover" >
    <tbody>
      <?php
      $linha=0;
      while($Listitles->valid()):

        $linha++;

        $dt_v   = new ActiveRecord\DateTime($Listitles->current()->dt_vencimento);
        $refe   = new ActiveRecord\DateTime($Listitles->current()->referencia);

        $class="uk-text-warning uk-text-bold";

        ?>

        <tr style="line-height:22px;" class="<?php echo $class; ?>">
          <th class="uk-width uk-text-center" style="width:20px;"><?php echo $linha; ?></th>
          <td class="uk-width uk-text-center" style="width:90px;"><?php echo $Listitles->current()->matricula; ?> </th>
            <td class="uk-width uk-text-center" style="width:90px;"><?php echo $Listitles->current()->id; ?></td>
            <td class="uk-width uk-text-center" style="width:90px;"><?php echo $Listitles->current()->titulos_bancarios_id; ?></td>
            <td class="uk-text-left" style="text-transform: uppercase;"><?php echo utf8_encode($Listitles->current()->nm_associado); ?></td>
            <td class="uk-width uk-text-center" style="width:120px;" ><?php echo $refe->format('m/Y'); ?></td>
            <td class="uk-width uk-text-center " style="width:120px;" ><?php echo $dt_v->format('d/m/Y'); ?></td>
            <td class="uk-width uk-text-center" style="width:120px;" ><?php echo number_format($Listitles->current()->valor,2,",","."); ?></td>
            <td class="uk-width uk-text-center" style="width:120px; vertical-align: middle;" >

            </td>
          </tr>
          <?php
          $Listitles->next();
        endwhile;
        ?>
      </tbody>
    </table>
  </div>

  <nav class="uk-navbar " style="bottom: 0; position: absolute; width: 100%">
    <span style="float: left; margin: 7px;" class="uk-text-small"><?php echo "Total de Registros ".$items.""; ?></span>
    <ul class="uk-pagination" style="position:relative; margin:3px 0;" data-uk-pagination="{edges:4,items:<?php echo $items; ?>, itemsOnPage:16, currentPage:<?php echo $pagina;?>}"></ul>

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
  url: "assets/relatorios/inadimplentes/ajax_grid.php",
  type: "post",
  data:'pagina='+(pageIndex+1)+'&'+jQuery("#FrmFiltroInad").serialize(),
  success: function(resultado) {
                            //abre a tela de preload
                            jQuery("#GridVeiw").html(resultado);
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