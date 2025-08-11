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
<ul class="uk-tab uk-gradient-cinza" data-uk-tab style="height:35px;">
    <li><a href="#Geral" >Geral</a></li>
    <li><a href="#Detalhes">Informações Adicionais</a></li>
</ul>
<form id="Frmcpagar" action="#" style="padding-top:10px;" class="uk-form">
<div id="Geral" class="tab-content">
<fieldset style=" margin-top:0; border:0;">

<label>
<span>Historico</span>
<input name="historico" autocomplete="off" type="text" class=" left w_400" id="historico" value="<?php if(isset($Query_cpagar)){ echo $Query_cpagar[0]->historico; }?>"  />
</label>

<label>
<span>Pagar Para</span>

<input onkeyup="AutoCompleteCF();" placeholder="Digite o nome para pesquisa" name="keyword" autocomplete="off" type="text" class=" left w_400" id="keyword" value="
<?php
if(isset($Query_cpagar)){

if($Query_cpagar[0]->nm_cliente != ""){$nm_cliente=$Query_cpagar[0]->nm_cliente;}
  else{
    if($Query_cpagar[0]->nm_fantasia != ""){$nm_cliente=$Query_cpagar[0]->nm_fantasia;}
    else{$nm_cliente=$Query_cpagar[0]->razao_social;}
  }

  echo $nm_cliente;

}

?>"  />
<button class="uk-button" type="button" id="Btn_Frmcpagar_05"><i class="uk-icon-users" data-uk-tooltip="{pos:'right'}" title="Adcionar Cliente" data-cached-title="Adcionar Cliente" ></i></button>
<button class="uk-button" type="button" id="Btn_Frmcpagar_06"><i class="uk-icon-cubes" data-uk-tooltip="{pos:'right'}" title="Adcionar Fornecedor" data-cached-title="Adcionar Fornecedor" ></i></button>

<input name="clientes_fornecedores_id" id="clientes_fornecedores_id" type="text" class="uk-hidden w_50"  value="<?php  if(isset($Query_cpagar)){echo $Query_cpagar[0]->clientes_fornecedores_id ; }?>"  />
</label>

<table id="list_clientes_fornecedores_id" class="uk-table uk-table-hover" style=" border:1px solid #ccc; background-color:#FFF;width:400px; position:absolute; margin:-2px 120px; z-index:1000;""  >
  <tbody id="list_id">
    <tr><td>teste</td></tr>
  </tbody>
</table>


<label>
<span>Conta Debito</span>
<select name="contas_bancarias_id" id="contas_bancarias_id" class="w_400" >
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
<span>Plano de conta</span>
<select name="planos_conta_id" id="planos_conta_id" class="w_400">
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
<!-- <button class="uk-button" type="button" id="Btn_Frmcpagar_07"><i class="uk-icon-list-ul" data-uk-tooltip="{pos:'right'}" title="Adcionar Plano de Conta" data-cached-title="Adcionar Plano de Conta" ></i></button>-->


<label>
<span>Centro de Custo</span>
<select name="centros_custo_id" id="centros_custo_id" class="W_400">
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
<!-- <button class="uk-button" type="button" id="Btn_Frmcpagar_08"><i class="uk-icon-list-ul" data-uk-tooltip="{pos:'right'}" title="Adcionar Centro de Custo" data-cached-title="Adcionar Centro de Custo" ></i></button> -->
</label>


<label>
<span>Forma de Pgto</span>
<select name="formas_pagamentos_id" id="formas_pagamentos_id" class="w_400">
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
<span>Tipo</span>
<select name="tp_doc" id="tp_doc" class="w_120">
<option value="" ></option>
<option value="NF" selected>Nota fiscal</option>
<option value="TB" selected>Titulo Bancario</option>
<option value="CN" selected>Carnê</option>
<option value="GU" selected>Guia</option>
<option value="NF" selected>Nota fiscal</option>
<option value="HT" selected>Holerite</option>
<option value="RC" selected>Recibo</option>
</select>
</label>

<label>
<span>Num Doc</span>
<input name="num_doc"  id="num_doc" type="text" class="uk-text-center w_100" value="<?php  if(isset($Query_cpagar)){ echo $Query_cpagar[0]->num_doc; }  ?>"  />
</label>


<?php if(!isset($Query_cpagar)):?>
    <label >
    <span>Qte Parcelas ?</span>
    <input type="number" id="qte_parcelas"  name="qte_parcelas" min="1" max="24" value="1"  class="uk-text-center w_100 ">
    </label>
