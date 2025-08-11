<?php

$Frm_cad = true;// fala pra sessão não encerra pois é uma janela de cadastro

set_time_limit(0);
require_once "../../../../sessao.php";
require_once("../../../../conexao.php");
require_once "../../../../classes/Cnab240Sicoob/CnabSicoob240CR.php";
$cfg->set_model_directory('../../../../models/');


$FRM_cod_banco0 = isset($_POST[ 'cod_banco_rem' ]) ? $_POST[ 'cod_banco_rem' ] : tool::msg_erros("O Campo cod_banco_rem é Obrigatorio.");
$FRM_dtinirem = isset($_POST[ 'dtinirem' ]) ? tool::InvertDateTime(tool::LimpaString($_POST[ 'dtinirem' ]), "-") : tool::msg_erros("O Campo dtinirem é Obrigatorio.");
$FRM_dtinifim = isset($_POST[ 'dtinifim' ]) ? tool::InvertDateTime(tool::LimpaString($_POST[ 'dtinifim' ]), "-") : tool::msg_erros("O Campo dtinifim é Obrigatorio.");


// trata o cod do banco
$FRM_cod_banco = explode("_", $FRM_cod_banco0);
$FRM_cod_banco = $FRM_cod_banco[ 0 ];



$fusohorario = 3; // como o servidor de hospedagem é a dreamhost pego o fuso para o horario do brasil
$timestamp = mktime(date("H") - $fusohorario, date("i"), date("s"), date("m"), date("d"), date("Y"));

$DATAHORA[ 'PT' ] = gmdate("d/m/Y H:i:s", $timestamp);
$DATAHORA[ 'EN' ] = gmdate("Y-m-d H:i:s", $timestamp);
$DATA[ 'PT' ] = gmdate("d/m/Y", $timestamp);
$DATA[ 'EN' ] = gmdate("Y-m-d", $timestamp);
$DATA[ 'DIA' ] = gmdate("d", $timestamp);
$DATA[ 'MES' ] = gmdate("m", $timestamp);
$DATA[ 'ANO' ] = gmdate("y", $timestamp);
$HORA = gmdate("H:i:s", $timestamp);


