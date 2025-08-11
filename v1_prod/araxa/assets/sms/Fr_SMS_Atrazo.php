<?php
require_once"../sessao.php";
?>
<div class="tabs-spacer" style="display:none;">
<?php
require_once("../conexao.php");
$cfg->set_model_directory('../models/');
?>
</div>
<link rel="stylesheet" href="css/style_forms.css?<?php echo microtime(); ?>" />
 
<form name="fr_filtro_sms" method="post" id="fr_filtro_sms"> 
<fieldset style=" width:300px;border:0; padding:0; padding-top:20px; margin:0;">


<label> 
<span>Referencia</span> 
<select name="referencia" id="referencia" class="select ">
<?php
$ref_encontradas=faturamento::all(array('group' => 'referencia','order' => 'referencia desc','limit' => 24));
$re= new ArrayIterator($ref_encontradas);
while($re->valid()):
$now = new ActiveRecord\DateTime($re->current()->referencia);
$ref=$now->format('Y-m-d');
$ref1=$now->format('m/Y');
echo'<option value="'.$ref.'" >'.$ref1.'</option>';
$re->next();
endwhile;
?>     
</select>
</label>

</fieldset>
</form>
<div id="menu-float" style=" position:absolute; width:120px; top:150px; right:15px; ">
 <ul class="menu-float" >
            <li id="Btn_Enviar_SMS" title="Enviar SMS de Cobrança"><span><i class="icon-doc-text" ></i>Enviar SMS</span></li>
 </ul>
</div>

<script type="text/javascript">	
$("#Btn_Enviar_SMS").click(function (){

	var referencia=$("#referencia").val();
	var confirma = confirm("Está Ação ira enviar um SMS de cobrança para cada associado com parcela vencida para referencia que você escolheu. , Você Confirma ?");
		
	if(confirma ==true){LoadContent('sms/Ajax_SMS_Aviso_Atrazo.php?referencia='+referencia+'','content');}else{alert('Ação Cancelada.');}

});
</script>