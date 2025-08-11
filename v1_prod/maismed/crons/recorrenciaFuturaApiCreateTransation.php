<?php

$Frm_cad = true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once "../sessao.php";
require_once("../conexao.php");
require_once("../config_ini.php");
$cfg->set_model_directory('../models/');

$FRM_matricula = 533;
$FRM_parcela_id = 22118;
$FRM_referencia_mes = date("Y-m")."-01";

$dadosAssociado = associados::find_by_sql("SELECT SQL_CACHE 
                                                  associados.nm_associado, associados.fone_cel,  associados.num,  associados.email,
                                                  associados.cpf, associados.dt_nascimento, logradouros.descricao as nm_logradouro,
                                                  logradouros.complemento,  logradouros.cep,  estados.sigla AS nm_estado,
                                                  cidades.descricao AS nm_cidade, bairros.descricao AS nm_bairro
                                                FROM
                                                  associados
                                                  LEFT JOIN logradouros ON logradouros.id = associados.logradouros_id
                                                  LEFT JOIN estados ON estados.id = logradouros.estados_id
                                                  LEFT JOIN cidades ON cidades.id = logradouros.cidades_id
                                                  LEFT JOIN bairros ON bairros.id = logradouros.bairros_id
                                                WHERE
                                                  associados.matricula = '" . $FRM_matricula . "'");

$end = $dadosAssociado[0]->complemento . " " . $dadosAssociado[0]->nm_logradouro . ", " . $dadosAssociado[0]->num;
$compl = "";
$dtNasc = new ActiveRecord\DateTime($dadosAssociado[0]->dt_nascimento);

$QueryDadosCob = faturamentos::find_by_sql("SELECT SQL_CACHE
                                              faturamentos.id AS faturamento_id,
                                              faturamentos.referencia,
                                              faturamentos.dt_vencimento,
                                              faturamentos.valor,
                                              dados_cobranca.dt_venc_p,
                                              dados_cobranca.valor,
                                              dados_cobranca.formascobranca_sys_id,
                                              dados_cobranca.planos_id,
                                              associados_cards.api_cob_card_id AS associados_card_id,
                                              dados_cobranca.api_cob_cliente_id
                                             FROM
                                              faturamentos
                                              LEFT JOIN titulos_bancarios ON titulos_bancarios.id = faturamentos.titulos_bancarios_id
                                              LEFT JOIN contas_bancarias ON contas_bancarias.id  = titulos_bancarios.contas_bancarias_id
                                              LEFT JOIN contas_bancarias_cobs ON contas_bancarias_cobs.contas_bancarias_id  = contas_bancarias.id
                                              LEFT JOIN dados_cobranca on dados_cobranca.id = faturamentos.dados_cobranca_id
                                              LEFT JOIN associados_cards on associados_cards.id = dados_cobranca.associados_card_id
                                            WHERE
                                              faturamentos.id = '" . $FRM_parcela_id . "' AND dados_cobranca.status = 1");

$dtVencParcela = new ActiveRecord\DateTime($QueryDadosCob[0]->dt_vencimento);
$dtreferencia = new ActiveRecord\DateTime($QueryDadosCob[0]->referencia);

$vlr = $QueryDadosCob[0]->valor;

$reference_id = tool::LimpaString($QueryDadosCob[0]->dt_vencimento);

require_once "../classes/FuturaApi/paymentTransationCard.php";

$sendCreateTransation = (new paymentTransationCard())
    ->setToken(TOKEN_API_COB)
    ->setCompany(COMPANY_ID_API_COB)
    ->setAmoutTransation("{$vlr}")
    ->setInstallments()
    ->setDescriptionTransation("Mensalidade Cartao Mais Saúde")
    ->setDetailsCardTransation(
        "",
        0,
        0,
        0,
        0)
    ->setReferenceIdTransation("{$dtreferencia->format('Ymd')}")
    ->setApiCobClienteId("{$QueryDadosCob[0]->api_cob_cliente_id}")
    ->setApiCobCardId("{$QueryDadosCob[0]->associados_card_id}")
    ->setMovimentoTransation(
        "{$dtVencParcela->format('Y-m-d')}",
        "{$QueryDadosCob[0]->faturamento_id}",
        "{$QueryDadosCob[0]->dt_venc_p}"
    )->setDetailsClientTransation(
        "{$dadosAssociado[0]->nm_associado}",
        "{$dtNasc->format('Y-m-d')}",
        "{$dadosAssociado[0]->email}",
        "{$dadosAssociado[0]->cpf}",
        "{$dadosAssociado[0]->fone_cel}",
        "{$dadosAssociado[0]->cep}",
        "{$end}",
        "{$compl}",
        "{$dadosAssociado[0]->nm_bairro}",
        "{$dadosAssociado[0]->nm_cidade}",
        "{$dadosAssociado[0]->nm_estado}")
    ->addTransationCorrence();

$returnSendCreateTransation = json_decode($sendCreateTransation);

if ($returnSendCreateTransation->error == "true") {
    var_dump($sendCreateTransation);
} else {
    var_dump($sendCreateTransation);
    // tudo ok grava os dados na tabela
}
