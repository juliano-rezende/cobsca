<div class="tabs-spacer" style="display:none;">
<?php
require_once '../../../sessao.php';

$proto = strtolower(preg_replace('/[^a-zA-Z]/', '', $_SERVER['SERVER_PROTOCOL'])); //pegando só o que for letra
$location = $proto.'://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];

$caminho = explode('assets', $location);
require_once '../../../conexao.php';
$cfg->set_model_directory('../../../models/');
?>

<?php

$FRM_convenio = isset($_POST['convenio_id']) ? intval($_POST['convenio_id']) : tool::msg_erros('O Campo convenio_id é Obrigatorio.');
$erro = '';
/*
DEFINE OS DADOS DA PAGINAÇÃO
*/
if (isset($_POST['pagina'])) {
    $pagina = $_POST['pagina'];
} else {
    $pagina = 1;
}

$limite = 15; // Define o limite de registros a serem exibidos com o valor cinco

$inicio = ceil(($pagina * $limite) - $limite);

/*
DEFINE O convenios
*/
if ($FRM_convenio != '') {
    $convenio = "a.empresas_id='{$COB_Empresa_Id}' AND a.convenios_id='{$FRM_convenio}'";
} else {
    $convenio = "a.empresas_id='{$COB_Empresa_Id}'";
}

$query = "SELECT a.matricula , a.nm_associado,a.convenios_id AS cdconv,
(SELECT COALESCE(SUM(valor), 0) FROM faturamentos AS f WHERE f.matricula = a.matricula AND f.referencia = DATE_FORMAT( ADDDATE( NOW(), INTERVAL -1 MONTH ) , '%Y-%m-01' )) AS primeiro,
(SELECT COALESCE(SUM(valor), 0) FROM faturamentos AS f WHERE f.matricula = a.matricula AND f.referencia = DATE_FORMAT( ADDDATE( NOW(), INTERVAL -2 MONTH ) , '%Y-%m-01' )) AS segundo,
(SELECT COALESCE(SUM(valor), 0) FROM faturamentos AS f WHERE f.matricula = a.matricula AND f.referencia = DATE_FORMAT( ADDDATE( NOW(), INTERVAL -3 MONTH ) , '%Y-%m-01' )) AS terceiro,
(SELECT COALESCE(SUM(valor), 0) FROM faturamentos AS f WHERE f.matricula = a.matricula) AS total
FROM associados AS a WHERE ".$convenio.'';

// retorna todo os registros para montar a paginação
$Query_all = titulos::find_by_sql($query);
$items = count($Query_all);

// monta a query especificaD
$query_p = $query.' ORDER BY matricula ASC LIMIT '.$inicio.','.$limite.'';

$Query_titulos = associados::find_by_sql($query_p);

$Listitles = new ArrayIterator($Query_titulos);

?>
</div>
<div style="height:<?php echo tool::HeightContent($COB_Heigth) - 75; ?>px; overflow:hidden; padding-bottom: 55px; ">

<table  class="uk-table uk-table-striped uk-table-hover" >
<tbody>
<?php
$linha = 0;
while ($Listitles->valid()):
  $linha++;
  ?>

  <tr style="line-height:22px;">
  <th class="uk-width uk-text-center" style="width:20px; vertical-align: middle;"><?php echo $linha; ?></th>
  <td class="uk-width uk-text-center" style="width:90px;"><?php echo $Listitles->current()->matricula; ?> </th>
  <td class="uk-text-left" style="text-transform: uppercase;"><?php echo utf8_encode($Listitles->current()->nm_associado); ?></td>
  <td class="uk-width uk-text-center" style="width:150px;" ><?php if($Listitles->current()->primeiro > 0):echo number_format($Listitles->current()->primeiro, 2, ',', '.');else: echo'-';endif; ?></td>
  <td class="uk-width uk-text-center " style="width:150px;" ><?php if($Listitles->current()->segundo > 0):echo number_format($Listitles->current()->segundo, 2, ',', '.');else: echo'-';endif; ?></td>
  <td class="uk-width uk-text-center" style="width:150px;" ><?php if($Listitles->current()->terceiro > 0):echo number_format($Listitles->current()->terceiro, 2, ',', '.');else: echo'-';endif; ?></td>
  <td class="uk-width uk-text-center" style="width:100px;" ><?php echo number_format($Listitles->current()->total, 2, ',', '.'); ?></td>
  <td class="uk-width uk-text-center" style="width:120px; vertical-align: middle;" >
  <div class="uk-coment-action" style="font-weight: normal; text-align: left; z-index: 10000; position: absolute; margin: -20px 25px;">
  <div class="uk-button-group">
  <div data-uk-dropdown="{pos:'left-center',mode:'click'}">
  <button class=" uk-button uk-button uk-button-large uk-icon-ellipsis-v " style="margin:5px 0; border:0; background:none;"></button>
  <div class="uk-dropdown uk-dropdown-small">
  <ul class="uk-nav uk-nav-dropdown">
  <li><a href="javascript:void(0);" class="BtnHist" data-mat="<?php echo $Listitles->current()->matricula; ?>"><i class="uk-icon-edit"></i> Adcionar Histórico</a></li>
  <li><a href="JavaScript:void(0);" onclick="D_Actions_Assoc('fat','<?php echo $Listitles->current()->matricula; ?>','<?php echo $Listitles->current()->cdconv; ?>');"><i class="uk-icon-list"></i> Faturamento</a></li>
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
<span style="float: left; margin: 7px;" class="uk-text-small"><?php echo 'Total de Registros '.$items.''; ?></span>
<ul class="uk-pagination" style="position:relative; margin:3px 0;" data-uk-pagination="{edges:4,items:<?php echo $items; ?>, itemsOnPage:16, currentPage:<?php echo $pagina; ?>}"></ul>

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
    url: "assets/relatorios/inadimplentes_mes_a_mes/ajax_grid.php",
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