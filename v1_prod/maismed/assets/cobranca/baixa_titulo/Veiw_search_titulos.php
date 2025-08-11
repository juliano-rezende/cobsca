<?php
require_once("../../../sessao.php");
?>
<div class="tabs-spacer" style="display:none;">
<?php
//classe de validação de cnpj e cpf
require_once("../../../classes/valid_cpf_cnpj.php");
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');
?>
</div>
<?php

$FRM_tp   = isset( $_POST['tp']) ? $_POST['tp'] : tool::msg_erros("O Campo tp é Obrigatorio.");
$FRM_vl   = isset( $_POST['vl']) ? $_POST['vl'] : tool::msg_erros("O Campo vl é Obrigatorio.");


// vericamos se não está vazio antes de qualquer coisa
if($FRM_vl == ""){

  echo'<span style="width:80%; display:block; text-align:center; margin: 10px auto;" class=" uk-alert uk-alert-danger">Não é possivel realizar sua pesquisa dados insuficientes.</span>';exit();

    }

// pesquisa pelo nome
if ($FRM_tp == 0) {

  //if ($FRM_vl) { echo'<span style="width:80%; display:block; text-align:center; margin: 10px auto;" class=" uk-alert uk-alert-danger">Digite apenas letras.</span>';exit(); }

    $where      = "titulos_bancarios.sacado LIKE '".$_POST['vl']."%'";
}

// cpf ou cnpj
if ($FRM_tp == 1 or $FRM_tp == 2) {

  if(strlen($FRM_vl) < 14 or strlen($FRM_vl) > 18){  echo'<span style="width:80%; display:block; text-align:center; margin: 10px auto;" class=" uk-alert uk-alert-danger">CPF ou CNPJ invalido.</span>';exit();}

  // Cria um objeto sobre a classe
  $cpf_cnpj = new ValidaCPFCNPJ(tool::limpaString($FRM_vl));

    $vl_campo   = tool::limpaString($FRM_vl);
    $where      = "titulos_bancarios.cpfcnpjsacado ='".$vl_campo."'";
}

// linha digitavel
if($FRM_tp == 3) {

  if(strlen($FRM_vl) != 54){  echo'<span style="width:80%; display:block; text-align:center; margin: 10px auto;" class=" uk-alert uk-alert-danger">Linha digitavél invalida '.strlen($FRM_vl).' digitos.</span>';exit();}

    $vl_campo   = tool::limpaString($FRM_vl);
    $where      = "titulos_bancarios.linha_digitavel ='".$vl_campo."'";
}// linha digitavel
if($FRM_tp == 4) {

  if(strlen($FRM_vl) == ""){  echo'<span style="width:80%; display:block; text-align:center; margin: 10px auto;" class=" uk-alert uk-alert-danger">Nosso Numero Invalido '.strlen($FRM_vl).' digitos.</span>';exit();}

    $vl_campo   =" ".tool::CompletaZeros(11,tool::limpaString($FRM_vl));
    $where      = "titulos_bancarios.nosso_numero='".substr($vl_campo,1,10)."' AND titulos_bancarios.dv_nosso_numero='".substr($vl_campo,11,1)."'";
}

?>
<div class="tabs-spacer" style="display:none;">
<?php
$query="SELECT
          titulos_bancarios.id,
          titulos_bancarios.status,
          titulos_bancarios.nosso_numero,
          titulos_bancarios.dv_nosso_numero,
          titulos_bancarios.sacado,
          titulos_bancarios.dt_emissao,
          titulos_bancarios.dt_vencimento,
          titulos_bancarios.vlr_nominal,
          contas_bancarias.cod_banco,
          contas_bancarias_cobs.tipo_arquivo,
          faturamentos.matricula
          FROM
          titulos_bancarios
          LEFT JOIN contas_bancarias ON titulos_bancarios.contas_bancarias_id = contas_bancarias.id
          LEFT JOIN contas_bancarias_cobs ON titulos_bancarios.contas_bancarias_id = contas_bancarias_cobs.contas_bancarias_id
          LEFT JOIN faturamentos ON faturamentos.titulos_bancarios_id = titulos_bancarios.id
          WHERE
          ".$where."  GROUP BY nosso_numero ORDER BY dt_vencimento ASC";

