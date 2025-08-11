<?php
require_once"../../sessao.php";
?>
<div class="tabs-spacer" style="display:none;">
<?php

require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');
?>
</div>

<form method="post" id="FrmFatGeral" class="uk-form" style="padding: 10px 0; margin-top: 0; ">
<label>
<span>Convênio</span>
    <select  class="select "  name="convenio_id" id="convenio_id" >
    <option value="">Selecionar Convênio</option>'
	<?php
		$descricaoconvenio=convenios::find('all',array('conditions'=>array('status= ? and empresas_id= ?',1,$COB_Empresa_Id)));
		$descricao= new ArrayIterator($descricaoconvenio);
		while($descricao->valid()):
		echo'<option value="'.$descricao->current()->id.'" >'.$descricao->current()->id." - ".utf8_encode($descricao->current()->nm_fantasia).'</option>';
		$descricao->next();
		endwhile;
	?>
    </select>
</label>

<label>
<span>Forma Cobrança</span>
    <select  class="select "  name="forma_cobranca" id="forma_cobranca" >
      <option>Aguardando...</option>
    </select>
</label>

<label>
<span>Plano</span>
    <select  class="select "  name="plano_id" id="plano_id" >
      <option>Aguardando...</option>
    </select>
</label>

<label>
<span>Forma reajuste</span>
<select  class="select"  name="tiporeajuste" id="tiporeajuste" >
      <option selected="selected">Selecionar</option>
      <option value="0">R$</option>
      <option value="1">%</option>
    </select>
</label>

<label>
<span>Reajuste (R$ / %)</span>
<input type="text" class="input_text center w_100" name="vreajuste" id="vreajuste" onblur="CalculaValor();"/>
</label>

<label>
<span>Valor Atual</span>
<input name="vatual" type="text" class="input_text center w_100" id="vatual" readonly="readonly"  />
</label>

<label>
<span>Valor reajustado</span>
<input name="vreajustado" type="text" class="input_text center w_100 camposleitura" id="vreajustado" readonly="readonly"  />
</label>


<a  id="Btn_reaj_001" class="uk-button uk-button-primary" style="right:10px;margin-right:5px; position:absolute;bottom: 30px; line-height: 30px; width: 120px;" data-uk-tooltip="{pos:'left'}" title="Reajustar Valor" data-cached-title="Reajustar Valor" >Confirmar</a>

</form>


<script type="text/javascript">

jQuery('#vatual').maskMoney({prefix:'R$ ', thousands:'.', decimal:',', affixesStay: true});
jQuery('#vreajustado').maskMoney({prefix:'R$ ', thousands:'.', decimal:',', affixesStay: true});

/* Ao selecionar o convenio */
jQuery("#convenio_id").change(function(){

	if(jQuery(this).val()==""){

		jQuery("#forma_cobranca").html('<option>Selecione um convênio</option>');	/*retorna o resultado da ação */

	}else{

		jQuery("#forma_cobranca").html('<option>Carregando ...</option>');	/*retorna o resultado da ação*/

		/* ja calcula o valor reajustado*/
		$.post("assets/planos/Controller_reajusta_planos.php",{acao:0,convenio_id:jQuery(this).val()},
		/* Carregamos o resultado acima para o campo marca*/
		function(valor){
			jQuery("#forma_cobranca").html(valor);	/*retorna o resultado da ação*/
		});
	}
});

/* Ao selecionar o a forma de cobranca */
$("#forma_cobranca").change(function(){

	if(jQuery(this).val()==""){

		jQuery("#plano_id").html('<option>Selecione uma forma</option>');	/*retorna o resultado da ação*/
	}else{
		jQuery("#plano_id").html('<option>Carregando ...</option>');	/*/retorna o resultado da ação*/
		/* ja calcula o valor reajustado*/
		$.post("assets/planos/Controller_reajusta_planos.php",{acao:1,forma_cobranca_id:jQuery(this).val()},
		//* Carregamos o resultado acima para o campo marca*/
		function(valor){
			jQuery("#plano_id").html(valor);	/*retorna o resultado da ação*/
		});
	}
});

