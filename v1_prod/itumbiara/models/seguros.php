<?php

class seguros extends ActiveRecord\Model{


// verifica se o cpf possui numero sequencias como  000000/111111/22222 etc
static	function VerificaIgualdade($cpf) {
        // Todos os caracteres em um array
        $caracteres = str_split($cpf);

        // Considera que todos os números são iguais
        $todos_iguais = true;
        // Primeiro caractere
        $last_val = $caracteres[0];
        // Verifica todos os caracteres para detectar diferença
        foreach( $caracteres as $val ) {
            // Se o último valor for diferente do anterior, já temos
            // um número diferente no CPF ou CNPJ
            if ( $last_val != $val ) {
               $todos_iguais = false;
            }
            // Grava o último número checado
            $last_val = $val;
        }
        // Retorna true para todos os números iguais
        // ou falso para todos os números diferentes
        return $todos_iguais;
    }


// valida o cpf
static function ValidaCPF($cpf)
	{

		$obs_cpf = "";

		// verifica se é numerico
		if(!is_numeric($cpf)) {

 			return false;

 		}else{

			// garante que seja enviado somente numero
			$cpf = preg_replace( '/[^0-9]/', '', $cpf );

			// garante que tenha 11 digitos
			if ( strlen( $cpf ) < 11 )
			{

				$cpf = str_pad($cpf, 11, "0", STR_PAD_LEFT);
			}

			$verifica_igual = self::VerificaIgualdade($cpf);

			if($verifica_igual == true){

				return false;

			}else{

				//PEGAO DIGITO VERIFIACADOR
				$dv_informado = substr($cpf, 9,2);

					for($i=0; $i<=8; $i++)
					{
					    $digito[$i] = substr($cpf, $i,1);
					}

				//CALCULA O VALOR DO 10º DIGITO DE VERIFICAÇÂO
				$posicao = 10;
				$soma = 0;

					for($i=0; $i<=8; $i++)
					{
				    	$soma = $soma + $digito[$i] * $posicao;
				    	$posicao = $posicao - 1;
					}

				$digito[9] = $soma % 11;

					if($digito[9] < 2)
					{
				    	$digito[9] = 0;
					}
					else
					{
				    	$digito[9] = 11 - $digito[9];
					}

				//CALCULA O VALOR DO 11º DIGITO DE VERIFICAÇÃO
				$posicao = 11;
				$soma = 0;

					for ($i=0; $i<=9; $i++)
					{
						$soma = $soma + $digito[$i] * $posicao;
				    	$posicao = $posicao - 1;
					}

				$digito[10] = $soma % 11;

					if ($digito[10] < 2)
					{
						$digito[10] = 0;
					}
					else
					{
						$digito[10] = 11 - $digito[10];
					}

				  //VERIFICA SE	O DV CALCULADO É IGUAL AO INFORMADO
				$dv= $digito[9] * 10 + $digito[10];
				  	if ($dv != $dv_informado) {

				   		return false;
				  	}
				  	else
				  	{
				  		return true;
				  	}

			}

		}

    }

// valida a data de nascimento
static function ValidaDataNasc($data){

	if ( !checkdate( substr($data,2,2) , substr($data,0,2) , substr($data,4,4) ) 					// se a data for inválida
	     or substr($data,4,4) < 1900                                    			   				// ou o ano menor que 1900
	     or mktime( 0, 0, 0, substr($data,2,2), substr($data,0,2), substr($data,4,4)) > time() )	// ou a data passar de hoje
	{
		return false;

	}else{

		return true;
	}

}


// FUNÇÃO DE INSERÇÃO DOS DADOS DO SEGURADO NA TABELA
// VALORES DENTRO DA FUNÇÃO " MATRICULA,REFERENCIA DE PGTO,TIPO DO CONVENIO,ID DA EMPRESA"
	static function segurar($matricula,$referencia_pgto,$tp_convenio,$empresa_id){

	// RECUPERA AS CONFIGURAÇÕES DA EMPRESA
	$config=configs::find_by_empresas_id($empresa_id);



// define a referencia que será assegurada
if($tp_convenio == "J"){ $ref_segurar = date("Y-m")."-01"; }else{$ref_segurar = $referencia_pgto;}


	// VERIFICAMOS SE JÁ EXISTE A REFERENCIA SALVA NO TABELA
	/*WHERE matricula=".$matricula." and YEAR(referencia) = YEAR(now()) AND MONTH(referencia) =  MONTH(now()) and empresas_id=".$empresa_id."");*/
	$Query_valida_ref=seguros::find_by_sql("SELECT id
											FROM seguros
											WHERE matricula=".$matricula." and referencia ='".$ref_segurar."' and empresas_id=".$empresa_id."");

			// se ele não encontrar a referencia ele prossegui
			if(!$Query_valida_ref){

				// RECUPERA OS DADOS DO ASSOCIADO
				$Query_associados=associados::find_by_matricula($matricula);

				// COLOCAMOS A DATA DE NASCIMENTO EM STRING PARA FORMATÇÃO
				$dt_nasc 	 = $Query_associados->dt_nascimento;

				// VALIDAMOS O CPF PELA SEGUNDA VEZ PARA NÃO HAVER ERROS
				$vld_cpf		= self::ValidaCPF($Query_associados->cpf);

				// VALIDAMOS A DATA DE NASCIMENTO PELA SEGUNDA VEZ PAR ANÃO HAVER ERROS
				$vld_dt_nasc 	= self::ValidaDataNasc($dt_nasc->format('dmY')) ;


				// VALIDAMOS O RETORNO DA VALIDAÇÃO DO CPF,DATA DE NASCIMENTO,REFERENCIA E DIAS EM ATRAZO PARA RETORNA A OBSERVAÇÃO DO REGISTRO NA TABELA SEGURO
				if($vld_cpf == false ){
					$obs 	= "CPF INVALIDO NÃO ASSEGURADO.";
					$st 	= "0";
					$dt_inclusao=date("Y-m-d");
					$dt_exclusao=date("Y-m-d");
				}elseif($vld_dt_nasc == false){
					$obs 	= "DATA DE NASCIMENTO INVALIDA NÃO ASSEGURADO";
					$st 	= "0";
					$dt_inclusao=date("Y-m-d");
					$dt_exclusao=date("Y-m-d");
				}elseif($ref_segurar < (date("Y-m")."-01")){
					$obs 	= "PAGAMENTO EFETUADO COM ATRAZO DE ".faturamentos::CalcularDiasAtrazo($ref_segurar,date("Y-m-d"))." DIAS NÃO ASSEGURADO";
					$st 	= "0";
					$dt_inclusao=date("Y-m-d");
					$dt_exclusao=date("Y-m-d");
				}elseif($ref_segurar == (date("Y-m")."-01") && date("d") > $config->dt_limit_seg){
					$obs 	= "PAGAMENTO FORA DO PRAZO LIMITE DO DIA 20 NÃO ASSEGURADO";
					$st 	= "0";
					$dt_inclusao=date("Y-m-d");
					$dt_exclusao=date("Y-m-d");

				}else{


					//status da ultima referencia
					//recupera a ultima referencia daquela matricula
					$Query_referencia=seguros::find_by_sql("SELECT MAX(referencia),dt_ult_inclusao,referencia,status FROM seguros WHERE matricula=".$matricula."");

					if(count($Query_referencia) == 0){$back_st = 0;}else{	$back_st = $Query_referencia[0]->status;}



					// DEFINIMOS O STATUS DO REGISTRO BASEADO NO ANTERIOR
					if($back_st == 0){

						$obs 	= "ASSOCIADO INCLUIDO E ASSEGURADO";
						$st 	= "1";
						$dt_inclusao=date("Y-m-d");
						$dt_exclusao="";

					}elseif($back_st == 1){

						$obs 	= "ASSOCIADO SEM MOVIMENTACAO E ASSEGURADO";
						$st 	= "2";
						$dt_inclusao = new ActiveRecord\DateTime($Query_referencia[0]->dt_ult_inclusao);
						$dt_inclusao = $dt_inclusao->format('Y-m-d');
						$dt_exclusao="";

					}elseif($back_st == 2){

						$obs 	= "ASSOCIADO SEM MOVIMENTACAO E ASSEGURADO";
						$st 	= "2";
						$dt_inclusao=new ActiveRecord\DateTime($Query_referencia[0]->dt_ult_inclusao);
						$dt_inclusao=$dt_inclusao->format('Y-m-d');
						$dt_exclusao="";

					}elseif($back_st == 3){

						$obs 	= "ASSOCIADO REINCLUÍDO E ASSEGURADO";
						$st 	= "1";
						$dt_inclusao=date("Y-m-d");
						$dt_exclusao=new ActiveRecord\DateTime($Query_referencia[0]->referencia);
						$dt_exclusao=$dt_exclusao->format('Y-m-d');

					}

				}

				// GRAVA UM NOVO REGISTRO
				$create= seguros::create(
										array(
												'empresas_id'=>$Query_associados->empresas_id,
												'convenios_id'=>$Query_associados->convenios_id,
												'matricula'=>$Query_associados->matricula,
												'nm_assegurado'=>strtoupper($Query_associados->nm_associado),
												'estado_civil'=>$Query_associados->estado_civil,
												'cpf'=>$Query_associados->cpf,
												'dt_nascimento'=>$dt_nasc->format('Y-m-d'),
												'dt_ult_inclusao'=>$dt_inclusao,
												'dt_ult_exclusao'=>$dt_exclusao,
												'referencia'=>$ref_segurar,
												'status'=>$st,
												'obs'=>$obs
											));

						// SE TUDO CORREU BEM RETORNOS TRUE
						if($create){
							return true;
						}else{
							return false;
						}

				}

	}

}

?>