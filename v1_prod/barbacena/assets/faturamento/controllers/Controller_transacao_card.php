<?php
$Frm_cad = true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once "../../../sessao.php";
require_once("../../../conexao.php");
require_once("../../../config_ini.php");
$cfg->set_model_directory('../../../models/');

$FRM_matricula = $_POST['mat'];

$FRM_nm_cli = $_POST['nm_cli'];
$FRM_numero_cc = $_POST['n_cc'];
$FRM_vm = $_POST['vm'];
$FRM_vy = $_POST['vy'];
$FRM_cod_seg_cc = $_POST['c_s_cc'];

$FRM_vlr_cob = tool::limpaMoney($_POST['vlcob']);

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

$dtVencParcela = date("Y-m-d");
$reference_id = date("Ymd");

require_once "../classes/FuturaApi/paymentTransationCard.php";

$sendCreateTransation = (new paymentTransationCard())
    ->setToken(TOKEN_API_COB)
    ->setCompany(COMPANY_ID_API_COB)
    ->setAmoutTransation("{$FRM_vlr_cob}")
    ->setInstallments()
    ->setDescriptionTransation("Mensalidades Cartao Mais Saúde Matricula {$FRM_matricula}")
    ->setDetailsCardTransation(
        "{$FRM_nm_cli}",
        "{$FRM_vm}",
        "{$FRM_vy}",
        "{$FRM_numero_cc}",
        "{$FRM_cod_seg_cc}")
    ->setReferenceIdTransation("{$reference_id}")
    ->setApiCobClienteId(0)
    ->setApiCobCardId(0)
    ->setMovimentoTransation(
        "{$dtVencParcela}",
        "0",
        "0"
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

}
