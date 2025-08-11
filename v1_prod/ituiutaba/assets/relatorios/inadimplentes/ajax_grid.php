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

$limite =15; // Define o limite de registros a serem exibidos com o valor cinco

$inicio = ceil(($pagina * $limite) - $limite);


if($FRM_dt_inicial == "" or $FRM_dt_final == ""){tool::msg_erros("Os campos Data inicial e data final são obrigatorios.");}

/*
DEFINE O convenios
*/
if($FRM_convenio != ""){$convenio=" AND faturamentos.convenios_id='".$FRM_convenio."'";}else{$convenio="";}


$where =" faturamentos.status='0' AND faturamentos.dt_vencimento BETWEEN  '".tool::InvertDateTime(tool::LimpaString($FRM_dt_inicial),0)."' AND '".tool::InvertDateTime(tool::LimpaString($FRM_dt_final),0)."' ".$convenio."";


?>
<div class="tabs-spacer" style="display:none;">

  <?php


  $query="SELECT
  faturamentos.*,associados.nm_associado,associados.convenios_id AS cdconv,
  (SELECT count(id) FROM faturamentos  WHERE faturamentos.matricula = associados.matricula) as total_fat,
  (SELECT count(id) FROM faturamentos  WHERE faturamentos.status = '0' and faturamentos.matricula = associados.matricula) as total_fat_ab,
  (SELECT count(id) FROM faturamentos  WHERE faturamentos.status = '1' and faturamentos.matricula = associados.matricula) as total_fat_pgto,
  (SELECT count(id) FROM faturamentos  WHERE faturamentos.status = '2' and faturamentos.matricula = associados.matricula) as total_fat_cancel
  FROM faturamentos LEFT JOIN associados ON faturamentos.matricula= associados.matricula   WHERE ".$where."";


// retorna todo os registros para montar a paginação
  $Query_all=titulos::find_by_sql($query);
  $items=count($Query_all);

// monta a query especificaD
  $query_p=$query." ORDER BY matricula ASC LIMIT ".$inicio.",".$limite."";

  $Query_titulos=titulos::find_by_sql($query_p);

  $Listitles= new ArrayIterator($Query_titulos);

  ?>
</div>
<div style="height:<?php echo tool::HeightContent($COB_Heigth,$COB_Browser)-75;?>px; overflow:hidden; padding-bottom: 55px; ">

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
          <th class="uk-width uk-text-center" style="width:20px; vertical-align: middle;"><?php echo $linha; ?></th>
          <td class="uk-width uk-text-center" style="width:90px;"><?php echo $Listitles->current()->matricula; ?> </th>
            <td class="uk-width uk-text-center" style="width:90px;"><?php echo $Listitles->current()->titulos_bancarios_id; ?></td>
            <td class="uk-text-left" style="text-transform: uppercase;"><?php echo utf8_encode($Listitles->current()->nm_associado); ?></td>
            <td class="uk-width uk-text-center" style="width:100px;" ><?php echo $refe->format('d/m/Y'); ?></td>
            <td class="uk-width uk-text-center " style="width:100px;" ><?php echo $dt_v->format('d/m/Y'); ?></td>
            <td class="uk-width uk-text-center" style="width:100px;" ><?php echo number_format($Listitles->current()->valor,2,",","."); ?></td>
            <td class="uk-width uk-text-center" style="width:150px;" >
             <div class="uk-badge uk-badge-success" style="width:100px;">Pagas <?php echo $Listitles->current()->total_fat_pgto; ?></div>
             <div class="uk-badge uk-badge-warning" style="width:100px;">Em aberto <?php echo $Listitles->current()->total_fat_ab; ?></div>
             <div class="uk-badge uk-badge-danger" style="width:100px;">Canceladas <?php echo $Listitles->current()->total_fat_cancel; ?></div>
             <div class="uk-badge uk-badge-primary" style="width:100px;">Total <?php echo $Listitles->current()->total_fat; ?></div>
           </td>
           <td class="uk-width uk-text-center" style="width:120px; vertical-align: middle;" >
             <div class="uk-coment-action" style="font-weight: normal; text-align: left; z-index: 10000; position: absolute; margin: -20px 25px;">
              <div class="uk-button-group">
                <div data-uk-dropdown="{pos:'left-center',mode:'click'}">
                  <button class=" uk-button uk-button uk-button-large uk-icon-ellipsis-v " style="margin:5px 0; border:0; background:none;"></button>
                  <div class="uk-dropdown uk-dropdown-small">
                    <ul class="uk-nav uk-nav-dropdown">
                      <li><a href="javascript:void(0);" class="BtnHist" data-mat="<?php echo $Listitles->current()->matricula; ?>"><i class="uk-icon-edit"></i> Adcionar Histórico</a></li>
                      <li><a href="JavaScript:void(0);" onclick="D_Actions_Assoc('fat','<?php echo $Listitles->current()->matricula;?>','<?php echo $Listitles->current()->cdconv;?>');"><i class="uk-icon-list"></i> Faturamento</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>

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


  jQuery(".BtnHist").click(function(){

    var matricula= jQuery(this).attr("data-mat");
    New_window('file-text-o','950','550','Histórico do Contrato','assets/associado/Historico_contrato.php?matricula='+matricula+'',true,false,'Carregando...');

  });

  function D_Actions_Assoc(action,val,conv){

    /* abre o dependente em modo de edição */
    if(action=='edit'){

      LoadContent('assets/associado/Frm_associado.php?matricula='+val+'','content');
      jQuery(".uk-dropdown").hide();
    }

    /* abre o dependente em modo de visão */
    if(action=='fat'){

      jQuery("#msg_loading").html(" Aguarde ");
      modal.show();jQuery(".uk-dropdown").hide();
      New_window('list','950','500','Faturamento','assets/faturamento/Frm_faturamento.php?matricula='+val+'&convenio_id='+conv+'',true,false,'Carregando...');

    }

    /* window  dependente*/
    if(action=='dep'){

      jQuery("#msg_loading").html(" Aguarde ");
      modal.show();jQuery(".uk-dropdown").hide();
      New_window('users','780','500','Dependentes','assets/dependente/Veiw_dependente.php?matricula='+val+'',true,false,'Carregando...');
    }
  }

</script>