<?php else:?>
    <label >
    <span>Parcela</span>
    <input type="text" id="parcela"  name="parcela"  value="<?php  if(isset($Query_cpagar)){ echo $Query_cpagar[0]->n_parcela; }  ?>""  class="uk-text-center w_100 ">
    </label>
<?php endif;?>


<label>
<span>Valor</span>
<input name="vlr_nominal" id="vlr_nominal" type="text" class="uk-text-center w_100 " placeholder="0,00" value="<?php  if(isset($Query_cpagar)){echo number_format($Query_cpagar[0]->vlr_nominal,2,",","."); }?>"  />
</label>

<label>
<span>Data Emissão</span>
<input name="dt_emissao_doc" id="dt_emissao_doc" type="text" class="uk-text-center w_100 " placeholder="00/00/0000" value="<?php
     if(isset($Query_cpagar)){
             $now=new ActiveRecord\DateTime($Query_cpagar[0]->dt_emissao_doc);echo $now->format('d/m/Y');
       }
     ?>"  data-uk-datepicker="{format:'DD/MM/YYYY'}"/>
</label>


<label>
<span>Data Vencimento</span>
<input name="dt_vencimento" id="dt_vencimento" type="text" class="uk-text-center w_100" placeholder="00/00/0000" value="<?php
     if(isset($Query_cpagar)){
             $now=new ActiveRecord\DateTime($Query_cpagar[0]->dt_vencimento);echo $now->format('d/m/Y');

       }
     ?>" data-uk-datepicker="{format:'DD/MM/YYYY'}" /> <div class="uk-badge">Em caso de parcelamento inserir a data do 1º vencimento.</div>
</label>

</fieldset>
</div>

<div id="Detalhes" class="tab-content">
<fieldset style=" margin-top:0; border:0;">

<label>
<span>Nun Aut</span>
<input name="cpagar_id" id="cpagar_id" type="text" class="uk-text-center w_150 " value="<?php if(isset($Query_cpagar)){ echo $cpagar_id; }?>" readonly="readonly" />
</label>
<!-- -->

<label>
<span>Linha Dig</span>
<input name="linha_dig" id="linha_dig" type="text" class="uk-text-center w_400 " value="<?php if(isset($Query_cpagar)){ echo $Query_cpagar[0]->linha_dig; }?>" placeholder="99999.99999.99999.999999 99999.999999 9 99999999999999"   />
</label>

<label>
<span>Observações</span>
<textarea name="obs" class="w_400" style="height:300px;" id="obs"><?php if(isset($Query_cpagar)){ echo $Query_cpagar[0]->obs; }?></textarea>
</label>
</fieldset>
</div>
</form>

<div style="height:28px; padding-top:6px; padding-right: 5px;  width:694px; position:absolute; bottom:25px; text-align:right;border:0; border-top:1px solid #ccc;margin:auto;" class="uk-gradient-cinza" >

<div style="float: right;">


<?php if(isset($Query_cpagar)):?>

  <a href="JavaScript:void(0);" id="Btn_Frmcpagar_00" class="uk-button uk-button-primary  uk-button-small" >
    <i class="uk-icon-check" > </i> Salvar Alterações
  </a>

  <a href="JavaScript:void(0);" id="Btn_Frmcpagar_01" class="uk-button uk-button-success  uk-button-small" >
    <i class="uk-icon-check" ></i> Lançar Pagamento
  </a>

  <a href="JavaScript:void(0);" id="Btn_Frmcpagar_02" class="uk-button uk-button-danger  uk-button-small" >
    <i class="uk-icon-remove" ></i> Excluir Lançamento
  </a>
<?php else: ?>

  <a href="JavaScript:void(0);" id="Btn_Frmcpagar_03" class="uk-button uk-button-primary  uk-button-small" >
    <i class="uk-icon-check" > </i> Gravar Nova
  </a>

<?php endif;?>

</div>

</div>

<script src="framework/uikit-2.21.0/js/core/tab.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function() {

  /*abre a tela de preload*/
  modal.hide();
  jQuery("#Detalhes,#qte_p,#list_clientes_fornecedores_id").hide();

  /* mascara para os campos */
  jQuery("#vlr_nominal").maskMoney({showSymbol:true, symbol:"", decimal:",", thousands:"."});
  jQuery("#dt_emissao,#dt_vencimento").mask("99/99/9999");
  jQuery("#linha_dig").attr("maxlength","54").mask("99999.99999.99999.999999 99999.999999 9 99999999999999").focus().posicaoCursor(0);

});


