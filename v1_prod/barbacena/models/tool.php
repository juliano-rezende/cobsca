<?php

class tool extends ActiveRecord\Model{


	//VALIDA HORARIO DE BACKUP
	static 	function valid_time_backup($time)
	{


		if($time >= "22:00" && $time <= "18:10" ){

			echo'Estamos fazendo backup dos servidores .';
			exit();

		}if($time >= "16:00" && $time <= "11:10" ){

			echo'Estamos fazendo backup dos servidores .';
			exit();

		}

	}

   // VALIDA A ALTURA DA TELA
	static function valid_heigth_screen($height)
	{

		if($height<600){

			echo'Resolução minima é de 800x600 !';
			exit();

		}

	}

	// DEFINE A ALTURA DA DIV CONTENT
	static function HeightContent($altura,$browser){


		if($browser == "Chrome"){

			switch ($altura) {
				case 480:
				$alt= "317";
				break;
				case 600:
				$alt= "437";
				break;
				case 768:
				$alt= "588";
				break;
				case 960:
				$alt= "810";
				break;
				case 1024:
				$alt= "874";
				break;
				case 1080:
				$alt= "900";
				break;
				default:
				$alt= "588";

			}
		}if($browser == "Firefox"){

			switch ($altura) {
				case 768:
				$alt= "580";
				break;
				case 960:
				$alt= "792";
				break;
				case 1024:
				$alt= "856";
				break;
				case 1080:
				$alt= "920";
				break;
				default:
				$alt= "588";

			}
		}
		return $alt;
	}


	/**
	 * MENSAGENS DE ERRO PADRAO PRE CONFIGURADAS
	 * @param string $erro codigo do erro da msg
	 * return msg de erro
	 */

	static function msg_erros($erro)
	{
		echo $erro;
		exit();
	}

