<?php


$Frm_cad = true;// fala pra sessão não encerra pois é uma janela de cadastro

require_once "../sessao.php";
require_once("../conexao.php");
require_once("../config_ini.php");
$cfg->set_model_directory('../models/');

$Query_dados_cobranca = dados_cobranca::find_by_sql("SELECT * FROM dados_cobranca WHERE id='1664'");



$dadosassociado = associados::find_by_sql("SELECT SQL_CACHE 
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
                                                  associados.matricula = '" . $Query_dados_cobranca[0]->matricula . "'");


$end = $dadosassociado[0]->complemento . " " . $dadosassociado[0]->nm_logradouro . ", " . $dadosassociado[0]->num;
$compl = "";

$fullName = explode(" ", $dadosassociado[0]->nm_associado);
$name = $fullName[0];
$lastName = str_replace("$name", "", $dadosassociado[0]->nm_associado);

$dt_nasc = new ActiveRecord\DateTime($dadosassociado[0]->dt_nascimento);

//fone_cel
//fone_fixo
//fone_trabalho


require_once "../classes/FuturaApi/paymentBuyer.php";


$sendCreateBuyer = (new paymentBuyer())
    ->setToken(TOKEN_API_COB)
    ->setCompany(COMPANY_ID_API_COB)
    ->createClient("{$Query_dados_cobranca[0]->matricula}",
        "{$end}",
        "{$compl}",
        "{$dadosassociado[0]->nm_bairro}",
        "{$dadosassociado[0]->nm_cidade}",
        "{$dadosassociado[0]->nm_estado}",
        "{$dadosassociado[0]->cep}",
        "{$name}",
        "{$lastName}",
        "{$dadosassociado[0]->email}",
        "{$dadosassociado[0]->fone_cel}",
        "{$dadosassociado[0]->cpf}",
        "{$dt_nasc->format('Y-m-d')}",
        "Associado Cob Barbacena Matricula: " . $Query_dados_cobranca[0]->matricula . "");


echo $sendCreateBuyer;
$resultSendCreateBuyer = json_decode($sendCreateBuyer);

var_dump($resultSendCreateBuyer);
