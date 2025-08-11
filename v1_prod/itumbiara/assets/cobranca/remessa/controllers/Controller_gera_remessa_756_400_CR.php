<?php

$Frm_cad    =   true;// fala pra sessão não encerra pois é uma janela de cadastro

set_time_limit(0);
require_once"../../../../sessao.php";
require_once("../../../../conexao.php");
$cfg->set_model_directory('../../../../models/');


$FRM_cod_banco0 = isset( $_POST['cod_banco_rem'])  ?  $_POST['cod_banco_rem'] : tool::msg_erros("O Campo cod_banco_rem é Obrigatorio.");
$FRM_dtinirem  = isset( $_POST['dtinirem'])  ?  tool::InvertDateTime(tool::LimpaString($_POST['dtinirem']),"-") : tool::msg_erros("O Campo dtinirem é Obrigatorio.");
$FRM_dtinifim  = isset( $_POST['dtinifim'])  ?  tool::InvertDateTime(tool::LimpaString($_POST['dtinifim']),"-") : tool::msg_erros("O Campo dtinifim é Obrigatorio.");


// trata o cod do banco
$FRM_cod_banco  = explode("_",$FRM_cod_banco0);
$FRM_cod_banco  = $FRM_cod_banco[0];


// assume $str esteja em UTF-8
$map = array(
    'á' => 'a',
    'à' => 'a',
    'ã' => 'a',
    'â' => 'a',
    'é' => 'e',
    'ê' => 'e',
    'í' => 'i',
    'ó' => 'o',
    'ô' => 'o',
    'õ' => 'o',
    'ú' => 'u',
    'ü' => 'u',
    'ç' => 'c',
    'Á' => 'A',
    'À' => 'A',
    'Ã' => 'A',
    'Â' => 'A',
    'É' => 'E',
    'Ê' => 'E',
    'Í' => 'I',
    'Ó' => 'O',
    'Ô' => 'O',
    'Õ' => 'O',
    'Ú' => 'U',
    'Ü' => 'U',
    'Ç' => 'C'
);


$fusohorario    = 3; // como o servidor de hospedagem é a dreamhost pego o fuso para o horario do brasil
$timestamp      = mktime(date("H") - $fusohorario, date("i"), date("s"), date("m"), date("d"), date("Y"));

$DATAHORA['PT'] = gmdate("d/m/Y H:i:s", $timestamp);
$DATAHORA['EN'] = gmdate("Y-m-d H:i:s", $timestamp);
$DATA['PT']     = gmdate("d/m/Y", $timestamp);
$DATA['EN']     = gmdate("Y-m-d", $timestamp);
$DATA['DIA']    = gmdate("d",$timestamp);
$DATA['MES']    = gmdate("m",$timestamp);
$DATA['ANO']    = gmdate("y",$timestamp);
$HORA           = gmdate("H:i:s", $timestamp);


