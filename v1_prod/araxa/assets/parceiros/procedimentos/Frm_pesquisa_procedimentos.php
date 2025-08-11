<?php
require_once"../../../sessao.php";

$FRM_esp_id         = isset( $_GET['esp_id'])    ? $_GET['esp_id']            : tool::msg_erros("O Campo especialidade id é Obrigatorio.");
$FRM_parceiro_id    = isset( $_GET['par_id'])    ? $_GET['par_id']            : tool::msg_erros("O Campo parceiro id é Obrigatorio.");

?>

<nav class="uk-navbar uk-gradient-cinza">
	<div class="uk-navbar-content uk-hidden-small uk-form" >
	<input type="search" id="vl" autocomplete="off" class="uk-width" style="width:500px;" placeholder="PROCEDIMENTO" >

		<a class="uk-icon-search uk-icon-small" style="margin-left:10px;" id="Btn_search_pro"></a>
	</div>
</nav>
<style>
#menu-float a{ background-color:transparent;}
</style>

<div id="menu-float" style="text-align:center;margin:0 800px;top:35px;border:0;background-color:#546e7a;">

    <a class="uk-icon-button uk-icon-plus" id="Btn_MedProc_001" style="margin-top:2px;text-align:center;"  data-uk-tooltip="{pos:'left'}" title="Adcionar Novo Procedimento" data-cached-title="Adcionar Novo Procedimento" ></a>

</div>

<div id="GridProcedimentos" style="height:344px; overflow-y:auto; padding:5px; margin-top: 2px; ">
<span style="width:100%; display:block; text-align:center;">
Carregando ...
</span>
</div><!-- fim gridassociado -->

<script type="text/javascript" >

jQuery(document).ready(function(){

    jQuery("#menu-float").css("background-color",""+jQuery("#"+jQuery("#menu-float").closest('.Window').attr('id')+"").css('border-left-color')+"");// define a cor do menu lateral

});

jQuery("#GridProcedimentos").load('assets/parceiros/procedimentos/Grid_procedimentos.php?par_id=<?php echo $FRM_parceiro_id; ?>&esp_id=<?php echo $FRM_esp_id; ?>');


 // grava os dados no banco de dados
jQuery(function() {

 jQuery("#Btn_search_pro").click(function(event) {

 var vl = jQuery("#vl").val();

 // mensagen de carregamento
 jQuery("#msg_loading").html(" Aguarde ... ");

 //abre a tela de preload
 modal.show();

 //desabilita o envento padrao do formulario
 event.preventDefault();

 jQuery.ajax({
				async: true,
				url: "assets/parceiros/procedimentos/Grid_procedimentos.php",
				type: "post",
				data:"acao=1&vl="+vl+"&par_id=<?php echo $FRM_parceiro_id; ?>&esp_id=<?php echo $FRM_esp_id; ?>",
				success: function(resultado) {
					
					jQuery("#GridProcedimentos").html(resultado);//carrega o resulta da requisição na div
					modal.hide();
				},
				error:function (){s
					UIkit.modal.alert("Erro ao enviar dados! Erro 404");/*erro de caminho invalido do arquivo*/
					modal.hide();
					}
			});
		});
});


//Abre uma janela para adcionar especialidade
jQuery("#Btn_MedProc_001").click(function(){

        // recarrega a pagina
        jQuery("#"+jQuery("#FrmProc").closest('.Window').attr('id')+"").remove();// remove a janela atual contendo o formulario procedimentos para abrir novamente com a nova chamada
        New_window('plus','700','250','Adcionar Procedimentos','assets/parceiros/procedimentos/Frm_procedimentos.php?par_id=<?php echo $FRM_parceiro_id; ?>&esp_id=<?php echo $FRM_esp_id; ?>',true,false,'Carregando...');

});


</script>