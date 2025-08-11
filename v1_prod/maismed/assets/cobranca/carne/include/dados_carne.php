<?php

echo '<div class="tabs-spacer" style="display:none;">';

$dados_titulo = faturamentos::find_by_sql("SELECT
    associados.matricula,
    associados.convenios_id,
    associados.empresas_id,
    associados.nm_associado,
    associados.dt_cadastro,
    associados.cpf,
    associados.cod_mais_med,
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
echo '</div>';

$dias_de_prazo_para_pagamento = 0;
$taxa_boleto = 0;


$dtvenc = new ActiveRecord\DateTime($dados_titulo[0]->dt_vencimento);
$data_venc = $dtvenc->format('d/m/Y');
$dtref = new ActiveRecord\DateTime($dados_titulo[0]->referencia);
$data_ref = $dtvenc->format('m/Y');
$data_ref_linha_digitavel = $dtref->format('my');

$dt_cad = new ActiveRecord\DateTime($dados_titulo[0]->dt_cadastro);
$dt_cadastro = $dtvenc->format('ym');


$valor_cobrado = $dados_titulo[0]->valor;
$valor_cobrado = str_replace(",", ".", $valor_cobrado);
$valor_boleto = number_format($valor_cobrado + $taxa_boleto, 2, ',', '');

$dadosboleto["nosso_numero"] = $dados_titulo[0]->fat_id;


$dadosboleto["matricula"] = tool::CompletaZeros(2, $dados_titulo[0]->empresas_id) . "." .
    tool::CompletaZeros(2, $dados_titulo[0]->convenios_id) . "." . tool::CompletaZeros(11, $dados_titulo[0]->matricula);


$dadosboleto["numero_documento"] = tool::CompletaZeros(11, $dados_titulo[0]->fat_id);
$dadosboleto["data_vencimento"] = $data_venc;
$dadosboleto["referencia"] = $data_ref;
$dadosboleto["data_documento"] = date("d/m/Y");
$dadosboleto["data_processamento"] = date("d/m/Y");
$dadosboleto["valor_boleto"] = $valor_boleto;


$dadosboleto["sacado"] = (ucwords($dados_titulo[0]->nm_associado)) . " " . tool::MascaraCampos("???.???.???-??", $dados_titulo[0]->cpf);
$dadosboleto["cod_mais_med"] = $dados_titulo[0]->cod_mais_med;
$dadosboleto["endereco1"] = utf8_encode(ucwords($dados_titulo[0]->ass_logradouro)) . " nº " . $dados_titulo[0]->ass_num . " " .
    utf8_encode(ucwords($dados_titulo[0]->ass_bairro));


$dadosboleto["endereco2"] = " " . utf8_encode(ucwords($dados_titulo[0]->ass_cidade)) . " / " . strtoupper($dados_titulo[0]->ass_estado) . " CEP " .
    tool::MascaraCampos("?????-???", $dados_titulo[0]->ass_cep);


$dadosboleto["local_pgto"] = "Núcleo";
$dadosboleto["demonstrativo1"] = "Pagamento de Compra na Loja Nonononono";
$dadosboleto["demonstrativo2"] = "Mensalidade referente a nonon nonooon nononon<br>Taxa bancária - R$ " . number_format($taxa_boleto, 2, ',', '');
$dadosboleto["demonstrativo3"] = "BoletoPhp - http://www.boletophp.com.br";


$jurosmes = $dados_titulo[0]->juros;
$jurosdia = ($jurosmes / 30);
$juros = ($jurosdia / 100);
$juros_ao_dia = ($dados_titulo[0]->valor * $juros);

$juros_ao_dia = number_format($juros_ao_dia, 2, ',', '.');


$multa = ($dados_titulo[0]->valor * ($dados_titulo[0]->multa / 100));
$vlr_multa = number_format($multa, 2, ',', '.');


$inst1 = "Após o vencimento cobrar juros de ";
$inst2 = "Após o vencimento cobrar cobrar multa de ";

$dadosboleto["instrucoes1"] = strtoupper($inst1 . " R$ " . $juros_ao_dia . " ao dia.");
$dadosboleto["instrucoes2"] = strtoupper($inst2 . " R$ " . $vlr_multa . "");
$dadosboleto["inst_adcional"] = "";


$dadosboleto["quantidade"] = "";
$dadosboleto["valor_unitario"] = "";
$dadosboleto["aceite"] = "";
$dadosboleto["especie"] = "";
$dadosboleto["especie_doc"] = "PGTO CARNÊ";


$dadosboleto["numero_parcela"] = "001";


$dadosboleto["agencia"]     = "";
$dadosboleto["conta"]       = "";
$dadosboleto["conta_dv"]    = "";


$dadosboleto["codigo_cliente"]  = "";
$dadosboleto["ponto_venda"]     = "";
$dadosboleto["carteira"]        = "";

if ($dados_titulo[0]->tipo_parcela == "M") {
    $descricao_cart = "MENSALIDADE";
} else {
    $descricao_cart = "ADESÃO";
}

$dadosboleto["carteira_descricao"]  = $descricao_cart;
$dadosboleto["numero_parcela"]      = "001";

// SEUS DADOS
$dadosboleto["logomarca"]       = tool::CompletaZeros(3, $dados_titulo[0]->logomarca);
$dadosboleto["identificacao"]   = utf8_encode($dados_titulo[0]->nm_fantasia);
$dadosboleto["cpf_cnpj"]        = tool::MascaraCampos("??.???.???/????-??", $dados_titulo[0]->cnpj);
$dadosboleto["endereco"]        = strtoupper(utf8_encode($dados_titulo[0]->emp_complemento . " " . $dados_titulo[0]->emp_logradouro));
$dadosboleto["cidade_uf"]       = strtoupper(utf8_encode($dados_titulo[0]->emp_cidade)) . " - " . strtoupper($dados_titulo[0]->emp_estado) . " CEP " . $dados_titulo[0]->emp_cep;
$dadosboleto["cedente"]         = strtoupper(utf8_encode($dados_titulo[0]->nm_fantasia));
$dadosboleto["linhadigital"]    = $dt_cadastro . "." . tool::CompletaZeros(2, $dados_titulo[0]->empresa_id) . "." . tool::CompletaZeros(11, $dados_titulo[0]->matricula) . "." . $data_ref_linha_digitavel . "." . preg_replace('/[^0-9]/', '', tool::CompletaZeros(9, ($valor_boleto)));
$dadosboleto["codigo_barras"]   = $dt_cadastro . tool::CompletaZeros(2, $dados_titulo[0]->empresa_id) . tool::CompletaZeros(11, $dados_titulo[0]->matricula) . $data_ref_linha_digitavel . preg_replace('/[^0-9]/', '', tool::CompletaZeros(9, ($valor_boleto)));
?>
