<?php
header("Content-type: text/html;charset=utf-8");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Data no passado
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Sempre modificado
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

require_once("../conexao.php");
$cfg->set_model_directory('../models/');
$estados_encontrados=estados::find_by_sql("SELECT descricao,sigla,id FROM estados GROUP BY descricao");

if(isset($_POST['estado'])){

	$estado=estados::find($_POST['estado']);
	echo'<option value="'.$estado->id.'">'.$estado->sigla.'</option>';

}else{
	echo'<option value="0">Selecionar</option>';
	}
$estados= new ArrayIterator($estados_encontrados);
while($estados->valid()):
?>

<option value="<?php  echo $estados->current()->id; ?>"><?php  echo strtoupper(utf8_encode($estados->current()->sigla)); ?></option>
<?php

$estados->next();
endwhile;
?>