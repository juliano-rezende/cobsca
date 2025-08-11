<?php
require_once("../../../sessao.php");

$proto = strtolower(preg_replace('/[^a-zA-Z]/','',$_SERVER['SERVER_PROTOCOL'])); //pegando só o que for letra
$location = $proto.'://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];

$caminho=explode("assets",$location);
?>
<div class="tabs-spacer" style="display:none;">
<?php
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');
?>
</div>
<?php

$FRM_pesquisapor    = isset( $_POST['pesquisarpor']) ? intval($_POST['pesquisarpor'])
                                                     : tool::msg_erros("O Campo pesquisarpor é Obrigatorio.");

$FRM_conta_bancaria = isset( $_POST['conta_id'])     ? intval($_POST['conta_id'])
                                                     : tool::msg_erros("O Campo conta_id residencia é Obrigatorio.");

$FRM_status         = isset( $_POST['status'])       ? intval($_POST['status'])
                                                     : tool::msg_erros("O Campo status de trabalho é Obrigatorio.");

$FRM_convenio       = isset( $_POST['convenio_id'])  ? intval($_POST['convenio_id'])
                                                     : tool::msg_erros("O Campo convenio_id celular é Obrigatorio.");

$FRM_dt_inicial     = isset( $_POST['dtini'])        ? $_POST['dtini']
                                                     : tool::msg_erros("O Campo dtini é Obrigatorio.");

$FRM_dt_final       = isset( $_POST['dtfim'])        ? $_POST['dtfim']
                                                     : tool::msg_erros("O Campo dtfim é Obrigatorio.");

$erro="";
/*
DEFINE OS DADOS DA PAGINAÇÃO
*/
if(isset($_POST['pagina'])){

  $pagina = $_POST['pagina'];

}else{

  $pagina = 1;

}

$limite =50; // Define o limite de registros a serem exibidos com o valor cinco

$inicio = ceil(($pagina * $limite) - $limite);


if($FRM_dt_inicial == "" or $FRM_dt_final == ""){tool::msg_erros("Os campos Data inicial e data final são obrigatorios.");}
/*
DEFINE A CONTA BANCARIA
*/
if($FRM_conta_bancaria != ""){$conta="AND titulos_bancarios.contas_bancarias_id='".$FRM_conta_bancaria."'";}else{$conta="";}

/*
DEFINE O convenios
*/
if($FRM_convenio != ""){$convenio="AND faturamentos.convenios_id='".$FRM_convenio."'";}else{$convenio="";}


/*
DEFINE O STATUS
"" todos
0 em aberto
1 pagos

*/
if($FRM_status >= "0"){$status="titulos_bancarios.status='".$FRM_status ."' AND";}else{$status="";}

/*
DEFINE  O PESQUISA POR
"0" Data da Vencimento
"1" Data da Pagamento
"2" Data Emissão
"3" Convênio
"4" Conta Bancaria
*/

if($FRM_pesquisapor == 0){

  $where ="".$status ."  titulos_bancarios.dt_vencimento BETWEEN  '".tool::InvertDateTime(tool::LimpaString($FRM_dt_inicial),0)."' AND '".tool::InvertDateTime(tool::LimpaString($FRM_dt_final),0)."' ".$conta." ".$convenio."";

}elseif($FRM_pesquisapor == 1){

  $where ="".$status." titulos_bancarios.dt_pagamento BETWEEN '".tool::InvertDateTime(tool::LimpaString($FRM_dt_inicial),0)."' AND '".tool::InvertDateTime(tool::LimpaString($FRM_dt_final),0)."' ".$conta." ".$convenio."";

}elseif($FRM_pesquisapor == 2){

  $where ="".$status." titulos_bancarios.dt_emissao BETWEEN '".tool::InvertDateTime(tool::LimpaString($FRM_dt_inicial),0)."' AND '".tool::InvertDateTime(tool::LimpaString($FRM_dt_final),0)."' ".$conta." ".$convenio."";

}else{
    $erro="<div class='uk-notify-message uk-notify-message-danger uk-text-center' style='width:500px; margin:0 auto;'>
                  <i class='uk-icon-warning  uk-text-danger' ></i> Selecione o tipo de pesquisa.</br>
           </div>";
}


