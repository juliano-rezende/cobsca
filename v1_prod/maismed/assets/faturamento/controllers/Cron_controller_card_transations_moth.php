<?php

$Frm_cad = true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once("../../../conexao.php");
require_once("../../../config_ini.php");
require_once "../../../classes/FuturaApi/paymentTransationCard.php";
$cfg->set_model_directory('../../../models/');

$ref = date("Y-m")."-01";
$dia = date('d');

// recupera as taxas configuradas na empresa
$dados_config = configs::find_by_empresas_id(1);
$Query_conta_deposito = contas_bancarias::find_by_sql("SELECT id FROM contas_bancarias WHERE empresas_id='1' AND tp_conta='0' AND status='1'");

$query_transations = "SELECT dd.id as iddadocobranca, dd.matricula, dd.associados_card_id, dd.valor, ac.number_card,
dd.dt_venc_p, dd.dt_venc_cob,dd.api_cob_cliente_id, ac.api_cob_card_id, fat.id as fatid, fat.dt_vencimento,
IFNULL((select count(status) from transacoes_cards tc
   where dd.matricula = tc.matricula AND  DATE_FORMAT(tc.dt_envio, '%Y%m') = '".date("Ym")."' and tc.status = 0 ),0)  as negadas
FROM dados_cobranca  as dd
INNER JOIN associados_cards ac ON ac.id = dd.associados_card_id
INNER JOIN faturamentos fat ON fat.matricula = dd.matricula
LEFT JOIN transacoes_cards tran ON tran.faturamentos_id = fat.id
WHERE
NOT EXISTS (SELECT id FROM transacoes_cards
WHERE faturamentos_id = fat.id AND dt_envio = '".date("Y-m-d")."' AND tran.card_number = ac.number_card )
AND dd.forma_cobranca_id = 4 AND fat.referencia = '".$ref."' AND fat.status = 0  AND dd.matricula != 533
and dd.dt_venc_cob =  '".$dia."'
GROUP BY dd.id, dd.matricula, dd.associados_card_id, dd.valor, ac.number_card, dd.api_cob_cliente_id,
ac.api_cob_card_id, fat.id , fat.dt_vencimento";

$Query_cob_cards = dados_cobranca::find_by_sql($query_transations);

$listfat= new ArrayIterator($Query_cob_cards);

