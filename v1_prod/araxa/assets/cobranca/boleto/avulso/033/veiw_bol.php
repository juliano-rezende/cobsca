<?php
ob_start();
header("Content-type: text/html;charset=utf-8");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Data no passado
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Sempre modificado
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
include("../../../../../sessao.php");
include("../../../../../conexao.php");
$cfg->set_model_directory('../../../../../models/');

// +----------------------------------------------------------------------+
// | BoletoPhp - Versão Beta                                              |
// +----------------------------------------------------------------------+
// | Este arquivo está disponível sob a Licença GPL disponível pela Web   |
// | em http://pt.wikipedia.org/wiki/GNU_General_Public_License           |
// | Você deve ter recebido uma cópia da GNU Public License junto com     |
// | esse pacote; se não, escreva para:                                   |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// | Originado do Projeto BBBoletoFree que tiveram colaborações de Daniel |
// | William Schultz e Leandro Maniezo que por sua vez foi derivado do    |
// | PHPBoleto de João Prado Maia e Pablo Martins F. Costa                |
// |                                                                      |
// | Se vc quer colaborar, nos ajude a desenvolver p/ os demais bancos :-)|
// | Acesse o site do Projeto BoletoPhp: www.boletophp.com.br             |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// | Equipe Coordenação Projeto BoletoPhp: <boletophp@boletophp.com.br>   |
// | Desenvolvimento Boleto BANCOOB/SICOOB: Marcelo de Souza              |
// | Ajuste de algumas rotinas: Anderson Nuernberg                        |
// +----------------------------------------------------------------------+


// ------------------------- DADOS DINÂMICOS DO SEU CLIENTE PARA A GERAÇÃO DO BOLETO (FIXO OU VIA GET) -------------------- //
// Os valores abaixo podem ser colocados manualmente ou ajustados p/ formulário c/ POST, GET ou de BD (MySql,Postgre,etc)	//


/* VALORES VINDOS DA REQUISIÇÃO */
$FRM_convenio_id        =   isset( $_GET['convenio_id'])    ? $_GET['convenio_id']  : tool::msg_erros("O Campo convenio_id é Obrigatorio.");
$FRM_referencia         =   isset( $_GET['referencia'])     ? $_GET['referencia']   : tool::msg_erros("O Campo referencia é Obrigatorio.");
$FRM_id_titulo 			    = 	isset( $_GET['t_id'])  			? $_GET['t_id'] 		: tool::msg_erros("Campo invalido.");



$id_titulo						= $FRM_id_titulo;

//RECUPERA OS DADOS DO TITULO
$dados_titulo					= titulos::find_by_sql("SELECT
														                  titulos_bancarios.id,
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
                                              titulos_bancarios.cpfcnpjsacado as cpfcnpjsacado,
                                              contas_bancarias.agencia,
                                              contas_bancarias.conta,
                                              contas_bancarias_cobs.cod_cedente,
                                              contas_bancarias.dv_conta,
                                              contas_bancarias_cobs.cod_cedente as convenio,
                                              contas_bancarias_cobs.dv_cod_cedente as dv_convenio,
                                              contas_bancarias_cobs.carteira_cobranca as carteira,
                                              contas_bancarias_cobs.desc_carteira_cob,
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
                                              empresas.num,
                                              empresas.id as logo,
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
$valor_boleto					  = number_format($valor_cobrado+$taxa_boleto, 2, ',', '');

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

$dadosboleto["nosso_numero"] 		   = $dados_titulo[0]->nosso_numero; // Deve informar um numero sequencial a ser passada a função abaixo, Até 6 dígitos
$dadosboleto["numero_documento"] 	 = $dados_titulo[0]->numero_doc;	// Num do pedido ou do documento
$dadosboleto["data_vencimento"] 	 = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
$dadosboleto["data_documento"] 		 = date("d/m/Y"); // Data de emissão do Boleto
$dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)

$dadosboleto["valor_boleto"] 		   = $valor_boleto; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula

// DADOS DO SEU CLIENTE
if(strlen($dados_titulo[0]->cpfcnpjsacado)>11){
	$cpfcnpj=tool::MascaraCampos("??.???.???/????-??",$dados_titulo[0]->cpfcnpjsacado);
}else{
	$cpfcnpj=tool::MascaraCampos("???.???.???-??",$dados_titulo[0]->cpfcnpjsacado);
}

