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
  echo ("<div class='uk-alert uk-alert-warning'><i class='uk-icon-warning uk-text-warning' ></i> Arquivo Já Processado!</br></div>");
  exit();
}


// definição do arquivo de log
$filename_log = "LOG_".date("dmY")."_retorno_id_".$FRM_id_ret.".txt";
$conteudo = '########################################### ARQUIVO DE LOG DE TRATAMENTO DO RETORNO ###########################################'.chr(13).chr(10);
$conteudo.= '##  ARQUIVO '.$Query_retorno->nm_arquivo.chr(13).chr(10);
$conteudo.= '##  Data '.date("d/m/Y").chr(13).chr(10);
$conteudo.= chr(13).chr(10);



$arquivo=$Query_retorno->path."/".$Query_retorno->nm_arquivo;

// ABRE O ARQUIVO
$lendo = @fopen($arquivo,"r");

// variaveis privadas
$i=0;
$t=0;


// variaveis privadas
$registros  = 0;
$creditos   = 0;
$debitos    = 0;
$compensados= 0;
$erros      = 0;
$titulos    = "";




while (!feof($lendo)){


$linha =" ".fgets($lendo,9999);
$t_tipo_reg =substr($linha,8,1); //Tipo de Registro - Código adotado pela FEBRABAN para identificar o tipo de registro:
                                 //0=Header de Arquivo, 1=Header de Lote, 3=Detalhe, 5=Trailer de Lote, 9=Trailer de Arquivo.


/********************************************************** se o segmento da linha for diferente de 3 não é header do lote então não usamos  ***********************************************/
if($t_tipo_reg != 3 ){ continue; }



$t_u_segmento =substr($linha,14,1);//Segmento T ou U

if($t_u_segmento == 'T'){

//Código do banco na compensação
$t_cod_banco        = substr($linha,1,3);

//Lote de serviço - Número seqüencial para identificar um lote de serviço.
$t_lote             = substr($linha,4,4);

//Nº Sequencial da linha no registro lote
$t_n_sequencial     = substr($linha,9,5);

//Código do convênio no banco - Código fornecido pela CAIXA, através da agência de relacionamento do cliente Deve ser preenchido com o código do Cedente (6 posições).
$t_cod_conv_banco   = substr($linha,23,6);

//Modalidade nosso número
$t_mod_nosso_n      = substr($linha,39,2);

//Identificação do titulo no banco - Número adotado pelo Banco Cedente para identificar o Título.
$t_id_titulo_banco  = ltrim(substr($linha,41,12), "0");

//Data de vencimento do titulo - Data de vencimento do título de cobrança.
$t_dt_vencimento    = substr($linha,70,8);

//Valor nominal do titulo - Valor original do Título. Quando o valor for expresso em moeda corrente, utilizar 2 casas decimais.
$t_v_nominal        = substr($linha,78,15);
$t_v_nominal        = substr($t_v_nominal,0,13).'.'.substr($t_v_nominal,13,2);

//Valor da tarifa/custas
$t_v_tarifa_custas  = substr($linha,194,15);
$t_v_tarifa_custas  = substr($t_v_tarifa_custas,0,13).'.'.substr($t_v_tarifa_custas,13,2);


}/* fim do segmento T e inicio do U*/
if($t_u_segmento == 'U'){


$t_id_titulo_banco;$t_v_tarifa_custas;

//Jurus / Multa / Encargos - Valor dos acréscimos efetuados no título de cobrança, expresso em moeda corrente.
$u_juros_multa      = substr($linha,18,15);
$u_juros_multa      = substr($u_juros_multa,0,13).'.'.substr($u_juros_multa,13,2);

//Valor pago pelo sacado - Valor do pagamento efetuado pelo Sacado referente ao título de cobrança, expresso em moeda corrente.
$u_v_pago           = substr($linha,78,15);
$u_v_pago           = substr($u_v_pago,0,13).'.'.substr($u_v_pago,13,2);

//Valor liquido a ser creditado - Valor efetivo a ser creditado referente ao Título, expresso em moeda corrente.
$u_v_liquido        = substr($linha,93,15);
$u_v_liquido        = substr($u_v_liquido,0,13).'.'.substr($u_v_liquido,13,2);

//Data da ocorrência - Data do evento que afeta o estado do título de cobrança.
$u_dt_ocorrencia    = substr(substr($linha,138,8),4,4).'-'.substr(substr($linha,138,8),2,2).'-'.substr(substr($linha,138,8),0,2);

//Data da efetivação do credito - Data de efetivação do crédito referente ao pagamento do título de cobrança.
$u_dt_efetivacao    = substr(substr($linha,146,8),4,4).'-'.substr(substr($linha,146,8),2,2).'-'.substr(substr($linha,146,8),0,2);

//Data do débito da tarifa
$u_dt_debito_tarifa = substr(substr($linha,158,8),4,4).'-'.substr(substr($linha,158,8),2,2).'-'.substr(substr($linha,158,8),0,2);

//Lote interno do sistema para controle de arquivos sem registro lote criado pela data de ocorrencia tipo dia mes ano
$u_lote_interno     = substr($linha,138,8);


$creditos   += $u_v_pago;                  /* total pago pelo titulo */
$debitos    += $t_v_tarifa_custas;         /* total da tarifa paga pelo titulo */


/* verifica se houve multa e juros*/
$TT_jurosmulta      = $t_v_nominal - $u_v_pago;

if($TT_jurosmulta > 0 ){$vlr_acrescimos=$TT_jurosmulta;}else{$vlr_acrescimos="0.00";}


/********************************************************** titulo nulo ou vazio  **********************************************************************************************/
if($t_id_titulo_banco !=""){


if(substr($t_id_titulo_banco,0,1) == 1){

  $t_id_titulo_banco=ltrim(substr($t_id_titulo_banco,3,12), '0');
}

/********************************************************** consulta o titulo no banco de dados **********************************************************************************/
$titulo=titulos::find_by_sql("SELECT id,tp_sacado,status
                              FROM titulos_bancarios
                              WHERE empresas_id='".$COB_Empresa_Id."' AND contas_bancarias_id='".$Query_retorno->contas_bancarias_id."' AND nosso_numero='".$t_id_titulo_banco."'");


/********************************************************** verificamos se encontrou o titulo ***********************************************************************************/
if(!$titulo){

  echo ("<div class='uk-alert uk-alert-warning'><i class='uk-icon-warning uk-text-warning' ></i>Titulo nº ".$t_id_titulo_banco." não encontrado.</br></div>");
  $conteudo.=strtoupper(tool::CompletaZeros(5,$registros)." ## INCONSISTENTE ## NÃO ENCONTRADO NA BASE DE DADOS TITULO nº ".tool::CompletaZeros(8,$t_id_titulo_banco));
  $conteudo.= chr(13).chr(10); //  essa é a quebra de linha
  $erros++;

  continue;

}if($titulo[0]->status == 1){

$conteudo .=strtoupper(tool::CompletaZeros(5,$registros)." ## TITULO JÁ PROCESSADO ## Titulo nº ".tool::CompletaZeros(8,$t_id_titulo_banco));
$conteudo .= chr(13).chr(10); //  essa é a quebra de linha
continue;

}if($titulo[0]->status == 2){

$conteudo .=strtoupper(tool::CompletaZeros(5,$registros)." ## TITULO CANCELADO PELO CEDENTE ## Titulo nº ".tool::CompletaZeros(8,$t_id_titulo_banco));
$conteudo .= chr(13).chr(10); //  essa é a quebra de linha
$erros++;
continue;

}if($titulo[0]->status == 3){

$conteudo .=strtoupper(tool::CompletaZeros(5,$registros)." ## TITULO NÃO POSSUI PARCELA NO FATURAMENTO ## Titulo nº ".tool::CompletaZeros(8,$t_id_titulo_banco));
$conteudo .= chr(13).chr(10); //  essa é a quebra de linha
$erros++;
continue;

}

/********************************************************** verificamos se duplicidade de registro  ***********************************************************************************/
if(count($titulo) > 1){


/* removemos o registro duplicado deixando o de maior id*/

$id_titulo=0;
$obs="";

$list_ti= new ArrayIterator($titulo);
while($list_ti->valid()):


if($id_titulo > 0){

  if($list_ti->current()->id > $id_titulo){

    /* deletamos o registro duplicado*/
    $del_titlte = titulos::find($list_ti->current()->id);
    $del_titlte->delete();

    //echo ("<div class='uk-alert uk-alert-danger'><i class='uk-icon-danger uk-text-danger' ></i>Titulo ".$list_ti->current()->id." removido por duplicidade. titulo mantido ".$id_titulo."</br></div>");
    $conteudo.=strtoupper(tool::CompletaZeros(5,$registros)." ## DUPLICIDADE removido ".$list_ti->current()->id." mantido ".$id_titulo."");
    $conteudo.=chr(13).chr(10);
    $obs="Titulo ".$list_ti->current()->id." removido por duplicidade. titulo mantido ".$id_titulo."";

  }

}else{

  $id_titulo = $list_ti->current()->id;
  $obs="Titulo Compensando.";
}

$list_ti->next();
endwhile;

}


/******************************************************************** inicia o tratamento do titulo **********************************************************************************/


/* recuperamos todas as parcelas referentes aquele titulo*/
$Q_faturamento = faturamentos::find_by_sql("SELECT faturamentos.id,faturamentos.matricula,faturamentos.referencia,convenios.tipo_convenio,planos.seguro
                                            FROM
                                              faturamentos
                                              LEFT JOIN convenios ON faturamentos.convenios_id = convenios.id
                                              LEFT JOIN dados_cobranca ON dados_cobranca.id =  faturamentos.dados_cobranca_id
                                              LEFT JOIN planos ON planos.id = dados_cobranca.planos_id
                                              WHERE titulos_bancarios_id='".$titulo[0]->id."' AND contas_bancarias_id='".$Query_retorno->contas_bancarias_id."'");
$t_parcelas_fat=count($Q_faturamento);


// no caso da tabela faturamentos devemos dividir o valor pago e as tarifas pelo total de parcelas encontradas
if($t_parcelas_fat > 1 ){ $new_vl_pago = $u_v_pago / $t_parcelas_fat; }else{ $new_vl_pago = $u_v_pago; }

// verificamos se o titulo existe na tabela faturamentos e conta quantas vezes ele existe
// maior que 0 ele faz o loop e atualiza os dados
if($t_parcelas_fat > 0){

// mensagem caso haja procedimento imbutidos no titulo
$msg_pro="";
$msg_fat="";

// o loop em si
$list_fat= new ArrayIterator($Q_faturamento);
while($list_fat->valid()):


/*********************************************************** SE POSSUI SEGURO FAZ A INSERÇÃO DO DADOS NA TABELA DE SEGURADOS *******************************************************/
    if($list_fat->current()->seguro == 1){

    // passa a matricula a referencia e o tipo de convenio pj ou pf
    $Query_assegurar = seguros::segurar($list_fat->current()->matricula,$list_fat->current()->referencia,$list_fat->current()->tipo_convenio,$COB_Empresa_Id);

      // VERIFICA SE CORREU TUDO BEM NA INSERÇÃO DO DADOS NA TABELA SEGUROS
      if($Query_assegurar == false){}
    }

/******************************************************************** atualiza o faturamento  **********************************************************************************/

$up_faturamento = faturamentos::find($list_fat->current()->id);
$up_faturamento->update_attributes(
                          array(
                              'status'          => 1,
                              'tipo_baixa'     =>'B',
                              'dt_pagamento'    =>$u_dt_ocorrencia,
                              'valor_pago'        =>$new_vl_pago,
                              'ultima_alteracao'=>date("Y-m-d h:m:s"),
                              'flag_pago'        =>'PAGO',
                              'usuarios_id'     =>$COB_Usuario_Id,
                              ));


/* verifica se existe procedimento atrelhado a este titulo consultas ou exames */
$up_procedimentos = procedimentos::find_by_faturamentos_id($list_fat->current()->id);

if($up_procedimentos){
  $msg_pro=" | Parcela possui procedimento."; $up_procedimentos->update_attributes(array('status'=> 3,'obs'=>'Procedimento pago titulo nº '.$t_id_titulo_banco.''));
}

/********* SE POSSUI SEGURO FAZ A INSERÇÃO DO DADOS NA TABELA DE SEGURADOS ***********************************************************************************************/
    if($list_fat->current()->seguro == 1){

    // passa a matricula a referencia e o tipo de convenio pj ou pf
    $Query_assegurar = seguros::segurar($list_fat->current()->matricula,$list_fat->current()->referencia,$list_fat->current()->tipo_convenio,$COB_Empresa_Id);

      /* VERIFICA SE CORREU TUDO BEM NA INSERÇÃO DO DADOS NA TABELA SEGUROS
      if($Query_assegurar == false){

      $msg_pro=" | Assegurado.";
      }*/
    }

$list_fat->next();
endwhile;

/***************  atualiza o titulo ******************************************************************************************************************************************/

$up_titulo      = titulos::find($titulo[0]->id);
$up_titulo->update_attributes(
                          array(
                              'status'          =>1,
                              'dt_pagamento'    =>$u_dt_ocorrencia,
                              'vlr_pago'        =>$u_v_pago,
                              'vlr_tarifa'      =>$t_v_tarifa_custas,
                              'vlr_acrescimos'  =>$vlr_acrescimos,
                              'dt_processamento'=>date("Y-m-d h:m:s"),
                              'dt_ocorrencia'   =>$u_dt_ocorrencia,
                              'dt_efet_cred'    =>$u_dt_efetivacao,
                              'dt_deb_tarifa'   =>$u_dt_debito_tarifa,
                              'dt_ocorrencia'   =>$u_dt_ocorrencia,
                              'st_flag_ret'     =>'1',
                              'obs'             =>$obs,
                              'cod_retorno'     =>$u_lote_interno,
                              'usuarios_id'     =>$COB_Usuario_Id
                            ));

if($t_parcelas_fat > 0){
 $msg_fat=strtoupper(" Titulo ".tool::CompletaZeros(11,$t_id_titulo_banco)." Sacado ".remessas::Limit($up_titulo[0]->sacado,40)." Não encontrado em faturamentos.");
}

echo "<div class='uk-alert uk-alert-success'><i class='uk-icon-success'></i>Titulo nº ".$t_id_titulo_banco." compensado com sucesso. ".$msg_pro."</br></div>";
$conteudo.=strtoupper(tool::CompletaZeros(5,$registros)." ## COMPENSADO ## Titulo nº ".tool::CompletaZeros(8,$t_id_titulo_banco." - ".$msg_pro." - ".$msg_fat));
$conteudo.=chr(13).chr(10);


}else{

echo "<div class='uk-alert uk-alert-warning'><i class='uk-icon-warning uk-text-warning'></i>Titulo nº ".$t_id_titulo_banco." não encontrado em faturamentos.</br></div>";
$conteudo.=strtoupper(tool::CompletaZeros(5,$registros)." ## NÃO ENCONTRADO EM FATURAMENTO ## Titulo nº ".tool::CompletaZeros(11,$t_id_titulo_banco));
$conteudo.=chr(13).chr(10);
$erros++;

continue;
}


} /*  fim do if de validação de titulo nulo o vazio*/

/* contador de registros */
$registros++;
$compensados++;

}/* fim do segmento U*/


}/* fim do while linha */


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
                                      't_compensados'     => $compensados,
                                      't_erros'           => $erros,
                                      'lote_retorno'      => $u_lote_interno,
                                      'path_log'          => $caminho."/".$filename_log,
                                      'status'            => 1,
                                      'vlr_credito_arq'   => $creditos,
                                      'vlr_debito_arq'    => $debitos
                                    ));

