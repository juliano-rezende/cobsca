<?php

ob_start();
set_time_limit(0);
ignore_user_abort(1);

include("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

require_once("../../../library/mpdf60/mpdf.php");

$FRM_parcelas_id 		= isset( $_GET['ids']) 	? $_GET['ids']/* variavel com os ids das parcelas*/
												: tool::msg_erros("O Campo ids Obrigatorio faltando.");
$FRM_action         = isset( $_GET['action'])  ? $_GET['action']/* variavel com os ids das parcelas*/
                        : tool::msg_erros("O Campo action Obrigatorio faltando.");

// string contendo o id dos titulos a imprimir
$FRM_pa = explode(",",$FRM_parcelas_id);

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

$bol          = 0; // inica a quantidade de boletos por folha como zero
// loopa os titulos
foreach ($FRM_pa as $id_faturamento){


if($bol==0){
    $bd_top=" border-top:0.3mm dotted #000;";
}if($bol!=0){$bol=0;$bd_top="";}


$validar_parcela=faturamentos::find($id_faturamento);

//if($validar_parcela->tipo_parcela == "A"){continue;}

$nm = md5(microtime());
require("include/dados_carne.php");

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
';

$bol++;// conta os boletos

}

$html.='</body></html>';

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
