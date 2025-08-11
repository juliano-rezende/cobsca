<?php
require_once("../../../sessao.php");
?>
<div class="tabs-spacer" style="display:none;">
<?php
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');
?>
</div>
<div id="DvFiltroAutorizacoes" class="uk-modal">
<?php include"ajax/ajax_filtro_autorizacoes.php"; ?>
</div>
<nav class="uk-navbar ">

<div id="print">
<table  class="uk-table" >
  <thead >
    <tr style="line-height:25px;">
      <th class="uk-width uk-text-center" style="width:20px">Seq</th>
      <th class="uk-width uk-text-center" style="width:90px;" >Codigo</th>
        <th class="uk-width uk-text-center" style="width:90px;" >Status</th>
        <th class="uuk-text-left" >Assegurado</th>
        <th class="uk-width uk-text-center" style="width:120px;" >Dt emissão</th>
        <th class="uk-width uk-text-center" style="width:120px;" >Dt realizacao</th>
        <th class="uk-width uk-text-center" style="width:120px;" >Valor</th>
        <th class="uk-width uk-text-center" style="width:120px;" >
           <a href="JavaScript:void(0);" id="BtnFiltro" class="uk-button uk-button-small" data-uk-modal="{target:'#DvFiltroAutorizacoes'}" style="border-left:1px solid #ccc;" ><i class="uk-icon-filter " ></i> Filtrar</a>
        </th>
    </tr>
    </thead>
 </table>
</nav>
<div id="GridAutorizacoes" style="background-color: #fff; height:<?php echo tool::HeightContent($COB_Heigth,$COB_Browser)-55;?>px; overflow-y: scroll;">
  Carregando ...
</div>
</div>
<script type="text/javascript">

  jQuery("#GridAutorizacoes").load('assets/relatorios/medautorizacoes/ajax_grid_autorizacoes.php');


</script>