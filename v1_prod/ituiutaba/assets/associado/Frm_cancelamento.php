<?php
require_once"../../sessao.php";
include("../../conexao.php");
$cfg->set_model_directory('../../models/');

?>

<style>
    .uk-notify{width: 500px;}
</style>

<form method="post" id="FrmCancelamento" class="uk-form" style="padding-top:0;margin:0;">
    <label>
    	<span>Motivo</span>
        <select class="select w_400" name="motivo"  id="motivo" >
			<?php

            $motivos=motivos_cancelamentos::all();

            $array_motivos= new ArrayIterator($motivos);

            echo'<option value="" selected></option>';

            while($array_motivos->valid()):

            echo'<option value="'.$array_motivos->current()->id.'" >'.(strtoupper($array_motivos->current()->descricao)).'</option>';

            $array_motivos->next();

            endwhile;

            ?>
        </select>
	</label>
    <label>
        <span>Observações</span>
        <textarea name="detalhes" class="message" style="height:200px; width: 400px;" id="detalhes"> </textarea>
    </label>

       <a  id="Btn_cancel_0" class="uk-button uk-button-primary" style=" bottom:30px; position:absolute;  right:15px;" data-uk-tooltip="{pos:'left'}" title="Confirmar" data-cached-title="Confirmar" ><i class="uk-icon-check"></i> Confirmar </a>

</form>
<script type="text/javascript">

// cancela o contrato
jQuery(function(){

    jQuery("#Btn_cancel_0").click(function(event) {

        // mensagen de carregamento
            jQuery("#msg_loading").html(" Aguarde...");
            //abre a tela de preload
            modal.show();

        //desabilita o envento padrao do formulario
        event.preventDefault();

        var data="action=cancel_contrato&mat="+jQuery("#matricula").val()+"&motivo="+jQuery("#motivo").val()+"&detalhes="+jQuery("#detalhes").val();

        jQuery.ajax({
                   async: true,
                    url: "assets/associado/Controller_matricula.php",
                    type: "post",
                    data:data,
                    success: function(resultado) {

                        if(jQuery.isNumeric(resultado)){

                            UIkit.notify("Cancelamento realizado com sucesso!", {status:'success',timeout: 2500});
                            LoadContent('assets/associado/Frm_associado.php?matricula='+jQuery("#matricula").val()+'','content');

                        // se for = 1 indica que houve erro ai retornamo o erro na tela do usuario
                        }else{

                            //abre a tela de preload
                            modal.hide();
                            UIkit.notify(""+resultado+"", {status:'danger',timeout: 3000});

                        }
                    },
                    error:function (){
                        UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
                    }
        });
    });
});
</script>