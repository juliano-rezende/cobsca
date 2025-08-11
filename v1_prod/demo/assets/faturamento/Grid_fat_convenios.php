<?php
require_once"../../sessao.php";

/* definir a url do arquivo para enviar o boleto para impressao */

$proto = strtolower(preg_replace('/[^a-zA-Z]/','',$_SERVER['SERVER_PROTOCOL'])); //pegando só o que for letra
$location = $proto.'://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];

$caminho=explode("assets",$location);

?>
<div class="tabs-spacer" style="display:none;">

<?php
// blibliotecas
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');

// recupera as taxas configuradas na empresa
$tx_encontrada=configs::find_by_empresas_id($COB_Empresa_Id);

// contas bancarias habilitadas a emitir boleto
$Query_contas=contas_bancarias::find_by_sql("SELECT id,nm_conta,cod_banco FROM contas_bancarias WHERE empresas_id='".$COB_Empresa_Id."' AND tp_conta='2' AND status='1' ORDER BY id");
$List_contas= new ArrayIterator($Query_contas);

$FRM_convenio_id		=	isset( $_GET['convenio_id']) 		? $_GET['convenio_id']						: tool::msg_erros("O Campo convenio_id é Obrigatorio.");
$FRM_referencia			=	isset( $_GET['referencia']) 		? $_GET['referencia']							: tool::msg_erros("O Campo referencia é Obrigatorio.");

