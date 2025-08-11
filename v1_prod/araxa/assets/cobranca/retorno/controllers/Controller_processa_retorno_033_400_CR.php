<?php

$Frm_cad  = true;// fala pra sessão não encerra pois é uma janela de cadastro

set_time_limit(0);
require_once"../../../../sessao.php";
require_once("../../../../conexao.php");
$cfg->set_model_directory('../../../../models/');



// variavel com o id do retorno
$FRM_id_ret  = isset( $_POST['id']) ?  $_POST['id']  : tool::msg_erros("O Campo id é Obrigatorio.");

// query para recupera o path do arquivo
$Query_retorno=retornos::find($FRM_id_ret);


/********************************************************** verificamos se o arquivo ja não foi tratado ****************************************************/
if($Query_retorno->status!=0){
  echo ("<div class='uk-alert uk-alert-warning'><i class='uk-icon-warning uk-text-warning' ></i> ARQUIVO JÁ PROCESSSADO ANTERIORMENTE!</br></div>");
  exit();
}

// definição do arquivo de log
$filename_log = "LOG_".date("dmY")."_retorno_id_".$FRM_id_ret.".txt";
$conteudo = '########################################### ARQUIVO DE LOG DE TRATAMENTO DO RETORNO ###########################################'.chr(13).chr(10);
$conteudo.= '##  ARQUIVO '.$Query_retorno->nm_arquivo.chr(13).chr(10);
$conteudo.= '##  DATA '.date("d/m/Y").chr(13).chr(10);
$conteudo.= chr(13).chr(10);

// caminho do arquivo a ser tratado
$arquivo=$Query_retorno->path."/".$Query_retorno->nm_arquivo;

// ABRE O ARQUIVO
$lendo = @fopen($arquivo,"r");

// variaveis privadas
$registros  = 0;
$creditos   = 0;
$debitos    = 0;
$compensados= 0;
$entradas   = 0;
$baixados   = 0;
$cancelados = 0;
$alterados  = 0;
$erros      = 0;
$sequencial = 0;
$titulos    = "";


/********************************************************** Caso ocorra algum erro ao abrir o arquivo **********************************************************/
if(!$lendo){ echo ("<div class='uk-alert uk-alert-warning'><i class='uk-icon-warning uk-text-warning' ></i>ERRO AO ABRIR ARQUIVO!</br></div>"); exit();}



