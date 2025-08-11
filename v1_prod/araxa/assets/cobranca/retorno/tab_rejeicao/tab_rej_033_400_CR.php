<?php
require_once("../../../../sessao.php");
?>
<div class="tabs-spacer" style="display:none;">
	<?php
require_once("../../../../conexao.php");
$cfg->set_model_directory('../../../../models/');

$FRM_tcod  = isset( $_GET['tcod_rej']) ? tool::CompletaZeros("3",$_GET['tcod_rej'])  : tool::msg_erros("O Campo tcod_rej é Obrigatorio.");

?>
</div>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
<nav class="uk-navbar ">
<table  class="uk-table" >
    <thead >
      <tr style="line-height:25px;">
        <th>Código</th>
        <th>Descrição</th>
      </tr>
    </thead>
 </table>
</nav>
<div style="width: 100%; overflow-x: auto; height: 460px;">
<table  class="uk-table" id="rejeicoes" >

<!-- inicio dos possiveis erros do CODIGO 01 = 004 OR 042 OR 017 OR 142 143 OR 371 OR 372-->
<tr class="tcod_001 cod_004"> <td>00004</td> <td>CONTA COBRANCA NAO NUMERICA</td></tr>
<tr class="tcod_001 cod_017"> <td>00017</td> <td>CODIGO DA AGENCIA COBRADORA NAO NUMERICA</td></tr>
<tr class="tcod_001 cod_142"> <td>00142</td> <td>NUM.AG.CEDENTE/DIG.NAO NUMERICO</td></tr>
<tr class="tcod_001 cod_143"> <td>00143</td> <td>NUM. CONTA CEDENTE/DIG. NAO NUMERICO</td></tr>
<tr class="tcod_001 cod_371"> <td>00371</td> <td>TITULO REJEITADO - OPERACAO DE DESCONTO</td></tr>
<tr class="tcod_001 cod_372"> <td>00372</td> <td>TIT. REJEITADO - HORARIO LIMITE OP DESCONTO</td></tr>

<!-- inicio dos possiveis erros do CODIGO 02 = 010 OR 011 OR 019 OR 065 067 OR 077 OR 125 OR 038-->
<tr class="tcod_002 cod_125"> <td>00125</td> <td>COMPLEMENTO DA INSTRUCAO NAO NUMERICO</td></tr>
<tr class="tcod_002 cod_077"> <td>00077</td> <td>DESC. POR ANTEC. MAIOR/IGUAL VLR TITULO</td></tr>
<tr class="tcod_002 cod_067"> <td>00067</td> <td>CLIENTE NAO TRANSMITE REG. DE OCORRENCIA</td></tr>
<tr class="tcod_002 cod_065"> <td>00065</td> <td>PEDIDO SUSTACAO JA SOLICITADO</td></tr>
<tr class="tcod_002 cod_038"> <td>00038</td> <td>MOVIMENTO EXCLUIDO POR SOLICITACAO</td></tr>
<tr class="tcod_002 cod_019"> <td>00019</td> <td>NUMERO DO CEP NAO NUMERICO</td></tr>
<tr class="tcod_002 cod_010"> <td>00010</td> <td>CODIGO PRIMEIRA INSTRUCAO NAO NUMERICA</td></tr>
<tr class="tcod_002 cod_011"> <td>00011</td> <td>CODIGO SEGUNDA INSTRUCAO NAO NUMERICA</td></tr>

<!-- inicio dos possiveis erros do CODIGO 04 = 039 OR 040 OR 059 OR 069 170 OR 201 -->
<tr class="tcod_004 cod_039"> <td>00039</td> <td>PERFIL NAO ACEITA TITULO EM BCO CORRESP</td>  </tr>
<tr class="tcod_004 cod_040"> <td>00040</td> <td>COBR RAPIDA NAO ACEITA-SE BCO CORRESP</td>  </tr>
<tr class="tcod_004 cod_069"> <td>00069</td> <td>PRODUTO DIFERENTE DE COBRANCA SIMPLES</td>  </tr>
<tr class="tcod_004 cod_059"> <td>00059</td> <td>INSTRUCAO ACEITA SO P/ COBRANCA SIMPLES</td>  </tr>
<tr class="tcod_004 cod_170"> <td>00170</td> <td>FORMA DE CADASTRAMENTO 2 INV.P.CART.5</td>  </tr>
<tr class="tcod_004 cod_201"> <td>00201</td> <td>ALT.DO CONTR.PARTICIPANTEINVALIDO</td>  </tr>

