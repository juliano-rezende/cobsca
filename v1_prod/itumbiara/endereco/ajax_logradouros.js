/* funções para pesquisa de adição de novos endereços */

	jQuery("select[name=pais]").change(function(){

		if(jQuery(this).val() == ""){return false;}// retorna falso se for vazio

		// Exibimos no campo marca antes de concluirmos
		jQuery("select[name=estado]").html('<option value="">Carregando...</option>');

		// Passando tipo por parametro para a pagina ajax-marca.php
		jQuery.post("endereco/ajax_estados.php",
		{cdpais:jQuery(this).val()},
		// Carregamos o resultado acima para o campo marca
		function(valor){
			jQuery("select[name=estado]").html(valor);
		});
	});

	// popula as cidades
	jQuery("select[name=uf]").change(function(){

		if(jQuery(this).val() == ""){return false;}// retorna falso se for vazio

		// Exibimos no campo marca antes de concluirmos
		jQuery("select[name=cidade]").html('<option value="">Carregando...</option>');

		// Passando tipo por parametro para a pagina ajax-marca.php
		jQuery.post("endereco/ajax_cidades.php",
		{cdestado:jQuery(this).val()},
		// Carregamos o resultado acima para o campo marca
		function(valor){
			jQuery("select[name=cidade]").html(valor).focus().css("color","#0277bd").addClass( "uk-text-warning" );
		});
	});

	// popula os bairros
	jQuery("select[name=cidade]").change(function(){

		if(jQuery(this).val() == ""){return false;}// retorna falso se for vazio

		// Exibimos no campo marca antes de concluirmos
		jQuery("select[name=bairro]").html('<option value="">Carregando...</option>');

		// Passando tipo por parametro para a pagina ajax-marca.php
		jQuery.post("endereco/ajax_bairros.php",
		{cdcidade:jQuery(this).val()},
		// Carregamos o resultado acima para o campo marca
		function(valor){
			jQuery("select[name=cidade]").removeClass( "uk-text-warning" );
			jQuery("select[name=bairro]").html(valor).focus().css("color","#0277bd").addClass( "uk-text-warning" );
		});
	});

	// popula os logradouros
	jQuery("select[name=bairro]").change(function(){

		if(jQuery(this).val() == ""){return false;}// retorna falso se for vazio

		// Exibimos no campo marca antes de concluirmos
		jQuery("select[name=logradouro]").html('<option value="">Carregando...</option>');


		jQuery.post("endereco/ajax_logradouros.php",
		{cdbairro:jQuery(this).val()},
		function(valor){
			jQuery("select[name=bairro]").css("color","#000").removeClass( "uk-text-warning" );
			jQuery("select[name=logradouro]").html(valor).focus().css("color","#0277bd").addClass( "uk-text-warning" );
		});
	});

	//carrega o cep no campo cep
	jQuery("select[name=logradouro]").change(function(){

		jQuery.post("endereco/ajax_cep.php",
		{logradouro_id:jQuery(this).val()},
		function(valor){
			jQuery("select[name=logradouro]").removeClass( "uk-text-warning" );
			jQuery("input[name=cep]").val(valor);
			jQuery("input[name=compl_end]").val("").focus();
		});
	});




/* funções para adcionar manualmente novos endereços*/

/* adionar novo estado */
function New_estado(){New_window('plus','400','150','Adcionar estado','endereco/add_end/Frm_uf.php',true,false,'Carregando...');}

/* adionar nova cidade */
function New_cidade(){New_window('plus','400','160','Adcionar cidade','endereco/add_end/Frm_cidade.php',true,false,'Carregando...');}

/* adionar novo bairro */
function New_bairro(){New_window('plus','400','190','Adcionar bairro','endereco/add_end/Frm_bairro.php',true,false,'Carregando...');}

/* adionar novo logradouro */
function New_logradouro(){New_window('plus','400','270','Adcionar Logradouro','endereco/add_end/Frm_logradouro.php',true,false,'Carregando...');}