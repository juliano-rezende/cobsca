<?php
require_once"../../sessao.php";
include("../../conexao.php");
$cfg->set_model_directory('../../models/');

$FRM_matricula		=	isset( $_GET['matricula']) 		? $_GET['matricula']		: tool::msg_erros("O Campo matricula é Obrigatorio.");
$FRM_convenio_id	=	isset( $_GET['convenio_id']) 	? $_GET['convenio_id']		: tool::msg_erros("O Campo convenio_id é Obrigatorio.");


// recupera os dados do associado
$Query_dados= dados_cobranca::find_by_sql("	SELECT
												dados_cobranca.*,
												planos.id as plano_id,
												planos.descricao as plano_desc,
												planos.valor as plano_valor,
												planos.obs_plano as obs_plano,
												dados_cobranca.forma_cobranca_id as forma_cobranca_id,
												formas_cobranca.forma_cobranca_sys_id as forma_cobranca_sys_id,
												formas_cobranca.descricao as formas_cobranca_desc
											FROM
												dados_cobranca
												INNER JOIN planos ON dados_cobranca.planos_id = planos.id
												INNER JOIN formas_cobranca ON dados_cobranca.forma_cobranca_id = formas_cobranca.id
											WHERE
												matricula = '".$FRM_matricula."'");

?>

<form method="post" id="Frmdadoscobranca" class="uk-form">
<fieldset style="width:450px;">

    <label>
    	<span>Cobrança </span>

		<input type="hidden" value="<?php if($Query_dados){echo $Query_dados[0]->id;} ?>" id="dados_cobranca_id" name="dados_cobranca_id"/>
		<input type="hidden" value="<?php echo $FRM_matricula ?>" id="dados_cobranca_matricula" name="dados_cobranca_matricula"/>
    	<input type="hidden" value="<?php if($Query_dados){echo $Query_dados[0]->forma_cobranca_id;} ?>" id="confirm_forma_cobranca" name="confirm_forma_cobranca"/>
    	<input type="hidden" value="<?php if($Query_dados){echo $Query_dados[0]->forma_cobranca_sys_id;} ?>" id="forma_cobranca_sys_id" name="forma_cobranca_sys_id"/>
        <select class="select"   name="formadecobranca" id="formadecobranca" >
			<?php
			if($Query_dados){


				$Query_formas=formas_cobranca::find('all',array('conditions'=>array('convenios_id= ? and  status= ?',''.$FRM_convenio_id.'',1)));

				$formas= new ArrayIterator($Query_formas);
				while($formas->valid()):

				if($Query_dados[0]->forma_cobranca_id == $formas->current()->id){$select='selected="selected"';}else{$select="";}

				echo'<option  '.$select.' value="'.$formas->current()->id.'-'.$formas->current()->forma_cobranca_sys_id.'">'.strtoupper(utf8_encode($formas->current()->descricao));

				$formas->next();
				endwhile;

			}else{

				echo'<option selected="selected"  value="" >Selecionar';

				$Query_formas=formas_cobranca::find('all',array('conditions'=>array('convenios_id= ? and status= ?',''.$FRM_convenio_id.'', 1)));
				$formas= new ArrayIterator($Query_formas);
				while($formas->valid()):
				echo'<option  value="'.$formas->current()->id.'-'.$formas->current()->forma_cobranca_sys_id.'" >'.strtoupper(utf8_encode($formas->current()->descricao));
				$formas->next();
				endwhile;

				}
			?>
          </select>
    </label>
	<label>
    	<span>Planos</span>
        <select class="select"  name="planos_id" id="planos_id" >
			<?php

			if($Query_dados){

				$Query_planos=planos::find('all',array('conditions'=>array('forma_cobranca_id= ? ',''.$Query_dados[0]->forma_cobranca_id.'')));
				$planos= new ArrayIterator($Query_planos);
				while($planos->valid()):

				if($Query_dados[0]->plano_id == $planos->current()->id){$select='selected="selected"';}else{$select="";}

				echo'<option  value="'.$planos->current()->id.'" '.$select.' >'.strtoupper(utf8_encode($planos->current()->descricao)).' - '.number_format($planos->current()->valor,2,",",".");
				$planos->next();
				endwhile;
			}
			?>
          </select>
	</label>
	<label>
    	<span>Vencimento</span>
		<select class="select"  name="dt_venc_p" id="dt_venc_p" >

		<?php if($Query_dados){ ?>

			<?php if($Query_dados[0]->dt_venc_p == 10){ ?>

				<option value="5" selected="selected">dia 05
				<option value="10">dia 10
				<option value="13">dia 13
				<option value="15">dia 15
	 			<option value="18">dia 18
	  			<option value="25">dia 25
	  			<option value="28">dia 28

			<?php }elseif($Query_dados[0]->dt_venc_p == 10){ ?>

				<option value="5">dia 05
				<option value="10" selected="selected">dia 10
				<option value="13">dia 13
				<option value="15">dia 15
	 			<option value="18">dia 18
	  			<option value="25">dia 25
	  			<option value="28">dia 28

			<?php }elseif($Query_dados[0]->dt_venc_p == 13){ ?>

				<option value="5">dia 05
				<option value="10">dia 10
				<option value="13" selected="selected">dia 13
				<option value="15">dia 15
	 			<option value="18">dia 18
	  			<option value="25">dia 25
	  			<option value="28">dia 28
			<?php }elseif($Query_dados[0]->dt_venc_p == 15){ ?>

				<option value="5">dia 05
				<option value="10">dia 10
				<option value="13">dia 13
				<option value="15" selected="selected">dia 15
	 			<option value="18">dia 18
	  			<option value="25">dia 25
	  			<option value="28">dia 28

			<?php }elseif($Query_dados[0]->dt_venc_p == 18){ ?>

				<option value="5">dia 05
				<option value="10">dia 10
				<option value="13">dia 13
				<option value="15">dia 15
	 			<option value="18" selected="selected" >dia 18
	  			<option value="25">dia 25
	  			<option value="28">dia 28

			<?php }elseif($Query_dados[0]->dt_venc_p == 25){ ?>

				<option value="5">dia 05
				<option value="10">dia 10
				<option value="13">dia 13
				<option value="15">dia 15
	 			<option value="18">dia 18
	  			<option value="25" selected="selected">dia 25
	  			<option value="28">dia 28
		<?php }elseif($Query_dados[0]->dt_venc_p == 28){ ?>

				<option value="5">dia 05
				<option value="10">dia 10
				<option value="13">dia 13
				<option value="15">dia 15
	 			<option value="18">dia 18
	  			<option value="25" >dia 25
	  			<option value="28" selected="selected">dia 28
		<?php }else{ ?>
			<option value="" selected="selected">Selecionar
			<option value="5">dia 05
			<option value="10">dia 10
			<option value="13">dia 13
			<option value="15">dia 15
 			<option value="18">dia 18
  			<option value="25">dia 25
  			<option value="28">dia 28
		<?php }

		}else{ ?>
			<option value="" selected="selected">Selecionar
			<option value="5">dia 05
			<option value="10">dia 10
			<option value="13">dia 13
			<option value="15">dia 15
 			<option value="18">dia 18
  			<option value="25">dia 25
  			<option value="28">dia 28
		<?php

		}

		?>
		</select>
    </label>
	<label>
        <span>Descrição</span>
        <input type="text" readonly="readonly" class="input_text w_300" value="<?php if($Query_dados):echo $Query_dados[0]->obs_plano; endif; ?>" name="desc_plano" id="desc_plano"/>
	</label>

<?php if(!$Query_dados){ ?>

<label>
	<div class="uk-grid uk-text-small" style="margin-left: 85px; margin-top: 10px;" >
	    <div class="uk-width-1-1">
	      <input type="checkbox" name="tx_adesao" id="tx_adesao" checked="checked" value="1" > Gerar Taxa de Adesão?
	    </div>
	</div>
</label>
<?php
}
?>

</fieldset>
<fieldset style="width:450px; display:none;background-color:transparent;" id="lz">
<legend>dados da Conta de Luz</legend>
	<label>
        <span>Identificador</span>
        <input type="text"  class="input_text w_300" value="<?php if($Query_dados):echo $Query_dados[0]->luz_identificador; endif; ?>" name="luz_identificador" id="luz_identificador"/>
	</label>
	<label>
        <span>Nº Cliente</span>
        <input type="text"  class="input_text w_300" value="<?php if($Query_dados):echo $Query_dados[0]->luz_num_cliente; endif; ?>" name="luz_num_cliente" id="luz_num_cliente"/>
	</label>
</fieldset>
<fieldset style="width:450px; display:none;background-color:transparent;" id="ag">
<legend>dados da Conta de Agua</legend>
	<label>
        <span>Matricula</span>
        <input type="text"  class="input_text w_300" value="<?php if($Query_dados):echo $Query_dados[0]->matricula_ca; endif; ?>" name="matricula_ca" id="matricula_ca"/>
	</label>
	<label>
        <span>Identificador</span>
        <input type="text"  class="input_text w_300" value="<?php if($Query_dados):echo $Query_dados[0]->identificador_ca; endif; ?>" name="identificador_ca" id="identificador_ca"/>
	</label>
</fieldset>
<fieldset style="width:450px; display:none;background-color:transparent;" id="cc">
<legend>dados do cartão de credito</legend>
	<label>
        <span>Nº Cartão</span>
        <input type="text"  class="center input_text w_180" value="<?php if($Query_dados):echo $Query_dados[0]->numero_cc; endif; ?>" name="numero_cc" id="numero_cc"/>
	</label>
	<label>
        <span>Validade</span>
        <input type="text"  class="center input_text w_80" value="<?php if($Query_dados):echo $Query_dados[0]->validade_cc; endif; ?>" name="validade_cc" id="validade_cc"/>
	</label>
	<label>
        <span>Cod Segurança</span>
        <input type="text"  class="input_text w_80" value="<?php if($Query_dados):echo $Query_dados[0]->cod_seg_cc; endif; ?>" name="cod_seg_cc" id="cod_seg_cc"/>
	</label>
</fieldset>

<fieldset style="width:450px; display:none; background-color:transparent;" id="dc">
<legend>dados da conta bancaria</legend>
	<label>
    	<span>Banco</span>
		<select class="select" name="banco_dc"  id="banco_dc" >
			<?php

			if($Query_dados){

				$bank=bancos::find_by_cod_banco($Query_dados[0]->banco_dc);

				if($bank){
				echo'<option selected="selected" value="'.$bank->cod_banco.'" >'.utf8_encode(strtoupper($bank->nm_banco)).'';

	            $banco=bancos::find_by_sql("SELECT cod_banco,nm_banco FROM bancos WHERE cod_banco!='".$bank->cod_banco."'");
	            $bancos= new ArrayIterator($banco);
	            while($bancos->valid()):
	            echo'<option value="'.$bancos->current()->cod_banco.'" >'.utf8_encode(strtoupper($bancos->current()->nm_banco)).'';
	            $bancos->next();
	            endwhile;
	        	}else{

	        	$banco=bancos::find('all');
	            $bancos= new ArrayIterator($banco);
	            echo'<option value="" selected="selected">Selecionar';
	            while($bancos->valid()):
	            echo'<option value="'.$bancos->current()->cod_banco.'" >'.utf8_encode(strtoupper($bancos->current()->nm_banco)).'';
	            $bancos->next();
	            endwhile;
	        	}
	        }else{
	        	$banco=bancos::find('all');
	            $bancos= new ArrayIterator($banco);
	            echo'<option value="" selected="selected">Selecionar';
	            while($bancos->valid()):
	            echo'<option value="'.$bancos->current()->cod_banco.'" >'.utf8_encode(strtoupper($bancos->current()->nm_banco)).'';
	            $bancos->next();
	            endwhile;
	        }
            ?>
        </select>
    </label>
<div style="height:30px; width:245px; position:absolute; top:45px; right:5px; text-align:center;">
    <label>
        <span>Agencia</span>
        <input type="text"  class="input_text w_80" value="<?php if($Query_dados):echo $Query_dados[0]->agencia_dc; endif; ?>" name="agencia_dc" id="agencia_dc"/>
	</label>
</div>

<div style="height:30px; width:245px; position:absolute; top:81px; right:5px; text-align:center;">
	<label>
        <span>Operação</span>
        <input type="text"  class="input_text w_80" value="<?php if($Query_dados):echo $Query_dados[0]->operacao_dc; endif; ?>" name="operacao_dc" id="operacao_dc"/>
	</label>
</div>
	<label>
        <span>Conta</span>
        <input type="text"  class="input_text w_80" value="<?php if($Query_dados):echo $Query_dados[0]->conta_dc; endif; ?>" name="conta_dc" id="conta_dc"/>
	</label>
</fieldset>
</form>

<a  id="Btn_confirm_cob" class="uk-button uk-button-primary " style="position:absolute;; right:15px; bottom:35px; z-index:2;" data-uk-tooltip="{pos:'left'}" title="Confirmar dados" data-cached-title="Confirmar dados" ><i class="uk-icon-check"></i> Confirmar</a>

<script type="text/javascript">

/* mascaras */
jQuery("#numero_cc").mask("9999-9999-9999-9999");
jQuery("#validade_cc").mask("99/9999");

/*seta o placeholder em todos os selects do forumlario*/
jQuery(function($) {
      /*function for placeholder select*/
      function selectPlaceholder(selectID){
        var selected = $(selectID + ' option:selected');
        var val = selected.val();
        $(selectID + ' option' ).css('color', '#333');
        selected.css('color', '#999');
        if (val == "") {
          $(selectID).css('color', '#999');
        };
        $(selectID).change(function(){
          var val = $(selectID + ' option:selected' ).val();
          if (val == "") {
            $(selectID).css('color', '#999');
          }else{
            $(selectID).css('color', '#333');
          };
        });
      };
      selectPlaceholder('.select');
    });

/* popula o campo plano */
jQuery("#formadecobranca").change(function(){

/* definições da altura da window */
var window_alvo 	= jQuery("#Frmdadoscobranca").closest('.Window_Content').attr('id'); /* recupera o id da janela atual */
var window_on 		= jQuery("#"+window_alvo+"").height(); /* recupera o tamanho da janela atual*/
var new_height 		= window_on+110;/* soma mais 200px ao tamanho atual da div*/


	var str = jQuery(this).val(); /* captura o valor selecionado*/
	var res = str.split("-"); /* separa os valores*/

	if(res[0] == "0"){
	UIkit.notify("Forma de Cobrança invalida", {status:'danger',timeout: 2500});
	return false;
	}

	jQuery("#planos_id").html('<option value="">Carregando...');

	jQuery.post("assets/planos/ajax_planos.php", {acao:0,forma_cobranca_id:res[0]},function(valor){ jQuery("#planos_id").html(valor); });

	/* valida a forma de cobrança para exibição dos campos adcionais*/
	if(res[1] > '2' ){

		if(window_on != 360){
			jQuery("#"+window_alvo+"").css("height",""+new_height+"");
		}
	}else{

		if(window_on != 250){
				jQuery("#"+window_alvo+"").css("height",""+window_on-110+"");
		}
	}
	/*exibe os dados adcionais de cada forma de cobrança*/
	if(res[1] == '1' ||  res[1] == '2'){

		jQuery("#ag,#lz,#cc,#dc").hide();

	}if(res[1] == '3'){

				jQuery("#ag,#cc,#dc").hide();
				jQuery("#lz").show();

	}if(res[1] == '4'){

					jQuery("#lz,#cc,#dc").hide();
					jQuery("#ag").show();

	}if(res[1] == '5'){

						jQuery("#lz,#cc,#ag").hide();
						jQuery("#dc").show();

	}if(res[1] == '6'){

						jQuery("#lz,#dc,#ag").hide();
						jQuery("#cc").show();

	}if(res[1] == ''){
							UIkit.modal.alert("Valor não encontrado !");
	}
});

/*popula o campo descricao do plano*/
jQuery("#planos_id").change(function(){

	if($(this).val() == ""){
	UIkit.notify("Plano invalido", {status:'danger',timeout: 2500});
	return false;
	}

	jQuery("#desc_plano").val('Carregando...');

	jQuery.post("assets/planos/ajax_planos.php",
					  {acao:1,plano_id:$(this).val()},
					  /* Carregamos o resultado acima para o campo marca*/
					  function(valor){
									  jQuery("#desc_plano").val(valor);
									  })
});

/*envia o formulario para o controller*/
jQuery(function() {

 jQuery("#Btn_confirm_cob").click(function(event) {

 /*mensagen de carregamento*/
 jQuery("#msg_loading").html(" Aguarde... ");

/*abre a tela de preload*/
 modal.show();


 //desabilita o envento padrao do formulario
 event.preventDefault();

 jQuery.ajax({
				async: true,
				url: "assets/faturamento/controllers/Controller_dados_cobranca.php",
				type: "post",
				data:jQuery("#Frmdadoscobranca").serialize(),
				success: function(resultado) {

				var text = '{"'+resultado+'"}';
                var obj = JSON.parse(text);


				UIkit.notify(''+obj.msg+'', {timeout: 1000,status:''+obj.status+''});

				/* id da janela dados de cobranca Frmdadoscobranca */
				jQuery("#"+jQuery("#Frmdadoscobranca").closest('.Window').attr('id')+"").remove();
				jQuery("#"+jQuery("#fr_faturamentos").closest('.Window').attr('id')+"").remove();

				jQuery("#"+jQuery("#FrmGridFaturamentos").closest('.Window').attr('id')+"").remove();

                New_window('list','950','500','Faturamento','assets/faturamento/Frm_faturamento.php?matricula='+obj.matricula+'&convenio_id='+obj.convenio+'',true,false,'Carregando...');

				},
				error:function (){
					UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
					modal.hide();
					}
			});
		});
});
</script>