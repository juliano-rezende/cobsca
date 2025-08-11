<?php


namespace classes\Cnab240Sicoob;

use stdClass;


/**
 * Class Siccob240
 * @package classes\Cnab240Sicoob
 */
class Siccob240
{


   /**
    * Siccob240 constructor.
    */
   public function __construct()
   {
      $this->data = new stdClass();

   }

   /**
    * @param int    $codbanco
    * @param int    $tipoInscricaoSacador
    * @param int    $cnpjSacador
    * @param int    $prefixoCoop
    * @param int    $divisorPrefixo
    * @param int    $contaCorrente
    * @param int    $divisorConta
    * @param string $razaoSocial
    * @param int    $sequencialLote
    * @return string
    */
   public function setParamBanco( $codbanco,  $tipoInscricaoSacador,  $cnpjSacador,  $prefixoCoop,  $divisorPrefixo,  $contaCorrente,  $divisorConta,  $razaoSocial,  $sequencialLote)
   {
      $this->data->codBanco = $codbanco;
      $this->data->tipoInscricaoSacador = $tipoInscricaoSacador;
      $this->data->cnpjSacador = $cnpjSacador;
      $this->data->prefixoCoop = $prefixoCoop;
      $this->data->divisorPrefixo = $divisorPrefixo;
      $this->data->contaCorrente = $contaCorrente;
      $this->data->divisorConta = $divisorConta;
      $this->data->razaoSocial = $razaoSocial;
      $this->data->sequencialLote = $sequencialLote;

   }

   /**
    * 240 posições
    */
   public function addHeaderFile()
   {
      $Line = self::limitaCaracteres(self::soNumero($this->data->codBanco), 3); /*3 codigo do banco*/
      $Line .= "0000";
      $Line .= "0";
      $Line .= self::complementoRegistro(9, "b");/* brancos */
      $Line .= 2;
      $Line .= self::limitaCaracteres(self::soNumero($this->data->cnpjSacador), 14);/*14 numeros*/
      $Line .= self::complementoRegistro(20, "b");/* brancos */
      $Line .= self::limitaCaracteres(self::completaZeros(5, self::soNumero($this->data->prefixoCoop)), 5);/*5 prefixo da cooperativa/agencia*/
      $Line .= self::limitaCaracteres(self::soNumero($this->data->divisorPrefixo), 1);/*1* divisor do prefixo*/
      $Line .= str_pad($this->data->contaCorrente, "12", "0", STR_PAD_LEFT);/*12 conta corrente*/
      $Line .= self::limitaCaracteres(self::soNumero($this->data->divisorConta), 1);/*1 divisor conta corrente*/
      $Line .= self::complementoRegistro(1, "z");/* zeros */
      $Line .= self::limitaCaracteres($this->data->razaoSocial, 30); /*30 razão social da empresa*/
      $Line .= self::limitaCaracteres("SICOOB", 30); /*30*/
      $Line .= self::complementoRegistro(10, "b");/* brancos */
      $Line .= "1";/*1*/
      $Line .= date("dmY"); /*8*/
      $Line .= date("his");/*6*/
      $Line .= self::limitaCaracteres(self::completaZeros(6, self::soNumero($this->data->sequencialLote)), 6); /*6 sequencial do lote*/
      $Line .= "081";/*3*/
      $Line .= self::complementoRegistro(5, "z");/* zeros */
      $Line .= self::complementoRegistro(20, "b");/* brancos */
      $Line .= self::complementoRegistro(20, "b");/* brancos */
      $Line .= self::complementoRegistro(29, "b");/* brancos */
      $Line .= chr(13) . chr(10);

      return $Line;

   }

