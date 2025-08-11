<?php

class faturamentos extends ActiveRecord\Model{



// valor real data de vencimento Y-m-d 0000-00-00 taxa de juros e multa por atrazo
static function  Calcula_Juros($valor,$datavenc,$juros,$multa){


	// Define os valores a serem usados
	$datavenc       = $datavenc; /*  0000-00-00 */


	// Usa a função criada e pega o timestamp das duas datas:
	$time_inicial   = tool::GeraTimeStamp(date("d-m-Y"));

	$time_final     = tool::GeraTimeStamp(
											substr("$datavenc",8, 2)."-".
											substr("$datavenc",5, 2)."-".
											substr("$datavenc",0, 4)
										);  /* 00-00-0000 */


	// Calcula a diferença de segundos entre as duas datas:
	$diferenca = $time_final - $time_inicial; // 19522800 segundos

	if($datavenc < date("Y-m-d") ):
	    // Calcula a diferença de dias
	    $dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias
	    $dias = abs($dias);
	else:
	    $dias="0";
	endif;


	// recupera a taxa de juros da empresa
	$jurosmes	= ($juros/12);
	$jurosdia   = ($jurosmes/30) * $dias;


	// recupera a multa por atrazo tx fixa de 2,00 tem 3 dias de carencia
	if($dias>'3'){
	                //CALCULA A MULTA
					$multa = $multa/100;
					$multaporatrazo  = ($valor * $multa) ;
	             }else{
	                   $multaporatrazo = '';
	                   }

	//encontra o valor base da parcela
	$valorbase  = $valor;

	// encontra a taxa de juros em %s
	$acrescimos = $jurosdia/100;

	// calcula quanto de juros
	$acrescimo  = ($valorbase * $acrescimos) + $multaporatrazo;

	$t_debito   = $acrescimo+$valorbase;      // valor total da divida

 	return $t_debito;
	}



// retorna a quantidade de dias em atrazo
	static function CalcularDiasAtrazo($data1, $data2)
	{
	    // as datas devem ser no formato aaaa-mm-dd h:m:s
	    //conversão das datas para o formato de tempo linux

	    $data1 = strtotime($data1);
	    $data2 = strtotime($data2);
	    $dias = floor(abs($data1-$data2)/60/60/24);	//cálculo da idade fazendo a diferença entre as duas datas

	    return($dias);
	}


}


?>