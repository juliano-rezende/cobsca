<?php

// bibliotecas

require_once("../../../sessao.php");
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');


require_once '../../../library/PHPExcel_1.8.0/Classes/PHPExcel/IOFactory.php';//carrega a biblioteca



/* varremos todos os assegurados do mes anterior e verificamos se ele existe neste mes se existe não faz nada se não existe inserir com status de exlusão*/
$QuerySe_mes_anterior=seguros::find_by_sql("SELECT SQL_CACHE empresas_id,convenios_id,matricula,nm_assegurado,estado_civil,cpf,dt_nascimento,referencia
											FROM seguros
											WHERE referencia =  DATE_SUB(concat (EXTRACT(YEAR FROM CURDATE()),'-',EXTRACT(MONTH FROM CURDATE()),'-01'), INTERVAL 1 MONTH)  AND
											NOT EXISTS(
											SELECT m.matricula
											FROM seguros AS m
											WHERE  m.matricula = seguros.matricula AND m.referencia = concat (EXTRACT(YEAR FROM CURDATE()),'-',EXTRACT(MONTH FROM CURDATE()),'-01'))");

$array_mes_ant= new ArrayIterator($QuerySe_mes_anterior);
while($array_mes_ant->valid()):

/*recebe a referencia para formataçao*/
$ref = new ActiveRecord\DateTime($array_mes_ant->current()->referencia);


/*criamos o assegurado com status de exclusão*/
$create= seguros::create(
						array(
								'empresas_id'	=>$array_mes_ant->current()->empresas_id,
								'convenios_id'	=>$array_mes_ant->current()->convenios_id,
								'matricula'		=>$array_mes_ant->current()->matricula,
								'nm_assegurado'	=>$array_mes_ant->current()->nm_assegurado,
								'estado_civil'	=>$array_mes_ant->current()->estado_civil,
								'cpf'			=>$array_mes_ant->current()->cpf,
								'dt_nascimento'	=>$ref->format('Y-m-d'),
								'dt_ult_exclusao'=>date("Y-m-d"),
								'referencia'	=>date("Y-m")."-01",
								'status'		=>3,
								'obs'			=>"ASSEGURADO EXCLUIDO POR FALTA DE PAGAMENTO!"
								));

if(!$create){echo"Erro ao criar registro do assegurado para exclusão!"; exit();}



$array_mes_ant->next();
endwhile;




/*configurações do arquivo excel*/

$fileType = 'Excel2007';// versão do excel

$objReader = PHPExcel_IOFactory::createReader($fileType);// classe do excel

$objPHPExcel = new PHPExcel();


$objPHPExcel->getProperties()->setCreator("Unicob - Sistema Unificado de Cobrança")
->setTitle("Movimentação de Associados")
->setSubject("Seguro")
->setDescription("Planilha de movimentação mensal")
->setKeywords("Seguro")
->setCategory("Mensal");

/* DEFINE A LARGURA DE CADA COLUNA*/
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(50);

/*  CRIA O TITULO DA PLANILHA  */
$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);

$objPHPExcel->getActiveSheet()->mergeCells('A1:I1')->getStyle("A1:I1")->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle("A1")->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,))
			->getActiveSheet()->getStyle("A1:H1")->getFont()->setBold(true)->setName('ARIAL')->setSize(16)->getColor()->setRGB('000000');

/**TITULO DA PLANILHA*/
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'MOVIMENTAÇÃO DE ASSEGURADOS');



/*  criamos o cabeçalho da tabela */
// Formatar em negrito
$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->getFont()->setBold(true);

// Colocar uma borda
$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('A2')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,))
			->getActiveSheet()->getStyle('B2')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,))
			->getActiveSheet()->getStyle('C2')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,))
			->getActiveSheet()->getStyle('D2')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,))
			->getActiveSheet()->getStyle('E2')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,))
			->getActiveSheet()->getStyle('F2')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,))
			->getActiveSheet()->getStyle('G2')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,))
			->getActiveSheet()->getStyle('H2')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,))
			->getActiveSheet()->getStyle('I2')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,));

$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '9E9E9E')
        )
    )
);


$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(25);

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'SEQ')
									->setCellValue('B2', 'MATRICULA')
									->setCellValue('C2', 'NOME SEGURADO')
									->setCellValue('D2', 'CPF')
									->setCellValue('E2', 'DATA NASC')
									->setCellValue('F2', 'ESTADO CIVIL')
									->setCellValue('G2', 'IDADE')
									->setCellValue('H2', 'DT INCLUSÃO')
									->setCellValue('I2', 'SITUAÇÃO');

