<?php
require_once("../../sessao.php");
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');

$cep		=	tool::LimpaString($_POST['cep']);

/* verificamos se ja existe na base de dados*/
$Consulta_cep=logradouros::find_by_sql("SELECT id FROM  logradouros  WHERE  cep = '".$cep."'");

if($Consulta_cep){
	echo 1;
}else{
	echo 0;
}

?>