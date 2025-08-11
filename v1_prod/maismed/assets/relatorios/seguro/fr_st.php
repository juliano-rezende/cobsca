<?php
require_once"../../sessao.php";
require_once("../../functions/funcoes.php");


$mes=explode("_",$_GET['idlinha']);
$m=$mes[1];
?>

<link rel="stylesheet" href="css/style_forms.min.css?<?php echo microtime(); ?>" />
 
<form name="fr_status" method="post" id="fr_status">  
<fieldset style=" width:490px;border:0; padding:0; padding-top:5px; margin:0;">

<label> 
<span>Cod Registro</span> 
<input type="text" class="input_text w_80 center" name="idreg" id="idreg" value="<?php echo $_GET['idreg']; ?>"/> 
</label>

<label> 
<span>Matricula</span> 
<input type="text" class="input_text w_80 center" name="matricula" id="matricula" value="<?php echo $_GET['matricula']; ?>"/> 
</label>
<label> 
<span>Assegurado</span> 
<input type="text" class="input_text w_300 center" name="assegurado" id="assegurado" value="<?php echo base64_decode($_GET['nmassegurado']); ?>"/> 
</label>

<label> 
<span>Mês a assegurar</span> 
<input type="text" class="input_text w_100 center" name="datasegurar" id="datasegurar" value="<?php echo "01/".completazeros("2",$m)."/".date("Y"); ?>"/> 
</label>


<label> 
<span>Status</span> 
<select name="status" id="status" class="select ">
  <option value="0" selected>Remover da base</option>
  <option value="1">Assegurar no mes</option>
  <option value="2">Incluir no mes</option>
  <option value="3">Excluir do mes</option>
</select>
</label>

</fieldset>
</form>
<div id="menu-float" style=" position:absolute; width:120px; top:170px; right:15px; ">
 <ul class="menu-float" >
            <li id="Btn_Status" title="Alterar Status"><span><i class="icon-doc-text" ></i>Alterar</span></li>
 </ul>
</div>

<script type="text/javascript">	

$("#Btn_Status").click(function (){

$("#content").append('<div id="msg_carregando" class="loading"><i class="animate-spin icon-spin6"></i><span id="msg">Aguarde ...</span></div>');


$.post("relatorios/seguro/ajax_status_seguro.php",
		  {
			  idreg:'<?php echo $_GET['idreg']; ?>',
			  matricula:'<?php echo $_GET['matricula']; ?>',
			  cdconvenio:'<?php echo $_GET['cdconvenio']; ?>',
			  datanasc:'<?php echo $_GET['datanasc']; ?>',
			  cpf:'<?php echo $_GET['cpf']; ?>',
			  estadocivil:'<?php echo $_GET['estadocivil']; ?>',
			  nmassegurado:'<?php echo $_GET['nmassegurado']; ?>',
			  datasegurar:$('#datasegurar').val(),
			  status:$('#status').val()
			  
			  },
	// Carregamos o resultado acima 
	function(resultado){
						
					
	//0,1,2,3
	if(resultado==0){
		$( "#ico_<?php echo $_GET['idlinha'];?>" ).html( "<i class=\"icon-resize-horizontal\"  style=\"color:#ccc; font-size:12px; cursor:pointer;\" ></i>" );
	}if(resultado==1){
		$( "#ico_<?php echo $_GET['idlinha'];?>" ).html( "<i class=\"icon-right-thin\" style=\"color:#063; font-size:12px; cursor:pointer;\"  ></i>" );
	}if(resultado==2){
		$( "#ico_<?php echo $_GET['idlinha'];?>" ).html( "<i class=\"icon-down-thin\" style=\"color:#06F; font-size:12px; cursor:pointer;\"  ></i>" );
	}if(resultado==3){
		$( "#ico_<?php echo $_GET['idlinha'];?>" ).html( "<i class=\"icon-up-thin\" style=\"color:#f00; font-size:12px; cursor:pointer;\"  ></i>" );
	}
					   
					   
						$(".loading").hide();
						$(".Window").hide();			
	});

});

</script>