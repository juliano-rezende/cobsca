<?php require_once"../../sessao.php";?>

<nav class="uk-navbar uk-gradient-cinza">
	<div class="uk-navbar-content uk-hidden-small uk-form" >
	<input type="search" id="vl" autocomplete="off" class="uk-width" style="width:600px;" placeholder="CODIGO | NOME | CPF " onkeyup="if ((window.event ? event.keyCode : event.which) == 13) { BuscaVendedor(); }">
		<a class="uk-icon-search uk-icon-small" style="margin-left:10px;" onclick="BuscaVendedor();"></a>
	</div>
</nav>

<div id="gridvendedor" style="height:444px; overflow-y:auto; padding:5px; margin-top: 2px; ">
<span style="width:100%; display:block; text-align:center;">
Carregando ...
</span>
</div><!-- fim gridassociado -->


<script type="text/javascript" >

$("#gridvendedor").load('assets/vendedor/Veiw_search_vendedor.php');

function BuscaVendedor(){

<!--inicia o loader -->
modal.show();

	/*variaveis recuperadas*/
	var vl=$("#vl").val();
	var tp= "0";/*$("input[name='tp']:checked").val();*/

	// pesquisa na tabela associados
	if(tp == 0){

			// envia os dados para o banco
		  	$.post("assets/assets/vendedor/Veiw_search_vendedor.php",
			  {acao:1,vl:vl},
	           /* Carregamos o resultado acima*/
				function(resultado){
								$("#gridvendedor").html(resultado);/*carrega o resulta da requisição na div*/
								modal.hide();
								});
	}else{
			/* envia os dados para o banco */
		  	$.post("assets/assets/vendedor/Veiw_search_vendedor.php",
			  {vl:vl},
	           /*Carregamos o resultado acima*/
				function(resultado){
								$("#gridvendedor").html(resultado);/*carrega o resulta da requisição na div*/
								modal.hide();
								});

	}
}



</script>