$dadosempresa=empresas::find_by_sql("SELECT
                                        empresas.razao_social,empresas.cnpj,
                                        contas_bancarias.id as conta_id,contas_bancarias.agencia,contas_bancarias.dv_agencia,contas_bancarias.conta,contas_bancarias.dv_conta,
                                        contas_bancarias_cobs.cod_cedente,contas_bancarias_cobs.dv_cod_cedente,contas_bancarias_cobs.carteira_remessa,contas_bancarias_cobs.aceite,
                                        configs.juros,configs.multa
                                     FROM
                                      empresas
                                     LEFT JOIN
                                      contas_bancarias ON contas_bancarias.empresas_id =  empresas.id
                                     LEFT JOIN
                                      contas_bancarias_cobs ON contas_bancarias_cobs.contas_bancarias_id =  contas_bancarias.id
                                     LEFT JOIN
                                      configs ON configs.empresas_id =  empresas.id
                                     WHERE empresas.id='".$COB_Empresa_Id."' AND
                                           contas_bancarias.cod_banco='".$FRM_cod_banco."' AND
                                           contas_bancarias.tp_conta='2' AND
                                           contas_bancarias.status='1' ");


$lote = remessas::find_by_sql("SELECT MAX((lote_remessa)+1) AS novolote
                               FROM  remessas_bancarias
                               WHERE cod_banco = '".$FRM_cod_banco."' AND
                               contas_bancarias_id='".$dadosempresa[0]->conta_id."' AND
                               empresas_id='".$COB_Empresa_Id."' ");

$novolote=$lote[0]->novolote;

if ($novolote=="") {$lte=1;}else {$lte=$novolote;}


$filename = "REM_".$DATA['DIA'].$DATA['MES'].$DATA['ANO'].$lte.".REM";

## REGISTRO HEADER ( TIPO 0)
                                #NOME DO CAMPO						          #SIGNIFICADO                                                                            #POSICAO       #PICTURE
$conteudo0 ='';
$conteudo0 .= '0';             					              		          //	Identificação do Registro Header: “0” (zero)                                       001 001        9(001)
$conteudo0 .= '1';             										          //	Tipo de Operação: “1” (um)				                                           002 002        9(001)
$conteudo0 .= 'REMESSA';        			    						          //	Identificação por Extenso do Tipo de Operação: "REMESSA"				           003 009        A(007)
$conteudo0 .= '01';           										          //	Identificação do Tipo de Serviço: “01” (um)                                        010 011        9(002)
$conteudo0 .= utf8_decode('COBRANÇA');    					        //	Identificação por Extenso do Tipo de Serviço: “COBRANÇA”                           012 019        A(008)
$conteudo0 .= remessas::ComplementoRegistro(7,"brancos");    		          //	Complemento do Registro: Brancos			                                       020 026        A(007)
$conteudo0 .= remessas::Limit($dadosempresa[0]->agencia,4);          //	Prefixo da Cooperativa: vide planilha "Capa"                                                   027 030        A(004)
$conteudo0 .= remessas::Limit($dadosempresa[0]->dv_agencia,1);       //  Dígito Verificador do Prefixo: vide planilha "Capa"                                            031 031        9(001)
$conteudo0 .= tool::completazeros(8,$dadosempresa[0]->cod_cedente);  //  Código do Cliente/Beneficiário: vide planilha "Capa"                                           032 039        9(008)
$conteudo0 .= remessas::Limit($dadosempresa[0]->dv_cod_cedente,1);   //  Dígito Verificador do Código: vide planilha "Capa"                                             040 040        9(001)
$conteudo0 .= remessas::ComplementoRegistro(6,"brancos");            //  Número do convênio líder: Brancos                                                              041 046        A(006)

$rz        = remessas::Limit($dadosempresa[0]->razao_social,30);    //  Nome do Beneficiário: vide planilha "Capa"                                                     047 076        A(030)
$conteudo0 .= strtoupper($rz);

$conteudo0 .= remessas::Limit('756BANCOOBCED',18);                   //  Identificação do Banco: "756BANCOOBCED"                                                        077 094        A(018)
$conteudo0.= $DATA['DIA'].$DATA['MES'].$DATA['ANO'];                //  Data da Gravação da Remessa: formato ddmmaa                                                    095 100        9(006)
$conteudo0 .= tool::Completazeros(7,$lte);                           //  Seqüencial da Remessa: número seqüencial acrescido de 1 a cada remessa. Inicia com "0000001"   101 107        A(007)
$conteudo0 .= remessas::ComplementoRegistro(287,"brancos");          //  Complemento do Registro: Brancos                                                               108 394        A(287)
$conteudo0 .= tool::Completazeros(6,1);                              //  Seqüencial do Registro:”000001” PRIMEIRA LINHA DO ARQUIVO                                      395 400        9(006)
$conteudo0 .= chr(13).chr(10);                                       //  essa é a quebra de linha


// pega todos os boletos pessoa fisica
$query_titulos=titulos::find_by_sql("SELECT *
                                     FROM titulos_bancarios
                                     WHERE dv_nosso_numero >='0' AND
                                     contas_bancarias_id='".$dadosempresa[0]->conta_id."' AND
                                     stflagrem='1' AND cod_mov_rem!='35' AND
                                     (dt_emissao BETWEEN '".$FRM_dtinirem."' AND '".$FRM_dtinifim."' OR dt_atualizacao BETWEEN '".$FRM_dtinirem."' AND '".$FRM_dtinifim."') ORDER BY nosso_numero ASC");

$list_titulo= new ArrayIterator($query_titulos);


// variaveis quantitativas
$qtelinhas=0;														// define a variavel quantidade de linhas no arquivo como 0
$vltotal=0;															// define o valor total do arquivo como 0
$i = 2;																// define o inicio do contador de linhas como 2 pois a primeira está na header seq 000001


$conteudo1 ='';  // define a variavel conteudo 1 antes do loop



while($list_titulo->valid()):


/* definimos a data de vencimento do boleto*/
$dtvenc = new ActiveRecord\DateTime($list_titulo->current()->dt_vencimento);
$dtemi  = new ActiveRecord\DateTime($list_titulo->current()->dt_emissao);

if($dadosempresa[0]->aceite == "N"){$aceite="0";}else{$aceite="1";}



/********************************************************* INICIO DA ESCRITA DA LINHA  *****************************************************/

# REGISTRO DETALHE ( TIPO 1) (OBRIGATORIO)
																		                          #NOME DO CAMPO                					                 	   #POSICAO         #PICTURE
$conteudo1 .= "1";                                                                                // tIdentificação do Registro Detalhe: 1 (um)                  001 001           9(01)
$conteudo1 .= '02';                                                                               // "Tipo de Inscrição do Beneficiário:                         002 003          9(02)
                                                                                                 //  01" = CPF
                                                                                                 //  02" = CNPJ
$conteudo1 .= remessas::Limit($dadosempresa[0]->cnpj,14);                                         // Número do CPF/CNPJ do Beneficiário                          004 017          9(14)
$conteudo1 .= $dadosempresa[0]->agencia;                                                          // Prefixo da Cooperativa: vide planilha "Capa"                018 021          9(04)
$conteudo1 .= $dadosempresa[0]->dv_agencia;                                                       // Dígito Verificador do Prefixo: vide planilha "Capa"         022 022          9(01)
$conteudo1 .= tool::CompletaZeros(8,$dadosempresa[0]->conta);                                     // conta corrente                                              023 030          9(08)
$conteudo1 .= $dadosempresa[0]->dv_conta;                                                         // Dígito Verificador conta corrente                           031 031          x(01)
$conteudo1 .= remessas::ComplementoRegistro(6,"zeros");                                           // Número do convênio de cobrança do beneficiario "000000"     032 037          9(06)
$conteudo1 .= remessas::ComplementoRegistro(25,"brancos");                                        // Número de controle do participante                          038 062          x(25)
$conteudo1 .= tool::CompletaZeros(11,$list_titulo->current()->nosso_numero);                      // USO / IDENT. DO TÍTULO NO BANCO  nosso numero + dv          063
$conteudo1 .= $list_titulo->current()->dv_nosso_numero;                                           //                                                                 074          9(12)

$conteudo1 .= "01";                                                                               // Numero da parcela 01 se unica                               075 076          9(02)
$conteudo1 .= "00";                                                                               // Grupo de valor "00"                                         077 078          9(02)
$conteudo1 .= remessas::ComplementoRegistro(3,"brancos");                                         // Complemento de registro                                     079 081          x(03)

/******************************************************************************************************************************************************************************************/

$conteudo1 .= remessas::ComplementoRegistro(1,"brancos");                                         // "Indicativo de Mensagem ou Sacador/Avalista:                082 082          x(25)
                                                                                                 // Brancos: Poderá ser informada nas posições 352 a 391 (SEQ 50) qualquer mensagem para ser
                                                                                                 // impressa no boleto; “A”: Deverá ser informado nas posições 352 a 391 (SEQ 50) o nome e CPF
                                                                                                 // CNPJ do sacador"
/******************************************************************************************************************************************************************************************/

$conteudo1.= remessas::ComplementoRegistro(3,"brancos");                                         // Prefixo do titulo brancos                                    083 085          x(03)
$conteudo1 .= "000";                                                                              // Variação de carteira                                         086 088          9(03)
$conteudo1 .= "0";                                                                                // Conta caução                                                 089 089          9(01)

/******************************************************************************************************************************************************************************************/

$conteudo1 .= "00000";                                                                            // "Número do Contrato Garantia: Para Carteira 1 preencher ""00000"";
                                                                                                 // Para Carteira 3 preencher com o  número do contrato sem DV."
                                                                                                 //                                                              090 094          9(05)
/******************************************************************************************************************************************************************************************/

$conteudo1 .= "0";                                                                                // "DV do contrato:  Para Carteira 1 preencher ""0"";
                                                                                                 // Para Carteira 3 preencher com o Dígito Verificador."
                                                                                                 //                                                              095 095          x(01)
$conteudo1 .= remessas::ComplementoRegistro(6,"brancos");                                         // Numero do borderô: preencher em caso de carteira             096 101          9(06)
$conteudo1 .= remessas::ComplementoRegistro(4,"brancos");                                         // Complemento de registro                                      102 105          x(04)
$conteudo1 .= "2";                                                                                // Tipo de emissao 1 cooperatica 2 cliente                      106 106          9(01)
$conteudo1 .= tool::CompletaZeros(2,$dadosempresa[0]->carteira_remessa);                          /* Carteira/Modalidade:                                         107 108          9(02)
                                                                                                    01 = Simples Com Registro
                                                                                                    02 = Simples Sem Registro
                                                                                                    03 = Garantida Caucionada "*/

/******************************************************************************************************************************************************************************************/

$conteudo1 .= tool::CompletaZeros(2,$list_titulo->current()->cod_mov_rem);                           /* Comando/Movimento:                                        109 110          9(02)
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
                                                                                                    34 = Baixa - Pagamento Direto ao Beneficiário**/

/******************************************************************************************************************************************************************************************/

$conteudo1 .= tool::CompletaZeros(10,$list_titulo->current()->numero_doc);                        // Numero para controle interno                                 111 120          X(10)
$conteudo1 .= $dtvenc->format('dmy');                                                             // Data de vencimento normal DDMMYY                             121 126          A(06)
                                                                                                 // A vista 888888
                                                                                                 // contra Apresentação 999999

/******************************************************************************************************************************************************************************************/

$valor_formatado=tool::limpamoney(number_format($list_titulo->current()->vlr_nominal,2,',','.') );
$valor_formatado=tool::limpamoney($valor_formatado);
$conteudo1 .= tool::CompletaZeros(13,$valor_formatado);                                           // valor titulo valor nominal moeda corrente    9(11)V99        127 139         9(013)1
$conteudo1 .= $FRM_cod_banco;                                                                     // Codigo do banco.                                         140 142         9(03)
$conteudo1 .= $dadosempresa[0]->agencia;                                                          // Prefixo da Cooperativa: vide planilha "Capa"                 143 143         9(04)
$conteudo1 .= remessas::Limit($dadosempresa[0]->dv_agencia,1);                                    // Dígito Verificador do Prefixo: vide planilha "Capa"          147 147         9(01)

/******************************************************************************************************************************************************************************************/

$conteudo1 .= "01";                                                                               // Espécie de documento                                         148 149         9(02)
                                                                                                 /* "Espécie do Título :
                                                                                                    01 = Duplicata Mercantil
                                                                                                    02 = Nota Promissória
                                                                                                    03 = Nota de Seguro
                                                                                                    05 = Recibo
                                                                                                    06 = Duplicata Rural
                                                                                                    08 = Letra de Câmbio
                                                                                                    09 = Warrant
                                                                                                    10 = Cheque
                                                                                                    12 = Duplicata de Serviço
                                                                                                    13 = Nota de Débito
                                                                                                    14 = Triplicata Mercantil
                                                                                                    15 = Triplicata de Serviço
                                                                                                    18 = Fatura
                                                                                                    20 = Apólice de Seguro
                                                                                                    21 = Mensalidade Escolar
                                                                                                    22 = Parcela de Consórcio
                                                                                                    99 = Outros"*/

/******************************************************************************************************************************************************************************************/

$conteudo1 .= $aceite;                                                                            // "Aceite do Título: 0 = Sem aceite 1 = Com aceite"           150 150         9(01)
$conteudo1 .= $dtemi->format('dmy');                                                              // Data de emissao formato DDMMYY                              151 166         9(06)

/******************************************************************************************************************************************************************************************/

$conteudo1 .= "01";                                                                               // Espécie de documento                                        157 158         9(02)
                                                                                                 /* "Primeira instrução codificada:
                                                                                                    Regras de impressão de mensagens nos boletos:
                                                                                                    * Primeira instrução (SEQ 34) = 00 e segunda (SEQ 35) = 00, não imprime nada.
                                                                                                    * Primeira instrução (SEQ 34) = 01 e segunda (SEQ 35) = 01, desconsidera-se as instruções CNAB e imprime as mensagens relatadas no trailler do arquivo.
                                                                                                    * Primeira e segunda instrução diferente das situações acima, imprimimos o conteúdo CNAB:
                                                                                                      00 = AUSENCIA DE INSTRUCOES
                                                                                                      01 = COBRAR JUROS
                                                                                                      03 = PROTESTAR 3 DIAS UTEIS APOS VENCIMENTO
                                                                                                      04 = PROTESTAR 4 DIAS UTEIS APOS VENCIMENTO
                                                                                                      05 = PROTESTAR 5 DIAS UTEIS APOS VENCIMENTO
                                                                                                      07 = NAO PROTESTAR
                                                                                                      10 = PROTESTAR 10 DIAS UTEIS APOS VENCIMENTO
                                                                                                      15 = PROTESTAR 15 DIAS UTEIS APOS VENCIMENTO
                                                                                                      20 = PROTESTAR 20 DIAS UTEIS APOS VENCIMENTO
                                                                                                      22 = CONCEDER DESCONTO SO ATE DATA ESTIPULADA
                                                                                                      42 = DEVOLVER APOS 15 DIAS VENCIDO
                                                                                                      43 = DEVOLVER APOS 30 DIAS VENCIDO"
                                                                                                    */
$conteudo1 .= "01";                                                                               // Segunda instrução: vide SEQ 33                              159 160         9(02)

/******************************************************************************************************************************************************************************************/
$conteudo1 .= tool::CompletaZeros(6,remessas::Limit(tool::LimpaString($dadosempresa[0]->juros / 12),5));   // "Taxa de mora mês Ex: 022000 = 2,20%)"    9(02)V9999        161 166         9(06)

/******************************************************************************************************************************************************************************************/

$valor_multa=str_pad(tool::LimpaString($dadosempresa[0]->multa),6);
$conteudo1 .= $valor_multa;                                                                       // "multa por atrazos Ex: 022000 = 2,20%)"   9(02)V9999         167 172         9(06)

$conteudo1 .= "2";                                                                               // "Tipo de Inscrição do Pagador: ""01"" = CPF ""02"" = CNPJ "  173 173         9(01)
$conteudo1 .= "000000";                                                                          // Data primeiro desconto                                       174 179         9(06)
$conteudo1 .= tool::CompletaZeros(13,0);                                                         // Valor primeiro desconto 9(11)V9999                           180 192         9(13)
$conteudo1 .= "9".tool::CompletaZeros(12,0);                                                     /*"193-193 – Código da moeda 9                                  183 205         9(13)
                                                                                                   194-205 – Valor IOF / Quantidade Monetária: ""000000000000""
                                                                                                   Se o código da moeda for REAL, o valor restante representa o IOF. Se o código da moeda for diferente de REAL, o valor restante será a quantidade monetária.
                                                                                                */

/*****************************************************************************************************************************************************************/

$conteudo1 .= tool::CompletaZeros(13,0);                                                         // Valor do abatimento 9(11)V9999                               206 218         9(13)
$conteudo1 .= $list_titulo->current()->tp_sacado;                                                // tipo de inscrição do pagado                                  219 220         9(02)
$conteudo1 .= remessas::Limit(tooL::Completazeros(14,$list_titulo->current()->cpfcnpjsacado),14);// Número do CPF/CNPJ do sacado                                 221 234         9(14)
$conteudo1 .= remessas::Limit(strtoupper($list_titulo->current()->sacado),40);                   // Nome sacado                                                  235 244         A(40)

/*****************************************************************************************************************************************************************/

$logradouro=strtr($list_titulo->current()->logradouro, $map);                                   // rua numero e compl sacado                                    275 311         X(32+4)
$num=$list_titulo->current()->num;
$conteudo1 .= remessas::Limit(strtoupper($logradouro),32).",".remessas::Limit($num,4);

/******************************************************************************************************************************************************************************************/

$bairro=strtr(strtoupper($list_titulo->current()->bairro), $map);                                   // bairro                                                       312 326         X(15)

$conteudo1 .= remessas::Limit($bairro,15);

/******************************************************************************************************************************************************************************************/
$cep =  tool::LimpaString($list_titulo->current()->cep);
$conteudo1 .= remessas::Limit(substr($cep,0,5),5);                                                 // cep do sacado                                                327 331         9(05)
$conteudo1 .= remessas::Limit(substr($cep,5,3),3);                                                 // Complemento cep do sacado                                    322 334         9(03)

/******************************************************************************************************************************************************************************************/

$cidade=strtr(utf8_encode(strtoupper($list_titulo->current()->cidade)), $map);                   // municipio do sacado                                          335 349         X(15)
$conteudo1 .= remessas::Limit($cidade,15);

/******************************************************************************************************************************************************************************************/

$conteudo1.= remessas::Limit(strtoupper($list_titulo->current()->uf),2);                       // uf do sacado                                                 350 351          X(02)

/******************************************************************************************************************************************************************************************/

//CALCULA O VALOR DE JUROS
// recupera a taxa de juros da empresa
$jurosmes   = ($dadosempresa[0]->juros/12);
$jurosdia   = ($jurosmes/30);
$juros      = $jurosdia/100;

 $juros_ao_dia  = ($list_titulo->current()->vlr_nominal * $juros) ;

//CALCULA A MULTA
 $multa = $dadosempresa[0]->multa/100;
 $multa_atrazo  = ($list_titulo->current()->vlr_nominal * $multa) ;

// INSTRUÇÕES PARA O CAIXA
//$dadosboleto["instrucoes"] = strtoupper("COBRAR ".number_format($juros_ao_dia,2,",",".")." JUROS/DIA E ".number_format($multa_atrazo,2,",",".")." DE MULTA.");
//$dadosboleto["instrucoes_detalhes"] = " SENHOR CAIXA APOS O VENCIMENTO COBRAR JUROS E MULTA!";
$conteudo1 .= remessas::Limit("SENHOR CAIXA APOS O VENCIMENTO COBRAR JUROS E MULTA!",40);    // Observações/Mensagem ou Sacador/Avalista                     352 391          X(40)
                                                                                                /*
                                                                                                Quando o SEQ 14 – Indicativo de Mensagem ou Sacador/Avalista - for preenchido com Brancos, as informações constantes desse campo serão impressas no campo “texto de responsabilidade da Empresa”, no Recibo do Sacado e na Ficha de Compensação do boleto de cobrança.

                                                                                                Quando o SEQ 14 – Indicativo de Mensagem ou Sacador/Avalista - for preenchido com “A” , este campo deverá ser preenchido com o nome/razão social do Sacador/Avalista"
                                                                                                */
/******************************************************************************************************************************************************************************************/

$conteudo1 .= "00";                                                                              // Número de Dias Para Protesto:                              392 393          x(2)
                                                                                                /*Quantidade dias para envio protesto. Se = ""0"", utilizar dias protesto padrão do cliente cadastrado na cooperativa. */
/******************************************************************************************************************************************************************************************/

$conteudo1 .= remessas::ComplementoRegistro(1,"brancos");                                        // Complementeo de registros : brancos                        394 395          x(1)
$conteudo1 .= remessas::sequencial($i);                                                          // numero sequencial    do registro no arquivo                395 400          9(06)
$conteudo1 .= chr(13).chr(10);                                                                   //essa é a quebra de linha

/* monta a linha a ser salva no banco de dados*/
$linha_remessa="";
$linha_remessa = "1";
$linha_remessa = '02';
$linha_remessa = remessas::Limit($dadosempresa[0]->cnpj,14);
$linha_remessa = $dadosempresa[0]->agencia;
$linha_remessa = $dadosempresa[0]->dv_agencia;
$linha_remessa = tool::CompletaZeros(8,$dadosempresa[0]->conta);
$linha_remessa = $dadosempresa[0]->dv_conta;
$linha_remessa = remessas::ComplementoRegistro(6,"zeros");
$linha_remessa = remessas::ComplementoRegistro(25,"brancos");
$linha_remessa = tool::CompletaZeros(11,$list_titulo->current()->nosso_numero);
$linha_remessa = $list_titulo->current()->dv_nosso_numero;
$linha_remessa = "01";
$linha_remessa = "00";
$linha_remessa = remessas::ComplementoRegistro(3,"brancos");
$linha_remessa = remessas::ComplementoRegistro(1,"brancos");
$linha_remessa = remessas::ComplementoRegistro(3,"brancos");
$linha_remessa = "000";
$linha_remessa = "0";
$linha_remessa = "00000";
$linha_remessa = "0";
$linha_remessa = remessas::ComplementoRegistro(6,"brancos");
$linha_remessa = remessas::ComplementoRegistro(4,"brancos");
$linha_remessa = "2";
$linha_remessa = tool::CompletaZeros(2,$dadosempresa[0]->carteira_remessa);
$linha_remessa = tool::CompletaZeros(2,$list_titulo->current()->cod_mov_rem);
$linha_remessa = tool::CompletaZeros(10,$list_titulo->current()->numero_doc);
$linha_remessa = $dtvenc->format('dmy');
$valor_formatado=tool::limpamoney(number_format($list_titulo->current()->vlr_nominal,2,',','.') );
$valor_formatado=tool::limpamoney($valor_formatado);
$linha_remessa = tool::CompletaZeros(13,$valor_formatado);
$linha_remessa = $FRM_cod_banco;
$linha_remessa = $dadosempresa[0]->agencia;
$linha_remessa = remessas::Limit($dadosempresa[0]->dv_agencia,1);
$linha_remessa = "01";
$linha_remessa = $aceite;
$linha_remessa = $dtemi->format('dmy');
$linha_remessa = "01";
$linha_remessa = "01";
$linha_remessa = tool::CompletaZeros(6,remessas::Limit(tool::LimpaString($dadosempresa[0]->juros / 12),5));
$valor_multa=str_pad(tool::LimpaString($dadosempresa[0]->multa),6);
$linha_remessa = $valor_multa;
$linha_remessa = "2";
$linha_remessa = "000000";
$linha_remessa = tool::CompletaZeros(13,0);
$linha_remessa = "9".tool::CompletaZeros(12,0);
$linha_remessa = tool::CompletaZeros(13,0);
$linha_remessa = $list_titulo->current()->tp_sacado;
$linha_remessa = remessas::Limit(tooL::Completazeros(14,$list_titulo->current()->cpfcnpjsacado),14);
$linha_remessa = remessas::Limit(strtoupper($list_titulo->current()->sacado),40);
$logradouro=strtr($list_titulo->current()->logradouro, $map);
$num=$list_titulo->current()->num;
$linha_remessa = remessas::Limit(strtoupper($logradouro),32).",".remessas::Limit($num,4);
$bairro=strtr(strtoupper($list_titulo->current()->bairro), $map);
$linha_remessa = remessas::Limit($bairro,15);

$cep=tool::LimpaString($list_titulo->current()->cep);

$linha_remessa = remessas::Limit(substr($cep,0,5),5);
$linha_remessa = remessas::Limit(substr($cep,5,3),3);
$cidade=strtr(utf8_encode(strtoupper($list_titulo->current()->cidade)), $map);
$linha_remessa = remessas::Limit($cidade,15);
$linha_remessa= remessas::Limit(strtoupper($list_titulo->current()->uf),2);

$jurosmes   = ($dadosempresa[0]->juros/12);
$jurosdia   = ($jurosmes/30);
$juros      = $jurosdia/100;

$juros_ao_dia  = ($list_titulo->current()->vlr_nominal * $juros) ;

$multa = $dadosempresa[0]->multa/100;
$multa_atrazo = ($list_titulo->current()->vlr_nominal * $multa) ;

$linha_remessa = remessas::Limit("SENHOR CAIXA APOS O VENCIMENTO COBRAR JUROS E MULTA!",40);
$linha_remessa = "00";
$linha_remessa = remessas::ComplementoRegistro(1,"brancos");
$linha_remessa .= remessas::sequencial($i);



// atualiza o registro
// flag de adcionar o registro a remessa ou nao int 0 ou 1
// data que esta sendo gerada a remessa
// cod da remessa sempre o ultimo acrescido de + 1
// data
$Query_update=titulos::find($list_titulo->current()->id);
$Query_update->update_attributes(array('stflagrem' =>0,'dt_remessa'=>date("Y-m-d h:m:s"),'cod_remessa'=>$lte,'linha_remessa'=>$linha_remessa));



$qtelinhas++;
$valor_t_formatado=number_format($list_titulo->current()->vlr_nominal,2,',','.') ;
$valor_t_formatado=tool::limpaMoney($valor_formatado);
$vltotal    +=  $valor_t_formatado;
$i          =   $i+1;


$list_titulo->next();
endwhile;


/****************************************************************** FIM DA ESCRITA DA LINHA  ***********************************************************/
# REGISTRO DETALHE ( TIPO 9) (OBRIGATORIO)
																		                      #NOME DO CAMPO                								       #POSICAO    	#PICTURE
$conteudo9 ='';
$conteudo9 .= "9";                                                     	                      // tipo transacao        										        001 001         9(001)
$conteudo9 .= remessas::ComplementoRegistro(193,"brancos");                                    // zeros                                                              002 194         9(193)
// INSTRUÇÕES PARA O CAIXA
$dadosboleto["instrucoes_trailher1"] = strtoupper("COBRAR JUROS/DIA APOS O VENCIMENTO ");
$conteudo9 .= remessas::Limit(($dadosboleto["instrucoes_trailher1"]),40);                     // Mensagem responsabilidade Beneficiário:                             195 234          X(40)
                                                                                                /*
                                                                                                Quando o SEQ 34 = ""01"" e o SEQ 35 = ""01"", preencher com mensagens/intruções de responsabilidade do Beneficiário
                                                                                                Quando o SEQ 34 e SEQ 35 forem preenchidos com valores diferentes destes, preencher com Brancos"
                                                                                                */
$dadosboleto["instrucoes_trailher2"] = strtoupper("COBRAR MULTA APOS O VENCIMENTO.");
$conteudo9 .= remessas::Limit(($dadosboleto["instrucoes_trailher2"]),40);                       // Mensagem responsabilidade Beneficiário:                            235 274          X(40)
                                                                                                /*
                                                                                                Quando o SEQ 34 = ""01"" e o SEQ 35 = ""01"", preencher com mensagens/intruções de responsabilidade do Beneficiário
                                                                                                Quando o SEQ 34 e SEQ 35 forem preenchidos com valores diferentes destes, preencher com Brancos"

                                                                                                */
$conteudo9 .= remessas::ComplementoRegistro(40,"brancos");                                     // Mensagem responsabilidade Beneficiário:                             275 314          X(40)
$conteudo9.= remessas::ComplementoRegistro(40,"brancos");                                     // Mensagem responsabilidade Beneficiário:                             315 354          X(40)
$conteudo9 .= remessas::ComplementoRegistro(40,"brancos");                                     // Mensagem responsabilidade Beneficiário:                             355 394          X(40)
$conteudo9 .= remessas::sequencial($i);                                                        // numero sequencial  do registro no arquivo					         395 400         9(006)
$conteudo9 .= chr(13).chr(10);                                                                 // essa é a quebra de linha


$conteudo=$conteudo0.$conteudo1.$conteudo9;
/**************************************************************** FIM DO ARQUIVO ****************************************************************************/

/************************************* SE A QUANTIDADE DE LINHAS FOR MAIOR QUE ZERO GRAVA A REMESSA NO BANCO DE DADOS ***************************************/


if($qtelinhas > 0){


$caminho ="../arquivos/emp_".$COB_Empresa_Id."_bank_".$FRM_cod_banco0."";//diretorio

// verifica se o caminho onde deve ser salvo arquivo existe se não cria
if (!file_exists($caminho)) { mkdir($caminho, 0777, true);/*cria o diretorio do arquivo*/}

// grava o endereço do retorno no banco
$query = remessas::create(
              array(
                  'nm_arquivo' => $filename,
                  'path' => $caminho,
                  'dt_criacao' => date("Y-m-d"),
                  'cod_banco' => $FRM_cod_banco,
                  'contas_bancarias_id'=>$dadosempresa[0]->conta_id,
                  'linhas' =>  $qtelinhas,
                  'lote_remessa' => $lte,
                  'empresas_id' => $COB_Empresa_Id
                  ));

// abre o  arquivo ou cria para começar a escrever
if (!$handle = fopen($caminho."/".$filename, 'w')){
erro("Não foi possível abrir o arquivo ($filename)");
$msg = '":"","callback":"1","msg":"Não foi possível abrir o arquivo '.($filename).'","cod_banco":"'.($FRM_cod_banco).'","status":"danger';
}

// Escreve $conteudo no nosso arquivo aberto.
if (fwrite($handle, "$conteudo") === FALSE){   $msg = '":"","callback":"1","msg":"Não foi possível escrever no arquivo '.($filename).'","cod_banco":"'.($FRM_cod_banco).'","status":"danger';}


fclose($handle);

    $z = new ZipArchive();

    // Criando o pacote chamado "teste.zip"
    $arq=explode(".",$filename);
    $criou = $z->open(''.$caminho."/".$arq[0].'.zip', ZipArchive::CREATE);

    if ($criou === true) {
        // Criando um diretorio chamado "teste" dentro do pacote
        //$z->addEmptyDir('remessa');
        // Copiando um arquivo do HD para o diretorio "teste" do pacote
        $z->addFile(''.$caminho."/".$filename.'', ''.$filename.'');
        // Salvando o arquivo
        $z->close();


        // apagamos o arquivo txt gerado
        if (!unlink(''.$caminho."/".$filename.'')){ $msg = '":"","callback":"1","msg":"Não foi remover o arquivo texto  '.$filename.' ","cod_banco":"'.($FRM_cod_banco).'","status":"danger';}else{  $msg = '":"","callback":"0","msg":"Arquivo de remessa gerado com sucesso !","cod_banco":"'.($FRM_cod_banco).'","status":"success';  }

    } else { $msg = '":"","callback":"1","msg":"Não foi possível criar o arquivo zip","cod_banco":"'.($FRM_cod_banco).'","status":"danger'; }


}else{ $msg = '":"","callback":"1","msg":"Não a registros para remessa !","cod_banco":"'.($FRM_cod_banco).'","status":"danger'; }

echo $msg;

/************************************************************************************************************************************************************/
 ?>