/* Ao selecionar o plano */
$("#plano_id").change(function(){

	if($(this).val()==""){

		jQuery("#vatual").val('Aguardando...');	/*retorna o resultado da ação*/

	}else{

		jQuery("#vatual").val('Aguarde...');	/*retorna o resultado da ação*/
		//* ja calcula o valor reajustado*/
		$.post("assets/planos/Controller_reajusta_planos.php",{acao:3,plano_id:jQuery(this).val()},
		/* Carregamos o resultado acima para o campo marca*/
		function(valor){
			jQuery("#vatual").val(valor);	/*retorna o resultado da ação*/
		});
	}
});

/* Ao selecionar o tipo de reajuste a ser aplicado */
jQuery("#tiporeajuste").change(function(){

	if(jQuery(this).val() == 0){

		jQuery("#vreajuste").mask("99,99").focus();

	}else{

		jQuery("#vreajuste").mask("99").focus

	}
});


/* função para calcular o valor de reajute */
function CalculaValor(){

var plano_id	 = jQuery("#plano_id").val();
var vreajuste	 = jQuery("#vreajuste").val();
var tiporeajuste = jQuery("#tiporeajuste").val();

	jQuery("#vreajustado").val('Aguarde...');	/*retorna o resultado da ação*/

	/* ja calcula o valor reajustado*/
	$.post("assets/planos/Controller_reajusta_planos.php",{acao:2,plano_id:plano_id,vreajuste:vreajuste,tiporeajuste:tiporeajuste},
	/* Carregamos o resultado acima para o campo marca*/
	function(valor){

		jQuery("#vreajustado").val(valor);	/*retorna o resultado da ação*/

	});

}

// atualiza os dados na tabela
jQuery("#Btn_reaj_001").click(function(){

var plano_id	 = jQuery("#plano_id").val();
var vreajuste	 = jQuery("#vreajuste").val();
var tiporeajuste = jQuery("#tiporeajuste").val();

/* mensagen de carregamento*/
jQuery("#msg_loading").html(" Reajustando plano...");

/*abre a tela de preload*/
modal.show();

/* reajusta o plano */
$.post("assets/planos/Controller_reajusta_planos.php",{acao:4,plano_id:plano_id,vreajuste:vreajuste,tiporeajuste:tiporeajuste},
	function(result_plano){

		var j_planos   = '{"'+result_plano+'"}';
		var obj_planos = JSON.parse(j_planos);

		/* se for = 1 indica que houve erro ai retornamo o erro na tela do usuario*/
		if(obj_planos.callback == 1){

			UIkit.modal.alert(''+obj_planos.msg++'');
			modal.hide();


		/* se o callback for 0 indica que não houve erro ai mostramos o resultado da execução das querys*/
		}else{

			/* mensagem de sucesso */
			UIkit.notify(''+obj_planos.msg+'', {timeout: 1000,status:''+obj_planos.status+''});

			/* mensagen de carregamento*/
			jQuery("#msg_loading").html(" Atualizando dados de cobrança");


			/* atualiza os dados de cobrança ligados aquele plano */
			$.post("assets/planos/Controller_atualiza_d_cobranca.php",{plano_id:obj_planos.plano_id},
			/* Carregamos o resultado acima*/
			function(resultado){

				var text = '{"'+resultado+'"}';
                var obj = JSON.parse(text);

				/* se for = 1 indica que houve erro ai retornamo o erro na tela do usuario*/
				if(obj.callback == 1){

					UIkit.modal.alert(''+obj.msg++'');
					modal.hide();

				}else{

					/* mensagem de sucesso */
					UIkit.notify(''+obj.msg+'', {timeout: 1000,status:''+obj.status+''});
					modal.hide();

					setTimeout(function(){

				    	UIkit.modal.confirm('Deseja Atualizar o faturamento ?', function(){

				    		New_window('list','200','200','Reajuste de Parcelas','assets/planos/Frm_atualiza_fat.php?plano_id='+obj_planos.plano_id+'',true,false,'Carregando...');

				    	});

					},1000);

				}

			});
		}
	});

 });
</script>
