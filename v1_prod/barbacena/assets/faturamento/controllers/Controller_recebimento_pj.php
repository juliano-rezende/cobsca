<?php
$Frm_cad = true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once "../../../sessao.php";
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');


$FRM_parcelas_id = isset($_POST['ids']) ? $_POST['ids']
    : tool::msg_erros("O Campo ids Obrigatorio faltando.");
$FRM_conta_bancaria_id = isset($_POST['conta_bancaria_id']) ? $_POST['conta_bancaria_id']
    : tool::msg_erros("O Campo conta_bancaria_id Obrigatorio faltando.");
$FRM_pagamento = isset($_POST['pagamento']) ? tool::InvertDateTime(tool::LimpaString($_POST['pagamento']), 0)
    : tool::msg_erros("O Campo vencimento Obrigatorio faltando.");
$FRM_f_receb_id = isset($_POST['f_receb_id']) ? $_POST['f_receb_id'] :
    $erro = ('Campo f_receb_id está faltando');
// id da forma de recebimento do sistema
$FRM_f_receb_sys_id = isset($_POST['f_receb_sys_id']) ? $_POST['f_receb_sys_id'] :
    $erro = ('Campo f_receb_sys_id está faltando');


// recupera as taxas configuradas na empresa
$dados_config = configs::find_by_empresas_id($COB_Empresa_Id);

// SEPARA AS PARCELAS DO ENVIADAS
$FRM_pa = explode(",", $FRM_parcelas_id);

// define a variavel de controle de erros
$query_errors = "";

// INICIA O LOOP DA PARCELAS
foreach ($FRM_pa as $value) {

// SELECIONA A PARCELA QUE SERÁ GERADA O TITULO
    $Query_faturamento = faturamentos::find($value);

    // verificamos se a parcela já não foi baixada
    if ($Query_faturamento->status == 1) {
        continue;
    }

    $Query_faturamento->update_attributes(
    array(
        'status'                => '1',
        'tipo_baixa'            => "M",
        'negociada'             => "N",
        'dt_negociacao'         => "0000-00-00",
        'dt_pagamento'          => $FRM_pagamento,
        'valor_negociado'       => "0.00",
        'valor_pago'            => $Query_faturamento->valor,
        'acrescimos'            => "0.00",
        'descontos'             => "0.00",
        'flag_pago'             => "PAGA",
        'contas_bancarias_id'   => $FRM_conta_bancaria_id,
        'usuarios_id'           => $COB_Usuario_Id
    ));

    if (!$Query_faturamento) {
        $query_errors .= ("<i class='uk-icon-exclamation-triangle  uk-text-warning' ></i> Não foi possivel atualizar a parcela " . $value . " houve algum erro verifique e reinicie o processo novamente. </br");
        echo '":"","callback":"1","msg":"' . $query_errors . '","status":"warning';
        break;
    }

    // cria o lançamento no caiza
    $create = caixa::create(
    array(
        'historico'         => "Recebimento parcela " . $value . "",
        'data'              => date("Y-m-d"),
        'valor'             => $Query_faturamento->valor,
        'numdoc'            => $Query_faturamento->matricula."-".$value,
        'tipolancamento'    => 1,
        'tipo'              => "c",
        'formas_pagamentos_id'      => 0,
        'formas_recebimentos_id'    => $FRM_f_receb_id,
        'contas_bancarias_id'       => $FRM_conta_bancaria_id,
        'empresas_id'               => $COB_Empresa_Id,
        'usuarios_id'               => $COB_Usuario_Id,
        'clientes_fornecedores_id'  => "0." . $Query_faturamento->matricula, // SERÁ ADIONADO O ZERO ANTES DA MATRICULA INDICANDO QUE É UM ASSOCIADO E NÃO UM CLIENTE OU FORNECEDOR
        'planos_contas_id'          => $dados_config->planos_contas_id,
        'centros_custos_id'         => $dados_config->centros_custos_id,
        'detalhes'                  => "matricula " . $Query_faturamento->matricula . ""
    ));

    if (!$Query_faturamento) {
        $query_errors .= ("<i class='uk-icon-exclamation-triangle  uk-text-warning' ></i> Erro ao criar lançamento no caixa parcela nº " . $value . ".</br");
        echo '":"","callback":"1","msg":"' . $query_errors . '","status":"warning';
        break;
    }

}/* FIM DO FOREACH */

echo '":"","callback":"0","msg":"Recebimento Concluido.","status":"success';

?>

