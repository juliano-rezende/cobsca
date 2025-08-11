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
.divider {
      border-bottom: 1px dashed #000;height: 1px; margin-top: 20px; border-top: 0;
    }
body{ margin:0; padding:0;}
.bc1 {font: bold 20px Arial; color: #000000 }
.img img{height: 60px;}
-->
</style> 

<table  width="100%" height="380"  border="0" cellpadding="0" cellspacing="0" style="padding: 0; margin: 0;" >
  <tr>
    <td width="171" height="22" valign="bottom" ><table width="134" border="0" cellspacing="0" style=" border-bottom:2px solid #000;">
      <tr>
        <td width="95" height="22" align="right" valign="top" >
        <span class="cp">RECIBO DO PAGADOR</span>
        <span class="ct">Autenticar no verso</span>
        </td>
        </tr>
    </table></td>
    <td width="16" style="border-left:1px dashed #000;">&nbsp;</td>
    <td height="22" colspan="3" valign="top"><div align=center>
      <table width="100%" border="0" cellspacing="0" style=" border-bottom:2px solid #000;">
        <tr>
          <td width="95" height="22" valign="middle" align="center"><img src="assets/cobranca/boleto/imagens/756.jpg" alt="logosicoob" width="91" height="22" style="padding:1px;"></td>
          <td width="72" valign="bottom"  style="color:#000; font:bold 18px arial;border-left:1px solid #000000; border-right:1px solid #000000; text-align:center;"><?php echo $dadosboleto["codigo_banco_com_dv"]?></td>
          <td width="574" align="center" valign="bottom" style="color:#000; font:bold 18px arial;"><?php echo $dadosboleto["linha_digitavel"]?></td>
        </tr>
    </table></td>
  </tr>

  <tr>
    <td rowspan="7" valign="top"><table width="134"   height="100%" border="0" cellpadding="0" cellspacing="0" >

     <tr>
        <td width="117" height="12" bgcolor="#f1f1f1" class="ct" style=" border-left: #000000 1px solid; border-top: #000 1px solid; " >&nbsp;&nbsp;Vencimento</td>
      </tr>
      <tr >
        <td height="12" align="right" bgcolor="#f1f1f1" class="ld" style=" border-left: #000000 1px solid;  " "   >
        <?php echo ($data_venc != "") ? $dadosboleto["data_vencimento"] : "Contra Apresenta&ccedil;&atilde;o" ?>&nbsp;</td>
      </tr>
      <tr>
        <td height="12" style=" border-left: #000000 1px solid;  border-top: #000 1px solid;" ><span class="ct" >&nbsp;&nbsp;Agencia/Cod.Beneficiário</span><br />
        </td>
      </tr>
      <tr>
        <td height="12" align="right" class="cp" style=" border-left: #000000 1px solid;  " ><?php echo $dadosboleto["agencia_codigo"]?></td>
      </tr>
      <tr>
        <td height="12" bgcolor="#f1f1f1" style=" border-left: #000000 1px solid;  border-top: #000 1px solid;" ><span class="ct">&nbsp;(=) Valor Documento</span><br />
        </td>
      </tr>
      <tr>
        <td height="12" align="right" class="ld" bgcolor="#f1f1f1" style="border-left: #000000 1px solid;" >R$ <?php echo $dadosboleto["valor_boleto"]?></td>
      </tr>
      <tr>
        <td height="18" valign="top" style=" border-left: #000000 1px solid;  border-top: #000 1px solid;"><span class="ct">&nbsp;(-) Descontos<br />
          </span></td>
      </tr>
      <tr>
        <td height="18" valign="top" style=" border-left: #000000 1px solid;  border-top: #000 1px solid;"><span class="ct">&nbsp;(+) Mora/Multa<br />
        </span></td>
      </tr>
      <tr>
        <td height="18" valign="top" style=" border-left: #000000 1px solid;  border-top: #000 1px solid;"><span class="ct">&nbsp;(+) Outros Acrec<br />
        </span>
        </td>
      <tr>
        <td height="18" valign="top" style=" border-left: #000000 1px solid;  border-top: #000 1px solid;"><span class="ct">&nbsp;(=) Valor Cobrado<br />
        </span></td>
      </tr>
        <tr>
        <td height="12" style=" border-left: #000000 1px solid;  border-top: #000 1px solid;"><span class="ct">&nbsp;&nbsp;Nosso Numero</span><br />
        </td>
      </tr>
      <tr>
        <td height="13" align="right" class="cp" style=" border-left: #000000 1px solid;  "  ><span ><?php echo $dadosboleto["nosso_numero"]?></span></td>
      </tr>
      <tr>
        <td height="12" style=" border-left: #000000 1px solid;  border-top: #000 1px solid;"><span class="ct">&nbsp;&nbsp;N&deg; Documento</span><br />
        </td>
      </tr>
      <tr>
        <td height="13" align="center" class="cp" style=" border-left: #000000 1px solid;  "  ><span ><?php echo $dadosboleto["numero_documento"]?></span></td>
      </tr>
      <tr>
        <td width="134" height="19" align="left"  style=" border-left: #000000 1px solid;border-top: #000 1px solid; " >
        <span class="ct">&nbsp;Pagador</span><br>
        <span class="ct"><?php echo $dadosboleto["sacado"];?></span>
        </td>
      </tr>
      <tr>
        <td height="32" class="ct" style=" border-left: #000000 1px solid; border-botton: #000 1px solid; border-top: #000 1px solid;">
          	<span class="cn" style="text-transform: uppercase;padding-left: 5px;">
            <?php echo utf8_encode($dadosboleto["cedente"]) ." ".$dadosboleto["cpf_cnpj"]; ?></span>
          	<br>
            <span class="cn" ><?php echo $dadosboleto["endereco"] ." - ". $dadosboleto["cidade_uf"];?></span>

</td>
      </tr>
    </table></td>
    <td height="36" rowspan="7" style="border-left:1px dashed #000;" >&nbsp;</td>
    <td width="779" height="22" ><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" >
      <tr>
        <td class="ct" >&nbsp;&nbsp;Local de Pagamento&nbsp;&nbsp;</td>
      </tr>
      <tr>
        <td height="18" style="text-transform:uppercase;"><span class="ti"><?php echo strtolower($dadosboleto["local_pgto"]); ?></span></td>
      </tr>
    </table>
    </td>
    <td width="138" rowspan="6" >
    <table width="100%"   height="100%" border="0" cellpadding="0" cellspacing="0" >
      <tr >
        <td width="117" height="12" bgcolor="#f1f1f1" class="ct" style=" border-left: #000000 1px solid;  " >&nbsp;&nbsp;Vencimento</td>
      </tr>
      <tr >
        <td height="13" align="right" bgcolor="#f1f1f1" class="ld" style=" border-left: #000000 1px solid;  "   ><?php echo ($data_venc != "") ? $dadosboleto["data_vencimento"] : "Contra Apresenta&ccedil;&atilde;o" ?>&nbsp;</td>
      </tr>
      <tr>
        <td height="12" style=" border-left: #000000 1px solid;  border-top: #000 1px solid;" ><span class="ct" >&nbsp;&nbsp;Agencia/Cod.Beneficiário</span><br />
        </td>
      </tr>
      <tr>
        <td height="13" align="center" class="cp" style=" border-left: #000000 1px solid;  " ><?php echo $dadosboleto["agencia_codigo"]?></td>
      </tr>
      <tr>
        <td height="12" style=" border-left: #000000 1px solid;  border-top: #000 1px solid;"><span class="ct">&nbsp;&nbsp;Nosso Numero</span><br />
        </td>
      </tr>
      <tr>
        <td height="13" align="right" class="cp" style=" border-left: #000000 1px solid;  "  ><span ><?php echo $dadosboleto["nosso_numero"]?></span></td>
      </tr>
      <tr>
        <td height="12" bgcolor="#f1f1f1" style=" border-left: #000000 1px solid;  border-top: #000 1px solid;" ><span class="ct">&nbsp;(=) Valor Documento</span><br />
        </td>
      </tr>
      <tr>
        <td height="13" align="right" class="ld" bgcolor="#f1f1f1" style="border-left: #000000 1px solid;" >R$ <?php echo $dadosboleto["valor_boleto"]?></td>
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
        <td height="25" valign="top" class="ct" style=" border-left: #000000 1px solid;  border-top: #000 1px solid;"><span >&nbsp;(+) Mora/Multa<br />
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
    <td height="1" >
      <table  width="100%" align="left" cellpadding="0" cellspacing="0" style="border-top: #ccc 1px solid;">
      <tr>
        <td width="462" height="18" class="ct">&nbsp;&nbsp;Beneficiario</td>
      </tr>
      <tr>
        <td height="15" class="cp">&nbsp;&nbsp;<span ><?php echo utf8_encode($dadosboleto["cedente"]) ." CNPJ: ".$dadosboleto["cpf_cnpj"]; ?></span></td>
      </tr>
        <tr>
        <td width="462" height="18" class="ct">&nbsp;Endereço Beneficiario</td>
      </tr>
      <tr>
        <td height="15" class="cp">&nbsp;&nbsp;<span ><?php echo $dadosboleto["endereco"] ." - ". $dadosboleto["cidade_uf"];?></span></td>
      </tr>
    </table>
  </td>
  <tr>
    <td height="1" ><table  width="100%" align="left"  cellpadding="0" cellspacing="0" style="border-top: #ccc 1px solid;">
      <tr >
        <td width="100" class="ct" style="  border-right: #ccc 1px solid;" >&nbsp;&nbsp;Dt Emissao</td>
        <td width="150" class="ct" style="  border-right: #ccc 1px solid;">&nbsp;&nbsp;N&ordm; Documento</td>
        <td width="100" class="ct" style="  border-right: #ccc 1px solid;">&nbsp;&nbsp;Esp.Doc</td>
        <td width="100" class="ct" style="  border-right: #ccc 1px solid;">&nbsp;&nbsp;Aceite</td>
        <td class="ct" >&nbsp;&nbsp;Dt.Processamento</td>
      </tr>
      <tr>
        <td height="15" align="left" class="cp" style=" border-botton:#ccc 1px solid; border-right: #ccc 1px solid;"><span >&nbsp;<?php echo $dadosboleto["data_documento"]?></span></td>
        <td height="15" align="left" class="cp" style=" border-botton:#ccc 1px solid; border-right: #ccc 1px solid;"><span >&nbsp;<?php echo $dadosboleto["numero_documento"]?></span></td>
        <td height="15" align="left" class="cp" style=" border-botton:#ccc 1px solid; border-right: #ccc 1px solid;"><span >&nbsp;<?php echo $dadosboleto["especie_doc"]?></span></td>
        <td height="15" align="left" class="cp" style=" border-botton:#ccc 1px solid; border-right: #ccc 1px solid;"><span >&nbsp;<?php echo $dadosboleto["aceite"]?></span></td>
        <td height="15" align="left" class="cp" style=" border-botton:#ccc 1px solid; "><span >&nbsp;<?php echo $dadosboleto["data_processamento"]?></span></td>
      </tr>
    </table>

    </td>
  </tr>
  <tr>
    <td height="2" >
    <table  width="100%" align="left"  cellpadding="0" cellspacing="0" style="border-top: #ccc 1px solid;">
      <tr >
        <td width="100" bgcolor="#f1f1f1" class="ct" style="  border-right: #ccc 1px solid;" >&nbsp;&nbsp;Uso do banco</td>
        <td width="150" class="ct" style="  border-right: #ccc 1px solid;">&nbsp;&nbsp;Carteira</td>
        <td width="100" class="ct" style="  border-right: #ccc 1px solid;">&nbsp;&nbsp;Esp.Moeda</td>
        <td width="100" class="ct" style="  border-right: #ccc 1px solid;">&nbsp;&nbsp;Quant Moeda</td>
        <td class="ct" >&nbsp;&nbsp;Valor Doc</td>
      </tr>
      <tr>
        <td height="15" align="left" bgcolor="#f1f1f1" class="cp" style=" border-botton:#ccc 1px solid; border-right: #ccc 1px solid;"><span >&nbsp;</span></td>
        <td height="15" align="left" class="cp" style=" border-botton:#ccc 1px solid; border-right: #ccc 1px solid;"><span >&nbsp;<?php echo $dadosboleto["carteira"]?></span></td>
        <td height="15" align="left" class="cp" style=" border-botton:#ccc 1px solid; border-right: #ccc 1px solid;"><span >&nbsp;<?php echo $dadosboleto["especie"]?></span></td>
        <td height="15" align="center" class="cp" style=" border-botton:#ccc 1px solid; border-right: #ccc 1px solid;"><span ><?php echo $dadosboleto["quantidade"]?></span></td>
        <td height="15" align="center" class="cp" style=" border-botton:#ccc 1px solid; border-right: #ccc 1px solid;"><span >&nbsp;<?php echo $dadosboleto["valor_unitario"]?></span></td>
      </tr>
    </table>
    </td>
  </tr>
  <tr>
    <td height="60" valign="top" style=" border-top:#000 2px solid; ">
    <span class="cp" style="padding: 0; margin: 0;" >Intruções(Texto de responsabilidade do benefíciario)</span> <br>
    <span class="cn" style="text-transform:uppercase; padding: 0px 5px; margin-top: 3px;"> <?php echo $dadosboleto["instrucoes1"]; ?>  </span> <br>
    <span class="cn" style="text-transform:uppercase; padding: 0px 5px; margin-top: 5px;"> <?php echo $dadosboleto["instrucoes2"]; ?> </span> <br>
    <span class="cn" style="text-transform:uppercase; padding: 0px 5px; margin-top: 5px;"> <?php echo $dadosboleto["inst_adcional"]; ?></span>
    </td>
  <tr>
    <td height="36" valign="top" style=" border-top:#000 2px solid; text-transform:uppercase;" >
     <span class="ct">Pagador</span><br>
     <span class="cn"> <?php echo $dadosboleto["sacado"];?></span> <br>

     <span class="cn" style="text-transform:uppercase;">
     <?php echo $dadosboleto["endereco1"];?></span>
     <span class="cn" style="text-transform:uppercase; ">
     <?php echo $dadosboleto["endereco2"]?></span>
     <span class="ct">Avalista</span>
      </td>
  </tr>
  <tr>
    <td height="50" class="img"  style=" max-height:50px; border-top:#000 2px solid; padding-top:3px;"  >
      <?php fbarcode($dadosboleto["codigo_barras"]); ?>
    </td>
    <td width="138" align="center" class="cn" style=" border-top:#000 0.2mm solid; "> Ficha de compensa&ccedil;&atilde;o<br>
    Autentica&ccedil;&atilde;o no verso </td> </tr>
</table>
<hr class="divider"></hr>
</body>
</html>
