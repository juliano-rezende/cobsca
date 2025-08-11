<?php require_once"../../../sessao.php"; ?>
<div class="tabs-spacer" style="display:none;">
<?php
include("../../../conexao.php");
$cfg->set_model_directory('../../../models/');


if(isset($_GET['cpagar_id'])){

$cpagar_id = $_GET['cpagar_id'];

$Query_cpagar=contas_pagar::find_by_sql("SELECT contas_pagar.*,
                                                clientes_fornecedores.tipo,
                                                clientes_fornecedores.nm_fantasia,
                                                clientes_fornecedores.nm_cliente
                                         FROM contas_pagar
                                         LEFT JOIN clientes_fornecedores ON clientes_fornecedores.id= contas_pagar.clientes_fornecedores_id
                                         WHERE contas_pagar.id ='".$cpagar_id."'");

// define o stylo do campo data de vencimento
$dtv=new ActiveRecord\DateTime($Query_cpagar[0]->dt_vencimento);
}
?>
</div>
<form id="FrmLancCx" action="#" style="padding-top:10px;" class="uk-form">
<div id="Geral_cx" style="float:left; width:420px; border-right:0;">
<fieldset style="margin-top:0; border:0;">

<label>
<span>Conta Debito</span>
<select name="contas_bancarias_id_cx" id="contas_bancarias_id_cx" class="w_400">
<?php
if(isset($Query_cpagar)):
	 $Query_contas=contas_bancarias::find('all',array('conditions'=>array('empresas_id= ?',$COB_Empresa_Id )));
	 $Arr_conta= new ArrayIterator($Query_contas);
	 while($Arr_conta->valid()):
            if($Arr_conta->current()->id == $Query_cpagar[0]->contas_bancarias_id){$select='selected="selected"';}else{$select="";}
            echo'<option value="'.$Arr_conta->current()->id.'" '.$select.'>'.utf8_encode(strtoupper($Arr_conta->current()->nm_conta)).'</option>';
	  $Arr_conta->next();
	  endwhile;
else:
    $Query_contas=contas_bancarias::find('all',array('conditions'=>array('empresas_id= ?',$COB_Empresa_Id )));
    $Arr_conta= new ArrayIterator($Query_contas);
    echo'<option value="" selected></option>';
	while($Arr_conta->valid()):
	        echo'<option value="'.$Arr_conta->current()->id.'" >'.utf8_encode(strtoupper($Arr_conta->current()->nm_conta)).'</option>';
	        $Arr_conta->next();
	endwhile;
endif;
?>
</select>
</label>

<label>
<span>Pagar Para</span>
<input name="keyword" autocomplete="off" type="text" class=" left w_400" id="keyword" value=" <?php if(isset($Query_cpagar)){if($Query_cpagar[0]->tipo == 1){  echo utf8_encode($Query_cpagar[0]->nm_cliente);}else{ echo utf8_encode($Query_cpagar[0]->nm_fantasia);}}
?>" />
<input name="clientes_fornecedores_id_cx" id="clientes_fornecedores_id_cx" type="text" class="uk-hidden w_50"  value="<?php  if(isset($Query_cpagar)){echo $Query_cpagar[0]->clientes_fornecedores_id ; }?>"  />
<input name="cpagar_id_cx" id="cpagar_id_cx" type="text" class="uk-hidden w_50 " value="<?php if(isset($Query_cpagar)){ echo $cpagar_id; }?>" readonly="readonly" />
</label>

<label>
<span>Plano de conta</span>
<select name="planos_conta_id_cx" id="planos_conta_id_cx" class="w_400">
<?php
  if(isset($Query_cpagar)):

 $Query_planos=planos_contas::find('all',array('conditions'=>array('tipo= ? and empresas_id= ? and categoria= ?','D',$COB_Empresa_Id,0 ),'order' => 'id ASC'));
        $Arr_conta= new ArrayIterator($Query_planos);
        echo'<option value="" selected></option>';
  while($Arr_conta->valid()):
        echo'<optgroup label="'.utf8_encode(strtoupper($Arr_conta->current()->descricao)).'">';
          $Query_planos1=planos_contas::find('all',array('conditions'=>array('tipo= ? and empresas_id= ? and subcategoria= ?','D',$COB_Empresa_Id,$Arr_conta->current()->id ),'order' => 'id ASC'));
          $Arr_planos1= new ArrayIterator($Query_planos1);
          while($Arr_planos1->valid()):
              if($Arr_planos1->current()->id == $Query_cpagar[0]->planos_contas_id){$select='selected="selected"';}else{$select="";}
              echo'<option value="'.$Arr_planos1->current()->id.'" '.$select.'>'.utf8_encode(strtoupper($Arr_planos1->current()->descricao)).'</option>';
              $Arr_planos1->next();
          endwhile;
        echo'</optgroup>';
        $Arr_conta->next();
  endwhile;
  else:
        $Query_planos=planos_contas::find('all',array('conditions'=>array('tipo= ? and empresas_id= ? and categoria= ?','D',$COB_Empresa_Id,0 ),'order' => 'id ASC'));
        $Arr_conta= new ArrayIterator($Query_planos);
        echo'<option value="" selected></option>';
  while($Arr_conta->valid()):
        echo'<optgroup label="'.utf8_encode(strtoupper($Arr_conta->current()->descricao)).'">';
          $Query_planos1=planos_contas::find('all',array('conditions'=>array('tipo= ? and empresas_id= ? and subcategoria= ?','D',$COB_Empresa_Id,$Arr_conta->current()->id ),'order' => 'id ASC'));
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
<select name="centros_custo_id_cx" id="centros_custo_id_cx" class="W_400">
<?php
if(isset($Query_cpagar)):

    $Query_c_custos=centros_custos::find('all',array('conditions'=>array('empresas_id= ? and categoria= ?',$COB_Empresa_Id,0 ),'order' => 'id ASC'));
    $Arr_c_custos= new ArrayIterator($Query_c_custos);
    echo'<option value="" selected></option>';
  	while($Arr_c_custos->valid()):
        echo'<optgroup label="'.utf8_encode(strtoupper($Arr_c_custos->current()->descricao)).'">';
     	$Query_c_custos1=centros_custos::find('all',array('conditions'=>array('empresas_id= ? and subcategoria= ?',$COB_Empresa_Id,$Arr_c_custos->current()->id ),'order' => 'id ASC'));
     	$Arr_c_custos1= new ArrayIterator($Query_c_custos1);
        while($Arr_c_custos1->valid()):
            if($Arr_c_custos1->current()->id == $Query_cpagar[0]->centros_custos_id){$select='selected="selected"';}else{$select="";}
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
<span>Forma de Pgto</span>
<select name="formas_pagamentos_id_cx" id="formas_pagamentos_id_cx" class="w_400">
<option value="0" selected></option>
<?php
  if(isset($Query_cpagar)):
  $Query_forma_pgto=formas_pagamentos::find_by_sql("SELECT
                                                   formas_pagamentos.id as id,formas_pagamento_sys.descricao as descricao
                                                  FROM
                                                   formas_pagamentos LEFT JOIN
                                                   formas_pagamento_sys ON formas_pagamentos.formas_pagamento_sys_id =
                                                     formas_pagamento_sys.id
                                                  WHERE
                                                   formas_pagamentos.empresas_id = '".$COB_Empresa_Id."'");
  $Arr_f_pgto= new ArrayIterator($Query_forma_pgto);
  while($Arr_f_pgto->valid()):
        if($Arr_f_pgto->current()->id == $Query_cpagar[0]->formas_pagamentos_id){$select='selected="selected"';}else{$select="";}
        echo'<option value="'.$Arr_f_pgto->current()->id.'" '.$select.'>'.strtoupper($Arr_f_pgto->current()->descricao).'</option>';
  $Arr_f_pgto->next();
  endwhile;
  else:
  $Query_forma_pgto=formas_pagamentos::find_by_sql("SELECT
                                         formas_pagamentos.id as id,formas_recebimento_sys.descricao as descricao
                                        FROM
                                         formas_pagamentos LEFT JOIN
                                         formas_recebimento_sys ON formas_pagamentos.formas_pagamento_sys_id =
                                           formas_recebimento_sys.id
                                        WHERE
                                         formas_pagamentos.empresas_id = '".$COB_Empresa_Id."'");
            $Arr_f_pgto= new ArrayIterator($Query_forma_pgto);
  while($Arr_f_pgto->valid()):
            echo'<option value="'.$Arr_f_pgto->current()->id.'" >'.strtoupper($Arr_f_pgto->current()->descricao).'</option>';
            $Arr_f_pgto->next();
  endwhile;
  endif;
?>
</select>
</label>

<label>
<span>Linha Dig</span>
<input name="linha_dig_cx" id="linha_dig_cx" type="text" class="uk-text-center w_400" value="<?php if(isset($Query_cpagar)){ echo $Query_cpagar[0]->linha_dig; }?>" placeholder="99999.99999.99999.999999 99999.999999 9 99999999999999"/>
</label>

<label>
<span>Historico</span>
<input name="historico_cx" autocomplete="off" type="text" class=" left w_400" id="historico_cx" value="<?php if(isset($Query_cpagar)){ echo $Query_cpagar[0]->historico; }?>"  />
</label>

<label>
<span>Detalhes</span>
<textarea name="obs_cx" class="w_400" style="height:120px;" id="obs_cx"><?php if(isset($Query_cpagar)){ echo $Query_cpagar[0]->obs; }?></textarea>
</label>

</fieldset>

</div>
<div id="Detalhes_cx" style="float: right; width:250px; height:450px; border-left:1px solid #F5F5F5;">
<fieldset style="width:250px; margin-top:0; border:0; padding:0; margin:0;">

<div style="border-bottom: 1px solid #ccc; padding-top:5px;padding-bottom:5px;">

	<label>
	<span>Num Doc</span>
	<input name="num_doc_cx"  id="num_doc_cx" type="text" class="uk-text-center w_100" value="<?php  if(isset($Query_cpagar)){ echo $Query_cpagar[0]->num_doc; }  ?>"  readonly="readonly"/>
	</label>

	 <label>
	    <span>Parcela</span>
	    <input type="text" id="parcela_cx"  name="parcela_cx"  value="<?php  if(isset($Query_cpagar)){ echo $Query_cpagar[0]->n_parcela; }  ?>""  class="uk-text-center w_100 " readonly="readonly">
	    </label>
	<label>

<label>
<span>Data Vencimento</span>
<input name="dt_vencimento_cx" id="dt_vencimento_cx" type="text" class="uk-text-center w_100" placeholder="00/00/0000" value="<?php
     if(isset($Query_cpagar)){
             $now=new ActiveRecord\DateTime($Query_cpagar[0]->dt_vencimento);echo $now->format('d/m/Y');}
     ?>" data-uk-datepicker="{format:'DD/MM/YYYY'}" />
</label>
	<label>
	<span>Data Pagamento</span>
	<input name="dt_pagamento_cx" id="dt_pagamento_cx" type="text" class="uk-text-center w_100" placeholder="00/00/0000"  data-uk-datepicker="{format:'DD/MM/YYYY'}" />
	</label>

</div>

<div style="border-bottom: 1px solid #ccc; padding-top:5px;padding-bottom:5px; margin-bottom: 5px;">
<label>
<span>Valor</span>
<input name="vlr_nominal_cx" id="vlr_nominal_cx" type="text" class="uk-text-center w_100 " placeholder="0,00" value="<?php  if(isset($Query_cpagar)){echo number_format($Query_cpagar[0]->vlr_nominal,2,",","."); }?>"  />
</label>

<label>
<span>Multa</span>
<input name="multa_cx" type="text" class="uk-text-center w_100 " id="multa_cx" autocomplete="off"/>
</label>

<label>
<span>Juros</span>
<input name="juros_cx" type="text" class="uk-text-center w_100" id="juros_cx" autocomplete="off"/>
</label>

<label>
<span>Descontos</span>
<input name="descontos_cx" id="descontos_cx" type="text" class="uk-text-center w_100" autocomplete="off" />
</label>
</div>

<label>
<span><strong>Valor Total</strong></span>
<input name="vlr_total_cx" type="text" class="uk-text-center w_100 uk-text-danger" id="vlr_total_cx"  value="<?php  if(isset($Query_cpagar)){echo number_format($Query_cpagar[0]->vlr_nominal,2,",","."); }?>" />
</label>


</fieldset>
</div>
</form>

<div style="height:28px; padding-top:6px; padding-right: 5px;  width:844px; position:absolute; bottom:25px; text-align:right;border:0; border-top:1px solid #ccc;margin:auto;" class="uk-gradient-cinza" >

<div style="float: right;">
	<a href="JavaScript:void(0);" id="Btn_lc_00" class="uk-button uk-button-primary uk-button-small" ><i class="uk-icon-check" ></i> Confirmar Pagamento</a>
	<a href="JavaScript:void(0);" id="Btn_lc_01" class="uk-button uk-button-danger  uk-button-small" ><i class="uk-icon-remove" ></i> Cancelar</a>
</div>

</div>

<script type="text/javascript">

jQuery(document).ready(function() {
	//abre a tela de preload
	modal.hide();
	// mascara para os campos
	jQuery("#descontos_cx,#multa_cx,#juros_cx").val('0,00');
	jQuery("#vlr_nominal_cx,#vlr_total_cx,#descontos_cx,#multa_cx,#juros_cx").maskMoney({showSymbol:true, symbol:"", decimal:",", thousands:"."});
	jQuery("#dt_vencimento_cx,#dt_pagamento_cx").mask("99/99/9999");

});

// BOTÃO DE CANCELAR AÇÃO
jQuery("#Btn_lc_01").click(function (){

var Window_alvo=$(this).closest('.Window').attr('id');
	jQuery("#"+Window_alvo+"").fadeOut(400, function(){
				jQuery("#"+Window_alvo+"").remove();
					});
});

//ENVIA OS DADOS PARA FORMATÇÃO DENTRO DO SERVIDOR E NÃO NA MAQUINA DO USUARIO
jQuery("#multa_cx,#juros_cx,#descontos_cx").focusout(function (){

var cp1 = jQuery("#vlr-nominal_cx").val();/*valor nominal*/
var cp2 = jQuery("#multa_cx").val();/*multa*/
var cp3 = jQuery("#juros_cx").val();/*juros*/
var cp4 = jQuery("#descontos_cx").val();/*descontos*/


     jQuery.ajax({
              url: "assets/financeiro/cpagar/Controller_tools.php",
              type: "post",
              data:'action=vlr_total&'+jQuery("#FrmLancCx").serialize(),
              success: function(resultado) {

                            var text = '{"'+resultado+'"}';
                            var obj = JSON.parse(text);

                            /* se o callback for 1 indica que  houve erro ai mostramos o resultado da execução das querys*/
                            if(obj.callback == 1){

                              jQuery("#vlr_total_cx").val(''+obj.vlr+'');

                            /* se for = 0 indica que não houve erro ai retornamo o erro na tela do usuario*/
                            }else{

                                UIkit.notify(''+obj.vlr+'', {timeout: 1000,status:''+obj.status+''});
                            }
              },
              error:function (){
                UIkit.modal.alert("Caminho Invalido!");
                }
            });
});

//FUNÇÃO DE ENVIO DO FORMULARIO ADIÇÃO
jQuery(function() {

  jQuery("#Btn_lc_00").click(function(event) {

    if(jQuery("#keyword_cx").val() == ""){
          UIkit.notify('Fornecedor ou cliente não localizado', {timeout: 1000,status:'danger'});
          jQuery("#keyword_cx").focus();
          return false;
      }
      else  if(jQuery("#contas_bancarias_id_cx").val() == ""){
          UIkit.notify('Conta debito não definida!', {timeout: 1000,status:'danger'});
          jQuery("#contas_bancarias_id_cx").focus();
          return false;
      }
      else  if(jQuery("#planos_conta_id_cx").val() == ""){
          UIkit.notify('Plano de conta não definido!', {timeout: 1000,status:'danger'});
          jQuery("#planos_conta_id_cx").focus();
          return false;
      }
      else  if(jQuery("#centros_custo_id_cx").val() == ""){
          UIkit.notify('Centro de custo não definido!', {timeout: 1000,status:'danger'});
          jQuery("#centros_custo_id_cx").focus();
          return false;
      }
      else  if(jQuery("#formas_pagamentos_id_cx").val() == ""){
          UIkit.notify('Forma de pagamento não definida!', {timeout: 1000,status:'danger'});
          jQuery("#formas_pagamentos_id_cx").focus();
          return false;
      }
      else  if(jQuery("#num_doc_cx").val() == ""){
          UIkit.notify('Favor informar o numero do documento!', {timeout: 1000,status:'danger'});
          jQuery("#num_doc_cx").focus();
          return false;
      }
      else  if(jQuery("#vlr_nominal_cx").val() == ""){
          UIkit.notify('Valor do documento incorreto!', {timeout: 1000,status:'danger'});
          jQuery("#vlr_nominal_cx").focus();
          return false;
      }
      else  if(jQuery("#dt_emissao_do_cx").val() == ""){
          UIkit.notify('Favor informar a data de emissão do documento!', {timeout: 1000,status:'danger'});
          jQuery("#dt_emissao_doc_cx").focus();
          return false;
      }
      else  if(jQuery("#dt_vencimento_cx").val() == ""){
          UIkit.notify('Favor informar a data de vencimento do documento!', {timeout: 1000,status:'danger'});
          jQuery("#dt_vencimento_cx").focus();
          return false;
      }

          // mensagen de carregamento
          jQuery("#msg_loading").html(" Aguarde... ");
          //abre a tela de preload
          modal.show();
          //desabilita o envento padrao do formulario
          event.preventDefault();

            jQuery.ajax({
              url: "assets/financeiro/cpagar/Controller_cpagar.php",
              type: "post",
              data:'action=pay&'+jQuery("#FrmLancCx").serialize(),
              success: function(resultado) {


                            var text = '{"'+resultado+'"}';

                            var obj = JSON.parse(text);

                            /* se o callback for 1 indica que  houve erro ai mostramos o resultado da execução das querys*/
                            if(obj.callback == 1){

                              UIkit.notify(''+obj.msg+'', {timeout: 1000,status:''+obj.status+''});
                              modal.hide();

                            /* se for = 0 indica que não houve erro ai retornamo o erro na tela do usuario*/
                            }else{

                                UIkit.notify(''+obj.msg+'', {timeout: 1000,status:''+obj.status+''});
                                // mensagen de carregamento
                                jQuery("#msg_loading").html(" Atualizando Grid...");

                                // recarrega a pagina
                                LoadContent('assets/financeiro/cpagar/Grid_c_pagar.php','GridContasPagar');
                            }
              },
              error:function (){
                UIkit.modal.alert("Caminho Invalido!");
                }
            });
          });
});
</script>