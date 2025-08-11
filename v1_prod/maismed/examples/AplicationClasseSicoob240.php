<?php

require "../classes/Cnab240Sicoob/CnabSicoob240CR.php";

use classes\Cnab240Sicoob;


$obj = new Cnab240Sicoob\Siccob240();
$obj->setParamBanco(
   756,
   2,
   16911117000167,
   4149,
   1,
   71022001,
   4,
   "CARTAO DE DESCONTOS E BENEFICIOS DE BARBACENA LTDA",
   1 // sequencia da remessa
);


$arquivo = $obj->addHeaderFile();
$arquivo .= $obj->addHeaderLote();


$vlrTotal       = 0;
$sequencial_registro  = 1;
$qteLotesArquivo   = 0; /* não sei do que se trata*/
$qteTitulosArquivo   = 0; /* não sei do que se trata*/


while($list_titulo->valid()):

   $arquivo .= $obj->addSeqP(
      $sequencial_registro ,
      "01",
      0001,
      "01",
      01,
      1,
      "1082020", // parcela
      "20200806",
      "1500",
      "02",
      "20200806", // data de vencimento do titulo
      "2",
      "20200806", // data de vencimento do titulo
      "0", // pegar taxa no banco e enviar
      "1202008" // código da parcela
   ); /**  adcionar parametros*/


   $arquivo .= $obj->addSeqQ(
      $sequencial_registro+1,
      "01",
      1,
      "01233751697",
      "juliano rezende",
      "rua jose eduardo de oliveira 30",
      "novo orozino",
      "38180000",
      "Araxa",
      "MG"
   );/**  adcionar parametros*/


   $arquivo .= $obj->addSeqR(
      $sequencial_registro+1,
      "01"
   );/**  adcionar parametros*/


   $vlrTotal   += "5";
   $sequencial_registro++;
   $qteTitulosArquivo++;


   $list_titulo->next();

endwhile;

/*tralher do arquivo*/
$arquivo .= $obj->trailherLote(($sequencial_registro+2),"{$qteTitulosArquivo}","{$vlrTotal}");/**  adcionar parametros*/
$arquivo .= $obj->trailherArquivo(($sequencial_registro+1),($sequencial_registro+2));/**  adcionar parametros*/


// Abre ou cria o arquivo bloco1.txt
// "a" representa que o arquivo é aberto para ser escrito
$handle = fopen("teste.rem", "w");

// Escreve "exemplo de escrita" no bloco1.txt
$escreve = fwrite($handle, $arquivo);

// Fecha o arquivo
fclose($handle);