$dadosboleto["sacado"] 				= utf8_encode(ucwords($dados_titulo[0]->sacado))." ".$cpfcnpj;
$dadosboleto["endereco1"] 			= utf8_encode(ucwords($dados_titulo[0]->logradouro_sacado))." nº ".$dados_titulo[0]->num_sacado." ".
								  	  utf8_encode(ucwords($dados_titulo[0]->bairro_sacado));

$dadosboleto["endereco2"] 			= utf8_encode(ucwords($dados_titulo[0]->cidade_sacado))." / ".
											  	  strtoupper($dados_titulo[0]->uf_sacado)." CEP ".
											  	  tool::MascaraCampos("?????-???",$dados_titulo[0]->cep_sacado);

// INFORMACOES PARA O CLIENTE

$dadosboleto["demonstrativo1"] 		= "Faturamento";
$dadosboleto["demonstrativo2"] 		= "Mensalidades e Serviços referente ".tool::Referencia($FRM_referencia,"/");
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
// DADOS ESPECIFICOS DO SANTANDER
$dadosboleto["numero_parcela"]      = "001";
// DADOS DA SUA CONTA - BANCO SANTANDER
$dadosboleto["agencia"]   = $dados_titulo[0]->agencia; // Num da agencia, sem digito
$dadosboleto["conta"]     = $dados_titulo[0]->conta;  // Num da conta, sem digito
$dadosboleto["conta_dv"]  = $dados_titulo[0]->dv_conta;   // Digito do Num da conta

// DADOS PERSONALIZADOS - SANTANDER
$dadosboleto["codigo_cliente"]      = $dados_titulo[0]->cod_cedente; // Código Cedente do Cliente, com 6 digitos (Somente Números)
$dadosboleto["ponto_venda"]         = $dados_titulo[0]->agencia; // Ponto de Venda = Agencia
$dadosboleto["carteira"]            = $dados_titulo[0]->carteira;  // Modalidade da carteira
if($dados_titulo[0]->desc_carteira_cob == "CR"){$descricao_cart="COBRANCA SIMPLES RCR";}
if($dados_titulo[0]->desc_carteira_cob == "SR"){$descricao_cart="COBRANCA  SIMPLES CSR";}
$dadosboleto["carteira_descricao"]  =$descricao_cart;  // Descrição da Carteira
$dadosboleto["numero_parcela"]      = "001";


// SEUS DADOS
$dadosboleto["logomarca"]     = tool::CompletaZeros(3,$dados_titulo[0]->logo);
$dadosboleto["identificacao"] = utf8_encode($dados_titulo[0]->nm_fantasia);
$dadosboleto["cpf_cnpj"]      = tool::MascaraCampos("??.???.???/????-??",$dados_titulo[0]->cnpjbeneficiario);

$dadosboleto["endereco"]      = strtoupper(utf8_encode($dados_titulo[0]->complemento))." ".
                                ucfirst($dados_titulo[0]->logradouro ." ". $dados_titulo[0]->compl_end )." Nº ".$dados_titulo[0]->num." ".
                                strtoupper(utf8_encode($dados_titulo[0]->bairro));

$dadosboleto["cidade_uf"]     = strtoupper(utf8_encode($dados_titulo[0]->cidade))." / ".
                                strtoupper($dados_titulo[0]->uf)." CEP ".
                                $dados_titulo[0]->cep;

$dadosboleto["cedente"]       = strtoupper(utf8_encode($dados_titulo[0]->razao_social));



// NÃO ALTERAR!
include("include/funcoes_santander.php");
include("include/layout_santander.php");

$Update_titulo=titulos::find($id_titulo);
// altera o status da parcela
$Update_titulo->update_attributes(
array(
'linha_digitavel'=>tool::limpaString($dadosboleto["linha_digitavel"]),
'stflagimp'=>1,'dv_nosso_numero'=>$dv_nosso_numero
));

// quebra de pagina
echo '<div style="page-break-after: always;"></div> ';

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

// quebra de pagina
echo '<div style="page-break-after: always;"></div> ';
include ("include/list_assoc.php");
?>
 <script type="text/javascript" charset="utf-8" async defer>
     window.print();
 </script>