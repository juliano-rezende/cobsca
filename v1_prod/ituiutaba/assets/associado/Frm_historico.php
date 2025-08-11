<?php
require_once"../../sessao.php";
include("../../conexao.php");
$cfg->set_model_directory('../../models/');

?>


<form method="post" id="FrmHistorico" class="uk-form" style="padding-top:10px;margin:0;">

    <label>
        <span>Motivo</span>
        <select class="select w_400" name="motivo"  id="motivo" >
           <option value="0" >Ligação telefonica</option>
           <option value="1" >Envio de SMS</option>
           <option value="2" >OUtros Motivos</option>
        </select>
    </label>

    <label>
        <span>Historico</span>
        <textarea name="detalhes" class="message" style="height:200px; width: 400px;" id="detalhes"> </textarea>
    </label>

       <a  id="Btn_add_hist_0" class="uk-button uk-button-primary" style=" bottom:30px; position:absolute;  right:15px; line-height: 40px; width: 120px;" data-uk-tooltip="{pos:'left'}" title="Confirmar" data-cached-title="Confirmar" ><i class="uk-icon-floppy-o"></i> Gravar </a>

</form>
<script type="text/javascript">

// cancela o contrato
jQuery(function(){

    jQuery("#Btn_add_hist_0").click(function(event) {

        // mensagen de carregamento
            jQuery("#msg_loading").html(" Aguarde...");
            //abre a tela de preload
            modal.show();

        //desabilita o envento padrao do formulario
        event.preventDefault();

        var data="action=add_historico&mat="+jQuery("#matricula").val()+"&motivo="+jQuery("#motivo").val()+"&detalhes="+jQuery("#detalhes").val();

        jQuery.ajax({
                   async: true,
                    url: "assets/associado/Controller_matricula.php",
                    type: "post",
                    data:data,
                    success: function(resultado) {

                        // se o callback for 0 indica que não houve erro ai mostramos o resultado da execução das querys
                        if(resultado == 1){
                            /* exibe a msg */
                            UIkit.notify('Erro ao adcionar histórico.', {timeout: 2000,status:'danger'});
                            /* fecha o preload */
                            modal.hide();

                        // se for = 1 indica que houve erro ai retornamo o erro na tela do usuario
                        }else{

                            /* exibe a msg de retorno*/

                            UIkit.notify('Histórico adcionado com sucesso', {timeout: 2000,status:'success'});
                            /* removemos as janelas abertas */
                            jQuery("#"+jQuery("#grid_historico").closest('.Window').attr('id')+"").remove();
                            jQuery("#"+jQuery("#FrmHistorico").closest('.Window').attr('id')+"").remove();
                            /* abrimos a tela novamente atualizada*/
                            New_window('file-text-o','950','550','Histórico do Contrato','assets/associado/Historico_contrato.php?matricula='+jQuery("#matricula").val()+'',true,false,'Carregando...');
                            /* fechamos o preload*/
                            modal.hide();

                        }
                    },
                    error:function (){
                        UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
                    }

        });
    });

});

</script>