<?php

require_once"../../../sessao.php";

?>

<link rel="stylesheet" href="css/style_forms.min.css?<?php echo microtime(); ?>" />

 

<form name="fr_segurados" method="post" id="fr_segurados"> 

<fieldset style=" width:300px;border:0; padding:0; padding-top:20px; margin:0;">





<label> 

<span>Mês Segurado</span> 

<select name="mesassegurar" id="mesassegurar" class="select ">


<option value="<?php echo date("Y-m"); ?>-01" ><?php echo date("m/Y"); ?></option>  

</select>

</label>



<label> 

<span>Tipo de Planilha</span> 

<select name="tipoplan" id="tipoplan" class="select ">

<option value="0" >Simplificada</option> 

<option value="1" >Completa</option>    

</select>

</label>



</fieldset>

</form>

<div id="menu-float" style=" position:absolute; width:120px; top:150px; right:15px; ">

 <ul class="menu-float" >

            <li id="Btn_Gerar_Planilha" title="Gerar Lista de Assegurados do Mês"><span><i class="icon-doc-text" ></i>Confirmar</span></li>

 </ul>

</div>



<script type="text/javascript">



$("#Btn_Gerar_Planilha").click( function(){

	

var confirma=confirm("Se já existir uma planilha gerada para esta referência ela será excluida. Você confirma ?");



if(confirma==true){	

//loader

$("#Btn_Gerar_Planilha").html('<span><i class="animate-spin icon-spin3" ></i>Aguarde ...</span>');





var tipoplan=$("#tipoplan").val();



/* planilha simplificada*/

if(tipoplan==0){

	

 $.post("relatorios/seguro/app_exportacao/Plan_Simplificada.php",

		  {datasegurar:$("#mesassegurar").val()},

           // Carregamos o resultado acima 

			function(resultado){

							  alert(""+resultado+"");

							  $("#Btn_Gerar_Planilha").html('<span><i class="icon-doc-text" ></i>Confirmar</span>');

							  }); 

	

	}else{/* planilha completa*/

		$.post("relatorios/seguro/app_exportacao/Plan_Completa.php",

		  {datasegurar:$("#mesassegurar").val()},

           // Carregamos o resultado acima 

			function(resultado){

							  alert(""+resultado+"");

							  $("#Btn_Gerar_Planilha").html('<span><i class="icon-doc-text" ></i>Confirmar</span>');

							  });

		

		}



}

})

</script>

