<?php
require_once("../../sessao.php");


if(isset($_POST['acao'])){


$total=str_replace(",",".",$_POST['valor']);
$parcelas=$_POST['parcelas'];

$vparcela=number_format($total/$parcelas,2,",",".");
echo $vparcela;

}else{

$tac=$_POST['tac'];
$valorbase=$_POST['valorbase'];

$acrescimo=$tac/100;

$v=$valorbase+($valorbase*$acrescimo);

echo number_format($v,2,",",".");

}
?>