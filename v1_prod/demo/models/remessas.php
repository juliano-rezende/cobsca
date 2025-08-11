<?php

class remessas extends ActiveRecord\Model{


/* Tabelas */
static $table_name='remessas_bancarias';



/* function Cod_Tab_Remessa  */
public static function Cod_Tab_Remessa($banco,$tipo_mov){

/* sicoob */
if ($banco == "756") {

    /*
    Movimentação do titulo
    01 = Registro de Títulos
    02 = Solicitação de Baixa
    04 = Concessão de Abatimento
    05 = Cancelamento de Abatimento
    06 = Alteração de Vencimento
    08 = Alteração de Seu Número
    09 = Instrução para Protestar
    10 = Instrução para Sustar Protesto
    11 = Instrução para Dispensar Juros
    12 = Alteração de Pagador
    31 = Alteração de Outros Dados
    34 = Baixa - Pagamento Direto ao Beneficiário
    */

    switch ($tipo_mov) {
      case "MOV00":   $cod_mov="00";  /* MOV00 - Codigo interno do sistema */                 break;
      case "MOV01":	$cod_mov="01";	/* MOV01 - Entrada/Registro de Títulos */               break;
    	case "MOV02":	$cod_mov="02";	/* MOV02 - Solicitação de Baixa */						break;
    	case "MOV03":	$cod_mov="04";	/* MOV03 - Concessão de Abatimento */					break;
    	case "MOV04":	$cod_mov="05";	/* MOV04 - Cancelamento de Abatimento */				break;
    	case "MOV05":	$cod_mov="06";	/* MOV05 - Alteração de Vencimento */					break;
    	case "MOV06":	$cod_mov="08";	/* MOV06 - Alteração de Seu Número */					break;
    	case "MOV07":	$cod_mov="09";	/* MOV07 - Instrução para Protestar */					break;
    	case "MOV08":	$cod_mov="10";	/* MOV08 - Instrução para Sustar Protesto */ 			break;
    	case "MOV09":	$cod_mov="11";	/* MOV09 - Instrução para Dispensar Juros */			break;
    	case "MOV10":	$cod_mov="12";	/* MOV10 - Alteração de Pagador */						break;
    	case "MOV11":	$cod_mov="31";	/* MOV11 - Alteração de Outros Dados */					break;
    	case "MOV12":	$cod_mov="34";	/* MOV12 - Baixa - Pagamento Direto ao Beneficiário */	break;
        case "MOV13":   $cod_mov="35";  /* MOV13 - Codigo interno sistema */                    break; /* proprio do sistema indicando que o titulo foi baixado antes de enviar ao banco */
    	}
    	// retorna p codigo solicitado
    	return $cod_mov;

}if ($banco == "033") {/* santander */


    /*
    Movimentação do titulo
    01 = ENTRADA DE TÍTULO
    02 = BAIXA DE TÍTULO
    04 = CONCESSÃO DE ABATIMENTO
    05 = CANCELAMENTO ABATIMENTO
    06 = PRORROGAÇÃO DE VENCIMENTO
    07 = ALT. NÚMERO CONT.CEDENTE
    08 = ALTERAÇÃO DO SEU NÚMERO
    09 = PROTESTAR
    18 = SUSTAR PROTESTO
    */
    switch ($tipo_mov) {
        case "MOV00":   $cod_mov="00";  /* MOV00 - Codigo interno do sistema */                 break;
        case "MOV01":   $cod_mov="01";  /* MOV01 - Entrada/Registro de Títulos */               break;
        case "MOV02":   $cod_mov="02";  /* MOV02 - Solicitação de Baixa */                      break;
        case "MOV03":   $cod_mov="04";  /* MOV03 - Concessão de Abatimento */                   break;
        case "MOV04":   $cod_mov="05";  /* MOV04 - Cancelamento de Abatimento */                break;
        case "MOV05":   $cod_mov="06";  /* MOV05 - Prorrogação de Vencimento */                 break;
        case "MOV06":   $cod_mov="08";  /* MOV06 - Alteração de Seu Número */                   break;
        case "MOV07":   $cod_mov="09";  /* MOV07 - Instrução para Protestar */                  break;
        case "MOV08":   $cod_mov="18";  /* MOV08 - Instrução para Sustar Protesto */            break;
        case "MOV09":   $cod_mov="";    /* MOV09 - Livre */                                     break;
        case "MOV10":   $cod_mov="";    /* MOV10 - Livre */                                     break;
        case "MOV11":   $cod_mov="";    /* MOV11 - Livre */                                     break;
        case "MOV12":   $cod_mov="02";  /* MOV12 - Baixa - Pagamento Direto ao Beneficiário */  break;
        case "MOV13":   $cod_mov="35";  /* MOV13 - Codigo interno sistema */                    break; /* proprio do sistema indicando que o titulo foi baixado antes de enviar ao banco */
        }
        // retorna p codigo solicitado
        return $cod_mov;

}if ($banco == "104") {/* caixa economica */

/*
Movimentação do titulo
01 = Registro de Títulos | 02 = Solicitação de Baixa | 04 = Concessão de Abatimento | 05 = Cancelamento de Abatimento
06 = Alteração de Vencimento | 08 = Alteração de Seu Número | 09 = Instrução para Protestar | 10 = Instrução para Sustar Protesto
11 = Instrução para Dispensar Juros | 12 = Alteração de Pagador | 31 = Alteração de Outros Dados | 34 = Baixa - Pagamento Direto ao Beneficiário
*/

switch ($tipo_mov) {

        case "MOV00":   $cod_mov="00";  /* MOV00 - Codigo interno do sistema */                 break;
        case "MOV01":   $cod_mov="01";  /* MOV01 - Entrada/Registro de Títulos */               break;
        case "MOV02":   $cod_mov="02";  /* MOV02 - Solicitação de Baixa */                      break;
        case "MOV03":   $cod_mov="04";  /* MOV03 - Concessão de Abatimento */                   break;
        case "MOV04":   $cod_mov="05";  /* MOV04 - Cancelamento de Abatimento */                break;
        case "MOV05":   $cod_mov="06";  /* MOV05 - Alteração de Vencimento */                   break;
        case "MOV06":   $cod_mov="08";  /* MOV06 - Alteração de Seu Número */                   break;
        case "MOV07":   $cod_mov="09";  /* MOV07 - Instrução para Protestar */                  break;
        case "MOV08":   $cod_mov="18";  /* MOV08 - Instrução para Sustar Protesto */            break;
        case "MOV09":   $cod_mov="";    /* MOV09 - Livre */                                     break;
        case "MOV10":   $cod_mov="";    /* MOV10 - Livre */                                     break;
        case "MOV11":   $cod_mov="";    /* MOV11 - Livre */                                     break;
        case "MOV12":   $cod_mov="02";  /* MOV12 - Baixa - Pagamento Direto ao Beneficiário */  break;
        case "MOV13":   $cod_mov="35";  /* MOV13 - Codigo interno sistema */                    break; /* proprio do sistema indicando que o titulo foi baixado antes de enviar ao banco */

    }

    // retorna p codigo solicitado
    return $cod_mov;
}



}/* fim function Cod_Tab_Remessa  */





/* function Cod_Tab_Remessa  */
public static function Det_cod_Remessa($banco,$Cod_mov_titulo){

/* sicoob */
if ($banco == "756") {

    /*
    Movimentação do titulo
    01 = Registro de Títulos
    02 = Solicitação de Baixa
    04 = Concessão de Abatimento
    05 = Cancelamento de Abatimento
    06 = Alteração de Vencimento
    08 = Alteração de Seu Número
    09 = Instrução para Protestar
    10 = Instrução para Sustar Protesto
    11 = Instrução para Dispensar Juros
    12 = Alteração de Pagador
    31 = Alteração de Outros Dados
    34 = Baixa - Pagamento Direto ao Beneficiário
    */

    switch ($Cod_mov_titulo) {
        case "MOV00":   $cod_mov="Codigo interno";                             /* MOV00 - Codigo interno do sistema */                 break;
        case "MOV01":   $cod_mov="Entrada/Registro de Títulos";                /* MOV01 - Entrada/Registro de Títulos */               break;
        case "MOV02":   $cod_mov="Solicitação de cancelamento";                /* MOV02 - Solicitação de Baixa */                      break;
        case "MOV04":   $cod_mov="Concessão de Abatimento ";                    /* MOV03 - Concessão de Abatimento */                   break;
        case "MOV05":   $cod_mov="Cancelamento de Abatimento ";                 /* MOV04 - Cancelamento de Abatimento */                break;
        case "MOV06":   $cod_mov="Alteração de Vencimento ";                    /* MOV05 - Alteração de Vencimento */                   break;
        case "MOV08":   $cod_mov="Alteração de Seu Número ";                    /* MOV06 - Alteração de Seu Número */                   break;
        case "MOV09":   $cod_mov="Instrução para Protestar ";                   /* MOV07 - Instrução para Protestar */                  break;
        case "MOV10":   $cod_mov="Instrução para Sustar Protesto ";             /* MOV08 - Instrução para Sustar Protesto */            break;
        case "MOV11":   $cod_mov="Instrução para Dispensar Juros ";             /* MOV09 - Instrução para Dispensar Juros */            break;
        case "MOV12":   $cod_mov="Alteração de Pagador ";                       /* MOV10 - Alteração de Pagador */                      break;
        case "MOV31":   $cod_mov="Alteração de Outros Dados ";                  /* MOV11 - Alteração de Outros Dados */                 break;
        case "MOV34":   $cod_mov="Baixa - Pagamento Direto ao Beneficiário ";   /* MOV12 - Baixa - Pagamento Direto ao Beneficiário */  break;
        case "MOV35":   $cod_mov="Codigo baixa interna";                        /* MOV13 - Codigo interno sistema */                    break; /* proprio do sistema indicando que o titulo foi baixado antes de enviar ao banco */
        default:
       $cod_mov="i is not equal to 0, 1 or 2";
}
        // retorna p codigo solicitado
        return $cod_mov;

}if ($banco == "033") {/* santander */


    /*
    Movimentação do titulo
    01 = ENTRADA DE TÍTULO
    02 = BAIXA DE TÍTULO
    04 = CONCESSÃO DE ABATIMENTO
    05 = CANCELAMENTO ABATIMENTO
    06 = PRORROGAÇÃO DE VENCIMENTO
    07 = ALT. NÚMERO CONT.CEDENTE
    08 = ALTERAÇÃO DO SEU NÚMERO
    09 = PROTESTAR
    18 = SUSTAR PROTESTO
    */
    switch ($Cod_mov_titulo) {
        case "MOV00":   $cod_mov="odigo interno do sistema ";       /* MOV00 - Codigo interno do sistema */                 break;
        case "MOV01":   $cod_mov="Entrada/Registro de Títulos";     /* MOV01 - Entrada/Registro de Títulos */               break;
        case "MOV02":   $cod_mov="Solicitação de Baixa ";           /* MOV02 - Solicitação de Baixa */                      break;
        case "MOV03":   $cod_mov="Concessão de Abatimento";         /* MOV03 - Concessão de Abatimento */                   break;
        case "MOV04":   $cod_mov="Cancelamento de Abatimento";      /* MOV04 - Cancelamento de Abatimento */                break;
        case "MOV05":   $cod_mov="Prorrogação de Vencimento ";      /* MOV05 - Prorrogação de Vencimento */                 break;
        case "MOV06":   $cod_mov="Alteração de Seu Número";         /* MOV06 - Alteração de Seu Número */                   break;
        case "MOV07":   $cod_mov="Instrução para Protestar";        /* MOV07 - Instrução para Protestar */                  break;
        case "MOV08":   $cod_mov="Instrução para Sustar Protesto";  /* MOV08 - Instrução para Sustar Protesto */            break;
        case "MOV09":   $cod_mov="";                                /* MOV09 - Livre */                                     break;
        case "MOV10":   $cod_mov="";                                /* MOV10 - Livre */                                     break;
        case "MOV11":   $cod_mov="";                                /* MOV11 - Livre */                                     break;
        case "MOV12":   $cod_mov="Baixa - Pagamento Direto ao Beneficiário";  /* MOV12 - Baixa - Pagamento Direto ao Beneficiário */  break;
        case "MOV13":   $cod_mov="Codigo interno sistema";  /* MOV13 - Codigo interno sistema */                    break; /* proprio do sistema indicando que o titulo foi baixado antes de enviar ao banco */
        }

        // retorna p codigo solicitado
        return $cod_mov;

}if ($banco == "104") {/* caixa economica */

/*
Movimentação do titulo
01 = Registro de Títulos | 02 = Solicitação de Baixa | 04 = Concessão de Abatimento | 05 = Cancelamento de Abatimento
06 = Alteração de Vencimento | 08 = Alteração de Seu Número | 09 = Instrução para Protestar | 10 = Instrução para Sustar Protesto
11 = Instrução para Dispensar Juros | 12 = Alteração de Pagador | 31 = Alteração de Outros Dados | 34 = Baixa - Pagamento Direto ao Beneficiário
*/

switch ($Cod_mov_titulo) {

        case "MOV00":   $cod_mov="00";  /* MOV00 - Codigo interno do sistema */                 break;
        case "MOV01":   $cod_mov="01";  /* MOV01 - Entrada/Registro de Títulos */               break;
        case "MOV02":   $cod_mov="02";  /* MOV02 - Solicitação de Baixa */                      break;
        case "MOV03":   $cod_mov="04";  /* MOV03 - Concessão de Abatimento */                   break;
        case "MOV04":   $cod_mov="05";  /* MOV04 - Cancelamento de Abatimento */                break;
        case "MOV05":   $cod_mov="06";  /* MOV05 - Alteração de Vencimento */                   break;
        case "MOV06":   $cod_mov="08";  /* MOV06 - Alteração de Seu Número */                   break;
        case "MOV07":   $cod_mov="09";  /* MOV07 - Instrução para Protestar */                  break;
        case "MOV08":   $cod_mov="18";  /* MOV08 - Instrução para Sustar Protesto */            break;
        case "MOV09":   $cod_mov="";    /* MOV09 - Livre */                                     break;
        case "MOV10":   $cod_mov="";    /* MOV10 - Livre */                                     break;
        case "MOV11":   $cod_mov="";    /* MOV11 - Livre */                                     break;
        case "MOV12":   $cod_mov="02";  /* MOV12 - Baixa - Pagamento Direto ao Beneficiário */  break;
        case "MOV13":   $cod_mov="35";  /* MOV13 - Codigo interno sistema */                    break; /* proprio do sistema indicando que o titulo foi baixado antes de enviar ao banco */

    }

    // retorna p codigo solicitado
    return $cod_mov;
}



}/* fim function Det_cod_Remessa  */





/*
LIMITE DE CARACTERES
*/
public static  function Limit($palavra,$limite){
    if(strlen($palavra) >= $limite)
    {
        $var = substr($palavra, 0,$limite);
    }
    else
    {
        $max = (int)($limite-strlen($palavra));
        $var = $palavra.remessas::ComplementoRegistro($max,"brancos");
    }
    return $var;
}


/*
ZEROS
*/

public static function zeros($min,$max)
{
    $zeros="";
	$x = ($max - strlen($min));
    for($i = 0; $i < $x; $i++)
    {
        $zeros .= '0';
    }
    return $zeros.$min;
}

/*
SEQUENCIAL
*/
public static function Sequencial($i)
{
    if($i < 10)
    {
        return self::zeros(0,5).$i;
    }
    else if($i >= 10 && $i < 100)
    {
        return self::zeros(0,4).$i;
    }
    else if($i >= 100 && $i < 1000)
    {
        return self::zeros(0,3).$i;
    }
    else if($i >= 1000 && $i < 10000)
    {
        return self::zeros(0,2).$i;
    }
    else if($i >= 10000 && $i < 100000)
    {
        return self::zeros(0,1).$i;
    }
}

/*
REMOVER VIRGULAS E PONTO DE VALORES
*/
public static function TiraMoeda($valor){
	$pontos = array(",", ".");
	$result = str_replace($pontos, "", $valor);
	return $result;
}

/*
TIRA MOEDA 1
*/
public static function TiraMoeda1($valor){
	$pontos = '.';
	$virgula = ',';
	$result = str_replace($pontos, "", $valor);
	$result2 = str_replace($virgula, ".", $result);
	return $result2;
}


/*
COMPLEMENTA REGISTROS
*/
public static function ComplementoRegistro($int,$tipo)
{
    if($tipo == "zeros")
    {
        $space = '';
        for($i = 1; $i <= $int; $i++)
        {
            $space .= '0';
        }
    }
    else if($tipo == "brancos")
    {
        $space = '';
        for($i = 1; $i <= $int; $i++)
        {
            $space .= ' ';
        }
    }
    return $space;
}


/*
MODULO 11
*/

// função modelo 11 para gerar o divisor do nosso numero
public static function modulo_11_sicoob($num, $base=9, $r=0) {
    $soma = 0;
    $fator = 2;
    for ($i = strlen($num); $i > 0; $i--) {
        $numeros[$i] = substr($num,$i-1,1);
        $parcial[$i] = $numeros[$i] * $fator;
        $soma += $parcial[$i];
        if ($fator == $base) {
            $fator = 1;
        }
        $fator++;
    }
    if ($r == 0) {
        $soma *= 10;
        $digito = $soma % 11;
        //corrigido
        if ($digito == 10) {
            $digito = "X";
        }

        /*
        alterado por mim, Daniel Schultz

        Vamos explicar:

        O módulo 11 só gera os digitos verificadores do nossonumero,
        agencia, conta e digito verificador com codigo de barras (aquele que fica sozinho e triste na linha digitável)
        só que é foi um rolo...pq ele nao podia resultar em 0, e o pessoal do phpboleto se esqueceu disso...
        No BB, os dígitos verificadores podem ser X ou 0 (zero) para agencia, conta e nosso numero,
        mas nunca pode ser X ou 0 (zero) para a linha digitável, justamente por ser totalmente numérica.

        Quando passamos os dados para a função, fica assim:

        Agencia = sempre 4 digitos
        Conta = até 8 dígitos
        Nosso número = de 1 a 17 digitos

        A unica variável que passa 17 digitos é a da linha digitada, justamente por ter 43 caracteres

        Entao vamos definir ai embaixo o seguinte...

        se (strlen($num) == 43) { não deixar dar digito X ou 0 }
        */
        if (strlen($num) == "43") {
            //então estamos checando a linha digitável
            if ($digito == "0" or $digito == "X" or $digito > 9) {
                    $digito = 1;
            }
        }
        return $digito;
    }
    elseif ($r == 1){
        $resto = $soma % 11;
        return $resto;
    }
}





}
?>