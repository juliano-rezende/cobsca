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



$FRM_convenio_id    = isset( $_POST['conv_id'])  ? $_POST['conv_id']
                                                     : tool::msg_erros("O Campo convenio_id é Obrigatorio.");
$FRM_ref            = isset( $_POST['ref'])  ? $_POST['ref']
                                                     : tool::msg_erros("O Campo refer é Obrigatorio.");                                                     

$FRM_ref = "01".tool::LimpaString($FRM_ref);
$FRM_ref = tool::InvertDateTime($FRM_ref,0);

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




/* define o convenio */
if($FRM_convenio_id =="" or $FRM_convenio_id =="0"){ tool::msg_erros("Convênio invalido.");}


// retorna todo os registros para montar a paginação
$Query_all=associados::find_by_sql("SELECT * FROM associados WHERE status='1' AND  convenios_id='".$FRM_convenio_id."'");

$items=count($Query_all);

$Query_associados=associados::find_by_sql("SELECT
                                           associados.matricula,
                                           associados.nm_associado,
                                           associados.cpf,
                                           associados.rg,
                                           associados.dt_cadastro,
                                           (SELECT valor FROM faturamentos WHERE matricula =  associados.matricula AND faturamentos.referencia='".$FRM_ref."' AND faturamentos.status='0' GROUP BY faturamentos.referencia) AS valor,
                                           convenios.cnpj,
                                           convenios.razao_social,
                                           empresas.logomarca
                                           FROM
                                           associados
                                           LEFT JOIN faturamentos ON associados.matricula = faturamentos.matricula
                                           LEFT JOIN convenios ON associados.convenios_id = convenios.id
                                           LEFT JOIN empresas ON associados.empresas_id = empresas.id
                                           WHERE associados.status='1' AND  associados.convenios_id='".$FRM_convenio_id."' GROUP BY associados.matricula");

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

?>
      <tr style="line-height:22px;" >
        <th class="uk-width uk-text-center" style="width:20px;"><?php echo $linha; ?></th>
        <td class="uk-width uk-text-center" style="width:90px;"><?php echo $List->current()->matricula; ?> </th>
        <td class="uk-text-left" style="text-transform: uppercase;"><?php echo $List->current()->nm_associado; ?></td>
        <td class="uk-width uk-text-center" style="width:120px;"><?php echo tool::MascaraCampos("???.???.???-??",$List->current()->cpf); ?></td>
        <td class="uk-width uk-text-center" style="width:120px;"><?php echo $List->current()->rg; ?></td>
        <td class="uk-width uk-text-center" style="width:120px;"><?php echo $dt_cad->format('d/m/Y'); ?> </td>
        <td class="uk-width uk-text-center" style="width:120px;"><?php echo number_format($List->current()->valor,2,",","."); ?></td>
        <td class="uk-width uk-text-center" style="width:120px;"></td>
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
        url: "assets/relatorios/fatconvenios/ajax_grid_convenios.php",
        type: "post",
        data:'pagina='+(pageIndex+1)+'&'+jQuery("#FrmFiltroConvenios").serialize(),
        success: function(resultado) {
                            //abre a tela de preload
                           jQuery("#Grid_convenios").html(resultado);
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