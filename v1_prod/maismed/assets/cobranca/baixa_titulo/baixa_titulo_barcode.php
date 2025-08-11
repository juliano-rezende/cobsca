<?php

$Frm_cad = true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once "../../../sessao.php";
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

// recupera as taxas configuradas na empresa
$dados_config = configs::find_by_empresas_id($COB_Empresa_Id);

// ids das parcelas selecionadas
$FRM_parcelas_id = isset($_POST['ids']) ? $_POST['ids'] : $erro = ('Campo pa_id está faltando ');
$FRM_juros_multa = $_POST['juros_multa'];
$FRM_f_receb_id = $_POST['f_receb'];


//	separa as parcelas
$FRM_pa = explode(",", $FRM_parcelas_id);
$FRM_t_p = count($FRM_pa);

$FRM_acrescimos = 0;
$FRM_descontos = 0;

foreach ($FRM_pa as $value) {

    $Query_update = faturamentos::find($value);

    $dt_venc = new ActiveRecord\DateTime($Query_update->dt_vencimento);
    $FRM_vl_nominal = $Query_update->valor;

    $jurosMulta = faturamentos::Calcula_Juros($FRM_vl_nominal, $dt_venc->format('Y-m-d'), $dados_config->juros, $dados_config->multa);
    $diferenca = $jurosMulta - $FRM_vl_nominal;

    if ($FRM_juros_multa == "S") {// cobrar juros e multa
        $FRM_vl_pago = $jurosMulta;
        $FRM_acrescimos = $diferenca;
        $FRM_neg = "N";/*negociada*/
    } else {
        $FRM_vl_pago = $FRM_vl_nominal;
        $FRM_descontos = $diferenca;
        $FRM_neg = "S";/*negociada*/
    }

    $Select_conta = "SELECT id FROM contas_bancarias WHERE empresas_id='" . $COB_Empresa_Id . "' AND tp_conta='0' AND status='1'";
    $Query_conta_deposito = contas_bancarias::find_by_sql($Select_conta);

    $Query_update->update_attributes(
        array(
            'status' => '1',
            'tipo_baixa' => "M",
            'negociada' => $FRM_neg,
            'dt_negociacao' => "0000-00-00",
            'dt_pagamento' => date("Y-m-d"),
            'valor_negociado' => $jurosMulta,
            'valor_pago' => $FRM_vl_pago,
            'acrescimos' => $FRM_acrescimos,
            'descontos' => $FRM_descontos,
            'flag_pago' => "PAGA",
            'contas_bancarias_id' => $Query_conta_deposito[0]->id,
            'usuarios_id' => $COB_Usuario_Id
        ));

    $create = caixa::create(array(
        'historico' => "Recebimento parcela " . $value . "",
        'data' => date("Y-m-d"),
        'valor' => $FRM_vl_pago,
        'numdoc' => "pid_" . $value,
        'tipolancamento' => 1,
        'tipo' => "c",
        'formas_pagamentos_id' => 0,
        'formas_recebimentos_id' => $FRM_f_receb_id,
        'contas_bancarias_id' => $Query_conta_deposito[0]->id,
        'empresas_id' => $COB_Empresa_Id,
        'usuarios_id' => $COB_Usuario_Id,
        'clientes_fornecedores_id' => "0." . $Query_update->matricula, // SERÁ ADIONADO O ZERO ANTES DA MATRICULA INDICANDO QUE É UM ASSOCIADO E NÃO UM CLIENTE OU FORNECEDOR
        'planos_contas_id' => $dados_config->planos_contas_id,
        'centros_custos_id' => $dados_config->planos_contas_id,
        'detalhes' => "matricula " . $Query_update->matricula . ""
    ));


    if(!$Query_update && !$create){
        echo '":"","callback":"1","msg":"Erro ao receber titulo favor entrar em contato com a central de suporte!!","status":"warning';
    } else {
        echo '":"","callback":"0","msg":"Recebimento realizado com sucesso!.","status":"success';
    }


}