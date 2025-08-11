<?php

    /* carregamos bibliotecas externas */
    require_once("../config_ini.php");
    require_once("../conexao.php");
    $cfg->set_model_directory('../models/');
    
    $name = $_POST['nm_titular'];
    $doc = $_POST['doc_titular'];
    $mat = $_POST['matricula'];
    $searchFor = $_POST['search_for'];



    if($_POST['search_for'] == 0){
    
         if((empty($doc) || !is_numeric($doc) ) && empty($name)){
            echo "Digite um valor para pesquisa";
            die();
        }
    
        $dadosassociado = associados::find_by_sql("SELECT count(matricula) as total,status,matricula  FROM  associados WHERE cpf = '" . $doc . "' OR  (nm_associado = '" . strtoupper($name) . "' OR nm_associado = '" . strtolower($name) . "')");
    
        $matriculaQuery = intval($dadosassociado[0]->matricula);

    }else{
    
        if(empty($mat)){
            echo "Digite uma matricula para pesquisa";
            die();
        }
    
        $dadosassociado = associados::find_by_sql("SELECT count(matricula) as total,status,matricula  FROM  associados WHERE matricula = '" . $mat . "'");

        $matriculaQuery = $mat;
    
    }

    if ($dadosassociado[0]->total == 0) {
        echo 'Associado não encontrado.';
        die(); 
    }

    if($dadosassociado[0]->status == 0){
        echo 'Este Associado se encontra com contrato inativo em nosso sistema.<br>Ele não esta autorizado a utilizar nossos serviços.';
        die();
    }

   
    $dadosfaturamento = faturamentos::find_by_sql("SELECT count(id) as total FROM faturamentos  WHERE matricula = '".$matriculaQuery."' AND dt_vencimento < '".date("Y-m-d")."' AND status = 0 ");
    
    if ($dadosfaturamento[0]->total > 0) {
        echo 'Este Associado não esta autorizado a utilizar nossos serviços. <br>Favor direciona-lo para nossa central de atendimento!';
        die();
    }

        if($dadosassociado[0]->total > 0 && $dadosfaturamento[0]->total  == 0){
            echo 1;
        die();
        }

    $dadosdependente = dependentes::find_by_sql("SELECT 
       count(dependentes.id) as total,
       dependentes.id,
       dependentes.nome,
       dependentes.status as dep_status,
       associados.status as assoc_status 
    FROM  
        dependentes 
         INNER JOIN associados ON associados.matricula = dependentes.matricula 
    WHERE dependentes.cpf = '" . $doc . "' AND  (dependentes.nome = '" . strtolower($name) . "' OR dependentes.nome = '" . strtoupper($name) . "')");


    if ($dadosdependente[0]->total == 0) {
        echo 'Associado não encontrado.';
        die(); 
    }
    

    if($dadosdependente[0]->dep_status == 0){
        echo 'Este dependente não esta autorizado a utilizar nossos serviços.';
        die();
    }else if($dadosdependente[0]->assoc_status == 0){
        echo 'Este Associado não esta autorizado a utilizar nossos serviços.';
        die();
    }else{
        echo 1;
        die();
    }




?>