// recupera as taxas configuradas na empresa
$tx_encontrada=configs::find_by_empresas_id($COB_Empresa_Id);

// monta a query especifica
$Query_titulos=titulos::find_by_sql($query);
$Listitles= new ArrayIterator($Query_titulos);

?>
</div>

<?php
// vericamos se não está vazio antes de qualquer coisa
if(count($Query_titulos) == 0){

  echo'<span style="width:80%; display:block; text-align:center; margin: 10px auto;" class=" uk-alert uk-alert-danger">Registro não localizado.</span>';exit();

    }
?>


<table  class="uk-table uk-table-striped uk-table-hover" style="border:1px solid #ccc; " id="table_tit">
<tbody>
<?php
$linha=0;
while($Listitles->valid()):

$linha++;

$dt_e = new ActiveRecord\DateTime($Listitles->current()->dt_emissao);
$dt_v = new ActiveRecord\DateTime($Listitles->current()->dt_vencimento);


/* dados para montar o link de recebimento*/

$banco    = $Listitles->current()->cod_banco;
$tp_arq   = $Listitles->current()->tipo_arquivo;
$id_titulo = $Listitles->current()->id;


echo '<div class="tabs-spacer" style="display:none;">';
/* valor atual com juros e multa*/
$vl_atual=faturamentos::Calcula_Juros($Listitles->current()->vlr_nominal,$dt_v->format('Y-m-d'),$tx_encontrada->juros,$tx_encontrada->multa);
echo'</div>';
?>

    <tr  id="tr<?php echo tool::CompletaZeros("7",$Listitles->current()->id); ?>"  uk-data-st="<?php echo $Listitles->current()->status; ?>" style="line-height:22px;" uk-data-path= "<?php echo $banco; ?>.<?php echo $tp_arq; ?>" class="<?php if($Listitles->current()->status!= 0){echo"uk-text-muted";} ?>">
      <th class="uk-width uk-text-center" style="width:20px;"><?php echo $linha; ?></th>
      <td id="id<?php echo $linha; ?>" class="uk-width uk-text-center" style="width:90px;"><?php echo tool::CompletaZeros("7",$Listitles->current()->id); ?></td>
      <td id="id<?php echo $linha; ?>" class="uk-width uk-text-center" style="width:90px;"><?php echo $Listitles->current()->matricula; ?></td>
      <td id="sc<?php echo $linha; ?>" class="uk-text-left" style="text-transform: uppercase;"><?php echo utf8_encode($Listitles->current()->sacado); ?></td>
      <td id="dte<?php echo $linha; ?>" class="uk-width uk-text-center" style="width:120px;" ><?php echo $dt_e->format('d/m/Y'); ?></td>
      <td id="dtv<?php echo $linha; ?>" class="uk-width uk-text-center " style="width:120px;"><?php echo $dt_v->format('d/m/Y'); ?></td>
      <td id="vlrn<?php echo $linha; ?>" class="uk-width uk-text-center" style="width:120px;" ><?php echo number_format($Listitles->current()->vlr_nominal,2,",","."); ?></td>
      <td id="vlrat<?php echo $linha; ?>" class="uk-width uk-text-center" style="width:120px;" ><?php  if($Listitles->current()->status == '0'){echo number_format($vl_atual,2,",",".");}else{echo "Baixada/Quitada";}  ?></td>
      <td class="uk-width uk-text-center" style="width:80px;" ><img src="imagens/icon_bancos/<?php echo $Listitles->current()->cod_banco; ?>.png" alt="" width="22" height="15"></td>
    </tr>
<?php

$Listitles->next();
endwhile;
?>
</tbody>
</table>


