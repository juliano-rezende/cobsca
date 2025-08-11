<?php
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

$keyword 				= $_POST['keywordpro'];
$med_parceiros_id 		= $_POST['parceiros_id'];
$med_especialidades_id 	= $_POST['especialidades_id'];
$query_p=med_procedimentos::find_by_sql("SELECT * FROM med_procedimentos WHERE descricao LIKE '".$keyword."%' AND status='1' AND med_parceiros_id='".$med_parceiros_id ."' AND med_especialidades_id='".$med_especialidades_id."' ORDER BY descricao limit 10");

$procedimentos= new ArrayIterator($query_p);

if($query_p){
while($procedimentos->valid()):

$vlpro=$procedimentos->current()->vlr_custo + $procedimentos->current()->tx_adm;	
	// add new option
	echo"<tr  style=\"line-height:22px;\" onclick=\"set_item_pro('".$procedimentos->current()->id."')\">";
    echo"<td class=\"uk-width uk-text-center\" style=\"width:50px;\">".$procedimentos->current()->id."</td>";
    echo"<td class=\"uk-text-left\"  >".$procedimentos->current()->descricao."</td>";
	echo"<td class=\"uk-width uk-text-center\" style=\"width:80px;\">R$ ".number_format($vlpro,2,",",".")."</td>";
    echo"</tr>";

$procedimentos->next();
endwhile;

}else{
		// add new option
	echo"<tr  style=\"line-height:22px;\" >";
    echo"<td class=\"uk-text-center\" >Procedimento Não Encontrado</td>";
    echo"<td class=\"uk-text-left\"  ></td>";
    echo"</tr>";

	}
?>