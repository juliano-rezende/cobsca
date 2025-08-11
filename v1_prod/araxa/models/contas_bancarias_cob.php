<?php

class contas_bancarias_cob extends ActiveRecord\Model{


	static $primary_key = 'contas_bancarias_id';


/******************* tipo de conta bancaria suportada pelo sistema ********************/

/******************* CARTEIRAS SUPORTADAS *******************/

/*
CAIXA ECONOMICA FEDERAL
02 SIMPLES SEM REGISTRO SIGCB

SANTANDER
101 SIMPLES RAPIDA COM REGISTRO
102 SIMPLES RAPIDA SEM REGISTRO

SICOOB 756-0
1 SIMPLES RAPIDA COM REGISTRO VARIAÇÃO 01

ITAU 341
175 SIMPLES SEM REGISTRO
*/


	static function tipo_carteira($banco,$cart){

/* CAIXA ECONOMICA FEDERAL */
		if($banco == "104" ){

				if($cart == 02){

					$option ='<option value="0"></option>';
					$option.='<option value="02_SR" selected="selected">02 - Cobrança sem Registro - SIGCB</option>';

				}else{
					$option ='<option value="0" selected="selected"></option>';
					$option.='<option value="02_SR">02 - Cobrança sem Registro - SIGCB</option>';
				}
/* SANTANDER */
		}elseif($banco == "033" ){

				if($cart == 101){

					$option ='<option value="0"></option>';
					$option.='<option value="101_CR" selected="selected">101 - Cobrança Simples RCR</option>';
					$option.='<option value="102_SR" >102 - Cobrança Simples CSR</option>';

				}elseif($cart == 102){

					$option ='<option value="0"></option>';
					$option.='<option value="101_SR">101 - Cobrança Simples Rápida RCR</option>';
					$option.='<option value="102_SR	" selected="selected">102 - Cobrança Simples CSR</option>';

				}else{
					$option ='<option value="0" selected="selected"></option>';
					$option.='<option value="101_CR">101 - Cobrança Simples Rápida RCR</option>';
					$option.='<option value="102_SR">102 - Cobrança Simples CSR</option>';
				}
/* ITAU */
		}elseif($banco == "341" ){

				if($cart == 175){

					$option ='<option value="0"></option>';
					$option.='<option value="175_SR" selected="selected">175 - Cobrança sem Registro</option>';

				}else{
					$option ='<option value="0" selected="selected"></option>';
					$option.='<option value="175_SR">175 - Cobrança sem Registro </option>';
				}
/* UNICRED*/
		}elseif($banco == "136"){

					$option='<option value="" selected="selected">Não implementado</option>';
/*SICOOB/BANCOOB*/
		}elseif($banco == "756"){

				if($cart == 1){

						$option ='<option value="0"></option>';
						$option.='<option value="1_CR" selected="selected">1 / 01 - Simples CR</option>';

				}elseif($cart == 2){

					$option ='<option value="0"></option>';
					$option.='<option value="1_CR" selected="selected">1 / 01 - Simples CR</option>';

				}else{
					$option ='<option value="0" selected="selected"></option>';
					$option.='<option value="1_CR" >1/01 - Simples CR</option>';
				}
		}
	return $option;
	}


}

?>