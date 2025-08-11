<?php
$Frm_cad = true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once "../../../sessao.php";
require_once("../../../conexao.php");
$cfg->set_model_directory('../../../models/');

// valida os campos do formulario para impedir ataques com rename de campos do form
$FRM_dados_cobranca_id = isset($_POST['dados_cobranca_id']) ? $_POST['dados_cobranca_id'] : tool::msg_erros("Campo Obrigatorio 1 faltando");
$FRM_matricula = isset($_POST['dados_cobranca_matricula']) ? $_POST['dados_cobranca_matricula'] : tool::msg_erros("Campo Obrigatorio 2 faltando");
$FRM_f_cobrança = isset($_POST['formadecobranca']) ? $_POST['formadecobranca'] : tool::msg_erros("Campo Obrigatorio 3 faltando");
$FRM_f_cob_conf = isset($_POST['confirm_forma_cobranca']) ? $_POST['confirm_forma_cobranca'] : tool::msg_erros("Campo Obrigatorio 4 faltando");
$FRM_f_cob_sys = isset($_POST['forma_cobranca_sys_id']) ? $_POST['forma_cobranca_sys_id'] : tool::msg_erros("Campo Obrigatorio 5 faltando");
$FRM_planos_id = isset($_POST['planos_id']) ? $_POST['planos_id'] : tool::msg_erros("Campo Obrigatorio 6 faltando");
$FRM_dia_vencimento = isset($_POST['dt_venc_p']) ? $_POST['dt_venc_p'] : tool::msg_erros("Campo Obrigatorio 7 faltando");
$FRM_tx_adesao = isset($_POST['tx_adesao']) ? $_POST['tx_adesao'] : 0;

// conta luz
$FRM_luz_identificador = isset($_POST['luz_identificador']) ? $_POST['luz_identificador'] : tool::msg_erros("Campo Obrigatorio 8 faltando");
$FRM_luz_num_cliente = isset($_POST['luz_num_cliente']) ? $_POST['luz_num_cliente'] : tool::msg_erros("Campo Obrigatorio 9 faltando");

// conta de agua
$FRM_matricula_ca = isset($_POST['matricula_ca']) ? $_POST['matricula_ca'] : tool::msg_erros("Campo Obrigatorio 10 faltando");
$FRM_identificador_ca = isset($_POST['identificador_ca']) ? $_POST['identificador_ca'] : tool::msg_erros("Campo Obrigatorio 11 faltando");

// cartão de credito
$FRM_associados_card_id = isset($_POST['associados_card_id']) ? $_POST['associados_card_id'] : tool::msg_erros("Campo Obrigatorio 14-1 faltando");

//debito em conta
$FRM_banco_dc = isset($_POST['banco_dc']) ? $_POST['banco_dc'] : tool::msg_erros("Campo Obrigatorio 15 faltando");
$FRM_agencia_dc = isset($_POST['agencia_dc']) ? $_POST['agencia_dc'] : tool::msg_erros("Campo Obrigatorio 16 faltando");
$FRM_operacao_dc = isset($_POST['operacao_dc']) ? $_POST['operacao_dc'] : tool::msg_erros("Campo Obrigatorio 17 faltando");
$FRM_conta_dc = isset($_POST['conta_dc']) ? $_POST['conta_dc'] : tool::msg_erros("Campo Obrigatorio 18 faltando");

// validações
if ($FRM_f_cobrança == "" or $FRM_f_cobrança == "0") {
    echo tool::msg_erros("Informe uma forma de cobrança.");
}
if ($FRM_planos_id == "" or $FRM_planos_id == "0") {
    echo tool::msg_erros("Selecione um plano.");
}
if ($FRM_dia_vencimento == "" or $FRM_dia_vencimento == "0") {
    echo tool::msg_erros("Selecione o dia de vencimento.");
}


