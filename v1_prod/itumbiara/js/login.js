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

function SearchAssoc(){

	var nm_titular=$("#nm_titular").val();
	var doc_titular=$("#doc_titular").val();

	UIkit.notify("<i class='uk-icon-spinner uk-icon-spin'></i> Pesquisando...",{pos:'top-center',timeout: 5000});

	jQuery.post("parceiro/consultaAssociado.php",
		{nm_titular:nm_titular,doc_titular:doc_titular},
		/* Carregamos o resultado acima */
		function(resultado){

			if(jQuery.isNumeric(resultado)){
				UIkit.modal.alert('<h1><i class="uk-icon-check uk-icon-large uk-text-success"></i> Successo!</h1> <p>Este associado esta autorizado a utilizar nossos serviços.</p>');
			}else{
				UIkit.modal.alert('<h1><i class="uk-icon-thumbs-down uk-icon-large uk-text-danger"></i> Opsssss!</h1> <p>Este associado não esta autorizado a utilizar nossos serviços.</p>');

			}
		});

}