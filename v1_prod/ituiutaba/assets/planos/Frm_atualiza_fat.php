<?php
$Frm_cad 	=	true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once"../../sessao.php";
?>
<div class="tabs-spacer" style="display:none;">
<?php

require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');

$plano_id		= isset( $_GET['plano_id']) 	 ? $_GET['plano_id']
											     : tool::msg_erros("O Campo id do plano Obrigatorio faltando.");
?>
</div>

<form method="post" id="FrmAtualizaFat" class="uk-form" style="padding: 10px 0; margin-top: 0; ">

<label>
<span>Referencia Inicial</span>
<input  type="text" class=" w_100 uk-text-center" name="ref_ini" id="ref_ini"  placeholder="00/0000"/>
<input  type="hidden" class=" w_100 uk-text-center" name="plano_id" id="plano_id" value="<?php echo $plano_id; ?>">
</label>

<label>
<span>Referencia Final</span>
<input  type="text" class=" w_100 uk-text-center" name="ref_fim" id="ref_fim"  placeholder="00/0000"/>
</label>

<a  id="Btn_r_fat_001" class="uk-button uk-button-primary" style="right:10px;margin-right:5px; position:absolute;bottom: 30px; line-height: 30px; width: 120px;" data-uk-tooltip="{pos:'left'}" title="Reajustar Parcelas" data-cached-title="Reajustar Parcelas" >Confirmar</a>

</form>

<script type="text/javascript">


jQuery("#ref_ini,#ref_fim").mask("99/9999");


// atualiza os dados na bd
jQuery("#Btn_r_fat_001").click(function(){


/* mensagen de carregamento*/
jQuery("#msg_loading").html(" Reajustando Parcelas...");

/*abre a tela de preload */
modal.show();

   jQuery.ajax({
                   async: true,
                    url: "assets/planos/Controller_atualiza_fat.php",
                    type: "post",
                    data:jQuery("#FrmAtualizaFat").serialize(),
                    success: function(resultado) {

                      New_window('check','980','500','Parcelas',''+resultado+'',false,true);// não envia msg pois não é load e sim html

                    },
                    error:function (){
                        UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
                        modal.hide();
                    }

        });

 });
 </script>