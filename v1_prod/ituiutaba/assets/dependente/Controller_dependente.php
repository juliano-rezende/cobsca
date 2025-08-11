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

    $FRM_id = isset($_POST['Dep_id'])     ? strtoupper($_POST['Dep_id'])           : tool::msg_erros("Dependente invalido.");

    $update = dependentes::find($FRM_id);
    $update->update_attributes(array('status' => "0"));

    if (!$update) {
        echo 'Erro ao desativar dependente';
    } else {
        echo $update->matricula;
    }

// reativa
} elseif ($acao == 'enabled') {

    $FRM_id = isset($_POST['Dep_id'])     ? strtoupper($_POST['Dep_id'])           : tool::msg_erros("Dependente invalido.");


    $update = dependentes::find($FRM_id);
    $update->update_attributes(array('status' => "1"));

    if (!$update) {
        echo 'Erro ao desativar dependente';
    } else {
        echo $update->matricula;
    }

//remove o dependente
} elseif ($acao == 'remove') {

    $FRM_id = isset($_POST['Dep_id'])     ? strtoupper($_POST['Dep_id'])           : tool::msg_erros("Dependente invalido.");

    $remove = dependentes::find($FRM_id);
    $remove->delete();

    if (!$remove) {
        echo 'Erro ao remover dependente';
    } else {
        echo $remove->matricula;
    }

// grava uma nova
} elseif ($acao == 'save') {

// verificações de variaveis

    $FRM_matricula = isset($_POST['Dep_Matricula'])     ? strtoupper($_POST['Dep_Matricula'])           : tool::msg_erros("Matricula não encontrada.");
    $FRM_nm_dependente = isset($_POST['Dep_nome'])      ? strtoupper($_POST['Dep_nome'])                : tool::msg_erros("Nome de dependente incorreto.");
    $FRM_data_nasc = isset($_POST['Dep_data_nasc'])     ? tool::LimpaString($_POST['Dep_data_nasc'])    : tool::msg_erros("Data de nascimento invalida.");
    $FRM_cpf = isset($_POST['Dep_cpf'])                 ? tool::LimpaString($_POST['Dep_cpf'])          : tool::msg_erros("Cpf invalido.");
    $FRM_rg = isset($_POST['Dep_rg'])                   ? strtoupper($_POST['Dep_rg'])                  : tool::msg_erros("RG invalido.");
    $FRM_cn = isset($_POST['dep_cn'])                   ? strtoupper($_POST['dep_cn'])                  : tool::msg_erros("Certidão invalida.");
    $FRM_parentesco = isset($_POST['Dep_Parentesco'])   ? strtoupper($_POST['Dep_Parentesco'])          : tool::msg_erros("Parentesco invalido.");
    $FRM_data_cadastro = date("Y-m-d");

    $Query_Sequencia = dependentes::find_by_sql("SELECT MAX(sequencia) as sequencia FROM dependentes WHERE matricula='" . $FRM_matricula . "' AND status= 1");

    $FRM_sequencia = ($Query_Sequencia[0]->sequencia + 1);

    if ($FRM_sequencia >= 8) {
        tool::msg_erros("Limite de Dependentes Atingido");
    }

    $datanasc = tool::InvertDateTime($FRM_data_nasc, 0);

    if (!checkdate(substr($FRM_data_nasc, 2, 2), substr($FRM_data_nasc, 0, 2), substr($FRM_data_nasc, 4, 4))                // se a data for inválida
        || substr($FRM_data_nasc, 4, 4) < 1900                                                                                // ou o ano menor que 1900
        || mktime(0, 0, 0, substr($FRM_data_nasc, 5, 2), substr($FRM_data_nasc, 0, 2), substr($FRM_data_nasc, 4, 4)) > time())    // ou a data passar de hoje
    {
        tool::msg_erros("Data de Nascimento Invalida!","matricula");

    }

    if ($FRM_cpf != "") {
        $cpf_cnpj = new ValidaCPFCNPJ($FRM_cpf);
        if (!$cpf_cnpj->valida()) {
            sleep(1);
            tool::msg_erros("Cpf Invalido");
        }

    } elseif ($FRM_rg == "") {

        if ($FRM_cn == "" or $FRM_cn == "0") {
            tool::msg_erros("Os Campos Documentos não pode Ficar em Branco");
        }

    }

    if ($FRM_parentesco == "" or $FRM_parentesco == "0") {
        tool::msg_erros("Grau de Parentesco Invalido");
    }


    $create = dependentes::create(
        array(
            'matricula' => $FRM_matricula,
            'sequencia' => $FRM_sequencia,
            'nome' => $FRM_nm_dependente,
            'cpf' => $FRM_cpf,
            'rg' => $FRM_rg,
            'cn' => $FRM_cn,
            'dt_nascimento' => tool::InvertDateTime($FRM_data_nasc, 0),
            'dt_cadastro' => $FRM_data_cadastro,
            'parentescos_id' => $FRM_parentesco,
            'usuarios_id' => $COB_Usuario_Id
        ));

    $last=dependentes::find("last");//recupera o ultimo id

    if (!$create) {
        echo 'Erro ao adicionar dependente';
    } else {
        echo tool::CompletaZeros("10",$last->matricula);
    }


// editar
} elseif ($acao == 'edit') {


// verificações de variaveis

    $FRM_id = isset($_POST['Dep_id'])                   ? strtoupper($_POST['Dep_id'])                  : tool::msg_erros("Dependente não localizado.");
    $FRM_matricula = isset($_POST['Dep_Matricula'])     ? strtoupper($_POST['Dep_Matricula'])           : tool::msg_erros("Matricula não encontrada.");
    $FRM_nm_dependente = isset($_POST['Dep_nome'])      ? strtoupper($_POST['Dep_nome'])                : tool::msg_erros("Nome de dependente incorreto.");
    $FRM_data_nasc = isset($_POST['Dep_data_nasc'])     ? tool::LimpaString($_POST['Dep_data_nasc'])    : tool::msg_erros("Data de nascimento invalida.");
    $FRM_cpf = isset($_POST['Dep_cpf'])                 ? tool::LimpaString($_POST['Dep_cpf'])          : tool::msg_erros("Cpf invalido.");
    $FRM_rg = isset($_POST['Dep_rg'])                   ? strtoupper($_POST['Dep_rg'])                  : tool::msg_erros("RG invalido.");
    $FRM_cn = isset($_POST['dep_cn'])                   ? strtoupper($_POST['dep_cn'])                  : tool::msg_erros("Certidão invalida.");
    $FRM_parentesco = isset($_POST['Dep_Parentesco'])   ? strtoupper($_POST['Dep_Parentesco'])          : tool::msg_erros("Parentesco invalido.");

    $queryFind = dependentes::find($FRM_id);


//VALIDA A DATA DE NASC
    $datanasc = tool::InvertDateTime($FRM_data_nasc, 0);

    if (!checkdate(substr($FRM_data_nasc, 2, 2), substr($FRM_data_nasc, 0, 2), substr($FRM_data_nasc, 4, 4))                // se a data for inválida
        || substr($FRM_data_nasc, 4, 4) < 1900                                                                                // ou o ano menor que 1900
        || mktime(0, 0, 0, substr($FRM_data_nasc, 5, 2), substr($FRM_data_nasc, 0, 2), substr($FRM_data_nasc, 4, 4)) > time())    // ou a data passar de hoje
    {
        tool::msg_erros("Data de Nascimento Invalida!","matricula");

    }


// VALIDAÇÃO DO CPF

    if ($FRM_cpf != "") {

        $cpf_cnpj = new ValidaCPFCNPJ($FRM_cpf);
        if (!$cpf_cnpj->valida()) {
            sleep(1);
            tool::msg_erros("Cpf Invalido");
        }

// valida rg
    } elseif ($FRM_rg == "") {

// valida certidão de nascimento
        if ($FRM_cn == "" or $FRM_cn == "0") {
            tool::msg_erros("Os Campos Documentos não pode Ficar em Branco");
        }

    }

//VALIDA O PARENTESCO
    if ($FRM_parentesco == "" or $FRM_parentesco == "0") {
        tool::msg_erros("Grau de Parentesco Invalido");
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
        echo 'Erro ao editar dependente';
    } else {
        echo tool::CompletaZeros("10",$FRM_matricula
        );
    }

}
?>