   /**
    * 240 posições
    */
   public function addHeaderLote()
   {
      $Line = self::limitaCaracteres(self::soNumero($this->data->codBanco), 3); /*3 codigo do banco*/
      $Line .= self::limitaCaracteres(self::completaZeros(4, self::soNumero($this->data->sequencialLote)), 4); /*4 sequencial do lote*/
      $Line .= "1";/*1 tipo de registro*/
      $Line .= "R";/*1 tipo de operação*/
      $Line .= "01";/*2 tipo de serviço*/
      $Line .= self::complementoRegistro(2, "b");/* brancos */
      $Line .= "040";/*3 layout do lote*/
      $Line .= self::complementoRegistro(1, "b");/* brancos */
      $Line .= self::limitaCaracteres(self::soNumero($this->data->tipoInscricaoSacador), 1);/*1*/ /*1 CPF 2 CNPJ/CGC*/
      $Line .= str_pad($this->data->cnpjSacador, "15", "0", STR_PAD_LEFT);/*12 conta corrente*/
      $Line .= self::complementoRegistro(20, "b");/* brancos */
      $Line .= self::completaZeros(5, self::soNumero($this->data->prefixoCoop), 5);/*5 prefixo da cooperativa/agencia*/
      $Line .= " ";/*1* divisor do prefixo*/
      $Line .= str_pad($this->data->contaCorrente, "12", "0", STR_PAD_LEFT);/*12 conta corrente*/
      $Line .= self::limitaCaracteres(self::soNumero($this->data->divisorConta), 1);/*1 divisor conta corrente*/
      $Line .= self::complementoRegistro(1, "b");/* brancos*/
      $Line .= self::limitaCaracteres($this->data->razaoSocial, 30); /*30 razão social da empresa*/
      $Line .= self::complementoRegistro(40, "b");/* brancos */
      $Line .= self::complementoRegistro(40, "b");/* brancos */
      $Line .= self::completaZeros(8, self::soNumero($this->data->sequencialLote)); /*4 sequencial do lote*/
      $Line .= date("dmY"); /*8*/
      $Line .= self::complementoRegistro(8, "z");/* zeros */
      $Line .= self::complementoRegistro(33, "b");/* brancos */
      $Line .= chr(13) . chr(10);

      return $Line;

   }


