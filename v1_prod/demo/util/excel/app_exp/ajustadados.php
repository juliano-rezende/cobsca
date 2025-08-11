<?php

// bibliotecas

require_once("../../../sessao.php");
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');


/* ano / mes / dia referencia anterior */
$ref_ant=date( "Y-m", strtotime( "-1 month" ))."-01";

/* varremos todos os assegurados do mes anterior e verificamos se ele existe neste mes se existe não faz nada se não existe inserir com status de exlusão*/
$Query_mes_anterior=seguros::find_by_sql("SELECT * FROM seguros WHERE referencia='".$ref_ant."'");



$array_mes_ant= new ArrayIterator($Query_mes_anterior);
while($array_mes_ant->valid()):


	// VERIFICAMOS SE JÁ EXISTE A REFERENCIA SALVA NO TABELA
	$Query_valida_ref=seguros::find_by_sql_matricula_and_referencia($array_mes_ant->current()->matricula,date("Y-m-d"));

	if($Query_valida_ref){


	}else{

		
	}



$array_mes_ant->next();
endwhile;










// recupera os dados do associado
$dadosassociado= seguros::find_by_sql("SELECT max(referencia) FROM seguros WHERE matricula='".$array_mes_ant->current()->matricula."'");

$dt_cad = new ActiveRecord\DateTime($dadosassociado->dt_cadastro);


if($dt_cad->format('Y') < 2017){
	$dt_inc="2017-01-01";
}else{
	$dt_inc=$dt_cad->format('Y-m-d');
}



$query_assoc=seguros::find($array_mes_ant->current()->id);
$query_assoc->update_attributes(array('dt_ult_inclusao'=>$dt_inc));


?>

