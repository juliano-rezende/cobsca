<?php
require_once"../../../sessao.php";
?>
<div class="tabs-spacer" style="display:none;">
<?php
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

if(isset($_GET['id'])){
// recupera os dados do usuario
$dadosdeposito=caixa::find($_GET['id']);
}else{}
?>
</div>
<style type="text/css" media="screen">
#FrmDeposito{padding-top: 5px;}
#FrmDeposito span{width: 150px;}
</style>
<ul class="uk-tab uk-gradient-cinza" data-uk-tab style="height:35px;">
    <li ><a href="#Geral" >Geral</a></li>
    <li><a href="#Detalhes">Informações Adicionais</a></li>
</ul>
<form name="FrmDeposito" method="post" id="FrmDeposito" class="uk-form">
<div id="Geral" class="tab-content">

<label>
<span>Codigo</span>
<input value="<?php if(isset($dadosdeposito)):echo $dadosdeposito->id; endif; ?>"  name="id" type="text" class="uk-text-center w_100  " id="id" readonly="readonly" />
</label>

<label>
<span>Conta para deposito</span>
<select name="contas_bancarias_id" class="select w_350" id="contas_bancarias_id">
<?php
if(isset($dadosdeposito)):

	$query_conta=contas_bancarias::all(array('conditions'=>array('status= ? AND empresas_id= ?','1', $COB_Empresa_Id)));
	$conta= new ArrayIterator($query_conta);

	while($conta->valid()):

	if($conta->current()->id == $dadosdeposito->contas_bancarias_id){$select='selected="selected"';}else{$select="";}

	echo'<option value="'.$conta->current()->id.'" '.$select.'>'.utf8_encode($conta->current()->nm_conta).'</option>';
	$conta->next();
	endwhile;

else:

	$query_conta=contas_bancarias::all(array('conditions'=>array('status= ? AND empresas_id= ?','1', $COB_Empresa_Id)));
	$conta= new ArrayIterator($query_conta);

	echo'<option value="" selected="selected"></option>';

	while($conta->valid()):
	echo'<option value="'.$conta->current()->id.'" >'.utf8_encode($conta->current()->nm_conta).'</option>';
	$conta->next();
	endwhile;

endif;
?>
</select>
</label>

<label>
<span>Receber de</span>
<select name="clientes_fornecedores_id" class="select w_350" id="clientes_fornecedores_id">
<?php
if(isset($dadosdeposito)){


// define se é associado ou cliente/fornecedor
$str 		=" ".$dadosdeposito->clientes_fornecedores_id;
$define_tp	=substr($str, 1,1);

if($define_tp == 0){

	$receberdeAssoc=associados::find(substr($str, 3,10));

	echo'<option value="'.$dadosdeposito->clientes_fornecedores_id.'" selected="selected">'.utf8_encode($receberdeAssoc->nm_associado).'</option>';

	$receberde=clientes_fornecedores::all(array('conditions'=>array('status= ? AND empresas_id= ?','1', $COB_Empresa_Id)));
	$cli_for= new ArrayIterator($receberde);

	while($cli_for->valid()):

	if($cli_for->current()->nm_cliente ==""){

		if($cli_for->current()->razao_social !=""){

			$descricao=$cli_for->current()->nm_fantasia;

		}else{$descricao=$cli_for->current()->razao_social;}

	}else{$descricao=$cli_for->current()->nm_cliente;}

	echo'<option value="1.'.$cli_for->current()->id.'">'.utf8_encode($descricao).'</option>';

	$cli_for->next();
	endwhile;


}

if($define_tp == 1){

	$receberde=clientes_fornecedores::all(array('conditions'=>array('status= ? AND empresas_id= ?','1', $COB_Empresa_Id)));
	$cli_for= new ArrayIterator($receberde);

	while($cli_for->valid()):

	if($cli_for->current()->id == substr($str, 3,10)){$select='selected="selected"';}else{$select="";}

	if($cli_for->current()->nm_cliente ==""){

		if($cli_for->current()->razao_social !=""){

			$descricao=$cli_for->current()->nm_fantasia;

		}else{$descricao=$cli_for->current()->razao_social;}

	}else{$descricao=$cli_for->current()->nm_cliente;}

	echo'<option value="1.'.$cli_for->current()->id.'" '.$select.'>'.utf8_encode($descricao).'</option>';
	$cli_for->next();
	endwhile;

}
/* valor vindo de retornos bancarios*/
if($define_tp == 2){

		echo'<option value="'.$dadosdeposito->clientes_fornecedores_id.'" selected="selected">OUTROS CLIENTES OU FORNECEDORES</option>';

}
/* transferencias entre contas*/
if($define_tp == 3){

		echo'<option value="3.0" selected="selected">TRANSFERÊNCIA ENTRE CONTAS</option>';

}

}else{

	$receberde=clientes_fornecedores::all(array('conditions'=>array('status= ? AND empresas_id= ?','1', $COB_Empresa_Id)));
	$cli_for= new ArrayIterator($receberde);

	echo'<option value="" selected="selected"></option>';


	while($cli_for->valid()):

	if($cli_for->current()->nm_cliente ==""){

		if($cli_for->current()->razao_social !=""){

			$descricao=$cli_for->current()->nm_fantasia;

		}else{$descricao=$cli_for->current()->razao_social;}

	}else{$descricao=$cli_for->current()->nm_cliente;}

	echo'<option value="1.'.$cli_for->current()->id.'" >'.utf8_encode($descricao).'</option>';
	$cli_for->next();
	endwhile;

}
?>