/* FUNÇÃO TABS */
jQuery(".uk-tab a").click(function(event) {
        event.preventDefault();
        var tab = $(this).attr("href");
        jQuery(".tab-content").not(tab).css("display", "none");
        jQuery(tab).fadeIn();
});


/* autocomplet : this function will be executed every time we change the text*/
function AutoCompleteCF() {

  var min_length = 1; /* min caracters to display the autocomplete*/
  var keyword = jQuery('#keyword').val();

  if(keyword!=""){
    if (keyword.length >= min_length) {
      $.ajax({
        url: 'assets/financeiro/cpagar/Controller_autocomplete.php',
        type: 'POST',
        data: {keyword:keyword},
        success:function(data){
                    jQuery('#list_clientes_fornecedores_id').show();
                    jQuery('#list_id').html(data);
                    jQuery('#list_id').show();
                    }
        });
    } else {
        jQuery('#list_clientes_fornecedores_id').hide();
        jQuery('#list_id').hide();
        }
  }else{
    jQuery('#list_clientes_fornecedores_id').hide();
    jQuery('#list_id').hide();
    }
}

/* set_item : this function will be executed when we select an item */
function set_item(nm_fantasia,clientes_fornecedores_id) {
  /* change input value*/
  jQuery('#clientes_fornecedores_id').val(clientes_fornecedores_id);
  jQuery('#keyword').val(nm_fantasia);
  jQuery('#contas_bancarias_id').focus();
  /* hide proposition list*/
  jQuery('#list_clientes_fornecedores_id').hide();
}

/* exibe o campo para criar parcelamento*/
jQuery("#parcelar").change(function(){

  if(jQuery(this).val()==0){jQuery("#qte_p").hide();}else{jQuery("#qte_p").show();}

});