   /**
    * 240 posições
    * @param int    $sequencialRegistro
    * @param int    $codMovRemessa
    * @param int    $nossoNumero
    * @param int    $parcela
    * @param int    $modalidade
    * @param int    $carteira
    * @param int    $numeroDoc
    * @param int    $dataVenc
    * @param int    $valorNominal
    * @param string $especie
    * @param int    $dataEmissao
    * @param int    $tipoJurosMora
    * @param int    $dataInicioCobrancaJuros
    * @param int    $vlrJurosMora
    * @param int    $identificacaoTitulo
    * @return $this|string
    */
   public function addSeqP(
       $sequencialRegistro,
       $codMovRemessa,
       $nossoNumero,
       $parcela, /* em caso de carne segmento o numero conforme o numero de parcelas auto incremento*/
       $carteira,
       $modalidade,
       $numeroDoc,
       $dataVenc,
       $valorNominal, /*valor já deve vir formatado no padrão sem ponto e sem virgula e com 2 casas decimais*/
       $especie, /*por padrão é duplicata mercantil se não informado como paramentro será utilizado ela*/
       $dataEmissao,
       $tipoJurosMora, /* 0 isento / 1 valor dia / 2 taxa mensal*/
       $dataInicioCobrancaJuros, /* data maior que a data de vencimento na qual se iniciara a incidencia de juros caso não informada utiliza-se a data de vecncimento mais 1*/
       $vlrJurosMoraTx, /* informar percentual ou valor conforme o tipo de juros deverra vir formatado*/
      $identificacaoTitulo
   )
   {
      $Line = self::limitaCaracteres(str_pad(self::soNumero($this->data->codBanco), "3", "0", STR_PAD_LEFT),3); /*3 codigo do banco*/
      $Line .= self::limitaCaracteres(str_pad(self::soNumero($this->data->sequencialLote), "4", "0", STR_PAD_LEFT),4); /*4 sequencial do lote*/
      $Line .= "3";/*1 tipo de serviço*/
      $Line .= self::limitaCaracteres(str_pad(self::soNumero($sequencialRegistro), "5", "0", STR_PAD_LEFT),5);/*5 segmento da linha*/
      $Line .= "P";/*1 tipo de segmento*/
      $Line .= self::complementoRegistro(1, "b");/* brancos */
      $Line .= self::limitaCaracteres(self::soNumero($codMovRemessa), 2); /*4 sequencial do lote*/
      $Line .= self::limitaCaracteres(str_pad(self::soNumero($this->data->prefixoCoop), "5", "0", STR_PAD_LEFT),5);/*5 prefixo da cooperativa/agencia*/
      $Line .= self::limitaCaracteres(self::soNumero($this->data->divisorPrefixo), 1);/*1* divisor do prefixo*/
      $Line .= str_pad($this->data->contaCorrente, "12", "0", STR_PAD_LEFT);/*12 conta corrente*/
      $Line .= self::limitaCaracteres(self::soNumero($this->data->divisorConta), 1);/*1 divisor conta corrente*/
      $Line .= self::complementoRegistro(1, "b");/* brancos */

      /* constroe o nosso numero com 20 posições*/
      $Line .= self::limitaCaracteres(self::completaZeros(10, self::soNumero($nossoNumero)), 10);/*10 nosso numero auto incremento*/
      $Line .= self::limitaCaracteres(self::completaZeros(2,self::soNumero($parcela)), 2); /* 2 parcelas*/
      $Line .= self::limitaCaracteres(self::completaZeros(2,self::soNumero($modalidade)), 2); /* 2 modalidade 01-Cobrança Simples*/
      $Line .= "4"; /* 1 tipo de formulario a4 sem envelopamento*/
      $Line .= self::complementoRegistro(5, "b");/* brancos */

      $Line .= self::limitaCaracteres(self::soNumero($carteira), 1); /*1 carteira*/
      $Line .= "0";/*1 forma de cadastro do titulo no banco*/
      $Line .= self::complementoRegistro(1, "b");/* brancos */
      $Line .= "2";/*1 emissão beneficiario*/
      $Line .= "2";/*1 distribuição beneficiario*/
      $Line .= self::limitaCaracteres(str_pad(self::soNumero($numeroDoc), "15", "0", STR_PAD_LEFT),15); /*15 numero do documento formado por 11 posições matricula com zeros a esquerda e 4 posições mês e ano da referencia da parcela*/
      $Line .= self::limitaCaracteres(self::soNumero($dataVenc), 8);/*8 data de vencimento do titulo padrão dmY*/
      $Line .= self::completaZeros(15, $valorNominal); /*13 valor com 2 casas decimais e zeros a esquerda*/
      $Line .= self::complementoRegistro(5, "z"); /* zeros */
      $Line .= self::complementoRegistro(1, "b"); /* brancos */
      $Line .= "02"; /*2 */
      $Line .= "A"; /*1 aceite*/
      $Line .= self::limitaCaracteres(self::soNumero($dataEmissao), 8);/*8 data de emissão do titulo padrão dmY*/
      $Line .= self::limitaCaracteres(self::soNumero($tipoJurosMora), 1); /* 0 isento / 1 valor dia / 2 taxa mensal / padrão 0*/
      $Line .= self::limitaCaracteres(self::soNumero($dataInicioCobrancaJuros), 8); /*8*/

      $juros = str_replace(",","",$vlrJurosMoraTx); /* valor do percentual a ser aplicado na multa $vlrMultaPercentual*/
      $juros = str_replace(".","",$juros); /* valor do percentual a ser aplicado na multa $vlrMultaPercentual*/
      $Line .= self::limitaCaracteres(self::completaZeros(15, self::soNumero($juros)), 15); /*13 valor com 2 casas decimais e zeros a esquerda*/


      $Line .= "0";     /* '0'  =  Não Conceder desconto '1'  =  Valor Fixo Até a Data Informada  '2'  =  Percentual Até a Data Informada*/
      $Line .= self::complementoRegistro(8, "z"); /* zeros */
      $Line .= self::complementoRegistro(15, "z"); /* zeros */
      $Line .= self::complementoRegistro(15, "z"); /* zeros */
      $Line .= self::complementoRegistro(15, "z"); /* zeros */
      $Line .= self::completaZeros(25,$identificacaoTitulo); /*25 numero do documento formado por
                                                                                           2 posições da empresa,
                                                                                           3 posições do convenio
                                                                                           11 posições matricula
                                                                                           4 posições mês e ano da referencia da parcela e
                                                                                           5 zeros a esquerda*/
      $Line .= "3"; /* 3 não protestar*/
      $Line .= "00";
      $Line .= "0"; /* 1 baixa ou devolução*/
      $Line .= self::complementoRegistro(3, "b"); /* brancos */
      $Line .= "09"; /* 2 moeda*/
      $Line .= self::complementoRegistro(10, "z"); /* zeros */
      $Line .= self::complementoRegistro(1, "b"); /* brancos */
      $Line .= chr(13) . chr(10);

      return $Line;
   }