if($erro!= ""){echo $erro; exit();}
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
          titulos_bancarios.dt_pagamento,
          titulos_bancarios.vlr_nominal,
          titulos_bancarios.vlr_pago,
          titulos_bancarios.cod_cedente,
          contas_bancarias.cod_banco,
          contas_bancarias.agencia,
          faturamentos.convenios_id,
          faturamentos.referencia
          FROM
          titulos_bancarios
          LEFT JOIN contas_bancarias ON titulos_bancarios.contas_bancarias_id = contas_bancarias.id
          LEFT JOIN faturamentos ON faturamentos.titulos_bancarios_id = titulos_bancarios.id
          LEFT JOIN associados ON faturamentos.matricula = associados.matricula
          WHERE
          ".$where." GROUP BY nosso_numero";


// retorna todo os registros para montar a paginação
$Query_all=titulos::find_by_sql($query);
$items=count($Query_all);

// monta a query especifica
$query_p=$query." ORDER BY nosso_numero ASC LIMIT ".$inicio.",".$limite."";

$Query_titulos=titulos::find_by_sql($query_p);
$Listitles= new ArrayIterator($Query_titulos);

?>
</div>
<div style="height:<?php echo tool::HeightContent($COB_Heigth)-75;?>px; overflow:hidden; ">

<table  class="uk-table uk-table-striped uk-table-hover" >
  <tbody>
  <?php
  $linha=0;
  while($Listitles->valid()):

  $linha++;

  $dt_e   = new ActiveRecord\DateTime($Listitles->current()->dt_emissao);
  $dt_v   = new ActiveRecord\DateTime($Listitles->current()->dt_vencimento);
  $dt_p   = new ActiveRecord\DateTime($Listitles->current()->dt_pagamento);
  $dt_ref = new ActiveRecord\DateTime($Listitles->current()->referencia);
  $print='0';


  if($Listitles->current()->status == 0){

    if(strtotime(''.$dt_v->format('d-m-Y').'') < strtotime( date( 'd-m-Y' ) ) ){

      $class="uk-text-warning uk-text-bold";
      $print='1';

    }else{

      $class="uk-text-bold uk-text-primary";

    }

  }elseif($Listitles->current()->status == 2){

  $class="uk-text-danger uk-text-bold uk-text-line-through";
  $print='1';

  }else{

  $class="uk-text-muted ";
  $print='1';

  }



