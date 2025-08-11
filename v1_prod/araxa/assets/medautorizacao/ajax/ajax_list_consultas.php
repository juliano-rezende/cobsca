<?php
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');


$parId 		= $_POST['parId'];
$espId 		= $_POST['espId'];



$query_p=med_procedimentos::find_by_sql("SELECT * FROM med_procedimentos WHERE status='1' AND med_parceiros_id='".$parId ."' AND med_especialidades_id='".$espId."' ORDER BY descricao");

$procedimentos= new ArrayIterator($query_p);

if($query_p){

echo '<option value="" >Selecionar</option>';

while($procedimentos->valid()):

$vlpro=$procedimentos->current()->vlr_custo + $procedimentos->current()->tx_adm;	


    echo '<option value="'.$procedimentos->current()->id.'" >'.$procedimentos->current()->descricao.' - (R$  '.number_format($vlpro,2,",",".").' )</option>';

$procedimentos->next();
endwhile;

}else{
	
	echo '<option value="" >Procedimentos não encontrados</option>';
	}
?>