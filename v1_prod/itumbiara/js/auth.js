$(".inputSearchDoc,.inputSearchMat").hide();

/* função de login */
function Login(){

	var login=$("#login").val();
	var h = screen.height;/*recupera a altura da tela*/
	var altura=h;
	var w = screen.width;/*recupera a altura da tela*/
	var largura=w;
	var senha=$("#senha").val();

	UIkit.notify("<i class='uk-icon-spinner uk-icon-spin'></i> Autenticando...",{pos:'top-center',timeout: 5000});
	jQuery.post("login/login.php",
		{login:login,senha:senha,Heigth:altura,Width:largura},
		/* Carregamos o resultado acima */
		function(resultado){
			if(jQuery.isNumeric(resultado)){
				jQuery(".uk-notify-message").addClass("uk-notify-message-success").html("<i class='uk-icon-spinner uk-icon-spin'></i> Login bem-sucedido. Redirecionando...");
				setTimeout(function(){window.location.href='sca.php';},100);
			}else{
				jQuery(".uk-notify-message").addClass("uk-notify-message-warning").html(""+resultado+"");
			}
		});
}
