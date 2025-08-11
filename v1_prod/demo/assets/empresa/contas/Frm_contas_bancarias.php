<?php
require_once"../../../sessao.php";

echo'<div class="tabs-spacer" style="display:none;">';

include("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

$Query_bancos=bancos::all();
$bc= new ArrayIterator($Query_bancos);

$Query_f_cob_sys=formas_cobranca_sys::all();
$f_cob_sys= new ArrayIterator($Query_f_cob_sys);



if(isset($_GET['conta_id'])){

$FRM_id_conta = isset( $_GET['conta_id'])     ? $_GET['conta_id'] : tool::msg_erros("Codigo da conta Invalido.");


// recupera os dados do associado
$dadosconta=contas_bancarias::find_by_sql("SELECT
                                            contas_bancarias.*,
                                            contas_bancarias_cobs.*,
                                            (SELECT sum(valor) FROM caixa  WHERE tipo = 'c' and contas_bancarias_id='".$FRM_id_conta."') as t_entradas,
                                            (SELECT sum(valor) FROM caixa  WHERE tipo = 'd' and contas_bancarias_id='".$FRM_id_conta."') as t_saidas
                                          FROM
                                            contas_bancarias
                                            LEFT JOIN contas_bancarias_cobs
                                          ON contas_bancarias_cobs.contas_bancarias_id = contas_bancarias.id
                                          WHERE
                                            contas_bancarias.id = '".$FRM_id_conta."'");
echo'</div>';

if($dadosconta[0]->status == 0){
					$st		="uk-text-danger";
                }else{
					$st		="uk-text-primary";
                    }
}
?>
</div>

<style>
#menu-float a{ background-color:transparent;}
.tab-content  label span {width: 130px;}
#FrmContasBancarias .uk-form-controls label { cursor: pointer; }
</style>

<link href="js/jquery/plugins/jquery_switch_Button/css/jquery.switchButton.css?<?php echo microtime(); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery/plugins/jquery_switch_Button/jquery.switchButton.js?<?php echo microtime(); ?>"></script>

<div id="menu-float" style="text-align:center;margin:0 780px;top:42px;border:0;background-color:#546e7a;">

	<a  id="Btn_conta_0" class="uk-icon-button uk-icon-search " style="margin-top:2px;text-align:center;" data-uk-tooltip="{pos:'left'}" title="Pesquisar" data-cached-title="Pesquisar" ></a>
<?php if(isset($dadosconta)):?>

    <a  id="Btn_conta_1" class="uk-icon-button uk-icon-bank " style="margin-top:2px;text-align:center; " data-uk-tooltip="{pos:'left'}" title="Nova Conta" data-cached-title="Nova Conta" ></a>
    <a  id="Btn_conta_2" class="uk-icon-button  uk-icon-pencil " style="margin-top:2px; text-align:center;" data-uk-tooltip="{pos:'left'}" title="Salvar Alterações" data-cached-title="Salvar Alterações" ></a>

<?php
else:
?>
    <a  id="Btn_conta_2" class="uk-icon-button  uk-icon-floppy-o" style="margin-top:2px;text-align:center;" data-uk-tooltip="{pos:'left'}" title="Gravar Nova" data-cached-title="Gravar Nova" ></a>

<?php endif;?>
</div>

<ul class="uk-tab uk-gradient-cinza" data-uk-tab style="height:35px;">
    <li ><a href="#dv0">Geral</a></li>
    <li><a href="#dv1" id="info" style="display: none;">Informações Adicionais</a></li>
    <li><a href="#dv2" id="cob_b" style="display: none;">Cobrança e boleto</a></li>
</ul>

<form method="post" id="FrmContasBancarias" class="uk-form" style=" width:900px;  padding-top:0; margin:0;">

<div id="dv0" class="tab-content">

<fieldset style="width:755px; left:5px; top:70px; position:absolute;">

  <input value="<?php  if(isset($dadosconta)):echo $dadosconta[0]->id; endif; ?>"  name="conta_id" type="hidden" id="conta_id" />

  <label>
  <span>Nome</span>
  <input value="<?php  if(isset($dadosconta)):echo $dadosconta[0]->nm_conta; endif; ?>"  name="nm_conta" type="text" class="uk-text-left w_300 " id="nm_conta" />
  </label>

<?php
if(isset($dadosconta)){
if($dadosconta[0]->tp_conta != 2 ){ ?>
<div id="disabled_alt" style="background-color: transparent; width: 760px; height: 300px; position: absolute; top:40px;"></div>
<?php } }?>

  <label>
  <span>Tipo da conta</span>
  <select class="select w_300" name="tp_conta"  id="tp_conta" >
  <?php
    if(isset($dadosconta)):
     echo contas_bancarias::tipo_conta($dadosconta[0]->tp_conta);
    else:
      echo contas_bancarias::tipo_conta(NULL);
    endif;
    ?>
  </select>
  </label>


<label>
<span>F. de Recebimento</span>
<select name="formas_recebimentos_id" class="select " id="formas_recebimentos_id" >


<?php
if(isset($dadosconta)):

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

  echo'<option></option>';
  while($formas_list->valid()):

  if($formas_list->current()->id == $dadosconta[0]->formas_recebimentos_id){$select='selected="selected"';}else{$select="";}
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
<div class="uk-badge uk-text-warning" style="background-color:transparent;">* Forma de rebecimento padrão para esta conta.</div>
</label>


  <label>
  <span>Banco</span>
  <select class="select w_300"  name="cod_banco"  id="cod_banco" >
  <?php
    if(isset($dadosconta)):

      if($dadosconta[0]->tp_conta == '0'){
        echo '<option ></option>';
      }else{

      while($bc->valid()):

      if($bc->current()->cod_banco == $dadosconta[0]->cod_banco){
        $select='selected="selected"';
      }else{
        $select="";
      }
      echo '<option value="'.$bc->current()->cod_banco.'" '.$select.'>'.$bc->current()->cod_banco." - ".$bc->current()->nm_banco.'</option>';
      $bc->next();
      endwhile;
      }

      else:

      echo '<option value="" ></option>';
      while($bc->valid()):
      echo '<option value="'.$bc->current()->cod_banco.'" >'.$bc->current()->cod_banco." - ".$bc->current()->nm_banco.'</option>';
      $bc->next();
      endwhile;


     endif;


  ?>
  </select>
  </label>

  <label>
  <span> Agencia</span>
  <input value="<?php if(isset($dadosconta)):echo $dadosconta[0]->agencia; else: ;endif; ?>"  name="agencia" type="text"  class="uk-text-left w_80" id="agencia" <?php if(isset($dadosconta) && $dadosconta[0]->tp_conta == '0'){echo'readonly="readonly"';} ?> />
  -
  <input value="<?php  if(isset($dadosconta)):echo $dadosconta[0]->dv_agencia; endif; ?>"  name="dv_agencia" type="text" class="uk-text-center w_30 " id="dv_agencia" <?php if(isset($dadosconta) &&  $dadosconta[0]->tp_conta == '0'){echo'readonly="readonly"';} ?>/> <div class="uk-badge uk-text-warning" style="background-color:transparent;">* Não havendo digito deixar em branco.</div>
  </label>

  <label>
  <span>Numero da Conta</span>
  <input value="<?php  if(isset($dadosconta)):echo $dadosconta[0]->conta; else: ;endif; ?>"   name="conta" type="text" class="uk-text-left w_80 "  id="conta"  <?php if(isset($dadosconta) &&  $dadosconta[0]->tp_conta == '0'){echo'readonly="readonly"';} ?>/>
   - 
   <input value="<?php  if(isset($dadosconta)):echo $dadosconta[0]->dv_conta; endif; ?>"  name="dv_conta" type="text" class="uk-text-center w_30 " id="dv_conta" <?php if(isset($dadosconta) &&  $dadosconta[0]->tp_conta == '0'){echo'readonly="readonly"';} ?> /> <div class="uk-badge uk-text-warning" style="background-color:transparent;">* Não havendo digito deixar em branco.</div>
  </label>
  <label>
  <span>Moeda</span>
  <select class="select w_100" name="moeda"  id="moeda" >
  <?php
    if(isset($dadosconta)):
      echo contas_bancarias::tipo_moeda($dadosconta[0]->moeda);
    else:
      echo contas_bancarias::tipo_moeda(NULL);
    endif;
  ?>
  </select>
  <hr>
  </label>

  <div class="uk-grid uk-text-small" >
    <div class="uk-width-1-2">
      <label>
        <span>Limite C.Credito</span>
        <input value="<?php if(isset($dadosconta)):echo number_format($dadosconta[0]->limite_credito,2,",","."); endif;?>" name="lt_credito" type="text" class="uk-text-center w_100" id="lt_credito" placeholder="0,00" />
    </label>
    </div>
    <div class="uk-width-1-2">
   <label>
      <span>Vencimento C.credito</span>
      <input value="<?php if(isset($dadosconta)):echo $dadosconta[0]->dt_venc_limite; endif; ?>" name="dt_v_limite" placeholder="00/00/0000" type="text" class="uk-text-center w_100" id="dt_v_limite" />
    </label>
    </div>
  </div>


<hr>
</fieldset>
<fieldset style="width:400px; left:150px; top:410px; position:absolute; border:0; ">


<div class="uk-grid uk-text-small" >
    <div class="uk-width-1-1">
      <input type="checkbox" name="pg_inicial" id="pg_inicial" class="lcs_check" value="1" <?php  if(isset($dadosconta)){if($dadosconta[0]->pg_inicial == 1){echo"checked";} }?> > Mostrar conta na pagina inicial ?
    </div>
    <div class="uk-width-1-1">
      <input type="checkbox" name="status" id="status" class="lcs_check" value="1" <?php  if(isset($dadosconta)){if($dadosconta[0]->status == 1){echo"checked";} }?> > Está conta está Ativa ?
    </div>
    <div class="uk-width-1-1">
      <input type="checkbox" name="prev_financeira" id="prev_financeira" class="lcs_check" value="1" <?php  if(isset($dadosconta)){if($dadosconta[0]->prev_financeira == 1){echo"checked";} }?> > Incluir conta nas previsões financeiras ?
    </div>
    <div class="uk-width-1-1">
      <input type="checkbox" name="deb_aut" id="deb_aut" class="lcs_check" value="1" <?php  if(isset($dadosconta)){if($dadosconta[0]->debito_auto == 1){echo"checked";} }?> > Está conta possui convênio debito automatico ?
    </div>
    <div class="uk-width-1-1">
      <input type="checkbox" name="maq_cartao" id="maq_cartao" class="lcs_check" value="1" <?php  if(isset($dadosconta)){if($dadosconta[0]->maq_cartao == 1){echo"checked";} }?> > Está conta possui recebimento de cartão ?
    </div>
</div>

</fieldset>

<fieldset style="width:170px; right:10px; top:70px; position:absolute; border:1px solid #ccc; ">

<?php if(isset($dadosconta)){ ?>

<legend style="width:120px; background-color:transparent; color:#666; font-size:10px; text-transform: capitalize; line-height:12px;box-shadow:none;">Saldo atual da conta</legend>

   <label style="text-align:center;">

    <input name="sd_abertura" type="text" class="w_150 uk-text-center" id="sd_abertura" value="<?php if(isset($dadosconta)):echo number_format($dadosconta[0]->t_entradas - $dadosconta[0]->t_saidas,2,",","."); endif;?>" />


    <div id="SaldoInicial" class="uk-modal">
        <div class="uk-modal-dialog" style="width:250px;">
            <!--<button type="button" class="uk-modal-close uk-close"></button> -->
            <div class="uk-modal-header ">
            <h2><i class="uk-icon-bank" ></i> Saldo Inicial</h2>
            </div>
                <label style="text-align:center;">
                    <input  type="text" class="w_150 uk-text-center" name="up_sd_ini " id="up_sd_ini" placeholder="valor" value="<?php if(isset($dadosconta)):echo number_format($dadosconta[0]->t_entradas - $dadosconta[0]->t_saidas,2,",","."); endif;?>"/>
                </label>

            <div class="uk-modal-footer uk-text-center">
                <a id="Btn_conta_3" class="uk-button uk-button-primary uk-button-small" ><i class="uk-icon-search" ></i> Confirmar</a>
                <a class="uk-button uk-button-danger uk-button-small uk-modal-close" ><i class="uk-icon-remove" ></i> Cancelar</a>
            </div>
        </div>
    </div>
    <a class="uk-button" data-uk-modal="{target:'#SaldoInicial'}" style="z-index:1000; margin:5px 0px;"><i class="uk-icon-barcode"></i> Alterar Saldo </a>

  </label>

<?php }else{ ?>

  <legend style=" width:120px; background-color:transparent; color:#666; font-size:10px; text-transform: capitalize; line-height:12px;box-shadow:none;">Saldo de Abertura</legend>
  <label style="text-align:center;">
    <input name="dt_abertura" type="text" class="w_150 uk-text-center" id="dt_abertura" placeholder="Data Abertura"/>
  </label >

  <label style="text-align:center;">
    <input name="sd_abertura" type="text" class=" w_150 uk-text-center " id="sd_abertura" placeholder="Valor abertura"/>
  </label>

<?php } ?>

</fieldset>
</div>

<div id="dv1" class="tab-content">
 <fieldset style="width:725px; left:5px; top:70px; position:absolute;">
 informações
</fieldset>
</div>

<div id="dv2" class="tab-content" >
<fieldset style="width:758px; left:5px; top:70px; position:absolute;">

    <label>
    <span>Favorecido:</span>
        <input value="<?php  if(isset($dadosconta)):echo $dadosconta[0]->favorecido; endif; ?>"  name="favorecido"  type="text" class="w_400 " id="favorecido"  />
    </label>
    <label><span>CNPJ:</span>
        <input value="<?php  if(isset($dadosconta)):echo $dadosconta[0]->cnpj; endif; ?>"  name="cnpj_fav"  type="text" class="w_200 " id="cnpj_fav"  />
    </label>
    <label><span>Cedente/Convenio</span>
    <input value="<?php  if(isset($dadosconta)):echo $dadosconta[0]->cod_cedente;endif; ?>"  name="cod_cedente" type="text" class="w_100 uk-text-center" id="cod_cedente"  />
             / <input value="<?php  if(isset($dadosconta)):echo $dadosconta[0]->dv_cod_cedente;endif; ?>"  name="dv_cod_ced" type="text"
             class="w_20 " id="dv_cod_ced"  /> <div class="uk-badge uk-text-warning" style="background-color:transparent;">Não havendo informar conta bancaria.<br>
             "Para cooperativas informar o convênio" </div>
    </label>

    <label id="lb_cod_transmissao" style="display:<?php if($dadosconta[0]->cod_banco == "033"){echo'block';}else{echo'none';}; ?>">
    <span>Cod Transmissão</span>
    <input value="<?php  if(isset($dadosconta)):echo $dadosconta[0]->cod_transmissao;endif; ?>"  name="cod_transmissao" type="text" class="w_150 uk-text-center" id="cod_transmissao"  />
              <div class="uk-badge uk-text-warning" style="background-color:transparent;">Específico para o banco santander. </div>
    </label>

    <label >
    <span>Tipo / Variação</span>
    <select class="select w_250" name="carteira_cobranca"  id="carteira_cobranca" >
		<?php
          if(isset($dadosconta)):
			 echo contas_bancarias_cob::tipo_carteira($dadosconta[0]->cod_banco,$dadosconta[0]->carteira_cobranca);
          endif;
		?>
    </select>
    <input value="<?php  if(isset($dadosconta)):echo $dadosconta[0]->variacao_carteira; endif; ?>"  name="variacao_carteira" type="text"
            class="w_50 uk-text-center" id="variacao_carteira"  /><div class="uk-badge uk-text-warning" style="background-color:transparent;">Não havendo variação deixar em branco.  </div>
    </label>
    <label>
    <span>Carteira Remessa</span>
    <input value="<?php  if(isset($dadosconta)):echo $dadosconta[0]->carteira_remessa; endif; ?>"  name="carteira_remessa" type="text"
            class="w_50 uk-text-center" id="carteira_remessa"  /> <div class="uk-badge uk-text-warning" style="background-color:transparent;">Não havendo deixar em branco.</div>
        | Modalidade <input value="<?php  if(isset($dadosconta)):echo $dadosconta[0]->modalidade; endif; ?>"  name="modalidade" type="text"
                            class="w_50 uk-text-center" id="modalidade"  /> <div class="uk-badge uk-text-warning" style="background-color:transparent;">Não havendo deixar em branco.</div>
    </label>
    <label>
    <span>Espêcie</span>
    <input value="<?php  if(isset($dadosconta)):echo $dadosconta[0]->especie; endif; ?>"  name="especie" type="text"
            class="w_50 uk-text-center" id="especie"  />
    | Esp.Doc | <input value="<?php  if(isset($dadosconta)):echo $dadosconta[0]->especie_doc; endif; ?>"  name="especie_doc" type="text"
            class="w_50 uk-text-center" id="especie_doc"  />
    | Aceite |
        <input value="<?php  if(isset($dadosconta)):echo $dadosconta[0]->aceite; endif; ?>"  name="aceite" type="text"
               class="w_50 uk-text-center" id="aceite"  />
    </select>
    </label>
    <label>
    <span>U.N Numero</span>
    <input value="<?php  if(isset($dadosconta)):echo $dadosconta[0]->ult_nosso_numero; endif; ?>"  name="ult_nosso_numero" type="text"
            class="w_100 uk-text-center" id="carteirult_nosso_numeroa_remessa"  /> <div class="uk-badge uk-text-warning" style="background-color:transparent;">Número Padrão 1</div>
    </label>
    <label>
    <span>Local de Pgto:</span>
    <input value="<?php  if(isset($dadosconta)):echo $dadosconta[0]->local_pgto; endif; ?>"  name="local_pgto" type="text" class="w_400 " id="local_pgto"  />
    </label>
    <label>
    <span>Instrução 1:</span>
    <input value="<?php  if(isset($dadosconta)):echo $dadosconta[0]->inst1; endif; ?>"  name="inst1" type="text" class="w_400 " id="inst1"  />
    </label>
    <label>
    <span>Instrução 2:</span>
    <input value="<?php  if(isset($dadosconta)):echo $dadosconta[0]->inst2; endif; ?>"  name="inst2" type="text" class="w_400 " id="inst2"  />
    </label>
    <label>
    <span>Inst.Adcional:</span>
    <input value="<?php  if(isset($dadosconta)):echo $dadosconta[0]->inst_adcional; endif; ?>"  name="inst_adcional" type="text" class="w_400 " id="inst_adcional" />
    </label>

</fieldset>
<fieldset style="width:120px; right:30px; top:70px; position:absolute; border:1px solid #ccc; ">
<legend style="width:120px; background-color:transparent; color:#666; font-size:10px; text-transform: capitalize; line-height:12px;box-shadow:none;">Layout Arquivo</legend>
<div class="uk-grid uk-text-small" >
    <div class="uk-width-1-1">
    <input type="radio"  name="tp_arquivo" value="240_SR" <?php  if(isset($dadosconta)){if($dadosconta[0]->tipo_arquivo == "240_SR"){echo"checked";} }?> > 240 SR
    </div>
     <div class="uk-width-1-1">
    <input type="radio"  name="tp_arquivo" value="240_CR" <?php  if(isset($dadosconta)){if($dadosconta[0]->tipo_arquivo == "240_CR"){echo"checked";} }?> > 240 CR
    </div>
     <div class="uk-width-1-1">
    <input type="radio"  name="tp_arquivo" value="400_CR" <?php  if(isset($dadosconta)){if($dadosconta[0]->tipo_arquivo == "400_CR"){echo"checked";} }?> > 400 CR
    </div>
  </div>
</fieldset>
</div>

</form>

<script src="framework/uikit-2.24.0/js/core/tab.min.js"></script>

<script type="text/javascript" >


/* define a cor do menu lateral*/
jQuery(document).ready(function(){
	jQuery("#menu-float").css("background-color",""+jQuery("#"+jQuery("#menu-float").closest('.Window').attr('id')+"").css('border-left-color')+"");
  jQuery("#dv1,#dv2,#cob_b,#info").hide(); // oculta a aba de configuração dos dados da cobrança bancaria
  jQuery("#dt_abertura,#dt_v_limite").mask("99/99/9999");
  jQuery("#cnpj_fav").mask("99.999.999/9999-99");
	jQuery("#sd_abertura,#lt_credito,#up_sd_ini").maskMoney({showSymbol:true, symbol:"", decimal:",", thousands:"."});
  if(jQuery("#tp_conta").val() == 2){jQuery("#cob_b").show();}

});

//FUNÇÃO TABS
jQuery(".uk-tab a").click(function(event) {
        event.preventDefault();
        var tab =jQuery(this).attr("href");
        jQuery(".tab-content").not(tab).css("display", "none");
        jQuery(tab).fadeIn();
    });

// exibe a aba cobranã
jQuery("#tp_conta").change(function(){

  if(jQuery(this).val() == "2"){
      jQuery("#cob_b").show(); // exibe a aba cobrança bancaria
      jQuery("#formas_recebimentos_id").prop("disabled", false);
  }else{
    jQuery("#cob_b").hide(); // exibe a aba cobrança bancaria
    jQuery("#formas_recebimentos_id").prop("disabled", true);
  }

});


/* popula as carteiras de cobrança dos bancos quando seleciona o banco*/
jQuery("#cod_banco").change(function(){

	$.post("assets/empresa/contas/Controller_carteiras.php",{cod_banco:jQuery(this).val(),carteira:"0"},
	function(resultado){
		jQuery("#carteira_cobranca").html(resultado);
	});

  if(jQuery(this).val() == "033"){jQuery("#lb_cod_transmissao").show();}else{jQuery("#lb_cod_transmissao").hide();}// habilita o campo codigo de transmissão para o banco santander

});

/*botão para abri o formulario de pesquisar contas cadastradas*/
jQuery("#Btn_conta_0").click(function(){
  New_window('search','700','520','Pesquisa Conta','assets/empresa/contas/Frm_pesquisa_contas.php',true,false,'Carregando...');

});

/* botão para deixar o formulario em modo de adcionar novo*/
jQuery("#Btn_conta_1").click(function(){
	var janela=jQuery("#FrmContasBancarias").closest('.Window').attr('id');
	jQuery("#"+janela+"").remove();
	New_window('search','780','520','Contas Bancarias','assets/empresa/contas/Frm_contas_bancarias.php',true,false,'Aguarde...');
});

 /*faz a requisição do formulario tanto para adição quanto para edição*/
jQuery(function() {

jQuery("#Btn_conta_2").click(function(event) {

	/*mensagen de carregamento*/
	jQuery("#msg_loading").html(" Aguarde... ");
	/*abre a tela de preload*/
	modal.show();
	/*desabilita o envento padrao do formulario*/
	event.preventDefault();


	jQuery.ajax({
		async: true,
		url: "assets/empresa/contas/Controller_contas.php",
		type: "post",
		data:jQuery("#FrmContasBancarias").serialize(),
		success: function(resultado) {

			if(jQuery.isNumeric(resultado)){

				var janela   = jQuery("#FrmContasBancarias").closest('.Window').attr('id'); /*variavel com o id da janela*/
        jQuery("#"+janela+"").remove();

				var conta_id = resultado; /*variavel com o resultado*/
				conta_id     = conta_id.replace(/[^\d]+/g,'');
				New_window('search','780','520','Contas Bancarias','assets/empresa/contas/Frm_contas_bancarias.php?conta_id='+conta_id+'',true,false,'Aguarde...');

			}else{
				//abre a tela de preload
				modal.hide();
				UIkit.notify(""+resultado+"", {status:'danger',timeout: 2500})
			}
	},
	error:function (){
		UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
		modal.hide();
	}

  });
 });
});


//up_sd_ini Btn_contas_3
 /*faz a requisição do formulario tanto para adição quanto para edição*/
jQuery(function() {

jQuery("#Btn_conta_3").click(function(event) {

  /*mensagen de carregamento*/
  jQuery("#msg_loading").html(" Aguarde... ");
  /*abre a tela de preload*/
  modal.show();
  /*desabilita o envento padrao do formulario*/
  event.preventDefault();


  jQuery.ajax({
    async: true,
    url: "assets/empresa/contas/Controller_saldo.php",
    type: "post",
    data:'valor='+jQuery("#up_sd_ini").val()+'&contas_bancarias_id='+jQuery("#conta_id").val()+'',
    success: function(resultado) {

      if(jQuery.isNumeric(resultado)){

        var janela   = jQuery("#FrmContasBancarias").closest('.Window').attr('id'); /*variavel com o id da janela*/
        jQuery("#"+janela+"").remove();

        var conta_id = resultado; /*variavel com o resultado*/
        conta_id     = conta_id.replace(/[^\d]+/g,'');
        New_window('search','750','520','Contas Bancarias','assets/empresa/contas/Frm_contas_bancarias.php?conta_id='+conta_id+'',true,false,'Aguarde...');

      }else{
        //abre a tela de preload
        modal.hide();
        UIkit.notify(""+resultado+"", {status:'danger',timeout: 2500})
      }
  },
  error:function (){
    UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
    modal.hide();
  }

  });
 });
});
</script>