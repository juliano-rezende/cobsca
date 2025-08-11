<!DOCTYPE html>
<html lang="en-gb" dir="ltr" class="uk-height-1-1">
<head>
	<meta charset="utf-8">
	<title>UNICOB</title>
	<link rel="shortcut icon" href="imagens/favi-con.ico" type="image/x-icon">
	<link rel="stylesheet" href="framework/uikit-2.24.0/css/uikit.css">
	<link href="css/doc.uikit.css?<?php echo microtime(); ?>" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="framework/uikit-2.24.0/css/components/notify.min.css">
	<link rel="stylesheet" href="framework/uikit-2.24.0/css/components/form-password.min.css">
</head>
<body class="uk-height-1-1" style="background-image:url(imagens/bgsys0.png); background-repeat:no-repeat; background-position:0% 100%; background-color:#000;">
	<div class="uk-vertical-align uk-text-center uk-height-1-1" >
		<div class="uk-vertical-align-middle" style="width: 500px;" >
			<div class="uk-panel uk-panel-box ">
				<div class="uk-panel-teaser"  style="background:#E4E4E4;  padding-top: 10px; border-bottom: 1px solid #52B5D5;">
					<img class="uk-margin-bottom"  src="imagens/icon_logo.png" alt="">
				</div>
				<form class="uk-form" id="fr_login" style="border-radius:0; padding:20px;">
					<div class="uk-form-row">
						<input class="uk-width-1-1 uk-form-large uk-text-uppercase" type="text" placeholder="LOGIN" id="login" autocomplete="off" style="height: 50px;"></div>
						<div class="uk-form-row">
							<input class="uk-width-1-1 uk-form-large" type="password" autocomplete="off" placeholder="SENHA" id="senha" onKeyPress="if ((window.event ? event.keyCode : event.which) == 13) { Login(); }" style="height: 50px;">
							<a href="" class="uk-form-password-toggle" data-uk-form-password="" style="margin:15px 30px;">Show</a>
						</div>
						<div class="uk-form-row">
							<a class="uk-width-1-1 uk-button uk-button-primary uk-button-large" id="Btn_login" style="border-radius:0; border:0; line-height: 40px;"  onclick="Login();">ACESSAR</a>
						</div>
		<!--<div class="uk-form-row uk-text-small">
		 <label class="uk-float-left"><input type="checkbox"> Lembrar senha ?</label> | <a class="uk-link uk-link-muted" href="#">Recuperar senha</a> 
		</div>-->
		<div class="uk-form-row uk-text-small"><label>Versão 3.5</label> </div>
	</form>
</div>
</div>

</body>
</html>
<script src="js/jquery/jquery-1.9.1.js"></script>
<script src="framework/uikit-2.24.0/js/uikit.min.js"></script>
<script src="js/login.min.js"></script>
<script src="framework/uikit-2.24.0/js/components/notify.min.js"></script>
<script src="framework/uikit-2.24.0/js/components/form-password.min.js"></script>
</script>