   /**
    * @param int    $sequencialRegistro
    * @param string $codMovRemessa
    * @param int    $tipoInscricao
    * @param int    $cpfCnpjPagador
    * @param string $nomePagador
    * @param string $enderecoPagador
    * @param string $numeroEndereco
    * @param string $bairroPagador
    * @param string $cepPagador
    * @param string $cidadePagador
    * @param string $ufPagador
    * @return string
    */
   public function addSeqQ(
       $sequencialRegistro,
       $codMovRemessa,
       $tipoInscricao,
       $cpfCnpjPagador,
       $nomePagador,
       $enderecoPagador,
       $numeroEndereco,
       $bairroPagador,
       $cepPagador,
       $cidadePagador,
       $ufPagador
   )
   {
      $Line = self::limitaCaracteres(self::soNumero($this->data->codBanco), 3); /*3 codigo do banco*/
      $Line .= self::limitaCaracteres(self::completaZeros(4, self::soNumero($this->data->sequencialLote)), 4); /*4 sequencial do lote*/
      $Line .= "3";/*1 tipo de registro*/
      $Line .= self::limitaCaracteres(self::completaZeros(5, self::soNumero($sequencialRegistro)), 5); /*5 segmento da linha*/
      $Line .= "Q";/*1 tipo de segmento*/
      $Line .= self::complementoRegistro(1, "b");/* brancos */
      $Line .= self::limitaCaracteres(self::soNumero($codMovRemessa), 2); /*4 codigo movimentação na remessa*/
      $Line .= intval($tipoInscricao); /*1 0 cpf 1 cnpj*/
      $Line .= str_pad(intval($cpfCnpjPagador), "15", "0", STR_PAD_LEFT); // 19 a 33
      $Line .= self::limitaCaracteres(self::Acentuacao($nomePagador), 40);/*40*/
      $Line .= self::limitaCaracteres(self::Acentuacao($enderecoPagador), 34). self::limitaCaracteres(self::completaZeros(6, self::soNumero($numeroEndereco)), 6);/*40*/
      $Line .= self::limitaCaracteres(self::Acentuacao($bairroPagador), 15);/*40*/
      $Line .= self::limitaCaracteres($cepPagador, 8);/*8*/
      $Line .= self::limitaCaracteres(self::Acentuacao($cidadePagador), 15);/*15*/
      $Line .= self::limitaCaracteres($ufPagador, 2);/*2*/
      $Line .= self::limitaCaracteres(self::soNumero($this->data->tipoInscricaoSacador), 1);/*1*/ /*1 CPF 2 CNPJ/CGC*/
      $Line .= str_pad($this->data->cnpjSacador, "15", "0", STR_PAD_LEFT);
      $Line .= self::limitaCaracteres($this->data->razaoSocial, 40);/*40*/
      $Line .= self::complementoRegistro(3, "z");/* zeros */
      $Line .= self::complementoRegistro(20, "b");/* brancos */
      $Line .= self::complementoRegistro(8, "b");/* brancos */
      $Line .= chr(13) . chr(10);

      return $Line;

   }


