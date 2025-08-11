<?php

// bibliotecas

require_once("../../../conexao.php");

require_once("../../../functions/funcoes_data.php");

$cfg->set_model_directory('../../../models/');

// mes a assegurar
if(isset( $_POST['datasegurar'] )){
	
	$datasegurado=$_POST['datasegurar'];

	require_once("../../../sessao.php");

	}else{
	
	$datasegurado=date("Y-m")."-01";

	$SCA_Id_empresa=1;

	}
	
require_once '../../../librarys/PHPExcel_1.8.0/Classes/PHPExcel/IOFactory.php';//carrega a biblioteca



$fileType = 'Excel2007';// versão do excel

$fileName = 'plan_base_completa.xlsx';//planilha principal

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

$rename ="Plan_completa_".referencia($datasegurado,"_").".xlsx";//novo nome

$objReader = PHPExcel_IOFactory::createReader($fileType);// classe do excel

$objPHPExcel = $objReader->load($fileName);// carrega o arquivo




/////////////////////////////////////////////////////////  ASSEGURADOS INCLUIDOS  /////////////////////////////////////////////////////////////////////////

$query=seguro::find_by_sql("select * from tbseguro where cdempresa='".$SCA_Id_empresa."' and st='2' and datasegurar='".$datasegurado."' ORDER BY matricula ASC");


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


/////////////////////////////////////////////////////////  ASSEGURADOS EXCLUIDAS  /////////////////////////////////////////////////////////////////////////

$queryex=seguro::find_by_sql("select * from tbseguro where cdempresa='".$SCA_Id_empresa."' and st='3' and datasegurar='".$datasegurado."' ORDER BY matricula ASC");;


$linhasex='2';// LINHA DE INICIO DE ESCRITA


$listex= new ArrayIterator($queryex);

while($listex->valid()):

 

$linhasex++;



// estado civil

if($listex->current()->estadocivil=="c"){$estadocivilex="Casado(a)";}

elseif($listex->current()->estadocivil=="a"){$estadocivilex="Amasiado(a)";} 

elseif($listex->current()->estadocivil=="v"){$estadocivilex="Viúvo(a)";}

elseif($listex->current()->estadocivil=="s"){$estadocivilex= "Solteiro(a)";}

else{$estadocivilex="Nao Informado";}


$dtnex= new ActiveRecord\DateTime($listex->current()->datanasc);

$dateex = new DateTime($dtnex->format("d-m-Y")); // data de nascimento formatada

$intervalex = $dateex->diff( new DateTime( date("Y-m-d")) ); // data agora

$iddeex=$intervalex->format( '%Y' ); //formato da idade

//escreve na planilha

$objPHPExcel->setActiveSheetIndex(1)->setCellValue('A'.$linhasex.'', ''.str_pad($listex->current()->cdempresa.".".$listex->current()->cdconvenio.".".$listex->current()->matricula, 11, "0", STR_PAD_LEFT).'');

$objPHPExcel->setActiveSheetIndex(1)->setCellValue('B'.$linhasex.'', ''.strtoupper(utf8_encode($listex->current()->nmassegurado)).'');

$objPHPExcel->setActiveSheetIndex(1)->setCellValue('C'.$linhasex.'', ''." ".str_pad($listex->current()->cpf, 11, "0", STR_PAD_LEFT).'');

$objPHPExcel->setActiveSheetIndex(1)->setCellValue('D'.$linhasex.'', ''.$dtnex->format('d/m/Y').'');

$objPHPExcel->setActiveSheetIndex(1)->setCellValue('E'.$linhasex.'', ''.$estadocivilex.'');

$objPHPExcel->setActiveSheetIndex(1)->setCellValue('F'.$linhasex.'', ''.$iddeex.' anos');


$listex->next();

endwhile;



/////////////////////////////////////////////////////////  SEM MOVIMENTAÇÃO  /////////////////////////////////////////////////////////////////////////

$querysm=seguro::find_by_sql("select * from tbseguro where cdempresa='".$SCA_Id_empresa."' and st='1' and datasegurar='".$datasegurado."' ORDER BY matricula ASC");



$linhassm='2';// LINHA DE INICIO DE ESCRITA



$listsm= new ArrayIterator($querysm);

while($listsm->valid()):

 

$linhassm++;



// estado civil

if($listsm->current()->estadocivil=="c"){$estadocivilsm="Casado(a)";}

elseif($listsm->current()->estadocivil=="a"){$estadocivilsm="Amasiado(a)";} 

elseif($listsm->current()->estadocivil=="v"){$estadocivilsm="Viúvo(a)";}

elseif($listsm->current()->estadocivil=="s"){$estadocivilsm= "Solteiro(a)";}

else{$estadocivilsm= "Nao Informado";}



$dtnsm= new ActiveRecord\DateTime($listsm->current()->datanasc);

$datesm = new DateTime($dtnsm->format("d-m-Y")); // data de nascimento formatada

$intervalsm= $datesm->diff( new DateTime( date("Y-m-d")) ); // data agora

$iddesm=$intervalsm->format( '%Y' ); //formato da idade

//escreve na planilha

$objPHPExcel->setActiveSheetIndex(2)->setCellValue('A'.$linhassm.'', ''.str_pad($listsm->current()->cdempresa.".".$listsm->current()->cdconvenio.".".$listsm->current()->matricula, 11, "0", STR_PAD_LEFT).'');

$objPHPExcel->setActiveSheetIndex(2)->setCellValue('B'.$linhassm.'', ''.strtoupper(utf8_encode($listsm->current()->nmassegurado)).'');

$objPHPExcel->setActiveSheetIndex(2)->setCellValue('C'.$linhassm.'', ''." ".str_pad($listsm->current()->cpf, 11, "0", STR_PAD_LEFT).'');

$objPHPExcel->setActiveSheetIndex(2)->setCellValue('D'.$linhassm.'', ''.$dtnsm->format('d/m/Y').'');

$objPHPExcel->setActiveSheetIndex(2)->setCellValue('E'.$linhassm.'', ''.$estadocivilsm.'');

$objPHPExcel->setActiveSheetIndex(2)->setCellValue('F'.$linhassm.'', ''.$iddesm.' anos');


$listsm->next();

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


echo "Planilha de assegurados gerada com sucesso!";

?>

