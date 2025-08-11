<?php
require_once "../../sessao.php";
include("../../conexao.php");
$cfg->set_model_directory('../../models/');

$FRM_idProtesto	=	isset( $_GET['id'])   ? $_GET['id']	: "";
?>
<form method="post" id="FrmCobranca" class="uk-form" style="padding: 10px;">
 
    <label>
        <span>Histórico inicial</span>
        <textarea name="detalhes" class="message" style="height:190px; id=" id="detalhes"> </textarea>
        
    </label>
    <a id="Btn_cancel_0" class="uk-button uk-button-primary" style="right: 10px; position:  absolute; bottom: 30px;" data-uk-tooltip="{pos:'left'}" title="Confirmar" data-cached-title="Confirmar"><i class="uk-icon-floppy-o"></i> Gravar </a>
</form>
<script type="text/javascript">
    // cancela o contrato
    jQuery(function () {
        jQuery("#Btn_cancel_0").click(function (event) {

            jQuery("#msg_loading").html(" Aguarde...");
            modal.show();
            event.preventDefault();

            var data = "mat=" + jQuery("#matricula").val() + "&historico=" + jQuery("#detalhes").val()+ "&id_prot=<?=$FRM_idProtesto ?>";

            jQuery.ajax({
                async: true,
                url: "assets/associado/Controller_cobranca.php",
                type: "post",
                data: data,
                success: function (resultado) {
                    
                    if(jQuery.isNumeric(resultado)){
                        UIkit.notify("Cobrança cadastrada com sucesso.", {status:'success',timeout: 2500});

                        setTimeout(function (){
                            jQuery("#msg_loading").html(" Carregando ");
                            jQuery("#" + jQuery("#FrmCobranca").closest('.Window').attr('id') + "").remove();
                            modal.hide();
                            jQuery("#" + jQuery("#gridHist").closest('.Window').attr('id') + "").remove();
                            var matricula=resultado;
                            New_window('archive','800','500','Historicos da cobrança','assets/associado/Grid_cobranca.php?id=<?=$FRM_idProtesto ?>&mat='+matricula+'',true,false,'Carregando...');
                        },2500);

                    }else{
                        modal.hide();
                        UIkit.notify(""+resultado+"", {status:'danger',timeout: 2500});
                    }
                },
                error: function () {
                    UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
                }
            });
        });
    });

</script>