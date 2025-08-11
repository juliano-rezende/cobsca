<?php
include '../../../sessao.php';
?>
<div class="tabs-spacer" style="display:none;">
  <?php
  require_once '../../../conexao.php';
  $cfg->set_model_directory('../../../models/');
  ?>
</div>
<div id="DvFiltro" class="uk-modal">
  <?php include 'ajax/ajax_filtro.php'; ?>
</div>
<nav class="uk-navbar ">
  <table  class="uk-table" >
    <thead >
      <tr style="line-height:25px;">
        <th class="uk-width uk-text-center" style="width:20px"></th>
        <th class="uk-width uk-text-center" style="width:90px;" >Matricula</th>
        <th class="uuk-text-left" >Associado</th>
        <th class="uk-width uk-text-center" style="width:150px;" >Ultimos 30 dias</th>
        <th class="uk-width uk-text-center" style="width:150px;" >Ultimos 60 dias</th>
        <th class="uk-width uk-text-center" style="width:150px;" >Ultimos 90 dias</th>
        <th class="uk-width uk-text-center" style="width:100px;" >Total</th>
        <th class="uk-width uk-text-center" style="width:120px;" >
         <a href="JavaScript:void(0);" id="BtnFiltro" class="uk-button uk-button-small" data-uk-modal="{target:'#DvFiltro'}" style="border-left:1px solid #ccc;" ><i class="uk-icon-filter " ></i> Filtrar</a>
       </th>
     </tr>
   </thead>
 </table>
</nav>
<div id="GridVeiw" style="background-color: #fff; height:<?php echo tool::HeightContent($COB_Heigth, $COB_Browser) - 40; ?>px;">
</div>