<?php
require_once("../../../sessao.php");
?>
<div class="tabs-spacer" style="display:none;">
<?php
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');
?>
</div>
<div id="DvFiltroTitulos" class="uk-modal">
<?php include"ajax/ajax_filtro_titulos.php"; ?>
</div>
<nav class="uk-navbar ">
<table  class="uk-table" >
  <thead >
    <tr style="line-height:25px;">
      <th class="uk-width uk-text-center" style="width:20px"></th>
      <th class="uk-width uk-text-center" style="width:90px;" >Parcela</th>
        <th class="uk-width uk-text-center" style="width:90px;" >Nº Boleto</th>
        <th class="uuk-text-left" >Sacado</th>
        <th class="uk-width uk-text-center" style="width:120px;" >Data Emissao</th>
        <th class="uk-width uk-text-center" style="width:120px;" >Data Vencimento</th>
        <th class="uk-width uk-text-center" style="width:120px;" >Data Pagamento</th>
        <th class="uk-width uk-text-center" style="width:120px;" >Valor</th>
        <th class="uk-width uk-text-center" style="width:120px;" >Valor pago</th>
        <th class="uk-width uk-text-center" style="width:80px;" >Banco</th>
        <th class="uk-width uk-text-center" style="width:120px;" >
           <a href="JavaScript:void(0);" id="BtnFiltro" class="uk-button uk-button-small" data-uk-modal="{target:'#DvFiltroTitulos'}"style="border-left:1px solid #ccc;" ><i class="uk-icon-filter " ></i> Filtrar</a>
        </th>
    </tr>
    </thead>
 </table>
</nav>
<div id="Grid_titulos" style="background-color: #fff; height:<?php echo tool::HeightContent($COB_Heigth,$COB_Browser)-40;?>px;">
</div>