   /**
    * @param int $sequencialRegistro
    * @param int $codMovRemessa
    * @return string
    */
   public function addSeqR( $sequencialRegistro,  $codMovRemessa,  $dataVenc, $vlrMultaPercentual)
   {

      $Line = self::limitaCaracteres(self::soNumero($this->data->codBanco), 3); /*3 codigo do banco*/
      $Line .= self::limitaCaracteres(self::completaZeros(4, self::soNumero($this->data->sequencialLote)), 4); /*4 sequencial do lote*/
      $Line .= "3";/*1 tipo de registro*/
      $Line .= self::limitaCaracteres(self::completaZeros(5, self::soNumero($sequencialRegistro)), 5); /*5 segmento da linha*/
      $Line .= "R";/*1 tipo de segmento*/
      $Line .= self::complementoRegistro(1, "b");/* brancos */
      $Line .= self::limitaCaracteres(self::soNumero($codMovRemessa), 2); /*4 codigo movimentação na remessa*/

      $Line .= "0";     /* '0'  =  Não Conceder desconto '1'  =  Valor Fixo Até a Data Informada  '2'  =  Percentual Até a Data Informada*/
      $Line .= self::complementoRegistro(8, "z");/* zeros */
      $Line .= self::complementoRegistro(15, "z");/* zeros */

      $Line .= "0"; /* Código da Multa:  '0'  =  Isento '1'  =  Valor Fixo '2'  =  Percentual*/
      $Line .= self::complementoRegistro(8, "z");/* zeros */
      $Line .= self::complementoRegistro(15, "z");/* zeros */

      $Line .= "2"; /* Código da Multa:  '0'  =  Isento '1'  =  Valor Fixo '2'  =  Percentual*/
      $Line .= self::limitaCaracteres(self::soNumero($dataVenc), 8); /* data de vencimento do titulo */

      $multa = str_replace(",","",$vlrMultaPercentual); /* valor do percentual a ser aplicado na multa $vlrMultaPercentual*/
      $multa = str_replace(".","",$multa); /* valor do percentual a ser aplicado na multa $vlrMultaPercentual*/
      $Line .= self::limitaCaracteres(self::completaZeros(15, self::soNumero($multa)), 15); /* valor do percentual a ser aplicado na multa $vlrMultaPercentual*/

      $Line .= self::complementoRegistro(10, "b");/* brancos */
      $Line .= self::complementoRegistro(40, "b");/* brancos */
      $Line .= self::complementoRegistro(40, "b");/* brancos */
      $Line .= self::complementoRegistro(20, "b");/* brancos */

      $Line .= self::complementoRegistro(8, "z");/* zeros */

      $Line .= self::complementoRegistro(3, "z");/* zeros */
      $Line .= self::complementoRegistro(5, "z");/* zeros */
      $Line .= self::complementoRegistro(1, "b");/* zeros */
      $Line .= self::complementoRegistro(12, "z");/* zeros */
      $Line .= self::complementoRegistro(1, "b");/* brancos */
      $Line .= self::complementoRegistro(1, "b");/* brancos */
      $Line .= self::complementoRegistro(1, "z");/* zeros */
      $Line .= self::complementoRegistro(9, "b");/* brancos */
      $Line .= chr(13) . chr(10);

      return $Line;

   }

