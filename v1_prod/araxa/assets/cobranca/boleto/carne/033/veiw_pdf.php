<?php

ob_start();
set_time_limit(0);
ignore_user_abort(1);

include("../../../../../conexao.php");
$cfg->set_model_directory('../../../../../models/');

require_once("../../../../../library/mpdf60/mpdf.php");


$FRM_parcelas_id 		= isset( $_GET['ids']) 	? $_GET['ids']/* variavel com os ids das parcelas*/
												: tool::msg_erros("O Campo ids Obrigatorio faltando.");

$FRM_action         = isset( $_GET['action'])  ? $_GET['action']/* variavel com os ids das parcelas*/
                        : tool::msg_erros("O Campo action Obrigatorio faltando.");

// string contendo o id dos titulos a imprimir
$FRM_pa = explode(",",$FRM_parcelas_id);



require_once("include/funcoes_santander.php");// carrega as funções do boleto

$html ='';
$html .='
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<style>
body{ font-family:"DejaVu Sans Condensed";}

<!--.cp {font-size:10px; font-weight:bold; color:#000; padding-left:2px;}
<!--.ti {font-size:9px;}
<!--.ld {font-size:12px;font-weight:bold;}
<!--.ct {font-size:9px;padding-left:3px;text-transform:capitalize;}
<!--.cn {font-size:8px;color:#000;text-transform:capitalize;}
<!--.bc {font-size:18px;color:#000;}
<!--.ld2{font-size:10px;color:#000;}

.bc1 {font: bold 14px Arial; color: #000000; }
.img img{height: 50px;}
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

if($validar_parcela->tipo_parcela == "A"){continue;}


require("include/dados_boleto.php");


header ('Content-type: text/html; charset=UTF-8');
$html .= '
<div id="print_c" style=" height:87mm; max-height:87mm;width:100%; overflow:auto; padding:0;padding-top:7px;  '.$bd_top.' padding-bottom:7px; margin-bottom:0 auto;background:#fff; border-bottom:0.3mm dotted #000; ">

<table width="100%" cellpadding="0" cellspacing="0">
  <tbody>
  <tr>
    <td width="17%" height="29" align="left" valign="bottom" style="border-right:1px dotted #000;"><table width="92%" border="0" cellspacing="0" style="border-bottom:0.2mm solid #000;">
      <tr>
        <td width="95" height="30" align="right" valign="top" >
        <span class="cp">RECIBO DO PAGADOR</span><br>
        <span class="ct">Autenticar no verso</span>
        </td>
      </tr>
    </table></td>
    <td colspan="2" align="right" valign="bottom">
    <table width="99%" height="100%" border="0" cellspacing="0" style=" border-bottom:0.2mm solid #000;">
      <tr>
        <td width="95" height="22" valign="middle" align="center">
        <img src="../../imagens/033.jpg" alt="Santander" width="91" height="22" style="padding:1px;">
        </td>
        <td width="72" align="center" valign="bottom" class="bc1" style="border-left:0.2mm solid #000; border-right:0.2mm solid #000;">'.$dadosboleto["codigo_banco_com_dv"].'</td>
        <td width="574" align="center" valign="bottom" class="bc1" >'.$dadosboleto["linha_digitavel"].'</td>
      </tr>
    </table>
    </td>
  </tr>
  <tr>
    <td width="120" rowspan="2" align="left" valign="top" style="border-right:1px dotted #000;">
    <table width="90%"  border="0" cellpadding="0" cellspacing="0" style="max-width:90%;" >
      <tr>
        <td width="134" bgcolor="#f1f1f1" class="ct" style=" border-left: #000000 0.1mm solid; " >Vencimento</td>
      </tr>
      <tr >
        <td height="25" align="right" bgcolor="#f1f1f1" class="ld" style=" border-left: #000000 0.1mm solid; padding-right:5px; ">'.$dadosboleto["data_vencimento"].'</td>
      </tr>
      <tr>
        <td class="ct" style=" border-left: #000000 0.1mm solid;  border-top: #000 0.1mm solid;" >Agencia/Cod.Beneficiário</td>
      </tr>
      <tr>
        <td height="20" align="right" valign="bottom" class="cp" style=" border-left: #000000 0.1mm solid;padding-right:5px; " >'.$dadosboleto["agencia"]."/".$dadosboleto["codigo_cliente"].'</td>
      </tr>
      <tr>
        <td bgcolor="#f1f1f1" class="ct" style=" border-left: #000000 1px solid;  border-top: #000 0.1mm solid;" >(=) Valor Documento</td>
      </tr>
      <tr>
        <td height="25" align="right" bgcolor="#f1f1f1" class="ld" style="border-left: #000000 0.1mm solid; padding-right:5px;" >'.$dadosboleto["valor_boleto"].'</td>
      </tr>
      <tr>
        <td height="18" valign="top" class="ct" style=" border-left: #000000 0.1mm solid;  border-top: #000 0.1mm solid;">(-)Descontos</td>
      </tr>
      <tr>
        <td height="18" valign="top" class="ct" style=" border-left: #000000 0.1mm solid;  border-top: #000 0.1mm solid;">(+) Mora/Multa</td>
      </tr>
      <tr>
        <td height="18" valign="top" class="ct" style=" border-left: #000000 0.1mm solid;  border-top: #000 0.1mm solid;">(+) Outros Acrec</td>
      <tr>
        <td height="18" valign="top" class="ct" style=" border-left: #000000 0.1mm solid;  border-top: #000 0.1mm solid;">(=) Valor Cobrado</td>
      </tr>
      <tr>
        <td class="ct" style=" border-left: #000000 0.1mm solid;  border-top: #000 0.1mm solid;">Nosso Numero</td>
      </tr>
      <tr>
        <td height="20" align="right" class="cp" style=" border-left: #000000 0.1mm solid;padding-right:5px;"  >'.tool::MascaraCampos("############-#",$dadosboleto["nosso_numero"]).'</td>
      </tr>
      <tr>
        <td class="ct" style=" border-left: #000000 0.1mm solid;  border-top: #000 0.1mm solid;">N&deg; Documento</td>
      </tr>
      <tr>
        <td height="20" align="center" class="cp" style=" border-left: #000000 0.1mm solid;  "  >'.$dadosboleto["numero_documento"].'</td>
      </tr>
      <tr>
        <td width="134"  align="left" valign="top" class="cn"  style=" border-left: #000000 0.1mm solid;border-top: #000 0.1mm solid; " >
        Pagador<br>
         '.$dadosboleto["sacado"].'
         </td>
      </tr>
      <tr>
        <td height="50" valign="top" class="cn" style=" border-left: #000000 0.1mm solid; border-botton: #000 0.1mm solid; border-top: #000 0.1mm solid;">
          '.utf8_encode($dadosboleto["cedente"]) ." ".$dadosboleto["cpf_cnpj"].'<br>
          '.$dadosboleto["endereco"] ." - ". $dadosboleto["cidade_uf"].'
        </td>
      </tr>
    </table></td>
    <td height="252" colspan="2" align="left" valign="top">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:0; padding:0;">
      <tr>
        <td width="87%" align="right" >
        <table width="99%"  border="0" cellpadding="0" cellspacing="0" style="padding:0; margin:0;" >
          <tr>
            <td width="134" align="left" class="ct"  >Local de pagamento</td>
          </tr>
          <tr>
            <td height="18" align="left"  class="cp" style="border-bottom:0.1mm solid #000;text-transform: uppercase;vertical-align: text-middle;" >'.strtolower($dadosboleto["local_pgto"]).'</td>
          </tr>
        </table>
        </td>
        <td width="100" rowspan="7" align="right" valign="top"><table width="100%"  border="0" cellpadding="0" cellspacing="0" style="max-width:90%;" >
          <tr>
            <td width="134" align="left" bgcolor="#f1f1f1" class="ct" style=" border-left: #000000 0.1mm solid; " >Vencimento</td>
          </tr>
          <tr >
            <td height="25" align="right" bgcolor="#f1f1f1" class="ld" style=" border-left: #000000 0.1mm solid; padding-right:5px; " >'.$dadosboleto["data_vencimento"].'</td>
          </tr>
          <tr>
            <td align="left" class="ct" style=" border-left: #000000 0.1mm solid;  border-top: #000 0.1mm solid;" >Agencia/Cod.Beneficiário</td>
          </tr>
          <tr>
            <td height="25" align="right" valign="bottom" class="cp" style=" border-left: #000000 0.1mm solid;padding-right:5px; " >'.$dadosboleto["agencia"]."/".$dadosboleto["codigo_cliente"].'</td>
          </tr>
          <tr >
            <td align="left" class="ct" style=" border-left: #000000 0.1mm solid;  border-top: #000 0.1mm solid;">Nosso Numero</td>
          </tr>
          <tr>
            <td height="25" align="right" class="cp" style=" border-left: #000000 0.1mm solid;padding-right:5px;"  >'.tool::MascaraCampos("############-#",$dadosboleto["nosso_numero"]).'</td>
          </tr>
          <tr>
            <td align="left" bgcolor="#f1f1f1" class="ct" style=" border-left: #000000 1px solid;  border-top: #000 0.1mm solid;">(=) Valor Documento</td>
          </tr>
          <tr>
            <td height="25" align="right" bgcolor="#f1f1f1" class="ld" style="border-left: #000000 0.1mm solid; padding-right:5px;">'.$dadosboleto["valor_boleto"].'</td>
          </tr>
          <tr>
            <td align="left" height="30" valign="top" class="ct" style=" border-left: #000000 0.1mm solid;  border-top: #000 0.1mm solid;">(-)Outras Deduc/Abat</td>
          </tr>
          <tr >
            <td align="left" height="30" valign="top" class="ct" style=" border-left: #000000 0.1mm solid;  border-top: #000 0.1mm solid;">(+) Mora/Multa</td>
          </tr>
          <tr>
            <td align="left" height="30" valign="top" class="ct" style=" border-left: #000000 0.1mm solid;  border-top: #000 0.1mm solid;">(+) Outros Acrec</td>
          <tr>
            <td align="left"height="40" valign="top" class="ct" style=" border-left: #000000 0.1mm solid;  border-top: #000 0.1mm solid;">(=) Valor Cobrado</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align="right">
        <table width="99%"  border="0" cellpadding="0" cellspacing="0" style="padding:0; margin:0;" >
          <tr>
            <td width="134" align="left" class="ct" >Beneficiario</td>
          </tr>
          <tr>
            <td height="18" align="left" valign="bottom" class="cp" style="padding-bottom:3px;"> 
           '.utf8_encode($dadosboleto["cedente"]) ." CNPJ: ".$dadosboleto["cpf_cnpj"].'
            </td>
          </tr>
        </table>
        </td>
        </tr>
      <tr>
        <td align="right">

        <table width="99%"  border="0" cellpadding="0" cellspacing="0" style="padding:0; margin:0;" >
          <tr>
            <td width="134" align="left" class="ct" >Endereço Beneficiario</td>
          </tr>
          <tr>
            <td height="18" align="left" valign="bottom" class="cp" style="padding-bottom:3px;"> 
           '.$dadosboleto["endereco"] ." - ". $dadosboleto["cidade_uf"].'
            </td>
          </tr>
        </table>

        </td>
        </tr>
      <tr>
        <td align="right" valign="top">

        <table width="99%"  border="0" cellpadding="0" cellspacing="0" style="padding:0;" >
          <tr>
            <td width="117" align="left" class="ct"  style="border-top:#ccc 0.1mm solid; border-right:#ccc 0.1mm solid; ">Dt Emissao</td>
            <td width="197" align="left" class="ct"  style="border-top:#ccc 0.1mm solid; border-right:#ccc 0.1mm solid;">N&ordm; Documento</td>
            <td width="176" align="left" class="ct"  style="border-top:#ccc 0.1mm solid; border-right:#ccc 0.1mm solid;">Esp.Doc</td>
            <td width="137" align="left" class="ct"  style="border-top:#ccc 0.1mm solid; border-right:#ccc 0.1mm solid;">Aceite</td>
            <td width="169" align="left" class="ct"  style="border-top:#ccc 0.1mm solid; ">Dt.Processamento</td>
          </tr>
          <tr>
            <td align="left" valign="bottom" class="cp" style="border-right:#ccc 0.1mm solid; ">'.$dadosboleto["data_documento"].'</td>
            <td align="left" valign="bottom" class="cp" style="border-right:#ccc 0.1mm solid; " >'.$dadosboleto["numero_documento"].'</td>
            <td align="left" valign="bottom" class="cp" style="border-right:#ccc 0.1mm solid; " >'.$dadosboleto["especie_doc"].'</td>
            <td align="left" valign="bottom" class="cp" style="border-right:#ccc 0.1mm solid; " >'.$dadosboleto["aceite"].'</td>
            <td align="left" valign="bottom" class="cp" style="border-right:#ccc 0.1mm solid; " >'.$dadosboleto["data_processamento"].'</td>
          </tr>
        </table>
        </td>
        </tr>
      <tr>
        <td align="right" valign="top"><table width="99%"  border="0" cellpadding="0" cellspacing="0" style="padding:0;" >
          <tr>
            <td align="left" class="ct"  style="width:105px;border-top:#ccc 1px solid; border-right:#ccc 1px solid;"bgcolor="#f1f1f1">Uso do banco</td>
            <td align="left" class="ct"  style="width:240px;border-top:#ccc 1px solid; border-right:#ccc 1px solid;">Carteira</td>
            <td align="left" class="ct"  style="width:176px;border-top:#ccc 1px solid; border-right:#ccc 1px solid;">Esp.Moeda</td>
            <td align="left" class="ct"  style="width:137px;border-top:#ccc 1px solid; border-right:#ccc 1px solid;">Quant Moeda</td>
            <td align="left" class="ct"   style="width:169px;border-top:#ccc 1px solid; ">Valor Doc</td>
          </tr>
          <tr>
            <td height="20" align="left" valign="bottom" class="cp" style="border-right:#ccc 1px solid;border-bottom:#ccc 1px solid;   "bgcolor="#f1f1f1" >&nbsp;</td>
            <td height="20" align="left" valign="bottom" class="cp" style="border-right:#ccc 1px solid;border-bottom:#ccc 1px solid; text-transform: uppercase; " >'.$dadosboleto["carteira_descricao"].'</td>
            <td height="20" align="left" valign="bottom" class="cp" style="border-right:#ccc 1px solid;border-bottom:#ccc 1px solid; " >'.$dadosboleto["especie"].'</td>
            <td height="20" align="left" valign="bottom" class="cp" style="border-right:#ccc 1px solid;border-bottom:#ccc 1px solid; " >'.$dadosboleto["quantidade"].'</td>
            <td height="20" align="left" valign="bottom" class="cp" style="border-right:#ccc 1px solid;border-bottom:#ccc 1px solid; " >'.$dadosboleto["valor_unitario"].'</td>
          </tr>
        </table></td>
        </tr>
      <tr>
        <td align="right" valign="top" class="ct" >
        <table width="99%"  border="0" cellpadding="0" cellspacing="0" style="padding:0; margin:0;" >
          <tr>
            <td width="134" height="18" align="left" valign="bottom" class="ct" style="border-bottom:0.1mm solid #000; padding-top:3px; padding-bottom:3px;" >Intruções(Texto de responsabilidade do benefíciario) <br>
              '.$dadosboleto["instrucoes1"].'<br>
              '.$dadosboleto["instrucoes2"].'<br>
              '.$dadosboleto["inst_adcional"].'</td>
          </tr>
        </table></td>
        </tr>
      <tr>
        <td height="60" align="right" valign="top" class="ct" >
        <table width="99%"  border="0" cellpadding="0" cellspacing="0" style="padding:0; margin:0;" >
          <tr>
            <td width="134" height="18" align="left" valign="bottom" class="ct" style="padding-top:3px; padding-bottom:3px;">
            '.$dadosboleto["sacado"].'<br>
            '.$dadosboleto["endereco1"].'<br>
            '.$dadosboleto["endereco2"].'<br>
              </td>
          </tr>
        </table>
    </td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td width="65%" class="img" align="left" valign="top" style=" border-top:#000 0.2mm solid; padding-top:2px; padding-left:10px; ">

'.fbarcode_pdf($dadosboleto["codigo_barras"]).'

    </td>
    <td width="18%" align="center" valign="top" class="ct" style=" border-top:#000 0.2mm solid; ">Ficha de compensação<br>
      Autenticação no verso</td>
  </tr>
  </tbody>
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