/* QUERY PARA RETORNO DOS ASSEGURADOS*/
$query_seguro=seguros::find_by_sql("SELECT SQL_CACHE
									  		seguros.empresas_id,
									  		seguros.convenios_id,
									  		seguros.matricula,
									  		seguros.dt_nascimento,
									  		seguros.dt_ult_inclusao,
									  		seguros.nm_assegurado,
									  		seguros.cpf,
									  		seguros.status,
									  		associados.estado_civil
										FROM
									  		seguros
									 	LEFT JOIN associados ON associados.matricula = seguros.matricula
									 	LEFT JOIN convenios ON convenios.id = seguros.convenios_id
										WHERE
										seguros.referencia =  concat (EXTRACT(YEAR FROM CURDATE()),'-',EXTRACT(MONTH FROM CURDATE()),'-01') AND seguros.status!='3'
										ORDER BY
									  	seguros.nm_assegurado ASC");

$list_seguro= new ArrayIterator($query_seguro);


$linha=3;$seq=1;$inc=0;$exc=0;$sm=0;

while($list_seguro->valid()):


// data de nascimento
$dtnasc = new ActiveRecord\DateTime($list_seguro->current()->dt_nascimento);
// calcula a idade
$idde=tool::CalcularIdade($dtnasc->format("Y-m-d"),date("Y-m-d"));
/* DATA INCLUSÃO NO SEGURO*/
$dtinclusao = new ActiveRecord\DateTime($list_seguro->current()->dt_ult_inclusao);

if($list_seguro->current()->estado_civil == "C"){$stc="Casado (a)";}
								            elseif($list_seguro->current()->estado_civil == "S"){$stc="Solteiro(a)";}
								            elseif($list_seguro->current()->estado_civil == "V"){$stc="Viuvo(a)";}
								            elseif($list_seguro->current()->estado_civil == "A"){$stc="Amasiado(a)";}
								            elseif($list_seguro->current()->estado_civil == "D"){$stc="Divorciado(a)";}
								            else{$stc="Não informado";}
// DEFINIMOS O STATUS DO REGISTRO BASEADO NO ANTERIOR
if($list_seguro->current()->status == '0' ){
$situação 	= "ASSEGURADO NÃO SE ENCAIXA NO PERFIL";
$objPHPExcel->getActiveSheet()->getStyle('I'.$linha.'')->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FF9800'))));

}elseif($list_seguro->current()->status == '1'){
$situação 	= "INCLUIR ASSEGURADO";
$objPHPExcel->getActiveSheet()->getStyle('I'.$linha.'')->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '00C853'))));
$inc++;
}elseif($list_seguro->current()->status == '2'){
$situação 	= "ASSEGURADO SEM MOVIMENTAÇÃO";
$objPHPExcel->getActiveSheet()->getStyle('I'.$linha.'')->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '64B5F6'))));
$sm++;
}elseif($list_seguro->current()->status == '3'){
$situação 	= "EXCLUIR ASSEGURADO";
$objPHPExcel->getActiveSheet()->getStyle('I'.$linha.'')->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'B71C1C'))));

$exc++;
}

$objPHPExcel->getActiveSheet()->getStyle('A'.$linha.':I'.$linha.'')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('A'.$linha.'')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,))
			->getActiveSheet()->getStyle('B'.$linha.'')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,))
			->getActiveSheet()->getStyle('D'.$linha.'')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,))
			->getActiveSheet()->getStyle('E'.$linha.'')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,))
			->getActiveSheet()->getStyle('G'.$linha.'')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,))
			->getActiveSheet()->getStyle('H'.$linha.'')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,))
			->getActiveSheet()->getStyle('I'.$linha.'')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,))
			->getActiveSheet()->getStyle('I'.$linha.'')->getFont()->getColor()->setRGB('FFFFFF');