	/**
	 * Criptografa a senha do usuário no padrão HXPHP
	 * @param  string $password Senha do usuário
	 * @param  string $salt     Código alfanumérico
	 * @return array            Array com o SALT e a SENHA
	 */
	static function hash_user($password, $salt = null)
	{

		if (is_null($salt))

			$salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));

		$password = hash('sha512', $password.$salt);

		return array(
			'salt' => $salt,
			'password' => $password
		);
	}

	static function CompletaZeros($zeros,$valor)
	{

		$string= str_pad($valor, $zeros, "0", STR_PAD_LEFT);  // retorno "0000000007"

		return $string;
	}

	/*
	Remove caracteres da string
	*/
	static function LimpaString($string)
	{

		$string=str_replace(".","",$string);
		$string=str_replace("-","",$string);
		$string=str_replace("(","",$string);
		$string=str_replace(")","",$string);
		$string=str_replace("/","",$string);
		$string=str_replace(" ","",$string);
		$string=str_replace(",","",$string);
		$string=str_replace("_","",$string);
		return $string;

	}

	/*
	Adiciona mascara no formato indicado
	*/
	static function MascaraCampos($mascara,$string)
	{
		$string	=	str_replace("(","",$string);
		$string	=	str_replace(")","",$string);
		$string	=	str_replace("-","",$string);
		$string	=	str_replace(",","",$string);
		$string	=	str_replace(" ","",$string);
		$string	=	str_replace(".","",$string);
		$mascara	=	str_replace("?","#",$mascara);


		for($i=0;$i<strlen($string);$i++)
		{
			$mascara[strpos($mascara,"#")] = $string[$i];
		}
		return $mascara;
	}

	/*
	 FUNCAO PARA RETIRAR OS CARACTERES DIFERENTES DE NUMEROS DOS VALORES
	 */
	 static function limpaMoney($string)
	 {
	 	$string=str_replace("R$","",$string);
	 	$string=str_replace(".","",$string);
	 	$string=str_replace(" ","",$string);
	 	$string=str_replace(",",".",$string);

	 	return $string;
	 }

	/*
	Troca virgula por ponto
	*/
	static function TiraVirgula($string)
	{
		$string=str_replace(",",".",$string);
		return $string;

	}

	/*
	 dia da semana por extenso
	*/
	 static function DataAtualExtenso($a = 0)
	 {

		// leitura das datas
	 	$dia = date('d');
	 	$mes = date('m');
	 	$ano = date('Y');
	 	$semana = date('w');

		// configuração mes
	 	switch ($mes){
	 		case 1: $mes = "JANEIRO"; break;
	 		case 2: $mes = "FEVEREIRO"; break;
	 		case 3: $mes = "MARÇO"; break;
	 		case 4: $mes = "ABRIL"; break;
	 		case 5: $mes = "MAIO"; break;
	 		case 6: $mes = "JUNHO"; break;
	 		case 7: $mes = "JULHO"; break;
	 		case 8: $mes = "AGOSTO"; break;
	 		case 9: $mes = "SETEMBRO"; break;
	 		case 10: $mes = "OUTUBRO"; break;
	 		case 11: $mes = "NOVEMBRO"; break;
	 		case 12: $mes = "DEZEMBRO"; break;
	 	}
		// configuração semana
	 	switch ($semana) {
	 		case 0: $semana = "DOMINGO"; break;
	 		case 1: $semana = "SEGUNDA FEIRA"; break;
	 		case 2: $semana = "TERÇA-FEIRA"; break;
	 		case 3: $semana = "QUARTA-FEIRA"; break;
	 		case 4: $semana = "QUINTA-FEIRA"; break;
	 		case 5: $semana = "SEXTA-FEIRA"; break;
	 		case 6: $semana = "SÁBADO"; break;
	 	}

	 	if($a == 0){
	 		return ("$semana, $dia DE $mes DE $ano");

	 	}else{return (", $dia DE $mes DE $ano");}

	 }

	/*
	valor por extenso
	*/
	static function valorPorExtenso($valor)
	{

		$singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão","quatrilhão");
		$plural = array("centavos", "reais", "mil", "milhões", "bilhões","trilhões","quatrilhões");

		$c = array("", "cem", "duzentos", "trezentos", "quatrocentos","quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
		$d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta","sessenta", "setenta", "oitenta", "noventa");
		$d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze","dezesseis", "dezesete", "dezoito", "dezenove");
		$u = array("", "um", "dois", "três", "quatro", "cinco", "seis","sete", "oito", "nove");

		$z=0;

		$valor = number_format($valor, 2, ".", ".");

		$inteiro = explode(".", $valor);
		for($i=0;$i<count($inteiro);$i++)
			for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
				$inteiro[$i] = "0".$inteiro[$i];

		// $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
			$fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
			$rt='';
			for ($i=0;$i<count($inteiro);$i++) {
				$valor = $inteiro[$i];
				$rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
				$rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
				$ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]])
				: "";

				$r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd &&
					$ru) ? " e " : "").$ru;
				$t = count($inteiro)-1-$i;
				$r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
				if ($valor == "000")$z++; elseif ($z > 0) $z--;
				if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " :
					"").$plural[$t];
					if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) &&
						($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
				}

			return($rt ? $rt : "zero");
		}

	/*
	 MAIUSCULO E MINUSCULO  O MINUSCULO 1 MAIUSCULO
	 */
	 static function ConverteCase($string,$tipo)
	 {

	 	if ($tipo == "1") 		$palavra = strtr(strtoupper($string),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
	 	elseif ($tipo == "0") 	$palavra = strtr(strtolower($string),"ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß","àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ");
	 	return $palavra;
	 }

	/*
	 Cria uma função que retorna o timestamp de uma data no formato DD/MM/AAAA
	 */
	 static function GeraTimeStamp($data)
	 {
	 	$partes = explode('-', $data);
	 	return mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
	 }


	 /*********************************************** funções de data ********************************************************************/
	/**
	* Função para calcular o próximo dia útil de uma data
	* Formato de entrada da $data: AAAA-MM-DD
	*/
	function ProximoDiaUtil($data, $saida = 'd/m/Y')
	{

		// Converte $data em um UNIX TIMESTAMP
		$timestamp = strtotime($data);

		// Calcula qual o dia da semana de $data
		// O resultado será um valor numérico:
		// 1 -> Segunda ... 7 -> Domingo
		$dia = date('N', $timestamp);

		// Se for sábado (6) ou domingo (7), calcula a próxima segunda-feira
		if ($dia >= 6) {

			$timestamp_final = $timestamp + ((8 - $dia) * 3600 * 24);

		} else {
			// Não é sábado nem domingo, mantém a data de entrada
			$timestamp_final = $timestamp;

		}

		return date($saida, $timestamp_final);
	}

	/*
	Modo de usar
	InvertDateTime(ddmmYYYY,0) para formato de inserção no banco sem separadores ou mascaras
	InvertDateTime(YYYYmmdd,1) para formato de exibição na tela sem separadores ou mascaras
	*/
	static function InvertDateTime($data,$formato)
	{

		if($formato == 0){						// formato para inserção no banco de dados

			$dia=substr("$data",0, 2);
			$mes=substr("$data",2, 2);
			$ano=substr("$data",4, 4);
			$datainv=$ano."-".$mes."-".$dia;

		}if($formato == 1){						// formato para exibição na tela

			$ano=substr("$data",6, 4);
			$mes=substr("$data",3, 2);
			$dia=substr("$data",0, 2);
			$datainv=$dia."/".$mes."/".$ano;

		}

		return $datainv;

	}


	// retorna a idade
	static function CalcularIdade($data_nascimento, $data_calcula)
	{
	    // as datas devem ser no formato aaaa-mm-dd h:m:s
	    //conversão das datas para o formato de tempo linux

		$data_nascimento = strtotime($data_nascimento);
		$data_calcula = strtotime($data_calcula);
	    $idade = floor(abs($data_calcula-$data_nascimento)/60/60/24/365);	//cálculo da idade fazendo a diferença entre as duas datas

	    return($idade);
	}



	// adciona dias a data atual ou data indicada
	static function AddDayIntoDate($date,$days)
	{
		$thisyear  = substr ( $date, 0, 4 );
		$thismonth = substr ( $date, 4, 2 );
		$thisday   = substr ( $date, 6, 2 );
		$nextdate  = mktime ( 0, 0, 0, $thismonth, $thisday + $days, $thisyear );

		return strftime("%Y%m%d", $nextdate);
	}

	/*
	 subatrai  dias da data atual ou data indicada
	*/
	 static function SubDayIntoDate($date,$days)
	 {
	 	$thisyear = substr ( $date, 0, 4 );
	 	$thismonth = substr ( $date, 4, 2 );
	 	$thisday =  substr ( $date, 6, 2 );
	 	$nextdate = mktime ( 0, 0, 0, $thismonth, $thisday - $days, $thisyear );
	 	$dataencontrada=strftime("%Y%m%d", $nextdate);
	 	$sep="-";
	 	$ano=substr("$dataencontrada",0, 4);
	 	$mes=substr("$dataencontrada",4, 2);
	 	$dia=substr("$dataencontrada",6, 2);
	 	$data="$ano$sep$mes$sep$dia";

	 	return $data;
	 }


	/*
	 Referencia
	 Recebe a data e o separador
	*/
	 static function Referencia($referencia,$sep)
	 {

	 	$ano=substr("$referencia",0, 4);
	 	$mes=substr("$referencia",4, 2);
	 	$datainv="$mes$sep$ano";
	 	return $datainv;
	 }

	/*
	 Ano de Referencia
	 recebe a data  e pega o ano da referencia
	*/
	 function AnoDeReferencia($referencia)
	 {

	 	$ano=substr("$referencia",0, 4);
	 	return $ano;
	 }

	/*
	 formato 'data', 'quantidade de dias','acao somar 1 sobtrair 0'  00-00-0000,0'0 ou 1
	
	static function DatasAddSub($data,$dias,$acao)
	{

		//Declaração de uma variável com uma data
		$data =$data;
		//Período (Qtd. de dias para somar ou subtrair)
		$periodo = $dias;

		switch($acao){
			case"0":
			//Subtrair Data
			$data = date('d-m-Y', strtotime("-".$dias." days",strtotime($data)));
			break;
			case"1":
			//Somar Data
			$data = date('d-m-Y', strtotime("+".$dias." days",strtotime($data)));
			break;
		}
	return $data;

	}

*/
	// veriica se é maior que zero
	static function MaiorQueZero($valor){
		if ($valor < 0){
			$total="0";
		}
		else{
			$total=$valor;
		}

		return $total;

	}

	// calendario
	static function Calendario($data,$campo){

		$dataEnviada = $data;// formato 01-05-2013 17:30

		//echo $dataEnviada;
		$diasExtenso = array("DOM","SEG","TER","QUA","QUI","SEX","SAB","");
		$date = DateTime::createFromFormat('d-m-Y H:i', $dataEnviada);
		$feriados = array('01-01','31-12','25-12','01-05','25-04');


		switch($campo){
			case"0":
			$return= $date->format('d-m-Y H:i');//echo 'Data Informada: ',
			break;
			case"1":
			$return= $date->format('w');//echo 'Dia da semana (numero): '
			break;
			case"2":
			$return=$diasExtenso[$date->format('w')];//echo 'Dia da semana (extenso): ',
			break;
			case"3":
			$return=$date->format('t');//echo 'Ultimo dia do mes: ',
			break;
			case"4":
			$return=$date->format('w') == 0 || $date->format('w') == 6 ? 'Sim' : 'Não';//echo 'Final de semana?: ',
			break;
			case"5":
			$return=in_array($date->format('d-m'),$feriados) ? 'Sim' : 'Não';//echo 'É feriado?: ',
			break;
		}
		return $return;
	}


	/*
 MÊS POR EXTENSO
*/
static function MesExtenso($mes)
{

	// configuração mes
	switch ($mes){
	case 1: $mes = "Janeiro"; break;
	case 2: $mes = "Fevereiro"; break;
	case 3: $mes = "Março"; break;
	case 4: $mes = "Abril"; break;
	case 5: $mes = "Maio"; break;
	case 6: $mes = "Junho"; break;
	case 7: $mes = "Julho"; break;
	case 8: $mes = "Agosto"; break;
	case 9: $mes = "Setembro"; break;
	case 10: $mes = "Outubro"; break;
	case 11: $mes = "Novembro"; break;
	case 12: $mes = "Dezembro"; break;
	}
	return $mes;
}




}