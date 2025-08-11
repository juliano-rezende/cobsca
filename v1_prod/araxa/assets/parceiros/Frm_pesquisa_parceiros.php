<?php
require_once"../../sessao.php";

?>


<nav class="uk-navbar">

	<div class="uk-navbar-content uk-hidden-small uk-form">

	<input type="text" id="vl" autocomplete="off" class="uk-width" style="width:600px;" placeholder="NOME | RAZAO SOCIAL | NOME FANTASIA" onkeyup="if ((window.event ? event.keyCode : event.which) == 13) { BuscaFornecedor(); }">

		<a class="uk-icon-search uk-icon-small" style="margin-left:10px;" onclick="BuscaParceiro();"></a>

	</div>


</nav>

<div id="gridparceiros" style="height:444px; overflow-y:auto; padding:5px;">
<span style="width:100%; display:block; text-align:center;">
Carregando ...
</span>
</div><!-- fim gridassociado -->


<script type="text/javascript" >


	jQuery("#gridparceiros").load('assets/parceiros/Veiw_pesquisa_parceiros.php');


	function BuscaParceiro(){

	// inicia o loader
	modal.show();

	// variaveis recuperadas
	var vl=$("#vl").val();

		// envia os dados para o banco
		$.post("assets/parceiros/Veiw_pesquisa_parceiros.php",
				{acao:1,vl:vl},
	           // Carregamos o resultado acima
	    function(resultado){
								$("#gridparceiros").html(resultado);//carrega o resulta da requisição na div
								modal.hide();
		});
	}



</script>