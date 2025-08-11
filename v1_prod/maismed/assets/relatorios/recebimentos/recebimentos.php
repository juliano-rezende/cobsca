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
  <?php include "ajax/ajax_filtro_recebimentos.php"; ?>
</div>
<nav class="uk-navbar uk-text-right" style="padding:5px;">
  <a href="JavaScript:void(0);" id="BtnFiltro" class="uk-button uk-button-small" data-uk-modal="{target:'#DvFiltroTitulos'}" style="border-left:1px solid #ccc;"><i class="uk-icon-filter "></i> Filtrar</a>
</nav>
<div id="Grid_recebimentos" style="background-color: #fff; height:<?php echo tool::HeightContent($COB_Heigth, $COB_Browser) - 55; ?>px; overflow-y: scroll; padding:0;">

</div>