<?php

// bibliotecas

require_once("../../../sessao.php");

require_once("../../../conexao.php");

require_once("../../../functions/funcoes_data.php");

$cfg->set_model_directory('../../../models/');



// mes a assegurar
if(isset( $_POST['datasegurar'] )){
	
	$datasegurado=$_POST['datasegurar'];
	}else{
	
	$datasegurado=date("Y-m")."-01";
	}



require_once '../../../librarys/PHPExcel_1.8.0/Classes/PHPExcel/IOFactory.php';//carrega a biblioteca





$fileType = 'Excel2007';// versão do excel

$fileName = 'plan_base_Simplificada.xlsx';//planilha principal

$caminho ="../Plan_enviadas/empresa_".$SCA_Id_empresa."/";//diretorio

// verifica se o caminho onde deve ser salvo arquivo existe se não cria

if (file_exists($caminho)) {

// diretorio onde arquivo vai ser salvo

$dir=$caminho;	

} else {

// cria o diretorio do arquivo   

mkdir($caminho, 0777);

// diretorio do arquivo

$dir=$caminho;

}

$rename ="Plan_Simplificada_".referencia($datasegurado,"_").".xlsx";//novo nome



$objReader = PHPExcel_IOFactory::createReader($fileType);// classe do excel

$objPHPExcel = $objReader->load($fileName);// carrega o arquivo





/////////////////////////////////////////////////////////  ASSEGURADOS /////////////////////////////////////////////////////////////////////////



$query=seguro::find_by_sql("select * from sca_tbseguro where cdempresa='".$SCA_Id_empresa."' and  datasegurar='".$datasegurado."' ORDER BY matricula ASC");





$linhas='2';// LINHA DE INICIO DE ESCRITA

$list= new ArrayIterator($query);

while($list->valid()):

 

$linhas++;



// estado civil

if($list->current()->estadocivil=="c"){$estadocivil="Casado(a)";}

elseif($list->current()->estadocivil=="a"){$estadocivil="Amasiado(a)";} 

elseif($list->current()->estadocivil=="v"){$estadocivil="Viúvo(a)";}

elseif($list->current()->estadocivil=="s"){$estadocivil= "Solteiro(a)";}

else{$estadocivil="Nao Informado";}

	



$dtn= new ActiveRecord\DateTime($list->current()->datanasc);

$date = new DateTime($dtn->format("d-m-Y")); // data de nascimento formatada

$interval = $date->diff( new DateTime( date("Y-m-d")) ); // data agora

$idde=$interval->format( '%Y' ); //formato da idade

//escreve na planilha

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$linhas.'', ''.str_pad($list->current()->cdempresa.".".$list->current()->cdconvenio.".".$list->current()->matricula, 11, "0", STR_PAD_LEFT).'');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$linhas.'', ''.strtoupper(utf8_encode($list->current()->nmassegurado)).'');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$linhas.'', ''." ".str_pad($list->current()->cpf, 11, "0", STR_PAD_LEFT).'');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$linhas.'', ''.$dtn->format('d/m/Y').'');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$linhas.'', ''.$estadocivil.'');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$linhas.'', ''.$idde.'');



$list->next();

endwhile;







//fecha o arquivo

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);

//salva o arquivo

$objWriter->save($dir.$rename);



$create = arq_seguradora::create(

array(

'datacriacao' =>date("Y-m-d h:m:s"),

'arquivo' =>$rename,

'cdempresa' =>$SCA_Id_empresa

));





echo "Planilha de assegurados gerada com sucesso !";

?>

