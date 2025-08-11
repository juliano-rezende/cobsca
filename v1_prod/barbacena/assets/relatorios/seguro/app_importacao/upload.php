<table width="100%"   cellpadding="0" cellspacing="0">
	<thead class="thead">
       <tr style="height:26px;" >
        <td width="80"  class="text_center" >matricula</td>
        <td width="300"  class="text_left" >Nome</td>
        <td width="150"  class="text_center" >cpf</td>
        <td   class="text_center" >Status</td>
      </tr>
	</thead>
<tbody class="tbody">
<div class="tabs-spacer" style="display:none;">
<?php

require_once"../../../sessao.php";
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

// bibliotecas
require_once("../../../conexao.php");
require_once("../../../extra/conexao.php");
$cfg->set_model_directory('../../../models/');
include_once("../../../functions/funcoes_data.php");
?>
</div>
<?php
$cdempresa=$_POST['cdempresa'];// id da empresa
$mesassegurar=$_POST['mesassegurar'];// mês de referencia para assegurar

// Pasta onde o arquivo vai ser salvo
$_UP['pasta'] = "../Plan_recebidas/";

// Tamanho máximo do arquivo (em Bytes)
$_UP['tamanho'] = 1024 * 1024 * 5; // 2Mb

// Array com as extensões permitidas
$_UP['extensoes'] = array('xlsx','xls');

// Renomeia o arquivo? (Se true, o arquivo será salvo como .jpg e um nome único)
$_UP['renomeia'] = true;

// Array com os tipos de erros de upload do PHP
$_UP['erros'][0] = 'Não houve erro';
$_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
$_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
$_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
$_UP['erros'][4] = 'Não foi feito o upload do arquivo';

// Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
if ($_FILES['arquivo']['error'] != 0) {
die("Não foi possível fazer o upload, erro:<br />" . $_UP['erros'][$_FILES['arquivo']['error']]);
exit; // Para a execução do script
}

// Caso script chegue a esse ponto, não houve erro com o upload e o PHP pode continuar

// Faz a verificação da extensão do arquivo
$extensao = end(explode('.', $_FILES['arquivo']['name']));
if (array_search($extensao, $_UP['extensoes']) === false) {
echo "Por favor, envie arquivos com a seguinte extensõe: xlsx";
}

// Faz a verificação do tamanho do arquivo
else if ($_UP['tamanho'] < $_FILES['arquivo']['size']) {
echo "O arquivo enviado é muito grande, envie arquivos de até 5Mb.";
}

// O arquivo passou em todas as verificações, hora de tentar movê-lo para a pasta
else {
// Primeiro verifica se deve trocar o nome do arquivo
if ($_UP['renomeia'] == true) {
// Cria um nome baseado no UNIX TIMESTAMP atual e com extensão .jpg
$nome_final ="Emp_".$cdempresa."_".$mesassegurar."_".date("d_m_Y").'.xlsx';
} else {
// Mantém o nome original do arquivo
$nome_final = $_FILES['arquivo']['name'];
}
// Depois verifica se é possível mover o arquivo para a pasta escolhida
if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'] . $nome_final)) {
// Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
//echo "Upload efetuado com sucesso!";
//echo '<br /><a href="' . $_UP['pasta'] . $nome_final . '">Clique aqui para acessar o arquivo</a>';

include '../../../librarys/PHPExcel_1.8.0/Classes/PHPExcel.php';
include '../../../librarys/PHPExcel_1.8.0/Classes/PHPExcel/IOFactory.php' ;

$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objReader->setReadDataOnly(true);
$objPHPExcel = $objReader->load("../Plan_recebidas/". $nome_final);
$objWorksheet = $objPHPExcel->getActiveSheet();
$highestRow = $objWorksheet->getHighestRow();
$highestColumn = $objWorksheet->getHighestColumn();
$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

for ($row = 4; $row < $highestRow; $row++) {
	
$matricula= $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();//matricula unica
$nome= utf8_decode($objWorksheet->getCellByColumnAndRow(1, $row)->getValue());//nome do assegurado
$cpf= str_replace("-","",$objWorksheet->getCellByColumnAndRow(2, $row)->getValue());//cpf do assegurado
$cpf= str_replace(".","",$cpf);//cpf do assegurado
$datanascimento=date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($objWorksheet->getCellByColumnAndRow(3, $row)->getValue()));//data de nascimento do assegurado
$estadocivil= strtoupper($objWorksheet->getCellByColumnAndRow(4, $row)->getValue());//estado civil do assegurado

// define o estado civil
switch ($estadocivil) {
    case "CASADO":
        $stcv="c";
        break;
    case "CASADA":
        $stcv="c";
        break;
    case "SOLTEIRO":
        $stcv="s";
        break;
    case "SOLTEIRA":
        $stcv="s";
        break;
    case "DIVORCIADA":
        $stcv="d";
        break;
    case "DIVORCIADO":
        $stcv="d";
        break;
    case "VIÚVA":
        $stcv="v";
        break;
    case "VIÚVO":
        $stcv="v";
        break;
    case "AMASIADO":
        $stcv="a";
        break;
    case "AMASIADA":
        $stcv="a";
        break;
	default:
	 	$stcv="o";
}
echo'<div class="tabs-spacer" style="display:none;">';
// prepara faz o update dos assegurados ja cadastrados no banco para essa empresa
$Query=seguro::find_by_sql("select * from tbseguro where matricula='".$matricula."' and cdempresa='".$cdempresa."'");

if($Query){
	
		$update=seguro::find_by_matricula_and_cdempresa($matricula,$cdempresa);
		$update->update_attributes(
									array(
										''.$mesassegurar.''=>'1',
										));
										
		echo'<tr style="height:26px;" >
        <td width="20"  class="text_center" >'.$matricula.'</td>
        <td width="110"  class="text_left" >'.utf8_encode($nome).'</td>
        <td width="20"  class="text_center" >'.$cpf.'</td>
		<td   class="text_center" >Sem movimentação</td>
        </tr>
		';
	}else{
		
			$dtnasc= date('Y-m-d', strtotime($datanascimento));
			$create = seguro::create(
										array(
										'matricula' =>$matricula,
										'cdempresa'=>''.$cdempresa.'',
										'cdconvenio' =>1,
										'nmassegurado' =>$nome,
										'estadocivil' =>$stcv,
										'cpf' =>$cpf,
										'datanasc' =>$dtnasc,
										'situacao' =>'a',
										''.$mesassegurar.''=>'4',
										));
		echo'<tr style="height:26px;" >
        <td width="20"  class="text_center" >'.$matricula.'</td>
        <td width="110"  class="text_left" >'.utf8_encode($nome).'</td>
        <td width="20"  class="text_center" >'.$cpf.'</td>
		<td   class="text_center" >Incluido</td>
        </tr>';
		}
}
echo'<div/>';
} else {
// Não foi possível fazer o upload, provavelmente a pasta está incorreta
echo "Não foi possível enviar o arquivo, tente novamente";
}
}
?>
</tbody>
</table>