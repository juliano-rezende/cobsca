<!DOCTYPE html>
<html lang="en-gb" dir="ltr" class="uk-height-1-1">
<head>
    <meta charset="utf-8">
    <title>UNICOB</title>
    <link rel="shortcut icon" href="framework/uikit-2.24.0/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="framework/uikit-2.24.0/css/uikit.css">
    <link href="css/doc.uikit.css?<?php echo microtime(); ?>" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="framework/uikit-2.24.0/css/components/notify.min.css">
    <link rel="stylesheet" href="framework/uikit-2.24.0/css/components/form-password.min.css">
</head>
<body class="uk-height-1-1" style="background-image:url(imagens/bgsys1.png); background-repeat: no-repeat; background-size: cover; background-color:#01579b;background-size: cover;">
<div class="uk-vertical-align uk-text-center uk-height-1-1">
    <div class="uk-vertical-align-middle" style="width: 400px; height: 350px;">
        <div class="uk-panel-teaser" style="background:#E4E4E4;  padding-top: 10px; border-bottom: 1px solid #52B5D5;margin: 0;border-radius:3px;width:390px; margin-left:5px;">
            <img class="uk-margin-bottom" src="imagens/icon_logo.png" alt="">
        </div>
        <div class="uk-panel uk-panel-box" style="padding: 0px; margin: 4px;">
            <form class="uk-form" id="fr_login" style="border-radius:0; padding:0px; margin:0;">
                    <ul class="uk-tab" data-uk-tab="{connect:'#tab-content',animation: 'fade'}">
                        <li class="uk-active" aria-expanded="true"><a href="#">Sistema</a></li>
                        <li aria-expanded="false" class=""><a href="#">Parceiros</a></li>
                    </ul>
                    <ul id="tab-content" class="uk-switcher uk-margin">
                        <li class="uk-active" aria-hidden="false" style="padding-left: 10px; padding-right: 10px;">
                            <div class="uk-form-row">
                                <p style="padding: 5px; margin: 1px; float: left; color:#212121;">Digite seu login</p>
                                <input class="uk-width-1-1 uk-form-large uk-text-uppercase" type="text" id="login" autocomplete="off" style="height: 40px;">
                            </div>
                            <div class="uk-form-row">
                                <p style="padding: 5px; margin: 1px; float: left; color:#212121;">Digite sua senha</p>
                                <input class="uk-width-1-1 uk-form-large" type="password" autocomplete="off" id="senha"
                                       onKeyPress="if ((window.event ? event.keyCode : event.which) == 13) { Login(); }" style="height: 40px;">
                                <a href="" class="uk-form-password-toggle" data-uk-form-password="" style="margin:30px 10px;">Show</a>
                            </div>
                            <div class="uk-form-row">
                                <a class="uk-width-1-1 uk-button uk-button-primary uk-button-large" id="Btn_login" style="border-radius:0; border:0; line-height: 40px;" onclick="Login();">ACESSAR</a>
                            </div>
                        </li>
                        <li aria-hidden="true" style="padding-left: 10px; padding-right: 10px;">
                            <div class="uk-form-row">
                            <p style="padding: 5px; margin: 1px; float: left; color:#212121;">Selecione como deseja pesquisar</p>
                            <select class="select uk-width-1-1 uk-form-large" name="search_for" id="search_for" style="line-height: 40px; height: 40px;text-capitalize" >
                            <option value="" selected></option>
                            <option value="0">Nome e Cpf</option>
                            <option value="1">Matricula</option>
                            </select>
                            
                            </div>
                            <div class="uk-form-row inputSearchMat">
                                <p style="padding: 5px; margin: 1px; float: left; color:#212121;">Digite a matricula</p>
                                <input class="uk-width-1-1 uk-form-large" type="text" id="matricula" autocomplete="off" style="height: 40px;">
                            </div>
                            <div class="uk-form-row inputSearchDoc">
                                <p style="padding: 5px; margin: 1px; float: left; color:#212121;">Digite o nome impresso no cartão</p>
                                <input class="uk-width-1-1 uk-form-large" type="text" id="nm" autocomplete="off" style="height: 40px;">
                            </div>
                            <div class="uk-form-row inputSearchDoc">
                                <p style="padding: 5px; margin: 1px; float: left; color:#212121;">Digite apenas números (CPF)</p>
                                <input class="uk-width-1-1 uk-form-large" type="text" autocomplete="off" id="doc"
                                       onKeyPress="if ((window.event ? event.keyCode : event.which) == 13) { SearchAssoc(); }" style="height: 40px;">
                            </div>
                            <div class="uk-form-row">
                                <a class="uk-width-1-1 uk-button uk-button-primary uk-button-large" id="Btn_login" style="border-radius:0; border:0; line-height: 40px;" onclick="SearchAssoc();">CONSULTAR</a>
                            </div>
                        </li>
                    </ul>

                <!--<div class="uk-form-row uk-text-small">
                 <label class="uk-float-left"><input type="checkbox"> Lembrar senha ?</label> | <a class="uk-link uk-link-muted" href="#">Recuperar senha</a>
                </div>-->
                <div class="uk-form-row uk-text-small" style="margin-bottom: 10px;"><label>Versão 3.1</label></div>
            </form>
        </div>
    </div>

</body>
</html>
<script src="js/jquery/jquery-1.9.1.js"></script>
<script src="framework/uikit-2.24.0/js/uikit.min.js"></script>
<script src="js/auth.js"></script>
<script src="framework/uikit-2.24.0/js/components/notify.min.js"></script>
<script src="framework/uikit-2.24.0/js/components/form-password.min.js"></script>
<script>
function SearchAssoc(){
	var nm_titular=$("#nm").val();
	var doc_titular=$("#doc").val();
	var matricula=$("#matricula").val();
	var search_for=$("#search_for").val();
	UIkit.notify("<i class='uk-icon-spinner uk-icon-spin'></i> Pesquisando...",{pos:'top-center',timeout: 1000});
	jQuery.post("parceiro/consultaAssociado.php",
		{search_for:search_for,nm_titular:nm_titular,doc_titular:doc_titular,matricula:matricula},
		/* Carregamos o resultado acima */
		function(resultado){
			if(jQuery.isNumeric(resultado)){
				UIkit.modal.alert('<h1><i class="uk-icon-check uk-icon-large uk-text-success"></i> Successo!</h1> <p style="width: 100%; display: inline-block; text-align: center;">Este associado esta autorizado a utilizar nossos serviços.</p>');
			}else{
				UIkit.modal.alert('<h1><i class="uk-icon-thumbs-down uk-icon-large uk-text-danger"></i> Opsssss!</h1> <p style="width: 100%; display: inline-block; text-align: center;">'+resultado+'</p>');

			}
		});
}

$("#search_for").change(function(){
    let $this = $(this);
    if($this.val() == 0){$(".inputSearchDoc").show();$(".inputSearchMat").hide();$("#matricula").val("");}else{$(".inputSearchDoc").hide();$(".inputSearchMat").show();$("#nm,#doc").val("");}
});
</script>
