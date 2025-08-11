<?php
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

$keyword = $_POST['keyword'];

$query="SELECT id,tipo,nm_fantasia,nm_cliente FROM clientes_fornecedores
		WHERE  (nm_fantasia like '%".$keyword."%' OR  (nm_cliente like '%".$keyword."%') OR  (razao_social like '%".$keyword."%'))
		ORDER BY id ASC LIMIT 0, 10";

$query_p=clientes_fornecedores::find_by_sql($query);
$fornecedor= new ArrayIterator($query_p);

while($fornecedor->valid()):

if($fornecedor->current()->nm_cliente != ""){$nm_cliente=$fornecedor->current()->nm_cliente;}
	else{
		if($fornecedor->current()->nm_fantasia != ""){$nm_cliente=$fornecedor->current()->nm_fantasia;}
		else{$nm_cliente=$fornecedor->current()->razao_social;}
	}
	$nome = str_replace($_POST['keyword'], '<b style="color:#f00;">'.$_POST['keyword'].'</b>', $nm_cliente);
	// add new option
	echo'<tr  style="line-height:22px; text-transform:uppercase;" onclick="set_item(\''.str_replace("'", "\'", utf8_encode($nm_cliente)).'\',\''.$fornecedor->current()->id.'\')">';
    echo'<td class="uk-text-left" style="text-transform:uppercase;" >'.utf8_encode($nm_cliente).'</td>';
    echo'</tr>';

$fornecedor->next();
endwhile;
?>