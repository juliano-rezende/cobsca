<?php
require_once"../../../sessao.php";
?>


<nav class="uk-navbar uk-gradient-cinza">
	<div class="uk-navbar-content uk-hidden-small uk-form" >
	<input type="search" id="search" autocomplete="off" class="uk-width uk-text-center" style="width:460px;" maxlength="60"  onkeyup="if ((window.event ? event.keyCode : event.which) == 13) { BuscaTitulo(); }" >
		<label style="margin:2px 10px;">
	        <input type="radio" name="tp" value="0" onclick="Mascara(0);" class="tp_search" checked> SACADO
	        <input type="radio" name="tp" value="1" onclick="Mascara(1);" class="tp_search"> CPF
	        <input type="radio" name="tp" value="2" onclick="Mascara(2);" class="tp_search"> CNPJ
	        <input type="radio" name="tp" value="3" onclick="Mascara(3);" class="tp_search"> LINHA DIGITAVEL
	        <input type="radio" name="tp" value="4" onclick="Mascara(4);" class="tp_search"> NOSSO NUMERO
        </label>
		<a class="uk-icon-search uk-icon-small" style="margin-left:10px;" onclick="BuscaTitulo();"></a>
	</div>
</nav>


<nav class="uk-navbar ">
<table  class="uk-table" >
  <thead >
    <tr style="line-height:25px;">
      <th class="uk-width uk-text-center" style="width:20px"></th>
        <th class="uk-width uk-text-center" style="width:90px;" >Nº Titulo</th>
        <th class="uk-width uk-text-center" style="width:90px;" >Matricula</th>
        <th class="uk-text-left" >Sacado</th>
        <th class="uk-width uk-text-center" style="width:120px;" >Data Emissao</th>
        <th class="uk-width uk-text-center" style="width:120px;" >Data Vencimento</th>
        <th class="uk-width uk-text-center" style="width:120px;" >vlr nominal</th>
        <th class="uk-width uk-text-center" style="width:120px;" >Vlr a pagar/pago</th>
        <th class="uk-width uk-text-center" style="width:80px;" >Banco</th>

    </tr>
    </thead>
 </table>
</nav>
<div id="Grid_titulos" style="height:440px; overflow-y:auto; ">
<span style="width:100%; display:block; text-align:center;">
Aguardando ...
</span>
</div>

<script type="text/javascript" >



function BuscaTitulo(){


// variaveis recuperadas
var vl=jQuery("#search").val();
var tp= jQuery("input[name='tp']:checked").val();

// inicia o loader
modal.show();

			// envia os dados para o banco
		  	$.post("assets/cobranca/baixa_titulo/Veiw_search_titulos.php",
			  {tp:tp,vl:vl},
	           // Carregamos o resultado acima
				function(resultado){
								$("#Grid_titulos").html(resultado);//carrega o resulta da requisição na div
								modal.hide();
								});

}

$('#search').click(function() {
    if (! $("input[type='radio'][name='tp']").is(':checked') ){
		 UIkit.modal.alert("Selecione o tipo de pesquisa!");/*erro de caminho invalido do arquivo*/
    }
  });



 function Mascara(val){

if(val == 0){jQuery("#search").val("").attr("maxlength","60").unmask().focus();}
	if(val == 1){jQuery("#search").val("").attr("maxlength","14").mask("999.999.999-99").focus().posicaoCursor(0);}
		if(val == 2){jQuery("#search").val("").attr("maxlength","18").mask("99.999.999/9999-99").focus().posicaoCursor(0);}
			if(val == 3){jQuery("#search").val("").attr("maxlength","54").mask("99999.99999.99999.999999 99999.999999 9 99999999999999").focus().posicaoCursor(0);}
				if(val == 4){jQuery("#search").val("").attr("maxlength","11").attr("placeholder","Ex: 00000-0").focus().posicaoCursor(0);}

}

</script>