</select> <button class="uk-button" type="button" id="Btn_dep_005"><i class="uk-icon-users" data-uk-tooltip="{pos:'right'}" title="Adcionar Cliente" data-cached-title="Adcionar Cliente" ></i></button>
<button class="uk-button" type="button" id="Btn_dep_006"><i class="uk-icon-cubes" data-uk-tooltip="{pos:'right'}" title="Adcionar Fornecedor" data-cached-title="Adcionar Fornecedor" ></i></button>
</label>

<label>
<span>Plano de conta</span>
<select name="planos_conta_id" id="planos_conta_id" class="w_350">
<?php
  if(isset($dadosdeposito)):

 $Query_planos=planos_contas::find('all',array('conditions'=>array('tipo= ? and empresas_id= ? and categoria= ?','R',$COB_Empresa_Id,0 ),'order' => 'id ASC'));
        $Arr_conta= new ArrayIterator($Query_planos);
        echo'<option value="" selected></option>';
  while($Arr_conta->valid()):

        echo'<optgroup label="'.utf8_encode(strtoupper($Arr_conta->current()->descricao)).'">';

          $Query_planos1=planos_contas::find('all',array('conditions'=>array('tipo= ? and empresas_id= ? and subcategoria= ?','R',$COB_Empresa_Id,$Arr_conta->current()->id ),'order' => 'id ASC'));
          $Arr_planos1= new ArrayIterator($Query_planos1);

          while($Arr_planos1->valid()):

              if($Arr_planos1->current()->id == $dadosdeposito->planos_contas_id){$select='selected="selected"';}else{$select="";}

              echo'<option value="'.$Arr_planos1->current()->id.'" '.$select.'>'.utf8_encode(strtoupper($Arr_planos1->current()->descricao)).'</option>';

              $Arr_planos1->next();
          endwhile;
        echo'</optgroup>';

        $Arr_conta->next();
  endwhile;

  else:

        $Query_planos=planos_contas::find('all',array('conditions'=>array('tipo= ? and empresas_id= ? and categoria= ?','R',$COB_Empresa_Id,0 ),'order' => 'id ASC'));
        $Arr_conta= new ArrayIterator($Query_planos);
        echo'<option value="" selected></option>';
  while($Arr_conta->valid()):

        echo'<optgroup label="'.utf8_encode(strtoupper($Arr_conta->current()->descricao)).'">';

          $Query_planos1=planos_contas::find('all',array('conditions'=>array('tipo= ? and empresas_id= ? and subcategoria= ?','R',$COB_Empresa_Id,$Arr_conta->current()->id ),'order' => 'id ASC'));
          $Arr_planos1= new ArrayIterator($Query_planos1);

          while($Arr_planos1->valid()):

              echo'<option value="'.$Arr_planos1->current()->id.'" >'.utf8_encode(strtoupper($Arr_planos1->current()->descricao)).'</option>';

              $Arr_planos1->next();
          endwhile;
        echo'</optgroup>';

        $Arr_conta->next();
  endwhile;

  endif;
