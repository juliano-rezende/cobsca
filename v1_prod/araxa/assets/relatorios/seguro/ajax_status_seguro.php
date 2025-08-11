<?php
require_once("../../sessao.php");
require_once("../../conexao.php");
require_once("../../functions/funcoes_data.php");
$cfg->set_model_directory('../../models/');

$idreg=$_POST['idreg'];
$matricula=$_POST['matricula'];
$cdconvenio=$_POST['cdconvenio'];
$nmassegurado=base64_decode($_POST['nmassegurado']);
$datanasc=$_POST['datanasc'];
$cpf=$_POST['cpf'];
$estadocivil=$_POST['estadocivil'];

$datasegurar=$_POST['datasegurar'];
$ref_segurar=invertdatain($datasegurar,"-");
$st=$_POST['status'];
$obs="Editado manualmente";

$query=seguro::find_by_matricula_and_datasegurar($matricula,$ref_segurar);


if($query==true){


if($st==0){
	
	$query->delete();
	
	}else{
	

$query->update_attributes(array('st'=>$st));
	
}
}else{


	$create= seguro::create(
	array(
	'cdempresa'=>$SCA_Id_empresa,
	'cdconvenio'=>$cdconvenio,
	'matricula' =>$matricula,
	'nmassegurado' =>$nmassegurado,
	'cpf' =>$cpf,
	'datanasc' =>$datanasc,
	'estadocivil' =>$estadocivil,
	'datasegurar'=>$ref_segurar,
	'st'=>$st,
	'obs'=>$obs
	 
	 ));			
		
}


echo $st;
?>