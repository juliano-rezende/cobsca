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

var plano_id = '<?php echo $plano_id; ?>';
var ref_ini	 = jQuery("#ref_ini").val();
var ref_fim	 = jQuery("#ref_fim").val();

/* mensagen de carregamento*/
jQuery("#msg_loading").html(" Reajustando Parcelas...");

/*abre a tela de preload */
modal.show();


/* reajusta o plano */
$.post("assets/planos/Controller_atualiza_fat.php",{plano_id:plano_id,ref_ini:ref_ini,ref_fim:ref_fim},
	/* Carregamos o resultado acima */
	function(resultado){


		var text   = '{"'+resultado+'"}';
		var obj = JSON.parse(text);

		/* se for = 1 indica que houve erro ai retornamo o erro na tela do usuario*/
		if(obj.callback == 1){

			UIkit.notify(''+obj.msg+'', {timeout: 1000,status:'danger'});
			modal.hide();


		/* se o callback for 0 indica que não houve erro ai mostramos o resultado da execução das querys*/
		}else{

			UIkit.notify(''+obj.msg+'', {timeout: 1000,status:''+obj.status+''});
			modal.hide();
		}

	});

 });
</script>