<div id="Form_title_0" class="uk-modal">
<div class="uk-modal-dialog" style="width:500px;">
    <!--<button type="button" class="uk-modal-close uk-close"></button> -->
    <div class="uk-modal-header ">
    <h2><i class="uk-icon-bank  uk-icon-small" ></i> Baixar Titulo</h2>
    </div>
      <form name="FrmFiltroRetorno" method="post" id="FrmBaixaTitulo" class="uk-form">
        <fieldset style=" width:450px;border:0; padding:0; padding-top:0px;">
          <label id="lab00">
          <span>Titulo nº</span>
            <input type="text"  class="input_text w_120 uk-text-center" name="bx_id_title" id="bx_id_title" readonly="readonly" />
          </label>
          <label id="lab01">
          <span>Valor Nominal</span>
            <input type="text"  class="input_text w_120 uk-text-center" name="vlr_nominal" id="vlr_nominal" readonly="readonly"  />
          </label>
          <label id="lab02">
          <span>Juros/Multa</span>
            <input type="text"  class="input_text w_120 uk-text-center" name="bx_vlr_total" id="bx_vlr_total" readonly="readonly"  />
          </label>
          <label id="lab03">
          <span>Descontos</span>
            <input type="text"  class="input_text w_120 uk-text-center" name="bx_vlr_descontos" id="bx_vlr_descontos" value="0,00"   />
          </label>
          <label id="lab04">
          <span>Data pgto</span>
          <input name="dtpgto"  type="text" class=" w_120 uk-text-center " id="dtpgto" placeholder="00/00/0000"  data-uk-datepicker="{format:'DD/MM/YYYY'}" />
          </label>
          <label id="lab05">
          <span>Local de Pgto</span>
            <select name="tpbaixa" class="select w_120" id="tpbaixa">
              <option value=""></option>
              <option value="0">Local</option>
              <option value="1">Bancaria</option>
            </select>
          </label>
          <label id="lab06">
            <span>Tarifa</span>
            <input name="bx_tf"  type="text" class=" w_100 uk-text-center " id="bx_tf" />
          </label>
        </fieldset>
      </form>
      <div class="uk-modal-footer uk-text-right">
            <button class="uk-button uk-button-danger"  id="BtnBaixa00" type="button"><i class="uk-icon-remove" ></i> Cancelar Boleto</button>
            <button class="uk-button uk-button-primary" id="BtnBaixa01" type="button"><i class="uk-icon-check" ></i> Receber Boleto</button>
            <button class="uk-button uk-button-success"  id="BtnBaixa02" type="button"><i class="uk-icon-reply-all" ></i> Estonar Baixa</button>
    </div>
</div>
</div>


<script type="text/javascript" >

jQuery("#lab06").hide();
jQuery("#dtpgto").mask("99/99/9999");
jQuery("#bx_vlr_descontos,#bx_tf").maskMoney({showSymbol:true, symbol:"", decimal:",", thousands:"."});

/* habilita e desabilita o cmapo tarifa*/
jQuery("#tpbaixa").change(function(){

  if(jQuery(this).val() == 1){jQuery("#lab06").show();}else{jQuery("#lab06").hide();}

});

/* variaveis geral*/
var vl  = jQuery("#search").val();
var tp  = jQuery("input[name='tp']:checked").val();

jQuery('#table_tit tr').click(function(event) {


/* verificamos o status do titulo se caso ja tenha sido baixado ou quitado não liberamos para abrir o formulario de recebimento */
var uk_data_st = jQuery(this).attr("uk-data-st");


 if (uk_data_st != 0){
     jQuery("#BtnBaixa00,#BtnBaixa01,#dtpgto,#tpbaixa,#bx_vlr_descontos").attr("disabled",true);
     jQuery("#BtnBaixa0").attr("disabled",false);
    }else{

    jQuery("#BtnBaixa00,#BtnBaixa01,#dtpgto,#tpbaixa,#bx_vlr_descontos").attr("disabled",false);
     jQuery("#BtnBaixa02").attr("disabled",true);
    }

/* definimos as configurações para pegar o valor das tds*/
    var linha = (jQuery(this).index()+1);


    var id_title    = jQuery("#id"+linha).text();
    var vlr_nominal = jQuery("#vlrn"+linha).text();
    var vlr_atual   = jQuery("#vlrat"+linha).text();


    jQuery("#bx_id_title").val(id_title);
    jQuery("#vlr_nominal").val(vlr_nominal);
    jQuery("#bx_vlr_total").val(vlr_atual);

    var modal = UIkit.modal("#Form_title_0");

    if ( modal.isActive() ) {
        modal.hide();
    } else {
        modal.show();
    }

  });