// recupera os dados do convenio
$queryfatu=faturamentos::find_by_sql("SELECT SQL_CACHE
                                       faturamentos.id,
                                       faturamentos.referencia,
                                       associados.matricula,
                                       associados.nm_associado,
                                       faturamentos.dt_vencimento,
                                       faturamentos.valor,
                                       (SELECT sum(valor) FROM procedimentos WHERE valor > 0 and matricula  = associados.matricula and status='0' and faturamentos_id = faturamentos.id GROUP BY faturamentos_id) as valor_pro
                                    FROM
                                        faturamentos
                                    LEFT JOIN associados ON associados.matricula = faturamentos.matricula
                                    WHERE
                                      faturamentos.convenios_id = '".$FRM_convenio_id."'
                                      AND faturamentos.status = '0'
                                      AND faturamentos.tipo_parcela='M'
                                      AND faturamentos.referencia='".$FRM_referencia."'
                                      AND titulos_bancarios_id='0'
                                      AND associados.status='1'
                                      ORDER BY faturamentos.matricula");

?>
</div>

<style>
#menu-float a{ background-color:transparent;}
.uk-text-warning {
    color: #F90 !important
}
</style>

<div id="menu-float" style="text-align:center;margin:0 800px;top:42px;border:0;background-color:#546e7a;">

    <a id="Btn_fat_4" class="uk-icon-button uk-icon-copy" style="margin-top:2px;text-align:center;" data-uk-modal="{target:'#DadosGeraTitulo'}" data-uk-tooltip="{pos:'left'}" title="Gerar Titulo" data-cached-title="Gerar Titulo" ></a>

</div>

<form class="uk-form uk-form-tab" id="FrmGridFatConvenios" style="padding: 0; margin: 0;">
<nav class="uk-navbar">
<table  class="uk-table" >
  <thead >
    <tr style="line-height: 25px;">
		<th class="uk-width uk-text-left" style="width:20px;" ></th>
        <th class="uk-width  uk-text-left" style="width:60px;"  data-uk-tooltip="" title="Matricula" data-cached-title="Matricula">Mat</th>
		<th class="uk-width  uk-text-left" style="width:270px;"  data-uk-tooltip="" title="Nome do Funcionario" data-cached-title="Nome do Funcionario">Nome Funcionario</th>
		<th class="uk-width uk-text-center" style="width:80px;"  data-uk-tooltip="" title="Codigo da parcela" data-cached-title="Codigo da parcela">Parcela Nº</th>
		<th class="uk-width uk-text-center" style="width:80px;" data-uk-tooltip="" title="Mês de referencia" data-cached-title="Mês de referencia">Referencia</th>
		<th class="uk-width uk-text-center" style="width:90px;" data-uk-tooltip="" title="Data de vencimento" data-cached-title="Data de vencimento">Vencimento</th>
		<th class="uk-width uk-text-center" style="width:90px;" data-uk-tooltip="" title="Valor da parcela" data-cached-title="Valor da parcela" >R$ Parcela</th>
        <th class="uk-width uk-text-center" style="width:90px;" data-uk-tooltip="" title="Valor da procedimentos" data-cached-title="Valor da procedimentos" >R$ Proced</th>
		<th class="uk-text-left"></th>
    </tr>
    </thead>
 </table>
</nav>

<div id="grid_faturamento_convenios" style="height:465px; overflow-y:scroll;">

<table  class="uk-table uk-table-striped uk-table-hover" >
  <tbody >
<?php

// laço que loopa os lançamentos dos convenios  agrupando por data
$listfat= new ArrayIterator($queryfatu);
$lf=1; // linha de titulos

$valor_t_pro   ='0';
$vl_t          ='0';

while($listfat->valid()):

$ref = new ActiveRecord\DateTime($listfat->current()->referencia);
$dtvenc = new ActiveRecord\DateTime($listfat->current()->dt_vencimento);

?>
    <tr>
        <th class="uk-width uk-text-center " style="width:20px;" >
        <?php echo $lf; ?>
        </th>
        <td class="uk-width  uk-text-left uk-text-bold" style="width:60px;" ><?php echo $listfat->current()->matricula; ?></td>
		<td class="uk-width  uk-text-left uk-text-bold" style="width:300px; text-transform: uppercase;" ><?php echo $listfat->current()->nm_associado; ?></td>
		<td class="uk-width uk-text-center" style="width:80px;" ><?php echo tool::CompletaZeros(10,$listfat->current()->id); ?></td>
        <td class="uk-width uk-text-center uk-text-bold" style="width:80px;" ><?php echo tool::Referencia($ref->format('Ymd'),"/"); ?></td>
        <td class="uk-width uk-text-center" style="width:90px;"><?php echo $dtvenc->format('d/m/Y'); ?></td>
        <td class="uk-width uk-text-center uk-text-primary uk-text-bold" style="width:90px;">

           <?php echo number_format($listfat->current()->valor,2,",","."); ?>

        </td>
         <td class="uk-width uk-text-center uk-text-primary uk-text-bold" style="width:90px;">

           <?php echo number_format($listfat->current()->valor_pro,2,",","."); ?>

        </td>
        <td class="uk-text-left">
        	<input type="checkbox" checked="checked" disabled="disabled" style="margin:3px;" class="check_ab" name="check0[]" value="<?php echo $listfat->current()->id; ?>"  />
        </td>

    </tr>
  <?php

$valor_t_pro    +=  $listfat->current()->valor_pro;
$vl_t           +=  $listfat->current()->valor;

  $lf++;
	$listfat->next();
	endwhile;
?>
  </tbody>
  <tfoot>
    <tr style="line-height: 30px;background-color: #f9f9f9;">
        <th class="uk-width  uk-text-left" style="width:300px;" colspan="6"  >Sub-Total</th>
        <th class="uk-width uk-text-center uk-text-warning" style="width:90px;" data-uk-tooltip="" title="Valor da parcela" data-cached-title="Valor da parcela" >
            <?php echo number_format($vl_t,2,',','.'); ?></th>
        <th class="uk-width uk-text-center uk-text-warning" style="width:90px;" >
            <?php echo number_format($valor_t_pro,2,',','.'); ?></th>
        <th class="uk-text-left"></th>
    </tr>
    <tr style="line-height: 30px; background-color: #f5f5f5;">
        <th class="uk-width  uk-text-left" style="width:300px;" colspan="6"  >Sub-Total</th>
        <th class="uk-width uk-text-center uk-text-primary" style="width:90px;" colspan="2" data-uk-tooltip="" title="Valor da parcela" data-cached-title="Valor da parcela" >
            <?php echo number_format($vl_t+$valor_t_pro,2,',','.'); ?></th>
        <th class="uk-text-left"></th>
    </tr>
  </tfoot>
 </table>
</div>

<div id="DadosGeraTitulo" class="uk-modal">
    <div class="uk-modal-dialog" style="width:500px;">
        <!--<button type="button" class="uk-modal-close uk-close"></button> -->
        <div class="uk-modal-header ">
        <h2><i class="uk-icon-bank" ></i> Banco / Vencimento</h2>
        </div>
            <label>
                <span>Banco Emissor</span>
                <select name="conta_bancaria" id="conta_bancaria" class="select">
                    <?php

                    echo'<option value="0" selected="selected">Selecionar banco</option>';

                    while($List_contas->valid()):

                    echo'<option value="'.$List_contas->current()->id.'">'. $List_contas->current()->nm_conta.'</option>';

                    $List_contas->next();

                    endwhile;
                    ?>
                </select>
            </label>
            <label>
              <span>Vencimento</span>
            <input type="text" class="uk-text-center w_120" style="background-color: #fff; color: #000;"  id="dt_venc_titulo_pj" data-uk-datepicker="{format:'DD/MM/YYYY'}"/>
            </label>
            <input type="hidden" readonly="readonly" value="<?php  echo $vl_t+$valor_t_pro; ?>" id="vl_t_title" />

        <div class="uk-modal-footer uk-text-right">
            <a id="Btn_fat_9" class="uk-button uk-button-primary uk-button-small" ><i class="uk-icon-check" ></i> Confirmar</a>
            <a id="BtnCancelarFiltro" class="uk-button uk-button-danger uk-button-small uk-modal-close" ><i class="uk-icon-remove" ></i> Cancelar</a>
        </div>
    </div>
</div>
</form>
<script type="text/javascript" >

jQuery(document).ready(function(){

  /* mascara no campo*/
jQuery("#dt_venc_titulo_pj").mask("99/99/9999");

jQuery("#menu-float").css("background-color",""+jQuery("#"+jQuery("#menu-float").closest('.Window').attr('id')+"").css('border-left-color')+"");// define a cor do menu lateral
});

/*
    pega todas as parcelas selecionadas para geraçao do carne
*/
jQuery(function(){

    jQuery("#Btn_fat_9").click(function(event) {

        /* mensagen de carregamento*/
        jQuery("#msg_loading").html(" Aguarde...");

        //abre a tela de preload*/
        modal.show();

        /*desabilita o envento padrao do formulario*/
        event.preventDefault();

        /* array com os valores dos checks*/
        var ids = [];
        /* verifica qual está marcado dentro da grid faturamento apenas*/
        jQuery("#grid_faturamento_convenios input[type=checkbox]").each(function(){

                if (this.checked) {
                    ids.push(jQuery(this).val());/* retorna o valor do campo marcado*/
                }
        });
        jQuery.ajax({
                   async: true,
                    url: "assets/faturamento/controllers/Controller_titulo_pj.php",
                    type: "POST",
                    data: "convenio_id=<?php echo $FRM_convenio_id; ?>&ids="+ids+"&valor="+jQuery("#vl_t_title").val()+"&vencimento="+jQuery("#dt_venc_titulo_pj").val()+"&conta_bancaria_id="+jQuery("#conta_bancaria").val()+"",
                    success: function(resultado) {


                        var text = '{"'+resultado+'"}';
                        var obj = JSON.parse(text);

                        /* se o callback for 0 indica que não houve erro ai mostramos o resultado da execução das querys*/
                        if(obj.callback == 1){

                            UIkit.modal.alert(""+obj.msg+"");
                            modal.hide();

                        /* se for = 1 indica que houve erro ai retornamo o erro na tela do usuario*/
                        }else{

                            UIkit.notify(''+obj.msg+'', {timeout: 1000,status:''+obj.status+''});
                            modal.hide();

/*window.open('<?php echo $caminho[0]; ?>assets/cobranca/boleto/avulso/'+obj.banco_emissor+'/veiw_bol.php?convenio_id=<?php echo $FRM_convenio_id; ?>&referencia=<?php echo $FRM_referencia; ?>&t_id='+obj.titulos_id+'');*/

 var LeftPosition = (screen.width) ? (screen.width-980)/2 : 0;

window.open('<?php echo $caminho[0]; ?>assets/cobranca/boleto/avulso/'+obj.banco_emissor+'/veiw_pdf.php?convenio_id=<?php echo $FRM_convenio_id; ?>&referencia=<?php echo tool::LimpaString($FRM_referencia); ?>&t_id='+obj.titulos_id+'','Impressão', 'width=980, height=550, top=100, left='+LeftPosition+', scrollbars=yes, status=no, toolbar=no, location=no, directories=no, menubar=no, resizable=no, fullscreen=no');


                            //New_window('list','980','550','Titulos','assets/cobranca/boleto/avulso/'+obj.banco_emissor+'/veiw_bol.php?t_id='+obj.titulos_id+'',true,false,'Carregando...');

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