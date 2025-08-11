<?php
// +----------------------------------------------------------------------+
// | BoletoPhp - Vers�o Beta                                              |
// +----------------------------------------------------------------------+
// | Este arquivo est� dispon�vel sob a Licen�a GPL dispon�vel pela Web   |
// | em http://pt.wikipedia.org/wiki/GNU_General_Public_License           |
// | Voc� deve ter recebido uma c�pia da GNU Public License junto com     |
// | esse pacote; se n�o, escreva para:                                   |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// | Originado do Projeto BBBoletoFree que tiveram colabora��es de Daniel |
// | William Schultz e Leandro Maniezo que por sua vez foi derivado do	  |
// | PHPBoleto de Jo�o Prado Maia e Pablo Martins F. Costa                |
// |                                                                      |
// | Se vc quer colaborar, nos ajude a desenvolver p/ os demais bancos :-)|
// | Acesse o site do Projeto BoletoPhp: www.boletophp.com.br             |
// +----------------------------------------------------------------------+

// +---------------------------------------------------------------------------------+
// | Equipe Coordena��o Projeto BoletoPhp: <boletophp@boletophp.com.br>              |
// | Desenvolvimento Boleto Banco do Brasil: Daniel William Schultz / Leandro Maniezo|
// +---------------------------------------------------------------------------------+
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title><?php echo $dadosboleto["identificacao"]; ?></title>
<meta charset="UTF-8">
<meta name="description" content="Layout Boleto CEF">
<meta name="keywords" content="HTML,CSS,XML,JavaScript">
<meta name="author" content="Juliano Rezende">
<meta name="Generator" content="Projeto BoletoPHP - www.boletophp.com.br - Licen�a GPL" />

<style type="text/css">

@media print {

/* *** TIPOGRAFIA BASICA *** */

* {
	font-family: Arial, Helvetica, sans-serif;
	margin: 0;
	padding: 0;
}
#instructions {
	height: 1px;
	visibility: hidden;
	overflow: hidden;
}

}
.notice {
	color: red;
}

.ti {font: 9px Arial, Helvetica, sans-serif}


/* *** LINHAS GERAIS *** */

#container {
	width: 800px;
	margin: 0px auto;
	padding-bottom: 0px;
	font-size:10px;
}

#instructions {
	margin: 0;
	padding: 0 0 20px 0;
}

#boleto {
	width: 800px;
	margin: 0;
	padding: 0;
}


/* *** CABECALHO *** */

#instr_header {

	padding-left: 5px;
	height: 85px;
}

#instr_header h1 {
	font-size: 16px;
	margin: 5px 0px;
}

#instr_header p {
	font-style: normal;
	font-size: 10px;
}

#instr_content {

}

#instr_content h2 {
	font-size: 10px;
	font-weight: bold;
}

#instr_content p {
	font-size: 10px;
	margin: 4px 0px;
}

#instr_content ol {
	font-size: 10px;
	margin: 5px 0;
}

#instr_content ol li {
	font-size: 10px;
	text-indent: 10px;
	margin: 2px 0px;
	list-style-position: inside;
}

#instr_content ol li p {
	font-size: 10px;
	padding-bottom: 4px;
}


/* *** BOLETO *** */

#boleto .cut {
	width: 800px;
	margin: 0px auto;
	border-bottom: 1px #000 dashed;
}

#boleto .cut p {
	margin: 0 0 5px 0;
	padding: 0px;
	font-family: 'Arial Narrow';
	font-size: 9px;
	color: #000;
}

table.header {
	width: 800px;
	height: 38px;
	margin-top: 5px;
	margin-bottom: 5px;
	border-bottom: 2px #000 solid;
}


table.header div.field_cod_banco {
	width: 46px;
	height: 19px;
    margin-left: 5px;
	padding-top: 0px;
	text-align: center;
	font-size: 17px;
	font-weight: bold;
	color: #000;
	border-right: 2px solid #000;
	border-left: 2px solid #000;
}

table.header td.linha_digitavel {
	width: 464px;
	text-align: right;
	font: bold 18px Arial;
	color: #000
}

