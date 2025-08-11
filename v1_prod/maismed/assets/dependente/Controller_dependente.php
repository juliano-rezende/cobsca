<?php
require_once "../../sessao.php";
require_once("../../conexao.php");
$cfg->set_model_directory('../../models/');

//classe de validação de cnpj e cpf
require_once("../../classes/valid_cpf_cnpj.php");
/*
COGIDOS DE ERROS E DESCRIÇÃO

DEP01 -> INDICA QUE HOUVER ALTERAÇÕES EM CAMPOS NO FORMULARIO POSSIVEL ATAQUE AO SISTEMA AO DESABILITAR DEPENDENTE
DEP02 -> INDICA QUE HOUVER ALTERAÇÕES EM CAMPOS NO FORMULARIO POSSIVEL ATAQUE AO SISTEMA AO HABILITAR DEPENDENTE
DEP03 -> INDICA QUE HOUVER ALTERAÇÕES EM CAMPOS NO FORMULARIO POSSIVEL ATAQUE AO SISTEMA AO REMOVER DEPENDENTE
DEP04 -> INDICA QUE HOUVER ALTERAÇÕES EM CAMPOS NO FORMULARIO POSSIVEL ATAQUE AO SISTEMA AO ADIÇÃO DEPENDENTE
DEP05 -> INDICA QUE HOUVER ALTERAÇÕES EM CAMPOS NO FORMULARIO POSSIVEL ATAQUE AO SISTEMA AO EDIÇÃO DEPENDENTE

*/

$acao = $_POST['acao'];//varivel de validação