<!-- inicio dos possiveis erros do CODIGO 05 = 070 OR 071 OR 072 OR 088 -->
<tr class="tcod_005 cod_070"> <td>00070</td> <td>DATA PRORROGACAO MENOR OUE DATA VENCTO</td></tr>
<tr class="tcod_005 cod_201"> <td>00071</td> <td>DATA ANTECIPACAO MAIOR OUE DATA VENCTO</td></tr>
<tr class="tcod_005 cod_201"> <td>00072</td> <td>DATA DOCUMENTO SUPERIOR A DATA INSTRUCAO</td></tr>
<tr class="tcod_005 cod_201"> <td>00088</td> <td>DATA INSTRUCAO INVALIDA</td></tr>

<!-- inicio dos possiveis erros do CODIGO 06 = 018 -->
<tr class="tcod_006 cod_201"> <td>00018</td> <td>VALOR DO IOC NAO NUMERICO</td></tr>

<!-- inicio dos possiveis erros do CODIGO 07 = 026 OR 041 -->
<tr class="tcod_007 cod_201"> <td>00026</td> <td>CODIGO BANCO COBRADOR INVALIDO</td></tr>
<tr class="tcod_007 cod_201"> <td>00041</td> <td>AGENCIA COBRADORA NAO ENCONTRADA</td></tr>

<!-- inicio dos possiveis erros do CODIGO 08 = 130 OR 131 OR 132 OR 133 -->
<tr class="tcod_008 cod_201"> <td>00130</td> <td>FORMA DE CADASTRAMENTO NAO NUMERICA</td></tr>
<tr class="tcod_008 cod_201"> <td>00131</td> <td>FORMA DE CADASTRAMENTO INVALIDA</td>  </tr>
<tr class="tcod_008 cod_201"> <td>00132</td> <td>FORMA CADAST. 2 INVALIDA PARA CARTEIRA 3</td></tr>
<tr class="tcod_008 cod_201"> <td>00133</td> <td>FORMA CADAST. 2 INVALIDA PARA CARTEIRA 4</td></tr>

<!-- inicio dos possiveis erros do CODIGO 09 = 136 OR 137 -->
<tr class="tcod_009 cod_201"> <td>00136</td> <td>CODIGO BCO NA COMPENSACAO NAO NUMERICO</td></tr>
<tr class="tcod_009 cod_201"> <td>00137</td> <td>CODIGO BCO NA COMPENSACAO INVALIDO</td></tr>

<!-- inicio dos possiveis erros do CODIGO 10 = 140 OR 141 -->
<tr class="tcod_010 cod_201"> <td>00140</td> <td>tcod. SEOUEC.DO REG. DETALHE INVALIDO</td></tr>
<tr class="tcod_010 cod_201"> <td>00141</td> <td>NUM. SEO. REG. DO LOTE NAO NUMERICO</td></tr>

<!-- inicio dos possiveis erros do CODIGO 11 = 138 OR 139 OR 164 -->
<tr class="tcod_011 cod_201"> <td>00138</td> <td>NUM. LOTE REMESSA(DETALHE) NAO NUMERICO</td></tr>
<tr class="tcod_011 cod_201"> <td>00139</td> <td>TIPO DE REGISTRO INVALIDO</td></tr>
<tr class="tcod_011 cod_201"> <td>00164</td> <td>NUMERO DO PLANO NAO NUMERICO</td></tr>

<!-- inicio dos possiveis erros do CODIGO 12 = 202 -->
<tr class="tcod_012 cod_201"> <td>00202</td> <td>TIPO INSC.SACADO NAO NUMERICO (D30)</td></tr>

<!-- inicio dos possiveis erros do CODIGO 26 = 22 OR 134 -->
<tr class="tcod_026 cod_201"> <td>00022</td> <td>CODIGO OCORRENCIA INVALIDO</td></tr>
<tr class="tcod_026 cod_201"> <td>00134</td> <td>CODIGO DO MOV. REMESSA NAO NUMERICO</td></tr>

<!-- inicio dos possiveis erros do CODIGO 28 = 001 OR 050 OR 092 OR 099 OR 051 -->
<tr class="tcod_028"> <td>00001</td> <td>NOSSO NUMERO NAO NUMERICO</td></tr>
<tr class="tcod_028"> <td>00050</td> <td>NUMERO DO TITULO IGUAL A ZERO</td>  </tr>
<tr class="tcod_028"> <td>00051</td> <td>TITULO NAO ENCONTRADO</td></tr>
<tr class="tcod_028"> <td>00092</td> <td>NOSSO NUMERO JA CADASTRADO</td></tr>
<tr class="tcod_028"> <td>00099</td> <td>REGISTRO DUPLICADO NO MOVIMENTO DIARIO</td></tr>

<!-- inicio dos possiveis erros do CODIGO 31 = 005 OR 006 -->
<tr class="tcod_031"> <td>00005</td> <td>CODIGO DA CARTEIRA NAO NUMERICO</td></tr>
<tr class="tcod_031"> <td>00006</td> <td>CODIGO DA CARTEIRA INVALIDO</td></tr>

