///////////////////////////////////////////////// BOTÃO BUSCA CEP /////////////////////////////////////////////////
function Busca_Cep(campo){

 //Nova variável "cep" somente com dígitos.
var cep = jQuery("input[name=cep"+campo+"]").val().replace(/\D/g, '');

	//Verifica se campo cep possui valor informado.
	if (cep != "") {

		//Expressão regular para validar o CEP.
		var validacep = /^[0-9]{8}$/;

		//Valida o formato do CEP se ele for valido consultamos na base de dados do correio
		if(validacep.test(cep)) {

			//Consulta o webservice viacep.com.br/
			jQuery.getJSON("//viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

			/* verificamos se existe a variavel erro dentro do jason e retornamos como cep não encontrado */
			if (("erro" in dados)) {

					//cep é inválido.
					UIkit.notify("<i class='uk-icon-info-circle' ></i> Cep não encontrado selecione diretamente o estado.", {status:'danger',timeout: 2500});
					jQuery("select[name=uf"+campo+"]").focus().attr('selected','selected'); //'select#ex1 option:eq(5)'
					modal.hide();// fecha msg na tela
					return;

			}if (dados.logradouro =="" && dados.complemento =="" && dados.bairro =="") {
				//cep é inválido.
					UIkit.notify("<i class='uk-icon-info-circle' ></i> Cidade com cep padrão selecione diretamente o estado.", {status:'info',timeout: 2500});
					jQuery("select[name=uf"+campo+"]").focus().attr('selected','selected');
					modal.hide();// fecha msg na tela
					return;
			}else{

			// mensagen de carregamento
			jQuery("#msg_loading").html(" Aguarde... ");
			//abre a tela de preload
			modal.show();

				/* bom acima finalizamos tudo o cep é um cep valido ele existe não é padrao, então agora verificamos se ja existe em nossa base de dados*/
				jQuery.post("endereco/cep/ajax_valida_cep.php",{cep:cep},function(resultado){


					/* aqui teremos apenas dois retornos true ou false no primeiro teste será caso não exista na base de dados interna do sistema*/
					if(resultado == 0){

						jQuery.post("endereco/cep/Controller_cep.php",{cep:cep,uf:dados.uf,cidade:dados.localidade,bairro:dados.bairro,logradouro:dados.logradouro},
									// Carregamos o resultado acima para o campo logradouro
									function(resultadocep){

													jQuery("#msg_loading").html(" Pesquisando... "); // msg de carregamento
													// carregado o valor no campo estado
													jQuery.post("endereco/cep/ajax_logradouros_cep.php",
														{cep:cep,tabela:'estados'},
														// Carregamos o resultado acima para o campo cidade
														function(valor){
															//alert(valor);
															jQuery("select[name=uf"+campo+"]").html(valor);
														});
													jQuery.post("endereco/cep/ajax_logradouros_cep.php",
														{cep:cep,tabela:'cidades'},
														// Carregamos o resultado acima para o campo cidade
														function(valor){
															//alert(valor);
															jQuery("select[name=cidade"+campo+"]").html(valor);
														});
													// valor no campo bairros
													jQuery.post("endereco/cep/ajax_logradouros_cep.php",
														{cep:cep,tabela:'bairros',},
														// Carregamos o resultado acima para o campo bairro
														function(valor){
															//alert(valor);
															jQuery("select[name=bairro"+campo+"]").html(valor);

														});
													// valor no campo bairros
													jQuery.post("endereco/cep/ajax_logradouros_cep.php",
														{cep:cep,tabela:'logradouros',},
														// Carregamos o resultado acima para o campo bairro
														function(valor){
															//alert(valor);
															jQuery("select[name=logradouro"+campo+"]").html(valor);
															jQuery("#compl_end"+campo+"").focus();
															modal.hide();// fecha msg na tela
														});
									});
						return;

					}if(resultado == 1){/* se existe o cep na gravado na base de dados fazemos a ação abaixo*/

							jQuery("#msg_loading").html(" Carregando endereço... "); // msg de carregamento

							// carregado o valor no campo estado
							jQuery.post("endereco/cep/ajax_logradouros_cep.php",
								{cep:cep,tabela:'estados'},
								// Carregamos o resultado acima para o campo cidade
								function(valor){
									//alert(valor);
									jQuery("select[name=uf"+campo+"]").html(valor);
							});
							jQuery.post("endereco/cep/ajax_logradouros_cep.php",
								{cep:cep,tabela:'cidades'},
								// Carregamos o resultado acima para o campo cidade
								function(valor){
									//alert(valor);
									jQuery("select[name=cidade"+campo+"]").html(valor);
							});
							// valor no campo bairros
							jQuery.post("endereco/cep/ajax_logradouros_cep.php",
								{cep:cep,tabela:'bairros',},
								// Carregamos o resultado acima para o campo bairro
								function(valor){
									//alert(valor);
									jQuery("select[name=bairro"+campo+"]").html(valor);

							});
							// valor no campo bairros
							jQuery.post("endereco/cep/ajax_logradouros_cep.php",
								{cep:cep,tabela:'logradouros',},
								// Carregamos o resultado acima para o campo bairro
								function(valor){
									//alert(valor);
									jQuery("select[name=logradouro"+campo+"]").html(valor);
									jQuery("#compl_end"+campo+"").focus();
									modal.hide();// fecha msg na tela
							});

							return;
					/* caso haja erro na pesquisa retorna o erro abaixo*/		
					}else{ UIkit.notify("<i class='uk-icon-info-circle' ></i> Erro ao consultar cep na base de dados interna.", {status:'info',timeout: 2500}); return;}

				});
			}
			});

		}else { //cep é inválido.
			UIkit.notify("<i class='uk-icon-info-circle' ></i> Formato de CEP inválido.", {status:'warning',timeout: 2500})
		}/* final do else cep invalido*/
	}/* final da verificação de cep em branco*/
}/* final do busca cep*/



///////////////////////////////////////////////// BOTÃO BUSCAR CEP NO CORREIO ///////////////////////////////////////////

function Buscacep_correios() {

var win=null;

var w=400;
var h=400;

var winl = (screen.width-w)/2;
var wint = (screen.height-h)/2;
var settings ='height='+h+',';
settings +='width='+w+',';
settings +='top='+wint+',';
settings +='left='+winl+',';
settings +='scrollbars='+scroll+',';
settings +='resizable=yes';
win=window.open("http://m.correios.com.br/movel/buscaCepConfirma.do","Busca Cep",settings);
if(parseInt(navigator.appVersion) >= 4){win.window.focus();}


}// fim botao pesquisar correios