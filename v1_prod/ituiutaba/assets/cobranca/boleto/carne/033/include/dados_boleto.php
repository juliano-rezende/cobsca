<?php



echo'<div class="tabs-spacer" style="display:none;">';


$queryfatu=faturamentos::find($id_faturamento);

$id_titulo=$queryfatu->titulos_bancarios_id;

//RECUPERA OS DADOS DO TITULO
$dados_titulo         = titulos::find_by_sql("SELECT
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
                                              LEFT JOIN contas_bancarias      ON contas_bancarias.id      = titulos_bancarios.contas_bancarias_id
                                              LEFT JOIN contas_bancarias_cobs ON contas_bancarias_cobs.contas_bancarias_id = contas_bancarias.id
                                              LEFT JOIN empresas              ON empresas.id              = titulos_bancarios.empresas_id
                                              LEFT JOIN logradouros           ON logradouros.id           = empresas.logradouros_id
                                              LEFT JOIN bairros               ON bairros.id               = logradouros.bairros_id
                                              LEFT JOIN cidades               ON cidades.id               = logradouros.cidades_id
                                              LEFT JOIN estados               ON estados.id               = logradouros.estados_id
                                              LEFT JOIN configs               ON configs.empresas_id      = titulos_bancarios.empresas_id
                                            WHERE
                                              titulos_bancarios.id = '".$id_titulo."'");
echo'</div>';

// DADOS DO BOLETO PARA O SEU CLIENTE
$dias_de_prazo_para_pagamento   = 0; // dias para pagamento
$taxa_boleto          = 0; // se houver taixa do boleto

//date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006";
$dtvenc               = new ActiveRecord\DateTime($dados_titulo[0]->dt_vencimento);
$data_venc            = $dtvenc->format('d/m/Y');

// Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
$valor_cobrado        = $dados_titulo[0]->vlr_nominal;
$valor_cobrado        = str_replace(",", ".",$valor_cobrado);
$valor_boleto         = number_format($valor_cobrado+$taxa_boleto, 2, ',', '');


if(!function_exists('formata_numdoc'))
{
  function formata_numdoc($num,$tamanho)
  {
    while(strlen($num)<$tamanho)
    {
      $num="0".$num;
    }
  return $num;
  }
}

$dadosboleto["nosso_numero"] = $dados_titulo[0]->nosso_numero; // Deve informar um numero sequencial a ser passada a função abaixo, Até 6 dígitos


/*************************************************************************
 * +++
 *************************************************************************/


$dadosboleto["numero_documento"]  = $dados_titulo[0]->numero_doc; // Num do pedido ou do documento
$dadosboleto["data_vencimento"]   = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
$dadosboleto["data_documento"]    = date("d/m/Y"); // Data de emissão do Boleto
$dadosboleto["data_processamento"]= date("d/m/Y"); // Data de processamento do boleto (opcional)
$dadosboleto["valor_boleto"]      = $valor_boleto;  // Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula

// DADOS DO SEU CLIENTE

$dadosboleto["sacado"]        = utf8_encode(ucwords($dados_titulo[0]->sacado))." ".tool::MascaraCampos("???.???.???-??",$dados_titulo[0]->cpfcnpjsacado);
$dadosboleto["endereco1"]     = utf8_encode(ucwords($dados_titulo[0]->logradouro_sacado))." nº ".$dados_titulo[0]->num_sacado." ".
                                utf8_encode(ucwords($dados_titulo[0]->bairro_sacado));

$dadosboleto["endereco2"]     = " ".utf8_encode(ucwords($dados_titulo[0]->cidade_sacado))." / ".
                                strtoupper($dados_titulo[0]->uf_sacado)." CEP ".
                                tool::MascaraCampos("?????-???",$dados_titulo[0]->cep_sacado);

// INFORMACOES PARA O CLIENTE
$dadosboleto["local_pgto"]      =  ucwords($dados_titulo[0]->local_pgto);
$dadosboleto["demonstrativo1"]  = "Pagamento de Compra na Loja Nonononono";
$dadosboleto["demonstrativo2"]  = "Mensalidade referente a nonon nonooon nononon<br>Taxa bancária - R$ ".number_format($taxa_boleto, 2, ',', '');
$dadosboleto["demonstrativo3"]  = "BoletoPhp - http://www.boletophp.com.br";


//CALCULA O VALOR DE JUROS
 $jurosmes       = ($dados_titulo[0]->juros/12);
 $jurosdia       = ($jurosmes/30);
 $juros          = ($jurosdia/100);
 $juros_ao_dia  = ($dados_titulo[0]->vlr_nominal * $juros) ;


// INSTRUÇÕES PARA O CAIXA
$dadosboleto["instrucoes1"]     = strtoupper($dados_titulo[0]->inst1." ".number_format($juros_ao_dia,2,",",".")." ao dia.");
$dadosboleto["instrucoes2"]     = strtoupper($dados_titulo[0]->inst2." ".intval($dados_titulo[0]->multa))." %";
$dadosboleto["inst_adcional"]   = strtoupper($dados_titulo[0]->inst_adcional);

// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["quantidade"]      = "";
$dadosboleto["valor_unitario"]  = "";
$dadosboleto["aceite"]          = $dados_titulo[0]->aceite;
$dadosboleto["especie"]         = $dados_titulo[0]->especie;
$dadosboleto["especie_doc"]     = $dados_titulo[0]->especie_doc;


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
                                strtoupper($dados_titulo[0]->logradouro ." ". $dados_titulo[0]->compl_end )." Nº ".$dados_titulo[0]->num." ".
                                strtoupper(utf8_encode($dados_titulo[0]->bairro));

$dadosboleto["cidade_uf"]     = strtoupper(utf8_encode($dados_titulo[0]->cidade))." / ".
                                strtoupper($dados_titulo[0]->uf)." CEP ".
                                $dados_titulo[0]->cep;

$dadosboleto["cedente"]       = strtoupper(utf8_encode($dados_titulo[0]->razao_social));



// NÃO ALTERAR! ////////////////////////////////////////////////////////////////////////////////////

$codigobanco = "033"; //Antigamente era 353
$codigo_banco_com_dv = geraCodigoBanco($codigobanco);
$nummoeda = "9";
$fixo     = "9";   // Numero fixo para a posição 05-05
$ios    = "0";   // IOS - somente para Seguradoras (Se 7% informar 7, limitado 9%)
           // Demais clientes usar 0 (zero)
$fator_vencimento = fator_vencimento($dadosboleto["data_vencimento"]);

//valor tem 10 digitos, sem virgula
$valor = formata_numero($dadosboleto["valor_boleto"],10,0,"valor");
//Modalidade Carteira
$carteira = $dadosboleto["carteira"];
//codigocedente deve possuir 7 caracteres
$codigocliente = formata_numero($dadosboleto["codigo_cliente"],7,0);

//nosso número (sem dv) é 7 digitos
$nnum = formata_numero($dadosboleto["nosso_numero"],7,0);
//dv do nosso número
$dv_nosso_numero = modulo_11($nnum,9,0);
// nosso número (com dvs) são 13 digitos
$nossonumero = "00000".$nnum.$dv_nosso_numero;

$vencimento = $dadosboleto["data_vencimento"];

$vencjuliano = dataJuliano($vencimento);

// 43 numeros para o calculo do digito verificador do codigo de barras
$barra ="$codigobanco$nummoeda$fator_vencimento$valor$fixo$codigocliente$nossonumero$ios$carteira";


//$barra = "$codigobanco$nummoeda$fixo$codigocliente$nossonumero$ios$carteira";
$dv = digitoVerificador_barra($barra);

// Numero para o codigo de barras com 44 digitos
$linha = substr($barra,0,4) . $dv . substr($barra,4);


$dadosboleto["codigo_barras"] = $linha;
$dadosboleto["linha_digitavel"] = monta_linha_digitavel($linha);
$dadosboleto["nosso_numero"] = $nossonumero;
$dadosboleto["codigo_banco_com_dv"] = $codigo_banco_com_dv;


$Update_titulo=titulos::find($id_titulo);
// altera o status da parcela
$Update_titulo->update_attributes(
array(
'linha_digitavel'=>tool::limpaString($dadosboleto["linha_digitavel"]),
'stflagimp'=>1,'dv_nosso_numero'=>$dv_nosso_numero,
'cod_barras'=>$dadosboleto["codigo_barras"]
));

?>