<!-- inicio dos possiveis erros do CODIGO 32 = 003 OR 016 OR 030 OR 068 -->
<tr class="tcod_032"> <td>00003</td> <td>DATA VENCIMENTO NAO NUMERICA</td></tr>
<tr class="tcod_032"> <td>00016</td> <td>DATA DE VENCIMENTO INVALIDA</td></tr>
<tr class="tcod_032"> <td>00030</td> <td>DT VENC MENOR DE 15 DIAS DA DT PROCES</td></tr>
<tr class="tcod_032"> <td>00068</td> <td>TIPO DE VENCIMENTO INVALIDO</td></tr>

<!-- inicio dos possiveis erros do CODIGO 34 = 012 OR 013 OR 093 OR 094 095 -->
<tr class="tcod_034"> <td>00012</td> <td>VALOR DO TITULO EM OUTRA UNIDADE</td></tr>
<tr class="tcod_034"> <td>00013</td> <td>VALOR DO TITULO NAO NUMERICO</td></tr>
<tr class="tcod_034"> <td>00093</td> <td>VALOR DO TITULO NAO INFORMADO</td></tr>
<tr class="tcod_034"> <td>00094</td> <td>OVALOR TIT. EM OUTRA MOEDA NAO INFORMADO</td></tr>
<tr class="tcod_034"> <td>00095</td> <td>PERFIL NAO ACEITA VALOR TITULO ZERADO</td></tr>

<!-- inicio dos possiveis erros do CODIGO 36 = 007 OR 060 OR 097 OR 129 144 OR 145 -->
<tr class="tcod_036"> <td>00007</td> <td>ESPECIE DO DOCUMENTO INVALIDA</td></tr>
<tr class="tcod_036"> <td>00060</td> <td>ESPECIE DOCUMENTO NAO PROTESTAVEL</td></tr>
<tr class="tcod_036"> <td>00097</td> <td>ESPECIE DOCTO NAO PERMITE IOC ZERADO</td></tr>
<tr class="tcod_036"> <td>00129</td> <td>ESPEC DE DOCUMENTO NAO NUMERICA</td></tr>
<tr class="tcod_036"> <td>00144</td> <td>TIPO DE DOCUMENTO NAO NUMERICO</td></tr>
<tr class="tcod_036"> <td>00145</td> <td>TIPO DE DOCUMENTO INVALIDO</td></tr>

<!-- inicio dos possiveis erros do tcod.39 = 015 OR 098 OR 100 -->
<tr class="tcod_039"> <td>00015</td> <td>DATA EMISSAO NAO NUMERICA </td></tr>
<tr class="tcod_039"> <td>00098</td> <td>DATA EMISSAO INVALIDA</td></tr>
<tr class="tcod_039"> <td>00100</td> <td>DATA EMISSAO MAIOR OUE A DATA VENCIMENTO</td></tr>

<!-- inicio dos possiveis erros do tcod.41 = 149 or 150 -->
<tr class="tcod_041"> <td>00149</td> <td>CODIGO DE MORA INVALIDO</td></tr>
<tr class="tcod_041"> <td>00150</td> <td>CODIGO DE MORA NAO NUMERICO</td></tr>

<!-- inicio dos possiveis erros do tcod.42 = 014 OR 029 OR 109 OR 151 OR 152 OR 153 OR 154 OR 155-->
<tr class="tcod_042"> <td>00014</td> <td>VALOR DE MORA NAO NUMERICO</td></tr>
<tr class="tcod_042"> <td>00029</td> <td>VALOR DE MORA INVALIDO</td></tr>
<tr class="tcod_042"> <td>00109</td> <td>VALOR MORA TEM OUE SER ZERO (TIT = ZERO)</td></tr>
<tr class="tcod_042"> <td>00151</td> <td>VL.MORA IGUAL A ZEROS P. tcod.MORA 1</td></tr>
<tr class="tcod_042"> <td>00152</td> <td>CVL. TAXA MORA IGUAL A ZEROS P.tcod MORA 2</td></tr>
<tr class="tcod_042"> <td>00153</td> <td>VL. MORA DIFERENTE DE ZEROS P.tcod.MORA 3</td></tr>
<tr class="tcod_042"> <td>00154</td> <td>VL. MORA NAO NUMERICO P. tcod MORA 2</td></tr>
<tr class="tcod_042"> <td>00155</td> <td>VL. MORA INVALIDO P. tcod.MORA 4</td></tr>

<!-- inicio dos possiveis erros do tcod.44 = 086 OR 087 OR 110 OR 111-->
<tr class="tcod_044"> <td>00086</td> <td>JA EXISTE TERCEIRO DESCONTO</td></tr>
<tr class="tcod_044"> <td>00087</td> <td>DATA TERCEIRO DESCONTO INVALIDA</td></tr>
<tr class="tcod_044"> <td>00110</td> <td>DATA PRIMEIRO DESCONTO INVALIDA</td></tr>
<tr class="tcod_044"> <td>00111</td> <td>DATA DESCONTO NAO NUMERICA</td></tr>

