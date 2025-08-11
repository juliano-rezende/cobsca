<?php
require_once "../../../sessao.php";
require_once("../../../library/mpdf60/mpdf.php");

// blibliotecas
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

/* ids das parcelas selecionadas*/
$FRM_ids		  =	isset( $_POST['ids']) 		  ? $_POST['ids']		: tool::msg_erros("Campo informado invalido.");
$FRM_matricula    = isset( $_POST['mat']) 		  ? $_POST['mat']		: tool::msg_erros("Campo informado invalido.");




$nm_arquivo=intval($FRM_matricula)."_".md5($FRM_ids).".pdf";


// verifica se o caminho onde deve ser salvo arquivo existe se não cria
if(file_exists($nm_arquivo)){

echo '":"","callback":"0","arquivo":"'.$nm_arquivo.'","ids":"'.$FRM_ids.'","status":"success';

exit();

}else{


// recupera os dados d
$dadosempresa=empresas::find_by_sql("
									SELECT empresas.*,logradouros.descricao as nm_logradouro,
									logradouros.complemento as complemento,
									logradouros.cep,estados.id AS estado_id,
									estados.sigla AS nm_estado,
									cidades.id AS cidade_id,
									cidades.descricao AS nm_cidade,
									bairros.id AS bairro_id,
									bairros.descricao AS nm_bairro
                                    FROM empresas
                                    INNER JOIN logradouros ON logradouros.id = empresas.logradouros_id
                                    INNER JOIN estados ON estados.id = logradouros.estados_id
                                    INNER JOIN cidades ON cidades.id = logradouros.cidades_id
                                    INNER JOIN bairros ON bairros.id = logradouros.bairros_id
                                    WHERE empresas.id = '".$COB_Empresa_Id."'");

// recupera os dados do associado
$dadosassociado= associados::find_by_sql("SELECT
										  associados.nm_associado,
										  associados.matricula,
										  associados.cpf
										FROM
										  associados
										WHERE
										  associados.matricula = ".$FRM_matricula."");


$html ='
	<div style="width:50%; float: left; border-left:1px solid #ccc; border-right:1px solid #ccc;padding:5px;" id="col_left">
		<h1 style="width: 100%; padding: 0; margin: 0; text-transform: uppercase; font-size: 10px;" class=" uk-text-small uk-text-left">
		Razão social :'. utf8_encode( $dadosempresa[0]->razao_social ).'
		</h1>
		<h1 style="width: 100%; padding: 0; margin: 0; text-transform: uppercase; font-size: 10px;" class=" uk-text-small uk-text-left">
		Nome fantasia: '. utf8_encode( $dadosempresa[0]->nm_fantasia).'
		</h1>
		<h1 style="width: 100%; padding: 0; margin: 0 ; text-transform: uppercase;font-size: 10px;" class=" uk-text-small uk-text-left">
		'.utf8_encode($dadosempresa[0]->complemento." ".$dadosempresa[0]->nm_logradouro).',
		'.$dadosempresa[0]->num.',
		'.$dadosempresa[0]->compl_end.'
		</h1>
		<h1 style="width: 100%; padding: 0; margin: 0 ; text-transform: uppercase;font-size: 10px;" class=" uk-text-small uk-text-left">
		'.utf8_encode($dadosempresa[0]->nm_bairro).' -
		'.utf8_encode($dadosempresa[0]->nm_cidade).' /
		'.utf8_encode($dadosempresa[0]->nm_estado).' -
		'.tool::MascaraCampos("?????-???",$dadosempresa[0]->cep).'
		</h1>
		<h1 style="width: 100%; padding: 0; margin: 0; text-transform: uppercase; font-size: 10px;" class=" uk-text-small uk-text-left">CNPJ: '. tool::MascaraCampos("??.???.???/????-??",$dadosempresa[0]->cnpj).'</h1>
		<h1 style="width: 100%; padding: 0; margin: 0 ; text-transform: uppercase;font-size: 10px;" class=" uk-text-small uk-text-left">IM: '.$dadosempresa[0]->im.'</h1>
		<h1 style="width: 100%; padding: 0; margin: 0 ; text-transform: uppercase;font-size: 10px;" class=" uk-text-small uk-text-left">data: '.date("d/m/Y h:m:s").'</h1>
		<h1 style="width: 100%; padding: 0; margin: 0; font-size: 8px; line-height: 15px;" class="uk-text-left">
		************************************************************************************************************</h1>
		<h1 style="width: 100%; padding: 0; margin: 0; font-size: 14px; line-height: 20px; text-align: center;" class="uk-text-bold uk-text-center">Cartão Unifamilia</h1>
		<h1 style="width: 100%; padding: 0; margin: 0; font-size: 8px;line-height: 15px;" class="uk-text-left">
		************************************************************************************************************</h1>
		<h1 style="width: 100%; padding: 5 0 0 0;  margin: 0; text-transform: uppercase;font-size: 10px; " class=" uk-text-small uk-text-left">Nome: '.$dadosassociado[0]->nm_associado.'</h1>
		<h1 style="width: 100%; padding: 0; margin: 0 ; text-transform: uppercase;font-size: 10px;" class=" uk-text-small uk-text-left">Cpf: '. tool::MascaraCampos("???.???.???/??",$dadosassociado[0]->cpf).'</h1>
		<h1 style="width: 100%; padding: 0; margin: 0; font-size: 11px;text-align: center;" class=" uk-text-small uk-text-center">Comprovante de pagamento</h1>

		<table  class="uk-table uk-table-striped uk-table-hover" >
		<thead style=" text-transform: capitalize; ">
		<tr class="uk-gradient-cinza"style="border-top: 1px solid #ccc;">
			<th style="font-size: 13px;width: 100px;">Nº parcela</th>
			<th style="font-size: 13px;width: 100px;">Referencia</th>
			<th style="font-size: 13px;width: 100px;">Vencimento</th>
			<th style="font-size: 13px;width: 100px;">Valor</th>
			<th style="font-size: 13px;">Pago</th>
		</tr>
		</thead>
		<tbody>
		';

		$total=0;
		$parcelas       = explode(',' ,$FRM_ids);
		$p_id="";

		foreach ($parcelas as $id){
		                                 //   faz um loop usando foreach e recupera os valores

		// recupera ps dados da parcela
		$Query_parcela=faturamentos::find($id);

		$ref = new ActiveRecord\DateTime($Query_parcela->referencia);
		$dtvenc = new ActiveRecord\DateTime($Query_parcela->dt_vencimento);
		$dtpgto = new ActiveRecord\DateTime($Query_parcela->dt_pagamento);

		$total+=$Query_parcela->valor_pago;

		$html.='
				<tr>
					<td style="text-align: center;font-size: 11px;">'. tool::completaZeros("9",$Query_parcela->id).'</td>
					<td style="text-align: center;font-size: 11px;">'. tool::Referencia($ref->format('Ymd'),"/").'</td>
					<td style="text-align: center;font-size: 11px;">'.$dtvenc->format('d/m/Y').'</td>
					<td style="text-align: center;font-size: 11px;">'. number_format($Query_parcela->valor,2,",",".").'</td>
					<td style="text-align: center;font-size: 11px;">'. number_format($Query_parcela->valor_pago,2,",",".").'</td>
				</tr>
			';
		$p_id.="_".intval($id);
		}

$html.='</tbody>';
$html.='<tfoot style="font-size: 8px;">';
$html.='<tr class="uk-gradient-cinza" style="border-top: 1px solid #ccc;">';
$html.='<td style="text-align: center;font-size: 11px;">Total</td>';
$html.='<td ></td>';
$html.='<td ></td>';
$html.='<td >R$</td>';
$html.='<td style="text-align: center;font-size: 11px;">'. number_format($total,2,",",".").'</td>';
$html.='</tr>';
$html.='</tfoot>';
$html.='</table>';

$html.='</div>';




$mpdf=new mPDF();
$mpdf->SetDisplayMode('fullpage');
$mpdf->WriteHTML($html);
$mpdf->Output("arquivos/".$nm_arquivo."",'F');



echo '":"","callback":"0","arquivo":"'.$nm_arquivo.'","ids":"'.$FRM_ids.'","status":"success';
}
?>