<?php
require_once"../../sessao.php";
include("../../conexao.php");
$cfg->set_model_directory('../../models/');

?>


<form method="post" id="FrmCancelamento" class="uk-form" style="padding-top:0;margin:0;">


    <label>
    	<span>Motivo</span>
        <select class="select w_400" name="motivo"  id="motivo" >
			<?php

            $motivos=motivos_cancelamentos::all();

            $array_motivos= new ArrayIterator($motivos);

            echo'<option value="" selected></option>';

            while($array_motivos->valid()):

            echo'<option value="'.$array_motivos->current()->id.'" >'.strtoupper($array_motivos->current()->descricao).'</option>';

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

                        var text = '{"'+resultado+'"}';
                        var obj = JSON.parse(text);

                        // se o callback for 0 indica que não houve erro ai mostramos o resultado da execução das querys
                        if(obj.callback == 1){

                            New_window('exclamation-triangle','500','250','Atenção','<div style="padding: 5px; width:490px; height:240px; overflow-x:auto;">'+obj.msg +'</div>',true,true,'Aguarde...');
                            modal.hide();


                        // se for = 1 indica que houve erro ai retornamo o erro na tela do usuario
                        }else{

                            UIkit.notify(''+obj.msg+'', {timeout: 2000,status:''+obj.status+''});
                            LoadContent('assets/associado/Frm_associado.php?matricula='+jQuery("#matricula").val()+'','content');
                        }
                    },
                    error:function (){
                        UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
                    }

        });
    });

});

</script>