?>

      <tr style="line-height:22px;" class="<?php echo $class; ?>">
        <th class="uk-width uk-text-center" style="width:20px;"><?php echo $linha; ?></th>
        <td class="uk-width uk-text-center" style="width:90px;"><?php echo $Listitles->current()->id; ?> </th>
        <td class="uk-width uk-text-center" style="width:90px;"><?php echo $Listitles->current()->nosso_numero."-".$Listitles->current()->dv_nosso_numero; ?></td>
        <td class="uk-text-left" style="text-transform: uppercase;"><?php echo utf8_encode($Listitles->current()->sacado); ?></td>
        <td class="uk-width uk-text-center" style="width:120px;" ><?php echo $dt_e->format('d/m/Y'); ?></td>
        <td class="uk-width uk-text-center " style="width:120px;" ><?php echo $dt_v->format('d/m/Y'); ?></td>
        <td class="uk-width uk-text-center" style="width:120px;" ><?php if($Listitles->current()->dt_pagamento == ""){echo "00/00/0000";}else{echo $dt_p->format('d/m/Y');} ?></td>
        <td class="uk-width uk-text-center" style="width:120px;" ><?php echo number_format($Listitles->current()->vlr_nominal,2,",","."); ?></td>
        <td class="uk-width uk-text-center" style="width:120px;" ><?php echo number_format($Listitles->current()->vlr_pago,2,",","."); ?></td>
        <td class="uk-width uk-text-center" style="width:80px;" ><img src="imagens/icon_bancos/<?php echo $Listitles->current()->cod_banco; ?>.png" alt="" width="22" height="15"></td>
        <td class="uk-width uk-text-center" style="width:120px; vertical-align: middle;" >


          <div class="uk-coment-action" style="font-weight: normal; text-align: left; z-index: 10000; position: relative; margin: -20px 25px;">
          <div class="uk-button-group">
          <div data-uk-dropdown="{pos:'left-center',mode:'click'}">
            <button class=" uk-button uk-button uk-button-large uk-icon-ellipsis-v " style="margin:5px 0; border:0; background:none;"></button>
            <div class="uk-dropdown uk-dropdown-small">
              <ul class="uk-nav uk-nav-dropdown">
                <li><a  onclick="ActionsTitle('edit','<?php echo $Listitles->current()->cod_banco; ?>','<?php echo $Listitles->current()->convenios_id; ?>','<?php echo $dt_ref->format('Y-m-d'); ?>','<?php echo $Listitles->current()->id; ?>');"><i class="uk-icon-edit"></i> Editar/Visualizar</a></li>
                <li><a  onclick="ActionsTitle('print','<?php echo $Listitles->current()->cod_banco; ?>','<?php echo $Listitles->current()->convenios_id; ?>','<?php echo $dt_ref->format('Ymd'); ?>','<?php echo $Listitles->current()->id; ?>');"><i class="uk-icon-print"></i> Imprimir</a></li>
              </ul>
            </div>
          </div>
        </div>
        </div>


        <?php if($print == '0'){ ?>


        <?php } ?>
        </td>
      </tr>
  <?php
  $Listitles->next();
  endwhile;
  ?>
  </tbody>
</table>
</div>

<nav class="uk-navbar " style="bottom: 0; position: absolute; width: 100%">
<span style="float: left; margin: 7px;" class="uk-text-small"><?php echo "Total de Registros ".$items.""; ?></span>
 <ul class="uk-pagination" style="position:relative; margin:3px 0;" data-uk-pagination="{edges:4,items:<?php echo $items; ?>, itemsOnPage:16, currentPage:<?php echo $pagina;?>}"></ul>

</nav>

<script src="framework/uikit-2.24.0/js/components/pagination.min.js"></script>
<script type="text/javascript">


jQuery('[data-uk-pagination]').on('select.uk.pagination', function(e, pageIndex){

    // mensagen de carregamento
 jQuery("#msg_loading").html("Pesquisando ");

 //abre a tela de preload
 modal.show();

   jQuery.ajax({
        async: true,
        url: "assets/relatorios/titulos/ajax_grid_titulos.php",
        type: "post",
        data:'pagina='+(pageIndex+1)+'&'+jQuery("#FrmFiltroTitulos").serialize(),
        success: function(resultado) {
                            //abre a tela de preload
                           jQuery("#Grid_titulos").html(resultado);
                            //abre a tela de preload
              modal.hide();
        },
        error:function (){
          UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
          modal.hide();
          }

      });
});

/* reimpressão de titulo */
function ActionsTitle(action,banco_emissor,convenio_id,referencia,titulos_id){


if(action =='edit'){}


if(action =='print'){

var LeftPosition = (screen.width) ? (screen.width-980)/2 : 0;

window.open('<?php echo $caminho[0]; ?>assets/cobranca/boleto/avulso/'+banco_emissor+'/segundaVia.php?convenio_id='+convenio_id+'&referencia='+referencia+'&t_id='+titulos_id+'','Impressão', 'width=980, height=550, top=100, left='+LeftPosition+', scrollbars=yes, status=no, toolbar=no, location=no, directories=no, menubar=no, resizable=no, fullscreen=no');

}
}




</script>