if(!$Query_retorno){
  echo "<div class='uk-alert uk-alert-warning'><i class='uk-icon-warning uk-text-warning'></i>Erro ao atualizar dados do retorno ".$Query_retorno->nm_arquivo.".</br></div>";

}


  if($creditos > 0){
  /******************************************************************** lançamentos no caixa  **********************************************************************************/

  // recupera o centro de custo e plano de conta padrao da conta
  $dados_config=configs::find_by_empresas_id($COB_Empresa_Id);


  // query para recupera a forma de recebimento padrão dessa conta
  $Query_conta_bancaria=contas_bancarias::find($Query_retorno->contas_bancarias_id);



  // cria o historico do lançamento no credito
  $historico_C='CREDITO COMPENSAÇÃO BANCARIA';
  $detalhes_C='CREDITO RECEBIMENTO BANCARIO LOTE : '.$u_lote_interno.' OCORRENCIA : '.tool::InvertDateTime(tool::LimpaString($u_dt_ocorrencia),"-").' ';

  // cria o historico do lançamento no debito
  $historico_D='DEBITO TARIFA BANCARIA.';
  $detalhes_D='DEBITO TARIFA COMPENSAÇÃO BANCARIA LOTE : '.$u_lote_interno.' OCORRENCIA : '.tool::InvertDateTime(tool::LimpaString($u_dt_ocorrencia),"-").' ';



  // lança os creditos na caixa
  $caixa = caixa::create(
                        array('historico'                 => $historico_C,
                              'detalhes'                  => $detalhes_C,
                              'valor'                     => $creditos,
                              'data'                      => $u_dt_efetivacao,
                              'tipolancamento'            => 1,
                              'tipo'                      => 'c',
                              'formas_recebimentos_id'     => $Query_conta_bancaria->formas_recebimentos_id,
                              'clientes_fornecedores_id'  => "2.0000000000", // SERÁ ADIONADO O ZERO ANTES NA PRIMEIRA ÓSIÇÃO INDICANDO QUE É RECIMENTO DE BOLETO BANCARIOS
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
                              'valor'                     => $debitos,
                              'data'                      => $u_dt_efetivacao,
                              'tipolancamento'            => 1,
                              'tipo'                      => 'd',
                              'formas_recebimentos_id'     => $Query_conta_bancaria->formas_recebimentos_id,
                              'clientes_fornecedores_id'  => "2.0000000000", // SERÁ ADIONADO O ZERO ANTES NA PRIMEIRA ÓSIÇÃO INDICANDO QUE É RECIMENTO DE BOLETO BANCARIOS
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
 $obs='Encontramos inconsistencias no retorno sem registro do banco santander favor verificar o arquivo de log ->';
 $obs.='<a href="assets/cobranca/retorno/controllers/Controller_dow_log.php?id='. $Query_retorno->id.' target="blank" class="uk-button uk-button-small" style="border-left:1px solid #ccc; float: right;" > <i class="uk-icon-search " ></i> Detalhes </a>';

$create_notificacao=notificacoes::create(array(
  'usuarios_id'  => $COB_Usuario_Id,
  'msg'          =>  'Erro de Processamento de Retorno.',
  'obs'          => $obs,
  'indice'       => 3,//importancia da msg 0 pouco importante, 1 normal, 2 importante e 3 urgente
  'data_hora'    => date("Y-m-d h:m:s"),
  'empresas_id'  => $COB_Empresa_Id
));


// Inclui o arquivo class.phpmailer.php localizado na pasta phpmailer
  require("../../../../library/PHPMailer/PHPMailerAutoload.php");

// enviamos um email ao suporte para verificações
$arquivo=$caminho."/".$filename_log;



//echo '<div class="uk-alert uk-alert-danger" style="margin:0;">  Titulos com erros '.$erros.'<a href="assets/cobranca/retorno/controllers/Controller_dow_log.php?id='.$Query_retorno->id.' target="blank" class="uk-button uk-button-small" style="border-left:1px solid #ccc; float: right;" > <i class="uk-icon-download " ></i> Download </a></div>';
echo suporte::Email_Suporte("Log de erros","Erro ao processar retorno",$COB_Empresa_Id,$arquivo);

}


echo "<div class='uk-alert uk-alert-success'><i class='uk-icon-success'></i>Total de Titulos processados ".$i."</br></div>";
echo "<div class='uk-alert uk-alert-success'><i class='uk-icon-success'></i>Total de creditos ".$creditos."</br></div>";
echo "<div class='uk-alert uk-alert-success'><i class='uk-icon-success'></i>Total de debitos ".$debitos."</br></div>";



 ?>
