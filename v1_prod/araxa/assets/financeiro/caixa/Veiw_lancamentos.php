<?php require_once("../../../sessao.php"); ?>
<div class="tabs-spacer" style="display:none;">
<?php
// blibliotecas
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

?>
</div>
<style>
.group_button a {border-radius:0; border:0;}
</style>
<div id="dvconta" class="uk-modal">
<?php include"../../../assets/financeiro/caixa/ajax/ajax_contas.php"; ?>
</div>

<div id="DvFiltroLancamentos" class="uk-modal">
<?php include"../../../assets/financeiro/caixa/ajax/ajax_filtro_lancamentos.php"; ?>
</div>

<div id="DvTransferencia" class="uk-modal">
<?php include"../../../assets/financeiro/caixa/ajax/ajax_transferir.php"; ?>
</div>


<div style="float:left;  padding-right: 10px; width:100%; border-top:2px solid #ccc; " class="uk-width-10-10 uk-gradient-cinza" >

      <div style="float:left; padding: 10px;"> <i class="uk-icon-tags" id="desc_conta"> Lançamentos</i> </div>
        <div class="uk-button-group group_button"  style="border:0px solid #ccc;float:right; margin-top: 1px; ">
        <a href="JavaScript:void(0);" style="padding-top:2px; line-height: 30px;" id="BtnContas" class="uk-button uk-button-primary  uk-button-small "  data-uk-modal="{target:'#dvconta'}" ><i class="uk-icon-archive " ></i> Alternar Conta</a>
        <a href="JavaScript:void(0);"  id="Btn_caixa_00" class="uk-button uk-button-primary uk-button-small" data-uk-modal="{target:'#DvFiltroLancamentos'}" style="border-left:1px solid #ccc;padding-top:2px;line-height: 30px;" ><i class="uk-icon-filter " ></i> Filtrar</a>
        <a href="JavaScript:void(0);" id="Btntransferir" class="uk-button uk-button-primary  uk-button-small" data-uk-modal="{target:'#DvTransferencia'}" style="border-left:1px solid #ccc;padding-top:2px;line-height: 30px;" ><i class="uk-icon-exchange " ></i> Transferir</a>
        <a href="JavaScript:void(0);" id="Btn_caixa_01" class="uk-button uk-button-primary  uk-button-small " onClick="PosicaoPainel('Btn_Contas','Dvconta');" style="border-left:1px solid #ccc;padding-top:2px;line-height: 30px;" ><i class="uk-icon-download " ></i> Depositar</a>
        <a href="JavaScript:void(0);" id="Btn_caixa_02" class="uk-button uk-button-primary  uk-button-small " onClick="PosicaoPainel('Btn_Contas','Dvconta');" style="border-left:1px solid #ccc;padding-top:2px;line-height: 30px;" ><i class="uk-icon-upload " ></i> Pagar</a>
        </div>
</div>
<!--
<div style="float:left;" class="uk-width-10-10 uk-gradient-cinza" >
<ul class="uk-tab " data-uk-tab style="height:28px;">
    <li><a href="#Geral" ><<</a></li>
    <li><a href="#Geral" >Jan/2017</a></li>
    <li><a href="#Detalhes">Fev/2017</a></li>
    <li><a href="#Detalhes">Mar/2017</a></li>
    <li><a href="#Detalhes">Abr/2017</a></li>
    <li><a href="#Detalhes">Mai/2017</a></li>
    <li><a href="#Detalhes">Jun/2017</a></li>
    <li><a href="#Detalhes">Jul/2017</a></li>
    <li><a href="#Detalhes">Ago/2017</a></li>
    <li><a href="#Detalhes">Set/2017</a></li>
    <li><a href="#Detalhes">Out/2017</a></li>
    <li><a href="#Detalhes">Nov/2017</a></li>
    <li><a href="#Detalhes">Dez/2017</a></li>
    <li><a href="#Geral" >>></a></li>

</ul>
</div>
-->
<div  style="float: left;" class="uk-width-10-10">
<table  class="uk-table"  >
  <thead class="uk-gradient-cinza">
    <tr style="line-height:30px;">
        <th class="uk-width uk-text-center" style="width:20px; color:#666;" >t</th>
        <th class="uk-width uk-text-center" style="width:90px; color:#666;" >Cd</th>
        <th class="uk-width uk-text-center" style="width:100px; color:#666;" >Data</th>
        <th class="uk-width uk-text-left" style="width:300px; color:#666;" >Histórico</th>
        <th class="uk-width uk-text-left" style="width:200px; color:#666;">Favorecido / Pagador </th>
        <th class="uk-text-left" style="color:#666;" ></th>
        <th class="uk-width uk-text-center" style="width:150px; color:#666;" >Num Doc</th>
        <th class="uk-width uk-text-center" style="width:100px; color:#666;" >Entradas</th>
        <th class="uk-width uk-text-center" style="width:95px; color:#666;" >Saidas</th>
        <th class="uk-width uk-text-center" style="width:115px; color:#666;" >Saldo</th>
    </tr>
  </thead>
</table>
</div>

<div  style="float: left;" class="uk-width-10-10">
<div id="gridlancamentos" style=" overflow-y: auto; float: left; margin:0px auto; z-index:100;height:<?php echo tool::HeightContent($COB_Heigth,$COB_Browser)-85;?>px;" class="uk-width-10-10">
<span style=" width:100%; display:block; text-align:center; ">
Carregando Grid...
</span>
</div>



<script type="text/javascript">

jQuery("#gridlancamentos").load('assets/financeiro/caixa/Grid_lancamentos.php?conta_id='+jQuery("#conta_id_alt").val()+'&periodo=0');
//jQuery("#FiltroLancamentos").load('assets/caixa/ajax/ajax_filtro_lancamentos.php');

jQuery(document).ready(function(){

jQuery("#Dvconta").hide();
jQuery("#DvFiltroLancamentos").hide();
jQuery("#FiltroLancamentos").hide();

});

/********************************************************************/
jQuery("#Btn_caixa_01").click(function(){

        jQuery(".Window").remove(); //  fecha demais janelas abertas para prevenir falhas
        New_window('money','650','440','Recebimentos','assets/financeiro/caixa/Frm_depositar.php',true,false,'Aguarde ...');// abre a janela atualizada
    })
/********************************************************************/
jQuery("#Btn_caixa_02").click(function(){

        jQuery(".Window").remove(); //  fecha demais janelas abertas para prevenir falhas
        New_window('money','650','440','Pagamentos','assets/financeiro/caixa/Frm_pagar.php',true,false,'Aguarde ...');// abre a janela atualizada
    })
</script>