table.line {
	margin-bottom: 3px;
	padding-bottom: 1px;
	border-bottom: 1px black solid;
}

table.line tr.titulos td {
	height: 13px;
	font-family: 'Arial Narrow';
	font-size: 9px;
	color: #000;
	border-left: 1px #000 solid;
	padding-left: 2px;
}

table.line tr.campos td {
	height: 12px;
	font-size: 10px;
	color: black;
	border-left: 1px #000 solid;
	padding-left: 2px;
}

table.line td p {
	font-size: 10px;
}


table.line tr.campos td.ag_cod_cedente,
table.line tr.campos td.nosso_numero,
table.line tr.campos td.valor_doc,
table.line tr.campos td.vencimento2,
table.line tr.campos td.ag_cod_cedente2,
table.line tr.campos td.nosso_numero2,
table.line tr.campos td.xvalor,
table.line tr.campos td.valor_doc2
{
	text-align: right;
}

table.line tr.campos td.especie,
table.line tr.campos td.qtd,
table.line tr.campos td.vencimento,
table.line tr.campos td.especie_doc,
table.line tr.campos td.aceite,
table.line tr.campos td.carteira,
table.line tr.campos td.especie2,
table.line tr.campos td.qtd2
{
	text-align: center;
}

table.line td.last_line {
	vertical-align: top;
	height: 25px;
	border-left: 1px #000 solid;

}

table.line td.last_line table.line {
	margin-bottom: -5px;
	border: 0 white none;

}

td.last_line table.line td.instrucoes {
	border-left: 0 white none;
	padding-left: 5px;
	padding-bottom: 0;
	margin-bottom: 0;
	height: 20px;
	vertical-align: top;

}

table.line td.cedente {
	width: 298px;
}

table.line td.valor_cobrado2 {
	padding-bottom: 0;
	margin-bottom: 0;
}


table.line td.ag_cod_cedente {
	width: 126px;
}

table.line td.especie {
	width: 35px;
}

table.line td.qtd {
	width: 53px;
}

table.line td.nosso_numero {
	/* width: 120px; */
	width: 115px;
	padding-right: 5px;
}

table.line td.num_doc {
	width: 113px;
}

table.line td.contrato {
	width: 72px;
}

table.line td.cpf_cei_cnpj {
	width: 132px;
}

table.line td.vencimento {
	width: 134px;
}

table.line td.valor_doc {
	/* width: 180px; */
	width: 175px;
	padding-right: 5px;
	font-weight:bold;
}

table.line td.desconto {
	width: 113px;
}

table.line td.outras_deducoes {
	width: 112px;
}

table.line td.mora_multa {
	width: 113px;
}

table.line td.outros_acrescimos {
	width: 113px;
}

table.line td.valor_cobrado {
	/* width: 180px; */
	width: 175px;
	padding-right: 5px;
	background-color: #ccc ;
}

table.line td.sacado {
	width: 659px;
}

table.line td.local_pagto {
	width: 472px;
}

table.line td.vencimento2 {
	/* width: 180px; */
	width: 149px;
	padding-right: 5px;
	background-color: transparent;
}

table.line td.cedente2 {
	width: 472px;
}

table.line td.ag_cod_cedente2 {
	/* width: 180px; */
	width: 149px;
	padding-right: 5px;
}

table.line td.data_doc {
	width: 93px;
}

table.line td.num_doc2 {
	width: 93px;
}

table.line td.especie_doc {
	width: 72px;
}

table.line td.aceite {
	width: 93px;
}

table.line td.data_process {
	width: 90px;
}

table.line td.nosso_numero2 {
	/* width: 180px; */
	width: 150px;
	padding-right: 5px;
}

table.line td.reservado {
	width: 93px;
	background-color: transparent;
}

table.line td.carteira {
	width: 93px;
}

table.line td.especie2 {
	width: 93px;
}

table.line td.qtd2 {
	width: 93px;
}

table.line td.xvalor {
	/* width: 72px; */
	width: 90px;
	padding-right: 5px;
}