<!-- inicio dos possiveis erros do tcod.45 = 025 OR 074 OR 075 OR 076 OR 077 OR 079 OR 080 OR 081 OR 082 OR 090 OR 091 OR 112 OR 113-->
<tr class="tcod_045"> <td>00025</td> <td>VALOR DESCONTO NAO NUMERICO</td></tr>
<tr class="tcod_045"> <td>00074</td> <td>PRIM. DESGONTO MAIOR/IGUAL VALOR TITULO</td></tr>
<tr class="tcod_045"> <td>00075</td> <td>SEG. DESCONTO MAIOR/IGUAL VALOR TITULO</td></tr>
<tr class="tcod_044"> <td>00076</td> <td>TERC. DESCONTO MAIOR/IGUAL VALOR TITULO</td></tr>
<tr class="tcod_045"> <td>00077</td> <td>DESC. POR ANTEC. MAIOR/IGUAL VLR TITULO</td></tr>
<tr class="tcod_045"> <td>00078</td> <td>NAO EXISTE ABATIMENTO P/ CANCELAR</td></tr>
<tr class="tcod_045"> <td>00079</td> <td>NAO EXISTE PRIM. DESCONTO P/ CANCELAR</td></tr>
<tr class="tcod_045"> <td>00080</td> <td>NAO EXISTE SEG. DESCONTO P/ CANCELAR</td></tr>
<tr class="tcod_045"> <td>00081</td> <td>NAO EXISTE TERC. DESCONTO P/ CANCELAR</td></tr>
<tr class="tcod_045"> <td>00082</td> <td>NAO EXISTE DESC. POR ANTEC. P/ CANCELAR</td></tr>
<tr class="tcod_045"> <td>00090</td> <td>JA EXISTE DESCONTO POR DIA ANTECIPACAO</td></tr>
<tr class="tcod_045"> <td>00091</td> <td>JA EXISTE CONCESSAO DE DESCONTO</td></tr>
<tr class="tcod_045"> <td>00112</td> <td>VALOR DESCONTO NAO INFORMADO</td></tr>
<tr class="tcod_045"> <td>00113</td> <td>VALOR DESCONTO INVALIDO</td></tr>

<!-- inicio dos possiveis erros do tcod.46 = 122 -->
<tr class="tcod_046"> <td>00122</td> <td>VALOR IOF MAIOR OUE VALOR TITULO</td></tr>

<!-- inicio dos possiveis erros do tcod.47 = 002 OR 114 -->
<tr class="tcod_047"> <td>00002</td> <td>VALOR DO ABATIMENTO NAO NUMERICO</td></tr>
<tr class="tcod_047"> <td>00114</td> <td>VALOR ABATIMENTO NAO INFORMADO</td></tr>

<!-- inicio dos possiveis erros do tcod.48	128 OR 146 -->
<tr class="tcod_048"> <td>00128</td> <td>CODIGO PROTESTO INVALIDO</td></tr>
<tr class="tcod_048"> <td>00146</td> <td>CODIGO P. PROTESTO NAO NUMERICO</td></tr>
<!--


tcod.49	046 OR 147 OR 148
tcod.52	045 OR 156 OR 157 OR 158
	159
tcod.53	008 OR 009
tcod.55 	024 OR 027 OR 028 OR 047
	048 OR 049 OR 162 OR 163
	165 OR 168 OR 169
tcod.57 	166 OR 167
tcod.59 	020 OR 021 OR 058 OR 105
	106 OR 108
tcod.61	101
tcod.62	019 OR 057 OR 063 OR 123
	124
tcod.63	102 OR 160
tcod.64	103
tcod.65	104 OR 107
tcod.66	161 OR 199
tcod.67	200
tcod.71	084
tcod.74	085
tcod.76	089 OR 116 OR 118 OR 119
tcod.77	083 OR 120 OR 121
tcod.80	053
tcod.81	052
tcod.84	056
tcod.89	062
tcod.90	073 OR 115
tcod.91	117
<!-- inicio dos possiveis erros do tcod.92	078 -->
<tr class="tcod_092"> <td>00128</td> <td>NOSSO NUMERO JA CADASTRADO</td></tr>
<!--
tcod.93	043
tcod.94	044 OR 054 OR 055 OR 061
	064 OR 066 OR 096
-->


</table>
</div>




</body>

<script type="text/javascript">

$(function(){
$("#rejeicoes tr").hide();
});


jQuery(".tcod_<?php echo $FRM_tcod; ?>").css("background-color","#FC0").css("color","#fff").show();

</script>
</html>