<?php require_once("../../../sessao.php"); ?>
<div class="tabs-spacer" style="display:none;">
<?php
// blibliotecas
require_once("../../../config_sys.php");
require_once("../../../conexao.php");
require_once("../../../functions/funcoes.php");
require_once("../../../functions/funcoes_data.php");
$cfg->set_model_directory('../../../models/');

?>
</div>
<div style="height:26px;float:left;width:<?php echo $SCM_Id_Width-26; ?>px; text-align:right;position:fixed; border-top:0; margin:0px" class="uk-gradient-cinza"  >
<div style="float:left; padding:4px;">
<i class="uk-icon-tags" ></i> Relatório de Fat Convênios
</div>
    <a href="JavaScript:void(0);" id="BtnPrint" class="uk-button uk-button-small" style="border-left:1px solid #ccc;" ><i class="uk-icon-print " ></i> Imprimir</a>
    <a href="JavaScript:void(0);" id="BtnFiltro" class="uk-button uk-button-small" data-uk-modal="{target:'#DvFiltroConsultas'}"style="border-left:1px solid #ccc;" ><i class="uk-icon-filter " ></i> Filtrar</a>
</div>
<div id="DvFiltroConsultas" class="uk-modal">
<?php include"ajax/ajax_filtro_fat.php"; ?>
</div>
<br>
<div style="height:26px;width:<?php echo $SCM_Id_Width-16;?>px; position:fixed; border-top:0; margin:15px 0px;"   >
<table  class="uk-table " >
  <thead class="uk-gradient-blue">
    <tr style="line-height:25px;" >
    	<th class="uk-width uk-text-center" style="width:20px; color:#fff;"></th>
        <th class="uk-width uk-text-center" style="width:100px;color:#fff;" >Codigo</th>
        <th class="uk-width uk-text-center" style="width:100px;color:#fff;" >Data Cad</th>
        <th class="uk-width uk-text-center" style="width:100px;color:#fff;" >Data Venc</th>
        <th class="uk-text-left" style="color:#fff;" >Paciente/Funcionario</th>
        <th class="uk-width uk-text-left" style="width:350px;color:#fff;" >Histórico</th>
        <th class="uk-width uk-text-center" style="width:100px;color:#fff;" >Valor</th>        
    </tr>
  </thead>
 </table>
</div>

<div id="Grid_Faturamentos" style=" padding-top:50px;margin:0px auto; width:<?php echo $SCM_Id_Width-17;?>px;">
</div>