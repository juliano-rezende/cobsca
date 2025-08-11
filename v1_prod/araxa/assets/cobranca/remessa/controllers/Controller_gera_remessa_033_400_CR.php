<?php

$Frm_cad    =   true;// fala pra sessão não encerra pois é uma janela de cadastro

set_time_limit(0);
require_once"../../../../sessao.php";
require_once("../../../../conexao.php");
$cfg->set_model_directory('../../../../models/');

$FRM_contas_bancarias_id = isset( $_POST['contas_bancarias_id'])  ?  $_POST['contas_bancarias_id'] : tool::msg_erros("O Campo contas_bancarias_id é Obrigatorio.");
$FRM_cod_banco='033';
$FRM_dtinirem  = isset( $_POST['dtinirem'])  ?  tool::InvertDateTime(tool::LimpaString($_POST['dtinirem']),"-") : tool::msg_erros("O Campo dtinirem é Obrigatorio.");
$FRM_dtinifim  = isset( $_POST['dtinifim'])  ?  tool::InvertDateTime(tool::LimpaString($_POST['dtinifim']),"-") : tool::msg_erros("O Campo dtinifim é Obrigatorio.");



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
                                        contas_bancarias.id as conta_id,contas_bancarias.cod_banco,contas_bancarias.agencia,contas_bancarias.dv_agencia,contas_bancarias.conta,contas_bancarias.dv_conta,
                                        contas_bancarias_cobs.cod_cedente,contas_bancarias_cobs.dv_cod_cedente,contas_bancarias_cobs.cod_transmissao,contas_bancarias_cobs.carteira_remessa,contas_bancarias_cobs.aceite,
                                        configs.juros,configs.multa
                                     FROM
                                      empresas
                                     LEFT JOIN
                                      contas_bancarias ON contas_bancarias.empresas_id =  empresas.id
                                     LEFT JOIN
                                      contas_bancarias_cobs ON contas_bancarias_cobs.contas_bancarias_id =  contas_bancarias.id
                                     LEFT JOIN
                                      configs ON configs.empresas_id =  empresas.id
                                     WHERE empresas.id='".$COB_Empresa_Id."' AND contas_bancarias.id='".$FRM_contas_bancarias_id."' ");

