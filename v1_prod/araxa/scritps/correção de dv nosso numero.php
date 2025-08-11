<?php

require_once("conexao.php");
$cfg->set_model_directory('models/');

$Query_titulos=titulos::find_by_sql("SELECT
  titulos_bancarios.id,
  titulos_bancarios.sacado,
  titulos_bancarios.nosso_numero,
  titulos_bancarios.dv_nosso_numero,
  faturamentos.matricula,
  faturamentos.id as id_parcela FROM titulos_bancarios left join faturamentos on faturamentos.titulos_bancarios_id=titulos_bancarios.id WHERE titulos_bancarios.contas_bancarias_id='3' and titulos_bancarios.status='0' and titulos_bancarios.dv_nosso_numero >='0' ORDER BY nosso_numero ASC limit 2");
$Listitles= new ArrayIterator($Query_titulos);





if(!function_exists('formata_numdoc'))
{
  function formata_numdoc($num,$tamanho)
  {
    while(strlen($num)<$tamanho)
    {
      $num="0".$num;
    }
  return $num;
  }
}


$linha=0;
while($Listitles->valid()):



$NossoNumero = formata_numdoc($Listitles->current()->nosso_numero,7);
$qtde_nosso_numero = strlen($NossoNumero);
$sequencia = formata_numdoc("3094",4).formata_numdoc(str_replace("-","","327964"),10).formata_numdoc($NossoNumero,7);
$cont=0;
$calculoDv = '';

  for($num=0;$num<=strlen($sequencia);$num++)
  {
    $cont++;
    if($cont == 1)
    {
      // constante fixa Sicoob » 3197
      $constante = 3;
    }
    if($cont == 2)
    {
      $constante = 1;
    }
    if($cont == 3)
    {
      $constante = 9;
    }
    if($cont == 4)
    {
      $constante = 7;
      $cont = 0;
    }
    $calculoDv = $calculoDv + (substr($sequencia,$num,1) * $constante);
  }
$Resto = $calculoDv % 11;

$Dv = 11 - $Resto;

if ($Resto == 0 or $Resto == 1) $Dv = 0;
if ($Resto == 10) $Dv = 1;
if ($Resto >  10) $Dv = 0;


$dadosboleto["nosso_numero"] = $NossoNumero ."-". $Dv;


if($Listitles->current()->dv_nosso_numero != $Dv ){

if($Listitles->current()->dv_nosso_numero >= 0  && $Dv == 1 ){
  echo "Matricula : [ ".$Listitles->current()->matricula." ] - Nome : ".$Listitles->current()->sacado." Nº boleto : ( ".$dadosboleto["nosso_numero"]." ) - Nº boleto BD : ( ".$Listitles->current()->nosso_numero."-".$Listitles->current()->dv_nosso_numero." ) <br />";

  $Query_update=titulos::find($Listitles->current()->id);
  $Query_update->update_attributes(array('copia_dv_nsm' =>$Dv));
}}
//echo "<br />Sequencia ".$sequencia."<br />";
//echo "Calculo Sequencia ".$calculoDv."<br />";
//echo "Resto Mod_11 ".$Resto."<br />";
//echo "Dv ".$Dv."<br />";
//echo "N Numero ".$dadosboleto["nosso_numero"];


//echo "<br /> ---------------------------------------------------------------------------------------------<br />";

$Listitles->next();
endwhile;

?>