<?php
require_once("../../../sessao.php");
// blibliotecas
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');



$C_pagar= contas_pagar::find_by_sql("SELECT SQL_CACHE
(SELECT  SUM(vlr_nominal) FROM contas_pagar WHERE MONTH(dt_vencimento) = MONTH(NOW()) AND status='0' AND empresas_id='".$COB_Empresa_Id."') AS vlr1,
(SELECT  SUM(vlr_nominal) FROM contas_pagar WHERE MONTH(dt_vencimento) = MONTH(DATE_ADD(NOW(), INTERVAL 1 MONTH)) AND status='0' AND empresas_id='".$COB_Empresa_Id."') AS vlr2,
(SELECT  SUM(vlr_nominal) FROM contas_pagar WHERE MONTH(dt_vencimento) = MONTH(DATE_ADD(NOW(), INTERVAL 2 MONTH)) AND status='0' AND empresas_id='".$COB_Empresa_Id."') AS vlr3,
(SELECT  SUM(vlr_nominal) FROM contas_pagar WHERE MONTH(dt_vencimento) = MONTH(DATE_ADD(NOW(), INTERVAL 3 MONTH)) AND status='0' AND empresas_id='".$COB_Empresa_Id."') AS vlr4,
(SELECT  SUM(vlr_nominal) FROM contas_pagar WHERE MONTH(dt_vencimento) = MONTH(DATE_ADD(NOW(), INTERVAL 4 MONTH)) AND status='0' AND empresas_id='".$COB_Empresa_Id."') AS vlr5
FROM contas_pagar")


?>

<style>
.group_button a {border-radius:0; border:0;}
</style>

<div style="float:left;  padding-right: 10px; width:100%; border-top:2px solid #ccc; " class="uk-width-10-10 uk-gradient-cinza" >
    <div id="DvFiltroCpagar" class="uk-modal">
    <?php include"../../../assets/financeiro/cpagar/ajax/ajax_filtro_cpagar.php"; ?>
    </div>
    <div style="float:left; padding: 10px;"> <i class="uk-icon-tags" id="desc_conta"> Contas a Pagar</i> </div>
    <div class="uk-button-group group_button"  style="border:0px solid #ccc;float:right; margin-top: 1px; ">
        <a href="JavaScript:void(0);"   class="uk-button uk-button-primary uk-button-small" data-uk-modal="{target:'#DvFiltroCpagar'}" style="border-left:1px solid #ccc;padding-top:2px;line-height: 30px;" ><i class="uk-icon-filter " ></i> Filtrar</a>
        <a href="JavaScript:void(0);" id="Btn_cpagar_01" class="uk-button uk-button-primary  uk-button-small" data-uk-modal="{target:'#DvNovaConta'}" style="border-left:1px solid #ccc;padding-top:2px;line-height: 30px;" ><i class="uk-icon-file-text-o" ></i> Adcionar</a>
    </div>
</div>

<div id="GridProvisaodespesas" style="padding:5px;margin-top:-13px; z-index:100;height:80px;" class="uk-grid uk-grid-match" data-uk-grid-match="{target:'.uk-panel'}">


                <div class="uk-width-medium-1-5">
                    <div class="uk-panel uk-panel-box uk-text-center" style="min-height: 55px;padding-left: 5px;height: 80px;">
                        <div class="uk-text-bold uk-text-muted uk-text-small" style=" margin-top: -12px; padding-top: 0;">
                            <?php echo date("m/Y"); ?>
                        </div>
                        <h2 class="uk-text-bold uk-text-danger" style=" margin-top: 2px; padding-top: 0;"><?php echo number_format($C_pagar[0]->vlr1,2,',','.'); ?></h2>
                        <div class="uk-text-bold uk-text-muted uk-text-small" style=" margin-top: -12px; padding-top: 0;">Programado
                            <a href="#" class="uk-icon-hover uk-icon-search uk-icon-small" style=" float:right; right: 5px; bottom:5px; position: absolute;"
                            uk-data-intevalo="<?php echo "01/".date("m/Y")."-"."31/".date("m/Y");?>"></a>
                        </div>
                    </div>
                </div>
                <div class="uk-width-medium-1-5">
                    <div class="uk-panel uk-panel-box uk-text-center" style="min-height: 55px;padding-left: 5px;height: 80px;">
                        <div class="uk-text-bold uk-text-muted uk-text-small" style=" margin-top: -12px; padding-top: 0;">
                            <?php echo date("m/Y ",strtotime(date("Y-m", strtotime(date(date("Y-m")))) . " +1 month")); ?>
                        </div>
                        <h2 class="uk-text-bold uk-text-danger" style=" margin-top: 2px; padding-top: 0;"><?php echo number_format($C_pagar[0]->vlr2,2,',','.'); ?></h2>
                        <div class="uk-text-bold uk-text-muted uk-text-small" style=" margin-top: -12px; padding-top: 0;">Programado
                            <a href="#" class="uk-icon-hover uk-icon-search uk-icon-small" style=" float:right; right: 5px; bottom:5px; position: absolute;"
                            uk-data-intevalo="01/<?php echo date("m/Y",strtotime(date("Y-m", strtotime(date(date("Y-m"))))." +1 month")); ?>-31/<?php echo date("m/Y",strtotime(date("Y-m-d", strtotime(date(date("Y-m"))))." +1 month")); ?>"></a>
                        </div>
                    </div>
                </div>
                <div class="uk-width-medium-1-5">
                    <div class="uk-panel uk-panel-box uk-text-center" style="min-height: 55px;padding-left: 5px;height: 80px;">
                        <div class="uk-text-bold uk-text-muted uk-text-small" style=" margin-top: -12px; padding-top: 0;">
                            <?php echo date("m/Y ",strtotime(date("Y-m-d", strtotime(date(date("Y-m-d")))) . " +2 month")); ?>
                        </div>
                        <h2 class="uk-text-bold uk-text-danger" style=" margin-top: 2px; padding-top: 0;"><?php echo number_format($C_pagar[0]->vlr3,2,',','.'); ?></h2>
                        <div class="uk-text-bold uk-text-muted uk-text-small" style=" margin-top: -12px; padding-top: 0;">Programado
                            <a href="#" class="uk-icon-hover uk-icon-search uk-icon-small" style=" float:right; right: 5px; bottom:5px; position: absolute;"
                            uk-data-intevalo="01/<?php echo date("m/Y",strtotime(date("Y-m", strtotime(date(date("Y-m")))) . " +2 month")); ?>-31/<?php echo date("m/Y",strtotime(date("Y-m", strtotime(date(date("Y-m"))))." +2 month")); ?>"></a>
                        </div>
                    </div>
                </div>
                <div class="uk-width-medium-1-5">
                    <div class="uk-panel uk-panel-box uk-text-center" style="min-height: 55px;padding-left: 5px;height: 80px;">
                        <div class="uk-text-bold uk-text-muted uk-text-small" style=" margin-top: -12px; padding-top: 0;">
                            <?php echo date("m/Y ",strtotime(date("Y-m", strtotime(date(date("Y-m")))) . " +3 month")); ?>
                        </div>
                        <h2 class="uk-text-bold uk-text-danger" style=" margin-top: 2px; padding-top: 0;"><?php echo number_format($C_pagar[0]->vlr4,2,',','.'); ?></h2>
                        <div class="uk-text-bold uk-text-muted uk-text-small" style=" margin-top: -12px; padding-top: 0;">Programado
                            <a href="#" class="uk-icon-hover uk-icon-search uk-icon-small" style=" float:right; right: 5px; bottom:5px; position: absolute;"
                            uk-data-intevalo="01/<?php echo date("m/Y",strtotime(date("Y-m", strtotime(date(date("Y-m")))) . " +3 month")); ?>-31/<?php echo date("m/Y",strtotime(date("Y-m-d", strtotime(date(date("Y-m"))))." +3 month")); ?>"></a>
                        </div>
                    </div>
                </div>
                <div class="uk-width-medium-1-5">
                    <div class="uk-panel uk-panel-box uk-text-center" style="min-height: 55px;padding-left: 5px;height: 80px;">
                        <div class="uk-text-bold uk-text-muted uk-text-small" style=" margin-top: -12px; padding-top: 0;">
                            <?php echo date("m/Y ",strtotime(date("Y-m", strtotime(date(date("Y-m")))) . " +4 month")); ?>
                        </div>
                        <h2 class="uk-text-bold uk-text-danger" style=" margin-top: 2px; padding-top: 0;"><?php echo number_format($C_pagar[0]->vlr5,2,',','.'); ?></h2>
                        <div class="uk-text-bold uk-text-muted uk-text-small" style=" margin-top: -12px; padding-top: 0;">Programado
                            <a href="#" class="uk-icon-hover uk-icon-search uk-icon-small" style=" float:right; right: 5px; bottom:5px; position: absolute;"
                            uk-data-intevalo="01/<?php echo date("m/Y",strtotime(date("Y-m", strtotime(date(date("Y-m"))))." +4 month")); ?>-31/<?php echo date("m/Y",strtotime(date("Y-m-d", strtotime(date(date("Y-m"))))." +4 month")); ?>"></a>
                        </div>
                    </div>
                </div>



</div>


<div id="GridContasPagar" style="padding: 0; overflow-y: auto; background-color: #fff; padding: 0; float: left; margin-top:5px auto; z-index:100;height:<?php echo tool::HeightContent($COB_Heigth,$COB_Browser)-145;?>px;" class="uk-width-10-10">
    <div style="color:#666; font-size:20px; text-align:center; width: 100%; margin-top: 10px;"  ><i class='uk-icon-spinner uk-icon-spin'></i><span id="msg_loading"> Carregando </span></div>
</div>




<script type="text/javascript">
/* Carrega a tela inicial com todas as contas*/
LoadContent('assets/financeiro/cpagar/Grid_c_pagar.php','GridContasPagar');

jQuery("#Btn_cpagar_01").click(function(){
        jQuery(".Window").remove(); //  fecha demais janelas abertas para prevenir falhas
        New_window('money','700','540','Novo Programação','assets/financeiro/cpagar/Frm_cpagar.php',true,false,'Aguarde ...');// abre a janela atualizada
});

/**filtro contas a pagar */
jQuery("#GridProvisaodespesas a").click(function(event) {

event.preventDefault();

var intervalo = jQuery(this).attr("uk-data-intevalo");
var res = intervalo.split("-");

var inicio = res[0]; //data inicial
var fim    = res[1];// data final
        LoadContent('assets/financeiro/cpagar/Grid_c_pagar.php?action=search&contas_bancarias_id=&status=&intervalo=6&p_inicio='+inicio+'&p_fim='+fim+'','GridContasPagar');

    });
</script>