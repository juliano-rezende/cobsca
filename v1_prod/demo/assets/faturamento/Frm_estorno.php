<?php
require_once"../../sessao.php";

include("../../conexao.php");
$cfg->set_model_directory('../../models/');

$FRM_ids		  =	isset( $_GET['ids']) 		  ? $_GET['ids']		: tool::msg_erros("Campo informado invalido.");
$FRM_matricula    = isset( $_GET['mat'])          ? $_GET['mat']        : tool::msg_erros("Campo matricula faltando.");
$FRM_convenio_id  = isset( $_GET['convenio_id'])  ? $_GET['convenio_id']: tool::msg_erros("Campo convenios_id faltando.");


//define as variaveis vazias
$parcelas       = explode(',' ,$FRM_ids);
$t_parcelas     =count($parcelas);
$vl_t_parcelas  ="";
$vl_pago        ="";
$conta_deposito ="";



//formas de recebimento do sistema
$Query_freceb=formas_recebimentos::find_by_sql("SELECT
                                              formas_recebimentos.id AS f_receb_emp_id,
                                              formas_recebimento_sys.descricao,
                                              formas_recebimentos.acrescimos,
                                              formas_recebimento_sys.id as f_recebe_sys_id
                                            FROM
                                              formas_recebimentos
                                              INNER JOIN formas_recebimento_sys ON formas_recebimento_sys.id =
                                            formas_recebimentos.formas_recebimento_sys_id
                                            WHERE
                                              formas_recebimentos.status = 1;");
$formas= new ArrayIterator($Query_freceb);


foreach ($parcelas as $id){                                     //   faz um loop usando foreach e recupera os valores

// recupera ps dados da parcela
$Query_parcela=faturamentos::find($id);

$vl_t_parcelas  += $Query_parcela->valor;                       // valor total das parcelas
$vl_pago        += $Query_parcela->valor_pago;                  // valor pago
$conta_deposito  = $Query_parcela->contas_bancarias_id;         // valor pago

}

echo'</div>';

?>
<form method="post" id="Frmestorno" class="uk-form" style="padding-top:0;margin:0;">

<fieldset style="width:300px; background-color:transparent;">

    <label>
        <span>Qte Parcelas</span>
        <input name="quantparcelas" type="text" class=" w_150 uk-text-center"  id="quantparcelas" value="<?php echo $t_parcelas ; ?>" readonly="readonly" />
    </label>
    <label>
        <span>Vl Parcelas</span>
        <div class="uk-form-icon">
        <i class="uk-icon-money uk-text-success"></i>
        <input type="text" class=" w_150 uk-text-center"   id="vl_nominal" value="<?php echo number_format($vl_t_parcelas,2,",",".") ; ?>" readonly="readonly"  />
        </div>
    </label>

     <label>
        <span>Total pago</span>
        <div class="uk-form-icon">
        <i class="uk-icon-check uk-text-primary"></i>
        <input  type="text" id="vl_pago" class=" w_150 uk-text-center uk-text-primary"  value="<?php echo number_format($vl_pago,2,",","."); ?>" readonly="readonly" />
        </div>
    </label>

  	<label >
    <span>Forma Receb</span>
        <select name="f_pgto" id="f_pgto" class="select w_150">
        <option value="0;0;0" selected>Selecionar</option>
        <?php
            while($formas->valid()):
            echo'<option value="'.$formas->current()->f_receb_emp_id.'" >'.utf8_encode(strtoupper($formas->current()->descricao)).'</option>';
            $formas->next();
            endwhile;
        ?>
        </select>
    </label>

</fieldset>
</form>

    <a  id="Btn_est_0" class="uk-button" style=" bottom:30px; position:absolute;  right:15px;" data-uk-tooltip="{pos:'left'}" title="Receber" data-cached-title="Receber" ><i class="uk-icon-reply"></i> Confirmar </a>


<script type="text/javascript">

// seta o placeholder em todos os selects do forumlario
jQuery(function($) {
      //function for placeholder select
      function selectPlaceholder(selectID){
        var selected = jQuery(selectID + ' option:selected');
        var val = selected.val();
        jQuery(selectID + ' option' ).css('color', '#333');
        selected.css('color', '#999');
        if (val == "") {
          jQuery(selectID).css('color', '#999');
        };
        jQuery(selectID).change(function(){
          var val = jQuery(selectID + ' option:selected' ).val();
          if (val == "") {
            jQuery(selectID).css('color', '#999');
          }else{
            jQuery(selectID).css('color', '#333');
          };
        });
      };
      selectPlaceholder('.select');
});



// envia os dados para gerar os boleto ou receber
jQuery(function(){

    jQuery("#Btn_est_0").click(function(event) {


        // mensagen de carregamento
        jQuery("#msg_loading").html(" Estornando...");

        //abre a tela de preload
        modal.show();

        //desabilita o envento padrao do formulario
        event.preventDefault();

        var data="mat=<?php echo $FRM_matricula; ?>&ids=<?php echo $FRM_ids; ?>&vl_pgto="+jQuery("#vl_pago").val()+"&f_pgto_id="+ jQuery("#f_pgto").val()+"";

        jQuery.ajax({
                   async: true,
                    url: "assets/faturamento/controllers/Controller_estorno.php",
                    type: "POST",
                    data: data,
                    success: function(resultado) {

                        var text = '{"'+resultado+'"}';
                        var obj = JSON.parse(text);

                        // se o callback for 0 indica que não houve erro ai mostramos o resultado da execução das querys
                        if(obj.callback == 1){

                            New_window('exclamation-triangle','500','250','Atenção','<div style="padding: 5px; width:490px; height:240px; overflow-x:auto;">'+obj.msg +'</div>',true,true,'Aguarde...');
                            modal.hide();

                        // se for = 1 indica que houve erro ai retornamo o erro na tela do usuario
                        }else{

                            UIkit.notify(''+obj.msg+'', {timeout: 2000,status:''+obj.status+''});
                            // /* id da janela dados de cobranca Frmdadoscobranca*/
                            jQuery("#"+jQuery("#Frmestorno").closest('.Window').attr('id')+"").remove();
                            jQuery("#"+jQuery("#grid_faturamento").closest('.Window').attr('id')+"").remove();
                            New_window('list','950','500','Faturamento','assets/faturamento/Frm_faturamento.php?matricula=<?php echo $FRM_matricula; ?>&convenio_id=<?php echo $FRM_convenio_id; ?>',true,false,'Carregando...');

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

