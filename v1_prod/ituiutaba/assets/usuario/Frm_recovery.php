<?php
require_once"../../sessao.php";
?>
<?php
include("../../conexao.php");
$cfg->set_model_directory('../../models/');

// valida a variavel de sessão
$FRM_usuario_id	=	isset($COB_Usuario_Id) ? $COB_Usuario_Id	: tool::msg_erros("O Campo Codigo de Usuario Invalido.");

// recupera os dados do usuario
$dadosusuario=users::find($FRM_usuario_id);

?>
<link rel="stylesheet" href="framework/uikit-2.24.0/css/components/form-password.min.css">
<style>
.group_button a {border-radius:0; border:0;}
</style>
<form  method="post" id="FrmConfigUser" class="uk-form">

<input value="<?php  if(isset($dadosusuario)):echo $dadosusuario->senha; endif; ?>"  name="r_pwd" type="hidden" id="r_pwd"  />
<input value="<?php  if(isset($dadosusuario)):echo $dadosusuario->salt; endif; ?>"  name="salt" type="hidden" id="salt"  />
    <label>
        <span>Nome Completo</span>
		<input value="<?php  if(isset($dadosusuario)): echo tool::CompletaZeros(3,$dadosusuario->id); endif; ?>"   type="hidden" id="usuario_id" name="usuario_id"  />
        <input value="<?php  if(isset($dadosusuario)):
        echo $dadosusuario->nm_usuario; endif; ?>"  name="nmcompleto" type="text" class="input_text w_400 " id="nmcompleto"  />
    </label>
    <label>
        <span>Usuario</span>
        <input value="<?php  if(isset($dadosusuario)):
        echo $dadosusuario->login; endif; ?>"  name="username" type="text" class="input_text w_200 " id="username"  />
    </label>
    <label>
        <span>Senha Atual</span>
        <input name="pwd" type="password" class="input_text w_150 " id="pwd"  />
        <a href="" class="uk-form-password-toggle" data-uk-form-password="" style="margin:-18px 365px;">Show</a>
    </label>
    <label>
        <span>Nova Senha</span>
        <input name="new_pwd" type="password" class="input_text w_150 " id="new_pwd"  />
        <a href="" class="uk-form-password-toggle" data-uk-form-password="" style="margin:18px 365px;">Show</a>
    </label>
    <label>
        <span>Confirmar Senha</span>
        <input name="conf_new_pwd" type="password" class="input_text w_150 " id="conf_new_pwd"  />
        <a href="" class="uk-form-password-toggle" data-uk-form-password="" style="margin:55px 365px;">Show</a>
    </label>
</form>
 <div class="uk-button-group group_button"  style="border:0px solid #ccc;float:right; margin-right: 5px; ">

        <a href="JavaScript:void(0);" id="Btn_recovery" class="uk-button uk-button-small uk-button-primary" data-uk-modal="{target:'#Form_0'}" style="border-left:1px solid #ccc;padding-top:2px; line-height: 30px;" ><i class="uk-icon-filter " ></i> Confirmar</a>
        </div>
<script src="framework/uikit-2.24.0/js/components/form-password.min.js"></script>
<script type="text/javascript" >


/////////////////////////////////////////// BOTÃO CONFIRMAR EDIÇÃO DOS DADOS ///////////////////////////////////////////
jQuery(function() {

	jQuery("#Btn_recovery").click(function(event) {
		// mensagen de carregamento
		jQuery("#msg_loading").html(" Aguarde ");
		//abre a tela de preload
		modal.show();
		//desabilita o envento padrao do formulario
		event.preventDefault();
		jQuery.ajax({
				url: "assets/usuario/Controller_recovery.php",
				type: "post",
				data:jQuery("#FrmConfigUser").serialize(),
				success: function(resultado) {
												if(jQuery.isNumeric(resultado)){

													// variavel com id
													UIkit.notify("<i class='uk-icon-spinner uk-icon-spin'></i> Dados Alterados.", {timeout:1000,status:'success'});
													modal.hide();
												}else{
														//abre a tela de preload
														modal.hide();
														UIkit.modal.alert(""+resultado+"");
													}
				},
				error:function (){
					UIkit.modal.alert("Caminho Invalido!");
					}
			});
		});
});
</script>