while($listfat->valid()):

   $dados_cobranca_id = $listfat->current()->iddadocobranca;
   $dt_venc_p = $listfat->current()->dt_venc_p;
   $dt_venc_cob = $listfat->current()->dt_venc_cob;

    $sendCreateTransation = (new paymentTransationCard())
     ->setToken(TOKEN_API_COB)
     ->setCompany(COMPANY_ID_API_COB)
     ->setAmoutTransation("".$listfat->current()->valor."")
     ->setInstallments()
     ->setDescriptionTransation("Mensalidade Cartao Mais Saúde")
     ->setDetailsCardTransation(
         "",
         0,
         0,
         0,
         0)
     ->setReferenceIdTransation("".date("Ym")."01")
     ->setApiCobClienteId("{$listfat->current()->api_cob_cliente_id}")
     ->setApiCobCardId("{$listfat->current()->api_cob_card_id}")
     ->setMovimentoTransation(
         "{$listfat->current()->dt_vencimento}",
         "{$listfat->current()->fatid}",
         "{$listfat->current()->dt_venc_p}"
     )->setDetailsClientTransation(
         "",
         "",
         "",
         "",
         "",
         "",
         "",
         "",
         "",
         "",
         "")
     ->addTransationCorrence();

    $returnApi = json_decode($sendCreateTransation);
    $pay = $returnApi->payment_authorization;
    $ret = $returnApi->message;


    if ($returnApi->result == "success") {

        $new_transation = transacoes_cards::create(
           array(
             "matricula" => "{$listfat->current()->matricula}",
             "card_number" => "{$listfat->current()->number_card}",
             "faturamentos_id" => "{$listfat->current()->fatid}",
             "idtransacao" => $pay->idtransacao,
             "transaction_number" => $pay->transaction_number,
             "authorizer_id" => $pay->authorizer_id,
             "authorization_code" => $pay->authorization_code,
             "authorization_nsu" => $pay->authorization_nsu,
             "apicob_transacao_id" => $returnApi->apicobtransacaoid,
             "expected_on" => $pay->expected_on,
             "status" => 1,
             "obs" => "{$ret->retorno}",
             "dt_envio" => date("Y-m-d")
           ));

        if($dados_config->futura_api_ambiente == 1) {

            //altera os dados da parcela na tabela faturamento
            $Query_update = faturamentos::find($listfat->current()->fatid);
            $Query_update->update_attributes(
                array(
                    'status' => '1',
                    'tipo_baixa' => "B",
                    'negociada' => "N",
                    'dt_negociacao' => "0000-00-00",
                    'dt_pagamento' => date("Y-m-d"),
                    'valor_negociado' => "0.00",
                    'valor_pago' => "{$listfat->current()->valor}",
                    'acrescimos' => "0.00",
                    'descontos' => "0.00",
                    'flag_pago' => "PAGA",
                    "apicob_transacao_id" => $returnApi->apicobtransacaoid,
                    "id_transacao" => $pay->idtransacao,
                    "status_trasation" => $pay->status,
                    "transaction_number" => $pay->transaction_number,
                    "authorizer_id" => $pay->authorizer_id,
                    "authorization_nsu" => $pay->authorization_nsu,
                    "expected_on_credit" => $pay->expected_on,
                    "contas_bancarias_id" => $Query_conta_deposito[0]->id,
                    'usuarios_id' => 1
                ));
            $update_dd = dados_cobranca::find($dados_cobranca_id);
            $Query_update->update_attributes(
              array(
                 'dt_venc_cob' => $dt_venc_p
              ));

            $create = caixa::create(array(
                'historico' => "Recebimento parcela " . $listfat->current()->fatid . "",
                'data' => "{$pay->expected_on}",
                'valor' => "{$listfat->current()->valor}",
                'numdoc' => "{$pay->idtransacao}",
                'tipolancamento' => 1,
                'tipo' => "c",
                'formas_pagamentos_id' => 0,
                'formas_recebimentos_id' => 3,
                'contas_bancarias_id' => "{$Query_conta_deposito[0]->id}",
                'empresas_id' => 1,
                'usuarios_id' => 1,
                'clientes_fornecedores_id' => "0." . $listfat->current()->matricula, // SERÁ ADIONADO O ZERO ANTES DA MATRICULA INDICANDO QUE É UM ASSOCIADO E NÃO UM CLIENTE OU FORNECEDOR
                'planos_contas_id' => "{$dados_config->planos_contas_id}",
                'centros_custos_id' => "{$dados_config->planos_contas_id}",
                'detalhes' => "matricula " . $listfat->current()->matricula . ""
            ));

        }

    } else {

       $obs = $ret->cderror." ) ".$ret->retorno." - ".$ret->solucao;

       $novaDtaCobP = $dt_venc_cob+3;
       if($novaDtaCobP > 28){
          $dtCob = 28;
       }else{
          $dtCob = $novaDtaCobP;
       }

       $update_dd = dados_cobranca::find($dados_cobranca_id);
       $Query_update->update_attributes(
          array(
             'dt_venc_cob' => $dtCob
          ));
        
        $new_transation = transacoes_cards::create(
         array(
             "matricula" => "{$listfat->current()->matricula}",
             "card_number" => "{$listfat->current()->number_card}",
             "faturamentos_id" => "{$listfat->current()->fatid}",
             "status" => 0,
             "obs" => "{$obs}",
             "dt_envio" => date("Y-m-d")
         ));

    }

    $listfat->next();
endwhile;
