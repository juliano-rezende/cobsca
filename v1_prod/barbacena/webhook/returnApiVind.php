<?php
require_once("../conexao.php");
require_once("../config_ini.php");
$cfg->set_model_directory('../models/');



header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');
$response = array();
$response[0] = json_decode(file_get_contents('php://input'), true);

echo json_encode($response);

//
////altera os dados da parcela na tabela faturamento
//$Query_update = faturamentos::find($listfat->current()->fatid);
//$Query_update->update_attributes(
//    array(
//        'status'              => '1',
//        'tipo_baixa'          => "B",
//        'negociada'           => "N",
//        'dt_negociacao'       => "0000-00-00",
//        'dt_pagamento'        => date("Y-m-d"),
//        'valor_negociado'     => "0.00",
//        'valor_pago'          => "{$listfat->current()->valor}",
//        'acrescimos'          => "0.00",
//        'descontos'           => "0.00",
//        'flag_pago'           => "PAGA",
//        "apicob_transacao_id" => $returnApi->apicobtransacaoid,
//        "id_transacao"        => $pay->idtransacao,
//        "status_trasation"    => $pay->status,
//        "transaction_number"  => $pay->transaction_number,
//        "authorizer_id"       => $pay->authorizer_id,
//        "authorization_nsu"   => $pay->authorization_nsu,
//        "expected_on_credit"  => $pay->expected_on,
//        "contas_bancarias_id" => $Query_conta_deposito[ 0 ]->id,
//        'usuarios_id'         => 1
//    ));
//$update_dd = dados_cobranca::find($dados_cobranca_id);
//$Query_update->update_attributes(
//    array(
//        'dt_venc_cob' => $dt_venc_p
//    ));
//
//$create = caixa::create(array(
//    'historico'                => "Recebimento parcela " . $listfat->current()->fatid . "",
//    'data'                     => "{$pay->expected_on}",
//    'valor'                    => "{$listfat->current()->valor}",
//    'numdoc'                   => "{$pay->idtransacao}",
//    'tipolancamento'           => 1,
//    'tipo'                     => "c",
//    'formas_pagamentos_id'     => 0,
//    'formas_recebimentos_id'   => 3,
//    'contas_bancarias_id'      => "{$Query_conta_deposito[0]->id}",
//    'empresas_id'              => 1,
//    'usuarios_id'              => 1,
//    'clientes_fornecedores_id' => "0." . $listfat->current()->matricula,
//    // SERÁ ADIONADO O ZERO ANTES DA MATRICULA INDICANDO QUE É UM ASSOCIADO E NÃO UM CLIENTE OU FORNECEDOR
//    'planos_contas_id'         => "{$dados_config->planos_contas_id}",
//    'centros_custos_id'        => "{$dados_config->planos_contas_id}",
//    'detalhes'                 => "matricula " . $listfat->current()->matricula . ""
//));