?>
</select>
</label>


<label>
<span>Centro de Custo</span>
<select name="centros_custos_id" class="select w_350"  id="centros_custos_id">
<?php
  if(isset($dadosdeposito)):


      $Query_c_custos=centros_custos::find('all',array('conditions'=>array('empresas_id= ? and categoria= ?',$COB_Empresa_Id,0 ),'order' => 'id ASC'));
      $Arr_c_custos= new ArrayIterator($Query_c_custos);
      echo'<option value="" selected></option>';
  while($Arr_c_custos->valid()):

        echo'<optgroup label="'.utf8_encode(strtoupper($Arr_c_custos->current()->descricao)).'">';

          $Query_c_custos1=centros_custos::find('all',array('conditions'=>array('empresas_id= ? and subcategoria= ?',$COB_Empresa_Id,$Arr_c_custos->current()->id ),'order' => 'id ASC'));
          $Arr_c_custos1= new ArrayIterator($Query_c_custos1);

          while($Arr_c_custos1->valid()):

              if($Arr_c_custos1->current()->id == $dadosdeposito->centros_custos_id){$select='selected="selected"';}else{$select="";}

              echo'<option value="'.$Arr_c_custos1->current()->id.'" '.$select.'>'.utf8_encode(strtoupper($Arr_c_custos1->current()->descricao)).'</option>';

              $Arr_c_custos1->next();
          endwhile;
        echo'</optgroup>';

      $Arr_c_custos->next();
  endwhile;

  else:

      $Query_c_custos=centros_custos::find('all',array('conditions'=>array('empresas_id= ? and categoria= ?',$COB_Empresa_Id,0 ),'order' => 'id ASC'));
      $Arr_c_custos= new ArrayIterator($Query_c_custos);
      echo'<option value="" selected></option>';
  while($Arr_c_custos->valid()):

        echo'<optgroup label="'.utf8_encode(strtoupper($Arr_c_custos->current()->descricao)).'">';

          $Query_c_custos1=centros_custos::find('all',array('conditions'=>array('empresas_id= ? and subcategoria= ?',$COB_Empresa_Id,$Arr_c_custos->current()->id ),'order' => 'id ASC'));
          $Arr_c_custos1= new ArrayIterator($Query_c_custos1);

          while($Arr_c_custos1->valid()):


              echo'<option value="'.$Arr_c_custos1->current()->id.'" >'.utf8_encode(strtoupper($Arr_c_custos1->current()->descricao)).'</option>';

              $Arr_c_custos1->next();
          endwhile;
        echo'</optgroup>';

      $Arr_c_custos->next();
  endwhile;

  endif;
?>

</select>
</label>

<label>
<span>F. de Recebimento</span>
<select name="formas_recebimentos_id" class="select w_350" id="formas_recebimentos_id">