   /**
    * @param int $qteRegistrosLote
    * @param int $qteTitulosCobranca
    * @param int $vlrTotalTitulosCarteira
    * @return string
    */
   public function trailherLote( $qteRegistrosLote,  $qteTitulosCobranca,  $vlrTotalTitulosCarteira)
   {
      $Line = self::limitaCaracteres(self::soNumero($this->data->codBanco), 3); /*3 codigo do banco*/
      $Line .= self::completaZeros(4, self::soNumero($this->data->sequencialLote)); /*4 sequencial do lote*/
      $Line .= "5";/*1 tipo de registro*/
      $Line .= self::complementoRegistro(9, "b");/* brancos */

      /*Totalização da Cobrança Simples*/
      $Line .= self::limitaCaracteres(self::completaZeros(6, self::soNumero($qteRegistrosLote)), 6);/*Quantidade de Registros no Lote*/

      $Line .= self::limitaCaracteres(self::completaZeros(6, self::soNumero($qteTitulosCobranca)), 6);/*Quantidade de Títulos em Cobrança*/

      $Line .= self::limitaCaracteres(self::completaZeros(17, self::soNumero($vlrTotalTitulosCarteira)), 17); /*Valor Total dos Títulos em Carteiras*/

      /*Totalização da Cobrança Vinculada*/
      $Line .= self::limitaCaracteres(self::completaZeros(6, 0), 6);/*Quantidade de Títulos em Cobrança*/
      $Line .= self::limitaCaracteres(self::completaZeros(17, 0), 17); /*Valor Total dos Títulos em Carteiras*/

      $Line .= self::limitaCaracteres(self::completaZeros(6, 0), 6);/*Quantidade de Títulos em Cobrança*/
      $Line .= self::limitaCaracteres(self::completaZeros(17, 0), 17); /*Valor Total dos Títulos em Carteiras*/

      $Line .= self::limitaCaracteres(self::completaZeros(6, 0), 6);/*Quantidade de Títulos em Cobrança*/
      $Line .= self::limitaCaracteres(self::completaZeros(17, 0), 17); /*Valor Total dos Títulos em Carteiras*/

      $Line .= self::complementoRegistro(8, "b");/* brancos */
      $Line .= self::complementoRegistro(117, "b");/* brancos */
      $Line .= chr(13) . chr(10);

      return $Line;
   }

   /**
    * @param int $qteLotesArquivo
    * @param int $qteRegistrosArquivo
    * @return string
    */
   public function trailherArquivo( $qteLotesArquivo,  $qteRegistrosArquivo)
   {
      $Line = self::limitaCaracteres(self::soNumero($this->data->codBanco), 3); /*3 codigo do banco*/
      $Line .= "9999";
      $Line .= "9";
      $Line .= self::complementoRegistro(9, "b");/* brancos */
      /*Quantidade de Lotes do Arquivo*/
      $Line .= self::limitaCaracteres(self::completaZeros(6, self::soNumero($qteLotesArquivo)), 6);
      /*Quantidade de Registros do Arquivo*/
      $Line .= self::limitaCaracteres(self::completaZeros(6, self::soNumero($qteRegistrosArquivo)), 6);
      $Line .= self::complementoRegistro(6, "z");/* zeros */
      $Line .= self::complementoRegistro(205, "b");/* zeros */
      $Line .= chr(13) . chr(10);

      return $Line;
   }


   /**************************************************************************************************************/

   /**
    * @param $str
    * @return string|string[]|null
    */
   private function soNumero($str)
   {
      return preg_replace("/[^0-9]/", "", $str);
   }
   /**************************************************************************************************************/

   /**
    * @param $int
    * @param $tipo
    * @return string
    */
   private function complementoRegistro($int, $tipo)
   {
      $string = "";

      if ($tipo == "z")//zeros
      {

         for ($i = 1; $i <= $int; $i++) {
            $string .= '0';
         }
      } else if ($tipo == "b")//brancos
      {

         for ($i = 1; $i <= $int; $i++) {
            $string .= " ";
         }
      }

      return $string;
   }
   /**************************************************************************************************************/

   /**
    * @param $str
    * @param $limite
    * @return false|string
    */
   private function limitaCaracteres($str, $limite)
   {
      if (strlen($str) >= $limite) {
         $var = substr($str, 0, $limite);
      } else {
         $max = (int)($limite - strlen($str));
         $var = $str . self::complementoRegistro($max, "b");
      }
      return $var;
   }
   /**************************************************************************************************************/

   /**
    * @param $zeros
    * @param $valor
    * @return string
    */
   private function completaZeros($zeros, $valor)
   {

      $return = str_pad($valor, $zeros, "0", STR_PAD_LEFT);  // retorno "0000000001"

      return $return;
   }

   private function Acentuacao($string){

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

     return  strtr($string, $map);
   }



}