while (!feof($lendo)){


$linha      = " ".fgets($lendo,4096);
$linha_ret  = $linha; // grava a linha do retorno no banco de dados
$tipo_reg   = substr($linha,1,1);   // Tipo de Registro - Código adotado pela FEBRABAN para identificar o tipo de registro:
                                 // 0=Header de Arquivo, 1=Header de Lote, 3=Detalhe, 5=Trailer de Lote, 9=Trailer de Arquivo.
$tipo_arq   = substr($linha,2,1);   // Tipo de arquivo - validamos o tipo do arquivo se possui registros ou não:


//echo $tipo_reg." - ".ltrim(substr($linha,127,7), "0")." / "; continue;

// validamos o sequencial de linha do arquivo
//$sequencial++;
//$sequencial_ret=tool::completazeros(6,substr($linha,395,6));

/* validamos a ultima linha do arquivo e paramos a execução
if($sequencial != $sequencial_ret){
  if($sequencial_ret != "000000"){
   echo "<div class='uk-alert uk-alert-success' style='margin:0;'> ARQUIVO CORROMPIDO SEQUENCIAL  ".$sequencial_ret." </div></br>";
    continue;
  }
}*/

/********************************************************** se o segmento da linha for 0 header ***********************************************/
if($tipo_reg == 0 ){

/*
1   001 001 001 9(001)  Identificação do Registro Header: “0”
2   002 002 001 9(001)  Tipo de Operação: “2”
3   003 009 007 A(007)  Identificação Tipo de Operação “RETORNO”
4   010 011 002 9(002)  Identificação do Tipo de Serviço: “01”
5   012 019 008 A(008)  Identificação por Extenso do Tipo de Serviço:“COBRANÇA”
6   020 026 007 A(007)  Complemento do Registro: Brancos
7   027 030 004 9(004)  Prefixo da Cooperativa: vide planilha "Capa" deste arquivo
8   031 031 001 A(001)  Dígito Verificador do Prefixo: vide planilha "Capa" deste arquivo
9   032 039 008 9(008)  Código do Cliente/Beneficiário: vide planilha "Capa" deste arquivo
10  040 040 001 A(001)  Dígito Verificador do Código: vide planilha "Capa" deste arquivo
11  041 046 006 9(006)  Número do convênio líder: Brancos
12  047 076 030 A(030)  Nome do Beneficiário
13  077 094 018 A(018)  Identificação do Banco: "756BANCOOBCED"
14  095 100 006 9(006)  Data da Gravação do Retorno: formato ddmmaa
15  101 107 007 9(007)  Seqüencial do Retorno: número seqüencial atribuído pelo Sicoob, acrescido de 1 a cada retorno. Inicia com "0000001"
16  108 394 287 A(287)  Complemento do Registro: Brancos
17  395 400 006 9(006)  Seqüencial do Registro:”000001”
*/

//Código do banco na compensação
$h_cod_banco        = substr($linha,77,3);
$lote_interno       = substr($linha,95,2).substr($linha,97,2).substr(date("Y"),0,2).substr($linha,99,2); //Lote de serviço - Número seqüencial para identificar um lote de serviço. DDMMYY de geração do arquivo

continue;
}
/********************************************************** se o segmento da linha for 1 detalhe ***********************************************/
if($tipo_reg == 1 ){

$h_lote_interno = $lote_interno;
$registros ++; // contador de registros


// Tipo do beneficiario  01= cpf 02= cnpj
$d_tp_beneficiario    = substr($linha,2,2);

// Número do CPF/CNPJ do Beneficiário
$d_doc_beneficiario   = substr($linha,4,14);

//cod de identificação do tipo de movimentação do titulo no banco
$d_cod_mov            = substr($linha,109,2);               /*
                                                              "Comando/Movimento:
                                                              01 = título não existe
                                                              02 = entrada tít. confirmada
                                                              03 = entrada tít. rejeitada
                                                              06 = liquidação
                                                              07 = liquidação por conta
                                                              08 = liquidação por saldo
                                                              09 = baixa automática
                                                              10 = tít. baix. conf. instrução ou por título protestado
                                                              11 = em ser
                                                              12 = abatimento concedido
                                                              13 = abatimento cancelado
                                                              14 = prorrogação de vencimento
                                                              15 = Enviado para Cartório
                                                              16 = tít. já baixado/liquidado
                                                              17 = liquidado em cartório
                                                              21 = Entrada em Cartório
                                                              22 = Retirado de cartório
                                                              24 = Custas de Cartório
                                                              25 = Protestar Título
                                                              26 = Sustar Protest
                                                            */

// Data pagamento YYYY-mm-dd ou data ocorrencia
$d_dt_liquidacao      = substr(date("Y"),0,2).substr(substr($linha,111,6),4,2).'-'.
                        substr(substr($linha,111,6),2,2).'-'.
                        substr(substr($linha,111,6),0,2);

// Identificação do titulo no cliente - Número adotado pelo cliente Cedente para identificar o Título. numero doc
$d_id_titulo_cliente  = ltrim(substr($linha,117,10), "0");

// Identificação do titulo no banco - Número adotado pelo Banco Cedente para identificar o Título. nosso numero, pegamos apenas 7 posições desconsiderando o dig verificador
$d_id_titulo_banco    = ltrim(substr($linha,127,7), "0");

// Código Original da Remessa Obs.:  Esse campo terá conteúdo diferente de 0(zeros) caso ocorra erro no processamento da remessa - Nota 5 codigo da tebela de rejeiçao
$d_cod_original_remessa  = substr($linha,135,2);

//Código do Erro (1ª ocorrência), será preenchido com brancos quando não ocorrer erro Nota 5
$d_cod_rej1  = substr($linha,137,3);

//Código do Erro (2ª ocorrência), será preenchido com brancos quando não ocorrer erro Nota 5
$d_cod_rej2  = substr($linha,140,3);

//Código do Erro (3ª ocorrência), será preenchido com brancos quando não ocorrer erro Nota 5
$d_cod_rej3  = substr($linha,143,3);

// Data de vencimento do titulo - Data de vencimento do título de cobrança.
$d_dt_vencimento      = substr(date("Y"),0,2).substr(substr($linha,147,6),4,2).'-'.
                        substr(substr($linha,147,6),2,2).'-'.
                        substr(substr($linha,147,6),0,2);

// Valor nominal do titulo - Valor original do Título. Quando o valor for expresso em moeda corrente, utilizar 2 casas decimais.
$d_v_nominal          = substr($linha,153,13);
$d_v_nominal          = substr($d_v_nominal,0,11).'.'.substr($d_v_nominal,11,2);

// Valor da tarifa/custas
$d_v_tarifa_custas  = substr($linha,176,13);
$d_v_tarifa_custas  = substr($d_v_tarifa_custas,0,11).'.'.substr($d_v_tarifa_custas,11,2);

// Data da efetivação do credito - Data de efetivação do crédito referente ao pagamento do título de cobrança.
$d_dt_efetivacao    = substr(date("Y"),0,2).substr(substr($linha,296,6),4,2).'-'.
                        substr(substr($linha,296,6),2,2).'-'.
                        substr(substr($linha,296,6),0,2);

$d_v_pago           = substr($linha,254,13); // Valor pago pelo sacado - Valor do pagamento efetuado pelo Sacado referente ao título de cobrança, expresso em moeda corrente.
$d_v_pago           = substr($d_v_pago,0,11).'.'.substr($d_v_pago,11,2);


/********************************************************** consulta o titulo no banco de dados ***************************************************************/
$titulo=titulos::find_by_sql("SELECT id,tp_sacado,sacado,status
                                FROM titulos_bancarios
                                WHERE empresas_id='".$COB_Empresa_Id."' AND contas_bancarias_id='".$Query_retorno->contas_bancarias_id."' AND nosso_numero='".$d_id_titulo_banco."'");




/********************************************************** verificamos se encontrou o titulo *****************************************************************/
if(!$titulo){

$conteudo.=strtoupper(tool::CompletaZeros(5,$registros)." ## INCONSISTENTE ## NÃO ENCONTRADO NA BASE DE DADOS TITULO nº ".tool::CompletaZeros(8,$d_id_titulo_banco)." ######  ERRO!");
$conteudo.= chr(13).chr(10); //  essa é a quebra de linha
$erros++;
continue;


}if($titulo[0]->status == 1){

$conteudo .=strtoupper(tool::CompletaZeros(5,$registros)." ## TITULO JÁ BAIXADO OU JÁ PROCESSADO ## Titulo nº ".tool::CompletaZeros(8,$d_id_titulo_banco)." ######  ATENÇÃO!");
$conteudo .= chr(13).chr(10); //  essa é a quebra de linha
$compensados++;
continue;

}


/********************************************************** validação de codigo de movimento do titulo *****************************************************************/

if($d_cod_mov == "01"){/* título não existe */

/******************************************************************** atualiza o titulo ********************************************************************/
$up_titulo = titulos::find($titulo[0]->id);
$up_titulo->update_attributes(array('obs'=>'Titulo não existe','cod_retorno'=>$h_lote_interno,'linha_retorno'=>$linha_ret,'cod_mov_ret'=>$d_cod_mov));

/******************************************************************** escreve no log ********************************************************************/
$conteudo.=strtoupper(tool::CompletaZeros(5,$registros)." ## TITULO NÃO EXISTE  ## TITULO nº ".tool::CompletaZeros(8,$d_id_titulo_banco)." SACADO =>".remessas::Limit($titulo[0]->sacado,40)." ######  ATENÇÃO!");
$conteudo.=chr(13).chr(10);
$erros++;
continue;

}if($d_cod_mov == "02"){/* codigo de confirmação de entrada de titulo*/

/******************************************************************** atualiza o titulo ********************************************************************/
$up_titulo = titulos::find($titulo[0]->id);
$up_titulo->update_attributes(
                              array(
                                      'tab_rej'       =>'',
                                      'cod_rej1'      =>'',
                                      'cod_rej2'      =>'',
                                      'cod_rej3'      =>'',
                                      'obs'           =>'Entrada tit. confirmada.',
                                      'cod_retorno'   =>$h_lote_interno,
                                      'cod_mov_ret'   =>$d_cod_mov,
                                      'linha_retorno' =>$linha_ret));

/******************************************************************** escreve no log ********************************************************************/
$conteudo.=strtoupper(tool::CompletaZeros(5,$registros)." ## ENTRADA TIT. CONFIRMADA ## Titulo nº ".tool::CompletaZeros(8,$d_id_titulo_banco)." ######  OK!");
$conteudo.= chr(13).chr(10); //  essa é a quebra de linha
$entradas++;
continue;


}if($d_cod_mov == "03"){/* codigo de confirmação de entrada rejeitada*/


$up_titulo = titulos::find($titulo[0]->id);

// verificamos se esse titulo não foi informado anteriormente e aceito pelo banco
if($up_titulo->cod_mov_ret == 02){

/******************************************************************** escreve no log ********************************************************************/
$conteudo.=strtoupper(tool::CompletaZeros(5,$registros)." ## TITULO COM ENTRADA JÁ CONFIRMADA ## Titulo nº ".tool::CompletaZeros(8,$d_id_titulo_banco)." ######  REMESSA DUPLICADA!");
$conteudo.=chr(13).chr(10);
continue;

}if($up_titulo->cod_mov_ret == 02 && $up_titulo->status == 02){

/******************************************************************** escreve no log ********************************************************************/
$conteudo.=strtoupper(tool::CompletaZeros(5,$registros)." ## SOLICITAÇÃO DE CANCELAMENTO ## Titulo nº ".tool::CompletaZeros(8,$d_id_titulo_banco)." ######  TITULO JÁ BAIXADO!");
$conteudo.=chr(13).chr(10);
continue;

}else{

/******************************************************************** atualiza o titulo ********************************************************************/
$up_titulo->update_attributes(
                            array(
                                'tab_rej'       =>'1',
                                'cod_rej1'      =>$d_cod_rej1,
                                'cod_rej2'      =>$d_cod_rej2,
                                'cod_rej3'      =>$d_cod_rej2,
                                'obs'           =>"Entrada tit. rejeitada remessa.",
                                'linha_retorno' =>$linha_ret,
                                'cod_mov_ret'   =>$d_cod_mov,
                                'cod_retorno'   =>$h_lote_interno,
                                'usuarios_id'   =>$COB_Usuario_Id,
                                'linha_retorno' =>$linha_ret
                              ));

/******************************************************************** escreve no log ********************************************************************/
$conteudo .=strtoupper(tool::CompletaZeros(5,$registros)." ## ENTRADA TIT. REJEITADA ## Titulo nº ".tool::CompletaZeros(8,$d_id_titulo_banco)." ######  ERRO!");
$conteudo .=chr(13).chr(10);
$erros++;
continue;

}

}if($d_cod_mov == "06"){                   /* codigo de liquidação de titulo */


$creditos   += $d_v_pago;                  /* total pago pelo titulo */
$debitos    += $d_v_tarifa_custas;         /* total da tarifa paga pelo titulo */
$titulos    .=$d_id_titulo_banco;          /* titulos que compoem o valor creditado no caixa */

  /* verifica se houve multa e juros*/
  $TT_jurosmulta      = $d_v_pago - $d_v_nominal;

  if($d_v_pago > $d_v_nominal ){$vlr_acrescimos = $TT_jurosmulta; }else{$vlr_acrescimos="0.00";}

  /* recuperamos todas as parcelas referentes aquele titulo*/
  $Q_faturamento = faturamentos::find_by_sql("SELECT faturamentos.id,faturamentos.matricula,faturamentos.referencia,convenios.tipo_convenio,planos.seguro
                                              FROM faturamentos
                                              LEFT JOIN convenios ON faturamentos.convenios_id = convenios.id
                                              LEFT JOIN dados_cobranca ON dados_cobranca.id =  faturamentos.dados_cobranca_id
                                              LEFT JOIN associados ON faturamentos.matricula =  associados.matricula
                                              LEFT JOIN planos ON planos.id = dados_cobranca.planos_id
                                              WHERE titulos_bancarios_id='".$titulo[0]->id."' AND contas_bancarias_id='".$Query_retorno->contas_bancarias_id."' AND associados.status='1'");
  $t_parcelas_fat=count($Q_faturamento);


  // no caso da tabela faturamentos devemos dividir o valor pago e as tarifas pelo total de parcelas encontradas
  if($t_parcelas_fat > 1 ){ $new_vl_pago = $d_v_pago / $t_parcelas_fat; }else{ $new_vl_pago = $d_v_pago; }



  if($t_parcelas_fat > 0){

  // mensagem caso haja procedimento imbutidos no titulo e tambem se não for encontrado em faturamentos
  $msg_pro="";
  $msg_fat="";

  // o loop em si
  $list_fat= new ArrayIterator($Q_faturamento);
  while($list_fat->valid()){

   /******************************************************************** atualiza o faturamento  ************************************************************/
    $up_faturamento = faturamentos::find($list_fat->current()->id);
    $up_faturamento->update_attributes(
                              array(
                                  'status'            => 1,
                                  'tipo_baixa'        =>'B',
                                  'dt_pagamento'      =>$d_dt_liquidacao,
                                  'valor_pago'        =>$new_vl_pago,
                                  'ultima_alteracao'  =>date("Y-m-d h:m:s"),
                                  'flag_pago'         =>'PAGO',
                                  'usuarios_id'       =>$COB_Usuario_Id
                                  ));

    /* verifica se existe procedimento atrelhado a este titulo consultas ou exames */
    $up_procedimentos = procedimentos::find_by_faturamentos_id($list_fat->current()->id);

    if($up_procedimentos){
      $msg_pro.=" | Parcela possui procedimento.";
      $up_procedimentos->update_attributes(array('status'=> 3,'obs'=>'Procedimento pago titulo nº '.$d_id_titulo_banco.'')." ######  OK!");
    }


  /*********************************************************** SE POSSUI SEGURO FAZ A INSERÇÃO DO DADOS NA TABELA DE SEGURADOS *************************/
      if($list_fat->current()->seguro == 1){

      // passa a matricula a referencia e o tipo de convenio pj ou pf
      $Query_assegurar = seguros::segurar($list_fat->current()->matricula,$list_fat->current()->referencia,$list_fat->current()->tipo_convenio,$COB_Empresa_Id);

        // VERIFICA SE CORREU TUDO BEM NA INSERÇÃO DO DADOS NA TABELA SEGUROS
        if($Query_assegurar == true){$msg_pro.=" | Parcela possui seguro.";}
      }


    $list_fat->next();
  } /* fim do while list faturamentos*/

  /******************************************************************** atualiza o titulo ********************************************************************/
  $up_titulo = titulos::find($titulo[0]->id);
  $up_titulo->update_attributes(
                            array(
                                'status'          =>1,
                                'dt_pagamento'    =>$d_dt_liquidacao,
                                'vlr_pago'        =>$d_v_pago,
                                'vlr_tarifa'      =>$d_v_tarifa_custas,
                                'vlr_acrescimos'  =>$vlr_acrescimos,
                                'dt_processamento'=>date("Y-m-d h:m:s"),
                                'dt_ocorrencia'   =>$d_dt_liquidacao,
                                'dt_efet_cred'    =>$d_dt_efetivacao,
                                'dt_deb_tarifa'   =>$d_dt_efetivacao,
                                'st_flag_ret'     =>'1',
                                'obs'             =>'Titulo compensado pelo banco',
                                'linha_retorno'   =>$linha_ret,
                                'cod_mov_ret'     =>$d_cod_mov,
                                'cod_retorno'     =>$h_lote_interno,
                                'usuarios_id'     =>$COB_Usuario_Id
                              ));

    }else{

    $msg_fat=strtoupper(" Titulo ".tool::CompletaZeros(11,$d_id_titulo_banco)." Sacado ".remessas::Limit($titulo[0]->sacado,40)." Não encontrado em faturamentos.");
    $erros++;

    }

$conteudo.=strtoupper(tool::CompletaZeros(5,$registros)." ## COMP. BANCARIA ## Titulo nº ".tool::CompletaZeros(8,$d_id_titulo_banco." - ".$msg_pro." - ".$msg_fat)." ######  OK!");
$conteudo.=chr(13).chr(10);
$compensados++;
continue;


}if($d_cod_mov == "07"){                                     /* codigo de confirmação de baixa de titulo a pedido do cliente*/

/******************************************************************** atualiza o titulo ********************************************************************/
$up_titulo = titulos::find($titulo[0]->id);
$up_titulo->update_attributes(array('obs'=>'Liquidação por conta do cedente.','cod_retorno'=>$lote_interno,'linha_retorno'=>$linha_ret,'cod_mov_ret'=>$d_cod_mov));

/******************************************************************** escreve no log ********************************************************************/
$conteudo.=strtoupper(tool::CompletaZeros(5,$registros)." ## LIQUIDAÇÃO POR CONTA CED ## Titulo nº ".tool::CompletaZeros(11,$d_id_titulo_banco)." ######  OK!");
$conteudo.=chr(13).chr(10);
$baixados++;
continue;

}if($d_cod_mov == "09"){                                     /* codigo de confirmação de baixa de titulo automatica pelo banco*/

/******************************************************************** atualiza o titulo ********************************************************************/
$up_titulo = titulos::find($titulo[0]->id);
$up_titulo->update_attributes(array('obs'             =>'Baixa automatica pelo banco titulo vencido a mais de 90 dias.',
                                    'cod_retorno'     =>$lote_interno,
                                    'status'          =>'3',
                                    'dt_ocorrencia'   =>$d_dt_liquidacao,
                                    'dt_processamento'=>date("Y-m-d h:m:s"),
                                    'dt_deb_tarifa'   =>$d_dt_efetivacao,
                                    'vlr_tarifa'      =>$d_v_tarifa_custas,
                                    'cod_mov_ret'     =>$d_cod_mov));

/* recuperamos todas as parcelas referentes aquele titulo*/
$Q_faturamento = faturamentos::find_by_sql("SELECT * FROM faturamentos WHERE titulos_bancarios_id='".$titulo[0]->id."' AND contas_bancarias_id='".$Query_retorno->contas_bancarias_id."'");
$t_parcelas_fat=count($Q_faturamento);

$list_fat= new ArrayIterator($Q_faturamento);
while($list_fat->valid()):

  /******************************************************************** atualiza o faturamento  ************************************************************/
    $up_faturamento = faturamentos::find($list_fat->current()->id);
    $up_faturamento->update_attributes(
                              array(
                                  'status'              => 0,
                                  'titulos_bancarios_id'=>'0',
                                  'ultima_alteracao'    =>date("Y-m-d h:m:s"),
                                  'obs'                 =>'TITULO BAIXADO PELO BANCO. VENCIDO A MAIS DE 90 DIAS TITULO Nº '.$titulo[0]->id.'',
                                  'usuarios_id'         =>$COB_Usuario_Id,
                                  'flag_pago'           =>'PARCELA SEM TITULO BANCARIO',
                                  'nossonumero'       => $titulo[0]->id /* se houver nosso numero e o titulos for 0 indica que esse titulo foi baixado pelo banco*/
                                  ));


$list_fat->next();
endwhile;
/******************************************************************** escreve no log ********************************************************************/
$conteudo.=strtoupper(tool::CompletaZeros(5,$registros)." ## BAIXA AUTOMATICA PELO BANCO TITULO VENCIDO A MAIS DE 90 DIAS ## Titulo nº ".tool::CompletaZeros(11,$d_id_titulo_banco)." ######  OK!");
$conteudo.=chr(13).chr(10);
$baixados++;
continue;

}if($d_cod_mov == "10"){                                     /* codigo de confirmação tít. baix. conf. instrução ou por título protestad */

/******************************************************************** atualiza o titulo ********************************************************************/
$up_titulo = titulos::find($titulo[0]->id);
$up_titulo->update_attributes(array('obs'=>' tít. baix. conf. instrução ou por título protestad.','cod_retorno'=>$lote_interno,'linha_retorno'=>$linha_ret,'cod_mov_ret'=>$d_cod_mov));

/******************************************************************** escreve no log ********************************************************************/
$conteudo.=strtoupper(tool::CompletaZeros(5,$registros)." ## TIT. BAIX. CONF. INTRUÇÃO OU POR TITULO PROTESTAD ## Titulo nº ".tool::CompletaZeros(11,$d_id_titulo_banco)." ######  OK!");
$conteudo.=chr(13).chr(10);
$baixados++;
continue;

}if($d_cod_mov == "11"){                                     /* codigo de confirmação de titulo em serviço*/

/******************************************************************** atualiza o titulo ********************************************************************/
$up_titulo = titulos::find($titulo[0]->id);
$up_titulo->update_attributes(array('obs'=>' Em serviço.','cod_retorno'=>$lote_interno,'linha_retorno'=>$linha_ret,'cod_mov_ret'=>$d_cod_mov));

/******************************************************************** escreve no log ********************************************************************/
$conteudo.=strtoupper(tool::CompletaZeros(5,$registros)." ## EM SERVIÇO ## Titulo nº ".tool::CompletaZeros(11,$d_id_titulo_banco)." ######  OK!");
$conteudo.=chr(13).chr(10);
continue;

}if($d_cod_mov == "12"){                                     /* codigo de confirmação de concessão de abatimento em titulo*/

/******************************************************************** atualiza o titulo ********************************************************************/
$up_titulo = titulos::find($titulo[0]->id);
$up_titulo->update_attributes(array('obs'=>' abatimento concedido','cod_retorno'=>$lote_interno,'linha_retorno'=>$linha_ret,'cod_mov_ret'=>$d_cod_mov));

/******************************************************************** escreve no log ********************************************************************/
$conteudo.=strtoupper(tool::CompletaZeros(5,$registros)." ## ABATIMENTO CONCEDIDO ## Titulo nº ".tool::CompletaZeros(11,$d_id_titulo_banco)." ######  OK!");
$conteudo.=chr(13).chr(10);
$alterados++;
continue;

}if($d_cod_mov == "13"){                                     /* codigo de confirmação de cancelamento de concessão de abatimento */

/******************************************************************** atualiza o titulo ********************************************************************/
$up_titulo = titulos::find($titulo[0]->id);
$up_titulo->update_attributes(array('obs'=>' abatimento cancelado','cod_retorno'=>$lote_interno,'linha_retorno'=>$linha_ret,'cod_mov_ret'=>$d_cod_mov));

/******************************************************************** escreve no log ********************************************************************/
$conteudo.=strtoupper(tool::CompletaZeros(5,$registros)." ## ABATIMENTO CANCELADO ## Titulo nº ".tool::CompletaZeros(11,$d_id_titulo_banco)." ######  OK!");
$conteudo.=chr(13).chr(10);
$alterados++;
continue;

}if($d_cod_mov == "14"){                                     /* codigo de confirmação de prorrogação de vencimento*/

/******************************************************************** atualiza o titulo ********************************************************************/
$up_titulo = titulos::find($titulo[0]->id);
$up_titulo->update_attributes(array('obs'=>'Alteração de vencimento confirmada.','cod_retorno'=>$lote_interno,'linha_retorno'=>$linha_ret,'cod_mov_ret'=>$d_cod_mov));

/******************************************************************** escreve no log ********************************************************************/
$conteudo.=strtoupper(tool::CompletaZeros(5,$registros)." ## ALTERAÇÃO DE VENCIMENTO CONFIRMADA ## Titulo nº ".tool::CompletaZeros(11,$d_id_titulo_banco)." ######  OK!");
$conteudo.=chr(13).chr(10);
$alterados++;
continue;

}if($d_cod_mov == "16"){                                     /* codigo de confirmação de titulo ja baixado */

/******************************************************************** atualiza o titulo ********************************************************************/
$up_titulo = titulos::find($titulo[0]->id);
$up_titulo->update_attributes(array('obs'=>'tít. já baixado / liquidadO.','cod_retorno'=>$lote_interno,'linha_retorno'=>$linha_ret,'cod_mov_ret'=>$d_cod_mov));

/******************************************************************** escreve no log ********************************************************************/
$conteudo.=strtoupper(tool::CompletaZeros(5,$registros)." ## TITULO JÁ BAIXADO / LIQUIDADO ## TITULO nº ".tool::CompletaZeros(11,$d_id_titulo_banco)." ######  ATENÇÃO!");
$conteudo.=chr(13).chr(10);
continue;

}if($d_cod_mov == "08" or /* liquidação por saldo */
    $d_cod_mov == "15" or /* Enviado para Cartório */
    $d_cod_mov == "17" or /* liquidado em cartório */
    $d_cod_mov == "21" or /* Entrada em Cartório */
    $d_cod_mov == "22" or /* Retirado de cartório */
    $d_cod_mov == "24" or /* Custas de Cartório */
    $d_cod_mov == "25" or /* Protestar Título */
    $d_cod_mov == "26"    /* 26 = Sustar Protest */
    ){

/******************************************************************** atualiza o titulo ********************************************************************/
$up_titulo = titulos::find($titulo[0]->id);
$up_titulo->update_attributes(array('obs'=>'Codigo de movimentação não encontrado. COD DO MOV ('.$d_cod_mov.')','cod_retorno'=>$lote_interno,'linha_retorno'=>$linha_ret,'cod_mov_ret'   =>$d_cod_mov));

/******************************************************************** escreve no log ********************************************************************/
$conteudo.=strtoupper(tool::CompletaZeros(5,$registros)." ## COD DE MOVIMENTAÇÃO SEM ESCRIÇÃO FAVOR VERIFICAR TABELA DE ERROS. COD DO MOV (".$d_cod_mov.") ## Titulo nº ".tool::CompletaZeros(11,$d_id_titulo_banco)." ######  ERRO!");
$conteudo.= chr(13).chr(10); //  essa é a quebra de linha").chr(13).chr(10);
$erros++;
continue;

}

}
/********************************************************** se o segmento da linha for "" ou 9 trailher ***********************************************/
if($tipo_reg == 9 or $tipo_reg == ""){

  if($tipo_arq == 1){echo '<div class="uk-alert" style="margin:0;"> Arquivo de confirmação de entrega de remessa. não há titulos a tratar.</div>';continue;}
    else{continue;}
}


$h_lote_interno = $lote_interno;

} /* fim do while linha*/



/****************************************************** arquivo de log  **********************************************************************************/

//diretorio
$caminho ="../../../../arquivos/retornos/logs/emp_".$COB_Empresa_Id."_bank_".$Query_retorno->cod_banco."";


// verifica se o caminho onde deve ser salvo arquivo existe se não cria
if (!file_exists($caminho)) { mkdir($caminho, 0777, true);/*cria o diretorio do arquivo*/}


if (!$handle = fopen($caminho."/".$filename_log, 'w')){

echo "<div class='uk-alert uk-alert-warning'> Não foi possivel abrir o arquivo de log.</div>";

}


if (fwrite($handle, "$conteudo") === FALSE){

  echo "<div class='uk-alert uk-alert-warning'> Não foi possivel escrever o arquivo de log.</div>";

}

fclose($handle);


/******************************************************************** atualiza o retorno **********************************************************************************/
$Query_retorno->update_attributes(
                                array('dt_processamento'  => date("Y-m-d"),
                                      't_linhas'          => $registros,
                                      't_baixa'           => $baixados,
                                      't_compensados'     => $compensados,
                                      't_erros'           => $erros,
                                      'lote_retorno'      => $h_lote_interno,
                                      'path_log'          => $caminho."/".$filename_log,
                                      'status'            => 1,
                                      'vlr_credito_arq'   => $creditos,
                                      'vlr_debito_arq'    => $debitos
                                    ));


if(!$Query_retorno){
  echo "<div class='uk-alert uk-alert-warning'> <i class='uk-icon-warning uk-text-warning'></i>Erro ao atualizar dados do retorno ".$Query_retorno->nm_arquivo.".</br></div>";
}



if($creditos > 0){
  /********************************************** lançamentos no caixa  **********************************************************************************/

  // recupera o centro de custo e plano de conta padrao da conta
  $dados_config=configs::find_by_empresas_id($COB_Empresa_Id);


  // query para recupera a forma de recebimento padrão dessa conta
  $Query_conta_bancaria=contas_bancarias::find($Query_retorno->contas_bancarias_id);


  // cria o historico do lançamento no credito
  $historico_C='CREDITO COMPENSAÇÃO BANCARIA';
  $detalhes_C='CREDITO RECEBIMENTO BANCARIO LOTE : '.$h_lote_interno.' OCORRENCIA : '.tool::InvertDateTime(tool::LimpaString($d_dt_liquidacao),"-").' n\r\  Titulos :'.$titulos.'';

  // cria o historico do lançamento no debito
  $historico_D='DEBITO TARIFA BANCARIA.';
  $detalhes_D='DEBITO TARIFA COMPENSAÇÃO BANCARIA LOTE : '.$h_lote_interno.' OCORRENCIA : '.tool::InvertDateTime(tool::LimpaString($d_dt_liquidacao),"-").' n\r\  Titulos :'.$titulos.'';

  /*lança os creditos na caixa*/
  $caixa = caixa::create(
                        array('historico'                 => $historico_C,
                              'detalhes'                  => $detalhes_C,
                              'valor'                     => tool::limpaMoney(number_format($creditos,2,',','.')),
                              'data'                      => $d_dt_efetivacao,
                              'tipolancamento'            => 1,
                              'tipo'                      => 'c',
                              'formas_recebimentos_id'    => $Query_conta_bancaria->formas_recebimentos_id,
                              'clientes_fornecedores_id'  => "2.0000000000", // SERÁ ADIONADO O ZERO ANTES NA PRIMEIRA PÓSIÇÃO INDICANDO QUE É RECEBIMENTO DE BOLETO BANCARIOS
                              'planos_contas_id'          => $dados_config->centros_custos_id,
                              'centros_custos_id'         => $dados_config->planos_contas_id,
                              'contas_bancarias_id'       => $Query_retorno->contas_bancarias_id,
                              'empresas_id'               => $COB_Empresa_Id,
                              'usuarios_id'               => $COB_Usuario_Id
                          ));

  // lança os debitos caixa
  $caixa = caixa::create(
                        array('historico'                 => $historico_D,
                              'detalhes'                  => $detalhes_D,
                              'valor'                     => tool::limpaMoney(number_format($debitos,2,',','.')),
                              'data'                      => $d_dt_liquidacao,
                              'tipolancamento'            => 1,
                              'tipo'                      => 'd',
                              'formas_recebimentos_id'     => $Query_conta_bancaria->formas_recebimentos_id,
                              'clientes_fornecedores_id'  => "2.0000000000", // SERÁ ADIONADO O ZERO ANTES NA PRIMEIRA ÓSIÇÃO INDICANDO QUE É RECEBIMENTO DE BOLETO BANCARIOS
                              'planos_contas_id'          => $dados_config->centros_custos_id,
                              'centros_custos_id'         => $dados_config->planos_contas_id,
                              'contas_bancarias_id'       => $Query_retorno->contas_bancarias_id,
                              'empresas_id'               => $COB_Empresa_Id,
                              'usuarios_id'               => $COB_Usuario_Id
                          ));
}





/**************************************************************** verifica se ouve erros ********************************************************/
if($erros > 0){


/* adcionamos uma notificação avisando que o retorno possui erros */
 $obs='Encontramos inconsistencias no retorno com registro do banco santander favor verificar o arquivo de log ->';
 $obs.='<a href="assets/cobranca/retorno/controllers/Controller_dow_log.php?id='. $Query_retorno->id.' target="blank" class="uk-button uk-button-small" style="border-left:1px solid #ccc; float: right;" > <i class="uk-icon-search " ></i> Detalhes </a>';

$create_notificacao=notificacoes::create(array(
	'usuarios_id'	 => $COB_Usuario_Id,
	'msg'			     => 'Erro de Processamento de Retorno.',
	'obs'			     => $obs,
	'indice'		   => 3,//importancia da msg 0 pouco importante, 1 normal, 2 importante e 3 urgente
	'data_hora'		 => date("Y-m-d h:m:s"),
	'empresas_id'  => $COB_Empresa_Id
));


// Inclui o arquivo class.phpmailer.php localizado na pasta phpmailer
  require("../../../../library/PHPMailer/PHPMailerAutoload.php");

// enviamos um email ao suporte para verificações
$arquivo=$caminho."/".$filename_log;



    echo '<div class="uk-alert uk-alert-danger" style="margin:2px;">  Titulos com erros '.$erros.'<a href="assets/cobranca/retorno/controllers/Controller_dow_log.php?id='.$Query_retorno->id.' target="blank" class="uk-button uk-button-small" style="border-left:1px solid #ccc; float: right;" > <i class="uk-icon-download " ></i> Download </a></div>';
    echo suporte::Email_Suporte("Log de erros","Erro ao processar retorno",$COB_Empresa_Id,$arquivo);



    if($alterados > 0){ echo "<hr><div class='uk-alert uk-alert-warning' style='margin:2px;'> Titulos Alterados ".$alterados."</div></br>"; }
    if($baixados > 0){ echo "<div class='uk-alert uk-alert-warning' style='margin:2px;'> Titulos baixados pelo cedente ".$baixados."</div></br>"; }
    if($entradas > 0){ echo "<div class='uk-alert uk-alert-success' style='margin:2px;'> Confirmações de entrada ".$entradas."</div></br>"; }
    if($cancelados > 0){ echo "<div class='uk-alert uk-alert-warning' style='margin:2px;'> Titulos baixados pelo banco  ".$cancelados."</div></br>"; }
    if($compensados > 0){ echo "<div class='uk-alert uk-alert-primary' style='margin:02px;'> Titulos Compensados ".$compensados."</div></br>"; }

}else{

  if($alterados > 0){ echo "<div class='uk-alert uk-alert-warning' style='margin:2px;'> Titulos Alterados ".$alterados."</div></br>";}
  if($baixados > 0){ echo "<div class='uk-alert uk-alert-warning' style='margin:2px;'> Titulos baixados pelo cedente ".$baixados."</div></br>";}
  if($entradas > 0){ echo "<div class='uk-alert uk-alert-success' style='margin:2px;'> Confirmações de entrada ".$entradas."</div></br>"; }
  if($cancelados > 0){ echo "<div class='uk-alert uk-alert-warning' style='margin:2px;'> Titulos baixados pelo banco  ".$cancelados."</div></br>"; }
  if($compensados > 0){ echo "<div class='uk-alert uk-alert-primary' style='margin:2px;'> Titulos Compensados ".$compensados."</div></br>";}

}


echo '<div class="uk-grid-divider"></div><div class="uk-alert" style="margin:2px;"> Arquivo Processado com sucesso ( '.$registros.' ) titulos processados.</div>';


?>