//FUNÇÃO DE ENVIO DO FORMULARIO ADIÇÃO
jQuery(function() {

  jQuery("#Btn_Frmcpagar_03").click(function(event) {

    if(jQuery("#keyword").val() == ""){
          UIkit.notify('Fornecedor ou cliente não localizado', {timeout: 1000,status:'danger'});
          jQuery("#keyword").focus();
          return false;
      }
      else  if(jQuery("#contas_bancarias_id").val() == ""){
          UIkit.notify('Conta debito não definida!', {timeout: 1000,status:'danger'});
          jQuery("#contas_bancarias_id").focus();
          return false;
      }
      else  if(jQuery("#planos_conta_id").val() == ""){
          UIkit.notify('Plano de conta não definido!', {timeout: 1000,status:'danger'});
          jQuery("#planos_conta_id").focus();
          return false;
      }
      else  if(jQuery("#centros_custo_id").val() == ""){
          UIkit.notify('Centro de custo não definido!', {timeout: 1000,status:'danger'});
          jQuery("#centros_custo_id").focus();
          return false;
      }
      else  if(jQuery("#formas_pagamentos_id").val() == ""){
          UIkit.notify('Forma de pagamento não definida!', {timeout: 1000,status:'danger'});
          jQuery("#formas_pagamentos_id").focus();
          return false;
      }
      else  if(jQuery("#num_doc").val() == ""){
          UIkit.notify('Favor informar o numero do documento!', {timeout: 1000,status:'danger'});
          jQuery("#num_doc").focus();
          return false;
      }
      else  if(jQuery("#vlr_nominal").val() == ""){
          UIkit.notify('Valor do documento incorreto!', {timeout: 1000,status:'danger'});
          jQuery("#vlr_nominal").focus();
          return false;
      }
      else  if(jQuery("#dt_emissao_doc").val() == ""){
          UIkit.notify('Favor informar a data de emissão do documento!', {timeout: 1000,status:'danger'});
          jQuery("#dt_emissao_doc").focus();
          return false;
      }
      else  if(jQuery("#dt_vencimento").val() == ""){
          UIkit.notify('Favor informar a data de vencimento do documento!', {timeout: 1000,status:'danger'});
          jQuery("#dt_vencimento").focus();
          return false;
      }
      else  if(jQuery("#historico").val() == ""){
          UIkit.notify('Favor informar o historico do documento!', {timeout: 1000,status:'danger'});
          jQuery("#historico").focus();
          jQuery("#Detalhes").fadeIn();
          jQuery("#Geral").fadeOut();
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
              data:'action=new&'+jQuery("#Frmcpagar").serialize(),
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

//FUNÇÃO DE ENVIO DO FORMULARIO edição
jQuery(function() {

  jQuery("#Btn_Frmcpagar_00").click(function(event) {

    if(jQuery("#keyword").val() == ""){
          UIkit.notify('Fornecedor ou cliente não localizado', {timeout: 1000,status:'danger'});
          jQuery("#keyword").focus();
          return false;
      }
      else  if(jQuery("#contas_bancarias_id").val() == ""){
          UIkit.notify('Conta debito não definida!', {timeout: 1000,status:'danger'});
          jQuery("#contas_bancarias_id").focus();
          return false;
      }
      else  if(jQuery("#planos_conta_id").val() == ""){
          UIkit.notify('Plano de conta não definido!', {timeout: 1000,status:'danger'});
          jQuery("#planos_conta_id").focus();
          return false;
      }
      else  if(jQuery("#centros_custo_id").val() == ""){
          UIkit.notify('Centro de custo não definido!', {timeout: 1000,status:'danger'});
          jQuery("#centros_custo_id").focus();
          return false;
      }
      else  if(jQuery("#formas_pagamentos_id").val() == ""){
          UIkit.notify('Forma de pagamento não definida!', {timeout: 1000,status:'danger'});
          jQuery("#formas_pagamentos_id").focus();
          return false;
      }
      else  if(jQuery("#num_doc").val() == ""){
          UIkit.notify('Favor informar o numero do documento!', {timeout: 1000,status:'danger'});
          jQuery("#num_doc").focus();
          return false;
      }
      else  if(jQuery("#vlr_nominal").val() == ""){
          UIkit.notify('Valor do documento incorreto!', {timeout: 1000,status:'danger'});
          jQuery("#vlr_nominal").focus();
          return false;
      }
      else  if(jQuery("#dt_emissao_doc").val() == ""){
          UIkit.notify('Favor informar a data de emissão do documento!', {timeout: 1000,status:'danger'});
          jQuery("#dt_emissao_doc").focus();
          return false;
      }
      else  if(jQuery("#dt_vencimento").val() == ""){
          UIkit.notify('Favor informar a data de vencimento do documento!', {timeout: 1000,status:'danger'});
          jQuery("#dt_vencimento").focus();
          return false;
      }
      else  if(jQuery("#historico").val() == ""){
          UIkit.notify('Favor informar o historico do documento!', {timeout: 1000,status:'danger'});
          jQuery("#historico").focus();
          jQuery("#Detalhes").fadeIn();
          jQuery("#Geral").fadeOut();
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
              data:'action=edit&'+jQuery("#Frmcpagar").serialize(),
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

//FUNÇÃO DE ENVIO DO FORMULARIO PARA REMOVER REGISTRO
jQuery(function() {

  jQuery("#Btn_Frmcpagar_02").click(function(event) {

          // mensagen de carregamento
          jQuery("#msg_loading").html(" Aguarde... ");
          //abre a tela de preload
          modal.show();
          //desabilita o envento padrao do formulario
          event.preventDefault();

            jQuery.ajax({
              url: "assets/financeiro/cpagar/Controller_cpagar.php",
              type: "post",
              data:'action=remove&cpagar_id='+jQuery("#cpagar_id").val(),
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


jQuery("#Btn_Frmcpagar_01").click(function(){
    var id_cpagar = jQuery("#cpagar_id").val();/*pega a linha de exibição*/
    New_window('money','850','470','Lançar pagamento','assets/financeiro/cpagar/Frm_lanc_cx.php?cpagar_id='+id_cpagar+'',true,false,'Aguarde ...');/* abre a janela atualizada*/
});

jQuery("#Btn_Frmcpagar_05").click(function(){
    New_window('user','900','500','Cadastro de Clientes','assets/cliente/Frm_cliente.php',false,false,'Aguarde ...');/* abre a janela atualizada*/
});

jQuery("#Btn_Frmcpagar_06").click(function(){
    New_window('user','900','500','Cadastro de Fornecedor','assets/fornecedor/Frm_fornecedor.php',false,false,'Aguarde ...');/* abre a janela atualizada*/
});

</script>
