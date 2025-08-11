<?php
require_once"../../sessao.php";
?>

<nav class="uk-navbar">
	<div class="uk-navbar-content uk-hidden-small uk-form">
	<input type="text" id="vl" autocomplete="off" class="uk-width" style="width:600px;" placeholder="CODIGO | NOME | CPF " onkeyup="if ((window.event ? event.keyCode : event.which) == 13) { BuscaCliente(); }">
		<a class="uk-icon-search uk-icon-small" style="margin-left:10px;" onclick="BuscaCliente();"></a>
	</div>
</nav>

<div id="gridclientes" style="height:444px; overflow-y:auto; padding:5px;">
<span style="width:100%; display:block; text-align:center;">
Carregando ...
</span>
</div><!-- fim gridassociado -->

<script type="text/javascript" >

$("#gridclientes").load('assets/cliente/Veiw_pesquisa_clientes.php');


function BuscaCliente(){

<!--inicia o loader -->
modal.show();

	// variaveis recuperadas
	var vl=$("#vl").val();
	var count = vl.length;

// define a mascara par ao cpf
if(count > 10 && count < 12){jQuery("#vl").mask("999.999.999-99");}
//define a mascara para data de cadastro
//if(count > 7 && count < 9){jQuery("#vl").mask("99/99/9999");}

			vl=$("#vl").val();

			// envia os dados para o banco
		  	$.post("assets/cliente/Veiw_pesquisa_clientes.php",
			  {acao:1,vl:vl},
	           // Carregamos o resultado acima
				function(resultado){
								$("#gridclientes").html(resultado);//carrega o resulta da requisição na di
								jQuery("#vl").unmask();
								modal.hide();
								});

}



</script>