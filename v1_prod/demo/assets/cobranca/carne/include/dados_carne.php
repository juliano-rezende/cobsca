<?php


echo'<div class="tabs-spacer" style="display:none;">';

//RECUPERA OS DADOS DO TITULO
$dados_titulo   = faturamentos::find_by_sql("SELECT
associados.matricula,
associados.convenios_id,
associados.empresas_id,
associados.nm_associado,
associados.cpf,
logassoc.descricao AS ass_num,
logassoc.descricao AS ass_logradouro,
logassoc.cep AS ass_cep,
baiassoc.descricao AS ass_bairro,
cidassoc.descricao AS ass_cidade,
estassoc.sigla AS ass_estado,

faturamentos.id AS fat_id,
faturamentos.dt_vencimento,
faturamentos.valor,
faturamentos.tipo_parcela,
faturamentos.matricula,
faturamentos.referencia,
faturamentos.tipo_parcela,

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
configs.juros,
configs.multa

FROM

faturamentos
LEFT JOIN associados ON associados.matricula = faturamentos.matricula
LEFT JOIN empresas ON empresas.id = faturamentos.empresas_id
LEFT JOIN logradouros AS logassoc ON logassoc.id = associados.logradouros_id
LEFT JOIN bairros AS baiassoc ON baiassoc.id = logassoc.bairros_id
LEFT JOIN cidades AS cidassoc ON cidassoc.id = logassoc.cidades_id
LEFT JOIN estados AS estassoc ON estassoc.id = logassoc.estados_id
LEFT JOIN logradouros AS logemp ON logemp.id = empresas.logradouros_id
LEFT JOIN bairros AS baiemp ON baiemp.id = logemp.bairros_id
LEFT JOIN cidades AS cidemp ON cidemp.id = logemp.cidades_id
LEFT JOIN estados AS estemp ON logemp.id = logemp.estados_id
LEFT JOIN configs ON configs.empresas_id = faturamentos.empresas_id

WHERE

faturamentos.id = '{$id_faturamento}'");

echo'</div>';

// DADOS DO BOLETO PARA O SEU CLIENTE
$dias_de_prazo_para_pagamento   = 0; // dias para pagamento
$taxa_boleto          = 0; // se houver taixa do boleto

//date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006";
$dtvenc               = new ActiveRecord\DateTime($dados_titulo[0]->dt_vencimento);
$data_venc            = $dtvenc->format('d/m/Y');
$dtref               = new ActiveRecord\DateTime($dados_titulo[0]->referencia);
$data_ref            = $dtvenc->format('d/m/Y');


// Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
$valor_cobrado        = $dados_titulo[0]->valor;
$valor_cobrado        = str_replace(",", ".",$valor_cobrado);
$valor_boleto         = number_format($valor_cobrado+$taxa_boleto, 2, ',', '');

$dadosboleto["nosso_numero"] = $dados_titulo[0]->fat_id; // Deve informar um numero sequencial a ser passada a função
// abaixo, Até 6 dígitos


/*************************************************************************
 * +++
 *************************************************************************/


$dadosboleto["matricula"]  = tool::CompletaZeros(2,$dados_titulo[0]->empresas_id).".".
                             tool::CompletaZeros(2,$dados_titulo[0]->convenios_id).".".tool::CompletaZeros(11, $dados_titulo[0]->matricula);


// Num do pedido ou do documento


$dadosboleto["numero_documento"]  = tool::CompletaZeros(11,$dados_titulo[0]->fat_id); // Num do pedido ou do documento
$dadosboleto["data_vencimento"]   = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
$dadosboleto["referencia"]   = $data_ref; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
$dadosboleto["data_documento"]    = date("d/m/Y"); // Data de emissão do Boleto
$dadosboleto["data_processamento"]= date("d/m/Y"); // Data de processamento do boleto (opcional)
$dadosboleto["valor_boleto"]      = $valor_boleto;  // Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula

// DADOS DO SEU CLIENTE

$dadosboleto["sacado"]        = utf8_encode(ucwords($dados_titulo[0]->nm_associado))." ".tool::MascaraCampos("???.???.???-??",$dados_titulo[0]->cpf);
$dadosboleto["endereco1"]     = utf8_encode(ucwords($dados_titulo[0]->ass_logradouro))." nº ".$dados_titulo[0]->ass_num   ." ".
                                utf8_encode(ucwords($dados_titulo[0]->ass_bairro));

$dadosboleto["endereco2"]     = " ".utf8_encode(ucwords($dados_titulo[0]->ass_cidade))." / ". strtoupper($dados_titulo[0]->ass_estado)." CEP ".
                                tool::MascaraCampos("?????-???",$dados_titulo[0]->ass_cep);

// INFORMACOES PARA O CLIENTE
$dadosboleto["local_pgto"]      =  "Núcleo";
$dadosboleto["demonstrativo1"]  = "Pagamento de Compra na Loja Nonononono";
$dadosboleto["demonstrativo2"]  = "Mensalidade referente a nonon nonooon nononon<br>Taxa bancária - R$ ".number_format($taxa_boleto, 2, ',', '');
$dadosboleto["demonstrativo3"]  = "BoletoPhp - http://www.boletophp.com.br";

//CALCULA O VALOR DE JUROS
$jurosmes       = $dados_titulo[0]->juros;
$jurosdia       = ($jurosmes/30);
$juros          = ($jurosdia/100);
$juros_ao_dia  = ($dados_titulo[0]->valor * $juros) ;

$juros_ao_dia  = number_format($juros_ao_dia, 2, ',', '.');



$multa  = ($dados_titulo[0]->valor * ($dados_titulo[0]->multa/100)) ;
$vlr_multa = number_format($multa, 2, ',', '.');


// INSTRUÇÕES PARA O CAIXA
$inst1 = "Após o vencimento cobrar juros de ";
$inst2 = "Após o vencimento cobrar cobrar multa de ";

$dadosboleto["instrucoes1"]     = strtoupper($inst1." R$ ".$juros_ao_dia." ao dia.");
$dadosboleto["instrucoes2"]     = strtoupper($inst2." R$ ".$vlr_multa."");
$dadosboleto["inst_adcional"]   = "";



// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["quantidade"]      = "";
$dadosboleto["valor_unitario"]  = "";
$dadosboleto["aceite"]          = "";
$dadosboleto["especie"]         = "";
$dadosboleto["especie_doc"]     = "PGTO CARNÊ";


// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //
// DADOS ESPECIFICOS DO SANTANDER
$dadosboleto["numero_parcela"]      = "001";

// DADOS DA SUA CONTA - BANCO SANTANDER
$dadosboleto["agencia"]   = ""; // Num da agencia, sem digito
$dadosboleto["conta"]     = "";  // Num da conta, sem digito
$dadosboleto["conta_dv"]  = "";   // Digito do Num da conta

// DADOS PERSONALIZADOS - SANTANDER
$dadosboleto["codigo_cliente"]      = ""; // Código Cedente do Cliente, com 6 digitos (Somente Números)
$dadosboleto["ponto_venda"]         = "";// Ponto de Venda = Agencia
$dadosboleto["carteira"]            = "";  // Modalidade da carteira
//if($dados_titulo[0]->desc_carteira_cob == "CR"){$descricao_cart="COBRANCA SIMPLES RCR";}

if($dados_titulo[0]->tipo_parcela == "M"){$descricao_cart="MENSALIDADE";}else{$descricao_cart="ADESÃO";}

$dadosboleto["carteira_descricao"]  = $descricao_cart;  // Descrição da Carteira
$dadosboleto["numero_parcela"]      = "001";

// SEUS DADOS
$dadosboleto["logomarca"]     = tool::CompletaZeros(3,$dados_titulo[0]->logomarca);
$dadosboleto["identificacao"] = utf8_encode($dados_titulo[0]->nm_fantasia);
$dadosboleto["cpf_cnpj"]      = tool::MascaraCampos("??.???.???/????-??",$dados_titulo[0]->cnpj);

$dadosboleto["endereco"]      = strtoupper( utf8_encode($dados_titulo[0]->emp_complemento ." ". $dados_titulo[0]->emp_logradouro ));

//$dadosboleto["endereco"]      = strtoupper(utf8_encode($dados_titulo[0]->emp_complemento))." ".
//   strtoupper($dados_titulo[0]->emp_logradouro ." Nº ".
//      $dados_titulo[0]->emp_num." ".
//      strtoupper( utf8_encode($dados_titulo[0]->emp_bairro) );

$dadosboleto["cidade_uf"]     = strtoupper(utf8_encode($dados_titulo[0]->emp_cidade))." - ". strtoupper($dados_titulo[0]->emp_estado)." CEP ". $dados_titulo[0]->emp_cep;

$dadosboleto["cedente"]       = strtoupper(utf8_encode($dados_titulo[0]->nm_fantasia));




?>
