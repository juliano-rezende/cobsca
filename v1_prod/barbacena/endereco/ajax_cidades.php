<?php
header("Content-type: text/html;charset=utf-8");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Data no passado
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Sempre modificado
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0


require_once("../conexao.php");
$cfg->set_model_directory('../models/');

$cidades_encontrados=cidades::find_by_sql("SELECT descricao,id FROM cidades WHERE estados_id='".$_POST['cdestado']."' ORDER BY descricao ASC");

$cidades= new ArrayIterator($cidades_encontrados);
echo'<option value="" selected="selected">Selecionar</option>';
while($cidades->valid()):
?>

<option value="<?php  echo $cidades->current()->id; ?>"><?php  echo strtoupper(utf8_encode($cidades->current()->descricao)); ?></option>

<?php
$cidades->next();
endwhile;
?>