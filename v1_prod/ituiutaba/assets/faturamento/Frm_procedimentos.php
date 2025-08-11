<?php
require_once"../../sessao.php";

// blibliotecas
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');

$FRM_faturamento_id	= isset( $_GET['ids']) 	? $_GET['ids']	: tool::msg_erros("O Campo codigo do faturamento é Obrigatorio.");
$FRM_matricula      = isset( $_GET['mat'])  ? $_GET['mat']   : tool::msg_erros("O Campo matricula é Obrigatorio.");


?>

<br />
<form method="post" id="FrmProcedimentos" class="uk-form" style="margin: 0; padding: 0;">


<label>
<span>Matricula</span>
<input  type="text" class="uk-text-center w_120" readonly="readonly"  id="mat" name="mat" value="<?php echo tool::CompletaZeros(10,$FRM_matricula);?>" />
</label>

<div style="height:30px; width:260px; position:absolute; top:54px; right:0px; text-align:center;">
<label>
<span>Cod Faturamento</span>
<input  type="text" class="uk-text-center w_120" readonly="readonly"  id="id_fat" name="id_fat" value="<?php echo tool::CompletaZeros(10,$FRM_faturamento_id);?>" />
</label>
</div>

<label>
     <span>Dt Lançamento</span>
    <div class="uk-form-icon">
    <i class="uk-icon-calendar "></i>
    <input  type="text" class="uk-text-center w_120" readonly="readonly"  id="dt_lanc" name="dt_lanc" value="<?php echo date("d/m/Y");?>" />
    </div>
</label>

<div style="height:30px; width:260px; position:absolute; top:89px; right:0px; text-align:center;">

<label>
     <span>1º vencimento</span>
    <div class="uk-form-icon">
    <i class="uk-icon-calendar "></i>
        <input  type="text" class="uk-text-center w_120"   id="dt_venc" name="dt_venc" placeholder="00/00/0000" data-uk-datepicker="{format:'DD/MM/YYYY'}" />
    </div>
</label>
</div>

<label>
     <span>Vlr Procedimento</span>
    <div class="uk-form-icon">
    <i class="uk-icon-money "></i>
    <input  type="text" class=" w_120"  id="vlr_proc" name="vlr_proc" style="bcolor: #000;" placeholder="0,00"   />
    </div>
</label>

<div style="height:30px; width:260px; position:absolute; top:124px; right:0px; text-align:center;">
<label>
    <span>Qte Parcelas</span>
    <input type="number" id="qte_parcelas" name="qte_parcelas" min="0" max="12" value="1" class="w_120">
</label>

</div>
<hr class="uk-article-divider">
<label>
     <span>Nome paciente</span>
    <div class="uk-form-icon">
    <i class="uk-icon-user "></i>
    <input type="text" class="uk-text-left uk_width" style="color: #000;width:450px;" id="nm_paciente" name="nm_paciente"  />
    </div>
</label>

<label>
<span>Historico</span>
<input type="text" class="uk-text-left uk_width" style="color: #000;width:450px;" id="historico" name="historico"  />
</label>

<label>
<span>Observações</span>
<textarea id="obs" name="obs" style="height: 120px; width: 450px; color: #000;" id="historico" ></textarea>
</label>

<a  id="Btn_proc_0" class="uk-button uk-button-primary" style=" bottom:30px; position:absolute;  right:15px;line-height: 40px;" data-uk-tooltip="{pos:'left'}" title="Confirmar lançamento" data-cached-title="Confirmar lançamento" ><i class="uk-icon-check"></i> Confirmar </a>


</form>
<script type="text/javascript">


jQuery(document).ready(function(){

jQuery("#dt_venc").mask("99/99/9999");
jQuery("#vlr_proc").maskMoney({showSymbol:true, symbol:"", decimal:",", thousands:"."});

});// fim document ready


/* envia os dados para o controlher*/

// grava os dados no banco de dados
jQuery(function() {

 jQuery("#Btn_proc_0").click(function(event) {

 // mensagen de carregamento
 jQuery("#msg_loading").html(" Aguarde ... ");

 //abre a tela de preload
 modal.show();

 //desabilita o envento padrao do formulario
 event.preventDefault();


var data="action=insert&"+jQuery("#FrmProcedimentos").serialize();


 jQuery.ajax({
                async: true,
                url: "assets/faturamento/controllers/Controller_procedimento.php",
                type: "post",
                data:data,
                success: function(resultado) {
                                                var text = '{"'+resultado+'"}';
                                                var obj = JSON.parse(text);

                                                /* se o callback for 1 indica que não houve erro ai mostramos o resultado da execução das querys*/
                                                if(obj.callback == 1){

                                                    UIkit.modal.alert(""+obj.msg+"");
                                                    modal.hide();

                                                /* se for = 0 indica que houve erro ai retornamo o erro na tela do usuario*/
                                                }else{

                                                    UIkit.notify(''+obj.msg+'', {timeout: 1000,status:''+obj.status+''});
                                                    modal.hide();
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