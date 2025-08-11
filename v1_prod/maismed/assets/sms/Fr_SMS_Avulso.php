<?php
require_once"../../sessao.php";
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>envio de sms</title>
</head>
<body>
<div class="tabs-spacer" style="display:none;">
<?php
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');
?>
</div>
<form name="fr_sms" method="post" id="fr_sms"> 
<fieldset style="border:0; padding:0; margin:0; width:500px; padding-top:5px">
<label> 
<span>N&deg; Tel</span> 
<div class="uk-form-icon">
<i class="uk-icon-mobile"></i>
<input value="<?php echo $_GET['fone']; ?>"  name="fone" type="text" class="input_text center w_150  " id="fone"  />
</div> 
</label> 

<label> 
<span>Mensagem</span>
<textarea cols="45" id="msg" class="message"  rows="5"  onkeyup="limitaCaractere('msg',180,'exibeLimite');" style="height:100px; width:300px;">
<?php echo base64_decode($_GET['msg']); ?>
</textarea>
</label>

<div   style=" height:35px; width:317px; float:right; margin-right:33px;">
<span style="position: absolute;">Limite de 180 caracteres</span>
<span id="exibeLimite" style="position: absolute; margin-top:18px;"></span>
</div>
</fieldset>
<fieldset style="border:0; padding:0; text-align:center;width:500px;">
<input name="Btn_Enviar_Sms" id="Btn_Enviar_Sms" type="button" class="button" value="Enviar" />
</fieldset>
</form>
</body>

<script type="text/javascript">
$("#fone").mask("(99) 9999-9999");

$("#Btn_Enviar_Sms").click(function(){
	
var fone=$("#fone").val();	
var msg=$("#msg").val();
var cdpaciente=$("#cdpaciente").val();


// mensagen de carregamento
$("#msg_loading").html(" Enviando ");
//abre a tela de preload
modal.show();

$.post("assets/sms/Ajax_SMS_Avulso.php",{fone:fone,msg:msg,cdpaciente:cdpaciente},
// Carregamos o resultado acima para o campo marca
function(resultado){
				$("#msg_loading").html(" Carregando ");// reseta a msg do preload
				modal.hide();// finaliza a janela do preload
				alert(resultado);
 });
})

// função para bloqueio de caracteres
function limitaCaractere(textareaId,limite,exibeRestante){
var caracterDigitado = document.getElementById(textareaId).value;
var caracterRestante = limite - caracterDigitado.length;
document.getElementById(exibeRestante).innerHTML = "<span style='color:blue;'>Você ainda pode digitar " + caracterRestante + " caracteres.</span>";
if(caracterDigitado.length == limite - 1)
document.getElementById(exibeRestante).innerHTML = "<span style='color:blue;'>Você ainda pode digitar " + caracterRestante + " caractere.</span>";
if(caracterDigitado.length >= limite){
document.getElementById(textareaId).value = document.getElementById(textareaId).value.substr(0, limite);
document.getElementById(exibeRestante).innerHTML = "<span style='color:red;'>Você atingiu o limite de caracteres permitido!</span>";
}
}

</script>

</html>