<?php
set_time_limit(0);
ignore_user_abort(1);
include("../../../../../sessao.php");
include("../../../../../conexao.php");
$cfg->set_model_directory('../../../../../models/');

require_once("../../../../../library/mpdf60/mpdf.php");

/* VALORES VINDOS DA REQUISIÇÃO */
$FRM_convenio_id        =   isset( $_GET['convenio_id'])    ? $_GET['convenio_id']  : tool::msg_erros("O Campo convenio_id é Obrigatorio.");
$FRM_referencia         =   isset( $_GET['referencia'])     ? $_GET['referencia']   : tool::msg_erros("O Campo referencia é Obrigatorio.");
$FRM_id_titulo 			= 	isset( $_GET['t_id'])  			? $_GET['t_id'] 		: tool::msg_erros("Campo invalido.");



$id_titulo						= $FRM_id_titulo;

//RECUPERA OS DADOS DO TITULO
$dados_titulo					= titulos::find_by_sql("SELECT
														  titulos_bancarios.id,
														  titulos_bancarios.tp_sacado,
			                                              titulos_bancarios.numero_doc,
			                                              titulos_bancarios.nosso_numero,
			                                              titulos_bancarios.dv_nosso_numero,
			                                              titulos_bancarios.vlr_nominal,
			                                              titulos_bancarios.dt_vencimento,
														  titulos_bancarios.sacado,
														  titulos_bancarios.logradouro as logradouro_sacado,
														  titulos_bancarios.num as num_sacado,
														  titulos_bancarios.bairro as bairro_sacado,
														  titulos_bancarios.cidade as cidade_sacado,
														  titulos_bancarios.uf as uf_sacado,
														  titulos_bancarios.cep as cep_sacado,
														  titulos_bancarios.cpfcnpjsacado cpfcnpj,
														  contas_bancarias.id,
														  contas_bancarias.agencia,
														  contas_bancarias.conta,
														  contas_bancarias_cobs.cod_cedente as convenio,
														  contas_bancarias_cobs.dv_cod_cedente as dv_convenio,
														  contas_bancarias_cobs.carteira_cobranca as carteira,
														  contas_bancarias_cobs.variacao_carteira as modalidade,
														  contas_bancarias_cobs.especie,
														  contas_bancarias_cobs.aceite,
														  contas_bancarias_cobs.especie_doc,
														  contas_bancarias_cobs.local_pgto,
														  contas_bancarias_cobs.inst1,
														  contas_bancarias_cobs.inst2,
														  contas_bancarias_cobs.inst_adcional,
														  contas_bancarias_cobs.favorecido as razao_social,
			                                              empresas.nm_fantasia as nm_fantasia,
			                                              contas_bancarias_cobs.cnpj as cnpjbeneficiario,
														  empresas.id as logo,
														  empresas.cnpj,
														  empresas.num,
														  logradouros.id,
														  logradouros.cep,
														  empresas.compl_end,
														  logradouros.complemento as complemento,
														  logradouros.descricao as logradouro,
														  bairros.descricao as bairro,
														  cidades.descricao as cidade,
														  estados.sigla as uf,
			                                              configs.juros,
			                                              configs.multa
														FROM
														titulos_bancarios
														LEFT JOIN contas_bancarias ON contas_bancarias.id = titulos_bancarios.contas_bancarias_id
														LEFT JOIN empresas ON empresas.id = titulos_bancarios.empresas_id
														LEFT JOIN contas_bancarias_cobs	ON contas_bancarias_cobs.contas_bancarias_id = contas_bancarias.id
														LEFT JOIN logradouros ON logradouros.id = empresas.logradouros_id
														LEFT JOIN bairros ON bairros.id = logradouros.bairros_id
														LEFT JOIN cidades ON cidades.id = logradouros.cidades_id
														LEFT JOIN estados ON estados.id = logradouros.estados_id
														LEFT JOIN configs ON configs.empresas_id      = titulos_bancarios.empresas_id
														WHERE
														  titulos_bancarios.id = '".$id_titulo."'");


// DADOS DO BOLETO PARA O SEU CLIENTE
$dias_de_prazo_para_pagamento 	= 0; // dias para pagamento
$taxa_boleto 				 	= 0; // se houver taixa do boleto

//date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006";
$dtvenc 						= new ActiveRecord\DateTime($dados_titulo[0]->dt_vencimento);
$data_venc 						= $dtvenc->format('d/m/Y');

// Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
$valor_cobrado 					= $dados_titulo[0]->vlr_nominal;
$valor_cobrado 					= str_replace(",", ".",$valor_cobrado);
$valor_boleto					= number_format($valor_cobrado+$taxa_boleto, 2, ',', '');

//$dadosboleto["nosso_numero"] = "08123456";  // Até 8 digitos, sendo os 2 primeiros o ano atual (Ex.: 08 se for 2008)


/*************************************************************************
 * +++
 *************************************************************************/

// http://www.bancoob.com.br/atendimentocobranca/CAS/2_Implanta%C3%A7%C3%A3o_do_Servi%C3%A7o/Sistema_Proprio/DigitoVerificador.htm
// http://blog.inhosting.com.br/calculo-do-nosso-numero-no-boleto-bancoob-sicoob-do-boletophp/
// http://www.samuca.eti.br
//
// http://www.bancoob.com.br/atendimentocobranca/CAS/2_Implanta%C3%A7%C3%A3o_do_Servi%C3%A7o/Sistema_Proprio/LinhaDigitavelCodicodeBarras.htm

// Contribuição de script por:
//
// Samuel de L. Hantschel
// Site: www.samuca.eti.br
//



$IdDoSeuSistemaAutoIncremento = $dados_titulo[0]->nosso_numero; // Deve informar um numero sequencial a ser passada a função abaixo, Até 6 dígitos
$agencia = $dados_titulo[0]->agencia; // Num da agencia, sem digito
$conta = $dados_titulo[0]->conta; // Num da conta, sem digito
$convenio =$dados_titulo[0]->convenio.$dados_titulo[0]->dv_convenio; //Número do convênio indicado no frontend




$dadosboleto["numero_documento"] 	= $dados_titulo[0]->id;	// Num do pedido ou do documento
$dadosboleto["data_vencimento"] 	= $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
$dadosboleto["data_documento"] 		= date("d/m/Y"); // Data de emissão do Boleto
$dadosboleto["data_processamento"] 	= date("d/m/Y"); // Data de processamento do boleto (opcional)
$dadosboleto["valor_boleto"] 		= $valor_boleto; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula

// DADOS DO SEU CLIENTE
if(strlen($dados_titulo[0]->cpfcnpj)>11){
	$cpfcnpj=tool::MascaraCampos("??.???.???/????-??",$dados_titulo[0]->cpfcnpj);
}else{
	$cpfcnpj=tool::MascaraCampos("???.???.???-??",$dados_titulo[0]->cpfcnpj);
}

$dadosboleto["sacado"] 				= utf8_encode(ucwords($dados_titulo[0]->sacado))." ".$cpfcnpj;
$dadosboleto["endereco1"] 			= utf8_encode(ucwords($dados_titulo[0]->logradouro_sacado))." nº ".$dados_titulo[0]->num_sacado." ".
								  	  utf8_encode(ucwords($dados_titulo[0]->bairro_sacado));

$dadosboleto["endereco2"] 			= utf8_encode(ucwords($dados_titulo[0]->cidade_sacado))." / ".
											  	  strtoupper($dados_titulo[0]->uf_sacado)." CEP ".
											  	  tool::MascaraCampos("?????-???",$dados_titulo[0]->cep_sacado);

// INFORMACOES PARA O CLIENTE

$dadosboleto["demonstrativo1"] 		= "Faturamento";
$dadosboleto["demonstrativo2"] 		= "Mensalidades e Serviços referente ".tool::Referencia(tool::LimpaString($FRM_referencia),"/");
$dadosboleto["demonstrativo3"] 		= utf8_encode($dados_titulo[0]->local_pgto);


//CALCULA O VALOR DE JUROS
 $jurosmes       = ($dados_titulo[0]->juros/12);
 $jurosdia       = ($jurosmes/30);
 $juros          = ($jurosdia/100);
 $juros_ao_dia  = ($dados_titulo[0]->vlr_nominal * $juros) ;

//CALCULA A MULTA
 $multa = $dados_titulo[0]->multa/100;
 $multa_atrazo  = ($dados_titulo[0]->vlr_nominal * $multa) ;

// INSTRUÇÕES PARA O CAIXA
$dadosboleto["instrucoes1"]     = strtoupper($dados_titulo[0]->inst1." ".number_format($juros_ao_dia,2,",",".")." ao dia.");
$dadosboleto["instrucoes2"]     = strtoupper($dados_titulo[0]->inst2." ".number_format($multa_atrazo,2,",","."));
$dadosboleto["inst_adcional"]   = strtoupper($dados_titulo[0]->inst_adcional);

// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["quantidade"] 			= "";
$dadosboleto["valor_unitario"] 		= "";
$dadosboleto["aceite"] 				= $dados_titulo[0]->aceite;
$dadosboleto["especie"] 			= $dados_titulo[0]->especie;
$dadosboleto["especie_doc"]			= $dados_titulo[0]->especie_doc;
$dadosboleto["local_pgto"] 			= strtoupper($dados_titulo[0]->local_pgto);


// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //
// DADOS ESPECIFICOS DO SICOOB
$dadosboleto["modalidade_cobranca"] = $dados_titulo[0]->modalidade;
$dadosboleto["numero_parcela"] 		= "001";


// DADOS DA SUA CONTA - BANCO SICOOB
$dadosboleto["agencia"] 			= $agencia; // Num da agencia, sem digito
$dadosboleto["conta"] 				= $conta; // Num da conta, sem digito

// DADOS PERSONALIZADOS - SICOOB
$dadosboleto["convenio"] 			= $convenio; // Num do convênio - REGRA: No máximo 7 dígitos
$dadosboleto["carteira"] 			= $dados_titulo[0]->carteira;

// SEUS DADOS
$dadosboleto["identificacao"] 		= strtoupper($dados_titulo[0]->nm_fantasia);
$dadosboleto["cpf_cnpj"] 			= tool::MascaraCampos("??.???.???/????-??",$dados_titulo[0]->cnpj);
$dadosboleto["logo"] 				= tool::CompletaZeros("3",$dados_titulo[0]->logo);

$dadosboleto["endereco"] 			= ucfirst($dados_titulo[0]->complemento)." ".
								 	  ucfirst($dados_titulo[0]->logradouro ." ". $dados_titulo[0]->compl_end )." Nº ".$dados_titulo[0]->num." ".
								  	  ucfirst(strtolower($dados_titulo[0]->bairro));

$dadosboleto["cidade_uf"] 			= ucfirst($dados_titulo[0]->cidade)." / ".
											  	  $dados_titulo[0]->uf." CEP ".
											  	  tool::MascaraCampos("?????-???",$dados_titulo[0]->cep);

$dadosboleto["cedente"] 			= utf8_encode($dados_titulo[0]->razao_social);



// NÃO ALTERAR!
include("include/funcoes_sicoob.php");


$Update_titulo=titulos::find($id_titulo);
// altera o status da parcela
$Update_titulo->update_attributes(
array(
'linha_digitavel'=>tool::limpaString($dadosboleto["linha_digitavel"]),
'stflagimp'=>1,'dv_nosso_numero'=>$Dv
));


ob_start();
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $dadosboleto["identificacao"]; ?></title>
<meta charset="UTF-8">
<meta name="description" content="Boleto Sicoob">
<style type="text/css">
* {
	font-family:"DejaVu Sans Condensed";
	font-size: 9pt;
	margin: 0;
	padding: 0;
}
.td_line{ padding:2px;}
.tr_t{ line-height:4mm; padding-top:0.2mm; padding-bottom:0.2mm;}
.tr_c{ line-height:7mm; padding-top:0.5mm; padding-bottom:0.2mm;}
.ct{ font-size:6pt; text-transform:capitalize; padding:2px; list-style:0.4mm; vertical-align:text-top; }
.cd{ font-size:7pt;  padding:1px; padding-left:3px; list-heigth:0.6mm; vertical-align:middle;}

/* *** LINHAS GERAIS *** */

.container {width: 205mm;margin: 0px auto;padding-bottom:0.3mm;height:297mm;}

.header{ width: 205mm; height:30mm; margin:0 auto; }
.header h1{ font-size:12pt;}
.header address{font-size: 8pt; text-transform: capitalize;}

.intrucoes{ width: 205mm; height:85mm; font-size:8pt;margin:0 auto; }
.intrucoes h1{ font-size:09pt; text-transform:capitalize; margin-left:2mm;}

.devider{border-bottom:0.1mm dotted #000; font-size:5pt; text-transform:capitalize;}

.boleto{ width:205mm; height:150mm;margin:0 auto; }
.cod_banco,.linhadigitavel{ font-size:12pt; font-weight:bold; text-align:center;}


/* *** LINHAS GERAIS *** */
.border-left{ border-left:0.2mm solid #000;}
.border-right{ border-right:0.2mm solid #000;}
.border-bottom-header{ border-bottom:0.2mm solid #000;}
.border-bottom{ border-bottom:0.1mm solid #000;}
.text-left{ text-align:left;}
.text-center{ text-align:center;}
.text-right{ text-align:right;}
.text-featured{ font-size:9pt; font-weight:bold;}
.text-featured{ font-size:8pt; font-weight:bold;}
.text-upper{ text-transform:uppercase;}
.text-cap{ text-transform: capitalize;}



/* configurações da tabela do extrato de faturamento*/

.ext_fat thead tr th{line-height:30px;  font-size:10px; background-color:#e1e1e1;}
.ext_fat tbody tr td{ line-height:25px; font-size:9px;}
.ext_fat tfoot tr th{line-height:30px;  font-size:10px; background-color:#e1e1e1;}
.cd1 {font-size:7pt;  padding:1px; padding-left:3px; list-heigth:0.6mm; vertical-align:middle;}
.cd2 {font-size:7pt;  padding:1px; padding-left:3px; list-heigth:0.6mm; vertical-align:middle;}
.cd3 {font-size:7pt;  padding:1px; padding-left:3px; list-heigth:0.6mm; vertical-align:middle;}
.cd4 {font-size:7pt;  padding:1px; padding-left:3px; list-heigth:0.6mm; vertical-align:middle;}
.cd5 {font-size:7pt;  padding:1px; padding-left:3px; list-heigth:0.6mm; vertical-align:middle;}
</style>
</head>
<body>
<div class="container">

  <div class="header">

        <div style="float:left; width:50mm; margin:10px 10px;" id="logomarcaempresa">
        <img src="../../../../../imagens/empresas/<?php echo $dadosboleto["logo"]; ?>.png" width="110" height="50">
        </div>
		<div style="float:left; margin-left:10mm;" id="cab_empresa">
		<h1 style="text-transform: uppercase;"><?php echo $dadosboleto["identificacao"]; ?></h1>
		<address>Cnpj: <?php echo isset($dadosboleto["cpf_cnpj"]) ? $dadosboleto["cpf_cnpj"] : '' ?></address>
		<address> Endereco: <?php echo $dadosboleto["endereco"]; ?><br></address>
		<address><?php echo $dadosboleto["cidade_uf"]; ?></address>
        </div>
    </div>

  	<div class="intrucoes">
    	<h1>Demonstrativo:</h1>
    	<p><?php echo $dadosboleto["demonstrativo1"]; ?><br />
		<?php echo $dadosboleto["demonstrativo2"]; ?><br />
		<?php echo $dadosboleto["demonstrativo3"]; ?></p>

    </div>

    <div class="devider">Corte na linha pontilhada</div>

    <div class="boleto">

          <table  border="0" cellspacing="0" cellpadding="0" style="margin-top:7mm;width:200mm;">
            <tr>
              <td>
              <table style="width:200mm;" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="20%" class="border-bottom-header">
                  <img src="../../imagens/756.jpg" alt="Santander" width="91" height="22" style="padding:1px;">
                  </td>
                  <td width="9%" class="cod_banco border-bottom border-left border-right" ><?php echo $dadosboleto["codigo_banco_com_dv"]?></td>
                  <td width="71%" class="linhadigitavel border-bottom" ><?php echo $dadosboleto["linha_digitavel"]?></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td class="td_line">
              <table style="width:200mm;" border="0" cellspacing="0" cellpadding="0">
                <tr class="tr_t">
                  <td style="width:100mm;" class="ct border-left border-right ct">Beneficiario</td>
                  <td style="width:30mm;" class="border-right ct">Agência/Codigo Cliente</td>
                  <td style="width:30mm;" class="border-right ct">Espécie</td>
                  <td style="width:30mm;" class="ct">Nosso Numero</td>
                </tr>
                <tr class="tr_c">
                  <td style="width:100mm;" class="cd border-bottom border-left border-right text-left text-upper"><?php echo $dadosboleto["cedente"]; ?></td>
                  <td style="width:30mm;" class="cd border-bottom  border-right text-right"><?php echo $dadosboleto["agencia_codigo"]?></td>
                  <td style="width:30mm;" class="cd border-bottom  border-right text-left"><?php echo $dadosboleto["especie"]?></td>
                  <td style="width:30mm;" class="cd border-bottom text-right text-featured"><?php echo $dadosboleto["nosso_numero"]?></td>
                </tr>
              </table>
              </td>
            </tr>
            <tr>
              <td class="td_line">
              <table style="width:200mm;" border="0" cellspacing="0" cellpadding="0">
                <tr class="tr_t">
                  <td style="width:39.06mm;" class="ct border-left border-right ct">cpf/cei/cnpj</td>
                  <td style="width:39.08mm;" class="border-right ct">contrato</td>
                  <td style="width:39.08mm;" class="border-right ct">num - documento</td>
                  <td style="width:39.08mm;" class="border-right ct">vencimento</td>
                  <td style="width:40mm;" class="ct">Valor Documento</td>
                </tr>
                <tr class="tr_c">
                  <td style="width:39.06mm;"  class="cd border-bottom border-left border-right text-right"><?php echo $dadosboleto["cpf_cnpj"]?></td>
                  <td style="width:39.08mm;" class="cd border-bottom  border-right text-right">&nbsp;</td>
                  <td style="width:39.08mm;" class="cd border-bottom  border-right text-right"><?php echo $dadosboleto["numero_documento"]?></td>
                  <td style="width:39.08mm;" class="cd border-bottom  border-right text-right text-featured"><?php echo $dadosboleto["data_vencimento"]?></td>
                  <td style="width:40mm;" class="cd border-bottom text-right text-featured"><?php echo $dadosboleto["valor_boleto"]?></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td class="td_line"><table style="width:200mm;"  border="0" cellspacing="0" cellpadding="0">
                <tr class="tr_t">
                  <td style="width:39.06mm;" class="ct border-left border-right ct">(-) descontos/abatimentos</td>
                  <td style="width:39.08mm;" class="border-right ct">(-) outras deduções</td>
                  <td style="width:39.08mm;" class="border-right ct">(+) juros/multa</td>
                  <td style="width:39.08mm;" class="border-right ct">(+) outros acrescimos</td>
                  <td style="width:40mm;" bgcolor="#f5f5f5" class="ct">(+) valor pago</td>
                </tr>
                <tr class="tr_c">
                  <td style="width:39.06mm;" class="cd border-bottom border-left border-right "></td>
                  <td style="width:39.08mm;" class="cd border-bottom border-right text-right">&nbsp;</td>
                  <td style="width:39.08mm;" class="cd border-bottom border-right text-center">&nbsp;</td>
                  <td style="width:39.08mm;" class="cd border-bottom border-right text-center ">&nbsp;</td>
                  <td style="width:40mm;" bgcolor="#f5f5f5" class="cd border-bottom  ">&nbsp;</td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td class="td_line"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr class="tr_t">
                  <td class="ct border-left  ct">Pagador
                  </td>
                </tr>
                <tr class="tr_c">
                  <td class="cd border-bottom border-left text-left text-upper"><?php echo $dadosboleto["sacado"]?></td>

                </tr>
              </table></td>
            </tr>
		</table>
        <div class="devider" style=" margin-top:5mm;">Corte na linha pontilhada</div>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-top:7mm;width:200mm;">
            <tr>
              <td>
              <table style="width:200mm;" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="20%" class="border-bottom-header">
                  <img src="../../imagens/756.jpg" alt="Santander" width="91" height="22" style="padding:1px;">
                  </td>
                  <td width="9%" class="cod_banco border-bottom border-left border-right" ><?php echo $dadosboleto["codigo_banco_com_dv"]?></td>
                  <td width="71%" class="linhadigitavel border-bottom" ><?php echo $dadosboleto["linha_digitavel"]?></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td class="td_line">
              <table style="width:200mm;" border="0" cellspacing="0" cellpadding="0">
                <tr class="tr_t">
                  <td style="width:160mm;" class="ct border-left border-right ct">local de pagamento</td>
                  <td style="width:40mm;"  bgcolor="#f5f5f5" class="ct">vencimento</td>
                </tr>
                <tr class="tr_c">
                  <td style="width:160mm;" class="cd border-bottom border-left border-right text-left text-upper text-featured2"><?php echo $dadosboleto["local_pgto"]?></td>
                  <td style="width:40mm;" bgcolor="#f5f5f5" class="cd border-bottom text-right text-featured"><?php echo $dadosboleto["data_vencimento"]?></td>
                </tr>
              </table>
              </td>
            </tr>
            <tr>
              <td class="td_line">
              <table style="width:200mm;" border="0" cellspacing="0" cellpadding="0">
                <tr class="tr_t">
                  <td style="width:160mm;" class="ct border-left border-right ct">Beneficiario</td>
                  <td style="width:40mm;" class="ct">agencia / codigo cedente</td>
                </tr>
                <tr class="tr_c">
                  <td style="width:160mm;"  class="cd border-left border-right text-left text-upper text-featured2">
				  <?php echo $dadosboleto["cedente"]?> (<?php echo  isset($dadosboleto["cpf_cnpj"]) ? $dadosboleto["cpf_cnpj"] : '' ?>)
                  </td>
                  <td style="width:40mm;" class="cd border-bottom text-right "><?php echo $dadosboleto["agencia_codigo"]?></td>
                </tr>
              </table>
              <table style="width:200mm;" border="0" cellspacing="0" cellpadding="0">
                <tr class="tr_t">
                  <td style="width:160mm;" class="ct border-left border-right ct">Endereço Beneficiario</td>
                  <td style="width:40mm;" class="ct">Nosso Numero</td>
                </tr>
                <tr class="tr_c">
                  <td style="width:160mm;"  class="cd border-bottom border-left border-right text-left text-upper text-featured2"><?php echo $dadosboleto["endereco"] ." - ". $dadosboleto["cidade_uf"];?> </td>
                  <td style="width:40mm;" class="cd border-bottom text-right "><?php echo $dadosboleto["nosso_numero"]?></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td class="td_line"><table style="width:200mm;" border="0" cellspacing="0" cellpadding="0">
                <tr class="tr_t">
                  <td style="width:160mm; vertical-align:middle;" rowspan="2" class="border-left border-right ct border-bottom"><table style="width:160mm;"  border="0" cellspacing="0" cellpadding="0">
                    <tr class="tr_t">
                      <td style="width:30mm;" class="ct border-right">data documento</td>
                      <td style="width:40mm;" class="border-right ct"> nº documento</td>
                      <td style="width:30mm;" class="border-right ct">especie doc</td>
                      <td style="width:20mm;" class="border-right ct">aceite</td>
                      <td style="width:30mm;" class="ct">data processamento</td>
                    </tr>
                    <tr class="tr_c">
                      <td class="cd border-right "><?php echo $dadosboleto["data_documento"]?></td>
                      <td class="cd border-right text-right"><?php echo $dadosboleto["numero_documento"]?></td>
                      <td class="cd border-right text-center"><?php echo $dadosboleto["especie_doc"]?></td>
                      <td class="cd border-right text-center "><?php echo $dadosboleto["aceite"]?></td>
                      <td class="cd "><?php echo $dadosboleto["data_processamento"]?></td>
                    </tr>
                  </table></td>
                  <td bgcolor="#f5f5f5" class="ct" style="width:40mm;">(=) valor documento</td>
                </tr>
                <tr class="tr_c">
                  <td bgcolor="#f5f5f5" class="cd border-bottom text-right text-featured" style="width:40mm;"><?php echo $dadosboleto["valor_boleto"]?></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td class="td_line"><table style="width:200mm;" border="0" cellspacing="0" cellpadding="0">
                <tr class="tr_t">
                  <td style="width:160mm;" rowspan="2" class="border-left border-right ct border-bottom"><table style="width:160mm;"  border="0" cellspacing="0" cellpadding="0">
                    <tr class="tr_t">
                      <td style="width:30mm;" class="ct border-right ct">uso do banco</td>
                      <td style="width:40mm;" class="border-right ct"> carteira</td>
                      <td style="width:30mm;" class="border-right ct">especie</td>
                      <td style="width:20mm;" class="border-right ct">quantidade</td>
                      <td style="width:30mm;" class="ct">x valor</td>
                    </tr>
                    <tr class="tr_c">
                      <td class="cd border-right "></td>
                      <td class="cd border-right text-center"><?php echo $dadosboleto["carteira_descricao"]?> </td>
                      <td class="cd border-right text-center"><?php echo $dadosboleto["especie"]?></td>
                      <td class="cd border-right text-center "><?php echo $dadosboleto["quantidade"]?></td>
                      <td class="cd  "><?php echo $dadosboleto["valor_unitario"]?></td>
                    </tr>
                  </table></td>
                  <td style="width:40mm;" class="ct">(-) desconto / abatimento</td>
                </tr>
                <tr class="tr_c">
                  <td style="width:40mm;" class="cd border-bottom text-right ">&nbsp;</td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td class="td_line"><table style="width:200mm;" border="0" cellspacing="0" cellpadding="0">
                <tr class="tr_t">
                  <td  class="cd border-left border-right text-left text-upper" style="width:160mm;">Intruções</td>
                  <td style="width:40mm;" class="ct">(-) outras deduções</td>
                </tr>
                <tr class="tr_c">
                  <td rowspan="3"  class="cd border-bottom border-left border-right text-left text-upper" style="width:160mm;"><p><?php echo $dadosboleto["instrucoes1"]; ?></p>
                    <p><?php echo $dadosboleto["instrucoes2"]; ?></p>
                    <p><?php echo $dadosboleto["inst_adcional"]; ?></p></td>
                  <td style="width:40mm;" class="cd border-bottom text-right ">&nbsp;</td>
                </tr>
                <tr class="tr_t">
                  <td style="width:40mm;" class="ct">(+) juros / multa</td>
                </tr>
                <tr class="tr_c">
                  <td style="width:40mm;" class="cd border-bottom text-right ">&nbsp;</td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td class="td_line"><table style="width:200mm;" border="0" cellspacing="0" cellpadding="0">
                <tr class="tr_t1">
                  <td style="width:160mm;" class="ct border-left border-right ct">Pagador</td>
                  <td style="width:40mm;" class="ct">(+) outros accrescimos</td>
                </tr>
                <tr class="tr_c1">
                  <td rowspan="3"  class="cd border-bottom border-left border-right text-left text-upper" style="width:160mm;"><p style="padding-top:3px; padding-left:6px; font-weight:bold;"><?php echo $dadosboleto["sacado"]?></p>
                    <p style="padding-left:6px;font-weight:bold;"><?php echo $dadosboleto["endereco1"]?></p>
                    <p style="padding-bottom:2px; padding-left:6px;font-weight:bold;"><?php echo $dadosboleto["endereco2"]?></p></td>
                  <td style="width:40mm;" class="cd border-bottom text-right ">&nbsp;</td>
                </tr>
                <tr class="tr_t1">
                  <td style="width:40mm;" class="ct">(=) valor cobrado</td>
                </tr>
                <tr class="tr_c1">
                  <td style="width:40mm;" class="cd border-bottom text-right ">&nbsp;</td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td class="td_line">
              <table style="width:200mm;" border="0" cellspacing="0" cellpadding="0">
                <tr class="tr_t">
                  <td class="ct" style="width:160mm;">
				<?php fbarcode_pdf($dadosboleto["codigo_barras"]); ?>

                  </td>
                  <td class="ct" style="width:40mm; vertical-align:text-top;">Autenticação Mecanica</td>
                </tr>
              </table>
              </td>
            </tr>
          </table>
    </div>

</div>

<?php

if($dados_titulo[0]->tp_sacado == 02){


// recupera os dados do convenio
$queryfatu=associados::find_by_sql("SELECT SQL_CACHE
                                       faturamentos.id,
                                       faturamentos.referencia,
                                       associados.matricula,
                                       associados.nm_associado,
                                       faturamentos.dt_vencimento,
                                       faturamentos.valor,
                                       (SELECT sum(valor) FROM procedimentos WHERE valor > 0 and matricula  = faturamentos.matricula and status='1' and faturamentos_id = faturamentos.id GROUP BY faturamentos_id) as valor_pro
                                    FROM
                                        faturamentos
                                    LEFT JOIN associados ON associados.matricula = faturamentos.matricula

                                    WHERE
                                      faturamentos.convenios_id = '".$FRM_convenio_id."'
                                      AND faturamentos.status = '0'
                                      AND faturamentos.tipo_parcela='M'
                                      AND faturamentos.referencia='".$FRM_referencia."'
                                      AND associados.status='1'
                                      ORDER BY faturamentos.matricula");

?>
<div class="container">

<table style="width:205mm;">

  <thead >
    <tr>
        <td align="center" colspan="6" style=" font-weight: bold;; font-size: 18px; text-transform: uppercase;" ><?php echo $dadosboleto["identificacao"]; ?></td>
    </tr>
     <tr>
        <td align="center" colspan="6" style="font-size:10pt;">CNPJ: <?php echo isset($dadosboleto["cpf_cnpj"]) ? $dadosboleto["cpf_cnpj"] : '' ?></td>
    </tr>
    <tr>
        <td align="center" colspan="6" style="font-size:10pt;">ENDEREÇO: <?php echo $dadosboleto["endereco"].' '.$dadosboleto["cidade_uf"]; ?></td>
    </tr>
    <tr>
        <td align="center" colspan="6" ><hr /></td>
    </tr>
 </thead>
 </table>
<table style="width:205mm;" class="ext_fat">
 <thead >
    <tr>
        <th align="center">Codígo</th>
        <th align="left"  >Nome Funcionario</th>
        <th align="center">Referencia</th>
        <th align="center">Mensalidade</th>
        <th align="center">Consulas/Exames</th>
        <th align="center">Sub-Total</th>
    </tr>
    </thead>
  <tbody style="font-size: 9px;">
<?php

// laço que loopa os lançamentos dos convenios  agrupando por data
$listfat= new ArrayIterator($queryfatu);
$lf=1; // linha de titulos

$vl_t="";
$valor_t_pro="";

while($listfat->valid()):

$ref = new ActiveRecord\DateTime($listfat->current()->referencia);
$dtvenc = new ActiveRecord\DateTime($listfat->current()->dt_vencimento);

?>
    <tr style="line-height: 30px;">

        <td align="center" style="border-bottom:1px dashed #e5e5e5"><?php echo $listfat->current()->matricula; ?></td>
        <td align="left"  style="border-bottom:1px dashed #e5e5e5"><?php echo strtoupper($listfat->current()->nm_associado); ?></td>
        <td align="center"  style="border-bottom:1px dashed #e5e5e5"><?php echo tool::Referencia($ref->format('Ymd'),"/"); ?></td>
        <td align="center"  style="border-bottom:1px dashed #e5e5e5">

           <?php echo number_format($listfat->current()->valor,2,",","."); ?>

        </td>
        <td align="center"  style="border-bottom:1px dashed #e5e5e5">

           <?php echo number_format($listfat->current()->valor_pro,2,",","."); ?>

        </td>
        <td align="center"  style="border-bottom:1px dashed #e5e5e5"><?php echo number_format($listfat->current()->valor_pro+$listfat->current()->valor,2,",","."); ?>
        </td>
    </tr>
  <?php

$valor_t_pro    +=  $listfat->current()->valor_pro;
$vl_t           +=  $listfat->current()->valor;

$lf++;
$listfat->next();
endwhile;
?>
  </tbody>
  <tfoot>
    <tr style="line-height: 30px;">
        <th align="center"  style="width:90px;border-bottom:1px dashed #e5e5e5">Total</th>
        <th align="left"  style="width:300px;border-bottom:1px dashed #e5e5e5"></th>
        <th align="center"  style="width:90px;border-bottom:1px dashed #e5e5e5" ></th>
        <th align="center"  style="width:90px;border-bottom:1px dashed #e5e5e5" ></th>
        <th align="center"  style="width:120px;border-bottom:1px dashed #e5e5e5"></th>
        <th align="center"  style="border-bottom:1px dashed #e5e5e5" ><?php echo number_format($vl_t+$valor_t_pro,2,',','.'); ?></th>
    </tr>
  </tfoot>
 </table>
 </div>
<?php

}

?>

</body>
</html>
</div>
<?php

$html = ob_get_clean();


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