/* cancelamento do titulo*/
jQuery("#BtnBaixa00").click(function(){

/* recupera o id do titulo */
var id  = jQuery("#bx_id_title").val();

/* recuperamos o campo uk-data-path contendo o endereço do controller que vai ser usado para processar o recebimento*/
var data = jQuery("#tr"+id).attr("uk-data-path");
/* separamos os dados*/
var res  = data.split(".");


// mensagen de carregamento
jQuery("#msg_loading").html(" Processando ");
//abre a tela de preload
modal.show();
//fecha o drop
jQuery(".uk-dropdown").hide();


  $.post("assets/cobranca/baixa_titulo/controllers/Controller_processa_titulo_"+res[0]+"_"+res[1]+".php",{action:"cancel",id:id},
   function(resultado){

      // envia os dados para o banco
      $.post("assets/cobranca/baixa_titulo/Veiw_search_titulos.php",{tp:tp,vl:vl},function(resultado){jQuery("#Grid_titulos").html(resultado);});

      modal.hide();
      UIkit.modal.alert(""+resultado+"");

    });


});


jQuery("#BtnBaixa01").click(function(){

/* recupera o id do titulo */
var id             = jQuery("#bx_id_title").val();
/* valor nominal */
var vlr_nominal    = jQuery("#vlr_nominal").val();
/* valor total com juros e multa */
var vlr_total      = jQuery("#bx_vlr_total").val();
/* descontos */
var vlr_descontos  = jQuery("#bx_vlr_descontos").val();
/* dt pgto */
var dtpgto         = jQuery("#dtpgto").val();
/* tipo de baixa */
var tpbaixa        = jQuery("#tpbaixa").val();
/* vlr tarifa */
var vlr_tarifa     = jQuery("#bx_tf").val();



/*validação de campo*/
if(tpbaixa == ""){ UIkit.modal.alert("Local de pagamento é obrigatorio !"); exit();}
if(tpbaixa == 1 && vlr_tarifa == ""){ UIkit.modal.alert("Valor de tarifa obrigatorio !"); exit();}

/* recuperamos o campo uk-data-path contendo o endereço do controller que vai ser usado para processar o recebimento*/
var data = jQuery("#tr"+id).attr("uk-data-path");
/* separamos os dados*/
var res  = data.split(".");


// mensagen de carregamento
jQuery("#msg_loading").html(" Processando ");
//abre a tela de preload
modal.show();
//fecha o drop
jQuery(".uk-dropdown").hide();


$.post("assets/cobranca/baixa_titulo/controllers/Controller_processa_titulo_"+res[0]+"_"+res[1]+".php",{action:"pay",id:id,vlr_nominal:vlr_nominal,vlr_total:vlr_total,vlr_descontos:vlr_descontos,dtpgto:dtpgto,tpbaixa:tpbaixa,vlr_tarifa:vlr_tarifa},
 function(resultado){

  // envia os dados para o banco
  $.post("assets/cobranca/baixa_titulo/Veiw_search_titulos.php",{tp:tp,vl:vl},function(resultado){jQuery("#Grid_titulos").html(resultado);});

  modal.hide();
  UIkit.modal.alert(""+resultado+"");

  });


});

jQuery("#BtnBaixa02").click(function(){

/* recupera o id do titulo */
var id             = jQuery("#bx_id_title").val();
/* tipo de baixa */
var tpbaixa        = jQuery("#tpbaixa").val();

/*validação de campo*/
if(tpbaixa == ""){ UIkit.modal.alert("Local de pagamento é obrigatorio !"); exit();}


/* recuperamos o campo uk-data-path contendo o endereço do controller que vai ser usado para processar o recebimento*/
var data = jQuery("#tr"+id).attr("uk-data-path");
/* separamos os dados*/
var res  = data.split(".");

// mensagen de carregamento
jQuery("#msg_loading").html(" Processando ");
//abre a tela de preload
modal.show();
//fecha o drop
jQuery(".uk-dropdown").hide();


$.post("assets/cobranca/baixa_titulo/controllers/Controller_processa_titulo_"+res[0]+"_"+res[1]+".php",{action:"reverse",id:id,tpbaixa:tpbaixa},
 function(resultado){
  alert(resultado);

  // envia os dados para o banco
  $.post("assets/cobranca/baixa_titulo/Veiw_search_titulos.php",{tp:tp,vl:vl},function(resultado){jQuery("#Grid_titulos").html(resultado);});

  modal.hide();
  UIkit.modal.alert(""+resultado+"");

  });


});
</script>