<?php
require_once("../../../sessao.php");
// blibliotecas
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');
?>
<style>
.group_button a {border-radius:0; border:0;}
</style>

<div style="float:left;  padding-right: 10px; width:100%; border-top:2px solid #ccc; " class="uk-width-10-10 uk-gradient-cinza" >
    <div id="DvFiltroCpagar" class="uk-modal">
    <?php include"../../../assets/financeiro/cpagar/ajax/ajax_filtro_cpagar.php"; ?>
    </div>
    <div style="float:left; padding: 10px;"> <i class="uk-icon-tags" id="desc_conta"> Contas a Receber</i> </div>
    <div class="uk-button-group group_button"  style="border:0px solid #ccc;float:right; margin-top: 1px; ">
        <a href="JavaScript:void(0);"   class="uk-button uk-button-primary uk-button-small" data-uk-modal="{target:'#DvFiltroCpagar'}" style="border-left:1px solid #ccc;padding-top:2px;line-height: 30px;" ><i class="uk-icon-filter " ></i> Filtrar</a>
        <a href="JavaScript:void(0);" id="Btn_creceber_01" class="uk-button uk-button-primary  uk-button-small" data-uk-modal="{target:'#DvNovaConta'}" style="border-left:1px solid #ccc;padding-top:2px;line-height: 30px;" ><i class="uk-icon-file-text-o" ></i> Adcionar</a>
    </div>
</div>
<div id="Grid_contas_receber" style="padding: 0; overflow-y: auto; padding: 0; float: left; margin:0px auto; z-index:100;height:<?php echo tool::HeightContent($COB_Heigth,$COB_Browser)-45;?>px;" class="uk-width-10-10">
    <div style="color:#666; font-size:20px; text-align:center; width: 100%; margin-top: 10px;"  ><i class='uk-icon-spinner uk-icon-spin'></i><span id="msg_loading"> Carregando </span></div>
</div>
<script type="text/javascript">
/* Carrega a tela inicial com todas as contas*/
LoadContent('assets/financeiro/creceber/Grid_c_receber.php','Grid_contas_receber');
jQuery("#Btn_creceber_01").click(function(){
        jQuery(".Window").remove(); //  fecha demais janelas abertas para prevenir falhas
        New_window('money','700','500','Novo Programação','assets/financeiro/creceber/Frm_creceber.php',true,false,'Aguarde ...');// abre a janela atualizada
})
</script>