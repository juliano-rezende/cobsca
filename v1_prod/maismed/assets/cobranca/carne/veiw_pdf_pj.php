<?php

ob_start();
set_time_limit(0);
ignore_user_abort(1);

include("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

require_once("../../../library/mpdf60/mpdf.php");


$FRM_convenio 	= isset( $_GET['cid']) 	? $_GET['cid'] 	: tool::msg_erros("O Campo ids Obrigatorio faltando.");
$FRM_valor      = isset( $_GET['vlr']) 	? $_GET['vlr'] 	: tool::msg_erros("O Campo action Obrigatorio faltando.");
$FRM_vencimento = isset( $_GET['vnc'])  ? $_GET['vnc'] 	: tool::msg_erros("O Campo action Obrigatorio faltando.");
$FRM_referencia = isset( $_GET['ref'])  ? $_GET['ref'] 	: tool::msg_erros("O Campo action Obrigatorio faltando.");
$FRM_action = "E";


$dados_titulo = faturamentos::find_by_sql("SELECT
    empresas.id as empresa_id,
    empresas.nm_fantasia,
    empresas.cnpj,
    empresas.fone_cel,
    empresas.logomarca,
    logemp.complemento AS emp_complemento,
    logemp.descricao AS emp_logradouro,
    empresas.num AS emp_num,
    baiemp.descricao AS emp_bairro,
    cidemp.descricao AS emp_cidade,
    estemp.sigla AS emp_estado,
    empresas.compl_end AS emp_compl_end,
    logemp.cep AS emp_cep,
    convenios. dt_cadastro AS cad_conv,
    convenios. razao_social AS razao_social_conv,
    convenios. cnpj AS cnpj_conv,
    convenios.id AS id_conv
FROM
convenios
    LEFT JOIN empresas ON empresas.id = convenios.empresas_id
    LEFT JOIN logradouros AS logemp ON logemp.id = empresas.logradouros_id
    LEFT JOIN bairros AS baiemp ON baiemp.id = logemp.bairros_id
    LEFT JOIN cidades AS cidemp ON cidemp.id = logemp.cidades_id
    LEFT JOIN estados AS estemp ON logemp.id = logemp.estados_id
WHERE
convenios.id = '".$FRM_convenio."'");


require_once("include/funcoes_carne.php");// carrega as funções do boleto

$html ='';
$html .='
<html>
<head>
    <TITLE>Carnê de Cobrança</TITLE>
    <META http-equiv=Content-Type content=text/html charset="utf-8">
    <meta name="Generator" content="Projeto BoletoPHP - www.boletophp.com.br - Licença GPL"/>
    <style type=text/css>
        <!--
        .cp {
            font: bold 10px Arial;
            color: black;
            padding-left: 2px;
            text-transform: uppercase;
        }

        <!--
        .ti {
            font: 9px Arial, Helvetica, sans-serif
        }

        <!--
        .ld {
            font: bold 13px Arial;
            color: #000000
        }

        <!--
        .ct {
            font-size: 9px "Arial";
            color: #000;
            padding-left: 5px;
        }

        <!--
        .cn {
            font-size: 9px "Arial Narrow";
            color: black
        }

        <!--
        .bc {
            font: bold 18px Arial;
            color: #000000
        }

        <!--
        .ld2 {
            font: bold 10px Arial;
            color: #000000
        }

        .style3 {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 7px;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .bc1 {
            font: bold 20px Arial;
            color: #000000
        }

        .img img {
            height: 60px;
        }
        -->
    </style>
</head>
<body>';

$nm = md5(microtime());
// SEUS DADOS
$dadosboleto["logomarca"] = tool::CompletaZeros(3, $dados_titulo[0]->logomarca);
$dadosboleto["identificacao"] = utf8_encode($dados_titulo[0]->nm_fantasia);
$dadosboleto["cpf_cnpj"] = tool::MascaraCampos("??.???.???/????-??", $dados_titulo[0]->cnpj);
$dadosboleto["endereco"] = strtoupper(utf8_encode($dados_titulo[0]->emp_complemento . " " . $dados_titulo[0]->emp_logradouro));
$dadosboleto["cidade_uf"] = strtoupper(utf8_encode($dados_titulo[0]->emp_cidade)) . " - " . strtoupper($dados_titulo[0]->emp_estado) . " CEP " . $dados_titulo[0]->emp_cep;
$dadosboleto["cedente"] = strtoupper(utf8_encode($dados_titulo[0]->nm_fantasia));

$dadosboleto["data_vencimento"] = $FRM_vencimento;

$taxa_boleto = 0;
$valor_cobrado = str_replace(",", ".", $FRM_valor);
$valor_boleto = number_format($valor_cobrado + $taxa_boleto, 2, ',', '');
$dadosboleto["valor_boleto"] = $valor_boleto;

$data_ref_linha_digitavel = tool::Referencia(tool::LimpaString($FRM_referencia),"");

$dt_cad_conv= new ActiveRecord\DateTime($dados_titulo[0]->cad_conv);
$dt_cadastro = $dt_cad_conv->format('ym');

$dadosboleto["linhadigital"]  = "2.".$dt_cadastro . "." . tool::CompletaZeros(2, $dados_titulo[0]->empresa_id) . "." . tool::CompletaZeros(11, $dados_titulo[0]->id_conv) . "." . $data_ref_linha_digitavel . "." . preg_replace('/[^0-9]/', '', tool::CompletaZeros(9, trim($valor_boleto)));
$dadosboleto["codigo_barras"] = "2".$dt_cadastro . tool::CompletaZeros(2, $dados_titulo[0]->empresa_id) . tool::CompletaZeros(11, $dados_titulo[0]->id_conv) . $data_ref_linha_digitavel .  preg_replace('/[^0-9]/', '', tool::CompletaZeros(9, trim($valor_boleto)));



$dadosboleto["referencia"]    = tool::Referencia(tool::LimpaString($FRM_referencia),"/");

$dadosboleto["sacado"] = utf8_encode(ucwords($dados_titulo[0]->razao_social_conv)) . " " . tool::MascaraCampos("??.???.???/????", $dados_titulo[0]->cnpj_conv);

$dadosboleto["cod_mais_med"] = $dt_cadastro. "." . $dados_titulo[0]->empresa_id . ".".tool::CompletaZeros(7, $dados_titulo[0]->id_conv);

header ('Content-type: text/html; charset=UTF-8');
$html .= '
<div style="margin-top: 20px;">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 2px;">
        <tr>
            <td style="height:8pt;width: 150px; max-width: 150px; max-height: 30px;">
                <img src="https://cobsca.com.br/maismed/assets/cobranca/carne/imagens/logo_carne.jpg" alt="logo" width="120" height="25px;" style="padding:1px;height: 25px !important;max-height: 25px;">
            </td>
            <td style="border-left: 3px solid #000; border-right: 3px solid #000;text-align: center; font-weight: bold; font-size: 14px; width: 80px; max-width: 80px; color: #000;max-height: 30px;">000-3</td>
            <td style="text-align: right;font-size: 14px; font-weight: bold;color: #000;max-height: 30px;" colspan="4">'. $dadosboleto["linhadigital"].'</td>
        </tr>
        <tr>
            <td style="border-bottom: 2px solid #000; margin-top: 2px; height: 2pt;" colspan="6"></td>
        </tr>
    </table>
    
    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 2px;">
        <tr>
            <td style="border-left: 5px solid #000; padding-left: 5px;"><span class="ct">Cedente</span></td>
            <td style="width:180px;max-width:180px; border-left: 5px solid #000;height: 20px; padding-left: 5px;"><span class="ct">Vencimento</span></td>
        </tr>
        <tr>
            <td class="cp" style="border-bottom: 1px solid #000; border-left: 5px solid #000;">
                <span class="cp">'. ($dadosboleto["cedente"]) . " CNPJ: " . $dadosboleto["cpf_cnpj"].'</span>
               </td>
            <td class="cp" style="width:180px;max-width:180px;border-bottom: 1px solid #000; border-left: 5px solid #000; text-align: right">'. $dadosboleto["data_vencimento"].'</td>
        </tr>
    </table>

    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 2px;">
        <tr>
            <td style="border-left: 5px solid #000; padding-left: 5px;"><span class="ct">Referencia</span></td>
            <td style="border-left: 5px solid #000; padding-left: 5px;"><span class="ct">Cliente</span></td>
            <td style="border-left: 5px solid #000; padding-left: 5px;"><span class="ct">Espécie Doc</span></td>
            <td style="border-left: 5px solid #000; padding-left: 5px;"><span class="ct">Aceite</span></td>
            <td style="border-left: 5px solid #000; padding-left: 5px;"><span class="ct">Data Process</span></td>
            <td style="width:180px;max-width:180px; border-left: 5px solid #000;height: 20px; padding-left: 5px;"><span class="ct">Valor Documento</span></td>
        </tr>
        <tr>
            <td class="cp" style="border-bottom: 1px solid #000; border-left: 5px solid #000;">'. $dadosboleto["referencia"].'</td>
            <td class="cp" style="border-bottom: 1px solid #000; border-left: 5px solid #000;">'. $dadosboleto["cod_mais_med"].'</td>
            <td class="cp" style="border-bottom: 1px solid #000; border-left: 5px solid #000;">DM</td>
            <td class="cp" style="border-bottom: 1px solid #000; border-left: 5px solid #000;">N</td>
            <td class="cp" style="border-bottom: 1px solid #000; border-left: 5px solid #000;">'. date("d/m/Y").'</td>
            <td class="cp" style="width:180px;max-width:180px;border-bottom: 1px solid #000; border-left: 5px solid #000; text-align: right">R$ '. $dadosboleto["valor_boleto"] .'</td>
        </tr>
    </table>

    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 2px;">
        <tr>
            <td style="border-left: 5px solid #000; padding-left: 5px;"><span class="ct">Sacado</span></td>
        </tr>
        <tr>
            <td class="cp" style="border-bottom: 1px solid #000; border-left: 5px solid #000;height: 20px;"><span class="cp">'. $dadosboleto["sacado"].'</span></td>
        </tr>
    </table>
</div>
<div style="width: 100%;border-bottom: 2px dotted #000; margin-top: 15px;margin-bottom: 8px;"></div>
<div>
    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 2px;">
        <tr>
            <td style="height:8pt;width: 150px; max-width: 150px; max-height: 30px;">
                <img src="https://cobsca.com.br/maismed/assets/cobranca/carne/imagens/logo_carne.jpg" alt="logo" width="120" height="25px;" style="padding:1px;height: 25px !important;max-height: 25px;">
            </td>
            <td style="border-left: 3px solid #000; border-right: 3px solid #000;text-align: center; font-weight: bold; font-size: 14px; width: 80px; max-width: 80px; color: #000;max-height: 30px;">000-3</td>
            <td style="text-align: right;font-size: 14px; font-weight: bold;color: #000;max-height: 30px;" colspan="4">'. $dadosboleto["linhadigital"].'</td>
        </tr>
        <tr>
            <td style="border-bottom: 2px solid #000; margin-top: 2px; height: 2pt;" colspan="6"></td>
        </tr>
    </table>

    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 2px;">
        <tr>
            <td style="border-left: 5px solid #000; padding-left: 5px;"><span class="ct">Cedente</span></td>
            <td style="width:180px;max-width:180px; border-left: 5px solid #000;height: 20px; padding-left: 5px;"><span class="ct">Vencimento</span></td>
        </tr>
        <tr>
            <td class="cp" style="border-bottom: 1px solid #000; border-left: 5px solid #000;"><span class="cp">'. ($dadosboleto["cedente"]) . " CNPJ: " . $dadosboleto["cpf_cnpj"].'</td>
            <td class="cp" style="width:180px;max-width:180px;border-bottom: 1px solid #000; border-left: 5px solid #000; text-align: right">'. $dadosboleto["data_vencimento"].'</td>
        </tr>
    </table>

    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 2px;">
        <tr>
            <td style="border-left: 5px solid #000; padding-left: 5px;"><span class="ct">Referencia</span></td>
            <td style="border-left: 5px solid #000; padding-left: 5px;"><span class="ct">Cliente</span></td>
            <td style="border-left: 5px solid #000; padding-left: 5px;"><span class="ct">Espécie Doc</span></td>
            <td style="border-left: 5px solid #000; padding-left: 5px;"><span class="ct">Aceite</span></td>
            <td style="border-left: 5px solid #000; padding-left: 5px;"><span class="ct">Data Process</span></td>
            <td style="width:180px;max-width:180px; border-left: 5px solid #000;height: 20px; padding-left: 5px;"><span class="ct">Valor Documento</span></td>
        </tr>
        <tr>
            <td class="cp" style="border-bottom: 1px solid #000; border-left: 5px solid #000;">'. $dadosboleto["referencia"].'</td>
            <td class="cp" style="border-bottom: 1px solid #000; border-left: 5px solid #000;">'. $dadosboleto["cod_mais_med"].'</td>
            <td class="cp" style="border-bottom: 1px solid #000; border-left: 5px solid #000;">DM</td>
            <td class="cp" style="border-bottom: 1px solid #000; border-left: 5px solid #000;">N</td>
            <td class="cp" style="border-bottom: 1px solid #000; border-left: 5px solid #000;">'. date("d/m/Y").'</td>
            <td class="cp" style="width:180px;max-width:180px;border-bottom: 1px solid #000; border-left: 5px solid #000; text-align: right">R$ '. $dadosboleto["valor_boleto"] .'</td>
        </tr>
    </table>

    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 2px;">
        <tr>
            <td style="border-left: 5px solid #000; padding-left: 5px;"><span class="ct">Sacado</span></td>
        </tr>
        <tr>
            <td class="cp" style="border-bottom: 1px solid #000; border-left: 5px solid #000;height: 20px;"><span class="cp">'. $dadosboleto["sacado"].'</span></td>
        </tr>
    </table>

    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td><span class="ct">Ficha de compensação</span></td>
        </tr>
        <tr>
            <td>
            '.fbarcode_pdf($dadosboleto["codigo_barras"]).'
            </td>
        </tr>
    </table>
</div>

<table style="width:205mm;margin-top: 30px;">
<thead>
    <tr>
        <th align="center" colspan="5" style="font-size: 18px; border-bottom: 1px solid #ccc;">Ralação de Colaboradores</th>
    </tr>
    <tr>
        <th style="font-size: 12px;border-bottom: 1px solid #ccc;"align="center">Matricula</th>
        <th style="font-size: 12px;border-bottom: 1px solid #ccc;"align="left"  >Nome Funcionario</th>
        <th style="font-size: 12px;border-bottom: 1px solid #ccc;"align="center">Referencia</th>
        <th style="font-size: 12px;border-bottom: 1px solid #ccc;"align="center">Valor</th>
    </tr>
   </thead>
   <tbody style="font-size: 12px;">
';

$queryfatu = faturamentos::find_by_sql("SELECT SQL_CACHE
                                                    associados.matricula,associados.nm_associado,
                                                    faturamentos.id as fatid,faturamentos.valor,faturamentos.referencia
                                                    FROM faturamentos
                                                    inner JOIN associados ON associados.matricula = faturamentos.matricula
                                                WHERE
                                                  faturamentos.convenios_id = '" . $FRM_convenio . "'
                                                  AND faturamentos.status = '0'
                                                  AND faturamentos.tipo_parcela='M'
                                                  AND faturamentos.referencia='" . $FRM_referencia . "'
                                                  AND associados.status='1'
                                                  ORDER BY faturamentos.matricula");

$listfat = new ArrayIterator($queryfatu);

 while ($listfat->valid()):
     $ref = new ActiveRecord\DateTime($listfat->current()->referencia);
     $html.= '<tr style="line-height: 35px;">
        <td align="center" style="font-size: 12px;border-bottom:1px dashed #e5e5e5">'.$listfat->current()->matricula.'</td>
        <td align="left"  style="font-size: 12px;border-bottom:1px dashed #e5e5e5">'.strtoupper($listfat->current()->nm_associado).'</td>
        <td align="center"  style="font-size: 12px;border-bottom:1px dashed #e5e5e5">'.tool::InvertDateTime(tool::LimpaString($FRM_referencia),0).'</td>
        <td align="center"  style="font-size: 12px;border-bottom:1px dashed #e5e5e5">'.number_format($listfat->current()->valor,2,",",".").'</td>
    </tr>';
     $update_venc = faturamentos::find($listfat->current()->fatid);
     $update_venc->dt_vencimento = tool::InvertDateTime(tool::LimpaString($FRM_vencimento),0);
     $update_venc->save();
     $listfat->next();
 endwhile;

$html.='</tbody></table></body></html>';

$mpdf = new mPDF('utf-8', 'A4-R',5,5,5,5,5,5);//new mPDF('c','A4','','',5,5,5,5,5,5);
$mpdf->SetDisplayMode('fullpage');
$mpdf->WriteHTML($html);
//echo $html;
if($FRM_action =='D'){
$mpdf->Output(date("dmY")."_bol.pdf",'D');
}elseif($FRM_action =='E'){
$mpdf->Output();
}else{
$mpdf->Output();
}
exit();
?>
