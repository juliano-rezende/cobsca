<?php
header("Content-type: text/html;charset=utf-8");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Data no passado
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Sempre modificado
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

require_once("../conexao.php");
$cfg->set_model_directory('../models/');


$logradouros_encontrados=logradouros::find_by_sql("SELECT DISTINCT descricao,id,complemento FROM logradouros WHERE bairros_id='".$_POST['cdbairro']."' ORDER BY descricao ASC");


$logradouros= new ArrayIterator($logradouros_encontrados);
echo'<option value="0" selected="selected">Selecionar</option>';
while($logradouros->valid()):

if($logradouros->current()->complemento != 'NULL'){$complemento=$logradouros->current()->complemento;}else{$complemento="RUA/AV";}

$logradouro=str_replace('RUA', '', strtoupper($logradouros->current()->descricao));
$logradouro=str_replace('AV', '', strtoupper($logradouro));
$logradouro=str_replace('PRAÇA', '', strtoupper($logradouro));
$logradouro=str_replace('TRAVESSA', '', strtoupper($logradouro));
?>
<option value="<?php  echo $logradouros->current()->id; ?>"><?php  echo strtoupper(utf8_encode($complemento." ".$logradouro)); ?></option>
<?php
$logradouros->next();
endwhile;
?>