<?php
require_once"../../sessao.php";
?>


<nav class="uk-navbar uk-gradient-cinza">

	<div class="uk-navbar-content uk-hidden-small uk-form" style="background-color:transparent;" >

	<input type="text" id="vl" autocomplete="off" class="uk-width" style="width:600px;" placeholder="CODIGO | RAZAO SOCIAL | NOME FANTASIA | CNPJ " onkeyup="if ((window.event ? event.keyCode : event.which) == 13) { BuscaEmpresa(); }">

		<a class="uk-icon-search uk-icon-small" style="margin-left:10px;" onclick="BuscaEmpresa();"></a>

	</div>


</nav>

<div id="gridempresas" style="height:444px; overflow-y:auto; margin-top: 2px; padding:5px;">
<span style="width:100%; display:block; text-align:center;">
Carregando ...
</span>
</div><!-- fim gridassociado -->


<script type="text/javascript" >


$("#gridempresas").load('assets/empresa/Veiw_pesquisa_empresas.php');


function BuscaEmpresa(){

<!--inicia o loader -->
modal.show();

	// variaveis recuperadas
	var vl=$("#vl").val();
	var count = vl.length;

// define a mascara par ao CNPJ 08473376000125
if( count > 12){jQuery("#vl").mask("99.999.999/9999-99");}
//define a mascara para data de cadastro
//if(count > 7 && count < 9){jQuery("#vl").mask("99/99/9999");}

			vl=$("#vl").val();

			// envia os dados para o banco
		  	$.post("assets/empresa/Veiw_pesquisa_empresas.php",
			  {acao:1,vl:vl},
	           // Carregamos o resultado acima
				function(resultado){
								$("#gridempresas").html(resultado);//carrega o resulta da requisição na di
								jQuery("#vl").unmask();
								modal.hide();
								});

}



</script>