$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$linha.'', ''.tool::CompletaZeros(4,$seq).'')
									->setCellValue('B'.$linha.'', ''.tool::CompletaZeros(10,$list_seguro->current()->empresas_id.".".$list_seguro->current()->convenios_id.".".$list_seguro->current()->matricula).'')
									->setCellValue('C'.$linha.'', ''.strtoupper($list_seguro->current()->nm_assegurado).'')
									->setCellValue('D'.$linha.'', ''.tool::MascaraCampos("???.???.???-??",$list_seguro->current()->cpf).'')
									->setCellValue('E'.$linha.'', ''.$dtnasc->format('d/m/Y').'')
									->setCellValue('F'.$linha.'', ''.$stc.'')
									->setCellValue('G'.$linha.'', ''.$idde.' anos')
									->setCellValue('H'.$linha.'', ''.$dtinclusao->format('d/m/Y').'')
									->setCellValue('I'.$linha.'', ''.$situação.'');



$linha++;
$seq++;
$list_seguro->next();
endwhile;


/*  CRIA O TITULO DOS TOTAIS
$objPHPExcel->getActiveSheet()->getRowDimension(''.($linha+2).'')->setRowHeight(35);

$objPHPExcel->getActiveSheet()->mergeCells('A'.($linha+2).':I'.($linha+2).'')->getStyle('A'.($linha+2).':I'.($linha+2).'')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
			->getActiveSheet()->getStyle('A'.($linha+2).'')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,))
			->getActiveSheet()->getStyle('A'.($linha+2).':I'.($linha+2).'')->getFont()->setBold(true)->setName('ARIAL')->setSize(16)->getColor()->setRGB('000000');

/*TITULO DA TOTALIZADORA
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.($linha+2).'', 'TOTALIZADORA');





$objPHPExcel->getActiveSheet()->mergeCells('A'.($linha+5).':B'.($linha+5).'');
$objPHPExcel->getActiveSheet()->mergeCells('A'.($linha+5).':B'.($linha+6).'');
$objPHPExcel->getActiveSheet()->mergeCells('A'.($linha+5).':B'.($linha+7).'');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.($linha+5).'', 'Incluidos')
									->setCellValue('A'.($linha+6).'', 'Excluidos')
									->setCellValue('A'.($linha+7).'', 'Sem movimentação');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.($linha+5).'', ''.$inc.'')
									->setCellValue('C'.($linha+6).'', ''.$exc.'')
									->setCellValue('C'.($linha+7).'', ''.$sm.'');
*/


//fecha o arquivo
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);



$filename = "Mov_Seguro_".date("m_Y").".xlsx"; // nome do arquivo

$caminho ="arquivos/empresa_".$COB_Empresa_Id."/";//diretorio


if (!file_exists($caminho)) { mkdir($caminho, 0777, true);/*cria o diretorio do arquivo*/}


//salva o arquivo
$objWriter->save($caminho.$filename);

if(!$objWriter){echo 'Erro ao gerar arquivo excel!';}



/*colocamos o arquivo criado dentro de um arquivo zip para evitar erros*/
 $z = new ZipArchive();

    // Criando o pacote chamado "teste.zip"
    $arq=explode(".",$filename);
    $criou = $z->open(''.$caminho."/".$arq[0].'.zip', ZipArchive::CREATE);
    $linkDow =$arq[0].".zip";

    if ($criou === true) {
        // Criando um diretorio chamado "teste" dentro do pacote
        //$z->addEmptyDir('remessa');
        // Copiando um arquivo do HD para o diretorio "teste" do pacote
        $z->addFile(''.$caminho."/".$filename.'', ''.$filename.'');
        // Salvando o arquivo
        $z->close();

        // apagamos o arquivo txt gerado
        if (!unlink(''.$caminho."/".$filename.'')){echo 'Não foi possivel remover o arquivo excel gerado!';}


		/*envia o arquivo para seguradora com copia para administrador*/

		// Inclui o arquivo class.phpmailer.php localizado na pasta phpmailer
		  require("../../../library/PHPMailer/PHPMailerAutoload.php");

		// enviamos um email ao suporte para verificações
		$arquivo=$caminho."/".$linkDow;


		//$enviar= suporte::Email_Seguradora("Movimentação seguro","Segue em anexo movimentação seguro!",$COB_Empresa_Id,$arquivo);




	echo 'Arquivo Gerado com sucesso!';
	echo '<a href="util/excel/app_exp/Controller_dow.php?arq='.$linkDow.'" target="blank" class="uk-button uk-button-small" style="border-left:1px solid #ccc; float: right;" > <i class="uk-icon-search " ></i> Download </a>';



}else{
		echo 'Erro ao gerar arquivo!';
		}



?>