//desativar
if ($acao == 'disabled') {

    $FRM_id = isset($_POST['Dep_id']) ? $_POST['Dep_id'] :
        $erro_int = '":"","callback":"1","msg":"Erro "DEP01" de Interno Contate o suporte!","matricula":"","status":"Danger';

    // verifica se a injeção de dados no formulario tais como alteração do nome do campo
    if ($erro_int) {
        echo $erro_int;
        exit();
    }

    $update = dependentes::find($FRM_id);
    $update->update_attributes(array('status' => "0"));

    // VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
    if (!$update) {
        echo '":"","callback":"1","msg":"Erro ao Desativar Dependente!","matricula":"' . $update->matricula . '","status":"warning';
    } else {
        echo '":"","callback":"0","msg":"Dependente Desativado!","matricula":"' . $update->matricula . '","status":"success';
    }

// reativa
} elseif ($acao == 'enabled') {

    $FRM_id = isset($_POST['Dep_id']) ? $_POST['Dep_id'] :
        $erro = '":"","callback":"1","msg":"Erro "DEP02" de Interno Contate o suporte!","matricula":"","status":"Danger';

    // verifica se a injeção de dados no formulario tais como alteração do nome do campo
    if ($erro) {
        echo $erro;
        exit();
    }

    $update = dependentes::find($FRM_id);
    $update->update_attributes(array('status' => "1"));

    // VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
    if (!$update) {
        echo '":"","callback":"1","msg":"Erro ao Desativar Dependente!","matricula":"' . $update->matricula . '","status":"warning';
    } else {
        echo '":"","callback":"0","msg":"Dependente Desativado!","matricula":"' . $update->matricula . '","status":"success';
    }

//remove o dependente
} elseif ($acao == 'remove') {

    $FRM_id = isset($_POST['Dep_id']) ? $_POST['Dep_id'] :
        $erro = '":"","callback":"1","msg":"Erro "DEP03" de Interno Contate o suporte!","matricula":"","status":"Danger';

    // verifica se a injeção de dados no formulario tais como alteração do nome do campo
    if ($erro) {
        echo $erro;
        exit();
    }

    $remove = dependentes::find($FRM_id);
    $remove->delete();

    // VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
    if (!$remove) {
        echo '":"","callback":"1","msg":"Erro ao Desativar Dependente!","matricula":"' . $remove->matricula . '","status":"warning';
    } else {
        echo '":"","callback":"0","msg":"Dependente Desativado!","matricula":"' . $remove->matricula . '","status":"success';
    }

// grava uma nova
} elseif ($acao == 'save') {

    // verificações de variaveis
    $FRM_matricula = isset($_POST['Dep_Matricula']) ? $_POST['Dep_Matricula'] :
        $erro = '":"","callback":"1","msg":"Erro "DEP05-1" de Interno Contate o suporte!","matricula":"","status":"Danger';
    $FRM_nm_dependente = isset($_POST['Dep_nome']) ? $_POST['Dep_nome'] :
        $erro = '":"","callback":"1","msg":"Erro "DEP05-2" de Interno Contate o suporte!","matricula":"","status":"Danger';
    $FRM_data_nasc = isset($_POST['Dep_data_nasc']) ? tool::LimpaString($_POST['Dep_data_nasc']) :
        $erro = '":"","callback":"1","msg":"Erro "DEP05-3" de Interno Contate o suporte!","matricula":"","status":"Danger';
    $FRM_cpf = isset($_POST['Dep_cpf']) ? tool::LimpaString($_POST['Dep_cpf']) :
        $erro = '":"","callback":"1","msg":"Erro "DEP05-4" de Interno Contate o suporte!":"","status":"Danger';
    $FRM_rg = isset($_POST['Dep_rg']) ? $_POST['Dep_rg'] :
        $erro = '":"","callback":"1","msg":"Erro "DEP05-6" de Interno Contate o suporte!":"","status":"Danger';
    $FRM_cn = isset($_POST['dep_cn']) ? $_POST['dep_cn'] :
        $erro = '":"","callback":"1","msg":"Erro "DEP05-7" de Interno Contate o suporte!":"","status":"Danger';
    $FRM_parentesco = isset($_POST['Dep_Parentesco']) ? $_POST['Dep_Parentesco'] :
        $erro = '":"","callback":"1","msg":"Erro "DEP05-8" de Interno Contate o suporte!","matricula":"","status":"Danger';
    $FRM_data_cadastro = date("Y-m-d");
    $FRM_sequencia = 1;


    // VALIDA SE O ASSOCIADO POSSUI DADOS DE COBRANÇA
    $Query_Sequencia = dados_cobranca::find_by_sql("SELECT count(matricula) as total FROM dados_cobranca WHERE matricula='" . $FRM_matricula . "' AND status='1'");

// DEFINE A SEQUENCIA
    if ($Query_Sequencia[0]->total == 0) {
        echo '":"","callback":"2","msg":"<h1><i class=\"uk-icon-exclamation uk-text uk-text-danger\"></i> Opsssss!</h1><br>Para realizar o cadastro de dependentes primeiro é necessário cadastrar o dados de cobrança para o associado.","matricula":"' . $FRM_matricula . '","status":"danger';
        die;
    }

    // VERIFICA DUPLICIDADE DE CPF
    $Query_Sequencia = dependentes::find_by_sql("SELECT MAX((sequencia)+1) as sequencia FROM dependentes WHERE matricula='" . $FRM_matricula . "' AND status='1'");

    // DEFINE A SEQUENCIA
    if (empty($Query_Sequencia[0]->sequencia)) {
        $FRM_sequencia = 2;
    } else {
        $FRM_sequencia = $Query_Sequencia[0]->sequencia;
    }

//    echo '":"","callback":"1","msg":"Erro ao adcionar novo dependente '.$FRM_sequencia.'","matricula":"' . $FRM_matricula . '","status":"warning';
//    die;

    //VERIFICA O LIMITE DE DEPENDENTES
    if ($FRM_sequencia == 7) {
        $erro = '":"","callback":"1","msg":"Limite de Dependentes Atingido!","matricula":"' . $FRM_matricula . '","status":"warning';
    }

    //VALIDA A DATA DE NASC
    $datanasc = tool::InvertDateTime($FRM_data_nasc, 0);

    if (!checkdate(substr($FRM_data_nasc, 2, 2), substr($FRM_data_nasc, 0, 2), substr($FRM_data_nasc, 4, 4))                // se a data for inválida
        || substr($FRM_data_nasc, 4, 4) < 1900                                                                                // ou o ano menor que 1900
        || mktime(0, 0, 0, substr($FRM_data_nasc, 5, 2), substr($FRM_data_nasc, 0, 2), substr($FRM_data_nasc, 4, 4)) > time())    // ou a data passar de hoje
    {
        $erro = '":"","callback":"1","msg":"Data de Nascimento Invalida!","matricula":"' . $FRM_matricula . '","status":"warning';
    }

    // VALIDAÇÃO DO CPF
//    if ($FRM_cpf != "") {
//        $cpf_cnpj = new ValidaCPFCNPJ($FRM_cpf);
//        if (!$cpf_cnpj->valida()) {
//            sleep(1);
//            $erro = '":"","callback":"1","msg":"Cpf Invalido!","matricula":"' . $FRM_matricula . '","status":"danger';
//        }
//    // valida rg
//    } elseif ($FRM_rg == "") {
//        // valida certidão de nascimento
//        if ($FRM_cn == "" or $FRM_cn == "0") {
//            $erro = '":"","callback":"1","msg":"Os Campos Documentos não pode Ficar em Branco!","matricula":"' . $FRM_matricula . '","status":"warning';
//        }
//    }

    //VALIDA O PARENTESCO
    if ($FRM_parentesco == "" or $FRM_parentesco == "0") {
        $erro = '":"","callback":"1","msg":"Grau de Parentesco Invalido!","matricula":"' . $FRM_matricula . '","status":"warning';
    }

    // verifica se a injeção de dados no formulario tais como alteração do nome do campo
    if (isset($erro)) {
        echo $erro;
        exit();
    }

    $create = dependentes::create(
        array(
            'matricula' => $FRM_matricula,
            'sequencia' => $FRM_sequencia,
            'nome'      => $FRM_nm_dependente,
            'cpf'       => $FRM_cpf,
            'rg'        => $FRM_rg,
            'cn'        => $FRM_cn,
            'dt_nascimento' => tool::InvertDateTime($FRM_data_nasc, 0),
            'dt_cadastro' => $FRM_data_cadastro,
            'parentescos_id' => $FRM_parentesco,
            'usuarios_id' => $COB_Usuario_Id
        ));


    // VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
    if (!$create) {
        echo '":"","callback":"1","msg":"Erro ao adcionar novo dependente","matricula":"' . $FRM_matricula . '","status":"warning';
    } else {
        echo '":"","callback":"0","msg":"Dependente Adcionado!","matricula":"' . $FRM_matricula . '","status":"success';
    }

// editar
} elseif ($acao == 'edit') {

    // verificações de variaveis
    $FRM_id = isset($_POST['Dep_id']) ? $_POST['Dep_id'] :
        $erro = '":"","callback":"1","msg":"Erro "DEP04-1" de Interno Contate o suporte!","matricula":"","status":"Danger';
    $FRM_nm_dependente = isset($_POST['Dep_nome']) ? $_POST['Dep_nome'] :
        $erro = '":"","callback":"1","msg":"Erro "DEP04-2 de Interno Contate o suporte!","matricula":"","status":"Danger';
    $FRM_data_nasc = isset($_POST['Dep_data_nasc']) ? tool::LimpaString($_POST['Dep_data_nasc']) :
        $erro = '":"","callback":"1","msg":"Erro "DEP04-3" de Interno Contate o suporte!","matricula":"","status":"Danger';
    $FRM_cpf = isset($_POST['Dep_cpf']) ? tool::LimpaString($_POST['Dep_cpf']) :
        $erro = '":"","callback":"1","msg":"Erro "DEP04-5" de Interno Contate o suporte!":"","status":"Danger';
    $FRM_rg = isset($_POST['Dep_rg']) ? $_POST['Dep_rg'] :
        $erro = '":"","callback":"1","msg":"Erro "DEP04-6" de Interno Contate o suporte!":"","status":"Danger';
    $FRM_cn = isset($_POST['dep_cn']) ? $_POST['dep_cn'] :
        $erro = '":"","callback":"1","msg":"Erro "DEP04-7" de Interno Contate o suporte!":"","status":"Danger';
    $FRM_parentesco = isset($_POST['Dep_Parentesco']) ? $_POST['Dep_Parentesco'] :

    //VALIDA A DATA DE NASC
    $datanasc = tool::InvertDateTime($FRM_data_nasc, 0);

    if (!checkdate(substr($FRM_data_nasc, 2, 2), substr($FRM_data_nasc, 0, 2), substr($FRM_data_nasc, 4, 4))                // se a data for inválida
        || substr($FRM_data_nasc, 4, 4) < 1900                                                                                // ou o ano menor que 1900
        || mktime(0, 0, 0, substr($FRM_data_nasc, 5, 2), substr($FRM_data_nasc, 0, 2), substr($FRM_data_nasc, 4, 4)) > time())    // ou a data passar de hoje
    {
        $erro = '":"","callback":"1","msg":"Data de Nascimento Invalida!","matricula":"' . $FRM_matricula . '","status":"warning';

    }

    // VERIFICA DUPLICIDADE DE CPF
    $Query_Sequencia = dependentes::find_by_sql("SELECT MAX((sequencia)+1) as sequencia FROM dependentes WHERE matricula='" . $FRM_matricula . "' AND status='1'");

    // DEFINE A SEQUENCIA
    if (empty($Query_Sequencia[0]->sequencia)) {
        $FRM_sequencia = 2;
    } else {
        $FRM_sequencia = $Query_Sequencia[0]->sequencia;
    }

//    // VALIDAÇÃO DO CPF
//    if ($FRM_cpf != "") {
//
//        $cpf_cnpj = new ValidaCPFCNPJ($FRM_cpf);
//        if (!$cpf_cnpj->valida()) {
//            sleep(1);
//            $erro = '":"","callback":"1","msg":"Cpf Invalido!","matricula":"' . $FRM_matricula . '","status":"danger';
//        }
//
//    // valida rg
//    } elseif ($FRM_rg == "") {
//        // valida certidão de nascimento
//        if ($FRM_cn == "" or $FRM_cn == "0") {
//            $erro = '":"","callback":"1","msg":"Os Campos Documentos não pode Ficar em Branco!","matricula":"' . $FRM_matricula . '","status":"warning';
//        }
//    }

    //VALIDA O PARENTESCO
    if ($FRM_parentesco == "" or $FRM_parentesco == "0") {
        $erro = '":"","callback":"1","msg":"Grau de Parentesco Invalido!","matricula":"' . $FRM_matricula . '","status":"warning';
    }

    // verifica se a injeção de dados no formulario tais como alteração do nome do campo
    if (isset($erro)) {
        echo $erro;
        exit();
    }

    $query = dependentes::find($FRM_id);
    $query->update_attributes(
        array(
            'nome' => $FRM_nm_dependente,
            'cpf' => $FRM_cpf,
            'rg' => $FRM_rg,
            'cn' => $FRM_cn,
            'dt_nascimento' => tool::InvertDateTime($FRM_data_nasc, 0),
            'parentescos_id' => $FRM_parentesco,
            'usuarios_id' => $COB_Usuario_Id
        ));

    // VERIFICAMOS SE NÃO HOUVE ERROS E RETORNA A MSG
    if (!$query) {
        echo '":"","callback":"1","msg":"Erro ao Atualizar dependente","matricula":"' . $FRM_matricula . '","status":"warning';
    } else {
        echo '":"","callback":"0","msg":"Dependente Atualizado!","matricula":"' . $FRM_matricula . '","status":"success';
    }

}
?>