<?php
if(isset($dadosdeposito)):

	$formas=formas_recebimentos::find_by_sql("SELECT
                                                formas_recebimento_sys.descricao,
                                                formas_recebimentos.id
                                             FROM
                                                formas_recebimentos
                                             INNER JOIN formas_recebimento_sys ON formas_recebimento_sys.id =
                                                 formas_recebimentos.formas_recebimento_sys_id
                                             WHERE
                                                  formas_recebimentos.status = '1' AND   formas_recebimentos.empresas_id = '".$COB_Empresa_Id."'");
	$formas_list= new ArrayIterator($formas);


	while($formas_list->valid()):

	if($formas_list->current()->id == $dadosdeposito->formas_recebimentos_id){$select='selected="selected"';}else{$select="";}

	echo'<option value="'.$formas_list->current()->id.'" '.$select.'>'.utf8_encode($formas_list->current()->descricao).'</option>';
	$formas_list->next();
	endwhile;

else:

	$formas=formas_recebimentos::find_by_sql("SELECT
                                                formas_recebimento_sys.descricao,
                                                formas_recebimentos.id
                                             FROM
                                                formas_recebimentos
                                             INNER JOIN formas_recebimento_sys ON formas_recebimento_sys.id =
                                                 formas_recebimentos.formas_recebimento_sys_id
                                             WHERE
                                                  formas_recebimentos.status = '1' AND   formas_recebimentos.empresas_id = '".$COB_Empresa_Id."'");
	$formas_list= new ArrayIterator($formas);


	echo'<option value="" selected="selected"></option>';


	while($formas_list->valid()):
	echo'<option value="'.$formas_list->current()->id.'" >'.utf8_encode($formas_list->current()->descricao).'</option>';
	$formas_list->next();
	endwhile;

endif;
?>
</select>
</label>

<label>
<span>Data</span>
<input name="data"  type="text" class=" w_100 uk-text-center " id="data" value="<?php
if(isset($dadosdeposito)):   $now=new ActiveRecord\DateTime($dadosdeposito->data);echo $now->format('d/m/Y'); else: echo date("d/m/Y"); endif ; ?> " data-uk-datepicker="{format:'DD/MM/YYYY'}" />
</label>

<label>
<span>Numero doc</span>
<input name="numdoc"  type="text" class=" w_100 uk-text-left " id="numdoc" value="<?php
if(isset($dadosdeposito)):   echo $dadosdeposito->numdoc; endif ; ?> " />
</label>

<label>
<span>Valor</span>
<input name="valor"  type="text" class=" w_100 uk-text-center " id="valor"  value="<?php
if(isset($dadosdeposito)):   echo number_format($dadosdeposito->valor,2,",","."); else: echo"0,00";endif ; ?>" />
</label>
</div>
<div id="Detalhes" class="tab-content" style="padding-top: 15px;">
<label>
<span>Historico</span>
<textarea name="historico" class="message" style="height:100px;" id="historico"><?php if(isset($dadosdeposito)):   echo $dadosdeposito->historico; endif ; ?></textarea>
</label>

<label>
<span>Detalhes</span>
<textarea name="detalhes" class="message" style="height:150px;" id="detalhes"><?php if(isset($dadosdeposito)):   echo $dadosdeposito->detalhes; endif ; ?> </textarea>
</label>
</div>
</form>

<div style="height:30px; width:97%; padding-top: 2px; padding-right: 10px; position:absolute; bottom:25px; border-top:1px  solid #ccc; border-bottom: 0; margin:0 auto;" class="uk-gradient-cinza" >
<div style="float: right;">

<div class="uk-button-group group_button"  style="border:0px solid #ccc;float:right; margin-top: 0px; ">

        <a href="JavaScript:void(0);" id="Btn_dep_001" class="uk-button uk-button-primary  uk-button-small " style="border-left:1px solid #ccc;padding-top:2px;line-height: 28px;" ><i class="uk-icon-check uk-text-small " ></i> Confirmar</a>
<?php if(isset($dadosdeposito)):?>
        <a href="JavaScript:void(0);" id="Btn_dep_002" class="uk-button uk-button-danger  uk-button-small " onClick="FcExluir('<?php echo $_GET['id'];?>');" style="border-left:1px solid #ccc;padding-top:2px;line-height: 28px;" ><i class="uk-icon-remove uk-text-small" ></i> Remover</a>
<?php endif;?>
</div>



</div>
</div>
<script src="framework/uikit-2.24.0/js/core/tab.min.js"></script>

<script type="text/javascript">
jQuery(document).ready(function() {

	//abre a tela de preload
	modal.hide();
	jQuery("#Detalhes").hide();

	// mascara para os campos
jQuery('#valor').maskMoney({prefix:'R$ ', thousands:'.', decimal:',', affixesStay: true});
	jQuery("#data").mask("99/99/9999");

});
//FUNÇÃO TABS
jQuery(".uk-tab a").click(function(event) {
        event.preventDefault();
        var tab = jQuery(this).attr("href");
        jQuery(".tab-content").not(tab).css("display", "none");
        jQuery(tab).fadeIn();
    });

//FUNÇÃO DE ENVIO DO FORMULARIO
jQuery(function() {

	jQuery("#Btn_dep_001").click(function(event) {

		var historico=jQuery("#historico").val();

		if(historico==""){
					UIkit.modal.alert('Digite o Histórico do Recebimento!');
					jQuery("#Detalhes").fadeIn();
					jQuery("#Geral").fadeOut();
					 return false;
			}
		if(valor==""){
					UIkit.modal.alert('Digite o Valor do Recebimento!');
					 return false;
			}

					// mensagen de carregamento
					jQuery("#msg_loading").html(" Aguarde ");
					//abre a tela de preload
					modal.show();
					//desabilita o envento padrao do formulario
					event.preventDefault();

						jQuery.ajax({
							url: "assets/financeiro/caixa/Controller_depositar.php",
							type: "post",
							data:'action=new_up&'+jQuery("#FrmDeposito").serialize(),
							success: function(resultado) {

								var text = '{"'+resultado+'"}';
		                        var obj = JSON.parse(text);

		                        /* se o callback for 1 indica que  houve erro ai mostramos o resultado da execução das querys*/
		                        if(obj.callback == 1){

		                            UIkit.modal.alert(""+obj.msg+"");
		                            modal.hide();

		                        /* se for = 0 indica que não houve erro ai retornamo o erro na tela do usuario*/
		                        }else{

		                            // mensagen de carregamento
									jQuery("#msg_loading").html(" Atualizando Grid...");

		                            // recarrega a pagina
									LoadContent('assets/financeiro/caixa/Grid_lancamentos.php?conta_id='+jQuery("#contas_bancarias_id").val()+'&periodo=0','gridlancamentos');
		                        }
							},
							error:function (){
								UIkit.modal.alert("Caminho Invalido!");
								}
						});
	});

});

//pega o id da linha para remover do procedimento
function FcExluir(caixa_id){

// mensagen de carregamento
jQuery("#msg_loading").html(" Removendo ");

//abre a tela de preload
modal.show();

//faz a requisição
jQuery.post("assets/financeiro/caixa/Controller_depositar.php",
		  {action:'remove',id:caixa_id},
           // Carregamos o resultado acima
			function(resultado){

							var text = '{"'+resultado+'"}';
		                    var obj = JSON.parse(text);

		                    /* se o callback for 1 indica que  houve erro ai mostramos o resultado da execução das querys*/
		                    if(obj.callback == 1){

		                        UIkit.modal.alert(""+obj.msg+"");
		                        modal.hide();

		                        /* se for = 0 indica que não houve erro ai retornamo o erro na tela do usuario*/
		                    }else{

								// mensagen de carregamento
								jQuery("#msg_loading").html(" Atualizando Grid...");

								// recarrega a pagina
								LoadContent('assets/financeiro/caixa/Grid_lancamentos.php?conta_id='+jQuery("#contas_bancarias_id").val()+'&periodo=0','gridlancamentos');
		                    }
					});
}
jQuery("#Btn_dep_005").click(function(){
    New_window('user','900','500','Cadastro de Clientes','assets/cliente/Frm_cliente.php',false,false,'Aguarde ...');/* abre a janela atualizada*/
});

jQuery("#Btn_dep_006").click(function(){
    New_window('user','900','500','Cadastro de Fornecedor','assets/fornecedor/Frm_fornecedor.php',false,false,'Aguarde ...');/* abre a janela atualizada*/
});

</script>