table.line td.valor_doc2 {
	/* width: 180px; */
	width: 150px;
	padding-right: 5px;
	font-weight:bold;
}
table.line td.instrucoes {
	width: 475px;
}

table.line td.desconto2 {
	/* width: 180px; */
	width: 149px;
	padding-right: 5px;
}

table.line td.outras_deducoes2 {
	/* width: 180px; */
	width: 149px;
	padding-right: 5px;
}

table.line td.mora_multa2 {
	/* width: 180px; */
	width: 149px;
	padding-right: 5px;
}

table.line td.outros_acrescimos2 {
	/* width: 180px; */
	width: 149px;
	padding-right: 5px;
}

table.line td.valor_cobrado2 {
	/* width: 180px; */
	width: 149px;
	padding-right: 5px;
	background-color: transparent ;
}

table.line td.sacado2 {
	width: 300px;
}

table.line td.sacador_avalista {
	width: 659px;
}

table.line tr.campos td.sacador_avalista {
	width: 472px;
}

table.line td.cod_baixa {
	color: #000;
	width: 180px;
}




div.footer {
	margin-bottom: 10px;
}

div.footer p {
	width: 88px;
	margin: 0;
	padding: 0;
	padding-left: 525px;
	font-family: 'Arial Narro';
	font-size: 9px;
	color: #000;
}


div.barcode {
	width: 700px;
	margin-bottom: 20px;
}


</style>
</head>
<body>

<div id="container">


<div id="logo_header" style="position: absolute;">
	<img src="../../../../../imagens/empresas/<?php echo $dadosboleto["logomarca"]; ?>.png" alt="" style="height: 50px; width: 140px">
	</div>
	<div id="instr_header">
    		<table class="header" border="0" cellspacing="0" cellpadding="0" style="border:0;">
		  <tbody>
		    <tr>
		      <td width="150" rowspan="2"></td>
		      <td class="linha_digitavel" style="text-align:left;"><?php echo strtoupper(utf8_encode($dadosboleto["identificacao"])); ?></td>
	        </tr>
		    <tr>
		      <td valign="top"  style="text-align:left; font-size:10px; text-transform:uppercase;">
             CNPJ: <?php echo isset($dadosboleto["cpf_cnpj"]) ? $dadosboleto["cpf_cnpj"] : '' ?><br />
		      ENDEREÇO: <?php echo $dadosboleto["endereco"]; ?> <?php echo $dadosboleto["cidade_uf"]; ?>

              </td>
	        </tr>
	      </tbody>
	  </table>
	</div>
	<!-- id="instr_header" -->

	<div id="" style="text-align:left; border:0; padding-top:300px;">
	  <div id="instr_content">
	  </div>

     <div id="boleto">
	  <div class="cut">
			<p>Corte na linha pontilhada</p>
	  </div>
    <table cellspacing=0 cellpadding=0 width=100% border=0><TBODY><TR><TD class=ct width=666><div align=right><b class=cp>Recibo Pagador</b></div></TD></tr></tbody></table>
	  <table class="header" border=0 cellspacing="0" cellpadding="0">
		<tbody>
		<tr>
			<td width=150><IMG SRC="../../imagens/033.jpg" width="145" height="30"></td>
			<td width=50>
        <div class="field_cod_banco"><?php echo $dadosboleto["codigo_banco_com_dv"]?></div>
			</td>
			<td class="linha_digitavel"><?php echo $dadosboleto["linha_digitavel"]?></td>
		</tr>
		</tbody>
	  </table>

	  <table width="100%" cellpadding="0" cellspacing="0" class="line">
		<tbody>
		<tr class="titulos">
			<td class="cedente">Beneficiário</TD>
			<td class="ag_cod_cedente">Ag&ecirc;ncia / C&oacute;digo do Cedente</td>
			<td class="especie">Esp&eacute;cie</TD>
			<td class="qtd">Quantidade</TD>
			<td class="nosso_numero">Nosso n&uacute;mero</td>
		</tr>

		<tr class="campos">
			<td class="cedente" style="text-transform: uppercase;"><?php echo $dadosboleto["cedente"]; ?>&nbsp;</td>
			<td class="ag_cod_cedente"><?php $tmp2 = $dadosboleto["codigo_cliente"];
    		 $tmp2 = substr($tmp2,0,strlen($tmp2)-1).'-'.substr($tmp2,strlen($tmp2)-1,1);
 			 echo $dadosboleto["ponto_venda"]."/".$tmp2;?> &nbsp;</td>
			<td class="especie"><?php echo $dadosboleto["especie"]?>&nbsp;</td>
			<TD class="qtd"><?php echo $dadosboleto["quantidade"]?>&nbsp;</td>
			<TD class="nosso_numero"><?php echo tool::MascaraCampos("############-#",$dadosboleto["nosso_numero"]);?>&nbsp;</td>
		</tr>
		</tbody>
	  </table>

		<table width="100%" cellPadding="0" cellspacing="0" class="line">
		<tbody>
		<tr class="titulos">
			<td class="num_doc">N&uacute;mero do documento</td>
			<td class="cpf_cei_cnpj">CPF/CEI/CNPJ</TD>
			<td class="vencmento">Vencimento</TD>
			<td class="valor_doc">Valor documento</TD>
		</tr>
		<tr class="campos">
			<td class="num_doc"><?php echo $dadosboleto["numero_documento"];?></td>
			<td class="cpf_cei_cnpj"><?php echo $dadosboleto["cpf_cnpj"];?></td>
			<td class="vencimento"><?php echo $dadosboleto["data_vencimento"];?></td>
			<td class="valor_doc"><?php echo $dadosboleto["valor_boleto"];?></td>
		</tr>
      </tbody>
      </table>
