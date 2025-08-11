<?php

class contas_bancarias extends ActiveRecord\Model{


	/* tipo de conta bancaria suportada pelo sistema*/
	static function tipo_conta($val){

			if($val == NULL ){
				$option ='<option value="" selected="selected"></option>';
				$option.='<option value="0" >Dinheiro</option>';
				$option.='<option value="1" >Conta Corrente</option>';
			    $option.='<option value="2" >Conta Cobrança</option>';
			 }elseif($val == 0 ){
				$option ='<option value="" ></option>';
				$option.='<option value="0" selected="selected">Dinheiro</option>';
				$option.='<option value="1" >Conta Corrente</option>';
			    $option.='<option value="2" >Conta Cobrança</option>';
			 }elseif($val == 1 ){
				$option ='<option value="" ></option>';
				$option.='<option value="0" >Dinheiro</option>';
				$option.='<option value="1" selected="selected">Conta Corrente</option>';
			    $option.='<option value="2" >Conta Cobrança</option>';
			 }elseif($val == 2){
				$option ='<option value="" ></option>';
				$option.='<option value="0" >Dinheiro</option>';
				$option.='<option value="1" >Conta Corrente</option>';
			    $option.='<option value="2" selected="selected">Conta Cobrança</option>';
			 }
	return $option;
	}


	/*tipos de moeda suportados pelo sistema */
	static function tipo_moeda($val){

				if($val == NULL ){
					$option ='<option value="" selected="selected"></option>';
					$option.='<option value="R$">R$</option>';
					$option.='<option value="US$" >US$</option>';
				 }elseif($val == "R$" ){
					$option ='<option value="" ></option>';
					$option.='<option value="R$" selected="selected">R$</option>';
					$option.='<option value="US$" >US$</option>';
				 }elseif($val == "US$" ){
					$option ='<option value=""></option>';
					$option.='<option value="R$">R$</option>';
					$option.='<option value="US$" selected="selected" >US$</option>';
				 }
	return $option;
	}



}

?>