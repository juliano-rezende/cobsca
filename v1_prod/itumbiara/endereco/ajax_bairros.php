<?php

require_once("../conexao.php");
$cfg->set_model_directory('../models/');

$bairros_encontrados=bairros::find_by_sql("SELECT DISTINCT descricao,id FROM bairros WHERE cidades_id='".$_POST['cdcidade']."' ORDER BY descricao ASC");


$bairros= new ArrayIterator($bairros_encontrados);

echo'<option value="0" selected="selected">Selecionar</option>';

while($bairros->valid()):

echo'<option value="'.$bairros->current()->id.'">'.strtoupper(utf8_encode($bairros->current()->descricao)).'</option>';

$bairros->next();
endwhile;
?>