<table width="100%" cellPadding="0" cellspacing="0" class="line">
		<tbody>
		<tr class="titulos">
			<td class="num_doc">Endereço Beneficiario</td>
		  </tr>
		<tr class="campos">
			<td class="num_doc" style="text-transform: uppercase;"><?php echo $dadosboleto["endereco"] ."  ". $dadosboleto["cidade_uf"]?></td>
		  </tr>
      </tbody>
    </table>
	  <table width="100%" cellPadding="0" cellspacing="0" class="line">
		<tbody>
		<tr class="titulos">
			<td class="desconto">(-) Desconto / Abatimento</td>
			<td class="outras_deducoes">(-) Outras dedu&ccedil;&otilde;es</td>
			<td class="mora_multa">(+) Juros / Multa</td>
			<td class="outros_acrescimos">(+) Outros acr&eacute;scimos</td>
			<td class="valor_cobrado">(=) Valor cobrado</td>
		</tr>
		<tr class="campos">
			<td class="desconto">&nbsp;</td>
			<td class="outras_deducoes">&nbsp;</td>
			<td class="mora_multa">&nbsp;</td>
			<td class="outros_acrescimos">&nbsp;</td>
			<td class="valor_cobrado">&nbsp;</td>
		</tr>
		</tbody>
	  </table>

      
		<table class="line" cellspacing="0" cellpadding="0" width="100%" >
		<tbody>
		<tr class="titulos">
			<td class="sacado">Pagador</td>
		</tr>
		<tr class="campos">
			<td class="sacado" style="text-transform:uppercase;"><?php echo $dadosboleto["sacado"]?></td>
		</tr>
		</tbody>
	  </table>
		</div>
		<div class="footer">
			<p>Autentica&ccedil;&atilde;o mec&acirc;nica</p>
		</div>
		<br />

	  <div class="cut">
			<p>Corte na linha pontilhada</p>
	  </div>


	  <table width="100%" border=0 cellpadding="0" cellspacing="0" class="header">
		<tbody>
		<tr>
			<td width=150><IMG SRC="../../imagens/033.jpg" width="145" height="30"></td>
			<td width=50>
        <div class="field_cod_banco"><?php echo $dadosboleto["codigo_banco_com_dv"]?></div>
			</td>
			<td class="linha_digitavel"><?php echo $dadosboleto["linha_digitavel"]?></td>
		</tr>
		</tbody>
	  </table>

	  <table width="100%" cellpadding="0" cellspacing="0" class="line">
		<tbody>
		<tr class="titulos">
			<td class="local_pagto">Local de pagamento</td>
			<td class="vencimento2">Vencimento</td>
		</tr>
		<tr class="campos">
			<td class="local_pagto"><strong>PREFERENCIALMENTE NO BANCO SANTANDER</strong></td>
			<td class="vencimento2" style="font-size:12px; font-weight:bold;"><?php echo $dadosboleto["data_vencimento"]?></td>
		</tr>
		</tbody>
	  </table>
		
	  <table width="100%" cellpadding="0" cellspacing="0" class="line">
		<tbody>
		<tr class="titulos">
			<td class="cedente2"><span class="cedente">Beneficiário</span></td>
			<td class="ag_cod_cedente2">Ag&ecirc;ncia/C&oacute;digo cedente</td>
		</tr>
		<tr class="campos">
			<td class="cedente2" style="text-transform: uppercase;"><?php echo $dadosboleto["cedente"]." ".$dadosboleto["cpf_cnpj"];?></td>
			<td class="ag_cod_cedente2"><?php $tmp2 = $dadosboleto["codigo_cliente"];
    		 $tmp2 = substr($tmp2,0,strlen($tmp2)-1).'-'.substr($tmp2,strlen($tmp2)-1,1);
 			 echo $dadosboleto["ponto_venda"]."/".$tmp2;?></td>
		</tr>
		</tbody>
	  </table>
      
	  <table width="100%" cellpadding="0" cellspacing="0" class="line">
        <tbody>
          <tr class="titulos">
            <td class="num_doc">Endereço Beneficiario</td>
          </tr>
          <tr class="campos">
            <td class="num_doc" style="text-transform: uppercase;"><?php echo $dadosboleto["endereco"] ."  ". $dadosboleto["cidade_uf"]?></td>
          </tr>
        </tbody>
      </table>
      
	<table width="100%" cellpadding="0" cellspacing="0" class="line">
	  <tbody>
		<tr class="titulos">
			<td class="data_doc">Data do documento</td>
			<td class="num_doc2">Nr do. documento</td>
			<td class="especie_doc">Esp&eacute;cie doc.</td>
			<td class="aceite">Aceite</td>
			<td class="data_process">Data process.</td>
			<td class="nosso_numero2">Nosso n&uacute;mero</td>
		</tr>
		<tr class="campos">
			<td class="data_doc"><?php echo $dadosboleto["data_documento"]?></td>
			<td class="num_doc2"><?php echo $dadosboleto["numero_documento"]?></td>
			<td class="especie_doc"><?php echo $dadosboleto["especie_doc"]?></td>
			<td class="aceite"><?php echo $dadosboleto["aceite"]?></td>
			<td class="data_process"><?php echo $dadosboleto["data_processamento"]?></td>
			<td class="nosso_numero2"><?php echo tool::MascaraCampos("############-#",$dadosboleto["nosso_numero"]);?></td>
		</tr>
		<tr class="titulos">
        
        	<td class="reservado" style="border-top:1px solid #000;">Uso do banco</td>
			<td class="carteira" style="border-top:1px solid #000;">Carteira</td>
			<td class="especie2" style="border-top:1px solid #000;">Esp&eacute;cie moeda</td>
			<td class="qtd2" style="border-top:1px solid #000;">Qtde moeda</td>
			<td class="xvalor" style="border-top:1px solid #000;">X Valor</td>
			<td class="valor_doc2" style="border-top:1px solid #000;">(=) Valor documento</td>
		</tr>
		<tr class="campos">
			<td class="reservado">&nbsp;</td>
			<td class="carteira"><?php echo $dadosboleto["carteira_descricao"]?><?php echo isset($dadosboleto["variacao_carteira"]) ? $dadosboleto["variacao_carteira"] : '&nbsp;' ?></td>
			<td class="especie2"><?php echo $dadosboleto["especie"]?></td>
			<td class="qtd2"><?php  echo $dadosboleto["quantidade"] ?></td>
			<td class="xvalor"><?php echo $dadosboleto["valor_unitario"]?></td>
			<td class="valor_doc2"><?php echo $dadosboleto["valor_boleto"]?></td>
		</tr>        
		</tbody>
	  </table>
	<table width="100%" cellpadding="0" cellspacing="0" class="line">
	  <tbody>
		<tr><td class="last_line" rowspan="6">
			<table width="100%" cellpadding="0" cellspacing="0" class="line">
			<tbody>
			<tr class="titulos">
				<td class="instrucoes">
						Instru&ccedil;&otilde;es (Texto de responsabilidade do cedente)
				</td>
			</tr>
			<tr class="campos">
				<td class="instrucoes" >
					<?php
						//echo utf8_encode($dadosboleto["demonstrativo1"]).",".
						//utf8_encode($dadosboleto["demonstrativo2"]).",".
						//utf8_encode($dadosboleto["demonstrativo3"]);
					?>
					<?php echo $dadosboleto["instrucoes1"]; ?><br />
					<?php echo $dadosboleto["instrucoes2"]; ?><br />
					<?php echo $dadosboleto["inst_adcional"]; ?>

				</td>
			</tr>
			</tbody>
			</table>
		</td></tr>
		<tr>
            <td>
		<table width="100%" cellpadding="0" cellspacing="0" class="line">
			<tbody>
			<tr class="titulos">
			  <td class="desconto2">(-) Desconto / Abatimento</td>
			  </tr>
			</tbody>
			</table>
		</td>
        </tr>

		<tr>
        <td>
			<table width="100%" cellpadding="0" cellspacing="0" class="line">
			<tbody>
			<tr class="titulos">
			  <td class="outras_deducoes2">(-) Outras dedu&ccedil;&otilde;es</td>
			  </tr>
			</tbody>
			</table>
		</td></tr>

		<tr><td>
			<table width="100%" cellpadding="0" cellspacing="0" class="line">
			<tbody>
			<tr class="titulos">
			  <td class="mora_multa2">(+) Juros / Multa</td>
			  </tr>
			</tbody>
			</table>
		</td></tr>

		<tr>
        <td>
			<table width="100%" cellpadding="0" cellspacing="0" class="line">
			<tbody>
			<tr class="titulos">
			  <td class="outros_acrescimos2">(+) Outros Acr&eacute;scimos</td>
			  </tr>
			</tbody>
			</table>
		</td>
        </tr>

		<tr>
        <td >
			<table width="100%" cellpadding="0" cellspacing="0" class="line" style="border-bottom:0;">
			<tbody>
			<tr class="titulos" >
			  <td class="valor_cobrado2" >(=) Valor cobrado</td>
			  </tr>
			</tbody>
			</table>
		</td>

        </tr>
	  </tbody>
	  </table>
	  <table width="100%" cellpadding="0" cellspacing="0" class="line" >
		<tbody>
		<tr class="titulos">
			<td class="sacado2">Pagador</td>
		  </tr>
		<tr class="campos">
			<td class="sacado2" style="text-transform: uppercase;">
            <?php echo " ".strtoupper($dadosboleto["sacado"]);?><br />
			<?php echo " ".strtoupper($dadosboleto["endereco1"]).",". strtoupper($dadosboleto["endereco2"]);?>
		</td>
		  </tr>
		</tbody>	  </table>
	  <table cellspacing=0 cellpadding=0 width=666 border=0>
	  <TBODY><TR>
      <TD width=666 align=left ><font style="font-size: 10px; margin-bottom:5px">Sacador/Avalista</font></TD></tr></tbody></table>
		<div class="barcode">
			<?php fbarcode($dadosboleto["codigo_barras"]); echo'<span style="vertical-align:25px;">Autenticação mecânica - Ficha de Compensação </span>'; ?>
		</div>
  </div>
</div>

</body>

</html>