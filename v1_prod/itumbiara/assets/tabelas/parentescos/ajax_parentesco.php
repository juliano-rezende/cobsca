<?php
require_once("../../sessao.php");
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');

$parentescos=parentesco::all();

$encontrados= new ArrayIterator($parentescos);
echo'<option value="" selected="selected">Selecionar</option>';
while($encontrados->valid()):
?>

<option value="<?php  echo $encontrados->current()->id; ?>"><?php  echo strtoupper(utf8_encode($encontrados->current()->descricao)); ?></option>
<?php
$encontrados->next();
endwhile;


?>