// recupera dados do associado
$Query_Associado = associados::find_by_sql("SELECT
	associados.convenios_id,
	convenios.tx_adesao
	FROM
	associados
	INNER JOIN convenios ON convenios.id = associados.convenios_id
	WHERE
	associados.matricula = '" . $FRM_matricula . "'");

if (!$Query_Associado) {
    echo tool::msg_erros("Erro ao selecionar dados do associado ");
}    // valida o select ta tabela associados com join na convenio

$Query_Plano = planos::find($FRM_planos_id);// recupera os dados do plano selecionado { COD 44 }

if (!$Query_Plano) {
    echo tool::msg_erros("Erro ao selecionar dados do plano -> 44");
}    // valida o select na tabela planos

$FRM_separa_f_cob = explode("-", $FRM_f_cobrança);

$Query_formas = formas_cobranca::find($FRM_separa_f_cob[0]);// recupera os dados do plano selecionado

if (!$Query_formas) {
    echo tool::msg_erros("Erro ao selecionar dados da forma de cobrança -> 65");
}

// se existe começa a verificar o que tem que ser feito
if ($FRM_dados_cobranca_id != "" or $FRM_dados_cobranca_id > 0) {

    // confirma se a forma de cobrança é mesma ja cadastrada ou se é para alterar
    if ($FRM_f_cob_conf != $FRM_f_cobrança) {

        // atualiza a forma de cobrança e os dados
        $Query_update_forma_cob = dados_cobranca::find($FRM_dados_cobranca_id);
        $Query_update_forma_cob->update_attributes(
            array(
                'forma_cobranca_id' => $FRM_separa_f_cob[0],
                'formascobranca_sys_id' => $FRM_separa_f_cob[1],
                'dt_venc_p' => $FRM_dia_vencimento,
                'valor' => $Query_Plano->valor,
                'luz_identificador' => $FRM_luz_identificador,
                'luz_num_cliente' => $FRM_luz_num_cliente,
                'matricula_ca' => $FRM_matricula_ca,
                'identificador_ca' => $FRM_identificador_ca,
                'associados_card_id' => $FRM_associados_card_id,
                'banco_dc' => $FRM_banco_dc,
                'agencia_dc' => $FRM_agencia_dc,
                'operacao_dc' => $FRM_operacao_dc,
                'conta_dc' => $FRM_conta_dc,
                'usuarios_id' => $COB_Usuario_Id,
                'empresas_id' => $COB_Empresa_Id,
                'planos_id' => $FRM_planos_id
            ));


        if (!$Query_update_forma_cob) {
            echo tool::msg_erros("A atualização da forma de cobrança do associado não foi completada.");
        } // valida a atualização do dados de cobrança
        else {
            $str = '":"","msg":"Atualização de dados Concluida ","matricula":"' . $FRM_matricula . '","convenio":"' . $Query_Associado[0]->convenios_id . '","status":"success';
            echo $str;
        }

        // se a forma de cobrança for a mesma é for para atualizar so plano ou outros dados
    } else {

        // atualiza os dados de cobrança
        $Query_update_dados_cob = dados_cobranca::find($FRM_dados_cobranca_id);
        $Query_update_dados_cob->update_attributes(
            array(
                'dt_venc_p' => $FRM_dia_vencimento,
                'valor' => $Query_Plano->valor,
                'luz_identificador' => $FRM_luz_identificador,
                'luz_num_cliente' => $FRM_luz_num_cliente,
                'matricula_ca' => $FRM_matricula_ca,
                'identificador_ca' => $FRM_identificador_ca,
                'associados_card_id' => $FRM_associados_card_id,
                'banco_dc' => $FRM_banco_dc,
                'agencia_dc' => $FRM_agencia_dc,
                'operacao_dc' => $FRM_operacao_dc,
                'conta_dc' => $FRM_conta_dc,
                'usuarios_id' => $COB_Usuario_Id,
                'planos_id' => $FRM_planos_id
            ));

        if (!$Query_update_dados_cob) {
            echo tool::msg_erros("A atualização dos dados de cobrança do associado não foi completada.");
        } // valida a atualização do dados de cobrança
        else {
            $str = '":"","msg":"Atualização de dados Concluida ","matricula":"' . $FRM_matricula . '","convenio":"' . $Query_Associado[0]->convenios_id . '","status":"success';
            echo $str;
        }
    }

// não existe então vamos criar
} else {

    // cria o dado de cobrança
    $create_dados_cobranca = dados_cobranca::create(
        array(
            'status' => 1,
            'matricula' => $FRM_matricula,
            'dt_venc_p' => $FRM_dia_vencimento,
            'valor' => $Query_Plano->valor,
            'luz_identificador' => $FRM_luz_identificador,
            'luz_num_cliente' => $FRM_luz_num_cliente,
            'matricula_ca' => $FRM_matricula_ca,
            'identificador_ca' => $FRM_identificador_ca,
            'banco_dc' => $FRM_banco_dc,
            'agencia_dc' => $FRM_agencia_dc,
            'operacao_dc' => $FRM_operacao_dc,
            'conta_dc' => $FRM_conta_dc,
            'forma_cobranca_id' => $FRM_separa_f_cob[0],
            'formascobranca_sys_id' => $FRM_separa_f_cob[1],
            'usuarios_id' => $COB_Usuario_Id,
            'usuarios_id' => $COB_Empresa_Id,
            'planos_id' => $FRM_planos_id
        ));

    $last_dados_cobranca = dados_cobranca::find("last");                                                            // id do dado de cobrança gerado para o associado
    if (!$create_dados_cobranca) {
        echo tool::msg_erros("Erro ao criar dados de cobrança para o associado");
    }            // valida a criação do dado de cobrança


    //verificamos se existe taxa de adesão para o convenio
    if ($FRM_tx_adesao > 0) {

        // cria a taxa de adesao no faturamento { COD 47 }
        $create_adesao = faturamentos::create(
            array('matricula' => $FRM_matricula,                    // matricula do associado
                'status' => "0",                                // status em aberto
                'tipo_parcela' => "A",                                // tipo da parcela
                'referencia' => date("Y-m-d"),                    // a referencia é mes e ano
                'dt_vencimento' => date("Y-m-d"),                    // a data de vencimento é o dia do cadastro
                'valor' => $Query_Associado[0]->tx_adesao,    //valor da taxa de adesao
                'usuarios_id' => $COB_Usuario_Id,
                'empresas_id' => $COB_Empresa_Id,
                'dados_cobranca_id' => $last_dados_cobranca->id,
                'flag_pago' => 'FATURADO',
                'convenios_id' => $Query_Associado[0]->convenios_id
            ));

        if (!$create_adesao) {
            echo tool::msg_erros("Erro ao criar adesão para o associado ");
        }    // valida a criação do dado de cobrança
    }

    // criamos por padrao as 12 primeiras parcelas do contrato
    $Tparcelas = 12;

    //GERA A DATA DE VENCIMENTO
    $mes = date("m");
    $dia = $last_dados_cobranca->dt_venc_p;
    $ano = date("Y");

    //CRIA O LAÇO DE REPETIÇÃO PARA GERAR A PARCELA

    for ($i = 1; $i <= $Tparcelas; $i++) {

        $datavenc = mktime(0, 0, 0, $mes + $i, $dia, $ano);

        $vencimento = date('Y-m-d', $datavenc) . ' 00:00:00';

        $referencia_parcela = date('Y-m', $datavenc) . '-01';

        // cria as parcelas no faturamento { COD 48 }
        $ceate_parcelas = faturamentos::create(
            array('matricula' => $FRM_matricula,
                'dt_vencimento' => $vencimento,
                'referencia' => $referencia_parcela,
                'valor' => $last_dados_cobranca->valor,
                'dados_cobranca_id' => $last_dados_cobranca->id,
                'tipo_parcela' => 'M',
                'usuarios_id' => $COB_Usuario_Id,
                'empresas_id' => $COB_Empresa_Id,
                'flag_pago' => 'FATURADO',
                'convenios_id' => $Query_Associado[0]->convenios_id,
                'status' => '0'
            ));

    }

    if (!$ceate_parcelas) {
        echo tool::msg_erros("Erro ao criar faturamento para o associado");
    } else {
        $str = '":"","msg":"Dados e Faturamentos Concluidos","matricula":"' . $FRM_matricula . '","convenio":"' . $Query_Associado[0]->convenios_id . '","status":"success';
        echo $str;
    }    // valida a criação do dado de cobrança
}
?>
