<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.0 Transitional//EN'>
<html>
<head>
<TITLE></TITLE>
<META http-equiv=Content-Type content=text/html charset="utf-8">
<meta name="Generator" content="Projeto BoletoPHP - www.boletophp.com.br - Licença GPL" />
<style type=text/css>
<!--.cp {  font: bold 10px Arial; color: black}
<!--.ti {  font: 9px Arial, Helvetica, sans-serif}
<!--.ld { font: bold 13px Arial; color: #000000}
<!--.ct { FONT: 9px "Arial Narrow"; COLOR: #000033}
<!--.cn { FONT: 9px "Arial Narrow"; COLOR: black }
<!--.bc { font: bold 18px Arial; color: #000000 }
<!--.ld2 { font: bold 10px Arial; color: #000000 }
.style3 {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 7px;
}

body{ margin:0; padding:0;}
.bc1 {font: bold 20px Arial; color: #000000 }
.img img{height: 60px;}
-->
</style>
</head>
<body>
<table  width="100%" height="380"  border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="171" height="22" valign="bottom" ><table width="134" border="0" cellspacing="0" style=" border-bottom:2px solid #000;">
      <tr>
        <td  height="22" align="right" valign="top" >
            <img src="assets/cobranca/carne/imagens/001.jpg" alt="logo" width="110" height="30" style="padding:1px;">
        </td>
        </tr>
    </table>
    </td>
    <td width="16" style="border-left:1px dashed #000;">&nbsp;</td>
    <td height="22" colspan="3" valign="top"><div align=center>
      <table width="100%" border="0" cellspacing="0" style=" border-bottom:2px solid #000;">
        <tr>
          <td width="95" height="22" valign="middle" align="center"><img src="assets/cobranca/carne/imagens/001.jpg" alt="logo" width="140" height="37" style="padding:1px;"></td>
          <td width="574" align="center" valign="bottom" style="color:#000; font:bold 18px arial;"></td>
        </tr>
    </table>
    </td>
  </tr>

   <tr>
    <td rowspan="7" valign="top">

    <table width="134"   height="100%" border="0" cellpadding="0" cellspacing="0" >

     <tr>
        <td width="117" height="12" bgcolor="#f1f1f1" class="ct" style=" border-left: #000000 1px solid; border-top: #000 1px solid; " >&nbsp;&nbsp;Vencimento</td>
      </tr>
      <tr >
        <td height="12" align="right" bgcolor="#f1f1f1" class="ld" style=" border-left: #000000 1px solid;"><?=$dadosboleto["data_vencimento"];?></td>
      </tr>
      <tr>
        <td height="12" style=" border-left: #000000 1px solid;  border-top: #000 1px solid;" ><span class="ct">Matricula</span><br />
        </td>
      </tr>
      <tr>
        <td height="12" align="right" class="cp" style=" border-left: #000000 1px solid;  " ><?=$dadosboleto["matricula"];?></td>
      </tr>
      <tr>
        <td height="12" bgcolor="#f1f1f1" style=" border-left: #000000 1px solid;  border-top: #000 1px solid;" ><span class="ct">&nbsp;(=) Valor</span><br />
        </td>
      </tr>
      <tr>
        <td height="12" align="right" class="ld" bgcolor="#f1f1f1" style="border-left: #000000 1px solid;">R$ <?=$dadosboleto["valor_boleto"]?></td>
      </tr>
      <tr>
        <td height="12" style=" border-left: #000000 1px solid;  border-top: #000 1px solid;"><span class="ct">&nbsp;Parcela nº</span><br />
        </td>
      </tr>
      <tr>
        <td height="13" align="right" class="cp" style=" border-left: #000000 1px solid;  "  ><span ><?=$dadosboleto["numero_documento"];?></span></td>
      </tr>
      <tr>
        <td height="12" style=" border-left: #000000 1px solid;  border-top: #000 1px solid;"><span class="ct">&nbsp;Referência</span><br />
        </td>
      </tr>
      <tr>
        <td height="13" align="center" class="cp" style=" border-left: #000000 1px solid;  "  ><span><?=$dadosboleto["referencia"]?></span></td>
      </tr>
      <tr>
        <td width="134" height="19" align="left"  style=" border-left: #000000 1px solid;border-top: #000 1px solid; " >
        <span class="ct">&nbsp;Associado</span><br>
        <span class="ct"><?=$dadosboleto["sacado"];?></span>
        </td>
      </tr>
      <tr>
        <td height="32" class="ct" style=" border-left: #000000 1px solid; border-botton: #000 1px solid; border-top: #000 1px solid;text-transform: capitalize;">
            <span class="cn" style="text-transform: uppercase;padding-left: 5px;">
            <?=utf8_encode($dadosboleto["cedente"]) ." ".$dadosboleto["cpf_cnpj"]; ?></span>
            <br>
            <span class="cn" ><?=$dadosboleto["endereco"] ." - ". $dadosboleto["cidade_uf"];?></span>
        </td>
      </tr>
        <tr>
            <td height="32" class="ct" style=" border-left: #000000 1px solid; border-botton: #000 1px solid; border-top: #000 1px solid;text-transform: capitalize;">
                <span class="cp">RECIBO DO PAGADOR</span>
                <span class="ct">Autenticar no verso</span>
            </td>
        </tr>
    </table>
    </td>
    <td height="36" rowspan="7" style="border-left:1px dashed #000;" >&nbsp;</td>
    <td width="779" height="22" ><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" >
      <tr>
        <td class="ct" >&nbsp;&nbsp;Local de Pagamento&nbsp;&nbsp;</td>
      </tr>
      <tr>
        <td height="18" style="text-transform:uppercase;"><span class="ti"><?=strtolower($dadosboleto["local_pgto"]); ?></span></td>
      </tr>
    </table></td>
    <td width="138" rowspan="6" ><table width="100%"   height="100%" border="0" cellpadding="0" cellspacing="0" >
      <tr >
        <td width="117" height="12" bgcolor="#f1f1f1" class="ct" style=" border-left: #000000 1px solid;  " >&nbsp;&nbsp;Vencimento</td>
      </tr>
      <tr >
        <td height="13" align="right" bgcolor="#f1f1f1" class="ld" style=" border-left: #000000 1px solid;">
            <?=$dadosboleto["data_vencimento"]?>
        </td>
      </tr>
      <tr>
        <td height="12" style=" border-left: #000000 1px solid;  border-top: #000 1px solid;" ><span class="ct"
            >&nbsp;&nbsp;Matricula</span><br />
        </td>
      </tr>
      <tr>
        <td height="13" align="center" class="cp" style=" border-left: #000000 1px solid;  " ><?=$dadosboleto["matricula"]; ?></td>
      </tr>
      <tr>
        <td height="12" style=" border-left: #000000 1px solid;  border-top: #000 1px solid;"><span class="ct">   Parcela nº</span><br />
        </td>
      </tr>
      <tr>
        <td height="13" align="right" class="cp" style=" border-left: #000000 1px solid;  "  ><span ><?=$dadosboleto["numero_documento"];?></span></td>
      </tr>
      <tr>
        <td height="12"style=" border-left: #000000 1px solid;  border-top: #000 1px solid;" ><span class="ct">&nbsp;(=) Valor Documento</span><br />
        </td>
      </tr>
      <tr>
        <td height="13" align="right" class="ld" style="border-left: #000000 1px solid; " > R$ <?= $dadosboleto["valor_boleto"]?></td>
      </tr>
      <tr>
        <td height="25" valign="top" style=" border-left: #000000 1px solid;  border-top: #000 1px solid;"><span class="ct">&nbsp;(-) Outras Deduc/Abat</span><br />
        </td>
      </tr>
      <tr>
        <td height="25" valign="top" class="ct" style=" border-left: #000000 1px solid;  border-top: #000 1px solid;"><span class="ct">&nbsp;(-) Descontos</span><br />
        </td>
      </tr>
      <tr>
        <td height="25" valign="top" class="ct" style=" border-left: #000000 1px solid;  border-top: #000 1px solid;"><span >&nbsp;(+) Juros/Multa<br />
        </span></td>
      </tr>
      <tr>
        <td height="25" valign="top" class="ct"style=" border-left: #000000 1px solid;  border-top: #000 1px solid;"><span >&nbsp;(+)&nbsp;Outros Acrec<br />
        </span></td>
      </tr>
      <tr>
        <td height="25" valign="top" class="ct" style=" border-left: #000000 1px solid;  border-top: #000 1px solid;" ><span >&nbsp;(=)&nbsp;Valor Cobrado<br />
        </span></td>
      </tr>
    </table></td>
  <tr>
    <td height="1" ><table  width="100%" align="left" cellpadding="0" cellspacing="0" style="border-top: #ccc 1px solid;">
      <tr>
        <td width="462" height="18" class="ct">&nbsp;&nbsp;Beneficiario</td>
      </tr>
      <tr>
        <td height="15" class="cp">&nbsp;&nbsp;<span ><?=utf8_encode($dadosboleto["cedente"]) ." CNPJ: ".$dadosboleto["cpf_cnpj"]; ?></span></td>
      </tr>
        <tr>
        <td width="462" height="18" class="ct">&nbsp;Endereço Beneficiario</td>
      </tr>
      <tr>
        <td height="15" class="cp">&nbsp;&nbsp;<span ><?=$dadosboleto["endereco"] ." - ". $dadosboleto["cidade_uf"];?></span></td>
      </tr>
    </table></td>
  <tr>
    <td height="1" ><table  width="100%" align="left"  cellpadding="0" cellspacing="0" style="border-top: #ccc 1px solid;">
      <tr >
        <td width="100" class="ct" style="  border-right: #ccc 1px solid;" >&nbsp;&nbsp;Dt Emissao</td>
        <td width="150" class="ct" style="  border-right: #ccc 1px solid;">&nbsp;&nbsp;Parcela n&ordm;</td>
        <td width="200" class="ct" style="  border-right: #ccc 1px solid;">&nbsp;&nbsp;Esp.Documento</td>
        <td class="ct" >&nbsp;&nbsp;Dt.Processamento</td>
      </tr>
      <tr>
        <td height="15" align="left" class="cp" style=" border-botton:#ccc 1px solid; border-right: #ccc 1px solid;"><span >&nbsp;<?=$dadosboleto["data_documento"]?></span></td>
        <td height="15" align="left" class="cp" style=" border-botton:#ccc 1px solid; border-right: #ccc 1px solid;"><span >&nbsp;<?=$dadosboleto["numero_documento"]?></span></td>
        <td height="15" align="left" class="cp" style=" border-botton:#ccc 1px solid; border-right: #ccc 1px solid;"><span >&nbsp;<?=$dadosboleto["especie_doc"]?></span></td>
        <td height="15" align="left" class="cp" style=" border-botton:#ccc 1px solid; "><span >&nbsp;<?=$dadosboleto["data_processamento"]?></span></td>
      </tr>
    </table>

    </td>
  <tr>
    <td height="2" >
    <table  width="100%" align="left"  cellpadding="0" cellspacing="0" style="border-top: #ccc 1px solid;">
      <tr >
        <td width="450" class="ct" style="  border-right: #ccc 1px solid;">&nbsp;&nbsp;Tipo</td>
        <td class="ct" >&nbsp;&nbsp;Referência</td>
      </tr>
      <tr>
        <td height="15" align="left" class="cp" style=" border-botton:#ccc 1px solid; border-right: #ccc 1px solid;"><span >&nbsp;<?=$dadosboleto["carteira_descricao"]?></span></td>
        <td height="15" align="center" class="cp" style=" border-botton:#ccc 1px solid; border-right: #ccc 1px solid;"><span >&nbsp;<?=$dadosboleto["referencia"]?></span></td>
      </tr>
    </table>

    </td>
  <tr>
    <td height="60" valign="top" style=" border-top:#000 2px solid; ">
    <span class="cn" style="padding: 0; margin: 0;" >Texto de responsabilidade do cedente</span> <br>
    <span class="cn" style="text-transform:uppercase; padding: 0px 5px; margin-top: 3px;"> <?=$dadosboleto["instrucoes1"]; ?>  </span> <br>
    <span class="cn" style="text-transform:uppercase; padding: 0px 5px; margin-top: 5px;"> <?=$dadosboleto["instrucoes2"]; ?> </span> <br>
    <span class="cn" style="text-transform:uppercase; padding: 0px 5px; margin-top: 5px;"> <?=$dadosboleto["inst_adcional"]; ?></span>
    </td>
  <tr>
    <td height="36" valign="top" style=" border-top:#000 2px solid; text-transform:uppercase;" >
     <span class="ct">Sacado</span><br>
     <span class="cn"> <?=$dadosboleto["sacado"];?></span> <br>

     <span class="cn" style="text-transform:uppercase;">
     <?=$dadosboleto["endereco1"];?></span>
     <span class="cn" style="text-transform:uppercase; ">
     <?=$dadosboleto["endereco2"]?></span>
     <span class="ct">Avalista</span>
      </td>
  <tr>
    <td height="70" class="img"  style=" max-height:70px; border-top:#000 2px solid; padding-top:1px;"  >

    </td>
    <td width="138" align="center" class="cn" style=" border-top:#000 2px solid; "> Ficha de compensa&ccedil;&atilde;o<br>
    Autentica&ccedil;&atilde;o no verso </td>
</table>
</body></html>