$lote = remessas::find_by_sql("SELECT MAX((lote_remessa)+1) AS novolote
                               FROM  remessas_bancarias
                               WHERE cod_banco = '".$dadosempresa[0]->cod_banco."' AND
                               contas_bancarias_id='".$dadosempresa[0]->conta_id."' AND
                               empresas_id='".$COB_Empresa_Id."' ");

$novolote=$lote[0]->novolote;

if ($novolote=="") {$lte=1;}else {$lte=$novolote;}


$filename = "REM_".$DATA['DIA'].$DATA['MES'].$DATA['ANO'].$lte.".REM";


## REGISTRO HEADER ( TIPO 0)
                                #NOME DO CAMPO						#SIGNIFICADO                                                                          #POSICAO       #PICTURE
$conteudo0 ='';
$conteudo0 .= '0';             					              		//	Identificação do Registro Header: “0” (zero)                                       001 001        9(001)
$conteudo0 .= '1';             										//	Tipo de Operação: “1” (um)				                                           002 002        9(001)
$conteudo0 .= 'REMESSA';        			    						//	Identificação por Extenso do Tipo de Operação: "REMESSA"				           003 009        X(007)
$conteudo0 .= '01';           										//	Identificação do Tipo de Serviço: “01” (um)                                        010 011        9(002)
$conteudo0 .= remessas::Limit(utf8_decode('COBRANÇA'),15);    		//	Identificação por Extenso do Tipo de Serviço: “COBRANÇA”                           012 026        X(015)
$conteudo0 .= $dadosempresa[0]->cod_transmissao;                     // Código de Transmissão (nota 1) 8 primeiros digitos da conta                        027 046        9(020)

$rz        = remessas::Limit($dadosempresa[0]->razao_social,30);    //  Nome do cedente                                                                    047 076        A(030)
$conteudo0 .= strtoupper($rz);
$conteudo0 .= "033";                                        		//  Código do Banco = 353 / 033                                                        077 079        9(003)
$conteudo0 .= remessas::limit('SANTANDER',15);                       //  nome do banco por ext.                                                             080 094        X(015)
$conteudo0 .= $DATA['DIA'].$DATA['MES'].$DATA['ANO'];                //  Data da Gravação da Remessa: formato ddmmaa                                        095 100        9(006)
$conteudo0 .= tool::Completazeros(16,0);                             //  zeros                                                                              101 116        X(016)
$conteudo0 .= remessas::ComplementoRegistro(275,"brancos");          //  brancos                                                                             117 391        X(274)
$conteudo0 .= "000";                                                 //  Número da versão da remessa opcional,                                              392 394        9(003)
$conteudo0 .= tool::Completazeros(6,1);                              //  numero sequencial    registro no arquivo                                            395 400        9(006)
$conteudo0 .= chr(13).chr(10);                                       //  essa é a quebra de linha



// pega todos os boletos pessoa fisica
$query_titulos=titulos::find_by_sql("SELECT *
                                     FROM titulos_bancarios
                                     WHERE dv_nosso_numero >='0' AND
                                     contas_bancarias_id='".$dadosempresa[0]->conta_id."' AND
                                     stflagrem='1' AND cod_mov_rem!='35' AND
                                     (dt_emissao BETWEEN '".$FRM_dtinirem."' AND '".$FRM_dtinifim."' OR dt_atualizacao BETWEEN '".$FRM_dtinirem."' AND '".$FRM_dtinifim."') ORDER BY nosso_numero ASC");


// validação para verificar se existe registros para  gerar remessa ou não
//if(count($query_titulos) == 0){echo '":"","callback":"1","msg":"Não a registros para remessa !","cod_banco":"'.($FRM_cod_banco).'","status":"danger'; exit(); }


$list_titulo= new ArrayIterator($query_titulos);


// variaveis quantitativas
$qte_regs=0;														// define a variavel quantidade de linhas no arquivo como 0
$vltotal=0;															// define o valor total do arquivo como 0
$linha = 2;																// define o inicio do contador de linhas como 2 pois a primeira está na header seq 000001


$conteudo1 =''; // define a variavel conteudo1 antes do loop

while($list_titulo->valid()):


/* definimos a data de vencimento do boleto*/
$dtvenc = new ActiveRecord\DateTime($list_titulo->current()->dt_vencimento);
$dtemi  = new ActiveRecord\DateTime($list_titulo->current()->dt_emissao);




/********************************************************* INICIO DA ESCRITA DA LINHA  *****************************************************/

# REGISTRO DETALHE ( TIPO 1) (OBRIGATORIO)
																		                        #NOME DO CAMPO                					               #POSICAO         #PICTURE
$conteudo1 .= "1";										                                        // Identificação do Registro Detalhe: 1 (um)  				    001 001          9(01)
$conteudo1 .= "02";                                                                               // "Tipo de Inscrição do Beneficiário:
                                                                                                //  01" = CPF
                                                                                                //  02" = CNPJ                                                  002 003          9(002)
$conteudo1 .= remessas::Limit($dadosempresa[0]->cnpj,14);				                        // Número do CPF/CNPJ do Beneficiário                           004 017		     9(014)
$conteudo1 .= $dadosempresa[0]->cod_transmissao;                                                 // codigo de transmissão  nota(01)                              018 037          9(020)

$conteudo1 .= "0000000000000000000000000";                                                       /* numero de controle do participante,                          038 062          X(025)
                                                                                                   para controle por parte do cedente
                                                                                                */
$conteudo1 .= tool::CompletaZeros(7,$list_titulo->current()->nosso_numero);                      // USO / IDENT. DO TÍTULO NO BANCO  nosso numero + dv            063 069         9(007)
$conteudo1 .= $list_titulo->current()->dv_nosso_numero;                                          // DV NOSSO NUMERO                                               070 070         9(001)
$conteudo1 .= tool::Completazeros(6,0);                                                          // DATA SEGUNDO DESCONTO                                         071 076         9(006)
$conteudo1 .= remessas::ComplementoRegistro(1,"brancos");                                        // BRANCOS                                                       077 077         9(001)
$conteudo1 .= "4";                                                                               // Informação de multa = 4, senão houver informar zero           078 078         9(001)
$conteudo1 .= tool::LimpaString($dadosempresa[0]->multa);                                        // Percentual multa por atraso %  9(2)v9(2)                      079 082         9(004)
$conteudo1 .= "00";                                                                              // Unidade de valor moeda corrente = 00                          083 084         9(002)
$conteudo1 .= remessas::ComplementoRegistro(13,"zeros");                                         // Valor do título em outra unidade (ver banco) 9(8)v9(5)        085 097         9(013)
$conteudo1 .= remessas::ComplementoRegistro(4,"brancos");                                        // brancos                                                       098 101         X(004)
$conteudo1 .= "000000";                                                                          // Data para cobrança de multa. NOTA 4                           102 107         9(006)


/**************************************************************************************************************************************************************************************/
$conteudo1 .= $dadosempresa[0]->carteira_remessa;                                                /* Carteira/Modalidade:                                          108 108         9(001)
                                                                                                    1 = Eletronica Com Registro
                                                                                                    3 = Garantida Caucionada
                                                                                                    4 = Cobrança sem registro
                                                                                                    5 = rapida com registro
                                                                                                    6 = caucionada rapida
                                                                                                    7 = descontada eletronica
                                                                                                    */
/**************************************************************************************************************************************************************************************/
$conteudo1 .= tool::CompletaZeros(2,$list_titulo->current()->cod_mov_rem);                           /* Comando/Movimento:                                        109 110          9(02)
                                                                                                    01 = Entrada de Títulos
                                                                                                    02 = Baixa de titulo
                                                                                                    04 = Concessão de Abatimento
                                                                                                    05 = Cancelamento de Abatimento
                                                                                                    06 = Prorrogação de Vencimento
                                                                                                    07 = Alt.Numero  cont.cedente
                                                                                                    08 = Alteração de Seu Número
                                                                                                    09 = Protestar
                                                                                                    */
/******************************************************************************************************************************************************************************************/
$conteudo1 .= tool::CompletaZeros(10,$list_titulo->current()->numero_doc);                        // Numero para controle interno (Seu Numero )                   111 120          X(10)
$conteudo1 .= $dtvenc->format('dmy');                                                             // Data de vencimento normal DDMMYY                             121 126          A(06)
                                                                                                 // A vista 888888
                                                                                                 // contra Apresentação 999999
/******************************************************************************************************************************************************************************************/

$valor_formatado=tool::limpamoney(number_format($list_titulo->current()->vlr_nominal,2,',','.') );
$valor_formatado=tool::limpamoney($valor_formatado);

$conteudo1 .= tool::CompletaZeros(13,$valor_formatado);                                           // valor titulo valor nominal moeda corrente    9(11)V99        127 139         9(013)
$conteudo1 .= "033";                                                                     		//  Código do Banco = 353 / 033                                 140 142         9(003)
$conteudo1 .= $dadosempresa[0]->agencia.$dadosempresa[0]->dv_agencia;                             /* Código da agência cobradora do Banco Santander               143 147         9(005)
                                                                                                 informar somente se carteira for igual a 5, caso
                                                                                                 contrário, informar zeros.*/
/********************************************************************************************************************************************************/
$conteudo1 .= "01";                                                                               /* Espécie de documento                                         148 149         9(002)
                                                                                                   Espécies:
                                                                                                   01 = DUPLICATA
                                                                                                   02 = NOTA PROMISSÓRIA
                                                                                                   03 = APÓLICE / NOTA DE SEGURO
                                                                                                   05 = RECIBO
                                                                                                   06 = DUPLICATA DE SERVIÇO
                                                                                                   07 = LETRA DE CAMBIO
                                                                                                 */
$conteudo1 .= $dadosempresa[0]->aceite;                                                           // "Aceite do Título: 0 = Sem aceite 1 = Com aceite"           150 150         9(001)
$conteudo1 .= $dtemi->format('dmy');                                                              // Data de emissao formato DDMMYY                              151 166         9(06)


/******************************************************************************************************************************************************************************************/
$conteudo1 .= "00";                                                                              // Primeira instrução cobrança                                  157 158         9(002)
$conteudo1 .= "00";                                                                              /* segunda instrução cobrança                                   159 160         9(002)
                                                                                                        código
                                                                                                         00 = NÃO HÁ INSTRUÇÕES
                                                                                                         02 = BAIXAR APÓS QUINZE DIAS DO VENCIMENTO
                                                                                                         03 = BAIXAR APÓS 30 DIAS DO VENCIMENTO
                                                                                                         04 = NÃO BAIXAR
                                                                                                         06 = PROTESTAR (VIDE POSIÇÃO 392/393)
                                                                                                         07 = NÃO PROTESTAR
                                                                                                         08 = NÃO COBRAR JUROS DE MORA
                                                                                                */

/******************************************************************************************************************************************************************************************/

// recupera a taxa de juros da empresa
$jurosmes       = ($dadosempresa[0]->juros/12);
$jurosdia       = ($jurosmes/30);
$juros          = ($jurosdia/100);
$juros_ao_dia   = ($list_titulo->current()->vlr_nominal * $juros) ;
$valor_juros    = tool::LimpaString(number_format(($juros_ao_dia*1),2,',','.'));

$conteudo1 .= tool::Completazeros(13,$valor_juros);                                               // Taxa de mora mês Ex: 022000 = 2,20%)    9(11)V99            161 173         9(013)
$conteudo1 .= remessas::ComplementoRegistro(6,"zeros");                                           // Data limite para concessão de desconto                      174 179         9(006)
$conteudo1 .= remessas::ComplementoRegistro(13,"zeros");                                          // Valor de desconto a se convebido          9(011)V9(2)       180 192         9(013)
$conteudo1 .= remessas::ComplementoRegistro(13,"zeros");                                          // Valor do IOF a ser recolhido pelo         9(008)V9(5)       193 205         9(013)
$conteudo1 .= remessas::ComplementoRegistro(13,"zeros");                                          // Valor do IOF a ser recolhido pelo         9(008)V9(5)       206 218         9(011)
$conteudo1 .= $list_titulo->current()->tp_sacado;                                                 // tipo de inscrição do pagado                                 219 220         9(02)
$conteudo1 .= remessas::Limit(tooL::Completazeros(14,$list_titulo->current()->cpfcnpjsacado),14); // Número do CPF/CNPJ do sacado                                221 234         9(14)
$conteudo1 .= remessas::Limit(strtoupper($list_titulo->current()->sacado),40);                    // Nome sacado                                                 235 274         A(40)


/****************************************************************************************************************************************************************************************/
$logradouro=strtr($list_titulo->current()->logradouro, $map);                                   // rua numero e compl sacado                                  275 314         X(36+4)
$num=$list_titulo->current()->num;
$conteudo1 .= remessas::Limit(strtoupper($logradouro),35).",".remessas::Limit($num,4);
/******************************************************************************************************************************************************************************************/
$bairro=strtr(strtoupper($list_titulo->current()->bairro), $map);                               // bairro                                         315 326         X(012)
$conteudo1 .= remessas::Limit($bairro,12);
/*****************************************************************************************************************************************************************************************/
$cep=tool::LimpaString($list_titulo->current()->cep);
$conteudo1 .= remessas::Limit(substr($cep,0,5),5);                                              // cep do sacado                                              327 331         9(05)
$conteudo1 .= remessas::Limit(substr($cep,5,3),3);                                              // Complemento cep do sacado                                  322 334         9(03)
/******************************************************************************************************************************************************************************************/
$cidade=strtr(utf8_encode(strtoupper($list_titulo->current()->cidade)), $map);                  // municipio do sacado                                        335 349         X(15)
$conteudo1 .= remessas::Limit($cidade,15);
/******************************************************************************************************************************************************************************************/
$conteudo1 .= remessas::Limit(strtoupper($list_titulo->current()->uf),2);                        // uf do sacado                                               350 351         X(02)
/******************************************************************************************************************************************************************************************/


$conteudo1 .= remessas::ComplementoRegistro(30,"brancos");                                       // Nome do sacador ou coobrigado                              352 381         X(030)
$conteudo1 .= remessas::ComplementoRegistro(1,"brancos");                                        // BRANCOS                                                    382 382         X(001)
$conteudo1 .="I";                                                                                // Identificador do Complemento (i maiúsculo – vide nota 2)   383 383         X(001)
$conteudo1 .= substr($dadosempresa[0]->conta,-1).$dadosempresa[0]->dv_conta;                     // Complemento (nota 2)                                       384 385         9(002)
$conteudo1 .= remessas::ComplementoRegistro(6,"brancos");                                        // BRANCOS                                                    386 391         X(006)
$conteudo1 .= "00";                                                                              // Número de dias para protesto.
                                                                                                 // Quando posições 157/158 ou 159/160 for igual a 06.         392 393         9(002)
/******************************************************************************************************************************************************************************************/
$conteudo1 .= remessas::ComplementoRegistro(1,"brancos");                                        // Complementeo de registros : brancos                        394 395          x(1)
$conteudo1 .= remessas::sequencial($linha);                                                          // numero sequencial    do registro no arquivo                395 400          9(06)
$conteudo1 .= chr(13).chr(10);                                                                   //essa é a quebra de linha




/* monta a linha a ser salva no banco de dados*/
$linha_remessa="";
$linha_remessa .= "1";
$linha_remessa .= "02";
$linha_remessa .= remessas::Limit($dadosempresa[0]->cnpj,14);
$linha_remessa .= $dadosempresa[0]->cod_transmissao;
$linha_remessa .= "0000000000000000000000000";
$linha_remessa .= tool::CompletaZeros(7,$list_titulo->current()->nosso_numero);
$linha_remessa .= $list_titulo->current()->dv_nosso_numero;
$linha_remessa .= tool::Completazeros(6,0);
$linha_remessa .= remessas::ComplementoRegistro(1,"brancos");
$linha_remessa .= "4";
$linha_remessa .= tool::LimpaString($dadosempresa[0]->multa);
$linha_remessa .= "00";
$linha_remessa .= remessas::ComplementoRegistro(13,"zeros");
$linha_remessa .= remessas::ComplementoRegistro(4,"brancos");
$linha_remessa .= "000000";
$linha_remessa .= $dadosempresa[0]->carteira_remessa;
$linha_remessa .= tool::CompletaZeros(2,$list_titulo->current()->cod_mov_rem);
$linha_remessa .= tool::CompletaZeros(10,$list_titulo->current()->numero_doc);
$linha_remessa .= $dtvenc->format('dmy');

$valor_formatado=tool::limpamoney(number_format($list_titulo->current()->vlr_nominal,2,',','.') );
$valor_formatado=tool::limpamoney($valor_formatado);

$linha_remessa .= tool::CompletaZeros(13,$valor_formatado);
$linha_remessa .= $FRM_cod_banco;
$linha_remessa .= $dadosempresa[0]->agencia.$dadosempresa[0]->dv_agencia;
$linha_remessa .= "01";
$linha_remessa .= $dadosempresa[0]->aceite;
$linha_remessa .= $dtemi->format('dmy');
$linha_remessa .= "00";
$linha_remessa .= "00";

$jurosmes       = ($dadosempresa[0]->juros/12);
$jurosdia       = ($jurosmes/30);
$juros          = ($jurosdia/100);
$juros_ao_dia   = ($list_titulo->current()->vlr_nominal * $juros) ;
$valor_juros    = tool::LimpaString(number_format(($juros_ao_dia*1),2,',','.'));

$linha_remessa .= tool::Completazeros(13,$valor_juros);
$linha_remessa .= remessas::ComplementoRegistro(6,"zeros");
$linha_remessa .= remessas::ComplementoRegistro(13,"zeros");
$linha_remessa .= remessas::ComplementoRegistro(13,"zeros");
$linha_remessa .= remessas::ComplementoRegistro(13,"zeros");
$linha_remessa .= $list_titulo->current()->tp_sacado;
$linha_remessa .= remessas::Limit(tooL::Completazeros(14,$list_titulo->current()->cpfcnpjsacado),14);
$linha_remessa .= remessas::Limit(strtoupper($list_titulo->current()->sacado),40);

$logradouro=strtr($list_titulo->current()->logradouro, $map);
$num=$list_titulo->current()->num;

$linha_remessa .= remessas::Limit(strtoupper($logradouro),35).",".remessas::Limit($num,4);

$bairro=strtr(strtoupper($list_titulo->current()->bairro), $map);

$linha_remessa .= remessas::Limit($bairro,12);

$cep=tool::Completazeros(8,tool::LimpaString($list_titulo->current()->cep));

$linha_remessa .= remessas::Limit(substr($cep,0,5),5);
$linha_remessa .= remessas::Limit(substr($cep,5,3),3);

$cidade=strtr(utf8_encode(strtoupper($list_titulo->current()->cidade)), $map);

$linha_remessa .= remessas::Limit($cidade,15);
$linha_remessa .= remessas::Limit(strtoupper($list_titulo->current()->uf),2);
$linha_remessa .= remessas::ComplementoRegistro(30,"brancos");
$linha_remessa .= remessas::ComplementoRegistro(1,"brancos");
$linha_remessa .="I";
$linha_remessa .= substr($dadosempresa[0]->conta,-1).$dadosempresa[0]->dv_conta;
$linha_remessa .= remessas::ComplementoRegistro(6,"brancos");
$linha_remessa .= "00";
$linha_remessa .= remessas::ComplementoRegistro(1,"brancos");
$linha_remessa .= remessas::sequencial($linha);

// atualiza o registro
// flag de adcionar o registro a remessa ou nao int 0 ou 1
// data que esta sendo gerada a remessa
// cod da remessa sempre o ultimo acrescido de + 1
// data
$Query_update=titulos::find($list_titulo->current()->id);
$Query_update->update_attributes(array('stflagrem' =>0,'dt_remessa'=>date("Y-m-d h:m:s"),'cod_remessa'=>$lte,'linha_remessa'=>$linha_remessa));


$qte_regs++;
$valor_t_formatado=number_format($list_titulo->current()->vlr_nominal,2,',','.') ;
$valor_t_formatado=tool::limpaMoney($valor_formatado);
$vltotal    +=  $valor_t_formatado;
$linha       =   $linha+1;


$list_titulo->next();
endwhile;


/****************************************************************** FIM DA ESCRITA DA LINHA  ***********************************************************/
# REGISTRO DETALHE ( TIPO 9) (OBRIGATORIO)
																		                      #NOME DO CAMPO                								       #POSICAO    	#PICTURE
$conteudo9 ='';
$conteudo9 .= "9";                                                     	                      // Código do registro = 9      								        001 001         9(001)
$conteudo9 .= tool::CompletaZeros(6,$qte_regs);                                               // Quantidade total de linhas no arquivo                              002 007         9(006)
$conteudo9 .= tool::CompletaZeros(13,$vltotal);                                                // Valor total dos títulos           9(013)v99                        008 020         9(013)
$conteudo9 .= remessas::ComplementoRegistro(374,"zeros");                                      // Complementeo de registros : brancos                                021 394         9(374)
$conteudo9 .= remessas::sequencial($linha);                                                    // numero sequencial  do registro no arquivo					        395 400         9(006)
$conteudo9 .= chr(13).chr(10);                                                                 // essa é a quebra de linha



$conteudo=$conteudo0.$conteudo1.$conteudo9;
/**************************************************************** FIM DO ARQUIVO ****************************************************************************/

/************************************* SE A QUANTIDADE DE LINHAS FOR MAIOR QUE ZERO GRAVA A REMESSA NO BANCO DE DADOS ***************************************/


if  ($qte_regs > 0){


$caminho ="../../../../arquivos/remessas/emp_".$COB_Empresa_Id."_bank_".$FRM_cod_banco."_400_RCR";//diretorio

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
                  'linhas' =>  $qte_regs,
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
        if (!unlink(''.$caminho."/".$filename.'')){ $msg = '":"","callback":"1","msg":"Não foi remover o arquivo texto  '.$filename.' ","cod_banco":"'.($FRM_cod_banco).'","status":"danger';}
         else{  $msg = '":"","callback":"0","msg":"Arquivo de remessa gerado com sucesso !","cod_banco":"'.($FRM_cod_banco).'","status":"success';  }

    } else { $msg = '":"","callback":"1","msg":"Não foi possível criar o arquivo zip","cod_banco":"'.($FRM_cod_banco).'","status":"danger'; }


}else{ $msg = '":"","callback":"1","msg":"Não a registros para remessa !","cod_banco":"'.($FRM_cod_banco).'","status":"danger'; }

echo $msg;

/************************************************************************************************************************************************************/
 ?>
