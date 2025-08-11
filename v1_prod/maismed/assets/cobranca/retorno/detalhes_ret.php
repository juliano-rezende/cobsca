<?php
require_once("../../../sessao.php");
?>
<div class="tabs-spacer" style="display:none;">

<?php
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

$FRM_cod_retorno        = isset( $_GET['cod_retorno'])        ? $_GET['cod_retorno'] : tool::msg_erros("O Campo cod_retorno é Obrigatorio.");
$FRM_contas_bancarias_id= isset( $_GET['conta_bancaria_id'])  ? $_GET['conta_bancaria_id'] : tool::msg_erros("O Campo conta_bancaria_id é Obrigatorio.");


// retorna todo os registros para montar a paginação
$Query_all=titulos::find_by_sql("SELECT faturamentos.matricula,titulos_bancarios.*,
                                (SELECT cod_banco FROM retornos_bancarios WHERE lote_retorno = '".$FRM_cod_retorno."' AND contas_bancarias_id='".$FRM_contas_bancarias_id."' group by lote_retorno) as cod_banco,
                                (SELECT tipo_arquivo FROM contas_bancarias_cobs WHERE contas_bancarias_id = '".$FRM_contas_bancarias_id."' ) as tipo_arquivo
                                FROM titulos_bancarios
                                RIGHT JOIN faturamentos ON (faturamentos.titulos_bancarios_id = titulos_bancarios.id OR faturamentos.nossonumero = titulos_bancarios.id)
                                WHERE titulos_bancarios.cod_retorno='".$FRM_cod_retorno."' AND  titulos_bancarios.contas_bancarias_id='".$FRM_contas_bancarias_id."' group by nosso_numero ORDER BY sacado ASC");


$List_detalhes= new ArrayIterator($Query_all);

?>
</div>

</style>
<div style="width: 100%; overflow: scroll; height: 515px;">
<nav class="uk-navbar" style="width:1300px;">
<table  class="uk-table">
    <thead >
      <tr style="line-height:25px;">
        <th class="uk-width uk-text-center" style="width:30px;" ></th>
        <th class="uk-width uk-text-center" style="width:40px;" ></th>
        <th class="uk-width uk-text-center" style="width:90px;" >Matricula</th>
        <th class="uk-width uk-text-left"   style="width:220px;">Obs</th>
        <th class="uk-width uk-text-left"   style="width:200px;">Sacado</th>
        <th class="uk-width uk-text-center" style="width:110px;" >Dt Emissão</th>
        <th class="uk-width uk-text-center" style="width:110px;" >Dt Atualização</th>
        <th class="uk-width uk-text-center" style="width:110px;" >Dt Vencimento</th>
        <th class="uk-width uk-text-center" style="width:90px;" >Valor</th>
        <th class="uk-width uk-text-center" style="width:90px;" >Tarifa</th>
        <th class="uk-width uk-text-center" style="width:100px;" >Nosso Numero</th>
      </tr>
    </thead>
 </table>
</nav>
<table  class="uk-table uk-table-striped uk-table-hover" style="width:1300px;">
<tbody>
<?php
$linha=0;
$baixados=0;
$pagos=0;
$cancelados=0;
$total=0;

while($List_detalhes->valid()):

$linha++;
$dt_emissao  = new ActiveRecord\DateTime($List_detalhes->current()->dt_emissao);
$dt_atualização  = new ActiveRecord\DateTime($List_detalhes->current()->dt_atualizacao);
$dt_venc  = new ActiveRecord\DateTime($List_detalhes->current()->dt_vencimento);



if($List_detalhes->current()->status == 0){

    if($List_detalhes->current()->cod_rej1 > 0 or $List_detalhes->current()->cod_rej2 > 0 or $List_detalhes->current()->cod_rej3 > 0) {

      $color_error="background-color: #f90;color:#fff;";

    }else{$color_error="";}

}elseif($List_detalhes->current()->status == 2){

$color_error="background-color: #f00;color:#fff;";

}else{$color_error="";}



?>

      <tr style="line-height:23px;<?php echo $color_error; ?>" id="tr_<?php echo $List_detalhes->current()->id; ?>" >
       <th class="uk-width uk-text-center" style="width:30px;" ><?php echo $linha; ?></th>
        <td class="uk-width uk-text-center" style="width:40px;">
<?php if($List_detalhes->current()->status != 2){ ?>
          <div class="uk-coment-action">
                <div class="uk-button-group">
          <div data-uk-dropdown="{pos:'bottom-left',mode:'click'}">
            <button class=" uk-button uk-button uk-button-large uk-icon-ellipsis-v " style="margin:5px 0; border:0; background:none;"></button>
            <div class="uk-dropdown uk-dropdown-small" >
              <ul class="uk-nav uk-nav-dropdown uk-text-left">
              <?php if($List_detalhes->current()->cod_rej1 > 0 or
                       $List_detalhes->current()->cod_rej2 > 0 or
                       $List_detalhes->current()->cod_rej3 > 0)
              {?>

              <?php if($List_detalhes->current()->cod_rej1 > 0){?>
              <li>
                <a  onclick="Tab_rej('<?php echo $List_detalhes->current()->cod_rej1; ?>','<?php echo $List_detalhes->current()->cod_banco."_".$List_detalhes->current()->tipo_arquivo;?>');"><i class="uk-icon-warning"></i> 1º rejeição </a>
              </li>
              <?php } ?>
              <?php if($List_detalhes->current()->cod_rej2 > 0){?>
              <li>
                <a  onclick="Tab_rej('<?php echo $List_detalhes->current()->cod_rej2; ?>','<?php echo $List_detalhes->current()->cod_banco."_".$List_detalhes->current()->tipo_arquivo;?>');"><i class="uk-icon-warning"></i> 2º rejeição </a>
              </li>
              <?php } ?>
              <?php if($List_detalhes->current()->cod_rej3 > 0){?>
              <li>
                <a  onclick="Tab_rej('<?php echo $List_detalhes->current()->cod_rej3; ?>','<?php echo $List_detalhes->current()->cod_banco."_".$List_detalhes->current()->tipo_arquivo;?>');"><i class="uk-icon-warning"></i> 3º rejeição</a>
              </li>
              <?php } ?>
              <li><a  onclick="Reenviar('<?php echo $List_detalhes->current()->id; ?>','tr_<?php echo $List_detalhes->current()->id; ?>','<?php echo $List_detalhes->current()->cod_banco; ?>');"><i class="uk-icon-refresh"></i> Reenviar</a></li>
              <li><a  onclick="Ignorar('<?php echo $List_detalhes->current()->id; ?>','tr_<?php echo $List_detalhes->current()->id; ?>');"><i class="uk-icon-reply"></i> Ignorar</a></li>
              <li><a  onclick="EditVeiwTittle('<?php echo $List_detalhes->current()->id; ?>');"><i class="uk-icon-edit"></i> Editar</a></li>
              <?php }else{ ?>
              <li><a  onclick="EditVeiwTittle('<?php echo $List_detalhes->current()->id; ?>');"><i class="uk-icon-eye"></i> Visualizar</a></li>
<?php } ?>
              </ul>
            </div>
          </div>
          </div>
          </div>
      <?php } ?>
        </td>
        <td class="uk-width uk-text-center " style="width:90px;" ><?php echo $List_detalhes->current()->matricula; ?></td>
        <td class="uk-width uk-text-left uk-text-overflow uk-text-uppercase " style="width:220px;max-width: 220px; " ><?php if($List_detalhes->current()->status == 2){echo "Titulo cancelado pelo beneficiario.";}else{ echo $List_detalhes->current()->obs;} ?></td>
        <td class="uk-width uk-text-left uk-text-overflow uk-text-uppercase" style="width:200px;max-width: 200px;" ><?php echo $List_detalhes->current()->sacado; ?></td>
        <td class="uk-width uk-text-center" style="width:110px;" ><?php echo $dt_emissao->format('d/m/Y'); ?></td>
        <td class="uk-width uk-text-center" style="width:110px;" ><?php echo $dt_atualização->format('d/m/Y'); ?></td>
        <td class="uk-width uk-text-center" style="width:110px;" ><?php echo $dt_venc->format('d/m/Y'); ?></td>
        <td class="uk-width uk-text-center" style="width:90px;"><?php echo number_format($List_detalhes->current()->vlr_nominal,2,',','.'); ?></td>
        <td class="uk-width uk-text-center" style="width:90px;"><?php echo number_format($List_detalhes->current()->vlr_tarifa,2,',','.'); ?></td>
        <td class="uk-width uk-text-center" style="width:100px;"><?php echo $List_detalhes->current()->nosso_numero."-".$List_detalhes->current()->dv_nosso_numero; ?></td>
       </tr>

<?php



if($List_detalhes->current()->status == 1){
  $pagos+=$List_detalhes->current()->vlr_nominal;
}elseif($List_detalhes->current()->status == 2){
  $cancelados+=$List_detalhes->current()->vlr_nominal;
}elseif($List_detalhes->current()->status == 3){
  $baixados+=$List_detalhes->current()->vlr_nominal;
}

$total+=$List_detalhes->current()->vlr_nominal;

$List_detalhes->next();
endwhile;
?>



</tbody>
</table>

<nav class="uk-navbar" >
<table  class="uk-table">
<tbody>
  <tr style="line-height:25px;">
  <td class="uk-text-center" colspan="11"  >Totalizadora</td>
</tr>
<tr style="line-height:25px;" class="uk-text-bold">
  <td class="uk-width uk-text-left" style="width:100px;" >Baixados</td>
  <td class="uk-width uk-text-left uk-text-warning" style="width:110px;"   >R$ <?php echo number_format($baixados,2,',','.'); ?></td>
  <td class="uk-width uk-text-left" style="width:100px;" >Pagos</td>
  <td class="uk-width uk-text-left uk-text-success" style="width:110px;"  >R$ <?php echo number_format($pagos,2,',','.'); ?></td>
  <td class="uk-width uk-text-left" style="width:100px;" >Cancelados</td>
  <td class="uk-width uk-text-left uk-text-danger" style="width:110px;" >R$ <?php echo number_format($cancelados,2,',','.'); ?></td>
  <td class="uk-width uk-text-left" style="width:100px;" >Total</td>
  <td class="uk-width uk-text-left uk-text-primary" style="width:110px;"  >R$ <?php echo number_format($total,2,',','.'); ?></td>
</tr>
</tbody>
</table>
</nav>

</div>
<script type="text/javascript">

function Tab_rej(cod_rej,tab_rej){
  New_window('list','600','500','Tabela de rejeição','assets/cobranca/retorno/tab_rejeicao/tab_rej_'+tab_rej+'.php?cod_rej='+cod_rej+'',true,false,'Carregando...');
}

function EditVeiwTittle(id){ New_window('list','650','500','Edição','assets/cobranca/retorno/Frm_detalhes_tt.php?t_id='+id+'',true,false,'Carregando...');
}

function Reenviar(id,target){

/* mensagen de carregamento*/
jQuery("#msg_loading").html(" Aguarde...");

//abre a tela de preload*/
modal.show();

 jQuery.ajax({
                   async: true,
                    url: "assets/cobranca/retorno/controllers/Controller_titulo.php",
                    type: "POST",
                    data: "action=reenviar&id="+id+"",
                    success: function(resultado) {

                        var text = '{"'+resultado+'"}';
                        var obj = JSON.parse(text);

                        /* se o callback for 1 indica que houve erro ai mostramos o resultado da execução das querys*/
                        if(obj.callback == 1){

                            UIkit.modal.alert(""+obj.msg+"");
                            modal.hide();

                        /* se for = 0 indica que houve erro ai retornamo o erro na tela do usuario*/
                        }else{

                            UIkit.notify(''+obj.msg+'', {timeout: 1000,status:''+obj.status+''});
                            jQuery("#"+target+"").remove();
                            modal.hide();

                        }
                    },
                    error:function (){

                        UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
                        modal.hide();

                    }
                    });
}