$dadosempresa = empresas::find_by_sql("SELECT
                                        empresas.razao_social,empresas.cnpj,
                                        contas_bancarias.id as conta_id,contas_bancarias.agencia,contas_bancarias.dv_agencia,contas_bancarias.conta,contas_bancarias.dv_conta,
                                        contas_bancarias_cobs.cod_cedente,contas_bancarias_cobs.dv_cod_cedente,contas_bancarias_cobs.carteira_remessa,contas_bancarias_cobs.aceite,contas_bancarias_cobs.modalidade, configs.juros,configs.multa,contas_bancarias_cobs.especie_doc
                                     FROM
                                      empresas
                                     LEFT JOIN
                                      contas_bancarias ON contas_bancarias.empresas_id =  empresas.id
                                     LEFT JOIN
                                      contas_bancarias_cobs ON contas_bancarias_cobs.contas_bancarias_id =  contas_bancarias.id
                                     LEFT JOIN
                                      configs ON configs.empresas_id =  empresas.id
                                     WHERE empresas.id='" . $COB_Empresa_Id . "' AND
                                           contas_bancarias.cod_banco='" . $FRM_cod_banco . "' AND
                                           contas_bancarias.tp_conta='2' AND
                                           contas_bancarias.status='1' ");


$lote = remessas::find_by_sql("SELECT MAX((lote_remessa)+1) AS novolote
                               FROM  remessas_bancarias
                               WHERE cod_banco = '" . $FRM_cod_banco . "' AND
                               contas_bancarias_id='" . $dadosempresa[ 0 ]->conta_id . "' AND
                               empresas_id='" . $COB_Empresa_Id . "' ");

//$novolote = $lote[ 0 ]->novolote;
$novolote = 1;

if ($novolote == "") {
   $lte = 1;
} else {
   $lte = $novolote;
}


$filename = "REM_" . $DATA[ 'DIA' ] . $DATA[ 'MES' ] . $DATA[ 'ANO' ] . $lte ."_".time().".REM";


// pega todos os boletos pessoa fisica
$query_titulos = titulos::find_by_sql("SELECT *
                                     FROM titulos_bancarios
                                     WHERE dv_nosso_numero >='0' AND
                                     contas_bancarias_id='" . $dadosempresa[ 0 ]->conta_id . "' AND
                                     stflagrem='1' AND cod_mov_rem!='35' AND
                                     (dt_emissao BETWEEN '" . $FRM_dtinirem . "' AND '" . $FRM_dtinifim . "' OR dt_atualizacao BETWEEN '" . $FRM_dtinirem . "' AND '" . $FRM_dtinifim . "') ORDER BY nosso_numero ASC");

$list_titulo = new ArrayIterator($query_titulos);

/* verifica se existe titulos*/
if (count($list_titulo) > 0) {

// variaveis quantitativas
$vlrTotal = 0;
$sequencial_registro = 1;
$qteLotesArquivo = 0; /* não sei do que se trata*/
$qteTitulosArquivo = 0; /* não sei do que se trata*/                                          // define o inicio do contador de linhas como 2 pois a primeira está na header seq 000001


$obj = new classes\Cnab240Sicoob\Siccob240();
$obj->setParamBanco(
   "{$FRM_cod_banco}",
   2,
   "{$dadosempresa[0]->cnpj}",
   "{$dadosempresa[0]->agencia}",
   "{$dadosempresa[0]->dv_agencia}",
   "".intval($dadosempresa[0]->conta)."",
   "{$dadosempresa[0]->dv_conta}",
   "CARTAO DE DESCONTOS E BENEFICIOS DE BARBACENA LTDA",
   "{$lte}"
);


$conteudo = $obj->addHeaderFile();
$conteudo .= $obj->addHeaderLote();

while ($list_titulo->valid()):


   /* definimos a data de vencimento do boleto*/
   $dtvenc = new ActiveRecord\DateTime($list_titulo->current()->dt_vencimento);
   $dtemi = new ActiveRecord\DateTime($list_titulo->current()->dt_emissao);


   /*data de cobrança de juros*/
   $data = DateTime::createFromFormat('d/m/Y', $dtvenc->format('d/m/Y'));
   $data->add(new DateInterval('P1D')); // 1 dias
   $dtJurosMora =  $data->format('dmY');


   $data = DateTime::createFromFormat('d/m/Y', $dtvenc->format('d/m/Y'));
   $data->add(new DateInterval('P1D')); // 1 dias
   $dtMulta =  $data->format('dmY');


   $valor_formatado = number_format($list_titulo->current()->vlr_nominal,2,",",".");
   $valor_formatado = str_replace(",","",$valor_formatado);
   $valor_formatado = str_replace(".","",$valor_formatado);
   $valor_formatado = $valor_formatado;

   if ($dadosempresa[ 0 ]->aceite == "N") {
      $aceite = "0";
   } else {
      $aceite = "1";
   }


   $conteudo .= $obj->addSeqP(
      $sequencial_registro,
      "{$list_titulo->current()->cod_mov_rem}",
      "".$list_titulo->current()->nosso_numero.$list_titulo->current()->dv_nosso_numero."",
      "01",
      "{$dadosempresa[0]->carteira_remessa}",
      "{$dadosempresa[0]->modalidade}",
      "02", // tipo de documento
      "{$dtvenc->format('dmY')}",
      "{$valor_formatado}",
      "{$dadosempresa[0]->especie_doc}",
      "{$dtemi->format('dmY')}", // data de vencimento do titulo
      "2",
      "{$dtJurosMora}", // data de vencimento do titulo
      "{$dadosempresa[0]->juros}", // pegar taxa no banco e enviar
      "{$list_titulo->current()->id}" // código da parcela
   );

   $sequencial_registro++;

   $conteudo .= $obj->addSeqQ(
      "".$sequencial_registro."",
      "{$list_titulo->current()->cod_mov_rem}",
      "{$list_titulo->current()->tp_sacado}",
      "{$list_titulo->current()->cpfcnpjsacado}",
      "{$list_titulo->current()->sacado}",
      "".$list_titulo->current()->logradouro."",
      "{$list_titulo->current()->num}",
      "".strtoupper($list_titulo->current()->bairro)."",
      "{$list_titulo->current()->cep}",
      "".strtoupper($list_titulo->current()->cidade)."",
      "".strtoupper($list_titulo->current()->uf).""
   );

   $sequencial_registro++;

   $conteudo .= $obj->addSeqR(
      "".$sequencial_registro."",
      "".$list_titulo->current()->cod_mov_rem."",
      "{$dtMulta}",
      "{$dadosempresa[0]->multa}"
   );

/* update dos titulos*/
   $Query_update=titulos::find($list_titulo->current()->id);
   $Query_update->update_attributes(array('stflagrem' =>0,'dt_remessa'=>date("Y-m-d h:m:s"),'cod_remessa'=>$lte));


   $vlrTotal   += $valor_formatado;
   $sequencial_registro++;
   $qteTitulosArquivo++;

   $list_titulo->next();
endwhile;

/*tralher do arquivo*/
$conteudo .= $obj->trailherLote(($sequencial_registro+2),"{$qteTitulosArquivo}","{$vlrTotal}");
$conteudo .= $obj->trailherArquivo(($sequencial_registro+1),($sequencial_registro+2));

/**************************************************************** FIM DO ARQUIVO ****************************************************************************/

/************************************* SE A QUANTIDADE DE LINHAS FOR MAIOR QUE ZERO GRAVA A REMESSA NO BANCO DE DADOS ***************************************/

   $caminho = "../arquivos/emp_" . $COB_Empresa_Id . "_bank_" . $FRM_cod_banco0 . "";//diretorio

// verifica se o caminho onde deve ser salvo arquivo existe se não cria
   if (!file_exists($caminho)) {
      mkdir($caminho, 0777, true);/*cria o diretorio do arquivo*/
   }

// grava o endereço do retorno no banco
   $query = remessas::create(array('nm_arquivo' => $filename, 'path' => $caminho, 'dt_criacao' => date("Y-m-d"), 'cod_banco' => $FRM_cod_banco, 'contas_bancarias_id' => $dadosempresa[ 0 ]->conta_id, 'linhas' => $qteTitulosArquivo, 'lote_remessa' => $lte, 'empresas_id' => $COB_Empresa_Id));

// abre o  arquivo ou cria para começar a escrever
   if (!$handle = fopen($caminho . "/" . $filename, 'w')) {
      erro("Não foi possível abrir o arquivo ($filename)");
      $msg = '":"","callback":"1","msg":"Não foi possível abrir o arquivo ' . ($filename) . '","cod_banco":"' . ($FRM_cod_banco) . '","status":"danger';
   }

// Escreve $conteudo no nosso arquivo aberto.
   if (fwrite($handle, "$conteudo") === FALSE) {
      $msg = '":"","callback":"1","msg":"Não foi possível escrever no arquivo ' . ($filename) . '","cod_banco":"' . ($FRM_cod_banco) . '","status":"danger';
   }


   fclose($handle);

   $z = new ZipArchive();

   // Criando o pacote chamado "teste.zip"
   $arq = explode(".", $filename);
   $criou = $z->open('' . $caminho . "/" . $arq[ 0 ] . '.zip', ZipArchive::CREATE);

   if ($criou === true) {
      // Criando um diretorio chamado "teste" dentro do pacote
      //$z->addEmptyDir('remessa');
      // Copiando um arquivo do HD para o diretorio "teste" do pacote
      $z->addFile('' . $caminho . "/" . $filename . '', '' . $filename . '');
      // Salvando o arquivo
      $z->close();


      // apagamos o arquivo txt gerado
      if (!unlink('' . $caminho . "/" . $filename . '')) {
         $msg = '":"","callback":"1","msg":"Não foi remover o arquivo texto  ' . $filename . ' ","cod_banco":"' . ($FRM_cod_banco) . '","status":"danger';
      } else {
         $msg = '":"","callback":"0","msg":"Arquivo de remessa gerado com sucesso !","cod_banco":"' . ($FRM_cod_banco) . '","status":"success';
      }

   } else {
      $msg = '":"","callback":"1","msg":"Não foi possível criar o arquivo zip","cod_banco":"' . ($FRM_cod_banco) . '","status":"danger';
   }

} else {
   $msg = '":"","callback":"1","msg":"Não a registros para remessa !","cod_banco":"' . ($FRM_cod_banco) . '","status":"danger';
}

echo $msg;

/************************************************************************************************************************************************************/
?>
